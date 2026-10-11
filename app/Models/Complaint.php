<?php

// FAIL: app/Models/Complaint.php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Complaint extends Model
{
    use HasFactory;

    // 'location' kini teks biasa, bukan location_id.
    // 'status' dan 'assigned_to' SENGAJA tiada di sini supaya
    // pelajar tidak boleh menetapkannya melalui borang.
    protected $fillable = [
        'user_id',
        'category_id',
        'location',
        'title',
        'description',
        'photo_path',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    // ---------- RELATIONSHIP ----------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Sejarah disusun dari yang PALING AWAL, supaya garis masa
    // dibaca dari atas ke bawah mengikut urutan kejadian.
    public function statusLogs(): HasMany
    {
        return $this->hasMany(StatusLog::class)->oldest();
    }

    // ---------- HELPER ----------

    public function statusLabel(): string
    {
        return match ($this->status) {
            'new'         => 'New',
            'assigned'    => 'Assigned',
            'in_progress' => 'In Progress',
            'completed'   => 'Completed',
            default       => (string) $this->status,
        };
    }

    public function statusColour(): string
    {
        return match ($this->status) {
            'new'         => 'secondary',
            'assigned'    => 'info',
            'in_progress' => 'warning',
            'completed'   => 'success',
            default       => 'secondary',
        };
    }

    // Tempoh dari aduan dihantar sehingga siap dibaiki.
    // Contoh hasil: "2 days 3 hours". Bukti prestasi untuk teknisyen,
    // dan data untuk mengira purata masa penyelesaian nanti.
    public function timeTaken(): ?string
    {
        if (! $this->resolved_at) {
            return null;
        }

        return $this->created_at->diffForHumans($this->resolved_at, [
            'syntax' => CarbonInterface::DIFF_ABSOLUTE,
            'parts'  => 2,
        ]);
    }
}
