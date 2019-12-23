<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends Filters
{

    protected $filters = ['by', 'popular', 'unanswered'];
    /**
     * Filter the query by given a username
     *
     * @param string $username
     * @param $username
     * @return bool
     */
    protected function by($username): bool
    {
        $user = User::where('name', $username) -> firstOrFail();
        return $this->builder-> where('user_id', $user -> id);
    }

    /**
     * Filter the query according to most popular threads.
     *
     * @return $this
     */
    protected function popular()
    {
         return $this->builder->orderBy('replies_count', 'desc');
    }

    protected function unanswered()
    {
        return $this->builder->where('replies_count', 0);
    }

}
