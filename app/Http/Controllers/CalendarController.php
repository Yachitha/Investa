<?php

namespace App\Http\Controllers;

use App\Calendar;
use Carbon\Carbon;
use function GuzzleHttp\Promise\task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function createCalendar (Request $request)
    {
        try {
            $date = $request['date'];
            $holiday = $request['holiday'];

            $calendar = Calendar::create ( [
                'date' => Carbon::parse ( $date )->format ( 'Y-m-d' ) ,
                'holiday' => $holiday
            ] );
        } catch (\Exception $e) {
            return response ()->json ( [
                'error' => true ,
                'message' => "error occurred!" ,
                'exception' => $e->getMessage ()
            ] );
        }
        if ( $calendar != null ) {
            return response ()->json ( [
                'error' => false ,
                'calendar' => $calendar
            ] );
        } else {
            return response ()->json ( [
                'error' => true ,
                'message' => "Error Occurred!"
            ] );
        }
    }

    private function checkEntry (String $date)
    {
        try {
            $checkDate = Carbon::parse ( $date )->format ( 'Y-m-d' );
            $isExisting = DB::table ( 'calendar' )->where ( 'date' , $checkDate )->exists ();
        } catch (\Exception $e) {

        }
    }

    public function getDates ()
    {
        try {
            $dates = DB::table ('calendar')->get();
            return response ()->json ([
               'error'=>false,
               'dates'=>$dates
            ]);
        } catch (\Exception $e) {

            return response ()->json ( [
                'error'=>true,
                'message'=>"error occurred"
            ] ,500);
        }
    }

    public function deleteDate(Request $request) {
        try {
            $id = $request['id'];
            $record = DB::table ('calendar')->where ('id',$id)->exists ();
            if ($record) {
                DB::table ('calendar')->delete ($id);
                return response ()->json ([
                   'error'=>false,
                   'message'=>"successfully removed"
                ]);
            } else{
                return response ()->json ([
                    'error'=>true,
                    'message'=>"record not exists maybe deleted"
                ]);
            }
        } catch (\Exception $e) {
            return response ()->json ([
                'error'=>true,
                'message'=>"error occurred"
            ]);
        }
    }
}
