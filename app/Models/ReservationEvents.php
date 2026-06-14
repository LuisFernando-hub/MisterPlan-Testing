<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationEvents extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationEventsFactory> */
    use HasFactory;

    protected $fillable = [
        "reservation_id",
        "type",
        "description"
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
