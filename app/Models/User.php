<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property integer $id
 * @property string $username
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property string $api_token
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property integer $paid
 * @property integer $active
 * @property string $payment_date
 * @property string $last_game_time
 * @property integer $games_left
 * @property GameUser[] $gameUsers
 * @property PrivateRoom[] $privateRooms
 * @property Rating[] $ratings
 */
class User extends Authenticatable
{
    /**
     * @var array
     */
    protected $fillable = ['username', 'name', 'email', 'email_verified_at', 'password', 'api_token', 'remember_token', 'created_at', 'updated_at', 'paid', 'active', 'payment_date'];

}
