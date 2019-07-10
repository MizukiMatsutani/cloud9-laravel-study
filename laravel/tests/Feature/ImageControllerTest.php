<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Eloquents\Friend;
use Illuminate\Http\UploadedFile;

class ImageControllerTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * @test
     */
    public function store_face_image_success()
    {

        $friend = factory(Friend::class)->create();
        
        \Storage::fake('local');

        $response = $this->actingAs($friend, 'api')
            ->json('POST', route('api.me.image.post'), [
                'file' => UploadedFile::fake()->image('test.png')->size(250),
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'face_image_url' => route('web.image.get', ['userId' => $friend->id])
            ]);

        $uploaded = Friend::find($friend->id)->image_path;
        \Storage::disk('local')->assertExists($uploaded);
    }
}
