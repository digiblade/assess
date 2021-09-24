<?php

use App\Models\Branch;
use App\Models\fee_category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialTansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_trans', function (Blueprint $table) {
            $table->id();
            $table->string("trans_date");
            $table->string("trans_session");
            $table->string("trans_rollno");
            $table->string("trans_admno");
            $table->foreignIdFor(fee_category::class);
            $table->foreignIdFor(Branch::class);
            $table->string("trans_totalpaidamount");
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
        Schema::dropIfExists('financial_trans');
    }
}
