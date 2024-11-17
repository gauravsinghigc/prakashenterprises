<section class="popup-form" id="ucid">
  <div class="popup-form-container">
    <div class="sys-bg flex-s-b p-2 app-heading">
      <h5 class="mb-0 mt-1 ml-3">Update Invoice Details</h5>
      <a href="#" onclick="Databar('ucid')" class='btn btn-danger btn-sm'><i class="fa fa-times"></i></a>
    </div>
    <div class="popup-form-body">
      <form class="row" action="<?php echo CONTROLLER; ?>/InvoiceController.php" method="POST">
        <?php FormPrimaryInputs(true, [
          "InvoiceId" => $InvoiceId
        ]); ?>
        <div class="col-md-12 form-group">
          <label>Update Customer</label>
          <select name="InvoiceMainCustomerId" class="form-control form-control-sm">
            <?php
            $AllCustomers = FETCH_TABLE_FROM_DB("SELECT * FROM users", true);
            if ($AllCustomers != null) {
              foreach ($AllCustomers as $Customer) {
                if ($Customer->UserId == FETCH("SELECT * FROM invoices where InvoiceId='$InvoiceId'", "InvoiceMainCustomerId")) {
                  $Selected = "selected";
                } else {
                  $Selected = "";
                }
            ?>
                <option value="<?php echo $Customer->UserId; ?>" <?php echo $Selected; ?>><?php echo $Customer->UserFullName; ?> @ <?php echo $Customer->UserPhoneNumber; ?></option>
            <?php
              }
            } ?>
          </select>
        </div>
        <div class='col-md-4 form-group'>
          <label>Invoice No <?php echo $req; ?></label>
          <input type=" text" readonly name="InvoiceCode" value="<?php echo FETCH("SELECT * FROM invoices where InvoiceId='$InvoiceId'", "InvoiceCode"); ?>" class="form-control form-control-sm" required="">
        </div>
        <div class='col-md-4 form-group'>
          <label>Invoice Ref No <?php echo $req; ?></label>
          <input type="text" name="InvoiceRefNo" value="<?php echo FETCH("SELECT * FROM invoices where InvoiceId='$InvoiceId'",  "InvoiceRefNo"); ?>" class="form-control form-control-sm" required="">
        </div>
        <div class='col-md-4 form-group'>
          <label>Invoice Date <?php echo $req; ?></label>
          <input type="text" value="<?php echo FETCH("SELECT * FROM invoices where InvoiceId='$InvoiceId'",  "InvoiceDate"); ?>" name="InvoiceDate" class="form-control form-control-sm" required="">
        </div>
        <div class="col-md-4 form-group">
          <label>Invoice Notes</label>
          <textarea name="InvoiceNotes" class="form-control form-control-sm editor" rows="4"><?php echo SECURE(FETCH("SELECT * FROM invoices where InvoiceId='$InvoiceId'",  "InvoiceNotes"), "d"); ?></textarea>
        </div>
        <div class="col-md-4 form-group">
          <label>Billing Address</label>
          <textarea name="InvoiceBillingAddress" class="form-control form-control-sm editor" rows="4"><?php echo SECURE(FETCH("SELECT * FROM invoices where InvoiceId='$InvoiceId'",  "InvoiceBillingAddress"), "d"); ?></textarea>
        </div>
        <div class="col-md-4 form-group">
          <label>Shipping Address</label>
          <textarea name="InvoiceShippingAddress" class="form-control form-control-sm editor" rows="4"><?php echo SECURE(FETCH("SELECT * FROM invoices where InvoiceId='$InvoiceId'",  "InvoiceShippingAddress"), "d"); ?></textarea>
        </div>
        <div class="col-md-12 text-right">
          <?php CONFIRM_DELETE_POPUP(
            "invoice_list",
            [
              "remove_invoice_record" => true,
              "InvoiceId" => FETCH("SELECT * FROM invoices where InvoiceId='$InvoiceId'",  "InvoiceId"),
            ],
            "InvoiceController",
            "Remove Invoice Permanently",
            "btn btn-sm text-danger"
          ); ?>
          <a href="#" onclick="Databar('ucid')" class="btn btn-sm btn-default">Cancel</a>
          <button type="submit" name="UpdateInvoiceRecord" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Update Invoice</button>
        </div>
      </form>
    </div>
  </div>
</section>