function rowStyle(row, index) {
    if (row.isDeleted === '1') {
        return {
            classes: 'text-muted font-weight-light'
        };
    }
    return {};
}

function addNewClient() {
    var data = $('#newClientForm').serializeArray();

    $.ajax({
        url: 'clients/client/add',
        type: 'post',
        data: data,
        success: function (response) {
            if (response === '1') {
                isChanged = true;
                $('#modalNewClient .result').attr("class", "result alert bg-success");
                $('#modalNewClient #resultMsg').html("Successfully added");
                $('#modalNewClient .result').show(1000);
                setTimeout(function () {
                    $('.result').hide(1000);
                    $('#modalNewClient').modal('hide');
                }, 2500);
            } else {
                $('#modalNewClient .result').attr("class", "result alert bg-danger");
                $('#modalNewClient #resultMsg').html('Something is going wrong. Updating failed.');
                $('#modalNewClient .result').show(1000);
            }
        }
    });
}

function openClient(clientId) {
    $.ajax({
        url: 'clients/client/open',
        type: 'post',
        dataType: 'json',
        data: {
            clientId: clientId
        },
        success: function (clientData) {
            fillClientModal(clientData);
            $('#modalClient').modal('show');
            $('#modalClient #deleteClient').prop('disabled', false);
            $('#modalClient #deleteClient').prop('hidden', false);
        }
    });
}

function fillClientModal(data) {
    $('#modalClient #submitService').text('Update Client');
    $('#modalClient #modalTitle').html((data.surname + ' ' + data.name + ' ' + data.patronymic).toUpperCase());
    $('#clientForm #clientId').val(data.clientId);
    $('#clientForm #isDeleted').val(data.isDeleted);
    $('#clientForm #surname').val(data.surname);
    $('#clientForm #name').val(data.name);
    $('#clientForm #patronymic').val(data.patronymic);
    $('#clientForm #phone').val(data.phone);
    $('#clientForm #dateOfBirth').val(data.dateOfBirth);
    if (data.isDeleted === '0') {
        $('#modalClient #deleteClient').text('Delete Client');
    } else {
        $('#modalClient #deleteClient').text('Restore Client');
    }
}

function updateClient() {
    var data = $('#modalClient #clientForm').serializeArray();
    $.ajax({
        url: 'clients/client/update',
        type: 'post',
        data: data,
        success: function (response) {
            if (response === '1') {
                isChanged = true;
                $('.result').attr("class", "result alert bg-success");
                $('#resultMsg').html("Successfully updated");
                $('.result').show(1000);
                setTimeout(function () {
                    $('.result').hide(1000);
                    $('#modalService').modal('hide');
                }, 2500);
            } else {
                $('.result').attr("class", "result alert bg-danger");
                $('#resultMsg').html('Something is going wrong. Updating failed.');
                $('.result').show(1000);
            }
        }
    });
}

function deleteClient() {
    var id = $('#modalClient #clientId').val();
    $.ajax({
        url: 'clients/client/delete',
        type: 'post',
        data: {
            clientId: id
        },
        success: function (response) {

            if (response === '1') {
                $('#modalClient .result').attr("class", "result alert bg-success");
                $('#modalClient #resultMsg').html("Successfully deleted");
                $('#modalClient .result').show(1000);
                setTimeout(function () {
                    $('#modalClient .result').hide(1000);
                    $('#modalClient').modal('hide');
                }, 2500);
            } else {
                $('#modalClient .result').attr("class", "result alert bg-danger");
                $('#modalClient #resultMsg').html('Something is going wrong. Deleting failed.');
                $('#modalClient .result').show(1000);
            }
        }
    });
}

function restoreClient() {
    var id = $('#modalClient #clientId').val();
    $.ajax({
        url: 'clients/client/restore',
        type: 'post',
        data: {
            clientId: id
        },
        success: function (response) {

            if (response === '1') {
                $('#modalClient .result').attr("class", "result alert bg-success");
                $('#modalClient #resultMsg').html("Successfully restored");
                $('#modalClient .result').show(1000);
                setTimeout(function () {
                    $('#modalClient .result').hide(1000);
                    $('#modalClient').modal('hide');
                }, 2500);
            } else {
                $('#modalClient .result').attr("class", "result alert bg-danger");
                $('#modalClient #resultMsg').html('Something is going wrong. Restoring failed.');
                $('#modalClient .result').show(1000);
            }
        }
    });
}