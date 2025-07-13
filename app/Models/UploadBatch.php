<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadBatch extends Model
{
    protected $table = "upload_batches";

    protected $fillable = ["user_id","total_rows","inserted","failed","uploaded_at"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function awbTrackings()
    {
        return $this->hasMany(AwbTracking::class, 'batch_id');
    }
}
