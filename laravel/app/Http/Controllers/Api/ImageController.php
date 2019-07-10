<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Eloquents\Friend;
use App\Http\Requests\Api\ImageRequest;

class ImageController extends Controller
{
    protected $friend;
    
    public function __construct(Friend $friend)
    {
        $this->friend = $friend;
    }
    
    public function store(ImageRequest $request)
    {
        $myId = \DB::transaction(function () use ($request) {
            $myId = $request->user()->id;
            $path = $request->file->store('images', 'local');
            
            $this->friend->imageStore($myId, $path);
            
            return $myId;
        });

        return response()->json([
            'face_image_url' => route('web.image.get', ['userId' => $myId])
        ]);
    }
}
