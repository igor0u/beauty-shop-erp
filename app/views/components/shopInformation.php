<p class="card-title">Beauty Shop Information</p>
<div class="row">
    <div class="col-md-10">
        <form method="post" class="form-horizontal row-border col-md-10 col-lg-8" action="/settings/update/info">
            <div class="form-group">
                <label for="beautyShopName">Beauty Shop Name</label>
                <input type="text" id="beautyShopName" name="beautyShopName" class="form-control"
                       value="<?php echo $shopInfo['beautyShopName'] ?>">
            </div>
            <div class="form-group">
                <label for="legalEntity">Legal Entity</label>
                <input type="text" id="legalEntity" name="legalEntity" class="form-control"
                       value="<?php echo $shopInfo['legalEntity'] ?>">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" class="form-control"
                       value="<?php echo $shopInfo['address'] ?>">
            </div>
            <div class="form-group">
                <label for="phoneNumber">Phone Number</label>
                <input type="tel" id="phoneNumber" name="phoneNumber" class="form-control"
                       value="<?php echo $shopInfo['phoneNumber'] ?>">
            </div>
            <div class="form-group">
                <label for="taxId">Tax ID</label>
                <input type="number" id="taxId" name="taxId" class="form-control"
                       value="<?php echo $shopInfo['taxId'] ?>">
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>