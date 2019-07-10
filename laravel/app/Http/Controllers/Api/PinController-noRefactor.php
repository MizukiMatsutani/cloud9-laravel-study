<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PinStoreRequest;
use App\Http\Resources\FriendCollection;

class PinController_NoRefactor extends Controller
{
    public function store(PinStoreRequest $request)
    {
        $myFriendId = $request->user()->id;
        
        // pinを登録
        \App\Eloquents\Pin::where('friends_id', $myFriendId)->delete();
        $pin = new \App\Eloquents\Pin;
        $pin->fill([
            'friends_id' => $myFriendId,
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
        ]);
        $pin->save();
        
        // すでに友達の人
        $myFriends = \App\Eloquents\FriendsRelationship::where('own_friends_id', $myFriendId)->get();
        
        // まだ友達ではない人
        $notFriends = \App\Eloquents\Friend::with(['pin'])
            ->where('id', '<>', $myFriendId)
            ->whereNotIn('id', $myFriends->pluck('other_friends_id')->toArray())
            ->whereHas('pin', function ($query) {
                $query->where('created_at', '>=', now()->subMinutes(5));
            })
            ->get();
        
        // 近くのピンの人（友達になれそうな人）を探す
        $canBeFriendIds = \Distance::canBeFriends($pin->toArray(), $notFriends->pluck('pin')->toArray());
        
        // 近くのピンの人がいれば友達になる
        foreach ($canBeFriendIds as $othersId) {
            // 自分の友達として登録
            $myRelation = new \App\Eloquents\FriendsRelationship;
            $myRelation->fill([
               'own_friends_id' => $myFriendId,
               'other_friends_id' => $othersId,
            ]);
            $myRelation->save();
            
            // 相手の友達として登録
            $otherRelation = new \App\Eloquents\FriendsRelationship;
            $otherRelation->fill([
               'own_friends_id' => $othersId,
               'other_friends_id' => $myFriendId,
            ]);
            $otherRelation->save();
        }
        
        // 新しく友達になった人
        $newFriends = \App\Eloquents\Friend::with(['pin'])
            ->whereIn('id', $canBeFriendIds)
            ->get();
        
        return new FriendCollection($newFriends);
    }
}
