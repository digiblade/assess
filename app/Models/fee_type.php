<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fee_type extends Model
{
    use HasFactory;
    protected $table = "fee_types";
    protected $fillable = ['branch_id', "fee_category_id", "fee_type","collection_type"];
}
