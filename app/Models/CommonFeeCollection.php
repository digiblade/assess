<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommonFeeCollection extends Model
{
    use HasFactory;
    protected $table = "common_fee_collections";
    protected $fillable = [
        "module_id",
        "trans_id",
        "admno",
        "rollno",
        "amount",
        "brid",
        "academicyear",
        "financialyear",
        "displayreceiptno",
        "collection_totalpaidamount",
    ];
}
