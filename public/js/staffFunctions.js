function rowStyle(row, index) {
    if (row.isDeleted === '1') {
        return {
            classes: 'text-muted font-weight-light'
        }
    }
    return {}
}

function openPosition(positionId) {
    $.ajax({
        url: 'staff/position/open',
        type: 'post',
        dataType: 'json',
        data: {
            positionId: positionId
        },
        success: function (data) {
            $('#modalPosition').modal('show');
            $('#submitPosition').html('Update Position');
            fillPositionForm(data);
            $('#deletePosition').prop('disabled', false);
            $('#deletePosition').prop('hidden', false);
        }
    });
}

function fillPositionForm(data) {
    $('#modalPosition #modalTitle').html(data.positionName);
    $('#positionId').val(data.positionId);
    $('#positionName').val(data.positionName);
    $('#isPositionDeleted').val(data.isDeleted);
    if (data.isDeleted === '0') {
        $('#deletePosition').text('Delete Position');
    } else {
        $('#deletePosition').text('Restore Position');
    }
}

function openEmployee(employeeId) {
    $.ajax({
        url: 'staff/employee/open',
        type: 'post',
        dataType: 'json',
        data: {
            employeeId: employeeId
        },
        success: function (data) {
            $('#modalEmployee').modal('show');
            $('#submitEmployee').html('Update Employee');
            fillEmployeeForm(data);
            $('#deleteEmployee').prop('disabled', false);
            $('#deleteEmployee').prop('hidden', false);
        }
    });
}

function fillEmployeeForm(data) {
    $('#modalEmployee #modalTitle').html((data.surname + ' ' + data.name + ' ' + data.patronymic).trim());
    $('#employeeId').val(data.employeeId);
    $('#surname').val(data.surname);
    $('#name').val(data.name);
    $('#patronymic').val(data.patronymic);
    $('#position').val(data.positionId);
    $('#dateOfBirth').val(data.dateOfBirth);
    $('#isEmployeeDeleted').val(data.isDeleted);
    if (data.isDeleted === '0') {
        $('#deleteEmployee').text('Delete Employee');
    } else {
        $('#deleteEmployee').text('Restore Employee');
    }
}

function addPosition() {
    isChanged = true;
    var data = $('#positionForm').serializeArray();

    $.ajax({
        url: 'staff/position/add',
        type: 'post',
        data: data,
        success: function (response) {
            if (response === '1') {
                isChanged = true;
                $('.result').attr("class", "result alert bg-success");
                $('#resultMsg').html("Successfully added");
                $('.result').show(1000);
                setTimeout(function () {
                    $('.result').hide(1000);
                    $('#modalPosition').modal('hide');
                }, 2500);
            } else {
                $('.result').attr("class", "result alert bg-danger");
                $('#resultMsg').html('Something is going wrong. Updating failed.');
                $('.result').show(1000);
            }
        }
    });
}

function updatePosition() {
    var data = $('#positionForm').serializeArray();
    $.ajax({
        url: 'staff/position/update',
        type: 'post',
        data: data,
        success: function (response) {

            if (response === '1') {
                $('.result').attr("class", "result alert bg-success");
                $('#resultMsg').html("Successfully updated");
                $('.result').show(1000);
                setTimeout(function () {
                    $('.result').hide(1000);
                    $('#modalPosition').modal('hide');
                }, 2500);
            } else {
                $('.result').attr("class", "result alert bg-danger");
                $('#resultMsg').html('Something is going wrong. Updating failed.');
                $('.result').show(1000);
            }
        }
    });
}

function loadPositionsList($el) {
    $.ajax({
        url: 'staff/position/list',
        dataType: 'json',
        success: function (data) {
            $.each(data, function (key, value) {
                $el.append($("<option></option>")
                    .attr("value", value.positionId)
                    .text(value.positionName));
            });
        }
    });
}

function reloadPositionsList($el) {
    $el.html("<option></option>");
    loadPositionsList($el);
}

function validateEmployeeForm() {
    var isValid = true;

    $('#name, #position').each(function () {
        if ($(this).val() === '') {
            isValid = false;
            $(this).addClass('is-invalid');
        }
    });
    return isValid;
}

