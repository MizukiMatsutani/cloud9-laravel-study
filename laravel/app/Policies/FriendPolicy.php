<?php

namespace App\Policies;

use App\Eloquents\Friend;
use App\Eloquents\FriendsRelationship;
use Illuminate\Auth\Access\HandlesAuthorization;

class FriendPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    public function view(Friend $user, Friend $friend)
    {
        return $friend->relationship->where('other_friends_id', $user->id)->count() > 0;
    }
}
