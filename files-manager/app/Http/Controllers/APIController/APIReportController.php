<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\User;

class APIReportController extends Controller
{
 

    public function countFile(Request $request)
    {
        $user = $request->user('sanctum');
        if (!$user) {
            return response()->json(['error' => 'Invalid token.'], 401);
        }
        try {
            $fileCount  = File::where('manager_id', $user->manager_id)->count();
                return response()->json([
                    'success' => true,
                    'message' => 'successfully',
                    'file_count'=>$fileCount
                ], 200);
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

    public function countUser(Request $request)
    {
        $user = $request->user('sanctum');
        if (!$user) {
            return response()->json(['error' => 'Invalid token.'], 401);
        }
        try {
            $userCount  = User::where('manager_id', $user->manager_id)->count();
                return response()->json([
                    'success' => true,
                    'message' => 'successfully',
                    'user_count'=>$userCount-1
                ], 200);
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


    public function reportFileUser(Request $request)
    {
        $user = $request->user('sanctum');
        if (!$user) {
            return response()->json(['error' => 'Invalid token.'], 401);
        }
        try {
            $manager = User::findOrFail($user->id);
            $files = File::with(['user' => function($query) {
                $query->select('id', 'name');
            }])->where('manager_id', $manager->manager_id)->get(['id','label','file_name', 'file_size','file_type', 'user_id', 'created_at']);
    
            return response()->json([
                'success' => true,
                'message' => 'Files retrieved successfully',
                'files' => $files
            ], 200);
        } catch (Exception $e) {
            $statusCode = 500;
            if ($e->getCode() >= 400 && $e->getCode() < 500) {
                $statusCode = $e->getCode();
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], $statusCode);
            }
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }


    public function reportUser(Request $request)
    {
        $user = $request->user('sanctum');
        if (!$user) {
            return response()->json(['error' => 'Invalid token.'], 401);
        }
        try {
            $allUser  = User::where('manager_id', $user->manager_id)->get(['id', 'name','email', 'created_at']);

            return response()->json([
                'success' => true,
                'message' => 'successfully',
                'users' => $allUser
            ], 200);
        } catch (Exception $e) {
            $statusCode = 500;
            if ($e->getCode() >= 400 && $e->getCode() < 500) {
                $statusCode = $e->getCode();
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], $statusCode);
            }
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }
    public function deleteUser(Request $request)
    {
        $user = $request->user('sanctum');
        if (!$user) {
            return response()->json(['error' => 'Invalid token.'], 401);
        }
        try {
           
            $deleteUser = User::where('manager_id', $user->manager_id)->where('id', $request->id)->first();
            $deleteUser->delete();
            return response()->json([
                'success' => true,
                'message' => 'successfully',
            ], 200);
        } catch (Exception $e) {
            $statusCode = 500;
            if ($e->getCode() >= 400 && $e->getCode() < 500) {
                $statusCode = $e->getCode();
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], $statusCode);
            }
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }
    
}
    
 











