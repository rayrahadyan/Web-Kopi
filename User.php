<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use Notifiable, SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'last_name', 'email', 'password',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function getFullNameAttribute()
  {
    if (is_null($this->last_name)) {
      return "{$this->name}";
    }

    return "{$this->name} {$this->last_name}";
  }

  /**
   * The products that belong to the User
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function products()
  {
    return $this->belongsToMany(Product::class, 'transactions', 'user_id', 'product_id')->withPivot('quantity', 'price')->withTimestamps();
  }
}
