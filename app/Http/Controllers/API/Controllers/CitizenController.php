<?php

namespace App\Http\Controllers\API;

use App\Citizen;
use Illuminate\Http\Request;
use App\User;
use App\Report;
use App\Citizen_Location;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use DB;
use App\Http\Controllers\Controller;

class CitizenController extends Controller
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
        $citizen = Citizen::where('user_id', '=', $id)->firstOrFail();
        $citizen_id = Citizen::where('user_id', '=', $id)
        ->value('id');
        $reports = Report::where('citizen_id','=',$citizen_id)->get();
        $locations = Citizen_Location::where('citizen_id','=',$citizen_id)
        ->get();
        // ->paginate(15);

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        // $accessToken = Auth::user()->token();

        return response()->json([
            'user' => $user,
            'citizen' => $citizen,
            'reports' => $reports,
            'locations' => $locations,
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
            'dob' => ['required', 'date',],
            'sex' => ['required', 'string', 'max:255'],
            'nic' => ['required', 'string', 'max:12'],
            'mobile' => ['required', 'string', 'max:13'],
            'address' => ['required', 'string', 'max:255'],
            'profession' => ['required', 'string', 'max:255'],
            'affiliation' => ['required', 'string', 'max:255'],
            'cl_latitude' => ['required', 'string', 'max:20'],
            'cl_longitude' => ['required', 'string', 'max:20'],
            'health_status' => ['required', 'string', 'max:20'],
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

        $citizen = new Citizen;
        $citizen-> user_id = $user_id;
        $citizen -> dob = $request['dob'];
        $citizen -> sex = $request['sex'];
        $citizen -> mobile = $request['mobile'];
        $citizen -> address = $request['address'];
        $citizen -> profession = $request['profession'];
        $citizen -> affiliation = $request['affiliation'];
        $citizen -> cl_latitude = $request['cl_latitude'];
        $citizen -> cl_longitude = $request['cl_longitude'];
        $citizen -> health_status = $request['health_status'];

        $citizen -> save();

        $citizen_id = Citizen::where('nic', '=',  $request['nic'])
        ->value('id');

        $location =  new Citizen_Location;
        $location-> citizen_id = $citizen_id;
        $location -> latitude = $request['cl_latitude'];
        $location -> longitude = $request['cl_longitude'];

        $location -> save();

 
        return response()->json([
            'user'=> $user,
            'citizen'=> $citizen,
            // 'access_token'=> $accessToken,
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Citizen  $citizen
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_id = DB::table('citizens')
        ->where('id', $id)
        ->value('user_id');
        $user = User::find($user_id);
        $citizen = Citizen::where('user_id', '=', $user_id)->first();
        $reports = Report::where('citizen_id','=', $id)->get();


        return response()->json(
        [
        'user' => $user,
        'citizen' => $citizen,
        'reports' => $reports,
        // 'access_token'=> $accessToken,
        ]);
    }

    public function showloc($id)
    {
        $user = User::find($id);
        $citizen = Citizen::where('user_id', '=', $id)->first();


        return response()->json(
        [
        'user' => $user,
        'citizen' => $citizen,
        // 'access_token'=> $accessToken,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Citizen  $citizen
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        // 
    }

    public function showcitizenprofile($id)
    {
        $user = User::find($id);
        $citizen = Citizen::where('user_id', '=', $id)->first();


        return response()->json(
        [
        'user' => $user,
        'citizen' => $citizen,
        // 'access_token'=> $accessToken,
        ]);
    }

    public function editcitizenprofile(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:13'],
            'address' => ['required', 'string', 'max:255'],
            'profession' => ['required', 'string', 'max:255'],
            'affiliation' => ['required', 'string', 'max:255'],
            'health_status' => ['required', 'string', 'max:20'],
        ]);
        $id = $request['user_id'];
        $citizen_id = Citizen::where('user_id', '=', $id)
        ->value('id');
        $citizen = Citizen::find($citizen_id);
        $citizen -> mobile = $request['mobile'];
        $citizen -> address = $request['address'];
        $citizen -> profession = $request['profession'];
        $citizen -> affiliation = $request['affiliation'];
        $citizen -> health_status = $request['health_status'];

        if ($request->hasFile('img')) {
            $user = User::find($id);
            $imagePath = request('img')->store('uploads','public');
            $user -> img = $imagePath;
            $user -> save();
            $citizen -> save();
        }
        else{
            $citizen -> save();
        }

        return response()->json(
        [
        'Status'=> 'Profile Updated.',
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Citizen  $citizen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $validatedData = $request->validate([
            'cl_latitude' => ['required', 'string', 'max:20'],
            'cl_longitude' => ['required', 'string', 'max:20'],
        ]);
        $citizen_id = Citizen::where('user_id', '=', $id)
        ->value('id');
        $citizen = Citizen::find($citizen_id);
        $citizen -> cl_latitude = $request['cl_latitude'];
        $citizen -> cl_longitude = $request['cl_longitude'];

        $citizen->save();

        $location =  new Citizen_Location;
        $location-> citizen_id = $citizen_id;
        $location -> latitude = $request['cl_latitude'];
        $location -> longitude = $request['cl_longitude'];

        $location -> save();


        return response()->json(
        [
        'Status'=> 'Location Updated.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Citizen  $citizen
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $citizen = Citizen::find($id);
        $user_id = Citizen::where('id', '=', $id)
        ->value('user_id');

        $user = User::find($user_id);
        $citizen->delete();
        $user ->delete();
        

        return response()->json(
        [
        'Status'=> 'Citizen Account Deleted.',
        ]);
    }
    public function contacts($id)
    {
        $user_id = Citizen::where('id', '=', $id)
        ->value('user_id');
        $mobile = Citizen::where('id', '=', $id)
        ->value('mobile');
        $email = User::where('id','=', $user_id)
        ->value('email');
        
        // return [$mobile,$email];
        return response()->json(
            [
            'Contact' => $mobile,
            'Email' => $email,
            ]);
    }
    public function positive($id)
    {
        $status = 'Positive';
        $citizen = Citizen::find($id);
        $citizen -> health_status = $status;

        $citizen -> save();

        return redirect()->back();
    }
    public function negative($id)
    {
        $status = 'Negative';
        $citizen = Citizen::find($id);
        $citizen -> health_status = $status;

        $citizen -> save();

        return redirect()->back();
    }
    public function recovered($id)
    {
        $status = 'Recovered';
        $citizen = Citizen::find($id);
        $citizen -> health_status = $status;

        $citizen -> save();

        return redirect()->back();
    }
}
