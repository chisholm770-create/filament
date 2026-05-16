<?php

namespace App\Models;

use App\Enums\ShipmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    protected $fillable = [
        'tracking_id',
        'customer_id',
        'driver_id',
        'origin',
        'destination',
        'weight',
        'status',
        'delivery_date',
        'notes',
    ];

    protected $casts = [
        'status' => ShipmentStatus::class,
        'delivery_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function trackingLogs(): HasMany
    {
        return $this->hasMany(TrackingLog::class);
    }

    public function delivery(): ?Delivery
    {
        return $this->hasOne(Delivery::class)->latest();
    }

    public static function generateTrackingId(): string
    {
        $date = now()->format('ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        return "SHP-{$date}-{$random}";
    }

    public function updateStatus(ShipmentStatus $status, ?string $notes = null, ?float $latitude = null, ?float $longitude = null): void
    {
        $this->update(['status' => $status]);
        $this->trackingLogs()->create([
            'status' => $status,
            'notes' => $notes,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }
}
