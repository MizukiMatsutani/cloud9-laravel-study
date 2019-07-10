<?php

namespace App\Eloquents;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Friend extends Authenticatable
{
    use HasApiTokens;
    
    private const SEARCH_LIMIT_MINUTES = 5;
    
    protected $table = 'friends';
    
    protected $fillable = [
        'nickname', 'email', 'password', 'image_path'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function store($email, $password, $nickname)
    {
        return $this->newInstance()
            ->create([
                'email' => $email,
                'password' => bcrypt($password),
                'nickname' => $nickname,
            ]);
    }
    
    public function imageStore($myId, $path)
    {
        return $this->newInstance()
            ->find($myId)
            ->fill([
                'image_path' => $path,
            ])
            ->save();
    }
    
    public function findById($friendId)
    {
        return $this->newInstance()
            ->with(['pin'])
            ->find($friendId);
    }
    
    public function findByIds($friendIds)
    {
        return $this->newInstance()
            ->with(['pin'])
            ->whereIn('id', $friendIds)
            ->get();
    }
    
    public function notFriendsWithPin($myId, $myFriends)
    {
        return $this->newInstance()
            ->with(['pin'])
            ->where('id', '<>', $myId)
            ->whereNotIn('id', $myFriends->pluck('other_friends_id')->toArray())
            ->whereHas('pin', function ($query) {
                $query->where('created_at', '>=', now()->subMinutes(self::SEARCH_LIMIT_MINUTES));
            })
            ->get();
    }
    
    public function relationship()
    {
        return $this->hasMany(\App\Eloquents\FriendsRelationship::class, 'own_friends_id', 'id');
    }
    
    public function pin()
    {
        return $this->hasOne(\App\Eloquents\Pin::class, 'friends_id', 'id');
    }
}
