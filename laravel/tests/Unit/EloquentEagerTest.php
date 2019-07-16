<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Eloquents\Friend;
use App\Eloquents\FriendsRelationship;

class EloquentEagerTest extends TestCase
{
    /**
     * @test
     */
    public function friendからfriend_relationshipをとる_eager()
    {
        # Friendを全件取得
        $allFriends = Friend::has('relationship')->get();

        # ループしてother_friends_idを取得
        $otherIds = [];
        foreach($allFriends as $friend) {
            $otherIds[] = $friend->relationship->pluck('other_friends_id');
        }
        
        # ddで見てみよう
        // dd($otherIds);

        $this->assertTrue(true);
    }
}
