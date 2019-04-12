function rowStyle(row, index) {
    if (row.isDeleted === '1') {
        return {
            classes: 'text-muted font-weight-light'
        }
    }
    return {}
}

function openCategory(categoryId) {
    $.ajax({
        url: 'services/category/open',
        type: 'post',
        dataType: 'json',
        data: {
            categoryId: categoryId
        },
        success: function (data) {
            $('#modalCategory').modal('show');
            $('#submitCategory').html('Update Category');
            fillCategoryForm(data);
            $('#deleteCategory').prop('disabled', false);
            $('#deleteCategory').prop('hidden', false);
        }
    });
}

function fillCategoryForm(data) {

    $('#modalCategory #modalTitle').html(data.categoryName);
    $('#serviceCategoryForm #categoryId').val(data.categoryId);
    $('#serviceCategoryForm #categoryName').val(data.categoryName);
    $('#serviceCategoryForm #isCategoryDeleted').val(data.isDeleted);
    if (data.isDeleted === '0') {
        $('#serviceCategoryForm #deleteCategory').text('Delete Category');
    } else {
        $('#serviceCategoryForm #deleteCategory').text('Restore Category');
    }
}

function addCategory() {
    isChanged = true;
    var data = $('#serviceCategoryForm').serializeArray();

    $.ajax({
        url: 'services/category/add',
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
                    $('#modalCategory').modal('hide');
                }, 2500);
            } else {
                $('.result').attr("class", "result alert bg-danger");
                $('#resultMsg').html('Something is going wrong. Updating failed.');
                $('.result').show(1000);
            }
        }
    });
}

function updateCategory() {
    var data = $('#serviceCategoryForm').serializeArray();
    $.ajax({
        url: 'services/category/update',
        type: 'post',
        data: data,
        success: function (response) {

            if (response === '1') {
                $('.result').attr("class", "result alert bg-success");
                $('#resultMsg').html("Successfully updated");
                $('.result').show(1000);
                setTimeout(function () {
                    $('.result').hide(1000);
                    $('#modalCategory').modal('hide');
                }, 2500);
            } else {
                $('.result').attr("class", "result alert bg-danger");
                $('#resultMsg').html('Something is going wrong. Updating failed.');
                $('.result').show(1000);
            }
        }
    });
}

function loadServiceCategoriesList() {
    $.ajax({
        url: 'services/category/list',
        dataType: 'json',
        success: function (data) {
            fillServiceCategoriesFilterBar(data);
            fillServiceCategoryOptions(data);
        }
    });
}

function fillServiceCategoriesFilterBar(data) {
    $.each(data, function (key, value) {
        var classMuted = '';
        if(value.isDeleted === '1') {
            classMuted = 'text-muted font-weight-light';
        }
        $('#categoriesFilter').append($('<a></a>')
            .attr('href', '#')
            .addClass('nav-link filter-item')
            .addClass(classMuted)
            .attr('data-category-id', value.categoryId)
            .attr('data-category-name', value.categoryName)
            .attr('data-toggle', 'pill')
            .text(value.categoryName));

    });
}

function clearServiceCategoriesFilterBar() {
    $('#categoriesFilter').find($('.filter-item')).remove();
}

function fillServiceCategoryOptions(data) {
    $.each(data, function (key, value) {
        if(value.isDeleted === '0') {
            $('#serviceForm #categoryId').append($("<option></option>")
                .attr("value", value.categoryId)
                .text(value.categoryName));
        }
    });
}

function clearServiceCategoryOptions() {
    $('#serviceForm #categoryId').html("<option></option>");
}

function openService(serviceId) {
    $.ajax({
        url: 'services/service/open',
        type: 'post',
        dataType: 'json',
        data: {
            serviceId: serviceId
        },
        success: function (serviceData) {
            fillServiceModal(serviceData);
            $('#modalService').modal('show');
            $('#submitService').prop('disabled', true)
            $('#deleteService').prop('disabled', false);
            $('#deleteService').prop('hidden', false);
        }
    });
}

