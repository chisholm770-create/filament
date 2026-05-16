<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    protected $fillable = [
        'shipment_id',
        'driver_id',
        'signature_url',
        'notes',
        'delivered_at',
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public static function confirmDelivery(Shipment $shipment, Driver $driver, ?string $signatureUrl = null, ?string $notes = null): self
    {
        $delivery = self::create([
            'shipment_id' => $shipment->id,
            'driver_id' => $driver->id,
            'signature_url' => $signatureUrl,
            'notes' => $notes,
            'delivered_at' => now(),
        ]);

        $shipment->updateStatus(
            \App\Enums\ShipmentStatus::DELIVERED,
            "Delivered by {$driver->name}. {$notes}"
        );

        return $delivery;
    }
}
