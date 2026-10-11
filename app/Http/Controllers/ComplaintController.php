<?php

// FAIL: app/Http/Controllers/ComplaintController.php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Location;
use App\Models\StatusLog;
use App\Services\ComplaintClassifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function __construct(
        private ComplaintClassifier $classifier
    ) {}

    /**
     * Senarai aduan pelajar yang sedang log masuk.
     */
    public function index()
    {
        $complaints = Complaint::where('user_id', Auth::id())
            ->with(['category'])
            ->latest()
            ->paginate(10);

        return view('complaints.index', compact('complaints'));
    }

    /**
     * Papar borang, bersama senarai venue sebagai CADANGAN.
     * Pelajar boleh pilih dari senarai atau menaip sendiri.
     */
    public function create()
    {
        $locations = Location::orderBy('floor')
            ->orderBy('room_name')
            ->get();

        return view('complaints.create', compact('locations'));
    }

    /**
     * Simpan aduan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
            // BARU: lokasi ditaip sendiri, bukan dipilih dari senarai.
            'location'    => ['required', 'string', 'min:3', 'max:150'],
            'photo'       => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ], [
            'description.min'   => 'Please describe the problem in at least 10 characters.',
            'location.required' => 'Please tell us where the problem is.',
            'location.min'      => 'Please give a clearer location, for example Block B, Level 2.',
            'photo.max'         => 'The photo must be smaller than 2MB.',
        ]);

        $path = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('complaints', 'public');
        }

        $categoryId = $this->classifier->classify(
            $validated['title'],
            $validated['description']
        );

        $complaint = Complaint::create([
            'user_id'     => Auth::id(),
            'category_id' => $categoryId,
            // trim() buang ruang kosong di hujung yang pelajar tertaip.
            'location'    => trim($validated['location']),
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'photo_path'  => $path,
        ]);

        // BARU: rekod penghantaran dalam sejarah.
        // Dengan ini garis masa bermula dari saat aduan dibuat,
        // bukan dari saat admin menugaskannya.
        StatusLog::create([
            'complaint_id' => $complaint->id,
            'updated_by'   => Auth::id(),
            'old_status'   => null,
            'new_status'   => 'new',
            'note'         => 'Complaint submitted',
        ]);

        return redirect()
            ->route('complaints.index')
            ->with('success', 'Your complaint has been sent. Check this page for updates.');
    }
}
