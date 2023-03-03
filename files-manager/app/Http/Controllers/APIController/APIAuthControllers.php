<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class APIAuthControllers extends Controller
{


    public function login(Request $request)
    {
      

        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6',
              

            ]);

            $data = $request->json()->all();
    
            if (!Auth::attempt($validatedData)) {
                return response()->json([
                    'message' => 'Invalid Credentials',
                ], 401);
            }
    
            $user = Auth::user();
    
            $token = $user->createToken('authToken');
    
         
            $plainTextToken = $token->plainTextToken;
            
    
            return response()->json([
                'message' => 'Successfully logged in',
                'user' => $user,
                'email'=>$user->email,
                'token'=>$plainTextToken
    
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error Occurred while login',
                'error' => $e->getMessage()
            ], 404);
        }
    }
    





    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
                'manager_id'=>'required|int',
                'type'=>'required|string',

            ]);
            $data = $request->json()->all();
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'manager_id' => $validatedData['manager_id'],

            ]);
            $typeUser= $validatedData['type'];


            $user->attachRole($typeUser);//type 
            return response()->json([
                'message' => 'Successfully created user!',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error Occurred while creating user',
                'error' => $e->getMessage()
            ], 404);
        }

    }
}
