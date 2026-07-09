// VARIABLES

// FUNCTIONS
// Save Inventory
function SaveInventory(){
    let url = globalLink.replace('link', 'save_inventory');
    let login = globalLink.replace('link', 'login');

    $.ajax({
        url: url,
        method: 'post',
        data: frmSaveInventory.serialize(),
        dataType: 'json',
        beforeSend() {
            btnSaveInventory.prop('disabled', true);
            btnSaveInventory.html('Saving...');
            $(".input-error", frmSaveInventory).text('');
            $(".form-control", frmSaveInventory).removeClass('is-invalid');
            cnfrmLoading.open();
        },
        success(data){
            btnSaveInventory.prop('disabled', false);
            btnSaveInventory.html('Save');
            cnfrmLoading.close();
            $("input[name='description']", frmSaveInventory).focus();

            if(data['auth'] == 1){
                if(data['result'] == 1){
                    toastr.success('Record Saved!');
                    frmSaveInventory[0].reset();
                    $(".input-error", frmSaveInventory).text('');
                    $(".form-control", frmSaveInventory).removeClass('is-invalid');
                    $("#mdlSaveInventory").modal('hide');
                    dtInventories.draw();
                }
                else{
                    toastr.error('Saving Failed!');
                    if(data['error'] != null){
                        if(data['error']['description'] != null){
                            $("input[name='description']", frmSaveInventory).addClass('is-invalid');
                            $("input[name='description']", frmSaveInventory).siblings('.input-error').text(data['error']['description']);
                        }
                        else{
                            $("input[name='description']", frmSaveInventory).removeClass('is-invalid');
                            $("input[name='description']", frmSaveInventory).siblings('.input-error').text('');
                        }
                    }
                }
            }
            else{ // if session expired
                cnfrmAutoLogin.open();
            }
        },
        error(xhr, data, status){
            cnfrmLoading.close();
            btnSaveInventory.prop('disabled', false);
            btnSaveInventory.html('Save');
            toastr.error('Saving Failed!');
        }
    });
}

function GetInventoryById(inventoryId){
    let url = globalLink.replace('link', 'get_inventory_by_id');
    let login = globalLink.replace('link', 'login');

    $.ajax({
        url: url,
        method: 'get',
        data: {
            inventory_id: inventoryId
        },
        dataType: 'json',
        beforeSend() {
            btnSaveInventory.prop('disabled', true);
            btnSaveInventory.html('Saving...');
            $(".input-error", frmSaveInventory).text('');
            $(".form-control", frmSaveInventory).removeClass('is-invalid');
            cnfrmLoading.open();
            frmSaveInventory[0].reset();
            $('input[name="inventory_id"]', frmSaveInventory).val('');
        },
        success(data){
            btnSaveInventory.prop('disabled', false);
            btnSaveInventory.html('Save');
            cnfrmLoading.close();
            $("input[name='description']", frmSaveInventory).focus();

            if(data['auth'] == 1){
                if(data['inventory_info'] != null){
                    $("#mdlSaveInventory").modal('show');
                    $('input[name="inventory_id"]', frmSaveInventory).val(data['inventory_info']['id']);
                    $('input[name="description"]', frmSaveInventory).val(data['inventory_info']['description']);
                }
                else{
                    toastr.error('No record found.');
                }
            }
            else{ // if session expired
                cnfrmAutoLogin.open();
            }
        },
        error(xhr, data, status){
            cnfrmLoading.close();
            btnSaveInventory.prop('disabled', false);
            btnSaveInventory.html('Save');
            toastr.error('An error occured!');
        }
    });
}

