<?php

namespace App\Models;

use App\Events\ReservationCreated;
use App\Events\ReservationUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\Reservation> */
    use HasFactory;

    protected $fillable = [
        "guest_name",
        "guest_email",
        "property_name",
        "check_in",
        "check_out",
        "status",
        "amount",
        "notes"
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
    ];

    protected $dispatchesEvents = [
        'created' => ReservationCreated::class,
        'updated' => ReservationUpdated::class,
    ];

    public function events() {
        return $this->hasMany(ReservationEvents::class, 'reservation_id', 'id');
    }
    
}
