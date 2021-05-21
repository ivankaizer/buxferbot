<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $short_name
 * @property string $category_name
 */
class Shortcut extends Model
{
    public $timestamps = false;

    protected $guarded = [];
}