function GetInventoryBohEoh(type, partId){
    let url = globalLink.replace('link', 'get_inventory_boh_eoh');
    let login = globalLink.replace('link', 'login');

    $.ajax({
        url: url,
        method: 'get',
        data: {
            type: type,
            part_id: partId,
        },
        dataType: 'json',
        beforeSend() {
            btnSaveInventory.prop('disabled', true);
            btnSaveInventory.html('Loading...');
            $(".input-error", frmSaveInventory).text('');
            $(".form-control", frmSaveInventory).removeClass('is-invalid');
            cnfrmLoading.open();
            $('input[name="boh"]', frmSaveInventory).val('');
            $('input[name="eoh"]', frmSaveInventory).val('');
        },
        success(data){
            btnSaveInventory.prop('disabled', false);
            btnSaveInventory.html('Save');
            cnfrmLoading.close();

            if(data['auth'] == 1){
                if(data['inventory_info'] != null){
                    $('input[name="boh"]', frmSaveInventory).val(data['inventory_info']['eoh']);
                    $('input[name="eoh"]', frmSaveInventory).val(data['inventory_info']['eoh']);
                }
                else{
                    $('input[name="boh"]', frmSaveInventory).val('0');
                    $('input[name="eoh"]', frmSaveInventory).val('0');
                }
            }
            else{ // if session expired
                cnfrmAutoLogin.open();
            }
        },
        error(xhr, data, status){
            cnfrmLoading.close();
            btnSaveInventory.prop('disabled', false);
            btnSaveInventory.html('Save');
            toastr.error('An error occured!');
            $('input[name="boh"]', frmSaveInventory).val('');
            $('input[name="eoh"]', frmSaveInventory).val('');
        }
    });
}

function InventoryAction(inventoryId, action, status){
    let url = globalLink.replace('link', 'inventory_action');
    let login = globalLink.replace('link', 'login');

    $.ajax({
        url: url,
        method: 'post',
        data: {
            _token: _token,
            inventory_id: inventoryId,
            action: action,
            status: status,
        },
        dataType: 'json',
        beforeSend() {
            cnfrmLoading.open();
        },
        success(data){
            cnfrmLoading.close();
            dtInventories.draw();

            if(data['auth'] == 1){
                if(data['result'] == 1){
                    toastr.success('Record Saved!');
                }
                else{
                    toastr.error('Saving Failed!');
                }
            }
            else{ // if session expired
                cnfrmAutoLogin.open();
            }
        },
        error(xhr, data, status){
            cnfrmLoading.close();
            toastr.error('An error occured!');
        }
    });
}

// function GetItemByRecNo(referenceNo, type, itemID){
//     let url = globalLink.replace('link', 'get_item_by_rec_no');
//     let login = globalLink.replace('link', 'login');

//     // console.log('referenceNo',referenceNo)
//     $.ajax({
//         url: url,
//         method: 'get',
//         data: {
//             // receiving_number: receivingNumber,
//             reference_no: referenceNo,
//             type: type,
//             item_id: itemID
//         },
//         dataType: 'json',
//         beforeSend() {
//             btnSaveInventory.prop('disabled', true);
//             btnSaveInventory.html('Loading...');
//             $(".input-error", frmSaveInventory).text('');
//             $(".form-control", frmSaveInventory).removeClass('is-invalid');
//             cnfrmLoading.open();
//             // frmSaveInventory[0].reset();
//             // $('input[name="inventory_id"]', frmSaveInventory).val('');
//         },
//         success(data){
//             btnSaveInventory.prop('disabled', false);
//             btnSaveInventory.html('Save');
//             cnfrmLoading.close();
//             // $("input[name='description']", frmSaveInventory).focus();

