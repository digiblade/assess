<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\financialTrans;

class CreateFinancialTransDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_trans_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(financialTrans::class);
            $table->string("detail_admno");
            $table->string("detail_due_amount");
            $table->string("detail_paid_amount");
            $table->string("detail_concession_amount");
            $table->string("detail_scholarship_amount");
            $table->string("detail_reverse_concession_amount");
            $table->string("detail_write_offamount");
            $table->string("detail_adjusted_amount");
            $table->string("detail_refund_amount");
            $table->string("detail_fund_tranCfer_amount");
            $table->string("detail_remark");
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
        Schema::dropIfExists('financial_trans_details');
    }
}
