<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AwbTracking extends Model
{
    protected $table = "awb_trackings";
    protected $fillable = [
        "awb_number",
        "status_code",
        "status_label", 
        "last_checked_at",
        "delivered_at",
        "pod_receiver",
        "is_completed",
        "uploaded_by",
        "batch_id"
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function batch()
    {
        return $this->belongsTo(UploadBatch::class, 'batch_id');
    }

    public function latestStatusCode()
    {
        return optional($this->histories()->latest('date')->first())->code;
    }

    public function detailInfo()
    {
        return $this->hasOne(AwbDetailInfo::class);
    }

    public function histories()
    {
        return $this->hasMany(AwbHistory::class);
    }

    public function photoHistories()
    {
        return $this->hasMany(AwbPhotoHistory::class);
    }


}
