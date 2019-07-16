<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Eloquents\Friend;
use App\Eloquents\Pin;

class EloquentRelationTest extends TestCase
{
    /**
     * @test
     */
    public function 単発でfriendをとる()
    {
        $friend = Friend::find(1);
        // dd($friend);
        $this->assertTrue(true);
    }
    
    /**
     * @test
     */
    public function friendからfriend_relationshipをとる()
    {
        # Friendが１のやつを取得
        $friend = Friend::find(1);
        
        # EloquentのFriend.phpで設定したメソッド名でアクセス
        $relationships = $friend->relationship;
        
        # ループしてみよう
        $myFriendIds = [];
        foreach($relationships as $myFriend) {
            $myFriendIds[] = $myFriend->other_friends_id;
        }
        
        # ddで見てみよう
        // dd($myFriendIds);

        $this->assertTrue(true);
    }
    
    /**
     * @test
     */
    public function friendからfriend_relationshipをとる_Shrot版()
    {
        $myFriendIds = Friend::find(1)->relationship->pluck('other_friends_id');
        // dd($myFriendIds->toArray());

        $this->assertTrue(true);
    }
    
    /**
     * @test
     */
    public function Friend経由でPinの座標を取得()
    {
        $pin = Friend::find(1)->pin;
        
        // dd($pin->latitude, $pin->longitude);
        
        $this->assertTrue(true);
    }
    
    /**
     * @test
     */
    public function Pin経由でFriendのニックネームを取得()
    {
        $friend = Pin::where('friends_id', '=', 1)->first()->friend;
        
        // dd($friend->nick/name);
        
        $this->assertTrue(true);
    }
    
    /**
     * @test
     */
    public function Pin経由でFriendRelationShipの友だち（other_id）を取得()
    {
        $friend = Pin::where('friends_id', '=', 1)->first()->friend;
        $myFriendIds = $friend->relationship->pluck('other_friends_id');
        
        // dd($myFriendIds);
        
        $this->assertTrue(true);
    }
    
    /**
     * @test
     */
    public function さらにそこから友だちの名前を取得()
    {
        $friend = Pin::where('friends_id', '=', 1)->first()->friend;
        $relationships = $friend->relationship;
        
        $myFriends = $relationships->map(function ($item, $key) {
            return Friend::find($item->other_friends_id);
        });
        
        // dd($myFriends->pluck('nickname'));
        
        // // 続けて書くこともできる（ddを入れやすい）
        // $myFriends = Pin::where('friends_id', '=', 1)
        //     ->first()
        //     ->friend
        //     ->relationship
        //     ->map(function ($item, $key) {
        //         return Friend::find($item->other_friends_id);
        //     })
        //     ->pluck('nickname');
        // dd($myFriends);
        
        $this->assertTrue(true);
    }
    
    /**
     * @test
     */
    public function Pinを持っているFriendを取得()
    {
        $weHavePin = Friend::has('pin')->get();
        
        // dd($weHavePin->toArray());
        
        $this->assertTrue(true);
    }
}
