@extends('layouts.app')

@section('content')
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Contract</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <div class="modal-body">
            <form>
              <input type="hidden" id="hid" name="hid">
              <div class="row">
                <div class="form-group col-md-12">
                  <label for="description">Description</label>
                  <input type="text" class="form-control" id="description" name="description" placeholder="Enter Description" required>
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
            <h1 class="m-0 text-dark">Contract</h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Master</a></li>
              <li class="breadcrumb-item"><a href="#">Project</a></li>
              <li class="breadcrumb-item active">Contract</li>
            </ol>
          </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary addNew"><i class="fa fa-plus"></i> Add New Contract</button>
                    <div class="input-group input-group-sm float-right" style="width: 450px; ">
                            <input type="text" id="table_search" name="table_search" class="form-control float-right" placeholder="Search">
                    </div>
                </div>   
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>                  
                        <tr>
                            <th style="width:10%">#</th>
                            <th style="width:30%">Description</th>
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
        show_contracts();

        $(".addNew").click(function(){
            empty_form();
            $("#modal").modal('show');
            $(".modal-title").html('Save Contract');
            $("#submit").html('Save Contract');
            $("#submit").click(function(){
                $("#submit").css("display","none");
                var hid =$("#hid").val();
                //save contract
                if(hid == ""){
                  var description =$("#description").val(); 

                  $.ajax({
                    'type': 'ajax',
                    'dataType': 'json',
                    'method': 'post',
                    'data' : {description:description },
                    'url' : 'contract',
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
            $(".modal-title").html('Edit Contract');
            $("#submit").html('Update Contract');

            $.ajax({
              'type': 'ajax',
              'dataType': 'json',
              'method': 'get',
              'url': 'contract/'+id,
              'async': false,
              success: function(data){
                $("#hid").val(data.id);
                $("#description").val(data.description);
              }
            });

            $("#submit").click(function(){
              if($("#hid").val() != ""){
                var id =$("#hid").val();
                var description =$("#description").val();

                $.ajax({
                  'type': 'ajax',
                  'dataType': 'json',
                  'method': 'put',
                  'data' : {description:description},
                  'url': 'contract/'+id,
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
                    'url': 'contract/'+id,
                    'async': false,
                    success: function(data){

                      if(data){
                        Swal.fire(
                            'Deleted!',
                            'Contract has been deleted.',
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
              'url': 'contract/search',
              'async': false,
              success: function(data){
                var html = "";
                for(var i=0; i<data.length;i++){
                  html+="<tr>";
                  html+="<td>"+data[i].id+"</td>";
                  html+="<td>"+data[i].description+"</td>";
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
      function show_contracts(){
        $.ajax({
          'type': 'ajax',
          'dataType': 'json',
          'url': 'contract/create',
          'method': 'get',
          'async': false,
          success:function(data){
            var html = "";
            for(var i=0; i<data.length;i++){
              html+="<tr>";
              html+="<td>"+data[i].id+"</td>";
              html+="<td>"+data[i].description+"</td>";
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
            $("#description").val("");
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