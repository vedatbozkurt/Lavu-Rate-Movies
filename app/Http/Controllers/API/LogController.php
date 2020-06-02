<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Admin');
    }

    public function index()
    {
        $logs = Activity::orderBy('id', 'desc')->paginate(25);
        return response()->json($logs);
    }
    public function deleteLog($id)
    {
      $log = Activity::find($id);
      $log->delete();
      return response()->json('successfully deleted');
  }

  public function deleteAllLogs()
  {
    Activity::truncate();
    return response()->json('successfully deleted');
}

}
