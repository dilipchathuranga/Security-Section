<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;
use App\Designation;
use App\Employee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $designation = Designation::all();
        $supplier = Supplier::all();
        return view('employee')->with(['designations' => $designation, 
                                       'suppliers' => $supplier]);
    }

    public function create(){
        $result = DB::table('employees')
                        ->join('designations','employees.designation_id','=','designations.id')
                        ->join('suppliers', 'employees.supplier_id','=','suppliers.id')
                        ->select('employees.*','designations.description as designation_des','suppliers.name as supplier_des')
                        ->limit(10)
                        ->get();

        return response()->json($result);

    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            
            'tele_no'=> 'required|numeric|min:10',
            'address'=> 'required|min:5',
            'name'=> 'required|min:2',
            'supplier_id'=> 'required|nullable',
            'designation_id'=> 'required|nullable',
            'nic'=> 'required|min:9'
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $employee = new Employee;
                $employee->designation_id = $request->designation_id;
                $employee->supplier_id = $request->supplier_id;
                $employee->nic = $request->nic;
                $employee->name = $request->name;
                $employee->address = $request->address;
                $employee->tele_no = $request->tele_no;

                $employee->save();

                DB::commit();
                return response()->json(['db_success' => 'Added New Employee']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }
        

    }

    public function show($id){
        $result = Employee::find($id);

        return response()->json($result);

    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'tele_no'=> 'required|numeric|min:10',
            'address'=> 'required|min:5',
            'name'=> 'required|min:2',
            'supplier_id'=> 'required',
            'designation_id'=> 'required',
            'nic'=> 'required|min:9'
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $employee = Employee::find($request->id);
                $employee->designation_id = $request->designation_id;
                $employee->supplier_id = $request->supplier_id;
                $employee->nic = $request->nic;
                $employee->name = $request->name;
                $employee->address = $request->address;
                $employee->tele_no = $request->tele_no;

                $employee->save();

                DB::commit();
                return response()->json(['db_success' => 'Employee Updated']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }

    }

    public function destroy($id){
        $result = Employee::destroy($id);

        return response()->json($result);

    }

    public function search(Request $request){

        $search = $request->data;
        $result = DB::table('employees')
                    ->join('designations','employees.designation_id','=','designations.id')
                    ->join('suppliers', 'employees.supplier_id','=','suppliers.id')
                    ->where('employees.name','like', '%'.$search.'%')
                    ->orWhere('employees.nic', 'like', '%'.$search.'%')
                    ->select('employees.*','designations.description as designation_des','suppliers.name as supplier_des')
                    ->get();

        return response()->json($result);
       

    }
}
