<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\StatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TechnicianController extends Controller
{
    /**
     * Display active jobs and completed job history.
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab') === 'history'
            ? 'history'
            : 'active';

        $query = Complaint::where('assigned_to', Auth::id())
            ->with([
                'user',
                'category',
                'statusLogs.updatedBy'
            ]);

        if ($tab === 'history') {
            $query->where('status', 'completed')
                  ->orderByDesc('resolved_at');
        } else {
            $query->whereIn('status', [
                'assigned',
                'in_progress'
            ])
            ->orderByRaw(
                "FIELD(status, 'in_progress', 'assigned')"
            )
            ->oldest();
        }

        $jobs = $query->paginate(10)->withQueryString();

        $counts = [
            'active' => Complaint::where(
                'assigned_to',
                Auth::id()
            )
            ->whereIn('status', [
                'assigned',
                'in_progress'
            ])
            ->count(),

            'history' => Complaint::where(
                'assigned_to',
                Auth::id()
            )
            ->where('status', 'completed')
            ->count(),
        ];

        return view(
            'technician.jobs.index',
            compact('jobs', 'tab', 'counts')
        );
    }

    /**
     * Start a maintenance job.
     * Status: assigned -> in_progress
     */
    public function start(Complaint $complaint)
    {
        $this->authoriseOwner($complaint);

        if ($complaint->status !== 'assigned') {
            return back()->withErrors([
                'status' => 'This job cannot be started.'
            ]);
        }

        DB::transaction(function () use ($complaint) {
            $complaint->status = 'in_progress';
            $complaint->save();

            StatusLog::create([
                'complaint_id' => $complaint->id,
                'updated_by'   => Auth::id(),
                'old_status'   => 'assigned',
                'new_status'   => 'in_progress',
                'note'         => 'Work started',
            ]);
        });

        return back()->with(
            'success',
            'Job started successfully.'
        );
    }

    /**
     * Complete a maintenance job.
     * Status: in_progress -> completed
     *
     * Technician must provide work notes.
     * Completion photo is optional.
     */
    public function complete(
        Request $request,
        Complaint $complaint
    ) {
        $this->authoriseOwner($complaint);

        if ($complaint->status !== 'in_progress') {
            return back()->withErrors([
                'status' => 'This job is not in progress.'
            ]);
        }

        $validated = $request->validateWithBag(
            'job' . $complaint->id,
            [
                'note' => [
                    'required',
                    'string',
                    'min:5',
                    'max:500',
                ],

                'completion_photo' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:5120',
                ],
            ],
            [
                'note.required' =>
                    'Please describe what you repaired.',

                'note.min' =>
                    'Please write at least 5 characters.',

                'completion_photo.image' =>
                    'Please upload a valid image.',

                'completion_photo.mimes' =>
                    'Only JPG, PNG and WEBP images are allowed.',

                'completion_photo.max' =>
                    'The photo must not exceed 5 MB.',
            ]
        );

        $photoPath = null;

        /**
         * Upload proof of work photo.
         */
        if ($request->hasFile('completion_photo')) {
            $photoPath = $request
                ->file('completion_photo')
                ->store('completion-proofs', 'public');
        }

        try {
            DB::transaction(function () use (
                $complaint,
                $validated,
                $photoPath
            ) {
                $complaint->status = 'completed';
                $complaint->resolved_at = now();

                if ($photoPath !== null) {
                    $complaint->completion_photo = $photoPath;
                }

                $complaint->save();

                StatusLog::create([
                    'complaint_id' => $complaint->id,
                    'updated_by'   => Auth::id(),
                    'old_status'   => 'in_progress',
                    'new_status'   => 'completed',
                    'note'         => $validated['note'],
                ]);
            });
        } catch (\Throwable $e) {

            // Remove uploaded photo if database saving fails.
            if ($photoPath !== null) {
                Storage::disk('public')->delete($photoPath);
            }

            throw $e;
        }

        return redirect()
            ->route('jobs.index', [
                'tab' => 'history'
            ])
            ->with(
                'success',
                'Job completed successfully. Work notes and photo have been saved.'
            );
    }

    /**
     * Only the assigned technician can update the job.
     */
    private function authoriseOwner(
        Complaint $complaint
    ): void {
        if ((int) $complaint->assigned_to !== (int) Auth::id()) {
            abort(
                403,
                'This job is not assigned to you.'
            );
        }
    }
}
