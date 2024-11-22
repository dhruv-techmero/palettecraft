<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Colour;
use App\Models\Like;
use App\Models\Palette;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PalleteController extends Controller
{
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $user_id = $request->cookie('user_identifier');
            $codes = $request->input('code') ? explode('-', $request->input('code')) : []; // Convert the string into an array
            $tags = $request->input('tags') 
            ? array_values(array_unique(explode(' ', $request->input('tags')))) 
            : [];
            
            $palette = Palette::where('color_1', $codes[0] ?? '#AAAAA')
                ->where('color_2', $codes[1] ?? '#BBBBB')
                ->where('color_3', $codes[2] ?? '#CCCCC')
                ->where('color_4', $codes[3] ?? '#DDDDD')
                ->first();
            if ($palette) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This palette was already submitted',
                    'id' => $palette->id
                ]);
            }

            $palette = DB::transaction(function () use ($request, $codes, $tags, $user_id) {
                foreach ($codes as $code) {
                    Colour::firstOrCreate(
                        ['code' => $code], // Condition to check uniqueness
                        ['code' => $code]  // Attributes to insert if it doesn't exist
                    );
                }
                foreach ($tags as $tag) {
                    Tag::firstOrCreate(
                        ['name' => $tag], // Condition to check uniqueness
                        ['name' => $tag]  // Attributes to insert if it doesn't exist
                    );
                }



                // Create the palette and store the result in $palette
                return Palette::create([
                    'user_id' => $user_id,
                    'code' => Str::uuid()->toString(),
                    'color_1' => $codes[0] ?? '#AAAAA',
                    'color_2' => $codes[1] ?? '#BBBBB',
                    'color_3' => $codes[2] ?? '#CCCCC',
                    'color_4' => $codes[3] ?? '#DDDDD',
               
                    'tags' => $tags
                ]);
            });

            if ($palette) {
                return response()->json([
                    'status' => 'success',
                    'redirect_url' => route('website-pallete-view', ['id' => $palette->id]),
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'An error occurred while creating the palette.',
                ]);
            }
            // return response()->json(['status' => 'success', 'id' => $palette->_id], 201);
        }
        return view('website.pallete.create');
    }

    public function view(Request $request)
    {

        $palette = Palette::find($request->id);

        if (!$palette) {
            return redirect()->route('website-home')->with('error', 'Palette not found');
        }
        // Prepare related data
        $tags = $palette->tags ?? [];
        $colors = [$palette->color_1, $palette->color_2, $palette->color_3, $palette->color_4];

        return view('website.pallete.view', compact('palette', 'tags', 'colors'));
    }


    public function collection(Request $request)
    {
        $user_id = $request->cookie('user_identifier');
        $collections = Like::with(['palette'])->where('user_id', $user_id)->get();
// dd($collections);
        return view('website.pallete.collection', [
            'collections' => $collections
        ]);
    }
    public function single(Request $request)
    {
        $palette = Palette::find($request->id);

        if (!$palette) {
            return redirect()->route('website-home')->with('error', 'Palette not found');
        }

        $tags = $palette->tags ?? [];
        $colors = [$palette->color_1, $palette->color_2, $palette->color_3, $palette->color_4];

        $related_palettes = Palette::where('_id', '<>', $palette->_id)
            ->where(function ($query) use ($tags) {
                if (!empty($tags)) {
                    $query->whereIn('tags', $tags);
                }
            })
            ->get();

        return view('website.pallete.single', compact('palette', 'tags', 'colors', 'related_palettes'));
    }
}
