<div class="card-body dashboard-tabs p-0">
    <ul class="nav nav-tabs px-4" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general"
               role="tab" aria-controls="general" aria-selected="true">General Information</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="services-tab" data-toggle="tab" href="#services"
               role="tab" aria-controls="services" aria-selected="false">Services</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="schedule-tab" data-toggle="tab" href="#schedule"
               role="tab" aria-controls="schedule" aria-selected="false">Schedule</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="salary-tab" data-toggle="tab" href="#salary"
               role="tab" aria-controls="salary" aria-selected="false">Salary Scheme</a>
        </li>
    </ul>
    <div class="tab-content py-0 px-0">
        <div class="tab-pane fade show m-3 active" id="general" role="tabpanel" aria-labelledby="services-tab">
            <div class="result" style="display: none;">
                <span id="resultMsg"></span> <a href="#" id="closeResultMsg" class="pull-right"><span
                            class="fa fa-close"></span></a>
            </div>
            <form method="post" id="employeeForm" class="form row-border" action="#">
                <input type="hidden" id="employeeId" name="employeeId" class="form-control form-control-sm"
                       value="">
                <input type="hidden" id="isEmployeeDeleted" name="isDeleted" class="form-control form-control-sm"
                       value="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="surname">Surname</label>
                            <input type="text" id="surname" name="surname"
                                   class="form-control form-control-sm"
                                   value="">
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" class="form-control form-control-sm"
                                   value="">
                        </div>
                        <div class="form-group">
                            <label for="patronymic">Patronymic</label>
                            <input type="text" id="patronymic" name="patronymic"
                                   class="form-control form-control-sm"
                                   value="">
                        </div>
                        <div class="form-group">
                            <label for="position">Position</label>
                            <select id="position" name="position" class="form-control form-control-sm">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="dateOfBirth">Date Of Birth</label>
                            <input type="date" id="dateOfBirth" name="dateOfBirth"
                                   class="form-control form-control-sm"
                                   value="">
                        </div>
                        <div class="form-group">
                            <label for="email">e-mail</label>
                            <input type="text" id="email" name="email" class="form-control form-control-sm"
                                   value="">
                        </div>
                        <div class="form-group">
                            <label for="phoneNumber">Phone Number</label>
                            <input type="text" id="phoneNumber" name="phoneNumber"
                                   class="form-control form-control-sm"
                                   value="">
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-6 form-group text-left">
                        <button type="button" id="deleteEmployee" class="btn btn-outline-danger"></button>
                    </div>
                    <div class="col-6 form-group text-right">
                        <button type="button" id="submitEmployee" class="btn btn-primary"></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="tab-pane fade m-3" id="services" role="tabpanel" aria-labelledby="services-tab">
            <p>Services</p>
            <p>Coming Soon!</p>
        </div>
        <div class="tab-pane fade m-3" id="schedule" role="tabpanel" aria-labelledby="schedule-tab">
            <p>Schedule</p>
            <p>Coming Soon!</p>
        </div>
        <div class="tab-pane fade m-3" id="salary" role="tabpanel" aria-labelledby="salary-tab">
            <p>Salary Scheme</p>
            <p>Coming Soon!</p>
        </div>
    </div>
</div>