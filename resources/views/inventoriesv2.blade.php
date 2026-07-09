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

    #tblItem thead th {
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
                    <div class="float-sm-right">
                        {{-- <button class="btn btn-primary btn-sm btnAddInventory"><i class="fa fa-plus"></i> Add Item</button> --}}
                        <button class="btn btn-primary btn-sm btnAddItemDetails"><i class="fa fa-plus"></i> Add Item Details</button>
                        <button class="btn btn-info btn-sm btnExportInventory"><i class="fa fa-download"></i> Export Inventory</button>
                        {{-- <button class="btn btn-success btn-sm btnAddWithdrawal"><i class="fa fa-plus"></i> Add Withdrawal</button> --}}
                    </div> <!-- .float-sm-right -->
                    <br><br>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover" id="tblItem" style="width: 100%;">
                        <thead>
                            <tr style="text-align: center;">
                            <th>Action</th>
                            <th>Status</th>
                            <th>Category</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Item Description</th>
                            <th>Min Stock</th>
                            <th>Max Stock</th>
                            <th>Current Stock</th>
                            <th>Unit Price</th>
                            <th>Dollar Rate</th>
                            <th>Converted Unit Price</th>
                            <th>UOM</th>
                            <th>Remarks</th>
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

<div class="modal fade" id="modalSaveItemDetails">
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
                <label>Select Item</label>
                  <select class="form-control select2 select2bs4 selItemCode" id="txtSelectItemCode" name="selected_item_id">
                    <option value="">Select Item Code</option>
                </select>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label>Item Code</label>
                <input class="form-control" type="text" name="add_item_code" id="txtAddItemCode" readonly>
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


            <div class="col-sm-6">
              <div class="form-group">
                <label>Unit Price</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="txtAddCurrency"></span>
                    <input type="hidden" name="currency" id="txtCurrencyId">
                  </div>
                  <input type="text" id="txtAddUnitPrice" name="unit_price" class="form-control" readonly>
                </div>
              </div>
            </div>

            <div class="col-sm-6" id="dollarRateId" hidden>
              <div class="form-group">
                <label>Dollar Rate</label>
                <input class="form-control" type="text" name="dollar_rate" id="txtAddDollarRate" autocomplete="off">
              </div>
            </div>

            <div class="col-sm-6" id="convertedUnitPriceId" hidden>
              <div class="form-group">
                <label>Converted Unit Price ($)</label>
                <input class="form-control" type="text" name="converted_rate" id="txtConvertedRate" placeholder="Auto Compute" readonly>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label>Unit of Measurement</label>
                <input class="form-control" type="text" name="add_uom" id="txtAddUOM" readonly>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label>Min</label>
                <input class="form-control" type="number" name="min_stock" id="txtAddMinStock" placeholder="Enter Minimum Stock" autocomplete="off">
              </div>
            </div>

             <div class="col-sm-6">
              <div class="form-group">
                <label>Max</label>
                <input class="form-control" type="number" name="max_stock" id="txtAddMaxStock" placeholder="Enter Maximum Stock" autocomplete="off">
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label>Remarks</label>
                <input class="form-control" type="text" name="add_item_remarks" id="txtAddItemRemarks" autocomplete="off">
              </div>
            </div>

          </div>


        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btnSaveItemDetails">Save</button>
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
          <input type="hidden" name="view_item_id" id="viewItemId">
          <div class="col-sm-4">
            <div class="form-group">
              <label>Item Code</label>
              <input class="form-control" type="text" name="item_code" id="txtItemCode" readonly>
            </div>
          </div>

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
              <label>Current Stocks</label>
              <input class="form-control" type="text" name="current_stocks" id="txtCurrentStocks" readonly>
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
              <button class="btn btn-primary btn-sm btnReceiveItem"><i class="fa fa-plus"></i> Receive Item</button>
              <button class="btn btn-success btn-sm btnWithdrawItem"><i class="fa fa-plus"></i> Withdraw Item</button>
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
                          <th>ACTION</th>
                          <th>TRANSACTION DATE</th>
                          <th>ACTUAL DELIVERY DATE</th>
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
        <h4 class="modal-title"><b>Add Item Transaction Details</b></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form class="form-horizontal" id="frmSaveItemTransaction">
        @csrf
        <div class="modal-body">
            <input type="hidden" name="form_type" id="txtFormType" value="1">
            <input type="hidden" name="inventory_id" id="txtInventoryId">
            <div id="hiddenReceivingFields">
                <input type="hidden" name="transaction_invoice_no" id="txtTransactionInvoceNo">
                <input type="hidden" name="transaction_rcv_no" id="txtTransactionRcvNo">
                <input type="hidden" name="transaction_po_no" id="transactionPoNo">
            </div>

            <div class="row" id="rowSupplierInfo">
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Reference Number</label>
                  <select class="form-control select2 select2bs4 selTransactionItemCode" id="txtSelectTransactionItemCode" name="select_transaction_item_code">
                      <option value="">Select Reference Number</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Supplier Name</label>
                  <input class="form-control" type="text" name="transaction_supplier_name" id="txtTransactionItemSupplierName" readonly>
                </div>
              </div>
            </div>

            <div class="row">
                <div class="col-sm-6" id="colDeliveryDate">
                    <div class="form-group">
                        <label>Delivery Date</label>
                        <input class="form-control" type="text" name="transaction_delivery_date" id="txtTransactionDeliveryDate" readonly>
                    </div>
                </div>

                <div class="col-sm-6" id="colTransactionDate">
                    <div class="form-group">
                        <label>Transaction Date</label>
                        <input class="form-control" type="date" name="transaction_date" id="txtTransactionDate">
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label id="lblQty">Received Qty.</label>
                        <input class="form-control" type="number" step="0.01" name="transaction_qty" id="txtTransactionQty" required readonly>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6" id="colWithdrawalApprover" style="display:none;">
                    <div class="form-group">
                        <label>Checked By (Approver)</label>
                        <select class="form-control select2 select2bs4 selWithdrawalApprover" name="withdrawal_approver">
                            <option value="">Select Approver</option>
                            </select>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Remarks</label>
                        <input class="form-control" type="text" name="transaction_remarks" id="txtTransactionRemarks" autocomplete="off">
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btnSaveItemTransaction">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /.modal -->

