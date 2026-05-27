<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'nama_lengkap',
    'username',
    'email',
    'password',
    'no_telp',
    'alamat',
    'foto',
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function bookings()
{
    return $this->hasMany(Booking::class);
}

/*
|-------------------------
| CHAT RELATION
|-------------------------
*/

// 1 user hanya punya 1 conversation
public function conversation()
{
    return $this->hasOne(Conversation::class);
}

// user bisa kirim banyak message
public function messages()
{
    return $this->hasMany(Message::class, 'sender_id');
}

}
