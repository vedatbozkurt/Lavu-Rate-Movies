<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Admin');
    }

    public function getBackups()
    {
        $files = Storage::allFiles('Laravel');
        return response()->json($files);
    }

    public function createBackup()
    {
        //php artisan backup:run
        \Artisan::call('backup:run');
        return response()->json('Success');

    }

    public function downloadBackup(Request $request)
    {
        return Storage::download($request->name);
    }

    public function deleteBackup(Request $request)
    {
       return Storage::delete($request->name);
       // return Storage::disk('Laravel')->delete($request->name);


    }


}
