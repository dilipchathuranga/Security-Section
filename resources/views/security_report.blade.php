@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <h1 class="m-0 text-dark">Security Reports</h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Reports</a></li>
              <li class="breadcrumb-item"><a href="#">Security Reports</a></li>
            </ol>
        </div>

        @if ($errors->any())
        <div class="col-md-12">
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="myForm" action="" method="post" target="_blank" >
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="from_date">From Date</label>
                                <input type="date" class="form-control" id="from" name="from" value="{{ date('Y-m-d')}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="from_date">To Date</label>
                                <input type="date" class="form-control" id="to" name="to" value="{{ date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="from_date">Employee</label>
                                <select class="form-control" id="employee_id" name="employee_id">
                                    <option value=""></option>
                                    @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="agreement_id">Agreement No</label>
                                <select class="form-control" id="agreement_id" name="agreement_id">
                                    <option value=""></option>
                                    @foreach($agreements as $agreement)
                                    <option value="{{ $agreement->id }}">{{ $agreement->agreement_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br><br>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <div class="radio">
                                    <label><input type="radio" class="optradio" name="optradio" value="security_report/daysheet_emp_wise"> Day Sheet Employee Wise</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <div class="radio">
                                    <label><input type="radio" class="optradio" name="optradio" value="security_report/monthly_advance_attendance"> Monthly Record of security Advance & Attendance</label>
                                </div>
                            </div>
                        </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary print">Print PDF</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    //csrf token error
    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

    $(".print").click(function(){
        var url = $(".optradio:checked").val();
        $("#myForm").attr('action', url) ;
        $("#myForm").attr('method', 'get') ;
        $('#myForm').submit();
    });

});
</script>
@endsection

