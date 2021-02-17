<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('supplier');
    }

    public function create(){
        $result = Supplier::all();

        return response()->json($result);

    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'email'=> 'required|email',
            'tele_no'=> 'required|numeric|min:10',
            'address'=> 'required|min:5',
            'name'=> 'required|min:2',
            'bp_no'=> 'required|min:2',
            'br_no'=> 'required|min:5'
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $supplier = new Supplier;
                $supplier->br_no = $request->br_no;
                $supplier->bp_no = $request->bp_no;
                $supplier->name = $request->name;
                $supplier->address = $request->address;
                $supplier->email = $request->email;
                $supplier->tele_no = $request->tele_no;

                $supplier->save();

                DB::commit();
                return response()->json(['db_success' => 'Added New Supplier']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }
        

    }

    public function show($id){
        $result = Supplier::find($id);

        return response()->json($result);

    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'email'=> 'required|email',
            'tele_no'=> 'required|numeric|min:10',
            'address'=> 'required|min:5',
            'name'=> 'required|min:2',
            'bp_no'=> 'required|min:2',
            'br_no'=> 'required|min:5'
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $supplier = Supplier::find($request->id);
                $supplier->br_no = $request->br_no;
                $supplier->bp_no = $request->bp_no;
                $supplier->name = $request->name;
                $supplier->address = $request->address;
                $supplier->email = $request->email;
                $supplier->tele_no = $request->tele_no;

                $supplier->save();

                DB::commit();
                return response()->json(['db_success' => 'Supplier Updated']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }

    }

    public function destroy($id){
        $result = Supplier::destroy($id);

        return response()->json($result);

    }

    public function search(Request $request){

        $search = $request->data;
        $result = DB::table('suppliers')
                    ->where('name','like', '%'.$search.'%')
                    ->orWhere('br_no', 'like', '%'.$search.'%')
                    ->orWhere('bp_no', 'like', '%'.$search.'%')
                    ->get();

        return response()->json($result);
       

    }

    public function employee($id){

        $supplier = Supplier::find($id);
        $result = $supplier->employee()->get();

        return response()->json($result);
    }
}
