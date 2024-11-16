<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ArchiveController extends Controller
{
    public function store(Request $request, $model, $id)
    {
        // Validate the uploaded image
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Verify the model class exists and uses the expected namespace
        if (!class_exists($model)) {
            return response()->json(['error' => 'Model class not found'], 400);
        }

        // Find the model instance
        $modelInstance = $model::find($id);
        if (!$modelInstance) {
            return response()->json(['error' => 'Model instance not found'], 404);
        }

        // Store the uploaded image
        $image = $request->file('image');
        $imagePath = $image->store('archives', 'public');

        // Create the archive record
        $modelInstance->archives()->create([
            'image_path' => $imagePath,
        ]);

        return response()->json([
            'message' => 'Image archived successfully',
            'image_path' => $imagePath,
        ], 201);
    }
}
