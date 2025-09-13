<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'bio',
        'phone',
        'password',
        'role',
        'telegram_url',
        'whatsapp_url',
        'vk_url',
        'youtube_url',
        'ok_url',
        'background_image',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Find the user instance for the given username.
     *
     * @param  string  $phone
     * @return \App\Models\User
     */
    public function findForPassport($phone)
    {
        return $this->where('phone', $phone)->first();
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'phone';
    }

    /**
     * Relationships
     */
    public function galleryImages()
    {
        return $this->hasMany(GalleryImage::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function banners()
    {
        return $this->hasMany(Banner::class);
    }

    /**
     * Получить пользовательские социальные ссылки
     */
    public function socialLinks()
    {
        return $this->hasMany(UserSocialLink::class)->ordered();
    }

    /**
     * Получить настройки секций пользователя
     */
    public function sectionSettings()
    {
        return $this->hasMany(UserSectionSettings::class)->ordered();
    }

    /**
     * Проверить, является ли пользователь администратором
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Проверить, является ли пользователь обычным пользователем
     */
    public function isUser()
    {
        return $this->role === 'user';
    }
}