<!-- MODALS Add Item Transaction -->
<div class="modal fade" id="modalExportInventory">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><b>Export Inventory Data</b></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

        <div class="modal-body">


            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>From: </label>
                        <input class="form-control" type="date" name="export_from" id="txtExportFrom" required>
                      </div>
                    </div>

                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>To: </label>
                        <input class="form-control" type="date" name="export_to" id="txtExportTo" required>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btnExportInventoryData">Export</button>
        </div>
    </div>
  </div>
</div>
<!-- /.modal -->

<script type="text/javascript">
  // Variables
  let dtItem, btnSaveItemDetails, frmsaveItemDetails, frmSaveItemTransaction, btnSaveItemTransaction, dtItemTransactionDetails;
</script>

@endsection

@section('js_content')
<!-- Custom Links -->
<script src="{{ asset('public/scripts/client/Inventory.js') }}"></script>

<!-- JS Codes -->
<script type="text/javascript">
$(document).ready(function () {

    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': _token
      }
    });

    frmsaveItemDetails = $("#frmsaveItemDetails");
    btnSaveItemDetails = $('.btnSaveItemDetails');

    frmSaveItemTransaction = $("#frmSaveItemTransaction");
    btnSaveItemTransaction = $('.btnSaveItemTransaction');

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

    // let groupColumn = 6;
    let itemGroupColumn = 8;

    dtItem = $("#tblItem").DataTable({
        "processing" : false,
        "serverSide" : true,
        ordering: false,
        "ajax" : {
            url: "{{ route('view_item_inventory') }}",
            data: function (param){
                // param.status = $(".selFilByStat").val();
            }
        },

        "columns":[
            { "data" : "action", },
            { "data" : "raw_status", },
            // { "data" : "delivery_date", },
            // { "data" : "invoice_no", },
            { "data" : "raw_category", orderable: false, },
            { "data" : "item_code", orderable: false, },
            { "data" : "item_name", orderable: false, },
            { "data" : "item_description", orderable: false, },
            { "data" : "min_stock", orderable: false, },
            { "data" : "max_stock", orderable: false, },
            { "data" : "current_stocks", orderable: false, },
            { "data" : "raw_unit_price", orderable: false, },
            { "data" : "dollar_rate", orderable: false, },
            { "data" : "converted_up", orderable: false, },
            { "data" : "item_uom", orderable: false, },
            { "data" : "remarks", orderable: false, },
            // { "data" : "user_name", orderable: false, },
            // { "data" : "raw_action2", orderable: false, },
        ],

      }).on( 'error', function () {

    });//end of dtItem

    $(".btnAddItemDetails").click(function(e){
        e.preventDefault();
        $("#modalSaveItemDetails").modal('show');

        getItemCode($('.selItemCode'));
    });

    $(document).on('click', '.btnExportInventory', function(e){
        e.preventDefault();
        $('#modalExportInventory').modal('show');
    });


    $(document).on('change', '#txtSelectItemCode', function() {
        // 1. Get the selected option element
        let selectedOption = $(this).find('option:selected');

        // 2. Pull data from the attributes we created in Part A
        let itemCode = selectedOption.data('code');
        let itemName = selectedOption.data('name');
        let itemUom = selectedOption.data('uom');
        let itemDesc = selectedOption.data('desc');
        let itemCurrency = selectedOption.data('currency');
        let itemPrice = selectedOption.data('uprice');

        $.ajax({
          url: 'check_item_if_exists',
          type: 'GET',
          data: { item_code: itemCode },
          success: function(response){
              if(response.exists){
                  // ✅ Existing → autofill
                  $('#txtAddMinStock').val(response.min_stock).prop('readonly', true);
                  $('#txtAddMaxStock').val(response.max_stock).prop('readonly', true);

              } else {
                  // ❌ New → allow input
                  $('#txtAddMinStock').val('').prop('readonly', false);
                  $('#txtAddMaxStock').val('').prop('readonly', false);
              }
          }
        });

        // 3. Update the UI fields
        $('#txtAddItemCode').val(itemCode);
        $('#txtAddItemName').val(itemName);
        $('#txtAddItemDescription').val(itemDesc);
        $('#txtAddUOM').val(itemUom);
        $('#txtAddUnitPrice').val(itemPrice);

        if (itemCurrency == 2) { // PHP
          $('#txtAddCurrency').text('₱');
          $('#txtCurrencyId').val(itemCurrency);
          $('#dollarRateId').attr('hidden',false);
          $('#convertedUnitPriceId').attr('hidden',false);


          $('#txtAddDollarRate').on('keyup', function() {
              // 1. Get the current Unit Price (PHP) and the conversion rate entered
              let unitPrice = parseFloat($('#txtAddUnitPrice').val()) || 0;
              let rate = parseFloat($(this).val()) || 0;
              // 2. Perform the calculation (Unit Price * Rate)
              let convertedValue = unitPrice / rate;

              // 3. Display the result in the target field
              // We format it to 2 decimal places
              if (rate > 0) {
                  let convertedValue = unitPrice / rate;

                  // 2. Use toFixed(2) for a clean decimal format
                  $('#txtConvertedRate').val(convertedValue.toFixed(2));
              } else {
                  // 3. If rate is 0 or deleted, set the result to 0 or empty
                  $('#txtConvertedRate').val('0.00');
              }

          });

        } else if (itemCurrency == 3) { // USD
            $('#txtAddCurrency').text('$');
            $('#txtCurrencyId').val(itemCurrency);
            $('#dollarRateId').attr('hidden',true);
            $('#convertedUnitPriceId').attr('hidden',true);
        } else {
            $('#txtAddCurrency').text('—'); // Default placeholder if no currency
        }

    });

    $("#frmsaveItemDetails").submit(function(e){
        e.preventDefault();
        SaveItemDetails();
    });

    $(document).on('click', '.btnViewItem', function () {
        let inventoryId = $(this).attr('inventory-id');

        // console.log("View Item ID:", itemId); // Debugging line to check the item ID
        $('#mdlViewItem').modal('show');
        $('#viewItemId').val(inventoryId);
        getItemDetailsByid(inventoryId);
        getTransactionDetails(inventoryId);


    })

    $(document).on('click', '.btnReceiveItem', function () {
        let itemCode = $('#txtItemCode').val();
        let inventoryId = $('#viewItemId').val();
        $('#txtInventoryId').val(inventoryId);
        $('#modalSaveItemTransaction').modal('show');
        $('#txtTransactionQty').prop('readonly', true);
        // Change this line: Wrap the selector in $()
        getItemByItemCode($('.selTransactionItemCode'), itemCode);
    });

   $(".btnWithdrawItem").click(function(e){
      e.preventDefault();
      let inventoryId = $('#viewItemId').val();
      $('#txtInventoryId').val(inventoryId);
      // getWithdrawalApprovers($('.selWithdrawalApprover'));

      // 1. Change UI to Withdrawal Mode
      $('#modalSaveItemTransaction .modal-title b').text('Withdraw Item Transaction');
      $('.btnSaveItemTransaction').text('Withdraw').removeClass('btn-primary').addClass('btn-danger');
      $('#lblQty').text('Withdrawal Qty.');
      $('#lblDate').text('Transaction Date');
      $('#txtFormType').val(2);

      // 2. Hide & Disable Receiving fields
      $('#rowSupplierInfo').hide();
      $('#colDeliveryDate').hide();
      $('#txtSelectTransactionItemCode, #txtTransactionItemSupplierName, #txtTransactionDeliveryDate').prop('disabled', true);
      $('#txtTransactionQty').prop('readonly', false);

      // 3. Show & Enable "Checked By" (Withdrawal Only)
      $('#colWithdrawalApprover').show();
      // $('.selWithdrawalApprover').prop('disabled', false).prop('required', true);

      $('#modalSaveItemTransaction').modal('show');
      getApproverList($('.selWithdrawalApprover'));
    });

    // Reset Modal on Close
    $('#modalSaveItemTransaction').on('hidden.bs.modal', function () {
        // Reset to Receiving Mode
        dtItem.draw();
        $('#txtFormType').val(1);
        $('#lblQty').text('Received Qty.');

        // Show & Enable Receiving fields
        $('#rowSupplierInfo, #colDeliveryDate').show();
        $('#txtSelectTransactionItemCode, #txtTransactionItemSupplierName, #txtTransactionDeliveryDate').prop('disabled', false);

        // Hide & Disable "Checked By"
        $('#colWithdrawalApprover').hide();
        // $('.selWithdrawalApprover').prop('disabled', true).prop
        //3('required', false);

        $('#frmSaveItemTransaction')[0].reset();
        $('.select2').val('').trigger('change');
    });

    $(document).on('change', '#txtSelectTransactionItemCode', function() {
        // 1. Get the selected option element
        let selectedOption = $(this).find('option:selected');

        // 2. Pull data from the attributes we created in Part A
        let referenceNo = selectedOption.data('ref');
        let rcvNo = selectedOption.data('rcvno');
        let delDate = selectedOption.data('deldate');
        let odrnum = selectedOption.data('odrnum');
        let qty = selectedOption.data('qty');
        let supplierName = selectedOption.data('supplier');


        // 3. Update the UI fields
        $('#txtTransactionInvoceNo').val(referenceNo);
        $('#txtTransactionRcvNo').val(rcvNo);
        $('#txtTransactionPoNo').val(odrnum);
        $('#txtTransactionItemSupplierName').val(supplierName);
        $('#txtTransactionQty').val(qty);
        $('#txtTransactionDeliveryDate').val(delDate);

    });

    $("#frmSaveItemTransaction").submit(function(e){
        e.preventDefault();
        SaveItemTransactionDetails();
    });

    $(document).on('click', '.btnApproveWithdrawal', function(){
      let transactionId = $(this).attr('data-id'); // Matches your Controller attr

      if(confirm("Are you sure you want to approve this withdrawal?")) {
          $.ajax({
              url: "{{ route('approve_withdrawal') }}",
              method: "POST",
              data: { id: transactionId }, // No _token needed here anymore!
              success: function(response) {
                  if(response.status == 0) {
                      alert(response.msg);
                      $("#mdlViewItem").modal('hide');
                      dtItemTransactionDetails.draw();
                      dtItem.draw();
                  }
              },
              error: function(xhr) {
                  // If you still get 419, the session is likely expired
                  if(xhr.status === 419) alert("Session expired. Please refresh.");
              }
          });
      }
    });


    $(document).on('click', '.btnRejectWithdrawal', function() {
      let transactionId = $(this).attr('data-id');

      // 1. Ask for rejection reason
      let remarks = prompt("Please enter a reason for rejection:");

      // 2. Only proceed if they didn't click 'Cancel' and didn't leave it empty
      if (remarks === null) {
          return; // User clicked cancel
      }

      if (remarks.trim() === "") {
          toastr.warning("Rejection remarks are required.");
          return;
      }

      if (confirm("Are you sure you want to reject this withdrawal?")) {
          $.ajax({
              url: "{{ route('reject_withdrawal') }}",
              method: "POST",
              data: {
                  id: transactionId,
                  rejection_remarks: remarks // Send the remarks here
              },
              success: function(response) {
                  if (response.status == 0) {
                      toastr.success(response.msg); // Using toastr for a better look
                      $("#mdlViewItem").modal('hide');

                      // Refresh both tables
                      if (typeof dtItemTransactionDetails !== 'undefined') dtItemTransactionDetails.draw();
                      if (typeof dtItem !== 'undefined') dtItem.draw();
                  } else if(respone.status == 2) {
                    toastr.warning(response.msg);
                  }else{
                    toastr.error(response.msg);
                  }
              },
              error: function(xhr) {
                  if (xhr.status === 419) toastr.error("Session expired. Please refresh.");
                  else toastr.error("An error occurred while rejecting.");
              }
          });
      }
  });

  $(document).on('click', '.btnExportInventoryData', function () {
      let from = $('#txtExportFrom').val();
      let to = $('#txtExportTo').val();

      if (!from || !to) {
          toastr.error('Please select both dates.', 'Error');
          return;
      }

      // Redirect to export route with params
      window.location.href = `export-inventory?from=${from}&to=${to}`;
  });



});
</script>
@endsection
