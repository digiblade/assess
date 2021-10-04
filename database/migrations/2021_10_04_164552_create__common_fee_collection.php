<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommonFeeCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('common_fee_collections', function (Blueprint $table) {
            $table->id();
            $table->string("module_id");
            $table->string("trans_id");
            $table->string("admno");
            $table->string("rollno");
            $table->string("amount");
            $table->string("brid");
            $table->string("academicyear");
            $table->string("financialyear");
            $table->string("displayreceiptno");
            $table->string("collection_totalpaidamount");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('common_fee_collections');
    }
}
