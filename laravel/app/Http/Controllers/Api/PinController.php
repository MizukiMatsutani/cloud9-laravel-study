<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PinStoreRequest;
use App\Http\Resources\FriendCollection;
use App\Eloquents\Pin;
use App\Eloquents\Friend;
use App\Eloquents\FriendsRelationship;

class PinController extends Controller
{
    protected $pin;
    protected $friend;
    protected $friendRelationship;
    
    public function __construct(
        Pin $pin,
        Friend $friend,
        FriendsRelationship $friendRelationship
    ) {
        $this->pin = $pin; 
        $this->friend = $friend;
        $this->friendRelationship = $friendRelationship;
    }
    
    public function store(PinStoreRequest $request)
    {
        $newFriends = \DB::transaction(function () use ($request) {
            $myFriendId = $request->user()->id;
            
            // pinを登録
            $this->pin->deleteByFriendId($myFriendId);
            $pin = $this->pin->store(
                $myFriendId,
                $request->input('latitude'),
                $request->input('longitude')
            );
            
            // すでに友達の人
            $myFriends = $this->friendRelationship->myFriends($myFriendId);
            
            // まだ友達ではない人
            $notFriends = $this->friend->notFriendsWithPin($myFriendId, $myFriends);
            
            // 近くのピンの人（友達になれそうな人）を探す
            $canBeFriendIds = \Distance::canBeFriends($pin->toArray(), $notFriends->pluck('pin')->toArray());
            
            // 近くのピンの人がいれば友達になる
            foreach ($canBeFriendIds as $othersId) {
                // 自分の友達として登録
                $this->friendRelationship->getAlongWith($myFriendId, $othersId);
                // 相手の友達として登録
                $this->friendRelationship->getAlongWith($othersId, $myFriendId);
            }
            
            // 新しく友達になった人
            return $this->friend->findByIds($canBeFriendIds);
        });
        
        return new FriendCollection($newFriends);
    }
}
