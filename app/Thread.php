<?php

namespace App;

use App\Filters\ThreadFilters;
use Illuminate\Database\Eloquent\Model;
use function foo\func;

class Thread extends Model
{
    use RecordsActivity;

    /**
     * Don't auto-play mass assigment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The relationship to always eager-load.
     *
     * @var array
     */
    protected  $with = ['creator', 'channel'];

    protected static function boot()
    {
        parent::boot();

//        static::addGlobalScope('replyCount', function ($builder) {
//            $builder->withCount('replies');
//        });

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });

    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function replies()
    {
        return $this -> hasMany(Reply::class);
    }

//======OPCION PARA EL CONTEO DE REPLIES
//    public function getReplyCountAttribute()
//    {
//       return $this ->replies() -> count();
//    }

    public function addReply($reply)
    {
       return $this->replies()->create($reply);

    }

    public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
         $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
         ]);
    }

    public function subscriptions()
    {
       return $this->hasMany(ThreadSubscriptions::class);
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
             ->where('user_id', $userId ?: auth()->id())
             ->delete();
    }
}
