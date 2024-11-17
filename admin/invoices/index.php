<?php
$Dir = "../..";
require $Dir . '/require/modules.php';
require $Dir . '/require/admin/access-control.php';
require $Dir . '/require/admin/sessionvariables.php';

//pagevariables
$PageName = "All Invoices";
$PageDescription = "Manage all customers";
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
                    <div class="col-md-10">
                      <h5 class="app-heading"><?php echo $PageName; ?></h5>
                    </div>
                    <div class="col-md-2">
                      <a class='btn btn-block btn-md btn-danger' href="<?php echo DOMAIN; ?>/admin/invoices/create"><i class="fa fa-plus"></i> Create Invoice</a>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-6 col-6 mb-2">
                      <div class='bg-info p-2 shadow-sm text-white'>
                        <h4 class="mb-0"><?php echo TOTAL("SELECT * FROM invoices"); ?></h4>
                        <p class="small mb-0">Total Invoices</p>
                      </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-6 col-6 mb-2">
                      <div class='bg-info p-2 shadow-sm text-white'>
                        <h4 class="mb-0"><?php echo Price(AMOUNT("SELECT * from invoice_items", "InvoiceItemNetCost"), "text-white", "Rs."); ?></h4>
                        <p class="small mb-0">Net Invoices Amount</p>
                      </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-6 col-6 mb-2">
                      <div class='bg-success p-2 shadow-sm text-white'>
                        <h4 class="mb-0"><?php echo TOTAL("SELECT * FROM invoices where InvoiceStatus='Paid'"); ?></h4>
                        <p class="small mb-0">Paid Invoices</p>
                      </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-6 col-6 mb-2">
                      <div class='bg-warning p-2 shadow-sm text-white'>
                        <h4 class="mb-0"><?php echo TOTAL("SELECT * FROM invoices where InvoiceStatus='Pending'"); ?></h4>
                        <p class="small mb-0">Un-Paid Invoices</p>
                      </div>
                    </div>

                  </div>

                  <div class="row">
                    <div class="col-md-5 mb-2">
                      <input type="search" class="form-control form-control-sm" id="searchid" oninput="SearchData('searchid', 'invoice-data')" placeholder="Search Invoice...">
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <?php
                      $AllInvoices = FETCH_TABLE_FROM_DB("SELECT * FROM invoices ORDER BY DATE(InvoiceDate) DESC", true);
                      if ($AllInvoices != null) {
                        $SerialNo = 0;
                        foreach ($AllInvoices as $Invoice) {
                          $SerialNo++;
                      ?>
                          <div class="invoice-data">
                            <p class="data-list flex-s-b">
                              <span class="w-pr-2">
                                <span class='title'>Sno</span><br>
                                <span><?php echo $SerialNo; ?></span>
                              </span>
                              <span class="w-pr-10">
                                <span class='title'>InvoiceNo</span><br>
                                <span class='text-primary'>
                                  <a href="details/?inv=<?php echo SECURE($Invoice->InvoiceId, "e"); ?>" class='text-info'>
                                    <?php echo $Invoice->InvoiceCode; ?>
                                  </a>
                                </span>
                              </span>
                              <span class="w-pr-15">
                                <span class='title'>Customer</span><br>
                                <span class='bold'><?php echo FETCH("SELECT * FROM users where UserId='" . $Invoice->InvoiceMainCustomerId . "'", "UserFullName"); ?></span>
                              </span>
                              <span class="w-pr-8">
                                <span class='title'>InvoiceDate</span><br>
                                <span><?php echo DATE_FORMATE("d M, Y", $Invoice->InvoiceDate); ?></span>
                              </span>
                              <span class="w-pr-8">
                                <span class='title'>CreatedAt</span><br>
                                <span><?php echo DATE_FORMATE("d M, Y", $Invoice->InvoiceCreatedAt); ?></span>
                              </span>
                              <span class="w-pr-7">
                                <span class='title'>InvoiceAmount</span><br>
                                <span><?php echo Price($Total = AMOUNT("SELECT * from invoice_items WHERE MainInvoiceId='" . $Invoice->InvoiceId . "'", "InvoiceItemNetCost"), "text-info", "Rs."); ?></span>
                              </span>
                              <span class="w-pr-7">
                                <span class='title'>PaidAmount</span><br>
                                <span class='text-success'>Rs.<?php echo $Paid = AMOUNT("SELECT * FROM invoice_payments where MainInvoiceId='" . $Invoice->InvoiceId . "' and InvoicePaymentStatus='Paid'", "InvoicePaidAmount"); ?></span>
                              </span>
                              <span class="w-pr-7">
                                <span class='title'>BalanceAmount</span><br>
                                <span class="text-danger">Rs.<?php echo $Balance = $Total - $Paid; ?></span>
                              </span>
                              <span class="w-pr-7 text-right">
                                <span class='title'>Status</span><br>
                                <span>
                                  <?php
                                  if ($Invoice->InvoiceStatus == "Paid") {
                                    echo "<span class='text-success'><i class='fa fa-check'></i> Paid</span>";
                                  } else {
                                    echo "<span class='text-warning'><i class='fa fa-warning'></i> Pending</span>";
                                  }; ?>
                                </span>
                              </span>
                            </p>
                          </div>
                      <?php
                          //update invoices
                          if ($Balance <= 0) {
                            UPDATE("UPDATE invoices SET InvoiceStatus='Paid' where InvoiceId='" . $Invoice->InvoiceId . "'");
                          }
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
      </section>
    </div>

    <?php
    include $Dir . "/include/admin/footer.php"; ?>
  </div>

  <?php include $Dir . "/include/admin/footer_files.php"; ?>

</body>

</html>