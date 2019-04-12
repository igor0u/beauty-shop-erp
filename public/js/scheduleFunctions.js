function createEvent(employeeId, employeeName, date, start) {
    $('#modalVisit').modal('show');
    $('#btnPlus').click();
    $('#visitDate').val(date);
    $('#employeeId').attr('value', employeeId);
    $('#employeeName').val(employeeName);
    $('#startTime').val(start);
    $('#modalTitle').html('New Visit');
    $('#deleteVisit').prop('disabled', true);
    $('#deleteVisit').prop('hidden', true);
    $('#submitVisit').html('Add Visit');
}

function openEvent(visitId) {
    $.ajax({
        url: 'schedule/visit/open',
        type: 'post',
        data: {
            visitId: visitId
        },
        success: function (response) {
            visitData = JSON.parse(response);
            openVisit(visitData)
        }
    });
}

function openVisit(visitData) {
    fillForm(visitData);
    $('#modalVisit').modal('show');
    $('#submitVisit').html('Update Visit');
    $('#modalTitle').html('Visit');
    $('.client').each(function () {
        $(this).prop("disabled", true);
    });
    $('#clearClientData').prop("disabled", true);
    $('#deleteVisit').prop("disabled", false);
    $('#submitVisit').prop("disabled", true);
}

function fillForm(data) {
    fillCommonForm(data.visitInfo);
    fillServices(data.services)
}

function fillCommonForm(visitInfo) {
    $('#visitId').attr('value', visitInfo.visitId);
    $('#clientId').attr('value', visitInfo.clientId);
    $('#surname').val(visitInfo.clientSurname);
    $('#name').val(visitInfo.clientName);
    $('#patronymic').val(visitInfo.clientPatronymic);
    $('#phone').val(visitInfo.clientPhone);
    $('#visitDate').val(visitInfo.visitDate);
}

function fillServices(services) {
    services.forEach(function (s) {
        $('#btnPlus').click();
        var row = $('#servicesTable').find('.recordset').last();
        row.find('#serviceId').attr('value', s.serviceId);
        row.find('#orderedServiceId').attr('value', s.orderedServiceId);
        row.find('#employeeId').attr('value', s.employeeId);
        row.find('#employeeName').val(s.employeeSurname + " " + s.employeeName + " " + s.employeePatronymic);
        row.find('#service').val(s.serviceName);
        row.find('#startTime').val(s.startTime);
        row.find('#endTime').val(s.endTime);
        row.find('#endTime').trigger('change');
        row.find('#measurementUnitAbbr').prop('title', s.measurementUnit);
        row.find('#measurementUnitAbbr').text(s.measurementUnitAbbr);
        changeReadonlyForDuration(row, s.measurementUnitId);
        row.find('#quantity').val(s.quantity);
        row.find('#serviceCost').val(s.cost);
        row.find('#discount').val(s.discount);
        row.find('.price').trigger('change');
    });

}

function parseTime(time) {
    var timeComponents = time.split(':');
    return parseInt(timeComponents[0]) * 60 + parseInt(timeComponents[1]);
}

function getTimeInHoursMinutes(minutes) {
    var resultMinutes = minutes % 60;
    var resultHours = (minutes - resultMinutes) / 60;
    return ('0' + resultHours).slice(-2) + ':' + ('0' + resultMinutes).slice(-2);
}

function recalculate(el) {
    var $row = $(el).closest('.recordset');
    var $cost = $row.find('.service-cost').val();
    var $quantity = $row.find('.service-quantity').val();
    var $discount = $row.find('.service-discount').val();
    var $total = ($cost * $quantity * (1 - $discount / 100));
    var $totalRounded = Math.round($total * 100) / 100;
    $row.find('.total-price').val($totalRounded);
    $row.find('.total-field').text($totalRounded.toFixed(2));
    recalculateGrandTotal();
}

function recalculateGrandTotal() {
    var $grandTotal = 0;
    var $allTotalPrices = $('#servicesTable').find('.total-price');
    $.each($allTotalPrices, function () {
        $grandTotal += parseFloat($(this).val());
    });
    $('#grandTotal').text($grandTotal.toFixed(2));
}

