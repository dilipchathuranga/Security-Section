@extends('layouts.app')

@section('content')
<div class="modal fade" id="modal-sub">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Agreement Renewal</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form>
             <input type="hidden" name="haid" id="haid"><!-- hidden agreement_id no -->
              <div class="row">
                <div class="form-group col-md-6">
                    <label for="name">Agreement No</label>
                    <input type="text" class="form-control" id="ragreement_no" name="ragreement_no" readonly> 
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="f_date">From Date</label>
                  <input type="date" name="f_date" id="f_date" class="form-control" placeholder="Enter From Date" required>
                </div>
                <div class="form-group col-md-6">
                  <label for="f_date">End Date</label>
                  <input type="date" name="e_date" id="e_date" class="form-control" placeholder="Enter End Date" required>
                </div>
              </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary rsubmit">Save changes</button>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Agreement</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <div class="modal-body">
            <form>
              <input type="hidden" id="hid" name="hid">
              <div class="row"> 
                <div class="form-group col-md-6">
                  <label for="project_id">Project Name</label>
                  <select name="project_id" id="project_id" class="form-control" required>
                    <option value="">--------------</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
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
                <div class="form-group col-md-12">
                    <label for="name">Agreement No</label>
                    <input type="text" class="form-control" id="agreement_no" name="agreement_no" placeholder="Enter Agreement Name" required> 
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-12">
                  <label for="agreement_date">Agreement Date</label>
                  <input type="date" name="agreement_date" id="agreement_date" class="form-control" placeholder="Enter Agreement Date" required>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-12">
                  <label for="expire_date">Agreement Expire Date</label>
                  <input type="date" name="expire_date" id="expire_date" class="form-control" placeholder="Enter Expire Date" required>
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
            <h1 class="m-0 text-dark">Agreement</h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Master</a></li>
              <li class="breadcrumb-item"><a href="#">Agreement</a></li>
              <li class="breadcrumb-item active">Agreement</li>
            </ol>
          </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary addNew"><i class="fa fa-plus"></i> Add New Agreement</button>
                    <div class="input-group input-group-sm float-right" style="width: 450px; ">
                            <input type="text" id="table_search" name="table_search" class="form-control float-right" placeholder="Search">
                    </div>
                </div>   
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>                  
                        <tr>
                            <th style="width:5%">#</th>
                            <th style="width:20%">Project Name</th>
                            <th style="width:20%">Supplier</th>
                            <th style="width:15%">Agreement No</th>
                            <th style="width:10%">Agreement Date</th>
                            <th style="width:10%">Expire Date</th>
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
    show_agreements();

    $(".addNew").click(function(){
        empty_form();
        $("#modal").modal('show');
        $(".modal-title").html('Save Agreement');
        $("#submit").html('Save Agreement');
        $("#submit").click(function(){
            var hid =$("#hid").val();
            //save agreement
            if(hid == ""){
                var project_id = $("#project_id").val();
                var supplier_id = $("#supplier_id").val();
                var agreement_no = $("#agreement_no").val();
                var agreement_date = $("#agreement_date").val();
                var expire_date = $("#expire_date").val();
               
                $.ajax({
                'type': 'ajax',
                'dataType': 'json',
                'method': 'post',
                'data' : {project_id:project_id, supplier_id:supplier_id, agreement_no:agreement_no, agreement_date:agreement_date, expire_date:expire_date },
                'url' : 'agreement',
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
        $(".modal-title").html('Edit agreement');
        $("#submit").html('Update agreement');

        $.ajax({
            'type': 'ajax',
            'dataType': 'json',
            'method': 'get',
            'url': 'agreement/'+id,
            'async': false,
            success: function(data){
            // console.log(data.id);
                $("#hid").val(data.id);
                $("#project_id").val(data.project_id);
                $("#supplier_id").val(data.supplier_id);
                $("#agreement_no").val(data.agreement_no);
                $("#agreement_date").val(data.agreement_date);
                $("#expire_date").val(data.expire_date);
            }
        });

        $("#submit").click(function(){
            if($("#hid").val() != ""){
            var id =$("#hid").val();
            var project_id = $("#project_id").val();
            var supplier_id = $("#supplier_id").val();
            var agreement_no = $("#agreement_no").val();
            var agreement_date = $("#agreement_date").val();
            var expire_date = $("#expire_date").val(); 

            $.ajax({
                'type': 'ajax',
                'dataType': 'json',
                'method': 'put',
                'data' : {project_id:project_id, supplier_id:supplier_id, agreement_no:agreement_no, agreement_date:agreement_date, expire_date:expire_date },
                'url': 'agreement/'+id,
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
                'url': 'agreement/'+id,
                'async': false,
                success: function(data){

                    if(data){
                    Swal.fire(
                        'Deleted!',
                        'Agreement has been deleted.',
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
            'url': 'agreement/search',
            'async': false,
            success: function(data){
            var html = "";
            for(var i=0; i<data.length;i++){
                html+="<tr>";
                    html+="<td>"+data[i].id+"</td>";
                    html+="<td>"+data[i].project_des+"</td>";
                    html+="<td>"+data[i].supplier_des+"</td>";
                    html+="<td>"+data[i].agreement_no+"</td>";
                    html+="<td>"+data[i].agreement_date+"</td>";
                    html+="<td>"+data[i].expire_date+"</td>";
                    html+="<td><button class='btn btn-warning btn-sm edit' data='"+data[i].id+"'><i class='fas fa-edit'></i></button>";
                    html+="&nbsp;<button class='btn btn-danger btn-sm delete' data='"+data[i].id+"'><i class='fas fa-trash'></i></button>";
                    html+="&nbsp;<button class='btn btn-success btn-sm renew' data='"+data[i].id+"'>Renew</button>";
                    html+="</td>";
                html+="</tr>";
            }

            $("#tdata").html(html);

            }
        });
        },1000);
        
    });

    $(".renew").click(function(){
        var id = $(this).attr('data');

        $("#modal-sub").modal('show');
        $.ajax({
            'type': 'ajax',
            'dataType': 'json',
            'method': 'get',
            'async': false,
            'url': 'agreement/get_renewal/'+id,
            success: function(data){
                $("#haid").val(data.agreement_id);
                $("#ragreement_no").val(data.agreement_no);
                $("#f_date").val(data.from_date);
                $("#e_date").val(data.end_date);
            }
        });

        $(".rsubmit").click(function(){
            var haid = $("#haid").val();
            var agreement_no = $("#ragreement_no").val();
            var f_date = $("#f_date").val();
            var e_date = $("#e_date").val();

            $.ajax({
            'type': 'ajax',
            'dataType': 'json',
            'method': 'post',
            'async': false,
            'data' : {haid:haid, agreement_no:agreement_no, f_date:f_date, e_date:e_date },
            'url': 'agreement/get_renewal',
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
                        $("#modal-sub").modal('hide');
                        location.reload();
                    }, 2000);
                    }
                },

            });

        });
    });

    });
    function show_agreements(){
    $.ajax({
        'type': 'ajax',
        'dataType': 'json',
        'url': 'agreement/create',
        'method': 'get',
        'async': false,
        success:function(data){
        var html = "";
        for(var i=0; i<data.length;i++){
            html+="<tr>";
            html+="<td>"+data[i].id+"</td>";
            html+="<td>"+data[i].project_des+"</td>";
            html+="<td>"+data[i].supplier_des+"</td>";
            html+="<td>"+data[i].agreement_no+"</td>";
            html+="<td>"+data[i].agreement_date+"</td>";
            html+="<td>"+data[i].expire_date+"</td>";
            html+="<td><button class='btn btn-warning btn-sm edit' data='"+data[i].id+"'><i class='fas fa-edit'></i></button>";
            html+="&nbsp;<button class='btn btn-danger btn-sm delete' data='"+data[i].id+"'><i class='fas fa-trash'></i></button>";
            html+="&nbsp;<button class='btn btn-success btn-sm renew' data='"+data[i].id+"'>Renew</button>";
            html+="</td>";
            html+="</tr>";
        }

        $("#tdata").html(html);
        }
        
    });
    }

    function empty_form(){
        $("#hid").val("");
        $("#project_id").val("");
        $("#supplier_id").val("");
        $("#agreement_date").val("");
        $("#agreement_no").val("");
        $("#expire_date").val("");
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