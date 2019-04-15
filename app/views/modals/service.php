<div class="panel-body card-body">
    <div class="result" style="display: none;">
        <span id="resultMsg"></span> <a href="#" id="closeResultMsg" class="pull-right"><span
                    class="fa fa-close"></span></a>
    </div>
    <form method="post" id="serviceForm" class="form row-border" action="#">
        <input type="hidden" id="serviceId" name="serviceId" value="">
        <input type="hidden" id="isServiceDeleted" name="isDeleted" value="">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="serviceName">Service Name</label>
                    <input type="text" id="serviceName" name="serviceName" class="form-control" value=""
                           required>
                </div>
                <div class="form-group">
                    <label for="categoryId">Category</label>
                    <select id="categoryId" name="categoryId" class="form-control">
                        <option></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="serviceCost">Service Cost</label>
                    <input type="number" id="serviceCost" name="serviceCost" class="form-control" value=""
                           min="0" required>
                </div>
                <div class="form-group">
                    <label for="measurementUnitId">Measurement Unit</label>
                    <select id="measurementUnitId" name="measurementUnitId" class="form-control">
                        <option></option>
                        <?php foreach ($measurementUnits as $row): ?>
                            <option value="<?php echo $row['measurementUnitId']; ?>"><?php echo $row['measurementUnitName']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="serviceDuration">Service Duration</label>
                    <input type="time" id="serviceDuration" name="serviceDuration" class="form-control" value=""
                           required>
                </div>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-6 form-group text-left">
                <button type="button" id="deleteService" class="btn btn-outline-danger"></button>
            </div>
            <div class="col-6 form-group text-right">
                <button type="button" id="submitService" class="btn btn-primary"></button>
            </div>
        </div>
    </form>
</div>

