<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Designation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('designation');
    }

    public function create(){
        
        $result = Designation::all();

        return response()->json($result);

    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'description'=> 'required|min:5'
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $designation = new Designation;
                $designation->description = $request->description;

                $designation->save();

                DB::commit();
                return response()->json(['db_success' => 'Added New Designation']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }
        

    }

    public function show($id){
        $result = Designation::find($id);

        return response()->json($result);

    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'description'=> 'required|min:5'
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $designation = Designation::find($request->id);
                $designation->description = $request->description;

                $designation->save();

                DB::commit();
                return response()->json(['db_success' => 'Designation Updated']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }

    }

    public function destroy($id){
        $result = Designation::destroy($id);

        return response()->json($result);

    }

    public function search(Request $request){

        $search = $request->data;
        $result = DB::table('designations')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->get();

        return response()->json($result);
       

    }
}
