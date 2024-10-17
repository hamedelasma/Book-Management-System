<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploaderController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,png,jpeg', 'max:2048'],
        ]);
        $path = $request->file('file')->store('images');

        return response()->json([
            'path' => $path,
            'url' => asset('storage/' . $path)
        ]);


    }
}
