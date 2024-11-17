<section class="popup-form" id="Service-Charge-Updates">
  <div class="popup-form-container">
    <div class="sys-bg flex-s-b p-2 app-heading">
      <h5 class="mb-0 mt-1 ml-3">Service Charge Details</h5>
      <a href="#" onclick="Databar('Service-Charge-Updates')" class='btn btn-danger btn-sm'><i class="fa fa-times"></i></a>
    </div>
    <div class="popup-form-body">
      <form class="row" action="<?php echo CONTROLLER; ?>/InvoiceController.php" method="POST">
        <?php FormPrimaryInputs(true, [
          "invoice_service_main_id" => $InvoiceId
        ]); ?>
        <div class='col-md-4 form-group'>
          <label>Service Type</label>
          <select name="invoice_service_type" class="form-control form-control-sm" required>
            <?php echo InputOptions(["Select Service Type", "Pay Per Call", "WARRANTY", "AMC", "CMC", "NO Service"], FETCH($ServiceSql, "invoice_service_type")); ?>
          </select>
        </div>
        <div class='col-md-4 form-group'>
          <label>Service Charge <?php echo $req; ?></label>
          <input type="text" value="<?php echo FETCH($ServiceSql, "invoice_service_type_charge"); ?>" name="invoice_service_type_charge" class="form-control form-control-sm">
        </div>
        <div class='col-md-4 form-group'>
          <label>Charge Payable at</label>
          <select name="invoice_service_charge_payable" class="form-control form-control-sm" required>
            <?php echo InputOptions(["Charges payable at", "Pay Per Call", "Monthly", "Annually", "Per Service"], FETCH($ServiceSql, "invoice_service_charge_payable")); ?>
          </select>
        </div>
        <div class="col-md-12 text-right">
          <a href="#" onclick="Databar('Service-Charge-Updates')" class="btn btn-sm btn-default">Cancel</a>
          <button type="submit" name="UpdateInvoiceCharges" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Update Service Charges</button>
        </div>
      </form>
    </div>
  </div>
</section>