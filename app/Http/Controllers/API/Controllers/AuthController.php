<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // public function register(Request $request)
    // {
    //      $validatedData = $request->validate([
    //          'name'=>'required|max:55',
    //          'email'=>'email|required|unique:users',
    //          'password'=>'required|confirmed'
    //      ]);
 
    //      $validatedData['password'] = bcrypt($request->password);
 
    //      $user = User::create($validatedData);
 
    //      $accessToken = $user->createToken('authToken')->accessToken;
 
    //      return response(['user'=> $user, 'access_token'=> $accessToken]);   
    // }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);
        
        if(!auth()->attempt($loginData)) {
            return response(['message'=>'Invalid credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        // $id = auth()->user()->id;

        // if(auth()->user()->category=='Citizen')
        // {
        //     return response(['user' => auth()->user()->category, 'access_token' => $accessToken]);
        //     // return redirect()->route('Citizen', ['access_token' =>$accessToken]);
        // }
        // else if(auth()->user()->category=='PHI')
        // {
        //     return response(['user' => auth()->user()->category, 'access_token' => $accessToken]);
        // }
        // else
        // {
        //     return response(['message'=>'Invalid credentials']);
        // }
        // return response(['user' => auth()->user(), 'access_token' => $accessToken]);

        return response(['user' => auth()->user()->category, 'access_token' => $accessToken]);
    }
    // public function getdata()
    // {
    //     return response()->json(['user' => auth()->user()], 200);
    // }
    public function logout()
    {
        auth()->user()->accessTokens()->delete();

        return response()->json(
            [
            'Status'=> 'Logged Out and Tokens Deleted.',
            ]);
    }
}
