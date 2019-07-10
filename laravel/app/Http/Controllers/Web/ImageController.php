<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Eloquents\Friend;

class ImageController extends Controller
{
    protected $friend;
    
    public function __construct(Friend $friend)
    {
        $this->friend = $friend;
    }
    
    public function show(Request $request, $userId)
    {
        $path = $this->friend->find($userId)->image_path;

        return response()->file(storage_path('app/' . $path));
    }
}
