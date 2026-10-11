<?php

// FAIL: app/Models/StatusLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'updated_by',
        'old_status',
        'new_status',
        'note',
    ];

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // BARU: label yang mudah dibaca untuk garis masa sejarah.
    public function label(): string
    {
        return match ($this->new_status) {
            'new'         => 'Reported',
            'assigned'    => 'Assigned',
            'in_progress' => 'Work started',
            'completed'   => 'Completed',
            default       => ucfirst((string) $this->new_status),
        };
    }
}
