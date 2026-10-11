
@extends('layouts.app')

@section('title', 'User Manual - CampusCare')

@section('content')

<style>
    .manual-intro {
        color: var(--muted);
        margin-bottom: 2rem;
    }

    .manual-card {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
    }

    .manual-card h2 {
        color: var(--brand);
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .manual-step {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.25rem;
        align-items: flex-start;
    }

    .manual-step:last-child {
        margin-bottom: 0;
    }

    .step-number {
        background: var(--brand);
        color: white;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
    }

    .step-content h3 {
        font-size: .98rem;
        font-weight: 700;
        margin: 0 0 .3rem;
    }

    .step-content p {
        color: var(--muted);
        font-size: .9rem;
        margin: 0;
    }

    .manual-tip {
        background: #eaf2fb;
        border-left: 4px solid var(--brand);
        padding: 1rem;
        border-radius: 6px;
        font-size: .9rem;
        margin-top: 1rem;
    }

    .manual-actions {
        margin-top: 1.5rem;
    }
</style>

<h1 class="page-title">User Manual</h1>

<p class="manual-intro">
    Welcome to the CampusCare User Manual.
    Follow the steps below to learn how to use the system.
</p>

{{-- STUDENT USER MANUAL --}}
@if(auth()->user()->isStudent())

    <div class="manual-card">
        <h2>Student Guide</h2>

        <div class="manual-step">
            <div class="step-number">1</div>
            <div class="step-content">
                <h3>Log in to CampusCare</h3>
                <p>
                    Enter your registered email address and password
                    to access the system.
                </p>
            </div>
        </div>

        <div class="manual-step">
            <div class="step-number">2</div>
            <div class="step-content">
                <h3>Submit a Complaint</h3>
                <p>
                    Click "Report a problem" in the navigation bar.
                    Fill in the complaint details, including the title,
                    description and location. Attach a photo if needed,
                    then submit your complaint.
                </p>
            </div>
        </div>

        <div class="manual-step">
            <div class="step-number">3</div>
            <div class="step-content">
                <h3>Automatic Complaint Classification</h3>
                <p>
                    CampusCare uses keyword-based classification to
                    automatically identify the complaint category
                    based on the description provided.
                </p>
            </div>
        </div>

        <div class="manual-step">
            <div class="step-number">4</div>
            <div class="step-content">
                <h3>Track Your Complaint</h3>
                <p>
                    Click "My complaints" to view your submitted
                    complaints and check their current status.
                </p>
            </div>
        </div>

        <div class="manual-step">
            <div class="step-number">5</div>
            <div class="step-content">
                <h3>View Complaint History</h3>
                <p>
                    Open a complaint to view its details and
                    available status updates.
                </p>
            </div>
        </div>

        <div class="manual-tip">
            <strong>Tip:</strong>
            Provide a clear description and accurate location
            to help the maintenance team understand the issue.
        </div>

        <div class="manual-actions">
            <a href="{{ route('complaints.create') }}"
               class="btn btn-brand">
                Report a Problem
            </a>
        </div>
    </div>

{{-- ADMIN USER MANUAL --}}
@elseif(auth()->user()->isAdmin())

    <div class="manual-card">
        <h2>Administrator Guide</h2>

        <div class="manual-step">
            <div class="step-number">1</div>
            <div class="step-content">
                <h3>Log in as Administrator</h3>
                <p>
                    Enter your administrator account details
                    to access CampusCare.
                </p>
            </div>
        </div>

        <div class="manual-step">
            <div class="step-number">2</div>
            <div class="step-content">
                <h3>View All Complaints</h3>
                <p>
                    Open "All complaints" to view complaints
                    submitted by students.
                </p>
            </div>
        </div>

        <div class="manual-step">
            <div class="step-number">3</div>
            <div class="step-content">
                <h3>Filter Complaints</h3>
                <p>
                    Use the status and category filters to
                    find specific complaints.
                </p>
            </div>
        </div>

        <div class="manual-step">
            <div class="step-number">4</div>
            <div class="step-content">
                <h3>Review Complaint Details</h3>
                <p>
                    Click "View" to check the complaint
                    description, location and category.
                </p>
            </div>
        </div>

        <div class="manual-step">
            <div class="step-number">5</div>
            <div class="step-content">
                <h3>Assign a Technician</h3>
                <p>
                    Select a suitable technician to handle
                    the complaint and save the assignment.
                </p>
            </div>
        </div>

        <div class="manual-step">
            <div class="step-number">6</div>
            <div class="step-content">
                <h3>Monitor Complaint Progress</h3>
                <p>
                    Review complaint statuses to monitor
                    maintenance progress and completed work.
                </p>
            </div>
        </div>

        <div class="manual-tip">
            <strong>Tip:</strong>
            Check the complaint details before assigning
            a technician to make sure the issue is handled
            by the appropriate person.
        </div>

        <div class="manual-actions">
            <a href="{{ route('admin.complaints.index') }}"
               class="btn btn-brand">
                View All Complaints
            </a>
        </div>
    </div>

{{-- TECHNICIAN USER MANUAL --}}
@elseif(auth()->user()->isTechnician())

    <div class="manual-card">
        <h2>Technician Guide</h2>

        <div class="manual-step">
            <div class="step-number">1</div>
            <div class="step-content">
                <h3>Log in as Technician</h3>
                <p>
                    Enter your technician account details.
                    The system will direct you to the Report Log page.
                </p>
            </div>
        </div>

        <div class="manual-step">
            <div class="step-number">2</div>
            <div class="step-content">
                <h3>View Assigned Complaints</h3>
                <p>
                    Open "Report Log" to view complaints
                    assigned to you by the administrator.
                </p>
            </div>
        </div>

        <div class="manual-step">
            <div class="step-number">3</div>
            <div class="step-content">
                <h3>Review Complaint Information</h3>
                <p>
                    Read the complaint details and location
                    before starting maintenance work.
                </p>
            </div>
        </div>

        <div class="manual-step">
            <div class="step-number">4</div>
            <div class="step-content">
                <h3>Start Maintenance Work</h3>
                <p>
                    Use the available start action to update
                    the complaint status when work begins.
                </p>
            </div>
        </div>

        <div class="manual-step">
            <div class="step-number">5</div>
            <div class="step-content">
                <h3>Complete Maintenance Work</h3>
                <p>
                    After resolving the issue, use the complete
                    action and provide any required work notes.
                </p>
            </div>
        </div>

        <div class="manual-step">
            <div class="step-number">6</div>
            <div class="step-content">
                <h3>Review Work History</h3>
                <p>
                    Check the Report Log for completed
                    complaints and available maintenance records.
                </p>
            </div>
        </div>

        <div class="manual-tip">
            <strong>Tip:</strong>
            Update the complaint status after carrying out
            maintenance work so the progress can be tracked.
        </div>

        <div class="manual-actions">
            <a href="{{ route('jobs.index') }}"
               class="btn btn-brand">
                Open Report Log
            </a>
        </div>
    </div>

@endif

@endsection
