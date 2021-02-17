@extends('layouts.app')

@section('content')


<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Employee</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <div class="modal-body">
            <form>
              <input type="hidden" id="hid" name="hid">
              <div class="row"> 
                <div class="form-group col-md-6">
                  <label for="designation_id">Designation</label>
                  <select name="designation_id" id="designation_id" class="form-control" required>
                    <option value="">--------------</option>
                    @foreach($designations as $designation)
                        <option value="{{ $designation->id }}">{{ $designation->description }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group col-md-6">
                  <label for="supplier_id">Supplier</label>
                  <select name="supplier_id" id="supplier_id" class="form-control" required>
                    <option value="">--------------</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                    <label for="nic">Employee NIC</label>
                    <input type="text" class="form-control" id="nic" name="nic" placeholder="Enter NIC No" required> 
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-12">
                    <label for="name">Employee name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Employee Name" required> 
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-12">
                  <label for="address">Employee Address</label>
                  <textarea name="address" id="address" class="form-control" placeholder="Enter Supplier Address" required></textarea>
                </div>
              </div>
              <div class="row"> 
                <div class="form-group col-md-4">
                  <label for="tele_no">Telephone No</label>
                  <input type="text" class="form-control" id="tele_no" name="tele_no" placeholder="Enter Telephone No" required>
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
            <h1 class="m-0 text-dark">Employee</h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Master</a></li>
                <li class="breadcrumb-item">Employee</li>
                <li class="breadcrumb-item active">Employee</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary addNew"><i class="fa fa-plus"></i> Add New Employee</button>
                    <div class="input-group input-group-sm float-right" style="width: 450px; ">
                            <input type="text" id="table_search" name="table_search" class="form-control float-right" placeholder="Search">
                    </div>
                </div>   
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>                  
                        <tr>
                            <th style="width:5%">#</th>
                            <th style="width:10%">Designation</th>
                            <th style="width:10%">Supplier</th>
                            <th style="width:30%">Name</th>
                            <th style="width:10%">Nic</th>
                            <th style="width:30%">Address</th>
                            <th style="width:10%">Telephone No</th>
                            <th style="width:20%">Action</th>
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
    show_employees();

    $(".addNew").click(function(){
        empty_form();
        $("#modal").modal('show');
        $(".modal-title").html('Save Employee');
        $("#submit").html('Save Employee');
        $("#submit").click(function(){
            var hid =$("#hid").val();
            //save Employee
            if(hid == ""){
                var designation_id = $("#designation_id").val();
                var supplier_id = $("#supplier_id").val();
                var nic = $("#nic").val();
                var name = $("#name").val();
                var address = $("#address").val();
                var tele_no = $("#tele_no").val();
               
                $.ajax({
                'type': 'ajax',
                'dataType': 'json',
                'method': 'post',
                'data' : {designation_id:designation_id, supplier_id:supplier_id, nic:nic, name:name, address:address, tele_no:tele_no },
                'url' : 'employee',
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
        $(".modal-title").html('Edit Employee');
        $("#submit").html('Update Employee');

        $.ajax({
            'type': 'ajax',
            'dataType': 'json',
            'method': 'get',
            'url': 'employee/'+id,
            'async': false,
            success: function(data){
            // console.log(data.id);
                $("#hid").val(data.id);
                $("#designation_id").val(data.designation_id);
                $("#supplier_id").val(data.supplier_id);
                $("#nic").val(data.nic);
                $("#name").val(data.name);
                $("#address").val(data.address);
                $("#tele_no").val(data.tele_no);
            }
        });

        $("#submit").click(function(){
            if($("#hid").val() != ""){
            var id =$("#hid").val();
            var designation_id = $("#designation_id").val();
            var supplier_id = $("#supplier_id").val();
            var nic = $("#nic").val();
            var name = $("#name").val();
            var address = $("#address").val();
            var tele_no = $("#tele_no").val(); 

            $.ajax({
                'type': 'ajax',
                'dataType': 'json',
                'method': 'put',
                'data' : {designation_id:designation_id, supplier_id:supplier_id, nic:nic, name:name, address:address, tele_no:tele_no },
                'url': 'employee/'+id,
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
                'url': 'employee/'+id,
                'async': false,
                success: function(data){

                    if(data){
                    Swal.fire(
                        'Deleted!',
                        'Supplier has been deleted.',
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
            'url': 'employee/search',
            'async': false,
            success: function(data){
            var html = "";
            for(var i=0; i<data.length;i++){
                html+="<tr>";
                    html+="<td>"+data[i].id+"</td>";
                    html+="<td>"+data[i].designation_des+"</td>";
                    html+="<td>"+data[i].supplier_des+"</td>";
                    html+="<td>"+data[i].name+"</td>";
                    html+="<td>"+data[i].nic+"</td>";
                    html+="<td>"+data[i].address+"</td>";
                    html+="<td>"+data[i].tele_no+"</td>";
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

    });
    function show_employees(){
    $.ajax({
        'type': 'ajax',
        'dataType': 'json',
        'url': 'employee/create',
        'method': 'get',
        'async': false,
        success:function(data){
        var html = "";
        for(var i=0; i<data.length;i++){
            html+="<tr>";
            html+="<td>"+data[i].id+"</td>";
            html+="<td>"+data[i].designation_des+"</td>";
            html+="<td>"+data[i].supplier_des+"</td>";
            html+="<td>"+data[i].name+"</td>";
            html+="<td>"+data[i].nic+"</td>";
            html+="<td>"+data[i].address+"</td>";
            html+="<td>"+data[i].tele_no+"</td>";
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
        $("#supplier_id").val("");
        $("#name").val("");
        $("#address").val("");
        $("#nic").val("");
        $("#tele_no").val("");
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

</script>
@endsection