function fillServiceModal(data) {
    $('#modalService #submitService').html('Update Service');
    $('#modalService #deleteService').html('Delete Service');
    $('#modalService #modalTitle').html(data.serviceName);
    $('#serviceForm #serviceId').val(data.serviceId);
    $('#serviceForm #serviceName').val(data.serviceName);
    $('#serviceForm #categoryId').val(data.categoryId);
    $('#serviceForm #serviceCost').val(data.serviceCost);
    $('#serviceForm #measurementUnitId').val(data.measurementUnitId);
    $('#serviceForm #serviceDuration').val(data.serviceDuration);
    $('#serviceForm #isServiceDeleted').val(data.isDeleted);
    if (data.isDeleted === '0') {
        $('#deleteService').text('Delete Service');
    } else {
        $('#deleteService').text('Restore Service');
    }
}

function validateServiceForm() {
    var isValid = true;
    $('#serviceForm').find('.form-control').each(function () {
        if ($(this).val() === '') {
            $(this).addClass('is-invalid');
            isValid = false;
        }
    });
    return isValid;
}

function addService() {
    var data = $('#serviceForm').serializeArray();
    $.ajax({
        url: 'services/service/add',
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
                    $('#modalService').modal('hide');
                }, 2500);
            } else {
                $('.result').attr("class", "result alert bg-danger");
                $('#resultMsg').html('Something is going wrong. Adding failed.');
                $('.result').show(1000);
            }
        }
    });
}

function updateService() {
    var data = $('#serviceForm').serializeArray();
    $.ajax({
        url: 'services/service/update',
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

function deleteService() {
    var id = $('#serviceForm #serviceId').val();
    $.ajax({
        url: 'services/service/delete',
        type: 'post',
        data: {
            serviceId: id
        },
        success: function (response) {

            if (response === '1') {
                $('#modalService .result').attr("class", "result alert bg-success");
                $('#modalService #resultMsg').html("Successfully deleted");
                $('#modalService .result').show(1000);
                setTimeout(function () {
                    $('#modalService .result').hide(1000);
                    $('#modalService').modal('hide');
                }, 2500);
            } else {
                $('#modalService .result').attr("class", "result alert bg-danger");
                $('#modalService #resultMsg').html('Something is going wrong. Deleting failed.');
                $('#modalService .result').show(1000);
            }
        }
    });
}

function restoreService() {
    var id = $('#serviceForm #serviceId').val();
    $.ajax({
        url: 'services/service/restore',
        type: 'post',
        data: {
            serviceId: id
        },
        success: function (response) {

            if (response === '1') {
                $('#modalService .result').attr("class", "result alert bg-success");
                $('#modalService #resultMsg').html("Successfully restored");
                $('#modalService .result').show(1000);
                setTimeout(function () {
                    $('#modalService .result').hide(1000);
                    $('#modalService').modal('hide');
                }, 2500);
            } else {
                $('#modalService .result').attr("class", "result alert bg-danger");
                $('#modalService #resultMsg').html('Something is going wrong. Restoring failed.');
                $('#modalService .result').show(1000);
            }
        }
    });
}

function deleteCategory() {
    var id = $('#serviceCategoryForm #categoryId').val();
    $.ajax({
        url: 'services/category/delete',
        type: 'post',
        data: {
            categoryId: id
        },
        success: function (response) {

            if (response === '1') {
                $('#modalCategory .result').attr("class", "result alert bg-success");
                $('#modalCategory #resultMsg').html("Successfully deleted");
                $('#modalCategory .result').show(1000);
                setTimeout(function () {
                    $('#modalCategory .result').hide(1000);
                    $('#modalCategory').modal('hide');
                }, 2500);
            } else {
                $('#modalCategory .result').attr("class", "result alert bg-danger");
                $('#modalCategory #resultMsg').html('Something is going wrong. Deleting failed.');
                $('#modalCategory .result').show(1000);
            }
        }
    });
}

function restoreCategory() {
    var id = $('#serviceCategoryForm #categoryId').val();
    $.ajax({
        url: 'services/category/restore',
        type: 'post',
        data: {
            categoryId: id
        },
        success: function (response) {

            if (response === '1') {
                $('#modalCategory .result').attr("class", "result alert bg-success");
                $('#modalCategory #resultMsg').html("Successfully restored");
                $('#modalCategory .result').show(1000);
                setTimeout(function () {
                    $('#modalCategory .result').hide(1000);
                    $('#modalCategory').modal('hide');
                }, 2500);
            } else {
                $('#modalCategory .result').attr("class", "result alert bg-danger");
                $('#modalCategory #resultMsg').html('Something is going wrong. Restoring failed.');
                $('#modalCategory .result').show(1000);
            }
        }
    });
}
