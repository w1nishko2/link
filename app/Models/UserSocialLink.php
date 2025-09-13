<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSocialLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_name',
        'url',
        'icon_class',
        'order'
    ];

    /**
     * Связь с пользователем
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope для получения ссылок в правильном порядке
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
