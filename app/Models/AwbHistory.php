<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AwbHistory extends Model
{
    protected $table = "awb_histories";

    protected $fillable = [
        "awb_tracking_id",
        "date",
        "code",
        "description",
        "photo1",
        "photo2", 
        "photo3",
        "photo4",
        "photo5"
    ];

    public function histories()
    {
        return $this->hasMany(AwbHistory::class);
    }
}
