<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contract;
use App\Project;
use App\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $contracts = Contract::all();
        $suppliers = Supplier::all();
        return view('project')->with(['contracts' => $contracts, 'suppliers' => $suppliers]);
    }

    public function create(){
        $result = DB::table('projects')
                        ->join('contracts','projects.contract_id','=','contracts.id')
                        ->join('suppliers', 'projects.supplier_id','=','suppliers.id')
                        ->select('projects.*','contracts.description as contract_des','suppliers.name as supplier_des')
                        ->get();

        return response()->json($result);

    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            
            'tele_no'=> 'required|numeric|min:10',
            'address'=> 'required|min:5',
            'name'=> 'required|min:2',
            'supplier_id'=> 'required|nullable',
            'contract_id'=> 'required|nullable'
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $project = new Project;
                $project->contract_id = $request->contract_id;
                $project->supplier_id = $request->supplier_id;
                $project->name = $request->name;
                $project->address = $request->address;
                $project->tele_no = $request->tele_no;

                $project->save();

                DB::commit();
                return response()->json(['db_success' => 'Added New Project']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }
        

    }

    public function show($id){
        $result = Project::find($id);

        return response()->json($result);

    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'tele_no'=> 'required|numeric|min:10',
            'address'=> 'required|min:5',
            'name'=> 'required|min:2',
            'supplier_id'=> 'required',
            'contract_id'=> 'required'
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $project = Project::find($request->id);
                $project->contract_id = $request->contract_id;
                $project->supplier_id = $request->supplier_id;
                $project->name = $request->name;
                $project->address = $request->address;
                $project->tele_no = $request->tele_no;

                $project->save();

                DB::commit();
                return response()->json(['db_success' => 'project Updated']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }

    }

    public function destroy($id){
        $result = Project::destroy($id);

        return response()->json($result);

    }

    public function search(Request $request){

        $search = $request->data;
        $result = DB::table('projects')
                    ->join('contracts','projects.contract_id','=','contracts.id')
                    ->join('suppliers', 'projects.supplier_id','=','suppliers.id')
                    ->where('projects.name','like', '%'.$search.'%')
                    ->orWhere('projects.nic', 'like', '%'.$search.'%')
                    ->select('projects.*','contracts.description as contract_des','suppliers.name as supplier_des')
                    ->get();

        return response()->json($result);
       

    }
}
