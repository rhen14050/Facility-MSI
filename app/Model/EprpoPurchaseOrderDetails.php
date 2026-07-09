<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EprpoPurchaseOrderDetails extends Model
{
    protected $table = 'purchase_order_details';
    protected $connection = 'mysql_systemone_eprpo';

}