//             if(data['auth'] == 1){
//                 if(data['result'] == 1) {
//                     if(data['item'] != null){
//                         $('.btnSearchItem').hide();
//                         $('.btnSearchAgain').show();
//                         $('input[name="receiving_no"]', frmSaveInventory).prop('readonly', true);
//                         $('input[name="reference_no"]', frmSaveInventory).prop('readonly', true);
//                         // $("#mdlSaveInventory").modal('show');
//                         $('input[name="po_no"]', frmSaveInventory).val(data['item']['reference_po_number']);
//                         $('input[name="item_code"]', frmSaveInventory).val(data['item']['item_code']);
//                         $('input[name="item_description"]', frmSaveInventory).val(data['item']['long_description']);
//                         // $('input[name="quantity"]', frmSaveInventory).val(data['item']['quantity_received']);
//                         $('input[name="unit_price"]', frmSaveInventory).val(data['item']['unit_price']);
//                         $('input[name="delivery_date"]', frmSaveInventory).val(data['item']['actual_delivery_date']);
//                         $('input[name="supplier_name"]', frmSaveInventory).val(data['item']['supplier_name']);
//                         // $('input[name="boh"]', frmSaveInventory).val(data['item']['boh']);
//                         // $('input[name="eoh"]', frmSaveInventory).val(data['item']['eoh']);
//                     }
//                     else{
//                         $('input[name="po_no"]', frmSaveInventory).val('');
//                         $('input[name="item_id"]', frmSaveInventory).val('');
//                         $('input[name="item_name"]', frmSaveInventory).val('');
//                         $('input[name="item_code"]', frmSaveInventory).val('');
//                         $('input[name="item_description"]', frmSaveInventory).val('');
//                         $('input[name="quantity"]', frmSaveInventory).val('');
//                         $('input[name="unit_price"]', frmSaveInventory).val('');
//                         $('input[name="delivery_date"]', frmSaveInventory).val('');
//                         $('input[name="supplier_name"]', frmSaveInventory).val('');
//                         // $('input[name="boh"]', frmSaveInventory).val('');
//                         // $('input[name="eoh"]', frmSaveInventory).val('');
//                         toastr.warning('No record found.');
//                     }
//                 }
//                 else if(data['result'] == 2) {
//                     $('input[name="po_no"]', frmSaveInventory).val('');
//                     $('input[name="item_id"]', frmSaveInventory).val('');
//                     $('input[name="item_name"]', frmSaveInventory).val('');
//                     $('input[name="item_code"]', frmSaveInventory).val('');
//                     $('input[name="item_description"]', frmSaveInventory).val('');
//                     $('input[name="quantity"]', frmSaveInventory).val('');
//                     $('input[name="unit_price"]', frmSaveInventory).val('');
//                     $('input[name="delivery_date"]', frmSaveInventory).val('');
//                     // $('input[name="boh"]', frmSaveInventory).val('');
//                     // $('input[name="eoh"]', frmSaveInventory).val('');
//                     toastr.warning('Item already received!');
//                 }
//             }
//             else{ // if session expired
//                 cnfrmAutoLogin.open();
//             }
//         },
//         error(xhr, data, status){
//             cnfrmLoading.close();
//             btnSaveInventory.prop('disabled', false);
//             btnSaveInventory.html('Save');
//             toastr.error('An error occured!');
//         }
//     });
// }

// function GetItemsByRecNo(receivingNumber, referenceNo, source){
//     let url = globalLink.replace('link', 'get_items_by_rec_no');
//     let login = globalLink.replace('link', 'login');

//     // console.log('referenceNo', referenceNo);

//     $.ajax({
//         url: url,
//         method: 'get',
//         data: {
//             receiving_number: receivingNumber,
//             reference_no: referenceNo,
//             source: source,
//         },
//         dataType: 'json',
//         beforeSend() {
//             btnSaveInventory.prop('disabled', true);
//             btnSaveInventory.html('Loading...');
//             $(".input-error", frmSaveInventory).text('');
//             $(".form-control", frmSaveInventory).removeClass('is-invalid');
//             cnfrmLoading.open();
//             // frmSaveInventory[0].reset();
//             // $('input[name="inventory_id"]', frmSaveInventory).val('');
//         },
//         success(data){
//             btnSaveInventory.prop('disabled', false);
//             btnSaveInventory.html('Save');
//             cnfrmLoading.close();
//             // $("input[name='description']", frmSaveInventory).focus();

//             let htmlSelItem = '<option value="">(Select Item)</option>';

//             $('select[name="item_id"]', frmSaveInventory).html(htmlSelItem);

//             if(data['auth'] == 1){
//                 // if(data['result'] == 1) {
//                     if(data['item'].length > 0){
//                         $('select[name="sel_source"]', frmSaveInventory).prop('disabled', true);
//                         $('.btnSearchItem').hide();
//                         $('.btnSearchAgain').show();
//                         $('input[name="receiving_no"]', frmSaveInventory).val(data['item'][0]['receiving_number']).prop('readonly', true);
//                         $('input[name="reference_no"]', frmSaveInventory).val(data['item'][0]['other_reference']).prop('readonly', true);
//                         $('input[name="item_code"]', frmSaveInventory).val(data['item'][0]['item_code']).prop('readonly', true);
//                         for(let index = 0; index < data['item'].length; index++) {

//                             htmlSelItem += `
//                                 <option value="${data['item'][index]['item_id']}">
//                                     ${data['item'][index]['item_code']} / ${data['item'][index]['item_name']} (${data['item'][index]['receiving_number']})
//                                 </option>`;                     
//                         }

//                         $('select[name="item_id"]', frmSaveInventory).html(htmlSelItem);

