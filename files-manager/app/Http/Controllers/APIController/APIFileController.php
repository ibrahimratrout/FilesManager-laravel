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
        $label = $request->input('label');
        $file = $request->file('file');
        $fileName = $request->input('name');
        $path = $file->store('imported-files');  /////save the file to storage
        $fileSize = $file->getSize();
        $fileType = $file->getClientOriginalExtension();
        
        $file = new File;
        $file->label = $label;
        $file->file_name = $fileName;
        $file->file_path = $path;
        $file->file_size = $fileSize;
        $file->file_type = $fileType;
        $file->user_id = $user->id;
        $file->save();
        
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
public function exportFile(Request $request)
{
    $user = $request->user('sanctum');
    if (!$user) {
        return response()->json(['error' => 'Invalid token.'], 401);
    }
    try {
        $file = File::where('id', $request->id)->firstOrFail();
        if ($file) {
            $filePath = storage_path('app/'.$file->file_path);
            $fileLabel= $file->label;
            $fileName= $file->file_name;
            if (file_exists($filePath)) {
                $headers = [
                    'Content-Type' => 'application/' . $file->file_type,
                    'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                ];
                return response(file_get_contents($filePath), 200, $headers);
               // return response()->download($filePath, $fileLabel); //   show file and download 
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found',
                ], 404);
            }
        } 
    } catch (Exception $e) {
        $statusCode = 500;
        if ($e->getCode() >= 400 && $e->getCode() < 500) {
            $statusCode = $e->getCode();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ],  $statusCode );
        }  
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 404);
    }
}


public function deleteFile(Request $request)
{
    $user = $request->user('sanctum');
    if (!$user) {
        return response()->json(['error' => 'Invalid token.'], 401);
    }
    try {
            $file = File::where('id', $request->id)->where('user_id', $user->id)->firstOrFail();
            if ($file) {
            $filePath = storage_path('app/' . $file->file_path);
            if (file_exists($filePath)) {
                unlink($filePath); ////to delete the file from the path
            }
            $file->delete();
            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully',
            ], 200);
        } 
    } catch (Exception $e) {
        $statusCode = 500;
        if ($e->getCode() >= 400 && $e->getCode() < 500) {
            $statusCode = $e->getCode();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ],  $statusCode );
        }  
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 404);

    }
}
}
