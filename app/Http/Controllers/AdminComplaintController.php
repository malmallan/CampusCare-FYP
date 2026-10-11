<?php

// FAIL: app/Http/Controllers/AdminComplaintController.php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\StatusLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminComplaintController extends Controller
{
    public function index(Request $request)
    {
        // 'location' tidak lagi dimuatkan sebagai relationship,
        // kerana ia kini lajur teks biasa dalam jadual complaints.
        $query = Complaint::with(['user', 'category', 'technician']);

        $query->when($request->status, fn ($q, $status) =>
            $q->where('status', $status)
        );

        $query->when($request->category, fn ($q, $category) =>
            $q->where('category_id', $category)
        );

        $complaints = $query
            ->orderByRaw('category_id IS NULL DESC')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::orderBy('category_name')->get();

        return view('admin.complaints.index', compact('complaints', 'categories'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load(['user', 'category', 'technician', 'statusLogs.updatedBy']);

        $categories  = Category::orderBy('category_name')->get();
        $technicians = User::where('role', 'technician')->orderBy('name')->get();

        return view('admin.complaints.show', compact(
            'complaint', 'categories', 'technicians'
        ));
    }

    public function assign(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'assigned_to' => ['required', 'exists:users,id'],
        ]);

        // exists:users,id hanya menyemak orang itu wujud, bukan peranannya.
        $technician = User::findOrFail($validated['assigned_to']);

        if (! $technician->isTechnician()) {
            return back()->withErrors([
                'assigned_to' => 'That user is not a technician.',
            ]);
        }

        $oldStatus = $complaint->status;

        // Ditetapkan satu per satu kerana 'status' dan 'assigned_to'
        // sengaja tiada dalam $fillable.
        $complaint->category_id = $validated['category_id'];
        $complaint->assigned_to = $technician->id;
        $complaint->status      = 'assigned';
        $complaint->save();

        StatusLog::create([
            'complaint_id' => $complaint->id,
            'updated_by'   => Auth::id(),
            'old_status'   => $oldStatus,
            'new_status'   => 'assigned',
            'note'         => 'Assigned to ' . $technician->name,
        ]);

        return redirect()
            ->route('admin.complaints.index')
            ->with('success', "Complaint assigned to {$technician->name}.");
    }
}
