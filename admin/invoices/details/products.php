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
                        <a href="index.php?inv=<?php echo IfRequested("GET", "inv", "", false); ?>" class="btn btn-sm btn-default"><i class='fa fa-exchange'></i> All Transactions</a>
                        <a href="#" onclick="Databar('aipoc')" class="btn btn-sm btn-default"><i class='fa fa-plus'></i> Payment Record</a>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <h4 class="app-sub-heading">All Products</h4>
                          <?php
                          $AllInvProducts = FETCH_TABLE_FROM_DB("SELECT * FROM invoice_items where MainInvoiceId='$InvoiceId'", true);
                          if ($AllInvProducts != null) {
                            foreach ($AllInvProducts as $InvPro) {
                              $InvoiceItemId = $InvPro->InvoiceItemId;
                              $ProductImages = FETCH("SELECT * FROM product_images where ProductMainProductid='$InvoiceItemId'", "ProductImages");
                              $SalePrice = $InvPro->InvoiceItemSalePrice;
                              $SalePrice = $InvPro->InvoiceItemSalePrice / $InvPro->InvoiceItemQty;
                              $NetCost = $InvPro->InvoiceItemNetCost;
                              $Balance = $NetCost - $SalePrice;
                              $TaxPer = $Balance;
                              $TaxPer = round($Balance / $SalePrice * 100, 2);
                          ?>
                              <div class="data-display flex-s-b">
                                <div class="w-20">
                                  <img src="<?php echo STORAGE_URL; ?>/products/pro-img/<?php echo $InvoiceItemId; ?>/<?php echo $ProductImages; ?>" class="img-fluid">
                                </div>
                                <div class="w-20">
                                  <h6><?php echo html_entity_decode(html_entity_decode($InvPro->InvoiceItemName)); ?></h6>
                                </div>
                                <div class='w-20'>
                                  <?php echo Price($SalePrice, "text-black", "Rs."); ?>
                                  +
                                  <span class='text-primary'>Rs.<?php echo $Balance; ?> <span class="text-grey"> GST (<?php echo $TaxPer; ?>%)</span><br> = Rs.<?php echo $SalePrice + $Balance; ?> </span>
                                </div>
                                <div class="w-20">
                                  x <?php echo $InvPro->InvoiceItemQty; ?>
                                </div>
                                <div class="w-20">
                                  <?php echo Price($InvPro->InvoiceItemNetCost, "text-success", "Rs."); ?>
                                </div>
                              </div>
                          <?php
                            }
                          } else {
                            NoData("No Products Found!");
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