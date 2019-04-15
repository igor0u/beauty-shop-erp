<div id="visitForm" class="panel-body card-body">
    <div class="result" style="display: none;">
        <span id="resultMsg"></span><a href="#" id="closeResultMsg" class=""><span
                    class="">&times;</span></a>
    </div>
    <form method="post" id="commonForm" class="form row-border visit-add" action="#">
        <input type="hidden" id="visitId" name="visitId" class="" value="">
        <input type="hidden" id="clientId" name="clientId" class="client" value="">
        <div class="row">
            <div class="col-md-9 card p-3 mb-3">
                <div class="form-inline">
                    <div class="typeahead__container">
                        <label for="surname">Surname</label>
                        <input type="text" id="surname" name="surname"
                               class="form-control form-control-sm client" autocomplete="off"
                               value="">
                    </div>
                    <div class="typeahead__container">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name"
                               class="form-control form-control-sm client" autocomplete="off"
                               value="">
                    </div>
                    <div class="typeahead__container">
                        <label for="patronymic">Patronymic</label>
                        <input type="text" id="patronymic" name="patronymic"
                               class="form-control form-control-sm client" autocomplete="off"
                               value="">
                    </div>
                    <div class="typeahead__container">
                        <label for="phoneNumber">Phone</label>
                        <input type="tel" id="phone" name="phoneNumber"
                               class="form-control form-control-sm client" autocomplete="off"
                               value="">
                    </div>
                    <div class="align-self-end ml-4">
                        <button type="button" id="clearClientData"
                                class="form-control pt-0 pb-0 btn btn-outline-warning btn-icon-text">
                            <i class="mdi mdi-reload btn-icon-prepend"></i>
                            Clear
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-3 card p-3 mb-3">
                <div class="">
                    <label for="visitDate">Visit Date</label>
                    <input type="date" id="visitDate" name="visitDate" autocomplete="off"
                           class="form-control form-control-sm"
                           value="">
                </div>
            </div>
        </div>
    </form>
</div>
<div class="card p-3 mb-3">
    <div id="servicesTable" class="d-table">
        <div id="tableHeader" class="d-table-row services-table-header">
            <div class="d-table-cell text-center">Service</div>
            <div class="d-table-cell text-center">Employee</div>
            <div class="d-table-cell text-center">Start Time</div>
            <div class="d-table-cell text-center">Duration</div>
            <div class="d-table-cell text-center">End Time</div>
            <div class="d-table-cell text-center"><abbr title="Unit Of Measurement">UoM</abbr></div>
            <div class="d-table-cell text-center">Quantity</div>
            <div class="d-table-cell text-center">Cost</div>
            <div class="d-table-cell text-center">Discount (%)</div>
            <div class="d-table-cell text-center">Total</div>
            <div class="d-table-cell"></div>
        </div>
        <div id="first">
            <form class="d-table-row form-group recordset">
                <input type="text" class="form-control service-id" name="serviceId" id="serviceId" value=""
                       hidden>
                <input type="text" class="service-id" name="orderedServiceId"
                       id="orderedServiceId" value=""
                       hidden>
                <input type="text" class="form-control employee-id" name="employeeId" id="employeeId"
                       value=""
                       hidden>
                <input type="number" class="form-control total-price" name="totalPrice" id="totalPrice"
                       value="0"
                       hidden>
                <div class="d-table-cell"><input type="text" class="form-control form-control-sm service"
                                                 name="service" id="service"></div>
                <div class="d-table-cell">
                    <input type="text"
                           class="form-control form-control-sm typeahead-staff employee"
                           name="employee" id="employeeName"
                    >
                </div>
                <div class="d-table-cell"><input type="time"
                                                 class="form-control form-control-sm service-start-time"
                                                 name="startTime" id="startTime"></div>
                <div class="d-table-cell"><input type="time"
                                                 class="form-control form-control-sm service-duration"
                                                 name="duration" id="duration"></div>
                <div class="d-table-cell"><input type="time"
                                                 class="form-control form-control-sm service-end-time"
                                                 name="endTime" id="endTime"></div>
                <div class="d-table-cell text-center">
                    <p><abbr id="measurementUnitAbbr" title=""></abbr>
                    </p></div>
                <div class="d-table-cell"><input type="number"
                                                 class="form-control form-control-sm price service-quantity"
                                                 name="quantity" id="quantity" min="1" value="1"></div>
                <div class="d-table-cell"><input type="number" id="serviceCost"
                                                 class="form-control form-control-sm price service-cost"
                                                 name="cost" min="0" value="0"></div>
                <div class="d-table-cell"><input type="number"
                                                 class="form-control form-control-sm price service-discount"
                                                 name="discount" id=discount" min="0" value="0"></div>
                <div class="d-table-cell total-field">0.00</div>
                <div class="d-table-cell delete-action"></div>
            </form>
        </div>
    </div>
    <hr>
    <div class="form-group text-left">
        <button type="button" id="btnPlus" class="btn btn-primary add-service">Add Service</button>
    </div>
    <div class="form-group text-right">
        <h4 class="d-inline">Grand Total: </h4><h4 class="d-inline" id="grandTotal">0.00</h4>
    </div>
    <hr>
    <div class="row text-center">
        <div class="col-6 form-group text-left">
            <button type="button" id="deleteVisit" class="btn btn-danger" disabled>Delete Visit</button>
        </div>
        <div class="col-6 form-group text-right">
            <button type="button" id="submitVisit" class="btn btn-primary" disabled></button>
        </div>
    </div>
</div>
