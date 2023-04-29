<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\File;
use Log;
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
        Log::warning("import file size $fileSize and the type $fileType");
        $file = new File;
        $file->label = $label;
        $file->file_name = $fileName;
        $file->file_path = $path;
        $file->file_size = $fileSize;
        $file->file_type = $fileType;
        $file->user_id = $user->id;
        $file->manager_id = $user->manager_id;

        $file->save();
        
        return response()->json([
            'success' => true,
            'message' => 'File imported successfully.',
        ], 200);
        } catch (\Exception $e) {
            Log::error("error import file ".$e->getMessage());
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
        $file = File::where('id', $request->id)->where('manager_id', $user->manager_id)->firstOrFail();
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
        Log::error("error export file ".$e->getMessage());

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
        $file = File::where('id', $request->id)->where('manager_id', $user->manager_id)->firstOrFail();
        if ($file) {
            $filePath = storage_path('app/' . $file->file_path);
            if (file_exists($filePath)) {
                unlink($filePath); ////to delete the file from the path
            }
            Log::warning("delete delete file   :  $file");

            $file->delete();
            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully',
            ], 200);
            Log::warning("delete file");
        } 
    } catch (Exception $e) {
        Log::error("error delete file ".$e->getMessage());
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



public function getFile(Request $request)
{
    $user = $request->user('sanctum');
    if (!$user) {
        return response()->json(['error' => 'Invalid token.'], 401);
    }
    try {
        $files = File::where('manager_id', $user->manager_id)->select('id', 'file_name','label', 'file_size', 'file_type')->get();
        return response()->json([
        'success' => true,
        'data' => $files,], 200);
     } catch (Exception $e) {
        Log::error("error get file ".$e->getMessage());
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


public function updateFile(Request $request)
{
    $user = $request->user('sanctum');
    if (!$user) {
        return response()->json(['error' => 'Invalid token.'], 401);
    }
    try {
        $file = File::where('id', $request->id)->where('manager_id', $user->manager_id)->firstOrFail();
        if ($file) {
            $file->label = $request->input('label'); 
            $file->file_name = $request->input('name'); 
            $file->save(); 
            return response()->json([
                'success' => true,
                'message' => 'File updated successfully',
            ], 200);
        }
    } catch (Exception $e) {
        Log::error("error update file ".$e->getMessage());
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
