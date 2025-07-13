<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AwbPhotoHistory extends Model
{
    protected $table = "awb_photo_histories";

    protected $fillable = [
        "awb_tracking_id",
        "date",
        "photo1",
        "photo2",
        "photo3", 
        "photo4",
        "photo5"
    ];

    // di model AwbTracking.php
    public function photoHistories()
    {
        return $this->hasMany(AwbPhotoHistory::class);
    }

}
