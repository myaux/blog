<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content','user_id','blog_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //   protected $hidden = [
    //    'password', 'remember_token',
    //];
}


//class Comment extends Model
//{
//    //
//    use SoftDeletes;
//    //...其他一些设置
//    protected $dates = ['delete_at'];
//}
