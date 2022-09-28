<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Class LogController constructor
     */
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('can:menu-cadastro');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $logs = Log::select('logs.*', 'users.name as user_name')->join('users', 'users.id', 'logs.user_id')->get();
        // foreach ($logs as $log) {
        //     $beforeChange = '';
        //     $afterChange = '';

        //     if (!empty($changeFrom)) {
        //         $changeFrom = (array)json_decode($log['detail'])->change_from;
        //         $changeTo = (array)json_decode($log['detail'])->change_to;
        //         $changed = array_diff($changeFrom, $changeTo);

        //         foreach ($changed as $key => $value) {
        //             $beforeChange .= "{$key} = {$changeFrom[$key]}<br>";
        //             $afterChange .= "{$key} = {$changeTo[$key]}<br>";
        //         }
        //     }

        //     $afterChange = (array)json_decode($log['detail']);

        //     $log['beforeChange'] = $beforeChange;
        //     $log['afterChange'] = $afterChange;
        // }

        return view('logs.index', [
            'logs' => $logs
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
}