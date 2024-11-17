<section class="popup-form" id="ucpid">
    <div class="popup-form-container">
        <div class="sys-bg flex-s-b p-2 app-heading">
            <h5 class="mb-0 mt-1 ml-3">Update Customers details</h5>
            <a href="#" onclick="Databar('ucpid')" class='btn btn-danger btn-sm'><i class="fa fa-times"></i></a>
        </div>
        <div class="popup-form-body">
            <form class="row" action="<?php echo CONTROLLER; ?>/CustomerController.php" method="POST">
                <?php FormPrimaryInputs(true, [
                    "CustomerId" => $Data->CustomerId
                ]); ?>
                <div class='col-md-12'>
                    <h6 class='app-sub-heading'>Customer Details</h6>
                </div>
                <div class='col-md-4 form-group'>
                    <label>Customer Name <?php echo $req; ?></label>
                    <input type="text" name="CustomerName" value="<?php echo $Data->CustomerName; ?>" class="form-control form-control-sm" required="">
                </div>
                <div class='col-md-4 form-group'>
                    <label>S/O, W/O, D/O <?php echo $req; ?></label>
                    <input type="text" name="CustomerRelationName" value="<?php echo $Data->CustomerRelationName; ?>" class="form-control form-control-sm" required="">
                </div>
                <div class='col-md-4 form-group'>
                    <label>Customer Phone <?php echo $req; ?> <span id='phonemsg_<?php echo $Data->CustomerId; ?>'></span></label>
                    <input type="tel" placeholder="without +91" value="<?php echo $Data->CustomerPhoneNumber; ?>" oninput="CheckExistingPhoneNumbers_<?php echo $Data->CustomerId; ?>()" id="PhoneNumber_<?php echo $Data->CustomerId; ?>" name="CustomerPhoneNumber" class="form-control form-control-sm" required="">
                </div>
                <div class='col-md-5 form-group'>
                    <label>Customer Email-ID <?php echo $req; ?> <span id='emailmsg_<?php echo $Data->CustomerId; ?>'></span></label>
                    <input type="email" oninput="CheckExistingMailId_<?php echo $Data->CustomerId; ?>()" value="<?php echo $Data->CustomerEmailId; ?>" class="form-control form-control-sm" id="EmailId_<?php echo $Data->CustomerId; ?>" name="CustomerEmailId" class="form-control form-control-sm" required="">
                </div>
                <div class='col-md-3 form-group'>
                    <label>Date of Birth <?php echo $req; ?></label>
                    <input type="date" value="<?php echo $Data->CustomerBirthdate; ?>" name="CustomerBirthdate" class="form-control form-control-sm" required="">
                </div>
                <div class="col-md-12 text-right">
                    <button type="submit" id="subbtn_<?php echo $Data->CustomerId; ?>" name="UpdateCustomerRecord" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Update Record</button>
                    <a href="#" onclick="Databar('ucpid')" class="btn btn-sm btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
    function CheckExistingPhoneNumbers_<?php echo $Data->CustomerId; ?>() {
        let SearchingFor_<?php echo $Data->CustomerId; ?> = document.getElementById("PhoneNumber_<?php echo $Data->UserId; ?>");
        var phonemsg_<?php echo $Data->CustomerId; ?> = document.getElementById("phonemsg_<?php echo $Data->UserId; ?>");
        var pattern_<?php echo $Data->CustomerId; ?> = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
        var subbtn_<?php echo $Data->CustomerId; ?> = document.getElementById("subbtn_<?php echo $Data->UserId; ?>");
        let ExistingPhoneNumbers_<?php echo $Data->CustomerId; ?> = [<?php
                                                                        $AllData = FETCH_TABLE_FROM_DB("SELECT * FROM users", true);
                                                                        if ($AllData != null) {
                                                                            foreach ($AllData as $Data) {
                                                                                echo "'" . $Data->UserPhoneNumber . "', ";
                                                                            }
                                                                        } ?>];

        if (ExistingPhoneNumbers_<?php echo $Data->CustomerId; ?>.includes(SearchingFor_<?php echo $Data->UserId; ?>.value)) {
            phonemsg_<?php echo $Data->CustomerId; ?>.classList.add("text-danger");
            phonemsg_<?php echo $Data->CustomerId; ?>.classList.remove("text-warning");
            phonemsg_<?php echo $Data->CustomerId; ?>.innerHTML = "<i class='fa fa-warning'></i> Phone Number Already Exits";
            subbtn_<?php echo $Data->CustomerId; ?>.type = "button";
        } else if (pattern_<?php echo $Data->CustomerId; ?>.test(SearchingFor_<?php echo $Data->UserId; ?>.value) == false) {
            phonemsg_<?php echo $Data->CustomerId; ?>.classList.add("text-warning");
            phonemsg_<?php echo $Data->CustomerId; ?>.classList.remove("text-danger");
            phonemsg_<?php echo $Data->CustomerId; ?>.innerHTML = "<i class='fa fa-warning'></i> Phone Number is not valid";
            subbtn_<?php echo $Data->CustomerId; ?>.type = "button";
        } else {
            phonemsg_<?php echo $Data->CustomerId; ?>.classList.remove("text-danger");
            phonemsg_<?php echo $Data->CustomerId; ?>.classList.remove("text-warning");
            phonemsg_<?php echo $Data->CustomerId; ?>.classList.add("text-success");
            phonemsg_<?php echo $Data->CustomerId; ?>.innerHTML = "<i class='fa fa-check'></i> Phone Number is Ok";
            subbtn_<?php echo $Data->CustomerId; ?>.type = "submit";
        }
    }

    function CheckExistingMailId_<?php echo $Data->CustomerId; ?>() {
        let SearchingFor_<?php echo $Data->CustomerId; ?> = document.getElementById("EmailId_<?php echo $Data->UserId; ?>");
        var emailmsg_<?php echo $Data->CustomerId; ?> = document.getElementById("emailmsg_<?php echo $Data->UserId; ?>");
        var pattern_<?php echo $Data->CustomerId; ?> = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        var subbtn_<?php echo $Data->CustomerId; ?> = document.getElementById("subbtn_<?php echo $Data->UserId; ?>");
        let CheckExistingMailId_<?php echo $Data->CustomerId; ?> = [<?php
                                                                    $AllData = FETCH_TABLE_FROM_DB("SELECT * FROM users", true);
                                                                    if ($AllData != null) {
                                                                        foreach ($AllData as $Data) {
                                                                            echo "'" . $Data->UserEmailId . "', ";
                                                                        }
                                                                    } ?>];

        if (CheckExistingMailId_<?php echo $Data->CustomerId; ?>.includes(SearchingFor_<?php echo $Data->UserId; ?>.value)) {
            emailmsg_<?php echo $Data->CustomerId; ?>.classList.add("text-danger");
            emailmsg_<?php echo $Data->CustomerId; ?>.classList.remove("text-warning");
            emailmsg_<?php echo $Data->CustomerId; ?>.innerHTML = "<i class='fa fa-warning'></i> Email-Id Already Exits";
            subbtn_<?php echo $Data->CustomerId; ?>.type = "button";
        } else if (pattern.test(SearchingFor.value) == false) {
            emailmsg_<?php echo $Data->CustomerId; ?>.classList.add("text-warning");
            emailmsg_<?php echo $Data->CustomerId; ?>.classList.remove("text-danger");
            emailmsg_<?php echo $Data->CustomerId; ?>.innerHTML = "<i class='fa fa-warning'></i> Email-ID is not valid";
            subbtn_<?php echo $Data->CustomerId; ?>.type = "button";
        } else {
            emailmsg_<?php echo $Data->CustomerId; ?>.classList.remove("text-danger");
            emailmsg_<?php echo $Data->CustomerId; ?>.classList.remove("text-warning");
            emailmsg_<?php echo $Data->CustomerId; ?>.classList.add("text-success");
            emailmsg_<?php echo $Data->CustomerId; ?>.innerHTML = "<i class='fa fa-check'></i> Email-ID is Ok";
            subbtn_<?php echo $Data->CustomerId; ?>.type = "submit";
        }
    }
</script>