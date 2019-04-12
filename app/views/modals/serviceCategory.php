<div class="modal-header">
    <span id="modalTitle" class="text-uppercase">New Position</span>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="grid-margin stretch-card">
    <div class="card border-0">
        <div class="panel-body card-body">
            <div class="result" style="display: none;">
                <span id="resultMsg"></span><a href="#" id="closeResultMsg" class=""><span
                        class="">&times;</span></a>
            </div>
            <form method="post" id="serviceCategoryForm" class="form row-border category-add" action="#">
                <input type="hidden" id="categoryId" name="categoryId" class="form-control form-control-sm"
                       value="">
                <input type="hidden" id="isCategoryDeleted" name="isDeleted" class="form-control form-control-sm"
                       value="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="categoryName">Category Name</label>
                            <input type="text" id="categoryName" name="categoryName"
                                   class="form-control form-control-sm"
                                   value="">
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-6 form-group text-left">
                        <button type="button" id="deleteCategory" class="btn btn-outline-danger"></button>
                    </div>
                    <div class="col-6 form-group text-right">
                        <button type="button" id="submitCategory" class="btn btn-primary"></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>