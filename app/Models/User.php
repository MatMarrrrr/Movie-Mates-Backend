<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login',
        'email',
        'password',
        'google_id',
        'avatar_url',
        'account_type',
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
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function sentFriendRequests(): HasMany
    {
        return $this->hasMany(FriendRequest::class, 'sender_id');
    }

    public function receivedFriendRequests(): HasMany
    {
        return $this->hasMany(FriendRequest::class, 'recipient_id');
    }

    public function movieComments(): HasMany
    {
        return $this->hasMany(MovieComment::class, 'user_id');
    }

    public function friends()
    {
        $loggedUserId = $this->id;

        $friendIds = Friend::where(function ($query) use ($loggedUserId) {
            $query->where('friend1_id', $loggedUserId)
                ->orWhere('friend2_id', $loggedUserId);
        })
            ->where('status', 'active')
            ->get()
            ->map(function ($friend) use ($loggedUserId) {
                return $friend->friend1_id == $loggedUserId ? $friend->friend2_id : $friend->friend1_id;
            })
            ->unique();

        return User::whereIn('id', $friendIds)->get();
    }
}
