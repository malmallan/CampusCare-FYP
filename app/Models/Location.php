<?php

// FAIL: app/Models/Location.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['floor', 'room_name'];

    // Teks yang dipaparkan dalam senarai pilihan dan disimpan
    // dalam aduan. Contoh: "Level 2, Computer Lab 1"
    public function fullName(): string
    {
        return "{$this->floor}, {$this->room_name}";
    }
}
