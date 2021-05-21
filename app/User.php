<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property string $buxfer_token
 * @property string $telegram_id
 * @property string $account_id
 */
class User extends Authenticatable
{
    protected $guarded = [];

    public $timestamps = false;
}
