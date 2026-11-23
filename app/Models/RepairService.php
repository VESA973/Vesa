<?php

namespace App\Models;

use Database\Factories\RepairServiceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One repair performed on a device, e.g. "Remplacement du port HDMI", with an optional starting price.
 */
#[Fillable(['repair_device_id', 'name', 'description', 'price_from', 'sort_order'])]
class RepairService extends Model
{
    /** @use HasFactory<RepairServiceFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price_from' => 'decimal:2',
        ];
    }

    /**
     * @return BelongsTo<RepairDevice, $this>
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(RepairDevice::class, 'repair_device_id');
    }
}
