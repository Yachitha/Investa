<?php
/**
 * Created by PhpStorm.
 * User: Yachitha Sandaruwan
 * Date: 11/22/2018
 * Time: 10:19 PM
 */

namespace App\Http\Controllers\Reports\DaySheet;


use App\Http\Controllers\Reports\ReportController;


class DaySheet extends ReportController
{
    public function createPdf ()
    {
        $pdf = \app ('dompdf.wrapper')->loadView('Reports.DaySheet.daySheet');
        return $pdf->download ('daySheet.pdf');
    }

    public function getDaySheetData() {
        $array["daily_payments"] = $this->createFakeData();
        return response()->json([
                'error'=>false,
                'data'=>collect($array)
            ]);
    }

    private function createFakeData() {
        $array = [];
        for ($i=0; $i<500; $i++) {
            $item = [
                'id'=>$i+800,
                'name'=>"south contest".$i,
                'amount'=>"-"
            ];
            $array[] = $item;
        }
        $group = collect($array)->split(count($array)/53)->toArray();
        return $group;
    }
}