//                         // $('input[name="po_no"]', frmSaveInventory).val(data['item'][0]['reference_po_number']);
//                         $('input[name="po_no"]', frmSaveInventory).val('');
//                         $('input[name="item_code"]', frmSaveInventory).val('');
//                         $('input[name="quantity"]', frmSaveInventory).val('');
//                         $('input[name="unit_price"]', frmSaveInventory).val('');
//                         $('input[name="delivery_date"]', frmSaveInventory).val('');
//                         // $('input[name="boh"]', frmSaveInventory).val('');
//                         // $('input[name="eoh"]', frmSaveInventory).val('');
//                     }
//                     else{
//                         $('input[name="po_no"]', frmSaveInventory).val('');
//                         $('input[name="item_id"]', frmSaveInventory).val('');
//                         $('input[name="item_name"]', frmSaveInventory).val('');
//                         $('input[name="item_code"]', frmSaveInventory).val('');
//                         $('input[name="quantity"]', frmSaveInventory).val('');
//                         $('input[name="unit_price"]', frmSaveInventory).val('');
//                         $('input[name="delivery_date"]', frmSaveInventory).val('');
//                         // $('input[name="boh"]', frmSaveInventory).val('');
//                         // $('input[name="eoh"]', frmSaveInventory).val('');
//                         toastr.warning('No record found.');
//                     }
//             }
//             else{ // if session expired
//                 cnfrmAutoLogin.open();
//             }
//         },
//         error(xhr, data, status){
//             cnfrmLoading.close();
//             btnSaveInventory.prop('disabled', false);
//             btnSaveInventory.html('Save');
//             toastr.error('An error occured!');
//         }
//     });
// }

function getItemDetailsByid(itemId){
    let url = globalLink.replace('link', 'get_item_details_by_id');

    $.ajax({
        url: url,
        method: 'get',
        data: {
            item_id: itemId,
        },
        dataType: 'json',
        beforeSend() {
        },
        success(data){

            if(data['auth'] == 1){
                if(data['result'] == 1) {
                    if(data['item'] != null){

                        $('input[name="item_name"]').val(data['item'].item_name);
                        $('input[name="item_code"]').val(data['item'].item_code);
                        $('input[name="item_description"]').val(data['item'].item_description);
                        $('input[name="item_remarks"]').val(data['item'].remarks);
                        $('input[name="current_stocks"]').val(data['item'].eoh);
                    }
                    else{
                        $('input[name="item_name"]').val('');
                        $('input[name="item_code"]').val('');
                        $('input[name="quantity"]').val('');
                        $('input[name="unit_price"]').val('');
                        $('input[name="boh"]').val('');
                        $('input[name="eoh"]').val('');
                        toastr.warning('No record found.');
                    }
                }
                else{
                    toastr.warning("There's a pending request.");
                    $('input[name="item_name"]').val('');
                    $('input[name="item_code"]').val('');
                    $('input[name="quantity"]').val('');
                    $('input[name="unit_price"]').val('');
                    $('input[name="boh"]').val('');
                    $('input[name="eoh"]').val('');
                    toastr.error('An error occured.');
                }
            }
            else{ // if session expired
                // cnfrmAutoLogin.open();
            }
        },
        error(xhr, data, status){
        }
    });
}

