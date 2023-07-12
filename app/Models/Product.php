<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable=[
        // 'price',
        'created_at',
    ];

    public function prices(): HasMany
    {
        return $this->hasMany(PriceHistory::class);
    }

    public function inventoryDetails(): HasMany
    {
        return $this->hasMany(InventoryDetail::class)->OrderByRecommended();
    }

    public function recommendedInventoryDetail(): HasOne
    {
        return $this->inventoryDetails()->one();
    }
}
