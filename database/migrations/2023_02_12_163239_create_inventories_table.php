<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id('id');
            // $table->foreignId('part_id');
            $table->integer('type')->comment = '1=Die Parts; 2=Grinding End-mill';
            $table->integer('form')->default(1)->comment = '1=Inventory; 2=Withdrawal';
            $table->string('receiving_no')->nullable();
            $table->string('reference_no')->nullable();
            $table->integer('category');
            $table->integer('source')->comment = '1=Receiving No.; 2=Reference No.';
            $table->string('po_no')->nullable();
            $table->string('item_id');
            $table->string('item_name');
            $table->string('item_code')->nullable();
            $table->double('quantity');
            $table->double('unit_price');
            $table->datetime('received_date')->nullable();
            $table->double('input')->default(null)->nullable();
            $table->double('output')->default(null)->nullable();
            $table->double('boh')->default(0);
            $table->double('eoh')->default(0);
            $table->text('remarks')->nullable();
            $table->integer('status')->comment = '1=Active; 2=Archived';
            $table->bigInteger('created_by');
            $table->bigInteger('last_updated_by');
            $table->bigInteger('checked_by')->nullable();
            $table->integer('approval_status')->default(1)->nullable()->comment = '0=Pending; 1=Approved; 2=Disapproved';
            $table->dateTime('approved_at')->nullable();
            $table->tinyInteger('logdel')->default(0);
            $table->timestamps();

            // $table->foreign('part_id')->references('id')->on('parts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
    }
}
