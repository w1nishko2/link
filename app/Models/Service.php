<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image_path',
        'price',
        'price_type',
        'order_index',
        'is_active',
        'button_text',
        'button_link'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index');
    }

    public function getFormattedPriceAttribute()
    {
        if (!$this->price) {
            return 'По договоренности';
        }

        $price = number_format((float)$this->price, 0, '.', ' ') . ' ₽';
        
        switch ($this->price_type) {
            case 'hourly':
                return $price . '/час';
            case 'project':
                return 'от ' . $price;
            default:
                return $price;
        }
    }
}
