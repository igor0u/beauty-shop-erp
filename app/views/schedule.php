<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div id="schedule">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .fc-today {
        background: transparent !important; /* FullCalendar hack. because demo will always start out on current day */
    }

    .modal-dialog {
        max-width: 90% !important;
    }

    .head-event {
        z-index: 2 !important;
    }
</style>
<script src="/public/js/scheduleFunctions.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        $(document).ready(function () {
            var calendarEl = document.getElementById('schedule');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                themeSystem: 'bootstrap',
                schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                plugins: ['interaction', 'resourceTimeGrid', 'bootstrap'],
                timeZone: 'local',
                nowIndicator: true,
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    omitZeroMinute: false,
                    hour12: false,
                },
                // minTime: '10:00:00',
                // maxTime: '22:00:00',
                defaultView: 'resourceTimeGridDay',
                scrollTime: '12:00',

                allDaySlot: false,
                editable: false,
                selectable: false,
                eventLimit: true,
                eventOverlap: false,
                height: $(window).height() * 0.77,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'resourceTimeGridDay,resourceTimeGridTwoDay'
                },

                views: {
                    resourceTimeGridDay: {
                        type: 'resourceTimeGrid',
                        duration: {days: 1},
                        slotDuration: '00:05:00',
                        buttonText: 'Scale +',
                        slotLabelInterval: '00:10',
                    },
                    resourceTimeGridTwoDay: {
                        type: 'resourceTimeGrid',
                        duration: {days: 1},
                        slotDuration: '00:30:00',
                        buttonText: 'Scale -',
                    }
                },
                resources: function (fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: 'schedule/staff/list',
                        type: 'post',
                        dataType: 'json',
                        success: function (data) {
                            successCallback(data);
                        }
                    });
                },
                events:
                    {
                        url: 'schedule/visit/date',
                        method: 'POST',
                        // failure: function () {
                        //     alert('there was an error while fetching events!');
                        // },
                    },
                dateClick: function (info) {
                    var date = info.date.toISOString().slice(0, 10);
                    var startTime = ('0' + info.date.getHours()).slice(-2) + ':' + ('0' + info.date.getMinutes()).slice(-2);
                    //calendar.scrollTime(startTime);
                    createEvent(info.resource.id, info.resource.title, date, startTime);
                },

                resourceRender: function (res) {
                    res.el.innerHTML = '<div>' + res.resource.title + '</div>' +
                        '<div class="font-weight-normal">' + res.resource.extendedProps.position + '</div>';
                },

                eventRender: function (event, element) {
                    event.el.classList.add("pl-1");
                    event.el.classList.add("pr-1");
                    event.el.classList.add("pr-1");
                    //event.el.attributes[1].nodeValue;
                    event.el.innerHTML = "";

                    if (event.event.extendedProps.isNextConsecutive === '0') {
                        event.el.classList.add("head-event");
                        event.el.classList.add("border-0");
                        event.el.innerHTML = '<div>' + event.event.title + '</div>' +
                            '<div>' + event.event.extendedProps.clientPhone + '</div>';
                    }
                    if (event.view.viewSpec.options.slotDuration === '00:05:00') {
                        event.el.innerHTML += '<div>' + event.event.extendedProps.serviceName + '</div>' +
                            '<div>' + event.event.extendedProps.totalPrice + '</div>';
                    }
                },

                eventClick: function (event) {
                    visitId = event.event.extendedProps.visitId;
                    openEvent(visitId);


                }


            });
            calendar.render();
            if (calendar) {
                $(window).resize(function () {
                    var calHeight = $(window).height() * 0.83;
                    calendar.setOption('height', calHeight);
                });
            }
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
                    $row.find('.service-end-time').data('prev',$end);

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
                var $measurementUnitId = $row.find('.measurement-unit-id').val();

                if (parseTime($end) - parseTime($start) <= 0) {
                    $row.find('.service-end-time')
                        .val(getTimeInHoursMinutes(parseTime($start) + parseTime($duration) * $quantity));
                } else {
                    if ($measurementUnitId === '1'){
                        $duration = '00:01';
                    } else if ($end < $prevEnd) {
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
                calendar.refetchEvents();
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
                    addVisit(calendar);
                } else {
                    updateVisit();
                }
                calendar.refetchEvents();
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
            $('#finishVisit').on('click', function () {
                $('#submitVisit').prop("disabled", true);
                $('#deleteVisit').prop("disabled", true);
                $(this).prop("disabled", true);
                if ($('#visitId').val() === '') {
                    $(this).prop("disabled", false);
                } else {
                    finishVisit();
                }
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
        });
    })
    ;
</script>