function getItemCode(cboElement){
    let url = globalLink.replace('link', 'get_item_code');
    let login = globalLink.replace('link', 'login');

    $.ajax({

    url: url,
    method: "get",
    dataType: "json",
    beforeSend: function(){
            result = '<option value=""> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(JsonObject){
            result = '';
            if(JsonObject['item'].length > 0){
                result = '<option selected disabled>-- Select One -- </option>';
                for (let index = 0; index < JsonObject['item'].length; index++) {
                    let item = JsonObject['item'][index];
                    let description = item.long_description;
                    // console.log('item', item)
                    let displayDesc = (description && description !== "") ? description : "N/A";

                    // FIX: Escape double quotes so they don't break the HTML attribute
                    let escapedDesc = displayDesc.replace(/"/g, '&quot;');
                    let escapedName = item.item_name.replace(/"/g, '&quot;');

                    // Store name and description in data attributes for instant retrieval
                    result += '<option value="' + item.item_id + '" ' +
                            'data-name="' + escapedName + '" ' +
                            'data-code="' + item.item_code + '" ' +
                            'data-uom="' + item.unit_of_measure_code + '" ' +
                            'data-currency="' + item.currency + '" ' +
                            'data-uprice="' + item.unit_price + '" ' +
                            'data-desc="' + escapedDesc + '">' + 
                            '[' + item.item_code + '] - ' + escapedName + ' - ' + escapedDesc +
                            '</option>';
                }


            }
            else{
                result = '<option value=""> -- No record found -- </option>';
            }

            cboElement.html(result);
        },
        error: function(data, xhr, status){
            result = '<option value=""> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }

    });
    $('#txtSelectItemCode').html(result);
}


function SaveItemDetails(){
    let url = globalLink.replace('link', 'save_item_details');

    $.ajax({
        url: url,
        method: 'post',
        data: frmsaveItemDetails.serialize(),
        dataType: 'json',
        beforeSend() {
            // btnSaveItemDetails.prop('disabled', true);
            // btnSaveItemDetails.html('Saving...');
            // $(".input-error", frmSaveInventory).text('');
            // $(".form-control", frmSaveInventory).removeClass('is-invalid');
            // cnfrmLoading.open();
        },
        success(data){
            btnSaveItemDetails.prop('disabled', false);
            btnSaveItemDetails.html('Save');
            // $("input[name='description']", frmsaveItemDetails).focus();

            if(data['auth'] == 1){
                if(data['result'] == 1){
                    toastr.success('Record Saved!');
                    frmsaveItemDetails[0].reset();
                    $(".input-error", frmsaveItemDetails).text('');
                    $(".form-control", frmsaveItemDetails).removeClass('is-invalid');
                    $("#modalSaveItemDetails").modal('hide');
                    dtItem.draw();
                }
                else{
                    if(data['result'] == 2){
                        toastr.error(data['error']['duplicate'], 'Saving Failed!');
                    }else{
                        toastr.error(data['error'], 'Saving Failed!');
                    }
                }
            }
            else{ // if session expired
                // cnfrmAutoLogin.open();
            }
        },
        error(xhr, data, status){
            btnSaveItemDetails.prop('disabled', false);
            btnSaveItemDetails.html('Save');
            toastr.error('Saving Failed!');
        }
    });

    
}

function getItemByItemCode(cboElement, itemCode) {
    // Ensure cboElement is a jQuery object even if a string is passed
    let url = globalLink.replace('link', 'get_item_by_item_code');
    let $cbo = $(cboElement); 
    

    $.ajax({
        url: url,
        method: "get",
        data: { item_code: itemCode },
        dataType: "json",
        beforeSend: function() {
            $cbo.html('<option value=""> -- Loading -- </option>');
        },
        success: function(JsonObject) {
            let result = '';
            if (JsonObject['item'] && JsonObject['item'].length > 0) {
                result = '<option selected disabled>-- Select One -- </option>';
                
                for (let index = 0; index < JsonObject['item'].length; index++) {
                    let item = JsonObject['item'][index];
                    let orderNo = item.order_number ? item.order_number : "N/A";
                    let referenceNo = item.reference_no ? item.reference_no : "N/A";
                    let itemId = item.item_id ? item.item_id : "N/A";

                    result += `<option value="${item.item_id}" 
                            data-qty="${item.quantity || ''}"
                            data-odrnum="${orderNo || ''}"
                            data-deldate="${item.actual_delivery_date || ''}"
                            data-rcvno="${item.receiving_number || ''}"
                            data-ref="${referenceNo || ''}"
                            data-supplier="${item.supplier_name || ''}">
                            [${item.reference_no}] - ${item.order_number}
                    </option>`;
                }
            } else {
                result = '<option value=""> -- No record found -- </option>';
            }
            $cbo.html(result);
        },
        error: function(xhr, status, error) {
            $cbo.html('<option value=""> -- Reload Again -- </option>');
            console.error("AJAX Error:", status, error);
        }
    });
}

function SaveItemTransactionDetails(){
    let url = globalLink.replace('link', 'save_item_transaction_details');

    $.ajax({
        url: url,
        method: 'post',
        data: frmSaveItemTransaction.serialize(),
        dataType: 'json',
        beforeSend() {
            btnSaveItemTransaction.prop('disabled', true);
            btnSaveItemTransaction.html('Saving...');
            $(".input-error", frmSaveItemTransaction).text('');
            $(".form-control", frmSaveItemTransaction).removeClass('is-invalid');
            // cnfrmLoading.open();
        },
        success(data){
            btnSaveItemTransaction.prop('disabled', false);
            btnSaveItemTransaction.html('Save');

            if(data['auth'] == 1){
                if(data['result'] == 1){
                    toastr.success('Record Saved!');
                    frmSaveItemTransaction[0].reset();
                    $(".input-error", frmSaveItemTransaction).text('');
                    $(".form-control", frmSaveItemTransaction).removeClass('is-invalid');
                    $("#modalSaveItemTransaction").modal('hide');
                    dtItemTransactionDetails.draw();
                }
                else if(data['result'] == 2) {
                    toastr.error(data['msg']);
                    frmSaveItemTransaction[0].reset();
                    $(".input-error", frmSaveItemTransaction).text('');
                    $('.selWithdrawalApprover ').val('').trigger('change');
                    $(".form-control", frmSaveItemTransaction).removeClass('is-invalid');
                    // $("#modalSaveItemTransaction").modal('hide');
                    dtItemTransactionDetails.draw();
                }
                else{
                    toastr.error('Saving Failed!');
                }
            }
            else{ // if session expired
                // cnfrmAutoLogin.open();
            }
        },
        error(xhr, data, status){
            btnSaveItemTransaction.prop('disabled', false);
            // btnSaveItemTransaction.html('Save');
            toastr.error('Saving Failed!');
        }
    });
}

function getTransactionDetails(itemId) {
    let url = globalLink.replace('link', 'view_transaction_details');

    dtItemTransactionDetails = $('#tblTransactionDetails').DataTable({
        "destroy": true, 
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: url,
            data: function (param) {
                param.item_id = itemId;
            }
        },
        "drawCallback": function(settings) {
            let api = this.api();
            let rows = api.rows().data().toArray();

            if (rows.length > 0) {
                // Grab the LAST row in the array (the most recent transaction)
                let latestRow = rows[0];
                let latestEoh = latestRow.eoh; 

                // If the data contains HTML tags like <strong>, this strip them out
                let cleanValue = $("<div/>").html(latestEoh).text();

                // console.log('Latest EOH (Last Row):', cleanValue);
                $('input[name="current_stocks"]').val(cleanValue);
            } else {
                $('input[name="current_stocks"]').val(0);
            }
        },
        "columns": [
            { "data": "action" },
            { "data": "transaction_date" },
            { "data": "del_date" },
            { "data": "supp_name" },
            { "data": "inv_no" },
            { "data": "boh" },
            { "data": "in" },
            { 
                "data": "out",
                "render": function(data, type, row) {
                    let form = parseInt(row.form);
                    let status = parseInt(row.approval_status);

                    if(form == 2) {
                        if(status == 1) {
                            return data + ' <br><span class="badge badge-warning">Pending Approval</span>';
                        } else if(status == 3) {
                            return data + ' <br><span class="badge badge-danger">Rejected</span>';
                        }
                    }
                    return data;
                }
            },
            { 
                "data": "eoh",
                "render": function(data, type, row) {
                    let form = parseInt(row.form);
                    let status = parseInt(row.approval_status);

                    if(form == 2) {
                        if(status == 1) {
                            return '<span class="text-muted" title="Waiting for approval">' + data + ' (Pending)</span>';
                        } else if(status == 3) {
                            return '<span class="text-danger" style="text-decoration: line-through;" title="Rejected - Stock not deducted">' + data + ' (Cancelled)</span>';
                        }
                    }
                    return '<strong>' + data + '</strong>';
                }
            },
            { "data": "raw_remarks" },
        ],
    });
}

function getApproverList(cboElement){
     // Ensure cboElement is a jQuery object even if a string is passed
    let url = globalLink.replace('link', 'get_approver_list');
    let $cbo = $(cboElement); 
    

    $.ajax({
        url: url,
        method: "get",
        // data: { item_code: itemCode },
        dataType: "json",
        beforeSend: function() {
            $cbo.html('<option value=""> -- Loading -- </option>');
        },
        success: function(JsonObject) {
            // console.log('approver list', JsonObject);
            let result = '';
            if (JsonObject['approvers'] && JsonObject['approvers'].length > 0) {
                console.log('approver list', JsonObject['approvers']);
                result = '<option selected disabled>-- Select One -- </option>';
                
                for (let index = 0; index < JsonObject['approvers'].length; index++) {
                    let approver = JsonObject['approvers'][index];

                    result += `<option value="${approver.id}">
                            ${approver.name}
                    </option>`;
                }
            } else {
                result = '<option value=""> -- No record found -- </option>';
            }
            $cbo.html(result);
        },
        error: function(xhr, status, error) {
            $cbo.html('<option value=""> -- Reload Again -- </option>');
            console.error("AJAX Error:", status, error);
        }
    });
}



