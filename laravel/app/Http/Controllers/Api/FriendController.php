<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Eloquents\Friend;
use App\Eloquents\FriendsRelationship;
use App\Http\Resources\FriendResource;
use App\Http\Resources\FriendCollection;
use App\Http\Requests\Api\ShowFriendRequest;

class FriendController extends Controller
{
    protected $friend;
    protected $relationship;
    
    public function __construct(Friend $friend, FriendsRelationship $relationship)
    {
        $this->friend = $friend;
        $this->relationship = $relationship;
    }
    
    public function me(Request $request)
    {
        $myId = $request->user()->id;
        $myInfo = $this->friend->findById($myId);
        
        return new FriendResource($myInfo);
    }
    
    public function show(ShowFriendRequest $request, $userId)
    {
        $friend = $this->friend->findById($userId);
        
        return new FriendResource($friend);
    }
    
    public function list(Request $request)
    {
        $myId = $request->user()->id;
        $friendIds = $this->relationship->myFriends($myId)->pluck('other_friends_id')->toArray();
        $friends = $this->friend->findByIds($friendIds);
        
        return new FriendCollection($friends);
    }
}
