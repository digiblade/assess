<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fee_category extends Model
{
    use HasFactory;
    protected $table = "fee_categories";
    protected $fillable = ['branch_id', "fee_category"];
    public  function getBranch()
    {
        return $this->hasOne(Branch::class, "id", "branch_id");
    }
    public function getBranchByName($name)
    {
        return $this->getBranch()->where("branch_name", "=", $name);
    }
}
