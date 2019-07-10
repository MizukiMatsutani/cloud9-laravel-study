<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Eloquents\Friend;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        // $friend = Friend::with('relationship')->whereIn('id', [1, 2, 3])->get();
        
        // foreach ($friend as $hoge) {
        //     $fuga[] = $hoge->relationship->toArray();
        // }
        // dd($fuga);
        // /**
        //  * select * from friends where id in (1, 2);
        //  * select * from friends_relationships where own_friends_id in (1, 2, 3);
        //  */
        
        $this->assertTrue(true);
    }
}
