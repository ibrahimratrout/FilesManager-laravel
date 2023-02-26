<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\File;

class APIFileController extends Controller
{
  public function import(Request $request)
    {
        $user = $request->user('sanctum');
       
        if (!$user) {
            return response()->json(['error' => 'Invalid token.'], 401);
        }

      try {
        // Get the label and file
        $label = $request->input('label');
        $file = $request->file('file');
        
        // Save the file to storage
        $path = $file->store('imported-files');
        $fileSize = $file->getSize();
        $fileType = $file->getClientOriginalExtension();
        
        // Save the label and file metadata to the database (assuming you have a 'labels' table)
        $file = File::create([
            'label' => $label,
            'file_path' => $path,
            'file_size' => $fileSize,
            'file_type' => $fileType,
            'user_id' => $user->id,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'File imported successfully.',
        ], 200);
        } catch (\Exception $e) {
            $statusCode = 500;
            if ($e->getCode() >= 400 && $e->getCode() < 500) {
                $statusCode = $e->getCode();
            }
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
}


}
