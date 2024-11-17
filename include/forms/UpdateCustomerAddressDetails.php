<section class="popup-form" id="ucad">
  <div class="popup-form-container">
    <div class="sys-bg flex-s-b p-2 app-heading">
      <h5 class="mb-0 mt-1 ml-3">Update Customer Details</h5>
      <a href="#" onclick="Databar('ucad')" class='btn btn-danger btn-sm'><i class="fa fa-times"></i></a>
    </div>
    <div class="popup-form-body">
      <div class="row">
        <?php
        $FetchAddress = FETCH_TABLE_FROM_DB("SELECT * FROM user_addresses where UserAddressUserId='" . $Data->UserId . "'", true);
        if ($FetchAddress != null) {
          foreach ($FetchAddress as $Address) {
        ?>
            <div class="col-md-6 mb-2">
              <form class="row" action="<?php echo CONTROLLER; ?>/CustomerController.php" method="POST">
                <?php FormPrimaryInputs(true, [
                  "UserAddressId" => $Address->UserAddressId,
                  "UserAddressUserId" => $Data->UserId,
                  "UserAddressType" => $Address->UserAddressType
                ]); ?>
                <div class="col-md-12 form-group">
                  <h6 class='app-sub-heading mb-0'><?php echo $Address->UserAddressType; ?> Address</h6>
                  <label>House No/Flat No/Villa No <?php echo $req; ?></label>
                  <textarea name="CustomerStreetAddress" class="form-control form-control-sm" rows="2" required><?php echo SECURE($Address->UserStreetAddress, "d"); ?></textarea>
                </div>
                <div class='col-md-7 form-group'>
                  <label>Sector/Area Locality <?php echo $req; ?></label>
                  <input type="text" name="CustomerAreaLocality" value="<?php echo $Address->UserLocality; ?>" class="form-control form-control-sm" required="">
                </div>
                <div class='col-md-5 form-group'>
                  <label>City <?php echo $req; ?></label>
                  <input type="text" name="CustomerCity" value="<?php echo $Address->UserCity; ?>" class="form-control form-control-sm" required="">
                </div>
                <div class='col-md-4 form-group'>
                  <label>State <?php echo $req; ?></label>
                  <input type="text" name="CustomerState" value="<?php echo $Address->UserState; ?>" class="form-control form-control-sm" required="">
                </div>
                <div class='col-md-4 form-group'>
                  <label>Country <?php echo $req; ?></label>
                  <input type="text" name="CustomerCountry" value="<?php echo $Address->UserCountry; ?>" class="form-control form-control-sm" required="">
                </div>
                <div class='col-md-4 form-group'>
                  <label>Pincode <?php echo $req; ?></label>
                  <input type="text" name="CustomerPincode" value="<?php echo $Address->UserPincode; ?>" class="form-control form-control-sm" required="">
                </div>
                <div class="col-md-12 text-right mt-3">
                  <?php CONFIRM_DELETE_POPUP(
                    "address",
                    [
                      "remove_address_list" => true,
                      "UserAddressId" => $Address->UserAddressId
                    ],
                    "CustomerController",
                    "<i class='fa fa-trash'></i> Remove Address",
                    "btn btn-sm text-danger pull-left"
                  ); ?>
                  <button type="submit" name="UpdateAddress" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Update Address</button>
                  <button type='button' onclick="Databar('ucad')" class="btn btn-sm btn-default">Cancel</button>

                </div>
              </form>
            </div>
        <?php }
        } else {
          NoData("No Address Found!");
        } ?>
      </div>
    </div>
  </div>
</section>