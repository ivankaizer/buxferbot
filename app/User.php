<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;

/**
 * @property string $buxfer_token
 * @property string $telegram_id
 * @property string $account_id
 * @property Collection<Shortcut> $shortcuts
 */
class User extends Authenticatable
{
    protected $guarded = [];

    public $timestamps = false;

    public function shortcuts()
    {
        return $this->hasMany(Shortcut::class);
    }
}
