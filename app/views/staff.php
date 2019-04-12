<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body dashboard-tabs p-0">
                        <ul class="nav nav-tabs px-4" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="staff-tab" data-toggle="tab" href="#staff"
                                   role="tab" aria-controls="staff" aria-selected="true">Staff</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="positions-tab" data-toggle="tab" href="#positions"
                                   role="tab"
                                   aria-controls="positions" aria-selected="false">Positions</a>
                            </li>
                        </ul>
                        <div class="tab-content py-0 px-0">
                            <div class="tab-pane fade show active" id="staff" role="tabpanel"
                                 aria-labelledby="staff-tab">
                                <div class="d-flex flex-wrap justify-content-xl-between p-3">
                                    <div class="table-responsive">
                                        <table id="staffListTable" class="table staff-table" data-pagination="true"
                                               data-search="true" data-toggle="table" data-method="post"
                                               data-url="staff/list" data-row-style="rowStyle">
                                            <thead class="thead-light">
                                            <tr>
                                                <th data-sortable="true" data-field="employeeId" data-visible="false">
                                                    Id
                                                </th>
                                                <th data-sortable="true" data-field="name">Name</th>
                                                <th data-sortable="true" data-field="position">Position</th>
                                                <th data-sortable="true" data-field="dateOfBirth">Date Of Birth</th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="button" id="addEmployee"
                                            class="btn btn-primary mb-3 mr-3">Add Employee
                                    </button>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="positions" role="tabpanel"
                                 aria-labelledby="positions-tab">
                                <div class="d-flex flex-wrap justify-content-xl-between p-3">
                                    <div class="table-responsive">
                                        <table id="positionsListTable" class="table positions-table"
                                               data-pagination="true"
                                               data-search="true" data-toggle="table" data-method="post"
                                               data-url="staff/position/list" data-row-style="rowStyle">
                                            <thead class="thead-light">
                                            <tr>
                                                <th data-sortable="true" data-field="positionId">id</th>
                                                <th data-sortable="true" data-field="positionName">Position</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="button" id="addPosition"
                                            class="btn btn-primary mb-3 mr-3 add-position">Add Position
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade mw-100 w-85 bd-modal-lg" id="modalEmployee" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <?php require_once ROOT . '/app/views/modals/employee.php'; ?>
        </div>
    </div>
</div>
<div class="modal fade mw-100 w-85 bd-modal-lg" id="modalPosition" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <?php require_once ROOT . '/app/views/modals/position.php'; ?>
        </div>
    </div>
</div>
<script src="/public/js/staffFunctions.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        $(document).ready(function () {
            var isChanged = false;
            var $staffTable = $('#staffListTable');
            var $positionsTable = $('#positionsListTable')
            loadPositionsList($('#position'));
            $positionsTable.on('click-row.bs.table', function (e, row) {
                openPosition(row.positionId);
            });
            $('#modalPosition').on('hide.bs.modal', function (e) {
                if (isChanged) {
                    $positionsTable.bootstrapTable('refresh')
                }
                isChanged = false;
                $('#positionForm').find(':input').each(function () {
                    $(this).removeClass('is-invalid');
                    $(this).val('');
                });
                $('.result').hide();
            });
            $staffTable.on('click-row.bs.table', function (e, row) {
                openEmployee(row.employeeId);
            });
            $('#addPosition').on('click', function () {
                $('#modalPosition').modal('show');
                $('#submitPosition').html('Add Position');
                $('#modalPosition #modalTitle').html('New Position');
                $('#deletePosition').prop('disabled', true);
                $('#deletePosition').prop('hidden', true);
            });
            $('#submitPosition').on('click', function () {
                if ($('#positionName').val() === '') {
                    $('#positionName').addClass('is-invalid');
                    return
                }
                isChanged = true;
                if ($('#positionId').val() === '') {
                    addPosition();
                } else {
                    updatePosition();
                }
            });
            $('#addEmployee').on('click', function () {
                $('#modalEmployee').modal('show');
                $('#submitEmployee').html('Add Employee');
                $('#modalEmployee #modalTitle').html('New Employee');
                $('#deleteEmployee').prop('disabled', true);
                $('#submitEmployee').prop('disabled', true);
                $('#deleteEmployee').prop('hidden', true);
            });
            $('#modalEmployee').on('hide.bs.modal', function (e) {
                if (isChanged) {
                    $staffTable.bootstrapTable('refresh')
                }
                isChanged = false;
                $('#employeeForm').find(':input').each(function () {
                    $(this).removeClass('is-invalid');
                    $(this).val('');
                });
                $('.result').hide();
            });
            $('#submitEmployee').on('click', function () {
                if (!validateEmployeeForm()) {
                    return;
                }
                isChanged = true;
                if ($('#employeeId').val() === '') {
                    addEmployee();
                } else {
                    updateEmployee();
                }
            });
            $('#deleteEmployee').on('click', function () {
                if ($('#employeeId').val() === '') {
                    return;
                }
                isChanged = true;
                if ($('#isEmployeeDeleted').val() === '0') {
                    deleteEmployee();
                } else {
                    restoreEmployee();
                }
            });
            $('#deletePosition').on('click', function () {
                if ($('#positionId').val() === '') {
                    return;
                }
                isChanged = true;
                if ($('#isPositionDeleted').val() === '0') {
                    deletePosition();
                } else {
                    restorePosition();
                }
            });
            $('#closeResultMsg').on('click', function () {
                $('.result').hide(1000);
            })
            $('#name, #position, #positionName').on('click', function () {
                $(this).removeClass('is-invalid');
            })
            $('#employeeForm').on('change', ':input', function () {
                $('#submitEmployee').prop('disabled', false);
            })
        })
    })
</script>
