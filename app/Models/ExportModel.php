<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportModel extends Model
{
    use HasFactory;
    protected $table = "export_models";
    protected $fillable = [
        'file_name',
        "is_import"
    ];
}
