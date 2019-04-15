<div class="panel-body card-body">
    <div class="result" style="display: none;">
        <span id="resultMsg"></span> <a href="#" id="closeResultMsg" class="pull-right"><span
                    class="fa fa-close"></span></a>
    </div>
    <form method="post" id="newClientForm" class="form row-border" action="#">
        <input type="hidden" id="clientId" name="serviceId" value="">
        <input type="hidden" id="isClientDeleted" name="isDeleted" value="">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="surname">Surname</label>
                    <input type="text" id="surname" name="surname" class="form-control" value="">
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="">
                </div>
                <div class="form-group">
                    <label for="patronymic">Patronymic</label>
                    <input type="text" id="patronymic" name="patronymic" class="form-control" value="">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="">
                </div>
                <div class="form-group">
                    <label for="dateOfBirth">Date of Birth</label>
                    <input type="date" id="dateOfBirth" name="dateOfBirth" class="form-control" value="">
                </div>
            </div>
            <div class="col-md-6"></div>
        </div>
        <div class="row text-center">
            <div class="col-6 form-group text-left">
            </div>
            <div class="col-6 form-group text-right">
                <button type="button" id="submitClient" class="btn btn-primary"></button>
            </div>
        </div>
    </form>
</div>


