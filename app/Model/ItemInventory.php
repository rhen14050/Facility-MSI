<?php

namespace App\Model;

use App\Model\ItemTransactionDetails;
use Illuminate\Database\Eloquent\Model;


class ItemInventory extends Model
{
    protected $table = 'item_inventory';

     public function item_transaction_details(){
        return $this->hasMany(ItemTransactionDetails::class, 'item_inventory_id', 'id');
    }

}
