<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Contract;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('contract');
    }

    public function create(){
        
        $result = Contract::all();

        return response()->json($result);

    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'description'=> 'required|min:3'
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $contract = new Contract;
                $contract->description = $request->description;

                $contract->save();

                DB::commit();
                return response()->json(['db_success' => 'Added New Contract']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }
        

    }

    public function show($id){
        $result = Contract::find($id);

        return response()->json($result);

    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'description'=> 'required|min:3'
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $contract = Contract::find($request->id);
                $contract->description = $request->description;

                $contract->save();

                DB::commit();
                return response()->json(['db_success' => 'Contract Updated']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }

    }

    public function destroy($id){
        $result = Contract::destroy($id);

        return response()->json($result);

    }

    public function search(Request $request){

        $search = $request->data;
        $result = DB::table('contracts')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->get();

        return response()->json($result);
       

    }
}
