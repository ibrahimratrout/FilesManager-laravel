<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Role;


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
            $isStaff = $user->hasRole('staff');
            $isAdmin = $user->hasRole('admin');
            $type="";

            if($isStaff)
               $type="staff";
            else if($isAdmin)
              $type="admin";
    
            return response()->json([
                'message' => 'Successfully logged in',
                'user' => $user,
                'email' => $user->email,
                'token' => $plainTextToken,
                'type' =>  $type
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
    





    public function registerManager(Request $request)
    {

        
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',

            ]);
            $randomNumber = random_int(100000, 999999);
            $managerId = $validatedData['email'] . $randomNumber;
            $managerIdString = strval($managerId);
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'manager_id' =>$managerIdString ,

            ]);
           
            $typeUser= 'admin';


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


    public function registerEmployee(Request $request)
    {

        $manager = $request->user('sanctum');
        if (!$manager) {
            return response()->json(['error' => 'Invalid token.'], 401);
        }
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
            

            ]);
           
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'manager_id' => $manager->manager_id,

            ]);
           

            $typeUser='staff';

            $user->attachRole($typeUser);

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


    public function logout(Request $request)
    {
        $user = $request->user('sanctum');
       
        if (!$user) {
            return response()->json(['error' => 'Invalid token.'], 401);
        }   
    
        $user->tokens()->delete();

        return response()->json(['message' => 'Logged out'], 200);
    }










}
