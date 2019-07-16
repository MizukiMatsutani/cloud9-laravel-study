<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Eloquents\Friend;

class EloquentTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * @test
     */
    public function IDを指定して取得()
    {
        $friend = Friend::find(1);
        
        // dd($friend);
        
        $this->assertTrue(true);
    }
    
    /**
     * @test
     */
    public function 全件取得()
    {
        $friends = Friend::all();
        
        // dd($friends->count());
        
        $this->assertTrue(true);
    }
    
    /**
     * @test
     */
    public function 条件を指定して取得()
    {
        $friends = Friend::where('nickname', 'like', '%kiyo%')->get();
        
        // dd($friends);
        
        $this->assertTrue(true);
    }
    
    /**
     * @test
     */
    public function 登録()
    {
        $friend = Friend::create([
            'nickname' => 'あああ',
            'email' => 'hogefuga3@piyo.com',
            'password' => 'パスワード',
            'remember_token' => 'aaaa', // fillableにより無視される
        ]);
        
        // dd($friend);
        
        // // こっちの書き方もOK
        // $friend = new Friend();
        // $friend->fill([
        //     'nickname' => 'あああ',
        //     'email' => 'hogefuga2@piyo.com',
        //     'password' => 'パスワード',
        //     'remember_token' => 'aaaa',  // fillableにより無視される
        // ])
        // ->save();
        
        // dd($friend);
        
        $this->assertTrue(true);
    }
    
    /**
     * @test
     */
    public function 更新()
    {
        $friend = Friend::find(2);
        $friend->fill([
            'nickname' => 'kiyopikko3',
            'remember_token' => 'aaaa',  // fillableにより無視される
        ])
        ->save();
        
        // dd($friend);
        
        $this->assertTrue(true);
    }
    
    /**
     * @test
     */
    public function 削除()
    {
        // ID指定の場合
        // $friend = Friend::destroy(7);
        
        // dd($friend);
        
        // 条件指定の場合
        // $friends = Friend::where('nickname', 'like', '%山田%')->delete();
        // dd($friends);
        
        $this->assertTrue(true);
    }
}
