@extends('layouts.app')

@section('content')
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Attendance</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <div class="modal-body">
            <form>
              <input type="hidden" id="hid" name="hid">
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="date">Select Date</label>
                  <input type="date" class="form-control" id="date" name="date" required>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="agreement_id">Select Agreement No</label>
                  <select name="agreement_id" id="agreement_id" class="form-control" required>
                    <option value=""></option>
                    @foreach($agreements as $agreement)
                    <option value="{{ $agreement->id}}">{{ $agreement->agreement_no}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="employee_id">Select Employee</label>
                  <select name="employee_id" id="employee_id" class="form-control" required>
                    <option value=""></option>
                    @foreach($employees as $employee)
                    <option value="{{ $employee->id}}">{{ $employee->name}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group col-md-6">
                  <label for="designation_id">Employee Designation</label>
                  <input type="hidden" id="designation_id" name="designation_id">
                  <input type="text" name="designation_des" id="designation_des" class="form-control" readonly required>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="shift">Select Shift</label>
                  <select name="shift" id="shift" class="form-control" required>
                    <option value=""></option>
                    <option value="0">07:00 - 19:00</option>
                    <option value="1">19:00 - 07:00</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="ot_hours">Overtime Hours</label>
                  <input type="text" name="ot_hours" id="ot_hours" class="form-control" required>
                </div>
              </div>
              <div class="row">
              <div class="form-group col-md-6">
                  <label for="operator">Operator</label>
                  <input type="hidden" name="operator_id" id="operator_id" value="{{ Auth::user()->id }}" required>
                  <input type="text" name="operator" id="operator" value="{{ Auth::user()->name }}" class="form-control" readonly >
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success submit" id="submit">Save changes</button>
          </div>
      </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <h1 class="m-0 text-dark">Officer Attendance </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Security Section</a></li>
              <li class="breadcrumb-item active">Officer Attendance</li>
            </ol>
          </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary addNew"><i class="fa fa-plus"></i> Add New Attendance</button>
                    <div class="input-group input-group-sm float-right" style="width: 450px; ">
                            <input type="text" id="table_search" name="table_search" class="form-control float-right" placeholder="Search">
                    </div>
                </div>   
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>                  
                        <tr>
                            <th style="width:5%">#</th>
                            <th style="width:20%">Agreement No</th>
                            <th style="width:10%">Date</th>
                            <th style="width:20%">Employee Name</th>
                            <th style="width:15%">Designation</th>
                            <th style="width:15%">Shift</th>
                            <th style="width:5%">OT Hours</th>
                            <th style="width:30%">Action</th>
                        </tr>
                        </thead>
                        <tbody id="tdata">
                        
                        </tbody>
                    </table>
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
    show_attendances();

    $(".addNew").click(function(){
        empty_form();
        $("#modal").modal('show');
        $(".modal-title").html('Save Attendance');
        $("#submit").html('Save Attendance');
        $("#submit").click(function(){
            var hid =$("#hid").val();
            //save Attendance
            if(hid == ""){
                var designation_id = $("#designation_id").val();
                var employee_id = $("#employee_id").val();
                var agreement_id = $("#agreement_id").val();
                var date = $("#date").val();
                var shift = $("#shift").val();
                var ot_hours = $("#ot_hours").val();
                var operator = $("#operator_id").val();
               
                $.ajax({
                'type': 'ajax',
                'dataType': 'json',
                'method': 'post',
                'data' : {designation_id:designation_id, employee_id:employee_id, agreement_id:agreement_id, date:date, shift:shift, ot_hours:ot_hours, operator:operator },
                'url' : 'attendance',
                'async': false,
                success:function(data){
                    if(data.validation_error){ 
                    validation_error(data.validation_error);//if has validation error call this function
                    }

                    if(data.db_error){ 
                    db_error(data.db_error);
                    }

                    if(data.db_success){ 
                    db_success(data.db_success);
                    setTimeout(function(){
                        $("#modal").modal('hide');
                        location.reload();
                    }, 2000);
                    }

                },
                error: function(jqXHR, exception) {
                    db_error(jqXHR.responseText);
                }
                });
            };
        });
    });

    $(".edit").click(function(){
        var id = $(this).attr('data');
        empty_form();
        $("#hid").val(id);
        $("#modal").modal('show');
        $(".modal-title").html('Edit Attendance');
        $("#submit").html('Update Attendance');

        $.ajax({
            'type': 'ajax',
            'dataType': 'json',
            'method': 'get',
            'url': 'attendance/'+id,
            'async': false,
            success: function(data){
                $("#hid").val(data.id);
                $("#designation_id").val(data.designation_id);
                $("#employee_id").val(data.employee_id);
                get_designation_des(data.designation_id);
                $("#agreement_id").val(data.agreement_id);
                $("#date").val(data.date);
                $("#shift").val(data.shift);
                $("#ot_hours").val(data.ot_hours);
                $("#operator_id").val(data.operator);
            }
        });

        $("#submit").click(function(){
            if($("#hid").val() != ""){
              var designation_id = $("#designation_id").val();
              var employee_id = $("#employee_id").val();
              var agreement_id = $("#agreement_id").val();
              var date = $("#date").val();
              var shift = $("#shift").val();
              var ot_hours = $("#ot_hours").val();
              var operator = $("#operator_id").val();
            $.ajax({
                'type': 'ajax',
                'dataType': 'json',
                'method': 'put',
                'data' : {designation_id:designation_id, employee_id:employee_id, agreement_id:agreement_id, date:date, shift:shift, ot_hours:ot_hours, operator:operator },
                'url': 'attendance/'+id,
                'async': false,
                success:function(data){
                if(data.validation_error){ 
                    validation_error(data.validation_error);//if has validation error call this function
                    }

                    if(data.db_error){ 
                    db_error(data.db_error);
                    }

                    if(data.db_success){ 
                    db_success(data.db_success);
                    setTimeout(function(){
                        $("#modal").modal('hide');
                        location.reload();
                    }, 2000);
                    }
                },
            });
            }
        });
        });

        $(".delete").click(function(){
        var id = $(this).attr('data');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                'type': 'ajax',
                'dataType': 'json',
                'method': 'delete',
                'url': 'attendance/'+id,
                'async': false,
                success: function(data){

                    if(data){
                    Swal.fire(
                        'Deleted!',
                        'Record has been deleted.',
                        'success'
                        );
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                    
                    }

                }
                });
                
            }
            });
    });

    $("#table_search").keyup(function(e){
        
        setTimeout(function(){
        var data = $("#table_search").val();
        $.ajax({
            'type': 'ajax',
            'dataType': 'json',
            'method': 'post',
            'data': {data:data},
            'url': 'attendance/search',
            'async': false,
            success: function(data){
            var html = "";
            for(var i=0; i<data.length;i++){
              var shift ="";
              if(data[i].shift == 0){
                shift = "07:00 - 19:00";
              }else{
                shift = "19:00 - 07:00";
              }

              html+="<tr>";
              html+="<td>"+data[i].id+"</td>";
              html+="<td>"+data[i].agreement_no+"</td>";
              html+="<td>"+data[i].date+"</td>";
              html+="<td>"+data[i].employee_name+"</td>";
              html+="<td>"+data[i].designation_des+"</td>";
              html+="<td>"+shift+"</td>";
              html+="<td>"+data[i].ot_hours+"</td>";
              html+="<td><button class='btn btn-warning btn-sm edit' data='"+data[i].id+"'><i class='fas fa-edit'></i></button>";
              html+="&nbsp;<button class='btn btn-danger btn-sm delete' data='"+data[i].id+"'><i class='fas fa-trash'></i></button>";
              html+="</td>";
              html+="</tr>";
            
            }

            $("#tdata").html(html);

            }
        });
        },1000);
        
    });

    $("#agreement_id").change(function(){
      
        var id = $(this).val();
        $.ajax({
          'type': 'ajax',
          'dataType': 'json',
          'method': 'get',
          'url': 'agreement/'+id,
          'async': false,
          success: function(data){
            get_employee_by_supplier(data.supplier_id);
          }
        });

      
    });

    $("#employee_id").change(function(){
      if($(this).val() == ""){
        $("#designation_id").val("");
        $("#designation_des").val("");

      }else{
        var emp_id = $("#employee_id").val();
        get_emp_designation();
      }
      
    });


    });
    function show_attendances(){
    $.ajax({
        'type': 'ajax',
        'dataType': 'json',
        'url': 'attendance/create',
        'method': 'get',
        'async': false,
        success:function(data){
        var shift ="";
        if(data.shift == 0){
          shift = "07:00 - 19:00";
        }else{
          shift = "19:00 - 07:00";
        }
        var html = "";
        for(var i=0; i<data.length;i++){
            html+="<tr>";
            html+="<td>"+data[i].id+"</td>";
            html+="<td>"+data[i].agreement_no+"</td>";
            html+="<td>"+data[i].date+"</td>";
            html+="<td>"+data[i].employee_name+"</td>";
            html+="<td>"+data[i].designation_des+"</td>";
            html+="<td>"+shift+"</td>";
            html+="<td>"+data[i].ot_hours+"</td>";
            html+="<td><button class='btn btn-warning btn-sm edit' data='"+data[i].id+"'><i class='fas fa-edit'></i></button>";
            html+="&nbsp;<button class='btn btn-danger btn-sm delete' data='"+data[i].id+"'><i class='fas fa-trash'></i></button>";
            html+="</td>";
            html+="</tr>";
        }

        $("#tdata").html(html);
        }
        
    });
    }

    function empty_form(){
        $("#hid").val("");
        $("#designation_id").val("");
        $("#employee_id").val("");
        $("#agreement_id").val("");
        $("#shift").val("");
        $("#ot_hours").val("");
        $("#designation_des").val("");
        $("#designation_id").val("");
        designation_des
    }

    function validation_error(error){
        for(var i=0;i< error.length;i++){
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error[i],
        });
        }
    }

    function db_error(error){
        Swal.fire({
            icon: 'error',
            title: 'Database Error',
            text: error,
        });
    }

    function db_success(message){
        
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: message,
        });
    }

    function get_emp_designation(){
      var id = $("#employee_id").val();
      $.ajax({
          'type': 'ajax',
          'dataType': 'json',
          'method': 'get',
          'async': false,
          'url': 'employee/'+id,
          success: function(data){
            $("#designation_id").val(data.designation_id);
            get_designation_des(data.designation_id);
          }
        });

    }

    function get_designation_des(id){
      $.ajax({
          'type': 'ajax',
          'dataType': 'json',
          'method': 'get',
          'async': false,
          'url': 'designation/'+id,
          success: function(data){
            $("#designation_des").val(data.description);
          }
        });

    }

    function get_employee_by_supplier(id){
      $.ajax({
          'type': 'ajax',
          'dataType': 'json',
          'method': 'get',
          'async': false,
          'url': '/supplier/get_employee/'+id,
          success: function(data){
            var html = "";
            html+="<option value=''></option>";
            for(var i=0; i<data.length;i++){
              
              html+="<option value='"+data[i].id+"'>"+data[i].name+"</option>";

            }

            $("#employee_id").html(html);
          }
        });

    }
</script>
@endsection