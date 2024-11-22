<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class Palette extends Model
{
    use HasFactory;
    protected $connection = 'mongodb'; // Specify MongoDB connection
    protected $collection = 'palettes'; // Collection name
    protected $casts = [
        'pallete_id' => 'string',
    ];
    protected $fillable = [
        'user_id',
        'code',
        'color_1',
        'color_2',
        'color_3',
        '_id',
        'color_4',
        'tags',
    ];

    public function likes(){
        return $this->hasMany(Like::class,'user_id','user_id');
    }

    public static function likesCount($id){

        $count =  Like::where('palette_code',$id)->count();
        if($count > 0){
            return $count;
        }else{
            return 0;
        }
    }
}
