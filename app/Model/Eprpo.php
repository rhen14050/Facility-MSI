<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Eprpo extends Model
{
    protected $table = 'receiving_header';
    protected $connection = 'mysql_systemone_eprpo';

    // public function item_details(){
    // 	return $this->hasOne(Eprpo::class, 'id', 'user_id');
    // }
}
