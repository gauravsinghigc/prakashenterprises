<section class="popup-form" id="AddNewInvoice">
    <div class="popup-form-container">
        <div class="sys-bg flex-s-b p-2 app-heading">
            <h5 class="mb-0 mt-1 ml-3">Add Invoice Details</h5>
            <a href="#" onclick="Databar('AddNewInvoice')" class='btn btn-danger btn-sm'><i class="fa fa-times"></i></a>
        </div>
        <div class="popup-form-body">
            <form class="row" action="<?php echo CONTROLLER; ?>/InvoiceController.php" method="POST">
                <?php FormPrimaryInputs(true, [
                    "InvoiceMainCustomerId" => $customerid
                ]); ?>
                <div class='col-md-4 form-group'>
                    <label>Invoice No <?php echo $req; ?></label>
                    <input type="text" readonly name="InvoiceCode" value="INV-<?php echo date("Y"); ?>-000<?php echo (int)FETCH("SELECT * FROM invoices where InvoiceCode like '%" . date('Y') . "%' ORDER by InvoiceId DESC limit 1", "InvoiceId") + 1; ?>" class="form-control form-control-sm" required="">
                </div>
                <div class='col-md-4 form-group'>
                    <label>Invoice Ref No <?php echo $req; ?></label>
                    <input type="text" name="InvoiceRefNo" class="form-control form-control-sm" required="">
                </div>
                <div class='col-md-4 form-group'>
                    <label>Invoice Date <?php echo $req; ?></label>
                    <input type="date" value="<?php echo date('Y-m-d'); ?>" name="InvoiceDate" class="form-control form-control-sm" required="">
                </div>
                <div class='col-md-4 form-group'>
                    <label>Service Type</label>
                    <select name="invoice_service_type" class="form-control form-control-sm" required>
                        <?php echo InputOptions(["Select Service Type", "Pay Per Call", "WARRANTY", "AMC", "CMC", "NO Service"], IfRequested("GET", "invoice_service_type", "Select Service Type", false)); ?>
                    </select>
                </div>
                <div class='col-md-4 form-group'>
                    <label>Service Charge <?php echo $req; ?></label>
                    <input type="text" value="" name="invoice_service_type_charge" class="form-control form-control-sm">
                </div>
                <div class='col-md-4 form-group'>
                    <label>Charge Payable at</label>
                    <select name="invoice_service_charge_payable" class="form-control form-control-sm" required>
                        <?php echo InputOptions(["Charges payable at", "Pay Per Call", "Monthly", "Annually", "Per Service"], IfRequested("GET", "invoice_service_charge_payable", "Charges payable at", false)); ?>
                    </select>
                </div>
                <div class="col-md-12 form-group">
                    <label>Invoice Notes</label>
                    <textarea name="InvoiceNotes" class="form-control form-control-sm editor" rows="4"></textarea>
                </div>
                <div class="col-md-12 text-right">
                    <a href="#" onclick="Databar('AddNewInvoice')" class="btn btn-sm btn-default">Cancel</a>
                    <button type="submit" name="SaveInvoiceRecord" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Create Invoice</button>
                </div>
            </form>
        </div>
    </div>
</section>