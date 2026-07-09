@extends('layouts.admin_layout')

@section('title', 'Dashboard')

@section('content_page')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">

          <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- <div class="col-lg-3 col-6">
          <div class="small-box bg-primary">
            <div class="inner">
              <br><br>
              <p>Reports</p>
            </div>
            <div class="icon">
              <i class="fas fa-file-excel"></i>
            </div>
            <a href="{{ route('parts') }}" class="small-box-footer">
              More info <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div> -->

        <div class="col-lg-3 col-6">
        <!-- small card -->
          <div class="small-box bg-success">
            <div class="inner">
              <br><br>
              <p>Inventory</p>
            </div>
            <div class="icon">
              <i class="fas fa-list"></i>
            </div>
            <a href="{{ route('inventoriesv2') }}" class="small-box-footer">
              More info <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('js_content')
<script type="text/javascript">
  $(document).ready(function () {
    bsCustomFileInput.init();
  });
</script>
@endsection