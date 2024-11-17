<section class="popup-form" id="UpdatePaymentRecord">
    <div class="popup-form-container">
        <div class="sys-bg flex-s-b p-2 app-heading">
            <h5 class="mb-0 mt-1 ml-3">Update Payment Details</h5>
            <a href="#" onclick="Databar('UpdatePaymentRecord')" class='btn btn-danger btn-sm'><i class="fa fa-times"></i></a>
        </div>
        <div class="popup-form-body">
            <form class="row" action="<?php echo CONTROLLER; ?>/PaymentController.php" method="POST">
                <?php FormPrimaryInputs(true, [
                    "CartPaymentId" => FETCH($PaymentSql, "CartPaymentId")
                ]); ?>
                <div class='col-md-4 form-group'>
                    <label>Cart Payment Mode <?php echo $req; ?></label>
                    <select name="CartPaymentMode" class="form-control form-control-sm" required>
                        <?php InputOptions(["Select Mode", "Cash", "Online Transfer", "Cheque OR DD", "Wallet", "On Credit"], FETCH($PaymentSql, "CartPaymentMode")); ?>
                    </select>
                </div>
                <div class='col-md-4 form-group'>
                    <label>Paid Amount <?php echo $req; ?></label>
                    <input type="text" name="CartPaidAmount" value="<?php echo FETCH($PaymentSql, "CartPaidAmount"); ?>" class="form-control form-control-sm" required="">
                </div>
                <div class='col-md-4 form-group'>
                    <label>Reference No <?php echo $req; ?></label>
                    <input type="text" name="CartPaymentRefNo" value="<?php echo FETCH($PaymentSql, "CartPaymentRefNo"); ?>" class="form-control form-control-sm" required="">
                </div>
                <div class='col-md-4 form-group'>
                    <label>Payment date <?php echo $req; ?></label>
                    <input type="date" value="<?php echo FETCH($PaymentSql, "CartPaymentDate"); ?>" name="CartPaymentDate" class="form-control form-control-sm" required="">
                </div>
                <div class='col-md-4 form-group'>
                    <label>Payment Source <?php echo $req; ?></label>
                    <input type="text" name="CartPaymentSource" value="<?php echo FETCH($PaymentSql, "CartPaymentSource"); ?>" list="CartPaymentSource" class="form-control form-control-sm" required="">
                </div>
                <div class='col-md-4 form-group'>
                    <label>Payment Status <?php echo $req; ?></label>
                    <select name="CartPaymentStatus" class="form-control form-control-sm">
                        <?php InputOptions(["Select Status", "Paid", "Pending"], FETCH($PaymentSql, "CartPaymentStatus")); ?>
                    </select>
                </div>
                <div class="col-md-12 form-group">
                    <label>More Details</label>
                    <textarea name="CartPaymentDetails" class="form-control form-control-sm editor" rows="4"><?php echo SECURE(FETCH($PaymentSql, "CartPaymentDetails"), "d"); ?></textarea>
                </div>
                <div class="col-md-12 text-right">
                    <a href="#" onclick="Databar('UpdatePaymentRecord')" class="btn btn-sm btn-default">Cancel</a>
                    <button type="submit" name="UpdatePaymentDetails" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Update Record</button>
                </div>
            </form>
        </div>
    </div>
</section>