<?php

namespace App\Http\Controllers\API;

use App\Report;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;
use App\Citizen;

class ReportController extends Controller
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
    public function create($id)
    {
        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json(
        [
            'id' => $id,
            'access_token'=> $accessToken,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $report = new Report;
        $report -> citizen_id = $request['citizen_id'];

        $filePath = request('file') ->store('reports','public');
        $report -> report = $filePath;
        $report -> status = $request['status'];

        $report -> save();

        $citizen = Citizen::find($request['citizen_id']);
        $citizen -> health_status = $request['status'];

        $citizen -> save();


        return response()->json(
            [
                'Status'=> 'Added and Updated.',
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $filePath = DB::table('reports')
        ->where('id', $id)
        ->value('report');
        $download_path = ( public_path() .'/storage/'. $filePath);

        // $accessToken = $user->createToken('authToken')->accessToken;

        // $headers = $accessToken;

        return response()->file($download_path);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }
}
