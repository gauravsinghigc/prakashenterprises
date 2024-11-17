<?php
$Dir = "../../..";
require $Dir . '/require/modules.php';
require $Dir . '/require/admin/access-control.php';
require $Dir . '/require/admin/sessionvariables.php';

//pagevariables
$PageName = IfRequested("GET", "view", "Invoice Details", false);

$InvoiceId = SECURE(IfRequested("GET", "inv", "", false), "d");
$PageDescription = "Manage all customers";
$ServiceSql = "SELECT * FROM invoice_service_type where invoice_service_main_id='$InvoiceId'";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title><?php echo $PageName; ?> | <?php echo APP_NAME; ?></title>
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
  <meta name="keywords" content="<?php echo APP_NAME; ?>">
  <meta name="description" content="<?php echo SHORT_DESCRIPTION; ?>">
  <?php include $Dir . "/include/admin/header_files.php"; ?>
  <script type="text/javascript">
    function SidebarActive() {
      document.getElementById("customers").classList.add("active");
    }
    window.onload = SidebarActive;
  </script>
</head>

<body class="hold-transition sidebar-mini sidebar-collapse">
  <div class="wrapper">
    <?php include $Dir . "/include/admin/loader.php"; ?>

    <?php
    include $Dir . "/include/admin/header.php";
    include $Dir . "/include/admin/sidebar.php"; ?>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">

            <div class="col-12">
              <div class="card card-primary">
                <div class="card-body">


                  <div class="row">
                    <div class="col-md-4">
                      <div class="bg-white p-2 shadow-sm">
                        <h2 class='text-primary'>
                          <i class='fa fa-hashtag'></i> <?php echo FETCH("SELECT * FROM invoices where InvoiceId='$InvoiceId'", "InvoiceCode"); ?>
                        </h2>
                        <p class="data-list mt-2 mb-3 flex-s-b">
                          <span>
                            <span class="title">NetAmount:</span><br>
                            <span class="h5"><?php echo Price($Total = AMOUNT("SELECT * from invoice_items WHERE MainInvoiceId='" . $InvoiceId . "'", "InvoiceItemNetCost"), "text-info", "Rs."); ?></span>
                          </span>
                          <span>
                            <span class="title">Received:</span><br>
                            <span class='text-success h5'>Rs.<?php echo $Paid = AMOUNT("SELECT * FROM invoice_payments where MainInvoiceId='" . $InvoiceId . "' and InvoicePaymentStatus='Paid'", "InvoicePaidAmount"); ?></span>
                          </span>
                          <span>
                            <span class="title">Balance:</span><br>
                            <span class="text-danger h5">Rs.<?php echo $Balance = $Total - $Paid; ?></span>
                          </span>
                        </p>

                        <p class="text-gray">
                          <span>
                            <span>Status :
                              <span class="">
                                <?php
                                if (FETCH("SELECT * FROM invoices where InvoiceId='$InvoiceId'", "InvoiceStatus") == "Paid") {
                                  echo "<span class='text-success'><i class='fa fa-check'></i> Paid</span>";
                                } else {
                                  echo "<span class='text-warning'><i class='fa fa-warning'></i> Pending</span>";
                                }; ?>
                              </span>
                            </span>
                            <br>
                            <span class="">Ref No:</span>
                            <span><?php echo FETCH("SELECT * FROM invoices where InvoiceId='$InvoiceId'", "InvoiceRefNo", false, "RefNo"); ?></span>
                            <br>
                            <span>Invoice Date :</span>
                            <span><?php echo DATE_FORMATE("d M, Y", FETCH("SELECT * FROM invoices where InvoiceId='$InvoiceId'", "InvoiceDate")); ?></span>
                            <br>
                            <span>Update Date :</span>
                            <span><?php echo DATE_FORMATE("d M, Y", FETCH("SELECT * FROM invoices where InvoiceId='$InvoiceId'", "InvoiceCreatedAt")); ?></span>
                            <br>
                          </span>
                        </p>

                        <h6 class="mb-1 text-gray">Customer Details:</h6>
                        <hr class="mt-0 mb-1">
                        <p class="mb-3 small"><?php echo html_entity_decode(FETCH("SELECT * FROM invoices where InvoiceId='$InvoiceId'", "InvoiceCustomerDetails")); ?></p>

                        <h6 class="mb-1 text-gray">Billing Address:</h6>
                        <hr class="mt-0 mb-1">
                        <p class="mb-3 small"><?php echo SECURE(FETCH("SELECT * FROM invoices where InvoiceId='$InvoiceId'", "InvoiceBillingAddress"), "d"); ?></p>

                        <h6 class="mb-1 text-gray">Shipping Address:</h6>
                        <hr class="mt-0 mb-1">
                        <p class="mb-3 small"><?php echo SECURE(FETCH("SELECT * FROM invoices where InvoiceId='$InvoiceId'", "InvoiceShippingAddress"), "d"); ?></p>

                        <h6 class="mb-1 text-gray">Notes:</h6>
                        <hr class="mt-0 mb-1">
                        <p class="mb-3 small"><?php echo SECURE(FETCH("SELECT * FROM invoices where InvoiceId='$InvoiceId'", "InvoiceNotes"), "d"); ?></p>

                        <h6 class="mb-1 text-gray">Service Types:</h6>
                        <hr class="mt-0 mb-1">
                        <p class="mb-4 small">
                          <span>
                            <span class="bold">Service Type :</span>
                            <span><?php echo FETCH($ServiceSql, "invoice_service_type"); ?></span>
                          </span><br>
                          <span>
                            <span class="bold">Service Charges:</span>
                            <span><?php echo Price(FETCH($ServiceSql, "invoice_service_type_charge"), "", "Rs."); ?></span>
                          </span><br>
                          <span>
                            <span class="bold">Charges Payable at:</span>
                            <span><?php echo FETCH($ServiceSql, "invoice_service_charge_payable"); ?></span>
                          </span><br>
                          <a href='#' onclick="Databar('Service-Charge-Updates')" class="btn btn-xs text-white btn-success pull-right"><span class='text-white'>Update Service Charge</span></a>
                        </p>
                      </div>
                    </div>
                    <div class="col-md-8">
                      <div class="mb-2">
                        <a href="#" onclick="Databar('ucid')" class="btn btn-sm btn-default"><i class='fa fa-edit'></i> Invoice details</a>
                        <a href="products.php?inv=<?php echo IfRequested("GET", "inv", "", false); ?>" class="btn btn-sm btn-default"><i class="fa fa-list"></i> Product Details</a>
                        <a href="#" onclick="Databar('aipoc')" class="btn btn-sm btn-default"><i class='fa fa-plus'></i> Payment Record</a>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <h4 class="app-sub-heading">All Transactions</h4>
                          <?php
                          $AllData = FETCH_TABLE_FROM_DB("SELECT * FROM invoice_payments where MainInvoiceId='$InvoiceId' ORDER BY DATE(InvoicePaymentDate) DESC", true);
                          if ($AllData != null) {
                            $SerialNo = 0;
                            foreach ($AllData as $Data) {
                              $SerialNo++;
                              $InvoicePaymentId = $Data->InvoicePaymentId;
                              $InvSql = "SELECT * FROM invoices where InvoiceId='" . $Data->MainInvoiceId . "'";
                          ?>
                              <div class="payment-data">
                                <p class="data-list flex-s-b">
                                  <span class="w-pr-5">
                                    <span class='title'>RefId</span><br>
                                    <span class='text-primary'>
                                      <a href="#" class='text-info' onclick="Databar('uprfihpi_<?php echo $Data->InvoicePaymentId; ?>')">TXN0<?php echo $Data->InvoicePaymentId; ?></a>
                                    </span>
                                  </span>
                                  <span class="w-pr-15">
                                    <span class='title'>PayMode</span><br>
                                    <span><?php echo $Data->InvoicePaymentMode; ?></span>
                                  </span>
                                  <span class="w-pr-15">
                                    <span class='title'>PaySource</span><br>
                                    <span><?php echo $Data->InvoicePaymentSource; ?></span>
                                  </span>
                                  <span class="w-pr-15">
                                    <span class='title'>PaidDate</span><br>
                                    <span><?php echo DATE_FORMATE("d M, Y", $Data->InvoicePaymentDate); ?></span>
                                  </span>
                                  <span class="w-pr-10">
                                    <span class='title'>Status</span><br>
                                    <span>
                                      <?php
                                      if ($Data->InvoicePaymentStatus == "Paid") {
                                        echo "<span class='text-success'><i class='fa fa-check'></i> Paid</span>";
                                      } else {
                                        echo "<span class='text-warning'><i class='fa fa-warning'></i> Pending</span>";
                                      }; ?>
                                    </span>
                                  </span>
                                  <span class="w-pr-10">
                                    <span class='title'>PaidAmount</span><br>
                                    <span><?php echo Price($Data->InvoicePaidAmount, "text-success", "Rs."); ?></span>
                                  </span>
                                </p>
                              </div>
                          <?php
                              include $Dir . "/include/forms/UpdatePaymentRecordForInvoices.php";
                            }
                          } else {
                            NoData("No Invoice found!");
                          } ?>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
      </section>
    </div>

    <?php
    include $Dir . "/include/forms/AddinvoicePaymentRecord.php";
    include $Dir . "/include/forms/UpdateInvoiceRecord.php";
    include $Dir . "/include/forms/Add-Service-Charges.php";
    include $Dir . "/include/admin/footer.php"; ?>
  </div>

  <?php include $Dir . "/include/admin/footer_files.php"; ?>

</body>

</html>