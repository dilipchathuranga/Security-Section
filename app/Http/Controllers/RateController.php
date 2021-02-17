<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Designation;
use App\Agreement;
use App\Rate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $designations = Designation::all();
        $agreements = Agreement::all();
        return view('rate')->with(['designations'=> $designations,
                                'agreements' => $agreements]);
    }

    public function create(){
        $result = DB::table('rates')
                        ->join('agreements','rates.agreement_id','=','agreements.id')
                        ->join('designations', 'rates.designation_id','=','designations.id')
                        ->select('rates.*','agreements.agreement_no','designations.description as designation_des')
                        ->get();

        return response()->json($result);

    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'rate'=> 'required',
            'agreement_id'=> 'required|nullable',
            'designation_id'=> 'required|nullable'
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $rate = new Rate;
                $rate->designation_id = $request->designation_id;
                $rate->agreement_id = $request->agreement_id;
                $rate->rate = $request->rate;

                $rate->save();

                DB::commit();
                return response()->json(['db_success' => 'Added New Rate']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }
        

    }

    public function show($id){
        $result = Rate::find($id);

        return response()->json($result);

    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'rate'=> 'required',
            'agreement_id'=> 'required|nullable',
            'designation_id'=> 'required|nullable'
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $rate = Rate::find($request->id);
                $rate->designation_id = $request->designation_id;
                $rate->agreement_id = $request->agreement_id;
                $rate->rate = $request->rate;

                $rate->save();

                DB::commit();
                return response()->json(['db_success' => 'Rate Updated']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }

    }

    public function destroy($id){
        $result = Rate::destroy($id);

        return response()->json($result);

    }

    public function search(Request $request){

        $search = $request->data;
        $result = DB::table('rates')
                    ->join('designations','rates.designation_id','=','designations.id')
                    ->join('agreements', 'rates.agreement_id','=','agreements.id')
                    ->where('agreements.agreement_no','like', '%'.$search.'%')
                    ->select('rates.*','designations.description as designation_des','agreements.agreement_no')
                    ->get();

        return response()->json($result);
       

    }
}
