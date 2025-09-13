<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSectionSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'section_key',
        'title',
        'subtitle',
        'is_visible',
        'order',
        'additional_settings',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'additional_settings' => 'array',
    ];

    /**
     * Связь с пользователем
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope для получения видимых секций
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * Scope для получения секций в правильном порядке
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Получить заголовок по умолчанию для секции
     */
    public static function getDefaultTitle($sectionKey)
    {
        $defaults = [
            'hero' => 'Главная секция',
            'services' => 'Мои услуги',
            'gallery' => 'Портфолио',
            'articles' => 'Статьи',
            'banners' => 'Баннеры',
        ];

        return $defaults[$sectionKey] ?? 'Секция';
    }

    /**
     * Получить подзаголовок по умолчанию для секции
     */
    public static function getDefaultSubtitle($sectionKey)
    {
        $defaults = [
            'hero' => 'Добро пожаловать на мою страницу',
            'services' => 'Что я предлагаю',
            'gallery' => 'Мои работы и проекты',
            'articles' => 'Последние публикации',
            'banners' => 'Важная информация',
        ];

        return $defaults[$sectionKey] ?? '';
    }
}
