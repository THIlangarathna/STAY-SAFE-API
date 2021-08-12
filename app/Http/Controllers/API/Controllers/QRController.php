<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\QR_Location;
use App\Citizen;
use App\Citizen_Location;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\Controller;

class QRController extends Controller
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
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:13'],
            'address' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'string', 'max:20'],
            'longitude' => ['required', 'string', 'max:20'],
        ]);
        $qr = new QR_Location;
        $qr -> name = $request['name'];
        $qr -> contact = $request['contact'];
        $qr -> address = $request['address'];
        $qr -> latitude = $request['latitude'];
        $qr -> longitude = $request['longitude'];

        $qr -> save();

        $id = QR_Location::where('latitude', '=', $request['latitude'])
        ->where('longitude', '=', $request['longitude'])
        ->value('id');

        // return redirect()->route('QR', ['id' => $id]);


        // return view('QR.View',
        // [
        // 'id' => $id,
        // 'name' => $request['name'],
        // ]);
        $url = ("http://127.0.0.1:8000/ScanQR$id");
        return QrCode::size(500)->backgroundColor(255,255,255)->generate($url);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return view('QR.View',
        // [
        // 'id' => $id,
        // ]);
        $url = ("http://127.0.0.1:8000/ScanQR$id");
        return QrCode::size(500)->backgroundColor(255,255,255)->generate($url);
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
    public function update($id)
    {
        $user_id = auth()->user()->id;
        $citizen_id = Citizen::where('user_id', '=', $user_id)
        ->value('id');
        $latitude = QR_Location::where('id', '=', $id)
        ->value('latitude');
        $longitude = QR_Location::where('id', '=', $id)
        ->value('longitude');

        $citizen = Citizen::find($citizen_id);
        $citizen -> cl_latitude = $latitude;
        $citizen -> cl_longitude = $longitude;

        $citizen->save();

        $location =  new Citizen_Location;
        $location-> citizen_id = $citizen_id;
        $location -> latitude = $latitude;
        $location -> longitude = $longitude;

        $location -> save();


        return response()->json(
            [
                'Status'=> 'Added and Updated.',
            ]);

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
}
