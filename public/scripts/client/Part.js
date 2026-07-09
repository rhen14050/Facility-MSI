// VARIABLES

// FUNCTIONS
// Save Part
function SavePart(){
    let url = globalLink.replace('link', 'save_part');
    let login = globalLink.replace('link', 'login');

    $.ajax({
        url: url,
        method: 'post',
        data: frmSavePart.serialize(),
        dataType: 'json',
        beforeSend() {
            btnSavePart.prop('disabled', true);
            btnSavePart.html('Saving...');
            $(".input-error", frmSavePart).text('');
            $(".form-control", frmSavePart).removeClass('is-invalid');
            cnfrmLoading.open();
        },
        success(data){
            btnSavePart.prop('disabled', false);
            btnSavePart.html('Save');
            cnfrmLoading.close();
            $("input[name='description']", frmSavePart).focus();

            if(data['auth'] == 1){
                if(data['result'] == 1){
                    toastr.success('Record Saved!');
                    frmSavePart[0].reset();
                    $(".input-error", frmSavePart).text('');
                    $(".form-control", frmSavePart).removeClass('is-invalid');
                    dtParts.draw();
                }
                else{
                    toastr.error('Saving Failed!');
                    if(data['error'] != null){
                        if(data['error']['description'] != null){
                            $("input[name='description']", frmSavePart).addClass('is-invalid');
                            $("input[name='description']", frmSavePart).siblings('.input-error').text(data['error']['description']);
                        }
                        else{
                            $("input[name='description']", frmSavePart).removeClass('is-invalid');
                            $("input[name='description']", frmSavePart).siblings('.input-error').text('');
                        }

                        if(data['error']['code'] != null){
                            $("input[name='code']", frmSavePart).addClass('is-invalid');
                            $("input[name='code']", frmSavePart).siblings('.input-error').text(data['error']['code']);
                        }
                        else{
                            $("input[name='code']", frmSavePart).removeClass('is-invalid');
                            $("input[name='code']", frmSavePart).siblings('.input-error').text('');
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
            btnSavePart.prop('disabled', false);
            btnSavePart.html('Save');
            toastr.error('Saving Failed!');
        }
    });
}

function GetPartById(partId){
    let url = globalLink.replace('link', 'get_part_by_id');
    let login = globalLink.replace('link', 'login');

    $.ajax({
        url: url,
        method: 'get',
        data: {
            part_id: partId
        },
        dataType: 'json',
        beforeSend() {
            btnSavePart.prop('disabled', true);
            btnSavePart.html('Saving...');
            $(".input-error", frmSavePart).text('');
            $(".form-control", frmSavePart).removeClass('is-invalid');
            cnfrmLoading.open();
            frmSavePart[0].reset();
            $('input[name="part_id"]', frmSavePart).val('');
        },
        success(data){
            btnSavePart.prop('disabled', false);
            btnSavePart.html('Save');
            cnfrmLoading.close();
            $("input[name='description']", frmSavePart).focus();

            if(data['auth'] == 1){
                if(data['part_info'] != null){
                    $("#mdlSavePart").modal('show');
                    $('input[name="part_id"]', frmSavePart).val(data['part_info']['id']);
                    $('input[name="description"]', frmSavePart).val(data['part_info']['description']);
                    $('input[name="code"]', frmSavePart).val(data['part_info']['code']);
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
            btnSavePart.prop('disabled', false);
            btnSavePart.html('Save');
            toastr.error('An error occured!');
        }
    });
}

function PartAction(partId, action, status){
    let url = globalLink.replace('link', 'part_action');
    let login = globalLink.replace('link', 'login');

    $.ajax({
        url: url,
        method: 'post',
        data: {
            _token: _token,
            part_id: partId,
            action: action,
            status: status,
        },
        dataType: 'json',
        beforeSend() {
            cnfrmLoading.open();
        },
        success(data){
            cnfrmLoading.close();
            dtParts.draw();

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