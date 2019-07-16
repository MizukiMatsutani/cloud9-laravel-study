<?php

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Model;

class FriendsRelationship extends Model
{
    protected $table = 'friends_relationships';
    
    protected $fillable = [
        'own_friends_id',
        'other_friends_id',
    ];
    
    public function myFriends($friendId)
    {
        return $this->newInstance()
            ->where('own_friends_id', $friendId)
            ->get();
    }
    
    public function getAlongWith($ownId, $otherId)
    {
        $myRelation = $this->newInstance();
        $myRelation->fill([
           'own_friends_id' => $ownId,
           'other_friends_id' => $otherId,
        ]);
        $myRelation->save();
    }
    
    public function friend()
    {
        return $this->belongsTo(\App\Eloquents\Friend::class, 'own_friends_id', 'id');
    }
}
