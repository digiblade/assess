<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\financialTrans;

class financialTransDetails extends Model
{
    use HasFactory;
    protected $table = "financial_trans_details";
    protected $fillable = [
        "financial_trans_id",
        "detail_admno",
        "detail_due_amount",
        "detail_paid_amount",
        "detail_concession_amount",
        "detail_scholarship_amount",
        "detail_reverse_concession_amount",
        "detail_write_offamount",
        "detail_adjusted_amount",
        "detail_refund_amount",
        "detail_fund_tranCfer_amount",
        "detail_remark",
        "entry_mode",
    ];
    public function getHead()
    {
        return $this->hasOne(financialTrans::class, "id", "financial_trans_id");
    }
}
