@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row ">
            <div class="col-mg-6">
                <h1 class="m-0 text-dark">Dashboard</h1>
            </div>
        </div>
        <br>

        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$suppliers}}</h3>

                <p>Suppliers</p>
              </div>
              <div class="icon">
              <i class="far fa-building"></i>
              </div>
              <a href="{{ route('supplier') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{$employees}}</h3>

                <p>Employees</p>
              </div>
              <div class="icon">
              <i class="fas fa-users"></i>
              </div>
              <a href="{{ route('employee') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{$projects}}</h3>

                <p>Projects</p>
              </div>
              <div class="icon">
              <i class="fas fa-project-diagram"></i>
              </div>
              <a href="{{ route('project') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{$agreements}}</h3>

                <p>Agreements</p>
              </div>
              <div class="icon">
              <i class="fas fa-file-contract"></i>
              </div>
              <a href="{{ route('agreement') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>
        
    </div>
@endsection