function addEmployee() {
    isChanged = true;
    var data = $('#employeeForm').serializeArray();

    $.ajax({
        url: 'staff/employee/add',
        type: 'post',
        data: data,
        success: function (response) {
            if (response === '1') {
                isChanged = true;
                $('.result').attr("class", "result alert bg-success");
                $('#resultMsg').html("Successfully added");
                $('.result').show(1000);
                setTimeout(function () {
                    $('.result').hide(1000);
                    $('#modalEmployee').modal('hide');
                }, 2500);
            } else {
                $('.result').attr("class", "result alert bg-danger");
                $('#resultMsg').html('Something is going wrong. Updating failed.');
                $('.result').show(1000);
            }
        }
    });
}

function updateEmployee() {
    var data = $('#employeeForm').serializeArray();
    $.ajax({
        url: 'staff/employee/update',
        type: 'post',
        data: data,
        success: function (response) {

            if (response === '1') {
                $('.result').attr("class", "result alert bg-success");
                $('#resultMsg').html("Successfully updated");
                $('.result').show(1000);
                setTimeout(function () {
                    $('.result').hide(1000);
                    $('#modalEmployee').modal('hide');
                }, 2500);
            } else {
                $('.result').attr("class", "result alert bg-danger");
                $('#resultMsg').html('Something is going wrong. Updating failed.');
                $('.result').show(1000);
            }
        }
    });
}

function deleteEmployee() {
    var id = $('#employeeForm #employeeId').val();
    $.ajax({
        url: 'staff/employee/delete',
        type: 'post',
        data: {
            employeeId: id
        },
        success: function (response) {

            if (response === '1') {
                $('#modalEmployee .result').attr("class", "result alert bg-success");
                $('#modalEmployee #resultMsg').html("Successfully deleted");
                $('#modalEmployee .result').show(1000);
                setTimeout(function () {
                    $('.result').hide(1000);
                    $('#modalEmployee').modal('hide');
                }, 2500);
            } else {
                $('#modalEmployee .result').attr("class", "result alert bg-danger");
                $('#modalEmployee #resultMsg').html('Something is going wrong. Deleting failed.');
                $('#modalEmployee .result').show(1000);
            }
        }
    });
}

function restoreEmployee() {
    var id = $('#employeeForm #employeeId').val();
    $.ajax({
        url: 'staff/employee/restore',
        type: 'post',
        data: {
            employeeId: id
        },
        success: function (response) {

            if (response === '1') {
                $('#modalEmployee .result').attr("class", "result alert bg-success");
                $('#modalEmployee #resultMsg').html("Successfully restored");
                $('#modalEmployee .result').show(1000);
                setTimeout(function () {
                    $('#modalEmployee .result').hide(1000);
                    $('#modalEmployee').modal('hide');
                }, 2500);
            } else {
                $('#modalEmployee .result').attr("class", "result alert bg-danger");
                $('#modalEmployee #resultMsg').html('Something is going wrong. Restoring failed.');
                $('#modalEmployee .result').show(1000);
            }
        }
    });
}

function deletePosition() {
    var id = $('#positionForm #positionId').val();
    $.ajax({
        url: 'staff/position/delete',
        type: 'post',
        data: {
            positionId: id
        },
        success: function (response) {

            if (response === '1') {
                $('#modalPosition .result').attr("class", "result alert bg-success");
                $('#modalPosition #resultMsg').html("Successfully deleted");
                $('#modalPosition .result').show(1000);
                setTimeout(function () {
                    $('.result').hide(1000);
                    $('#modalPosition').modal('hide');
                }, 2500);
            } else {
                $('.result').attr("class", "result alert bg-danger");
                $('#modalPosition #resultMsg').html('Something is going wrong. Deleting failed.');
                $('.result').show(1000);
            }
        }
    });
}

function restorePosition() {
    var id = $('#positionForm #positionId').val();
    $.ajax({
        url: 'staff/position/restore',
        type: 'post',
        data: {
            positionId: id
        },
        success: function (response) {

            if (response === '1') {
                $('#modalPosition .result').attr("class", "result alert bg-success");
                $('#modalPosition #resultMsg').html("Successfully restored");
                $('#modalPosition .result').show(1000);
                setTimeout(function () {
                    $('#modalPosition .result').hide(1000);
                    $('#modalPosition').modal('hide');
                }, 2500);
            } else {
                $('#modalPosition .result').attr("class", "result alert bg-danger");
                $('#modalPosition #resultMsg').html('Something is going wrong. Restoring failed.');
                $('#modalPosition .result').show(1000);
            }
        }
    });
}