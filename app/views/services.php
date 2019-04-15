<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="row nav flex-column nav-pills category-filter" id="categoriesFilter">
                                    <a href="#"
                                       class="nav-link active"
                                       data-category-name="all" data-toggle="pill">All Categories</a>

                                </div>
                                <div class="text-center">
                                    <button type="button" id="addCategory"
                                            class="btn btn-primary add-category m-3">Add Category
                                    </button>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="d-flex flex-wrap justify-content-xl-between">
                                    <div class="table-responsive">
                                        <table id="servicesListTable" class="table services-table" data-toggle="table"
                                               data-pagination="true"
                                               data-search="true" data-method="post"
                                               data-url="services/list" data-row-style="rowStyle">
                                            <thead class="thead-light">
                                            <tr>
                                                <th data-sortable="true" data-field="serviceName">Name</th>
                                                <th data-sortable="true" data-field="categoryName">Category</th>
                                                <th data-sortable="true" data-field="serviceCost">Cost</th>
                                                <th data-sortable="true" data-field="measurementUnitAbbr"><abbr
                                                            title="Unit Of Measurement">UoM</abbr></th>
                                                <th data-sortable="true" data-field="serviceDuration">Duration</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="button" id="addService" class="btn btn-primary add-service">Add
                                Service
                            </button>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/public/js/servicesFunctions.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        $(document).ready(function () {
            var servicesTable = $('#servicesListTable');
            var isChanged = false;
            loadServiceCategoriesList()
            $('.category-filter').on('click', 'a', function () {

                var filterRule = $(this).attr('data-category-name');
                if (filterRule === 'all') {
                    servicesTable.bootstrapTable('filterBy', {});
                } else {
                    servicesTable.bootstrapTable('filterBy', {
                        categoryName: filterRule
                    });
                }
            });
            $('.category-filter').on('dblclick', '.filter-item', function () {
                openCategory($(this).data('category-id'));
            });
            $('#submitCategory').on('click', function () {
                if ($('#serviceCategoryForm #categoryName').val() === '') {
                    $('#serviceCategoryForm #categoryName').addClass('is-invalid');
                    return
                }
                isChanged = true;
                if ($('#serviceCategoryForm #categoryId').val() === '') {
                    addCategory();
                } else {
                    updateCategory();
                }
            });
            $('#serviceCategoryForm #categoryName, .form-control').on('click', function () {
                $(this).removeClass('is-invalid');
            });
            $('#modalCategory').on('hide.bs.modal', function (e) {
                if (isChanged) {
                    clearServiceCategoriesFilterBar();
                    clearServiceCategoryOptions();
                    loadServiceCategoriesList();
                }
                isChanged = false;
                $('#serviceCategoryForm').find(':input').each(function () {
                    $(this).removeClass('is-invalid');
                    $(this).val('');
                });
                $('.result').hide();
            });
            $('#modalService').on('hide.bs.modal', function (e) {
                if (isChanged) {
                    servicesTable.bootstrapTable('refresh')
                }
                isChanged = false;
                $('#serviceForm').find(':input').each(function () {
                    $(this).removeClass('is-invalid');
                    $(this).val('');
                });
                $('.result').hide();
            });
            $('#addCategory').on('click', function () {
                $('#modalCategory').modal('show');
                $('#submitCategory').html('Add Category');
                $('#modalCategory #modalTitle').html('New Category');
                $('#modalCategory #deleteCategory').prop('disabled', true);
                $('#modalCategory #deleteCategory').prop('hidden', true);
            });
            $('#addService').on('click', function () {
                $('#modalService').modal('show');
                $('#submitService').html('Add Service');
                $('#modalService #modalTitle').html('New Service');
                $('#modalService #submitService').prop('disabled', true);
                $('#modalService #deleteService').prop('disabled', true);
                $('#modalService #deleteService').prop('hidden', true);
            });
            servicesTable.on('click-row.bs.table', function (e, row) {
                openService(row.serviceId);
            });
            $('#submitService').on('click', function () {
                $('#serviceForm')
                if (!validateServiceForm()) {
                    return;
                }
                $('#submitService').prop('disabled', true);
                $('#deleteService').prop('disabled', true);
                isChanged = true;
                if ($('#serviceId').val() === '') {
                    addService();
                } else {
                    updateService();
                }
            });
            $('#deleteService').on('click', function () {
                if ($('#serviceId').val() === '') {
                    return;
                }
                isChanged = true;
                if ($('#isServiceDeleted').val() === '0') {
                    deleteService();
                } else {
                    restoreService();
                }
            });
            $('#deleteCategory').on('click', function () {
                if ($('#serviceCategoryForm #categoryId').val() === '') {
                    return;
                }
                isChanged = true;
                if ($('#serviceCategoryForm #isCategoryDeleted').val() === '0') {
                    deleteCategory();
                } else {
                    restoreCategory();
                }
            });
            $('#measurementUnitId, #serviceDuration').on('change', function () {
                if ($('#measurementUnitId').val() === '1') {
                    $('#serviceDuration').val('00:01');
                }
            });
            $('#modalService').on('change', ':input', function () {
                $('#modalService #submitService').prop('disabled', false)
            })
        });
    });
</script>