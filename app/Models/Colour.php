<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Colour extends Model
{
    use HasFactory;
    protected $connection = 'mongodb'; // Specify MongoDB connection
    protected $collection = 'colours'; // Collection name
    protected $fillable  = [
        'code'
    ];

    // public static function ColourName($code)
    // {
    //     $filePath = public_path('website-assets/json/colours.json');
    
    //     // Check if the file exists
    //     if (!file_exists($filePath)) {
    //         return 'File not found';
    //     }
    
    //     // Decode the JSON file
    //     $colorData = json_decode(file_get_contents($filePath), true);
    
    //     // Ensure the JSON is decoded properly
    //     if (json_last_error() !== JSON_ERROR_NONE) {
    //         return 'Invalid JSON file';
    //     }
    
    //     // Iterate through the colors array to find the matching hex code
    //     foreach ($colorData['colors'] as $color) {
    //         if ($color['hex'] === '#' . $code) {
    //             $formattedName = strtolower(str_replace(' ', '-', $color['name']));
    //             return $formattedName;
    //         }
    //     }
    
    //     // Return default if no match is found
    //     return 'Unknown-Color';
    // }
    
    
}
