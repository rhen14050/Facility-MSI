@extends('layouts.admin_layout')

@section('title', 'Inventory')



@section('content_page')

<style>
  .custom-panel-blue {
        background-color: #d9edf7 !important; 
        border-color: #bce8f1 !important;
    }

    /* Matches the blue text color */
    .custom-panel-blue label {
        color: #31708f !important;
        font-weight: bold;
        letter-spacing: 0.5px;
    }

    /* Centers and styles the DataTable headers */
    #tblTransactionDetails thead th {
        background-color: #fcfcfc;
        text-align: center;
        vertical-align: middle;
        font-size: 11px; /* Smaller font like in the image */
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><i class="fas fa-inventories"></i> Inventory</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Inventory</li>
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
                  {{-- <div class="float-sm-left" style="min-width: 300px; display: none;">
                    <div class="form-group row">
                      <div class="col">
                        <div class="input-group input-group-md mb-3">
                          <div class="input-group-prepend w-20">
                            <span class="input-group-text w-100">Status</span>
                          </div>
                          <select class="form-control form-control-md selFilByStat" name="status">
                            <option value="1" selected="true">Active</option>
                            <option value="2">Archived</option>
                          </select>
                        </div>
                      </div>
                    </div> <!-- .form-group row -->
                  </div> <!-- .float-md-left --> --}}

                  {{-- <div class="float-md-left" style="min-width: 200px; display: {{ $display }}; margin-left: 10px;">
                    <div class="form-group row">
                      <div class="col">
                        <div class="input-group input-group-md mb-3">
                          <div class="input-group-prepend w-20">
                            <span class="input-group-text w-100">Type</span>
                          </div>
                          <select class="form-control form-control-md selFilByType" name="type">
                            <option value="1" selected="true">Die Parts</option>
                            <option value="2">Grinding End-mill</option>
                          </select>
                        </div>
                      </div>
                    </div> <!-- .form-group row -->
                  </div> <!-- .float-sm-left --> --}}

                  {{-- <div class="float-md-left" style="min-width: 500px; display: {{ $display }}; margin-left: 10px;">
                    <div class="form-group row">
                      <div class="col">
                        <div class="input-group input-group-md mb-3">
                          <div class="input-group-prepend w-20">
                            <span class="input-group-text w-100">Receiving/Invoice No.</span>
                          </div>
                          <select class="form-control form-control-md selFilByRecRefNo select2 select2bs4" name="receiving_no">
                          </select>
                        </div>
                      </div>
                    </div> <!-- .form-group row -->
                  </div> <!-- .float-sm-left --> --}}

                  {{-- <div class="float-md-left" style="min-width: 300px; display: {{ $display }}; margin-left: 10px;">
                    <div class="form-group row">
                      <div class="col">
                        <div class="input-group input-group-md mb-3">
                          <div class="input-group-prepend w-20">
                            <span class="input-group-text w-100">Item</span>
                          </div>
                          <select class="form-control form-control-md selFilByItem select2 select2bs4" name="item_id">
                          </select>
                        </div>
                      </div>
                    </div> <!-- .form-group row -->
                  </div> <!-- .float-sm-left --> --}}

                  {{-- <div class="float-md-left" style="min-width: 200px; display: none; margin-left: 10px;">
                    <div class="form-group row">
                      <div class="col">
                        <div class="input-group input-group-md mb-3">
                          <div class="input-group-prepend w-20">
                            <span class="input-group-text w-100">Form</span>
                          </div>
                          <select class="form-control form-control-md selFilByForm" name="form">
                            <option value="0" selected="true">All</option>
                            <option value="1">Inventory</option>
                            <option value="2">Withdrawal</option>
                          </select>
                        </div>
                      </div>
                    </div> <!-- .form-group row -->
                  </div> <!-- .float-sm-left --> --}}

                  <div class="float-sm-right">
                    {{-- <button class="btn btn-primary btn-sm btnAddInventory"><i class="fa fa-plus"></i> Add Item</button> --}}
                    <button class="btn btn-primary btn-sm btnAddItem"><i class="fa fa-plus"></i> Add Item</button>
                    {{-- <button class="btn btn-success btn-sm btnAddWithdrawal"><i class="fa fa-plus"></i> Add Withdrawal</button> --}}
                  </div> <!-- .float-sm-right -->
                  <br><br>

                  {{-- <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover" id="tblMsiInventories" style="width: 100%;">
                      <thead>
                        <tr>
                          <th>Action</th>
                          <th>Date/Time Received</th>
                          <th>Receiving No.</th>
                          <th>Invoice No.</th>
                          <th>Item Code</th>
                          <th>Item Name</th>
                          <th>Item Description</th>
                          <th>BOH</th>
                          <th>Input</th>
                          <th>Output</th>
                          <th>EOH</th>
                          <th>Unit Price</th>
                          <th>Remarks</th>
                          <th>Created By</th>
                          <th>Approval</th>
                          <th>Category</th>
                        </tr>
                      </thead>
                      <tbody>

                      </tbody>
                    </table>
                  </div> <!-- .table-responsive --> --}}

                  <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover" id="tblItem" style="width: 100%;">
                      <thead>
                        <tr style="text-align: center;">
                          <th>Action</th>
                          <th>Status</th>
                          <th>Item Name</th>
                          <th>Item Description</th>
                          {{-- <th>Unit Price</th> --}}
                          <th>Remarks</th>
                          {{-- <th>Current Stock</th> --}}
                          <th>Category</th>
                        </tr>
                      </thead>
                    </table>
                  </div>


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
<div class="modal fade" id="mdlSaveInventory" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fa fa-info-circle text-info"></i> Inventory Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" id="frmSaveInventory">
        @csrf
        <div class="modal-body">
          <div class="card-body">
            {{-- <div class="form-group row">
              <label class="col-sm-3 col-form-label">Type</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="type" placeholder="" readonly="true" required="true" style="display: none;">
                <input type="text" class="form-control" name="type_text" placeholder="" readonly="true" required="true">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div> --}}

            <input type="text" class="form-control" name="form" readonly="true" style="display: none">

            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Classification</label>
              <div class="col-sm-9">
                <select class="form-control" name="category" placeholder="(Auto Generated)" required="true">
                  <option value="1" selected="true">ACU</option>
                  <option value="2">ACU - F2</option>
                  <option value="3">Air Compressor</option>
                  <option value="4">Electrical Spare</option>
                  <option value="5">Bldg. Maintenance</option>

                </select>
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Source</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="source" placeholder="(Auto Generated)" readonly="true" required="true" style="display: none;" value="1">

                {{-- <select class="form-control" name="sel_source" placeholder="(Auto Generated)" required="true" readonly disabled>
                  <option value="1" selected="true">Receiving No.</option>
                  <option value="1">Invoice No.</option>
                </select> --}}

                <select class="form-control" name="sel_source" style="pointer-events: none; touch-action: none;" readonly tabindex="-1">
                    <option value="1">Invoice No.</option>
                </select>

                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label lbl-source">Reference No.</label>
              <div class="col-sm-9">
                <div class="input-group input-group-md mb-3">

                  <input type="text" class="form-control" name="receiving_no" placeholder="Receiving No." hidden>
                  <input type="text" class="form-control" name="reference_no" placeholder="Invoice No.">
                  <div class="input-group-append w-20">
                    <button type="button" class="btn btn-success btnSearchItem" title="Click to Search"><i class="fa fa-search"></i></button>
                    <button type="button" class="btn btn-danger btnSearchAgain" title="Click to Search Again" style="display: none;"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Item Name</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="item_name" placeholder="(Auto Generated)" readonly="true" required="true" style="display: none;">
                <select class="form-control" id="selItemId" name="item_id" placeholder="(Auto Generated)" required="true">
                  <option value="">(Select Item)</option>
                </select>
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
 
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">PO Number</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="po_no" placeholder="(Auto Generated)" readonly="true" required="true">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>

              <div class="form-group row">
              <label class="col-sm-3 col-form-label">Item Code</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="item_code" placeholder="(Auto Generated)" readonly="true" required="true">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>

            

            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Item Description</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="item_description" placeholder="(Auto Generated)" readonly="true" required="true">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>

             <div class="form-group row">
              <label class="col-sm-3 col-form-label">Supplier Name</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="supplier_name" placeholder="(Auto Generated)" readonly="true" required="true">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Quantity</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="quantity" placeholder="(Auto Generated)" readonly="true" required="true">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Unit Price</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="unit_price" placeholder="(Auto Generated)" readonly="true" required="true">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Delivery Date</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="delivery_date" placeholder="(Auto Generated)" readonly="true" required="true">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>

            {{-- <div class="form-group row">
              <label class="col-sm-3 col-form-label">BOH</label>
              <div class="col-sm-9">
                <input type="number" class="form-control" name="boh" placeholder="(Auto Computed)" required="true">
                <span class="float-sm-right"><i><b>Note:</b> Be mindful inputting BOH.</i></span>
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div> --}}

            
            {{-- <div class="form-group row">
              <label class="col-sm-3 col-form-label">EOH</label>
              <div class="col-sm-9">
                <input type="number" step="0.01" class="form-control" name="eoh" placeholder="(Auto Computed)" required="true">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div> --}}
            
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Remarks</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="remarks" placeholder="(Optional)">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btnSaveInventory">Save</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- MODALS withdrawal --> 
{{-- <div class="modal fade" id="mdlSaveWithdrawal">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fa fa-info-circle text-info"></i> Withdrawal Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" id="frmSaveWithdrawal">
        @csrf
        <div class="modal-body">
          <div class="card-body">
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Type</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="type" placeholder="" readonly="true" required="true" style="display: none;">
                <input type="text" class="form-control" name="type_text" placeholder="" readonly="true" required="true">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Product Category</label>
              <div class="col-sm-9">
                <input class="form-control" name="category" placeholder="(Auto Generated)" required="true" readonly="true" style="display: none;">
                <select class="form-control" name="sel_category" placeholder="(Auto Generated)" required="true" disabled="true">
                  <option value="1" selected="true">Burn-in Memory Sockets</option>
                  <option value="2">Burn-in Other Sockets</option>
                  <option value="3">Grinding Multi-Spindle</option>
                  <option value="4">Grinding Conventional</option>
                  <!-- <option value="5">Flexicon & TC/DC Connectors</option> -->
                  <option value="5">Flexicon Connector</option>
                  <option value="6">Card Connector</option>
                  <option value="7">FUS/FRS/FMS Connector</option>
                  <option value="8">CN171 Connector</option>
                  <option value="9">Mounting Sockets Connector</option>
                  <option value="10">TC/DC Connector</option>

                </select>
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Source</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="source" placeholder="(Auto Generated)" readonly="true" required="true" style="display: none;" value="1">
                <select class="form-control" name="sel_source" placeholder="(Auto Generated)" required="true">
                  <option value="1" selected="true">Receiving No.</option>
                  <option value="2">Invoice No.</option>
                </select>
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label lbl-source-wd">Receiving No.</label>
              <div class="col-sm-9">
                <div class="input-group input-group-md mb-3">

                  <input type="text" class="form-control" name="receiving_no" placeholder="Receiving No.">
                  <input type="text" class="form-control" name="reference_no" placeholder="Invoice No." style="display: none;">
                  <div class="input-group-append w-20">
                    <button type="button" class="btn btn-success btnSearchItemWd" title="Click to Search"><i class="fa fa-search"></i></button>
                    <button type="button" class="btn btn-danger btnSearchAgainWd" title="Click to Search Again" style="display: none;"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 col-form-label">PO Number</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="po_no" placeholder="(Auto Generated)" readonly="true" required="true">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Item Name</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="item_name" placeholder="(Auto Generated)" readonly="true" required="true" style="display: none;">
                <select class="form-control" id="selItemIdWd" name="item_id" placeholder="(Auto Generated)" required="true">
                  <option value="">(Select Item)</option>
                </select>
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
            <div class="form-group row" style="">
              <label class="col-sm-3 col-form-label">Item Code</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="item_code" placeholder="(Auto Generated)" readonly="true" required="true">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Quantity</label>
              <div class="col-sm-9">
                <input type="number" step="1" class="form-control" min="1" name="quantity" placeholder="" required="true">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Unit Prices</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="unit_price" placeholder="(Auto Generated)" readonly="true" required="true">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Received Date</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="received_date" placeholder="(Auto Generated)" readonly="true" required="true">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">BOH</label>
              <div class="col-sm-9">
                <input type="number" step="0.01" class="form-control" name="boh" placeholder="(Auto Computed)" required="true" readonly="true">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">EOH</label>
              <div class="col-sm-9">
                <input type="number" step="0.01" class="form-control" name="eoh" placeholder="(Auto Computed)" required="true" readonly="true">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Remarks</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="remarks" placeholder="(Optional)">
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Checked By</label>
              <div class="col-sm-9">
                <select class="form-control select2bs4 select2" id="selCheckedByWd" name="checked_by" placeholder="(Auto Generated)" required="true">
                </select>
                <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btnSaveInventory">Save</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div> --}}
<!-- /.modal -->

<div class="modal fade" id="modalSaveItem">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">
            <b>Add Item Details</b>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form class="form-horizontal" id="frmsaveItemDetails">
      @csrf
      <div class="modal-body">

          <div class="row">
            <div class="col-sm-6">    
              <div class="form-group row">
                <input type="hidden" name="item_id" id="itemId">
                <label>Classification</label>
                  <select class="form-control" name="category" placeholder="(Auto Generated)" required="true">
                    <option value="0" selected="true">Select Category</option>
                    <option value="1">ACU</option>
                    <option value="2">ACU - F2</option>
                    <option value="3">Air Compressor</option>
                    <option value="4">Electrical Spare</option>
                    <option value="5">Bldg. Maintenance</option>

                  </select>
                  <span class="text-danger float-sm-right input-error"></span>
              </div>
            </div>
          </div>

          <div class="row">
            
            <div class="col-sm-6">
              <div class="form-group"> 
                <label>Item Code</label>
                  <select class="form-control select2 select2bs4 selItemCode" id="txtSelectItemCode" name="select_item_code">
                    <option value="">Select Item Code</option>
                </select>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label>Item Name</label>
                <input class="form-control" type="text" name="add_item_name" id="txtAddItemName" readonly>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label>Item Description</label>
                <input class="form-control" type="text" name="add_item_description" id="txtAddItemDescription" readonly>
              </div>
            </div>

            {{-- <div class="col-sm-6">
              <div class="form-group">
                <label>Unit Price</label>
                <input class="form-control" type="text" name="add_unit_price" id="txtAddUnitPrice" readonly>
              </div>
            </div> --}}

            <div class="col-sm-6">
              <div class="form-group">
                <label>Remarks</label>
                <input class="form-control" type="text" name="add_item_remarks" id="txtAddItemRemarks" autocomplete="false">
              </div>
            </div>

          </div>


        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btnSaveItem">Save</button>
        </div>
        </form>
          

    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>


<!-- MODALS View Item --> 
<div class="modal fade" id="mdlViewItem" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title w-100 text-center">
            <b>MSI - TRANSACTION DETAILS</b>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        
        <div class="row">
          {{-- <div class="col-sm-4">
            <div class="form-group">
              <label>Item Code</label>
              <input class="form-control" type="text" name="item_code" id="txtItemCode" readonly>
            </div>
          </div> --}}

          <div class="col-sm-4">
            <div class="form-group">
              <label>Item Name</label>
              <input class="form-control" type="text" name="item_name" id="txtItemName" readonly>
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              <label>Item Description</label>
              <input class="form-control" type="text" name="item_description" id="txtItemDescription" readonly>
            </div>
          </div>

          <div class="col-sm-4">
            <div class="form-group">
              <label>Remarks</label>
              <input class="form-control" type="text" name="item_remarks" id="txtItemRemarks" readonly>
            </div>
          </div>
          
        </div>


          <div class="float-sm-right">
              <button class="btn btn-primary btn-sm btnAddItemTransaction"><i class="fa fa-plus"></i> Add Item Transaction</button>
              <button class="btn btn-success btn-sm btnAddWithdrawal"><i class="fa fa-plus"></i> Add Withdrawal Transaction</button>
            </div> <!-- .float-sm-right -->
            <br><br>

        <div class="panel panel-info" style="border-color: #bce8f1;">
          <div class="panel-heading custom-panel-blue">
              <label style="padding-top: 5.5px;">TRANSACTION DETAILS</label>
          </div>
          <div class="panel-body">

          
            <div class="table-responsive">
              <table class="table table-bordered table-condensed table-hover" id="tblTransactionDetails" style="width: 100%;">
                  <thead>
                      <tr>
                          <th>TRANSACTION DATE</th>
                          <th>SUPPLIER NAME</th>
                          <th>INVOICE NO.</th>
                          <th>BOH</th>
                          <th>IN</th>
                          <th>OUT</th>
                          <th>EOH</th>
                          <th>REMARKS</th>
                      </tr>
                  </thead>
                  <tbody>
                      </tbody>
              </table>
            </div>
          </div>
        </div>
        
      </div>

    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- MODALS Add Item Transaction --> 
<div class="modal fade" id="modalSaveItemTransaction">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">
            <b>Add Item Transaction Details</b>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form class="form-horizontal" id="frmSaveItemTransaction">
      @csrf
      <div class="modal-body">

          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label>Item Name</label>
                <input class="form-control" type="text" name="transaction_item_name" id="txtTransactionItemName" readonly>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group"> 
                <label>Item Code</label>
                  <select class="form-control select2 select2bs4 selTransactionItemCode" id="txtSelectTransactionItemCode" name="select_transaction_item_code">
                    <option value="">Select Item Code</option>
                </select>
              </div>
            </div>
            
          </div>

          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label>Supplier Name</label>
                <input class="form-control" type="text" name="transaction_supplier_name" id="txtTransactionItemSupplierName" readonly>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label>Received Qty.</label>
                <input class="form-control" type="text" name="transaction_received_qty" id="txtTransactionItemReceivedQty" readonly>
              </div>
            </div>            
          </div>

        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btnSaveInventory">Save</button>
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
  let dtInventories, frmSaveInventory, btnSaveInventory, frmSaveItemTransaction, btnSaveItem, frmsaveItemDetails;
</script>

@endsection

@section('js_content')
<!-- Custom Links -->
<script src="{{ asset('public/scripts/client/Inventory.js') }}"></script>

<!-- JS Codes -->
<script type="text/javascript">
  $(document).ready(function () {
    frmSaveInventory = $("#frmSaveInventory");
    frmSaveItemTransaction = $("#frmSaveItemTransaction");
    frmsaveItemDetails = $("#frmsaveItemDetails");
    btnSaveInventory = $('.btnSaveInventory');
    btnSaveItem = $('.btnSaveItem');

    frmSaveWithdrawal = $("#frmSaveWithdrawal");
    btnSaveWithdrawal = $('.btnSaveWithdrawal');

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

    $(document).on('click','#tblInventories tbody tr',function(e){
      $(this).closest('tbody').find('tr').removeClass('table-active');
      $(this).closest('tr').addClass('table-active');
    });

    $.fn.dataTable.ext.errMode = 'none';

    let groupColumn = 6;
    let itemGroupColumn = 5;

    // Inventory Datatables
    // dtInventories = $("#tblMsiInventories").DataTable({
    //   "processing" : false,
    //   "serverSide" : true,
    //   "ajax" : {
    //     url: "{{ route('view_inventories') }}",
    //     data: function (param){
    //         // param.status = $(".selFilByStat").val();
    //         // param.type = $(".selFilByType").val();
    //         // param.part_id = $(".selFilByParts").val();
    //         // param.receiving_no = $('.selFilByRecRefNo').val();
    //         param.reference_no = $('.selFilByRecRefNo').val();
    //         param.item_name = $('.selFilByItem').val();
    //     }
    //   },

    //   "columns":[
    //     { "data" : "action", },
    //     { "data" : "delivery_date", },
    //     // { "data" : "receiving_no", orderable: false, },
    //     // { "data" : "reference_no", orderable: false, },
    //     // { "data" : "item_code", orderable: false, },
    //     { "data" : "item_name", orderable: false, },
    //     { "data" : "item_description", orderable: false, },
    //     // { "data" : "raw_input", orderable: false, },
    //     // { "data" : "quantity", orderable: false, },
    //     // { "data" : "raw_output", orderable: false, },
    //     // { "data" : "eoh", orderable: false, },
    //     { "data" : "unit_price", orderable: false, },
    //     { "data" : "remarks", orderable: false, },
    //     { "data" : "user_name", orderable: false, },
    //     // { "data" : "raw_action2", orderable: false, },
    //     { "data" : "raw_category", orderable: false, },
    //   ],

    //   "columnDefs": [
    //     {
    //       "className": "text-center",
    //       "targets": [0],
    //       // "data": null,
    //       "defaultContent": "--"
    //     },
    //     { visible: false, targets: groupColumn }
    //   ],
    //   "order": [[ groupColumn, "asc" ], [ 0, "desc" ]],
    //   "initComplete": function(settings, json) {

    //   },
    //   "drawCallback": function( settings ) {
    //       var api = this.api();
    //       var rows = api.rows({ page: 'current' }).nodes();
    //       var last = null;

    //       api
    //           .column(groupColumn, { page: 'current' })
    //           .data()
    //           .each(function (group, i) {
    //               if (last !== group) {
    //                   $(rows)
    //                       .eq(i)
    //                       .before('<tr class="group bg-secondary"><td colspan="7">' + group.toUpperCase() + '</td></tr>');

    //                   last = group;
    //               }
    //           });
    //   },

    //   // "rowCallback": function(row,data,index ){
    //   //   if(data.form == 1) {
    //   //     $(row).attr('title', 'Inventory');
    //   //   }
    //   //   else {
    //   //     $(row).attr('title', 'Withdrawal');

    //   //     if(data.approval_status == 0) {
    //   //       $(row).addClass('table-warning');
    //   //       $(row).attr('title', 'Withdrawal For Approval');
    //   //     }
    //   //     else if(data.approval_status == 1) {
    //   //       $(row).addClass('table-success');
    //   //       $(row).attr('title', 'Withdrawal Approved at ' + data.approved_at);
    //   //     }
    //   //     else if(data.approval_status == 2) {
    //   //       $(row).addClass('table-danger');
    //   //       $(row).attr('title', 'Withdrawal Disapproved at ' + data.approved_at);
    //   //     }
    //   //   }
    //   // },
    // }).on( 'error', function () {
    //   // toastr.warning('DataTable not loaded properly. Please reload the page. <br> <button class="pull-right btn btn-danger btn-xs btnReload float-sm-right">Reload</button>');
    // });//end of dtInventories


      dtItem = $("#tblItem").DataTable({
      "processing" : false,
      "serverSide" : true,
      "ajax" : {
        url: "{{ route('view_item_inventory') }}",
        data: function (param){
            // param.status = $(".selFilByStat").val();
            // param.type = $(".selFilByType").val();
            // param.part_id = $(".selFilByParts").val();
            // param.receiving_no = $('.selFilByRecRefNo').val();
            // param.reference_no = $('.selFilByRecRefNo').val();
            // param.item_name = $('.selFilByItem').val();
        }
      },

      "columns":[
        { "data" : "action", },
        { "data" : "status", },
        { "data" : "item_name", orderable: false, },
        { "data" : "item_description", orderable: false, },
        // { "data" : "unit_price", orderable: false, },
        { "data" : "remarks", orderable: false, },
        // { "data" : "user_name", orderable: false, },
        // { "data" : "raw_action2", orderable: false, },
        { "data" : "raw_category", orderable: false, },
      ],

      "columnDefs": [
        {
          "className": "text-center",
          "targets": [0],
          // "data": null,
          "defaultContent": "--"
        },
        { visible: false, targets: itemGroupColumn }
      ],
      "order": [[ itemGroupColumn, "asc" ], [ 0, "desc" ]],
      "initComplete": function(settings, json) {

      },
      "drawCallback": function( settings ) {
          var api = this.api();
          var rows = api.rows({ page: 'current' }).nodes();
          var last = null;

          api
              .column(itemGroupColumn, { page: 'current' })
              .data()
              .each(function (group, i) {
                  if (last !== group) {
                      $(rows)
                          .eq(i)
                          .before('<tr class="group bg-secondary"><td colspan="5">' + group.toUpperCase() + '</td></tr>');

                      last = group;
                  }
              });
      },

      // "rowCallback": function(row,data,index ){
      //   if(data.form == 1) {
      //     $(row).attr('title', 'Inventory');
      //   }
      //   else {
      //     $(row).attr('title', 'Withdrawal');

      //     if(data.approval_status == 0) {
      //       $(row).addClass('table-warning');
      //       $(row).attr('title', 'Withdrawal For Approval');
      //     }
      //     else if(data.approval_status == 1) {
      //       $(row).addClass('table-success');
      //       $(row).attr('title', 'Withdrawal Approved at ' + data.approved_at);
      //     }
      //     else if(data.approval_status == 2) {
      //       $(row).addClass('table-danger');
      //       $(row).attr('title', 'Withdrawal Disapproved at ' + data.approved_at);
      //     }
      //   }
      // },
    }).on( 'error', function () {
      // toastr.warning('DataTable not loaded properly. Please reload the page. <br> <button class="pull-right btn btn-danger btn-xs btnReload float-sm-right">Reload</button>');
    });//end of dtInventories
    


    $('.selFilByRecRefNo').select2({
      // dropdownParent: $('#mdlSaveItemRegistration'),
      placeholder: "",
      minimumInputLength: 2,
      allowClear: true,
      ajax: {
         url: "{{ route('get_cbo_receiving_no') }}",
         type: "get",
         dataType: 'json',
         delay: 250,
         // quietMillis: 100,
         data: function (params) {
          return {
            search: params.term, // search term
            type: $('.selFilByType').val(),
            // receiving_no: $('.selFilByRecRefNo').val(),
            reference_no: $('.selFilByRecRefNo').val(),
          };
         },
         processResults: function (response) {
           return {
              results: response
           };
         },
         cache: true
      },
    }).on('select2:select', function (e) {
      $('.selFilByItem').html('').val('');
      dtInventories.draw();
    })
    .on('select2:clear', function (e) {
      $('.selFilByItem').html('').val('');
      dtInventories.draw();
    });

    $('.selFilByItem').select2({
      // dropdownParent: $('#mdlSaveItemRegistration'),
      placeholder: "",
      minimumInputLength: 2,
      allowClear: true,
      ajax: {
         url: "{{ route('get_cbo_items') }}",
         type: "get",
         dataType: 'json',
         delay: 250,
         // quietMillis: 100,
         data: function (params) {
          return {
            search: params.term, // search term
            type: $('.selFilByType').val(),
            // receiving_no: $('.selFilByRecRefNo').val(),
            reference_no: $('.selFilByRecRefNo').val(),
          };
         },
         processResults: function (response) {
           return {
              results: response
           };
         },
         cache: true
      },
    }).on('select2:select', function (e) {
      dtInventories.draw();
    })
    .on('select2:clear', function (e) {
      dtInventories.draw();
    });

    $(document).on('click', '.btnViewItem', function () {
      let itemId = $(this).attr('inventory-id');
      $('#mdlViewItem').modal('show');
      GetItemByItemId(itemId);

    })


    // $('select[name="part_id"]', frmSaveInventory).select2({
    //   // dropdownParent: $('#mdlSaveItemRegistration'),
    //   placeholder: "",
    //   minimumInputLength: 2,
    //   allowClear: true,
    //   ajax: {
    //      url: "{{ route('get_cbo_part_by_stat') }}",
    //      type: "get",
    //      dataType: 'json',
    //      delay: 250,
    //      // quietMillis: 100,
    //      data: function (params) {
    //       return {
    //         search: params.term, // search term
    //       };
    //      },
    //      processResults: function (response) {
    //        return {
    //           results: response
    //        };
    //      },
    //      cache: true
    //   },
    // }).on('select2:select', function (e) {
    //   GetInventoryBohEoh($(".selFilByType").val(), $('select[name="part_id"]', frmSaveInventory).val());
    // })
    // .on('select2:clear', function (e) {
    //   $('input[name="boh"]', frmSaveInventory).val('');
    //   $('input[name="eoh"]', frmSaveInventory).val('');
    // });

    // $('select[name="checked_by"]', frmSaveWithdrawal).select2({
    //   // dropdownParent: $('#mdlSaveItemRegistration'),
    //   placeholder: "",
    //   minimumInputLength: 2,
    //   allowClear: true,
    //   ajax: {
    //      url: "{{ route('get_checked_by') }}",
    //      type: "get",
    //      dataType: 'json',
    //      delay: 250,
    //      // quietMillis: 100,
    //      data: function (params) {
    //       return {
    //         search: params.term, // search term
    //       };
    //      },
    //      processResults: function (response) {
    //        return {
    //           results: response
    //        };
    //      },
    //      cache: true
    //   },
    // }).on('select2:select', function (e) {
    //   // GetItemByItemId($('select[name="item_id"]', frmSaveWithdrawal).val(), $('input[name="type"]', frmSaveWithdrawal).val());
    //   // GetInventoryBohEoh($(".selFilByType").val(), $('select[name="part_id"]', frmSaveInventory).val());
    // })
    // .on('select2:clear', function (e) {
    //   // $('input[name="unit_price"]', frmSaveInventory).val('');
    //   // $('input[name="boh"]', frmSaveInventory).val('');
    //   // $('input[name="eoh"]', frmSaveInventory).val('');
    // });

    // $(document).on('click', '.btnReload', function(){
    //   // window.location.reload();
    //   dtInventories.draw();
    // });

    $(".selFilByStat").change(function(e){
      dtInventories.draw();
    });

    // $(".selFilByType").change(function(e){
    //   $('.selFilByRecRefNo').html('').val('');
    //   $('.selFilByItem').html('').val('');
    //   dtInventories.draw();
    // });

    $(".btnSearchItem").click(function(e){
      GetItemsByRecNo($('input[name="receiving_no"]', frmSaveInventory).val(), $('input[name="reference_no"]', frmSaveInventory).val(), $('input[name="source"]', frmSaveInventory).val());
    });

    $(".btnSearchItemWd").click(function(e){
      GetItemsByRecNoWd($('input[name="receiving_no"]', frmSaveWithdrawal).val(), $('input[name="reference_no"]', frmSaveWithdrawal).val(), $('input[name="type"]', frmSaveWithdrawal).val(), $('input[name="source"]', frmSaveWithdrawal).val());
    });

    $(".btnSearchAgain").click(function(e){
      $('.btnSearchItem').show();
      $('.btnSearchAgain').hide();
      $('select[name="item_id"]', frmSaveInventory).html('<option value="">(Select Item)</option>');
      $('input[name="receiving_no"]', frmSaveInventory).prop('readonly', false).val('');
      $('input[name="reference_no"]', frmSaveInventory).prop('readonly', false).val('');
      $('input[name="po_no"]', frmSaveInventory).val('');
      $('input[name="item_id"]', frmSaveInventory).val('');
      $('input[name="item_name"]', frmSaveInventory).val('');
      $('input[name="item_code"]', frmSaveInventory).val('');
      $('input[name="quantity"]', frmSaveInventory).val('');
      $('input[name="unit_price"]', frmSaveInventory).val('');
      $('input[name="delivery_date"]', frmSaveInventory).val('');
      $('select[name="sel_source"]', frmSaveInventory).prop('disabled', false);
    });

    // $(".btnSearchAgainWd").click(function(e){
    //   $('.btnSearchItemWd').show();
    //   $('.btnSearchAgainWd').hide();
    //   $('select[name="item_id"]', frmSaveWithdrawal).html('<option value="">(Select Item)</option>');
    //   $('input[name="receiving_no"]', frmSaveWithdrawal).prop('readonly', false).val('');
    //   $('input[name="reference_no"]', frmSaveWithdrawal).prop('readonly', false).val('');
    //   $('input[name="po_no"]', frmSaveWithdrawal).val('');
    //   $('input[name="item_id"]', frmSaveWithdrawal).val('');
    //   $('input[name="item_name"]', frmSaveWithdrawal).val('');
    //   $('input[name="item_code"]', frmSaveWithdrawal).val('');
    //   $('input[name="quantity"]', frmSaveWithdrawal).val('');
    //   $('input[name="unit_price"]', frmSaveWithdrawal).val('');
    //   $('input[name="received_date"]', frmSaveWithdrawal).val('');
    //   $('select[name="sel_source"]', frmSaveWithdrawal).prop('disabled', false);
    // });
// getItemCodeByItemName 
    $(".btnAddItem").click(function(e){
        e.preventDefault();
        $("#modalSaveItem").modal('show');

        getItemCode($('.selItemCode, .selTransactionItemCode'));
    });

    $("#frmsaveItemDetails").submit(function(e){
      e.preventDefault();
      SaveItemDetails();
    });



    $(".btnAddInventory").click(function(e){
      $('.btnSearchItem').show();
      $('.btnSearchAgain').hide();
      // $('input[name="receiving_no"]', frmSaveInventory).prop('readonly', false).show();
      // $('input[name="reference_no"]', frmSaveInventory).prop('readonly', false).hide();
      $('select[name="sel_source"]', frmSaveInventory).prop('disabled', false);
      // $('.lbl-source').text('Receiving No.');
      // $('input[name="receiving_no"]', frmSaveInventory).show();
      // $('input[name="reference_no"]', frmSaveInventory).hide();
      $("#mdlSaveInventory").modal('show');
      frmSaveInventory[0].reset();
      $(".input-error", frmSaveInventory).text('');
      $(".form-control", frmSaveInventory).removeClass('is-invalid');
      $('select[name="part_id"]', frmSaveInventory).html('').val('');
      $('input[name="type"]', frmSaveInventory).val($('.selFilByType').val());

    });

    $(".btnAddItemTransaction").click(function(e){
      e.preventDefault();
      itemName = $('#txtItemName').val();
      $('#modalSaveItemTransaction').modal('show');
      $('#txtTransactionItemName').val(itemName);
      getItemCodeByItemName(itemName)
      // frmSaveItemTransaction[0].reset();
    })

    // $(".btnAddWithdrawal").click(function(e){
    //   $('.btnSearchItemWd').show();
    //   $('.btnSearchAgainWd').hide();
    //   $('input[name="receiving_no"]', frmSaveWithdrawal).prop('readonly', false).show();
    //   $('input[name="reference_no"]', frmSaveWithdrawal).prop('readonly', false).hide();
    //   $('select[name="sel_source"]', frmSaveWithdrawal).prop('disabled', false);
    //   $('.lbl-source').text('Receiving No.');
    //   $('input[name="receiving_no"]', frmSaveWithdrawal).show();
    //   $('input[name="reference_no"]', frmSaveWithdrawal).hide();
    //   $("#mdlSaveWithdrawal").modal('show');
    //   frmSaveWithdrawal[0].reset();
    //   $(".input-error", frmSaveWithdrawal).text('');
    //   $(".form-control", frmSaveWithdrawal).removeClass('is-invalid');
    //   $('select[name="part_id"]', frmSaveWithdrawal).html('').val('');
    //   $('select[name="checked_by"]', frmSaveWithdrawal).html('').val('');
    //   $('input[name="type"]', frmSaveWithdrawal).val($('.selFilByType').val());
    //   if($('.selFilByType').val() == 1) {
    //     $('input[name="type_text"]', frmSaveWithdrawal).val('Die Parts');
    //   }
    //   else{
    //     $('input[name="type_text"]', frmSaveWithdrawal).val('Grinding End-mill');
    //   }

    // });

    $('#mdlSaveInventory').on('shown.bs.modal', function (e) {
      $('input[name="receiving_no"]', frmSaveInventory).focus();
    })

    // $("#tblInventories").on('click', '.btnActions', function(e){
    //   let inventoryId = $(this).attr('reference-type-id');
    //   let action = $(this).attr('action');
    //   let status = $(this).attr('status');
    //   let title = '';

    //   if(action == 1){
    //     if(status == 2){
    //       title = 'Archive Inventory';
    //     }
    //     else if(status == 1){
    //       title = 'Restore Inventory';
    //     }
    //   }
    //   // else if(action == 2){
    //   //   title = 'Reset Password';
    //   // }

    //   $.confirm({
    //     title: title,
    //     content: 'Please confirm to continue.',
    //     backgroundDismiss: true,
    //     type: 'blue',
    //     buttons: {
    //       confirm: {
    //         text: 'Confirm',
    //         btnClass: 'btn-blue',
    //         keys: ['enter'],
    //         action: function(){
    //           InventoryAction(inventoryId, action, status);
    //           cnfrmLoading.open();
    //         }
    //       },
    //       cancel: function () {

    //       },
    //     }
    //   });
    // });

    // $("#tblInventories").on('click', '.btnWithdawalActions', function(e){
    //   let inventoryId = $(this).attr('inventory-id');
    //   let action = $(this).attr('action');
    //   let approvalStatus = $(this).attr('approval-status');
    //   let title = '';
    //   if(approvalStatus == 1){
    //     title = 'Approve Withdrawal';
    //   }
    //   else if(approvalStatus == 2){
    //     title = 'Disapprove Withdrawal';
    //   }

    //   $.confirm({
    //     title: title,
    //     content: 'Please confirm to continue.',
    //     backgroundDismiss: true,
    //     type: 'blue',
    //     buttons: {
    //       confirm: {
    //         text: 'Confirm',
    //         btnClass: 'btn-blue',
    //         keys: ['enter'],
    //         action: function(){
    //           InventoryAction(inventoryId, action, approvalStatus);
    //           cnfrmLoading.open();
    //         }
    //       },
    //       cancel: function () {

    //       },
    //     }
    //   });
    // });

    $("#frmSaveInventory").submit(function(e){
      e.preventDefault();
      SaveInventory();
    });

    // $("#frmSaveWithdrawal").submit(function(e){
    //   e.preventDefault();
    //   SaveWithdrawal();
    // });

    // $("#tblInventories").on('click', '.btnEditInventory', function(e){
    //   let inventoryId = $(this).attr('reference-type-id');
    //   GetInventoryById(inventoryId);
    // });

    $('select[name="item_id"]', frmSaveInventory).change(function(){
      let itemName = $('#selItemId option:selected').text();
      $('input[name="item_name"]', frmSaveInventory).val(itemName);
      GetItemByRecNo($('input[name="reference_no"]', frmSaveInventory).val(), $('input[name="type"]', frmSaveInventory).val(), $(this).val());
    });

    // $('select[name="item_id"]', frmSaveWithdrawal).change(function(){
    //   let itemName = $('#selItemIdWd option:selected').text();
    //   let selectedItemId = $(this).val();
    //   console.log(selectedItemId);

    //   $('input[name="item_name"]', frmSaveWithdrawal).val(itemName);
    // //   GetItemByRecNoWd($('input[name="receiving_no"]', frmSaveWithdrawal).val(), $('input[name="type"]', frmSaveWithdrawal).val(), $(this).val());
    //   GetItemByRecNoWd($('input[name="reference_no"]', frmSaveWithdrawal).val(), $('input[name="type"]', frmSaveWithdrawal).val(), selectedItemId);
    // });

    // $('select[name="sel_source"]', frmSaveInventory).change(function(){
    //   let source = $(this).val();
    //   $('input[name="source"]', frmSaveInventory).val(source);
    //   if(source == 1) {
    //     $('.lbl-source').text('Receiving No.');
    //     $('input[name="receiving_no"]', frmSaveInventory).show();
    //     $('input[name="reference_no"]', frmSaveInventory).hide();
    //   }
    //   else {
    //     $('.lbl-source').text('Invoice No.');
    //     $('input[name="receiving_no"]', frmSaveInventory).hide();
    //     $('input[name="reference_no"]', frmSaveInventory).show();
    //   }
    // });

    // $('select[name="sel_source"]', frmSaveWithdrawal).change(function(){
    //   let source = $(this).val();
    //   $('input[name="source"]', frmSaveWithdrawal).val(source);
    //   if(source == 1) {
    //     $('.lbl-source-wd').text('Receiving No.');
    //     $('input[name="receiving_no"]', frmSaveWithdrawal).show();
    //     $('input[name="reference_no"]', frmSaveWithdrawal).hide();
    //   }
    //   else {
    //     $('.lbl-source-wd').text('Invoice No.');
    //     $('input[name="receiving_no"]', frmSaveWithdrawal).hide();
    //     $('input[name="reference_no"]', frmSaveWithdrawal).show();
    //   }
    // });


    // $('input[name="quantity"]', frmSaveWithdrawal).keyup(function(){
    //   let quantity = $(this).val();
    //   let boh = $('input[name="boh"]', frmSaveWithdrawal).val();
    //   if(quantity == "") {
    //     $('input[name="eoh"]', frmSaveWithdrawal).val(boh);
    //   }
    //   else {
    //     let eoh = parseFloat(boh) - parseFloat(quantity);
    //     $('input[name="eoh"]', frmSaveWithdrawal).val(eoh);
    //   }
    // });

    $('input[name="boh"]', frmSaveInventory).keyup(function(){
      let boh = $(this).val();
      let quantity = $('input[name="quantity"]', frmSaveInventory).val();
      if(boh == "") {
        $('input[name="eoh"]', frmSaveInventory).val(quantity);
      }
      else {
        let eoh = parseFloat(boh) + parseFloat(quantity);
        $('input[name="eoh"]', frmSaveInventory).val(eoh);
      }
    });

    $('input[name="boh"]', frmSaveInventory).blur(function(){
      let boh = $(this).val();
      let quantity = $('input[name="quantity"]', frmSaveInventory).val();
      if(boh == "") {
        $('input[name="eoh"]', frmSaveInventory).val(quantity);
      }
      else {
        let eoh = parseFloat(boh) + parseFloat(quantity);
        $('input[name="eoh"]', frmSaveInventory).val(eoh);
      }
    });



    $(document).on('change', '#txtSelectItemCode', function() {
      // 1. Get the selected option element
      let selectedOption = $(this).find('option:selected');

      // 2. Pull data from the attributes we created in Part A
      let itemName = selectedOption.data('name');
      let itemDesc = selectedOption.data('desc');

      // 3. Update the UI fields
      $('#txtAddItemName').val(itemName);
      $('#txtAddItemDescription').val(itemDesc);
      
      // Optional: Log it to check
      console.log("Selected Item:", itemName, "| Description:", itemDesc);
  });

  });
</script>
@endsection
