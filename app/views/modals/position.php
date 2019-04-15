<div class="panel-body card-body">
    <div class="result" style="display: none;">
        <span id="resultMsg"></span><a href="#" id="closeResultMsg" class=""><span
                    class="">&times;</span></a>
    </div>
    <form method="post" id="positionForm" class="form row-border position-add" action="#">
        <input type="hidden" id="positionId" name="positionId" class="form-control form-control-sm"
               value="">
        <input type="hidden" id="isPositionDeleted" name="isDeleted" class="form-control form-control-sm"
               value="">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="surname">Position Name</label>
                    <input type="text" id="positionName" name="positionName"
                           class="form-control form-control-sm"
                           value="">
                </div>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-6 form-group text-left">
                <button type="button" id="deletePosition" class="btn btn-outline-danger"></button>
            </div>
            <div class="col-6 form-group text-right">
                <button type="button" id="submitPosition" class="btn btn-primary"></button>
            </div>
        </div>
    </form>
</div>