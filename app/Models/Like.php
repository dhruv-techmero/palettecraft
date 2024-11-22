<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    protected $connection = 'mongodb'; // Specify MongoDB connection
    protected $collection = 'likes'; // Collection name
    protected $casts = [
        'palette_code' => 'string',
    ];
    protected $fillable = [
        'user_id','palette_code'
    ];

    public function palette() {
        return $this->hasOne(Palette::class, 'code', 'palette_code');
    }
    public static function userLiked($code,$user_id){

        $status = self::where('palette_code',$code)->where('user_id',$user_id)->exists();

        return $status;

    }    
}
