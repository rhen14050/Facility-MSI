<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RapidxUser extends Model
{
    protected $table = 'users';
    protected $connection = 'mysql_rapidx';
}
