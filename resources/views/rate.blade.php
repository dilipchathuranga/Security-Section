@extends('layouts.app')

@section('content')
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Rate</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <div class="modal-body">
            <form>
              <input type="hidden" id="hid" name="hid">
              <div class="row"> 
                <div class="form-group col-md-6">
                  <label for="agreement_id">Agreement No</label>
                  <select name="agreement_id" id="agreement_id" class="form-control" required>
                    <option value="">--------------</option>
                    @foreach($agreements as $agreement)
                        <option value="{{ $agreement->id }}">{{ $agreement->agreement_no }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group col-md-6">
                  <label for="designation_id">Designation</label>
                  <select name="designation_id" id="designation_id" class="form-control" required>
                    <option value="">--------------</option>
                    @foreach($designations as $designation)
                        <option value="{{ $designation->id }}">{{ $designation->description }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-12">
                    <label for="name">Rate</label>
                    <input type="text" class="form-control" id="rate" name="rate" placeholder="Enter Rate" required> 
                </div>
              </div>
              
              </div>
            </form>
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
            <h1 class="m-0 text-dark">Designation Rate</h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Master</a></li>
              <li class="breadcrumb-item"><a href="#">Agreement</a></li>
              <li class="breadcrumb-item active">Designation Rate</li>
            </ol>
          </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary addNew"><i class="fa fa-plus"></i> Add New Rate</button>
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
                            <th style="width:20%">Designation</th>
                            <th style="width:20%">Rate</th>
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
    show_rates();

    $(".addNew").click(function(){
        empty_form();
        $("#modal").modal('show');
        $(".modal-title").html('Save Rate');
        $("#submit").html('Save Rate');
        $("#submit").click(function(){
            var hid =$("#hid").val();
            //save Rate
            if(hid == ""){
                var agreement_id = $("#agreement_id").val();
                var designation_id = $("#designation_id").val();
                var rate = $("#rate").val();
               
                $.ajax({
                'type': 'ajax',
                'dataType': 'json',
                'method': 'post',
                'data' : {agreement_id:agreement_id, designation_id:designation_id, rate:rate },
                'url' : 'rate',
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
        $(".modal-title").html('Edit Rate');
        $("#submit").html('Update Rate');

        $.ajax({
            'type': 'ajax',
            'dataType': 'json',
            'method': 'get',
            'url': 'rate/'+id,
            'async': false,
            success: function(data){
            // console.log(data.id);
                $("#hid").val(data.id);
                $("#agreement_id").val(data.agreement_id);
                $("#designation_id").val(data.designation_id);
                $("#rate").val(data.rate);
            }
        });

        $("#submit").click(function(){
            if($("#hid").val() != ""){
            var id =$("#hid").val();
            var agreement_id = $("#agreement_id").val();
            var designation_id = $("#designation_id").val();
            var rate = $("#rate").val(); 

            $.ajax({
                'type': 'ajax',
                'dataType': 'json',
                'method': 'put',
                'data' : {agreement_id:agreement_id, designation_id:designation_id, rate:rate },
                'url': 'rate/'+id,
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
                'url': 'rate/'+id,
                'async': false,
                success: function(data){

                    if(data){
                    Swal.fire(
                        'Deleted!',
                        'Rate has been deleted.',
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
            'url': 'rate/search',
            'async': false,
            success: function(data){
            var html = "";
            for(var i=0; i<data.length;i++){
                html+="<tr>";
                    html+="<td>"+data[i].id+"</td>";
                    html+="<td>"+data[i].agreement_no+"</td>";
                    html+="<td>"+data[i].designation_des+"</td>";
                    html+="<td>"+data[i].rate+"</td>";
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
    function show_rates(){
    $.ajax({
        'type': 'ajax',
        'dataType': 'json',
        'url': 'rate/create',
        'method': 'get',
        'async': false,
        success:function(data){
        var html = "";
        for(var i=0; i<data.length;i++){
            html+="<tr>";
            html+="<td>"+data[i].id+"</td>";
            html+="<td>"+data[i].agreement_no+"</td>";
            html+="<td>"+data[i].designation_des+"</td>";
            html+="<td>"+data[i].rate+"</td>";
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
        $("#agreement_id").val("");
        $("#designation_id").val("");
        $("#rate").val("");
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