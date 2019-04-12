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
                                        <table id="clientsListTable" class="table services-table" data-toggle="table"
                                               data-pagination="true"
                                               data-search="true" data-method="post"
                                               data-url="clients/list" data-row-style="rowStyle">
                                            <thead class="thead-light">
                                            <tr>
                                                <th data-sortable="true" data-field="fullName">Full Name</th>
                                                <th data-sortable="true" data-field="phone">Phone</th>
                                                <th data-sortable="true" data-align="right" data-field="volumeOfSales">
                                                    Volume of Sales
                                                </th>
                                                <th data-sortable="true" data-align="right" data-field="numberOfVisits">
                                                    Number of Visits
                                                </th>
                                                <th data-sortable="true" data-field="dateOfBirth">Date of Birth</th>
                                                <th data-sortable="true" data-field="lastVisitDate">Last Visit Date</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="button" id="addClient" class="btn btn-primary add-service">Add
                                Client
                            </button>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade mw-100 w-85 bd-modal-lg" id="modalNewClient" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <?php require_once ROOT . '/app/views/modals/newClient.php'; ?>
        </div>
    </div>
</div>
<div class="modal fade mw-100 w-85 bd-modal-lg" id="modalClient" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <?php require_once ROOT . '/app/views/modals/client.php'; ?>
        </div>
    </div>
</div>
<script src="/public/js/clientsFunctions.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        $(document).ready(function () {
            var isChanged = false;
            var clientsListTable = $('#clientsListTable');
            $('#addClient').on('click', function () {
                $('#modalNewClient').modal('show');
                $('#submitClient').html('Add Client');
                $('#modalNewClient #modalTitle').html('New Client');
            });
            $('#modalNewClient #submitClient').on('click', function () {
                if ($('#modalNewClient #name').val().trim() === '') {
                    $('#modalNewClient #name').addClass('is-invalid');
                    return;
                }
                addNewClient();
                $('#modalNewClient #submitClient').prop('disabled', true);
            });
            $('#modalNewClient').on('hide.bs.modal', function (e) {
                if (isChanged) {
                    clientsListTable.bootstrapTable('refresh');
                }
                isChanged = false;
                $('#modalNewClient').find(':input').each(function () {
                    $(this).removeClass('is-invalid');
                    $(this).val('');
                });
                $('.result').hide();
                $('#modalNewClient #submitClient').prop('disabled', false);
            });
            $('#modalClient').on('hide.bs.modal', function (e) {
                if (isChanged) {
                    clientsListTable.bootstrapTable('refresh');
                }
                isChanged = false;
                $('#modalClient').find(':input').each(function () {
                    $(this).removeClass('is-invalid');
                    $(this).val('');
                    $('#modalClient #submitClient').prop('disabled', true);
                    $('#modalClient #deleteClient').prop('disabled', false);
                });
                $('.result').hide();
                $('#modalNewClient #submitClient').prop('disabled', false);
            });
            $('#modalNewClient #name').on('click', function () {
                $(this).removeClass('is-invalid');
            });
            clientsListTable.on('click-row.bs.table', function (e, row) {
                openClient(row.clientId);
            });
            $('#modalClient #clientForm').on('change', ':input', function () {
                $('#modalClient #submitClient').prop('disabled', false);
                isChanged = true;
            });
            $('#modalClient #submitClient').on('click', function () {
                if ($('#modalClient #serviceId').val() === '') {
                    return;
                }
                isChanged = true;
                $('#modalClient #submitClient').prop('disabled', true);
                $('#modalClient #deleteClient').prop('disabled', true);
                updateClient();
            });
            $('#modalClient #deleteClient').on('click', function () {
                if ($('#modalClient #serviceId').val() === '') {
                    return;
                }
                isChanged = true;
                $('#modalClient #submitClient').prop('disabled', true);
                $('#modalClient #deleteClient').prop('disabled', true);
                if ($('#modalClient #isDeleted').val() === '0') {
                    deleteClient();
                } else {
                    restoreClient();
                }
            });
        })
    })
</script>