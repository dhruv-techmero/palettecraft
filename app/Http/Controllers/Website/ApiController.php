<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Exception;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function collection(Request $request)
    {
        $user_id = $request->cookie('user_identifier');
        try {

            $collections = Like::with(['palette'])->where('user_id', $user_id)->get();
            // dd(count($collections));
            return response()->json([
                'status' => 'success',
                'collections' => $collections
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
