<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar_path',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','email',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'confirmed' => 'boolean'
    ];

    /**
     * Get the route key name for Laravel.
     *
     * @return  string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }

    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    public function confirm()
    {
        $this->confirmed = true;
        $this->confirmation_token = null;
        $this->save();
    }

    public function isAdmin()
    {
        return in_array($this->name, ['MansonK', 'John Doe']);
    }

    public function read($thread)
    {
        //Simulate that the user visited the thread
        cache()->forever(
            $this->visitedThreadCacheKey($thread),
            Carbon::now());
    }

    //getAvatarPathAttribute($avatar)
    public function avatar()
    {
        //return asset($this->avatar_path ?: 'avatars/default.jpg');
        return $this->avatar_path ?: 'avatars/default.jpg';
        //return asset($avatar ?: 'avatars/default.jpg');
    }

    public function visitedThreadCacheKey($thread)
    {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }



}
