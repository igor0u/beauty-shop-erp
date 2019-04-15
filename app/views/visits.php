<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex flex-wrap justify-content-xl-between">
                                    <div class="table-responsive">
                                        <table id="visitsListTable" class="table services-table" data-toggle="table"
                                               data-pagination="true"
                                               data-search="true" data-method="post"
                                               data-url="visits/list" data-row-style="rowStyle">
                                            <thead class="thead-light">
                                            <tr>
                                                <th data-sortable="true" data-field="visitId">Visit ID</th>
                                                <th data-sortable="true" data-field="visitDate">Visit Date</th>
                                                <th data-sortable="true" data-field="clientName">Client Name</th>
                                                <th data-sortable="true" data-field="visitTotalCost">Total Cost</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade mw-100 w-85 bd-modal-lg" id="modalVisit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <?php require_once ROOT . '/app/views/modals/visit.php'; ?>
        </div>
    </div>
</div>
<style>
    .modal-dialog {
        max-width: 90% !important;
    }
</style>
<script src="/public/js/scheduleFunctions.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        $(document).ready(function () {
            var isChanged = false;
            var visitsListTable = $('#visitsListTable');
            visitsListTable.on('click-row.bs.table', function (e, row) {
                openEvent(row.visitId);
            });
            // Calculating total price
            $(document).on('change', '.price', function () {
                recalculate(this);
            });

            // Calculating end time
            $('#servicesTable').on('change', '.service-start-time, .service-duration, .service, .service-quantity',
                (function () {
                    var $row = $(this).closest('.recordset');
                    var $start = $row.find('.service-start-time').val();
                    var $duration = $row.find('.service-duration').val();
                    var $quantity = $row.find('.service-quantity').val()

                    if (parseTime($start) + parseTime($duration) * $quantity >= 24 * 60) {
                        $duration = getTimeInHoursMinutes((24 * 60 - 1 - parseTime($start)) / $quantity);
                    }
                    $row.find('.service-duration').val($duration);
                    var $end = getTimeInHoursMinutes(parseTime($start) + parseTime($duration) * $quantity);
                    $row.find('.service-end-time').val($end);
                    $row.find('.service-end-time').data('prev', $end);

                }));

            // Calculating duration
            $('#servicesTable').on('focusin', '.service-end-time', (function () {
                $(this).data('prev', $(this).val());
            }));

            $('#servicesTable').on('change', '.service-end-time', (function () {
                var $row = $(this).closest('.recordset');
                var $prevEnd = $row.find('.service-end-time').data('prev');
                var $start = $row.find('.service-start-time').val();
                var $duration = $row.find('.service-duration').val();
                var $end = $row.find('.service-end-time').val();
                var $quantity = $row.find('.service-quantity').val();

                if (parseTime($end) - parseTime($start) <= 0) {
                    $row.find('.service-end-time')
                        .val(getTimeInHoursMinutes(parseTime($start) + parseTime($duration) * $quantity));
                } else {
                    if ($end < $prevEnd) {
                        $duration = getTimeInHoursMinutes(Math.floor((parseTime($end) - parseTime($start)) / $quantity));
                    } else {
                        $duration = getTimeInHoursMinutes(Math.ceil((parseTime($end) - parseTime($start)) / $quantity));
                    }
                    $row.find('.service-duration').val($duration);
                    $row.find('.service-duration').trigger('change');

                }
            }));

            // Dynamic add fields
            $("#servicesTable").czMore({
                onDelete: function () {
                    recalculateGrandTotal();
                },
                onAdd: function () {
                    if ($('#servicesTable_czMore_txtCount').val() > 1) {
                        var lastRow = $('.recordset').last();
                        var prevRow = lastRow.prev('.recordset');
                        lastRow.find('#startTime').val(prevRow.find('#endTime').val());
                        lastRow.find('#employeeId').attr('value', prevRow.find('#employeeId').val());
                        lastRow.find('#employeeName').val(prevRow.find('#employeeName').val());
                    }
                }
            });

            // Autocompletes
            $('#commonForm').on('focus', '.client', function () {
                $(this).autocomplete({
                    source: function (request, response) {
                        $.ajax({
                            url: "schedule/clients/find",
                            type: "POST",
                            dataType: "json",
                            data: {
                                query: request.term
                            },
                            success: function (data) {
                                response($.map(data, function (item) {
                                    return {
                                        label: item.surname + " " + item.name + " " + item.patronymic + " " + item.phone,
                                        id: item.clientId,
                                        phone: item.phone,
                                        surname: item.surname,
                                        name: item.name,
                                        patronymic: item.patronymic,
                                    }
                                }));
                            }
                        });
                    },
                    minLength: 2,
                    select: function (event, ui) {
                        event.preventDefault();
                        $('#clientId').val(ui.item.id);
                        $('#surname').val(ui.item.surname);
                        $('#name').val(ui.item.name);
                        $('#patronymic').val(ui.item.patronymic);
                        $('#phone').val(ui.item.phone);
                    }
                });
            });
            $('#servicesTable').on('focus', '.service', function () {
                $(this).autocomplete({
                    source: function (request, response) {
                        $.ajax({
                            url: "schedule/services/find",
                            type: "POST",
                            dataType: "json",
                            data: {
                                query: request.term
                            },
                            success: function (data) {
                                response($.map(data, function (item) {
                                    return {
                                        label: item.serviceName,
                                        value: item.serviceName,
                                        id: item.serviceId,
                                        cost: item.serviceCost,
                                        duration: item.serviceDuration,
                                        measurementUnitId: item.measurementUnitId,
                                        measurementUnit: item.measurementUnit,
                                        measurementUnitAbbr: item.measurementUnitAbbr
                                    }
                                }));
                            }
                        });
                    },
                    minLength: 1,
                    select: function (event, ui) {
                        $(this).closest('.recordset').find('#serviceId').attr('value', (ui.item.id));
                        $(this).closest('.recordset').find('#duration').val(ui.item.duration);
                        $(this).closest('.recordset').find('#serviceCost').val(ui.item.cost);
                        $(this).closest('.recordset').find('#measurementUnitAbbr').html(ui.item.measurementUnitAbbr);
                        console.log(ui.item.measurementUnitAbbr);
                        $(this).closest('.recordset').find('#measurementUnitAbbr').attr('title', ui.item.measurementUnit);
                        changeReadonlyForDuration($(this), ui.item.measurementUnitId)
                        recalculate(this);
                        $(this).trigger("change");
                    }
                });
            });
            $('#servicesTable').on('focus', '.employee', function () {
                $(this).autocomplete({
                    source: function (request, response) {
                        $.ajax({
                            url: "schedule/staff/find",
                            type: "POST",
                            dataType: "json",
                            data: {
                                query: request.term
                            },
                            success: function (data) {
                                response($.map(data, function (item) {
                                    return {
                                        label: item.surname + " " + item.name + " " + item.patronymic,
                                        value: item.surname + " " + item.name + " " + item.patronymic,
                                        id: item.employeeId
                                    }
                                }));
                            }
                        });
                    },
                    minLength: 1,
                    select: function (event, ui) {
                        $(this).closest('.recordset').find('#employeeId').attr('value', (ui.item.id));
                        $(this).closest('.recordset').find('#employeeName').val(ui.item.label);
                    }
                });
            });
            // End of autocompletes


            $('#modalVisit').on('hide.bs.modal', function () {
                visitsListTable.bootstrapTable('refresh');
                $("#commonForm")[0].reset();
                $('#commonForm').find(':input').removeClass('is-invalid');
                $('#commonForm').find(':input').prop('disabled', false);
                $('#deleteVisit').prop('disabled', false);
                $('#deleteVisit').prop('hidden', false);
                $('#modalTitle').html('');
                $('#submitVisit').prop("disabled", false);
                $('#submitVisit').prop("disabled", true);


                $("#servicesTable").czDeleteAll();
                $('#grandTotal').text('0.00');
                $('.result').hide(1000);
            });
            $('#modalVisit').on('click', '#closeResultMsg', function () {
                $('.result').hide(1000);
            });
            $('#modalVisit').on('click', function () {
            });
            $('#submitVisit').on('click', function () {
                if (!validateForm()) {
                    return false
                } else {
                    $('#submitVisit').prop("disabled", true);
                    $('#deleteVisit').prop("disabled", true);
                }
                if ($('#visitId').val() === '') {
                    return false
                } else {
                    updateVisit();
                }
            });
            $('#deleteVisit').on('click', function () {
                if ($('#visitId').val() === '' || !confirm("Delete this visit?")) {
                    return false
                }
                $('#submitVisit').prop("disabled", true);
                $('#deleteVisit').prop("disabled", true);
                deleteVisit();
                calendar.refetchEvents();
            });
            $('#visitForm').on('click', ':input', function () {
                $(this).removeClass('is-invalid');
            });
            $('#visitForm, #servicesTable').on('change', ':input', function () {
                $('#submitVisit').prop("disabled", false);
            });
            $(document).on('click', '#btnMinus', function () {
                $('#submitVisit').prop("disabled", false);
            });
            $('#commonForm').on('click', '#clearClientData', function () {
                $('#commonForm').find('.client').each(function () {
                        $(this).val('');
                    }
                )
            })
        })
    })
</script>