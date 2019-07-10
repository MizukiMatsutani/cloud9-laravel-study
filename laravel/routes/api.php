<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/signup', 'Api\SignupController@signup')->name('api.signup.post');

Route::get('/oauth/client-credentials' , function () {
    $secret = \DB::table('oauth_clients')
        ->select(['id', 'secret'])
        ->first();
        
    return response()->json([
        'client_id' => $secret->id,
        'client_secret' => $secret->secret,
    ]);
});

Route::middleware('auth:api')->group(function () {
    
    Route::get('/me', 'Api\FriendController@me')->name('api.me.get');
    
    Route::post('/me/image', 'Api\ImageController@store')->name('api.me.image.post');
    
    Route::post('/my/pin', 'Api\PinController@store')->name('api.my.pin.post');
    
    Route::get('/friends/{userId}', 'Api\FriendController@show')->name('api.friends.get');
    
    Route::get('/friends', 'Api\FriendController@list')->name('api.friends.list.get');
});
