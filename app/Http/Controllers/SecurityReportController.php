<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Agreement;
use App\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SecurityReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $agreements = Agreement::all();
        $employees = Employee::all();
        return view('security_report')->with(['agreements' => $agreements, 'employees' => $employees]);
    }

    public function daysheet_emp_wise(Request $request){

      $validator = Validator::make($request->all(), [
                  'from' => 'required|date',
                  'to' => 'required|date',
                  'agreement_id' => 'required|nullable']);

      if($validator->fails()){

        return redirect('security_report')->withErrors($validator);

      }else{
        $from=$request->from;
        $to=$request->to;
        $agreement_id=$request->agreement_id;

        $result = DB::table('attendances')
                        ->join('employees', 'attendances.employee_id','=', 'employees.id')
                        ->select('attendances.employee_id',
                        'employees.name',
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 1 THEN attendances.shift END) ,'-') AS '1' "),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 2 THEN attendances.shift END) ,'-') AS '2'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 3 THEN attendances.shift END) ,'-') AS '3'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 4 THEN attendances.shift END) ,'-') AS '4'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 5 THEN attendances.shift END) ,'-') AS '5'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 6 THEN attendances.shift END) ,'-') AS '6'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 7 THEN attendances.shift END) ,'-') AS '7'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 8 THEN attendances.shift END) ,'-') AS '8'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 9 THEN attendances.shift END) ,'-') AS '9'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 10 THEN attendances.shift END) ,'-') AS '10'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 11 THEN attendances.shift END) ,'-') AS '11'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 12 THEN attendances.shift END) ,'-') AS '12'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 13 THEN attendances.shift END) ,'-') AS '13'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 14 THEN attendances.shift END) ,'-') AS '14'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 15 THEN attendances.shift END) ,'-') AS '15'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 16 THEN attendances.shift END) ,'-') AS '16'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 17 THEN attendances.shift END) ,'-') AS '17'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 18 THEN attendances.shift END) ,'-') AS '18'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 19 THEN attendances.shift END) ,'-') AS '19'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 20 THEN attendances.shift END) ,'-') AS '20'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 21 THEN attendances.shift END) ,'-') AS '21'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 22 THEN attendances.shift END) ,'-') AS '22'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 23 THEN attendances.shift END) ,'-') AS '23'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 24 THEN attendances.shift END) ,'-') AS '24'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 25 THEN attendances.shift END) ,'-') AS '25'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 26 THEN attendances.shift END) ,'-') AS '26'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 27 THEN attendances.shift END) ,'-') AS '27'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 28 THEN attendances.shift END) ,'-') AS '28'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 29 THEN attendances.shift END) ,'-') AS '29'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 30 THEN attendances.shift END) ,'-') AS '30'"),
                        DB::raw("IFNULL(SUM(CASE WHEN DAY(attendances.date) = 31 THEN attendances.shift END) ,'-') AS '31'"))
                      ->whereBetween('attendances.date', [$from, $to])
                      ->where('attendances.agreement_id',$agreement_id)
                      ->groupBy('attendances.employee_id','attendances.shift','employees.name','attendances.agreement_id')
                      ->get();

        $count = $result->count();

        if($count>0){
          
        $data = array('data' => $result,
                      'from' => $from,
                      'to' => $to);

        view()->share('employee',$data);
        $pdf = PDF::loadView('reports/daysheet_emp', $data)->setPaper('A4', 'landscape');
    
          
        return $pdf->download('daysheet_emp_wise_'.date("Y-m-d H:i:s").'.pdf');

        }else{
          return redirect('security_report')->withErrors('No Records');
        }

      }
    }

    public function monthly_advance_attendance(){
      $data = array();
      view()->share('attendance',$data);
        $pdf = PDF::loadView('reports/monthly_advance_attendance', $data)->setPaper('A4', 'portrait');
    
          
        return $pdf->download('monthly_advance_attendance_'.date("Y-m-d H:i:s").'.pdf');

    }
}
