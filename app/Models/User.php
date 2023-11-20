<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
 */
class User extends Authenticatable
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['username', 'name', 'email', 'email_verified_at', 'password', 'api_token', 'remember_token', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany('App\Models\Review', 'user_id');
    }
}
