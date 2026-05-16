<?php

namespace App\Models;

use App\Enums\ShipmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackingLog extends Model
{
    protected $fillable = [
        'shipment_id',
        'status',
        'notes',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'status' => ShipmentStatus::class,
        'created_at' => 'datetime',
    ];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public static function log(Shipment $shipment, ShipmentStatus $status, ?string $notes = null, ?array $location = null): self
    {
        return self::create([
            'shipment_id' => $shipment->id,
            'status' => $status,
            'notes' => $notes,
            'latitude' => $location['latitude'] ?? null,
            'longitude' => $location['longitude'] ?? null,
        ]);
    }
}
