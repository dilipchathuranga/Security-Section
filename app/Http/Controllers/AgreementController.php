<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Agreement;
use App\Supplier;
use App\Project;
use App\Agreement_renewal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AgreementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $project = Project::all();
        $supplier = Supplier::all();
        return view('agreement')->with(['projects' => $project, 
                                       'suppliers' => $supplier]);
    }

    public function create(){
        $result = DB::table('agreements')
                        ->join('projects','agreements.project_id','=','projects.id')
                        ->join('suppliers', 'agreements.supplier_id','=','suppliers.id')
                        ->select('agreements.*','projects.name as project_des','suppliers.name as supplier_des')
                        ->limit(10)
                        ->get();

        return response()->json($result);

    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            
            'expire_date'=> 'required|date',
            'agreement_date'=> 'required|date',
            'agreement_no'=> 'required|min:2',
            'supplier_id'=> 'required|nullable',
            'project_id'=> 'required|nullable'
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $agreement = new Agreement;
                $agreement->project_id = $request->project_id;
                $agreement->supplier_id = $request->supplier_id;
                $agreement->agreement_no = $request->agreement_no;
                $agreement->agreement_date = $request->agreement_date;
                $agreement->expire_date = $request->expire_date;

                $agreement->save();

                //agreement renewal

                $renewal = new Agreement_renewal;
                $renewal->agreement_id = $agreement->id;
                $renewal->agreement_no = $request->agreement_no;
                $renewal->from_date = $request->agreement_date;
                $renewal->end_date = $request->expire_date;

                $renewal->save();

                DB::commit();
                return response()->json(['db_success' => 'Added New Agreement']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }
        

    }

    public function show($id){
        $result = Agreement::find($id);

        return response()->json($result);

    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'expire_date'=> 'required|date',
            'agreement_date'=> 'required|date',
            'supplier_id'=> 'required|nullable',
            'agreement_no'=> 'required|nullable',
            'project_id'=> 'required'
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $agreement = Agreement::find($request->id);
                $agreement->project_id = $request->project_id;
                $agreement->supplier_id = $request->supplier_id;
                $agreement->agreement_no = $request->agreement_no;
                $agreement->agreement_date = $request->agreement_date;
                $agreement->expire_date = $request->expire_date;

                $agreement->save();

                //agreement renewal

                $renewal = Agreement_renewal::where('agreement_id',$request->id)->latest('created_at')->first();
                $renewal->agreement_no = $request->agreement_no;
                $renewal->from_date = $request->agreement_date;
                $renewal->end_date = $request->expire_date;

                $renewal->save();

                DB::commit();
                return response()->json(['db_success' => 'Agreement Updated']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }

    }

    public function destroy($id){
        $result = Agreement::destroy($id);

        return response()->json($result);

    }

    public function search(Request $request){

        $search = $request->data;
        $result = DB::table('agreements')
                    ->join('projects','agreements.project_id','=','projects.id')
                    ->join('suppliers', 'agreements.supplier_id','=','suppliers.id')
                    ->where('agreements.agreement_no','like', '%'.$search.'%')
                    ->select('agreements.*','projects.name as project_des','suppliers.name as supplier_des')
                    ->get();

        return response()->json($result);
       

    }

    public function get_renewal($id){
        $result = Agreement_renewal::where('agreement_id',$id)->latest('created_at')->first();

        return response()->json($result);
    }

    public function store_renewal(Request $request){

        $validator = Validator::make($request->all(), [
            
            'e_date'=> 'required|date',
            'f_date'=> 'required|date',
            'agreement_no'=> 'required|min:2',
            'haid'=> 'required|nullable'
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $renewal = new Agreement_renewal;
                $renewal->agreement_id = $request->haid;
                $renewal->agreement_no = $request->agreement_no;
                $renewal->end_date = $request->e_date;
                $renewal->from_date = $request->f_date;

                $renewal->save();

                DB::commit();
                return response()->json(['db_success' => 'Added New Agreement']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }
        

    }

}
