<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AwbImportFailureLog extends Model
{
    protected $table = "awb_import_failures";
    protected $fillable = [
        "batch_id",
        "awb_number",
        "reason",
    ];
    public $timestamps = false;
    public function failed()
    {   
        return $this->hasMany("App\Models\AwbTracking","awb_number","awb_number");
    }           
    public function batch()
    {
        return $this->hasOne("App\Models\UploadBatch","id","batch_id");
    }
    public function failed_logs()
    {
        return $this->hasMany("App\Models\AwbImportFailureLog","batch_id","batch_id");
    }
}
