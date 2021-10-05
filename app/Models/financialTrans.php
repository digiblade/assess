<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class financialTrans extends Model
{
    use HasFactory;
    protected $table = "financial_trans";
    protected $fillable = [
        'trans_date',
        'trans_session',
        'trans_rollno',
        'trans_admno',
        'trans_totalpaidamount',
        'fee_category_id',
        'branch_id',
        'fee_type_id',
        'trans_receipt',
        // 'entry_mode'

    ];
}
