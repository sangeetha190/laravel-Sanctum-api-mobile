<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    //register function
    public function register(Request $request)
    {

        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'c_password' => 'required|same:password'
            ]
        );

        //if any of the validate  above field is fails means this "if condition" will wrk

        if ($validate->fails()) {
            return response()->json(['message' => 'validator error'], 400);
        }

        $data = $request->all(); //getting all the values to store in DB.
        $data['password'] = Hash::make($data['password']); //hashing only password and store in DB.
        $user = User::create($data);

        // token will give the particular user datas
        $response['token'] = $user->createToken('Myapp')->plainTextToken;
        $response['name'] = $user->name;
        return response()->json($response, 200);
        // $user->save();
    }
    //login function
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $user = Auth::User();
            // token will give the particular user datas
            $response['token'] = $user->createToken('Myapp')->plainTextToken;
            $response['name'] = $user->name;
            return response()->json($response, 200);
        } else {
            return response()->json(['message' => 'Invalid Credentials error'], 400);
        }
    }

    //detail function
    public function detail()
    {
        // if the user login means this $user will get the data.
        //way 1
        // $user = Auth::user();
        // $response['user'] = $user;

        // Way 2
        $user = Auth::user();
        // only sending need field.
        // for More see the "API Resources in Laravel"
        $data = [
            'name' => $user->name,
            'email' => $user->email
        ];
        $response['user'] = $data;

        return response()->json($response, 200);
    }
}
