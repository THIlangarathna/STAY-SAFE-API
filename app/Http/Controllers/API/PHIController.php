<?php

namespace App\Http\Controllers\API;

use App\PHI;
use Illuminate\Http\Request;
use App\User;
use App\Citizen;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use DB;
use App\Http\Controllers\Controller;

class PHIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = auth()->user()->id;
        $user = User::find($id);
        $phi = PHI::where('user_id', '=', $id)->firstOrFail();

        $citizens = DB::table('citizens')
        ->join('users', 'citizens.user_id', '=', 'users.id')
        ->select('citizens.id','users.name','users.nic','citizens.dob','citizens.sex','citizens.mobile','citizens.health_status')
        ->orderBy('id', 'DESC')
        ->paginate(10);

        // $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response()->json([
            'user' => $user,
            'phi' => $phi,
            'citizens' => $citizens,
            // 'access_token' => $accessToken,
        ]
        );

    }

    public function search(Request $request)
    {
        $id = auth()->user()->id;
        $user = User::find($id);
        $phi = PHI::where('user_id', '=', $id)->firstOrFail();

        $nic = $request['nic'];
        $citizens = DB::table('citizens')
        ->join('users', 'citizens.user_id', '=', 'users.id')
        ->select('citizens.id','users.name','users.nic','citizens.dob','citizens.sex','citizens.mobile','citizens.health_status')
        ->where('users.nic','LIKE',"%$nic%")
        // ->paginate(10);
        ->get();

        // $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response()->json(
        [
            'user' => $user,
            'phi' => $phi,
            'citizens' => $citizens,
            // 'access_token' => $accessToken,
        ]);
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
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'category' => ['required', 'string', 'max:10'],
            'nic' => ['required', 'string', 'max:12'],
            'phi_id' => ['required', 'string', 'max:12'],
            'contact' => ['required', 'string', 'max:13'],
            'region' => ['required', 'string', 'max:255'],
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $user = new User;
        $user -> name = $request['name'];
        $user -> nic = $request['nic'];
        $user -> email = $request['email'];
        $user -> password = Hash::make($request['password']);
        $user -> category = $request['category'];

        $user->save();

        $user_id = User::where('nic', '=',  $request['nic'])
        ->value('id');

        $phi = new PHI;
        $phi-> user_id = $user_id;
        $phi -> phi_id = $request['phi_id'];
        $phi -> contact = $request['contact'];
        $phi -> region = $request['region'];

        $phi -> save();

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
 
        return response()->json(
        [
            'user'=> $user,
            'phi'=> $phi,
            'access_token'=> $accessToken,
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PHI  $pHI
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $phi = Phi::where('user_id', '=', $id)->first();


        return response()->json(
        [
        'user' => $user,
        'phi' => $phi,
        // 'access_token'=> $accessToken,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PHI  $pHI
     * @return \Illuminate\Http\Response
     */
    public function edit(PHI $pHI)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PHI  $pHI
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'contact' => ['required', 'string', 'max:13'],
            'region' => ['required', 'string', 'max:255'],
        ]);
        $id = $request['user_id'];
        $phi_id = PHI::where('user_id', '=', $id)
        ->value('id');
        $phi = PHI::find($phi_id);
        $phi -> contact = $request['contact'];
        $phi -> region = $request['region'];

        if ($request->hasFile('img')) {
            $user = User::find($id);
            $imagePath = request('img')->store('uploads','public');
            $user -> img = $imagePath;
            $user -> save();
            $phi -> save();
        }
        else{
            $phi -> save();
        }


        return response()->json(
        [
            'Status'=> 'Profile Updated.',
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PHI  $pHI
     * @return \Illuminate\Http\Response
     */
    public function destroy(PHI $pHI)
    {
        //
    }
}
