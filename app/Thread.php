<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
use App\Filters\ThreadFilters;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
use \Illuminate\Support\Str;
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

    protected $appends = ['isSubscribedTo'];

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

        static::created(function ($thread) {
            $thread->update(['slug' => $thread->title]);
        });

    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
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
        //(new\App\Spam)->detect($reply->body);

        $reply = $this->replies()->create($reply);
        event(new ThreadReceivedNewReply($reply));

        return $reply;
    }

    public function lock()
    {
       return $this->update(['locked' => true]);
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

         return $this;
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

    public function  getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function hasUpdatesFor(){
        $key = auth()->user()->visitedThreadCacheKey($this);
        return $this->updated_at >cache($key);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setSlugAttribute($value)
    {

        $slug = Str::slug($value);
       // $original = $slug;
        //$count =  2;

//        while(static::whereSlug($slug)->exists()){
//            $slug = "{$original}-" . $count++;
//        }

        //si se quiere usar el slug con id
        if(static::whereSlug($slug)->exists()){
            $slug = "{$slug}-" . $this->id;
        }

        return $this->attributes['slug'] = $slug;
    }

//        public function visits()
//    {
//        return new Visits($this);
//    }

      public function markBestReply(Reply $reply)
      {
          $this->update(['best_reply_id' => $reply->id]);
      }

}
