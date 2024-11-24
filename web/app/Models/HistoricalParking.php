<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricalParking extends Model
{
    use HasFactory;

    protected $fillable = ['parking_id', 'occupied'];
    
    public function parking()
    {
        return $this->belongsTo(Parking::class);
    }
}