function addVisit(calendar) {
    var $data = {};
    var $commonData = {};
    $('#commonForm :input').each(function () {
        $commonData[this.name] = $(this).val();
    });
    $data.common = $commonData;
    var $orderedServices = [];
    $('.recordset').each(function () {
        var $service = {};
        $(this).find(':input').each(function () {
            $service[this.name] = $(this).val();
        });
        $orderedServices.push($service);
    });
    $data.orderedServices = $orderedServices;
    console.log($data);
    $.ajax({
        url: 'schedule/visit/add',
        type: 'POST',
        data: 'visit=' + JSON.stringify($data),
        success: function (response) {
            if (response === '1') {
                $('.result').attr("class", "result alert bg-success");
                $('#resultMsg').html("Successfully added");
                $('.result').show(1000);
                //$("#addPositionForm")[0].reset();
                setTimeout(function () {
                    $('.result').hide(1000)
                    $('#modalVisit').modal('hide');
                }, 2500);

            } else {
                $('.result').attr("class", "result alert bg-danger");
                $('#resultMsg').html('Something is going wrong. Updating failed.');
                $('.result').show(1000);
            }
        }
    });
}

function updateVisit() {
    var $data = {};
    var $commonData = {};
    $('#commonForm :input').each(function () {
        $commonData[this.name] = $(this).val();
    });
    $data.common = $commonData;
    var $orderedServices = [];
    $('.recordset').each(function () {
        var $service = {};
        $(this).find(':input').each(function () {
            $service[this.name] = $(this).val();
        });
        $orderedServices.push($service);
    });
    $data.orderedServices = $orderedServices;

    $.ajax({
        url: 'schedule/visit/update',
        type: 'POST',
        data: 'visit=' + JSON.stringify($data),
        success: function (response) {
            if (response === '1') {
                $('.result').attr("class", "result alert bg-success");
                $('#resultMsg').html("Successfully updateed");
                $('.result').show(1000);
                setTimeout(function () {
                    $('.result').hide(1000);
                    $('#modalVisit').modal('hide');
                }, 2500);
                isVisitAdded = true;

            } else {
                $('.result').attr("class", "result alert bg-danger");
                $('#resultMsg').html('Something is going wrong. Updating failed.');
                $('.result').show(1000);
            }
        }
    });
}

function deleteVisit() {
    var $visitId = $('#visitId').val();
    $.ajax({
        url: 'schedule/visit/delete',
        type: 'POST',
        data: 'visitId=' + $visitId,
        success: function (response) {
            if (response === '1') {
                $('.result').attr("class", "result alert bg-success");
                $('#resultMsg').html("Successfully deleted");
                $('.result').show(1000);
                //$("#addPositionForm")[0].reset();
                setTimeout(function () {
                    $('.result').hide(1000)
                    $('#modalVisit').modal('hide');
                }, 2500);
                isVisitAdded = true;
            } else {
                $('.result').attr("class", "result alert bg-danger");
                $('#resultMsg').html('Something is going wrong. Deleting failed.');
                $('.result').show(1000);
            }
        }
    });
}

function validateForm() {
    let isPersonalDataValid = false;
    let $name = $('#name');
    let $phone = $('#phone');
    if ($name.val() === '' && $phone.val() === '') {
        $name.addClass('is-invalid');
        $phone.addClass('is-invalid');
    } else {
        isPersonalDataValid = true;
    }

    var isServicesDataValid = true;
    var $servicesData = $('#servicesTable').find('.form-control');
    $servicesData.each(function () {
        if ($(this).val() === '') {
            $(this).addClass('is-invalid');
            isServicesDataValid = false;
        }
    });
    var isDateValid = $('#visitDate').val() !== '';
    if (!isDateValid) {
        $('#visitDate').addClass('is-invalid');
    }
    return isPersonalDataValid && isServicesDataValid && isDateValid;
}

function changeReadonlyForDuration($el, measurementUnitId) {
    if (measurementUnitId === '1') {
        $el.closest('.recordset').find('#duration').prop('readonly', true);
        $el.closest('.recordset').find('#endTime').prop('readonly', true);
    } else if (measurementUnitId === '0') {
        $el.closest('.recordset').find('#duration').prop('readonly', false);
        $el.closest('.recordset').find('#endTime').prop('readonly', false);
    }
}