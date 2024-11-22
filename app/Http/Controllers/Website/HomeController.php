<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Colour;
use App\Models\Like;
use App\Models\Palette;
use Illuminate\Http\Request;
use Carbon\Carbon;
use MongoDB\BSON\UTCDateTime;


class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->cookie('user_identifier'); // Get user ID from cookies
        $palettes = collect(); // Default to an empty collection
    
        if ($request->has('random') && $request->random) {
            $palettes = $this->getRandomPalettes();
        } elseif ($request->popular == 'all') {
            $palettes = $this->getPopularPalettes();
        } elseif ($request->popular == 'month') {
            $palettes = $this->getMonthlyPopularPalettes();
        } elseif ($request->popular == 'year') {
            $palettes = $this->getYearlyPopularPalettes();
        } elseif ($request->tags) {
            return $this->search($request); // If searching, handle via `search` method
        }elseif($request->sidebar_filter){
            $palettes = $this->TagFilter($request);
        }
         else {
            $palettes = $this->getAllPalettes();
        }
    
        // Regular view rendering
        return view('website.home', compact('palettes'));
    }
    private function search(Request $request)
    {
        $tags = $request->query('tags');
        $tagArray = explode('-', $tags);
    
        $palettes = Palette::whereIn('tags', $tagArray)
            ->get()
            ->map(function ($palette) {
                $palette->likes_count = Like::where('palette_code', $palette->code)->count();
                return $palette;
            });
    // dd(count($palettes));
            if ($request->ajax()) {
                $html = '';
                foreach ($palettes as $palette) {
                    $html .= view('components.palette', compact('palette'))->render();
                }
                return response()->json(['html' => $html]);
            }
    
        return view('website.home', compact('palettes'));
    }
    
        
     private function TagFilter(Request $request)
    {
        $tag = $request->sidebar_filter;
    
        $palettes = Palette::where('tags', $tag)
            ->get()
            ->map(function ($palette) {
                $palette->likes_count = Like::where('palette_code', $palette->code)->count();
                return $palette;
            });
    
          return $palettes;
    }
    
    
    
    // Fetch random palettes
    private function getRandomPalettes()
    {
        return Palette::raw(function ($collection) {
            return $collection->aggregate([
                ['$sample' => ['size' => 100]] // Adjust size as needed
            ]);
        });
    }



    // Fetch popular palettes (all-time)
    private function getPopularPalettes()
    {
        return Palette::raw(function ($collection) {
            return $collection->aggregate([
                [
                    '$lookup' => [
                        'from' => 'likes', // The 'likes' collection
                        'localField' => 'code', // Field in 'palettes'
                        'foreignField' => 'palette_code', // Field in 'likes'
                        'as' => 'likes', // Alias for the joined data
                    ],
                ],
                [
                    '$addFields' => [
                        'likes_count' => ['$size' => '$likes'], // Count likes
                    ],
                ],
                [
                    '$sort' => ['likes_count' => -1], // Sort descending by likes_count
                ],
            ]);
        });
    }

    // Fetch popular palettes for the current month
    private function getMonthlyPopularPalettes()
    {
        $startOfMonth = new UTCDateTime(Carbon::now()->startOfMonth()->timestamp * 1000);
        $endOfMonth = new UTCDateTime(Carbon::now()->endOfMonth()->timestamp * 1000);

        return $this->getPopularPalettesByDateRange($startOfMonth, $endOfMonth);
    }

    // Fetch popular palettes for the current year
    private function getYearlyPopularPalettes()
    {
        $startOfYear = new UTCDateTime(Carbon::now()->startOfYear()->timestamp * 1000);
        $endOfYear = new UTCDateTime(Carbon::now()->endOfYear()->timestamp * 1000);

        return $this->getPopularPalettesByDateRange($startOfYear, $endOfYear);
    }

    // Helper function to fetch palettes by a date range
    private function getPopularPalettesByDateRange($startDate, $endDate)
    {
        return Palette::raw(function ($collection) use ($startDate, $endDate) {
            return $collection->aggregate([
                [
                    '$lookup' => [
                        'from' => 'likes',
                        'localField' => 'code',
                        'foreignField' => 'palette_code',
                        'as' => 'likes',
                    ],
                ],
                [
                    '$addFields' => [
                        'filtered_likes' => [
                            '$filter' => [
                                'input' => '$likes',
                                'as' => 'like',
                                'cond' => [
                                    '$and' => [
                                        ['$gte' => ['$$like.created_at', $startDate]],
                                        ['$lte' => ['$$like.created_at', $endDate]],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    '$addFields' => [
                        'likes_count' => ['$size' => '$filtered_likes'],
                        'latest_like' => ['$max' => '$filtered_likes.created_at'],
                    ],
                ],
                [
                    '$addFields' => [
                        'latest_like' => ['$ifNull' => ['$latest_like', new UTCDateTime(0)]],
                    ],
                ],
                [
                    '$sort' => [
                        'likes_count' => -1, // Descending order by likes_count
                        'latest_like' => -1, // Descending order by latest_like
                    ],
                ],
            ]);
        });
    }

    // Fetch all palettes
    private function getAllPalettes()
    {
        return Palette::with(['likes'])->get();
    }
    public function like(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'status' => 'required|in:on,off',
        ]);

        $palette = Palette::where('code', $request->code)->first();
        if (!$palette) {
            return response()->json([
                'status' => 'error',
                'message' => 'Palette not found',
            ]);
        }

        $user_id = $request->cookie('user_identifier');

        if ($request->status == 'on') {
            Like::firstOrCreate([
                'user_id' => $user_id,
                'palette_code' => $palette->code,
            ]);
        } elseif ($request->status == 'off') {
            $deleted = Like::where('palette_code', $palette->code)
                ->where('user_id', $user_id)
                ->delete();

            if (!$deleted) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Like not found or already removed.',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid action.',
            ]);
        }

        // Calculate the total likes for the palette
        $totalLikes = Like::where('palette_code', $palette->code)->count();

        return response()->json([
            'status' => 'success',
            'total_likes' => $totalLikes,
        ]);
    }
}
