@extends('layouts.admin_layout')

@section('title', 'Parts')

@section('content_page')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><i class="fas fa-parts"></i> Parts</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Parts</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="card card-primary">
            <!-- Start Page Content -->
            <div class="card-body">
              <div class="row">
                <div class="col-sm-12">
                  @php
                    $display = 'block';
                  @endphp
                  <div class="float-sm-left" style="min-width: 200px; display: {{ $display }}">
                    <div class="form-group row">
                      <div class="col">
                        <div class="input-group input-group-sm mb-3">
                          <div class="input-group-prepend w-20">
                            <span class="input-group-text w-100">Status</span>
                          </div>
                          <select class="form-control form-control-sm selFilByStat" name="status">
                            <option value="1" selected="true">Active</option>
                            <option value="2">Archived</option>
                          </select>
                        </div>
                      </div>
                    </div> <!-- .form-group row -->
                  </div> <!-- .float-sm-left -->

                  <div class="float-sm-right">
                    <button class="btn btn-primary btn-sm btnAddPart"><i class="fa fa-plus"></i> Add New</button>
                  </div> <!-- .float-sm-right -->
                  <br><br>

                  <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover" id="tblParts" style="width: 100%;">
                      <thead>
                        <tr>
                          <th>Description</th>
                          <th>Code</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        
                      </tbody>
                    </table>
                  </div> <!-- .table-responsive -->
                </div> <!-- .col-sm-12 -->
              </div> <!-- .row -->
            </div>
            <!-- !-- End Page Content -->

          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- MODALS -->
<div class="modal fade" id="mdlSavePart">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fa fa-info-circle text-info"></i> Part Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" id="frmSavePart">
        @csrf
        <div class="modal-body">
          <div class="card-body">
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Description</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="part_id" placeholder="Part ID" style="display: none;">
                <input type="text" class="form-control" name="description" placeholder="Description" required="true">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Code</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="code" placeholder="Code">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btnSavePart">Save</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script type="text/javascript">
  // Variables
  let dtParts, frmSavePart, btnSavePart;
</script>

@endsection

@section('js_content')
<!-- Custom Links -->
<script src="{{ asset('public/scripts/client/Part.js') }}"></script>

<!-- JS Codes -->
<script type="text/javascript">
  $(document).ready(function () {
    frmSavePart = $("#frmSavePart");
    btnSavePart = $('.btnSavePart');

    bsCustomFileInput.init();
    //Initialize Select2 Elements
    $('.select2').select2();

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });
    
    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

    $(document).on('click','#tblParts tbody tr',function(e){
      $(this).closest('tbody').find('tr').removeClass('table-active');
      $(this).closest('tr').addClass('table-active');
    });

    $.fn.dataTable.ext.errMode = 'none';

    dtParts = $("#tblParts").DataTable({
      "processing" : false,
      "serverSide" : true,
      "ajax" : {
        url: "{{ route('view_parts') }}",
        data: function (param){
            param.status = $(".selFilByStat").val();
        }
      },
      
      "columns":[
        { "data" : "description" },
        { "data" : "code" },
        { "data" : "raw_status" },
        { "data" : "raw_action", orderable:false, searchable:false }
      ],

      "columnDefs": [ 
        {
          "targets": [0, 1, 2],
          "data": null,
          "defaultContent": "--"
        },
        // { "visible": false, "targets": 1 }
      ],
      "order": [[ 1, "asc" ]],
      "initComplete": function(settings, json) {
          
      },
      "drawCallback": function( settings ) {
          
      }
    }).on( 'error', function () {
      toastr.warning('DataTable not loaded properly. Please reload the page. <br> <button class="pull-right btn btn-danger btn-xs btnReload float-sm-right">Reload</button>');
    });//end of dtParts

    $(document).on('click', '.btnReload', function(){
      // window.location.reload();
      dtParts.draw();
    });

    $(".selFilByStat").change(function(e){
      dtParts.draw();
    });

    $(".btnAddPart").click(function(e){
      $("#mdlSavePart").modal('show');
      frmSavePart[0].reset();
      $(".input-error", frmSavePart).text('');
      $(".form-control", frmSavePart).removeClass('is-invalid');
    });

    $('#mdlSavePart').on('shown.bs.modal', function (e) {
      $('input[name="description"]', frmSavePart).focus();
    })

    $("#tblParts").on('click', '.btnActions', function(e){
      let partId = $(this).attr('part-id');
      let action = $(this).attr('action');
      let status = $(this).attr('status');
      let title = '';

      if(action == 1){
        if(status == 2){
          title = 'Archive Part';        
        }
        else if(status == 1){
          title = 'Restore Part';        
        }
      }
      // else if(action == 2){
      //   title = 'Reset Password';
      // }

      $.confirm({
        title: title,
        content: 'Please confirm to continue.',
        backgroundDismiss: true,
        type: 'blue',
        buttons: {
          confirm: {
            text: 'Confirm',
            btnClass: 'btn-blue',
            keys: ['enter'],
            action: function(){
              PartAction(partId, action, status);
              cnfrmLoading.open();
            }
          },
          cancel: function () {
            
          },
        }
      });
    });

    $("#frmSavePart").submit(function(e){
      e.preventDefault();
      SavePart();
    });

    $("#tblParts").on('click', '.btnEditPart', function(e){
      let partId = $(this).attr('part-id');
      GetPartById(partId);
    });

  });
</script>
@endsection