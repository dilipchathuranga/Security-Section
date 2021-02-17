<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $suppliers = DB::table('suppliers')->count();
        $employees = DB::table('employees')->count();
        $projects = DB::table('projects')->count();
        $agreements = DB::table('agreements')->count();

        return view('home')->with(['suppliers' => $suppliers,
                            'employees' =>  $employees,
                            'projects' =>  $projects,
                            'agreements' =>  $agreements ]);
    }
}
