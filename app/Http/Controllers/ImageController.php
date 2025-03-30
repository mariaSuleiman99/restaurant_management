<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ImageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // Handle image upload using the helper
        $image = $request->hasFile('image')
            ? ImageHelper::uploadImage($request->file('image'))
            : null;
        return ResponseHelper::success("Restaurant created successfully.", $image, 201);

    }
}
