<?php

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Model;

class Pin extends Model
{
    protected $table = 'pins';
    
    protected $fillable = [
        'friends_id',
        'latitude',
        'longitude',
    ];
    
    public function deleteByFriendId($friendId)
    {
        $this->newInstance()
            ->where('friends_id', $friendId)
            ->delete();
    }
    
    public function store($friendId, $latitude, $longitude)
    {
        $pin = $this->newInstance();
        $pin->fill([
            'friends_id' => $friendId,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
        $pin->save();
        
        return $pin;
    }
    
    public function friend()
    {
        return $this->hasOne(\App\Eloquents\Friend::class, 'id', 'friends_id');
    }
}
