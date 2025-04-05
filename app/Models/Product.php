<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'image', // Added image to fillable attributes
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'float',
    ];

    /**
     * Get the formatted price in USD
     *
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Get the EUR price based on the current exchange rate
     *
     * @param float $exchangeRate
     * @return float
     */
    public function getEurPrice(float $exchangeRate): float
    {
        return $this->price * $exchangeRate;
    }

    /**
     * Get the formatted EUR price
     * Note: This is a regular method, not an accessor
     *
     * @param float $exchangeRate
     * @return string
     */
    public function formattedEurPrice(float $exchangeRate): string
    {
        return '€' . number_format($this->getEurPrice($exchangeRate), 2);
    }
}
