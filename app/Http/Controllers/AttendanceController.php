<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Agreement;
use App\Attendance;
use App\Employee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $agreements =Agreement::all();
        $employees =Employee::all();
        return view('attendance')->with(['agreements' => $agreements, 'employees' => $employees]);
    }

    public function create(){
        $result = DB::table('attendances')
                        ->join('designations','attendances.designation_id','=','designations.id')
                        ->join('employees', 'attendances.employee_id','=','employees.id')
                        ->join('agreements', 'attendances.agreement_id','=','agreements.id')
                        ->select('attendances.*','designations.description as designation_des','employees.name as employee_name','agreements.agreement_no')
                        ->limit(10)
                        ->orderByRaw('attendances.date DESC')
                        ->get();

        return response()->json($result);

    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            
            'shift'=> 'required',
            'designation_id'=> 'required',
            'employee_id'=> 'required|nullable',
            'agreement_id'=> 'required|nullable',
            'date'=> 'required|date'
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $attendance = new Attendance;
                $attendance->designation_id = $request->designation_id;
                $attendance->employee_id = $request->employee_id;
                $attendance->agreement_id = $request->agreement_id;
                $attendance->shift = $request->shift;
                $attendance->ot_hours = $request->ot_hours;
                $attendance->operator = $request->operator;
                $attendance->date = $request->date;

                $attendance->save();

                DB::commit();
                return response()->json(['db_success' => 'Added New Attendance']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }
        

    }

    public function show($id){
        $result = Attendance::find($id);

        return response()->json($result);

    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'operator'=> 'required',
            'shift'=> 'required',
            'designation_id'=> 'required|nullable',
            'employee_id'=> 'required|nullable',
            'agreement_id'=> 'required|nullable',
            'date'=> 'required|date'
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $attendance = Attendance::find($request->id);
                $attendance->designation_id = $request->designation_id;
                $attendance->employee_id = $request->employee_id;
                $attendance->agreement_id = $request->agreement_id;
                $attendance->shift = $request->shift;
                $attendance->ot_hours = $request->ot_hours;
                $attendance->operator = $request->operator;
                $attendance->date = $request->date;

                $attendance->save();

                DB::commit();
                return response()->json(['db_success' => 'Attendance Updated']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }

    }

    public function destroy($id){
        $result = Attendance::destroy($id);

        return response()->json($result);

    }

    public function search(Request $request){

        $search = $request->data;
        $result = DB::table('attendances')
                    ->join('employees', 'attendances.employee_id','=','employees.id')
                    ->join('designations', 'attendances.designation_id','=','designations.id')
                    ->join('agreements', 'attendances.agreement_id','=','agreements.id')
                    ->where('attendances.date','like', '%'.$search.'%')
                    ->orWhere('employees.name', 'like', '%'.$search.'%')
                    ->orWhere('agreements.agreement_no', 'like', '%'.$search.'%')
                    ->select('attendances.*','designations.description as designation_des','employees.name as employee_name','agreements.agreement_no')
                    ->get();

        return response()->json($result);
       

    }
}
