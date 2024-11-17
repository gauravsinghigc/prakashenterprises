<section class="popup-form" id="uprfihpi_<?php echo $Data->InvoicePaymentId; ?>">
    <div class="popup-form-container">
        <div class="sys-bg flex-s-b p-2 app-heading">
            <h5 class="mb-0 mt-1 ml-3">Update Payment Details</h5>
            <a href="#" onclick="Databar('uprfihpi_<?php echo $Data->InvoicePaymentId; ?>')" class='btn btn-danger btn-sm'><i class="fa fa-times"></i></a>
        </div>
        <div class="popup-form-body">
            <form class="row" action="<?php echo CONTROLLER; ?>/PaymentController.php" method="POST">
                <?php FormPrimaryInputs(true, [
                    "InvoicePaymentId" => $Data->InvoicePaymentId
                ]); ?>
                <div class='col-md-4 form-group'>
                    <label>Payment Mode <?php echo $req; ?></label>
                    <select name="InvoicePaymentMode" class="form-control form-control-sm" required>
                        <?php InputOptions(["Select Mode", "Cash", "Online Transfer", "Cheque OR DD", "Wallet"], FETCH("SELECT * FROM invoice_payments where MainInvoiceId='$InvoicePaymentId'", "InvoicePaymentMode")); ?>
                    </select>
                </div>
                <div class='col-md-4 form-group'>
                    <label>Paid Amount <?php echo $req; ?></label>
                    <input type="text" name="InvoicePaidAmount" value="<?php echo FETCH("SELECT * FROM invoice_payments where MainInvoiceId='$InvoicePaymentId'", "InvoicePaidAmount"); ?>" class="form-control form-control-sm" required="">
                </div>
                <div class='col-md-4 form-group'>
                    <label>Reference No <?php echo $req; ?></label>
                    <input type="text" name="InvoicePaymentRefNo" value="<?php echo FETCH("SELECT * FROM invoice_payments where MainInvoiceId='$InvoicePaymentId'", "InvoicePaymentRefNo"); ?>" class="form-control form-control-sm" required="">
                </div>
                <div class='col-md-4 form-group'>
                    <label>Payment date <?php echo $req; ?></label>
                    <input type="date" name="InvoicePaymentDate" value="<?php echo FETCH("SELECT * FROM invoice_payments where MainInvoiceId='$InvoicePaymentId'", "InvoicePaymentDate"); ?>" class="form-control form-control-sm" required="">
                </div>
                <div class='col-md-4 form-group'>
                    <label>Payment Source <?php echo $req; ?></label>
                    <input type="text" name="InvoicePaymentSource" list="CartPaymentSource" value="<?php echo FETCH("SELECT * FROM invoice_payments where MainInvoiceId='$InvoicePaymentId'", "InvoicePaymentSource"); ?>" class="form-control form-control-sm" required="">
                </div>
                <div class='col-md-4 form-group'>
                    <label>Payment Status <?php echo $req; ?></label>
                    <select name="InvoicePaymentStatus" class="form-control form-control-sm">
                        <?php InputOptions(["Select Status", "Paid", "Pending"], FETCH("SELECT * FROM invoice_payments where MainInvoiceId='$InvoicePaymentId'", "InvoicePaymentStatus")); ?>
                    </select>
                </div>
                <div class="col-md-12 form-group">
                    <label>More Details</label>
                    <textarea name="InvoicePaymentNotes" class="form-control form-control-sm editor" rows="4"><?php echo SECURE(FETCH("SELECT * FROM invoice_payments where MainInvoiceId='$InvoicePaymentId'", "InvoicePaymentNotes"), "d"); ?></textarea>
                </div>
                <div class="col-md-12 text-right">
                    <a href="#" onclick="Databar('uprfihpi_<?php echo $Data->InvoicePaymentId; ?>')" class="btn btn-sm btn-default">Cancel</a>
                    <button type="submit" name="UpdateInvoicePaymentDetails" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Update Record</button>
                </div>
            </form>
        </div>
    </div>
</section>