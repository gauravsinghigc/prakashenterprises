<?php
$Dir = "../..";
require $Dir . '/require/modules.php';
require $Dir . '/require/admin/access-control.php';
require $Dir . '/require/admin/sessionvariables.php';

//pagevariables
$PageName = "All Transactions";
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
                      <h5 class='app-heading'>All Service/Complaint Payments</h5>
                    </div>
                    <div class='col-md-2'>
                      <a href="#" class='btn btn-md btn-block btn-danger' onclick="Databar('Filters')"><i class='fa fa-filter'></i> Apply Filters</a>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <a href="index.php">
                        <div class="card p-2 bg-info">
                          <h4 class='mb-0'>Rs.<?php echo $Receiable = AMOUNT("SELECT * FROM complaint_charges", "complaint_charge_amount"); ?></h4>
                          <p class="small">Net Receivable</p>
                        </div>
                      </a>
                    </div>
                    <div class="col-md-4">
                      <a href="index.php?status=PAID">
                        <div class="card p-2 bg-success">
                          <h4 class='mb-0'>Rs.<?php echo $Receiced = AMOUNT("SELECT * FROM complaint_charges where complaint_charge_status='PAID'", "complaint_charge_amount"); ?></h4>
                          <p class="small">Net Received</p>
                        </div>
                      </a>
                    </div>

                    <div class="col-md-4">
                      <a href="index.php?status=UN-PAID">
                        <div class="card p-2 bg-danger">
                          <h4 class='mb-0'>Rs.<?php echo $Pendings = AMOUNT("SELECT * FROM complaint_charges where complaint_charge_status='UN-PAID'", "complaint_charge_amount"); ?></h4>
                          <p class="small">Pendings</p>
                        </div>
                      </a>
                    </div>
                  </div>

                  <?php if (isset($_GET['ComplaintsNo'])) {
                    $Filters = "";
                  } else {
                    $Filters = "hidden";
                  }
                  ?>
                  <div id='Filters' class="<?php echo $Filters; ?>">
                    <form>
                      <div class="row">
                        <div class="col-md-3 form-group">
                          <label>Complaint No</label>
                          <input type="search" class="form-control" list='ComplaintsCustomRefId' onchange="form.submit()" name="ComplaintsNo" value='<?php echo IfRequested("GET", "ComplaintsNo", "", false); ?>' placeholder="Complaint No">
                          <?php SUGGEST("complaints", "ComplaintsCustomRefId", "ASC"); ?>
                        </div>
                        <div class="col-md-3 form-group">
                          <label>Engineer Name</label>
                          <input type="search" class="form-control" onchange="form.submit()" name="ServiceEngineerName" value='<?php echo IfRequested("GET", "ServiceEngineerName", "", false); ?>' placeholder="Service Engineer Name">
                        </div>
                        <div class="col-md-3 form-group">
                          <label>Hospital Name</label>
                          <input type="search" class="form-control" list='UserCompanyName' onchange="form.submit()" name="UserCompanyName" value='<?php echo IfRequested("GET", "UserCompanyName", "", false); ?>' placeholder="Hospital Name">
                          <?php SUGGEST("users", "UserCompanyName", "ASC"); ?>
                        </div>
                        <div class="col-md-3 form-group">
                          <label>Complaint Status</label>
                          <select name='ComplaintStatus' onchange="form.submit()" class="form-control">
                            <?php echo InputOptions(["", "NEW COMPLAINT", "EXECUTIVE ASSIGN", "IN PROGRESS", "COMPLETED"], IfRequested("GET", "ComplaintStatus", "", false)); ?>
                          </select>
                        </div>
                        <div class="col-md-3 form-group">
                          <label>Complaint Type</label>
                          <select name='ComplaintType' onchange="form.submit()" class="form-control">
                            <?php echo InputOptions(["", "Pay Per Call", "WARRANTY", "AMC", "CMC"], IfRequested("GET", "ComplaintType", "", false)); ?>
                          </select>
                        </div>
                        <div class="col-md-3 form-group">
                          <label>From</label>
                          <input type="date" onchange="form.submit()" value='<?php echo IfRequested("GET", "DateFrom", date('Y-m-d', strtotime("-365 days")), false); ?>' class="form-control" name="DateFrom">
                        </div>
                        <div class="col-md-3 form-group">
                          <label>To</label>
                          <input type="date" onchange="form.submit()" value='<?php echo IfRequested("GET", "DateTo", date('Y-m-d'), false); ?>' class="form-control" name="DateTo">
                        </div>
                        <div class="col-md-12 text-center mb-2">
                          <button type='submit' name='Search' class='btn btn-sm btn-success'>Apply Filters</button>
                          <a href='index.php' onclick="Databar('Filters')" class='btn btn-sm btn-default'>Hide Filters <i class='fa fa-times text-danger'></i></a>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class='row'>
                    <div class="col-md-12">
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Sno</th>
                              <th>ComplaintNo</th>
                              <th>Customer</th>
                              <th>ServiceEngineer</th>
                              <th>ComplaintType</th>
                              <th>ComplaintDate</th>
                              <th>ServiceCharge</th>
                              <th>ComplaintStatus</th>
                              <th>PayStatus</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $start = START_FROM;
                            $End = DEFAULT_RECORD_LISTING;

                            if (isset($_GET['status'])) {
                              $status = $_GET['status'];
                              $GetComplaints = FETCH_TABLE_FROM_DB("SELECT * FROM complaint_charges where complaint_charge_amount!='0' and complaint_charge_status like '$status'", true);
                            } elseif (isset($_GET['ComplaintsNo'])) {
                              $ComplaintNo = $_GET['ComplaintsNo'];
                              $ServiceEngineerName = $_GET['ServiceEngineerName'];
                              $DateFrom = $_GET['DateFrom'];
                              $DateTo = $_GET['DateTo'];
                              $ComplaintStatus = $_GET['ComplaintStatus'];
                              $ComplaintType = $_GET['ComplaintType'];
                              $UserCompanyName = $_GET['UserCompanyName'];
                              $GetComplaints = FETCH_TABLE_FROM_DB("SELECT * FROM complaint_charges, complaints, users where complaint_charge_amount!='0' and UserCompanyName like '%$UserCompanyName%' and ComplaintStatus like '%$ComplaintStatus%' and DATE(ComplaintCreatedAt)>='$DateFrom' and DATE(ComplaintCreatedAt)<='$DateTo' and complaint_charges.complaint_main_id=complaints.ComplaintsId and complaints.ComplaintAssignedTo=users.UserId and users.UserFullName like '%$ServiceEngineerName%' and complaints.ComplaintsCustomRefId like '%$ComplaintNo%' ORDER BY ComplaintsId DESC", true);
                            } else {
                              $GetComplaints = FETCH_TABLE_FROM_DB("SELECT * FROM complaint_charges where complaint_charge_amount!='0' ORDER BY complaint_charge_idc DESC LIMIT $start, $End", true);
                            }
                            if ($GetComplaints != null) {
                              $SerialNo = SERIAL_NO;
                              foreach ($GetComplaints as $Complaint) {
                                $SerialNo++;
                                $ComplaintId = $Complaint->complaint_main_id;
                            ?>
                                <tr>
                                  <td><?php echo $SerialNo; ?></td>
                                  <td>
                                    <a class='text-primary' href="<?php echo ADMIN_URL; ?>/complaints/details?id=<?php echo $Complaint->complaint_main_id; ?>">
                                      <?php echo FETCH("SELECT * FROM complaints where ComplaintsId='" . $Complaint->complaint_main_id . "'", "ComplaintsCustomRefId"); ?>
                                    </a>
                                  </td>
                                  <td>
                                    <?php
                                    $UserId = FETCH("SELECT * FROM complaints where ComplaintsId='$ComplaintId'", "ComplaintsUserId"); ?>
                                    <a class='text-black' href="<?php echo ADMIN_URL; ?>/customers/details/?uid=<?php echo SECURE($UserId, "e"); ?>">
                                      <i class="fa fa-user"></i>
                                      <?php
                                      echo FETCH("SELECT * FROM users where UserId='$UserId'", "UserFullName");
                                      echo " @ ";
                                      echo FETCH("SELECT * FROM users where UserId='$UserId'", "UserPhoneNumber") . "<br>";
                                      echo "<span classs='text-danger'><i class='fa fa-hospital text-danger'></i> " . FETCH("SELECT * FROM users where UserId='$UserId'", "UserCompanyName") . "</span>";
                                      ?>
                                    </a>
                                  </td>
                                  <td>
                                    <?php
                                    $ComplaintAssignedTo = FETCH("SELECT * FROM complaints where ComplaintsId='$ComplaintId'", "ComplaintAssignedTo"); ?>
                                    <a class='text-info' href="<?php echo ADMIN_URL; ?>/users/details/complaints.php?uid=<?php echo SECURE($ComplaintAssignedTo, "e"); ?>">
                                      <i class="fa fa-user"></i>
                                      <?php
                                      echo FETCH("SELECT * FROM users where UserId='$ComplaintAssignedTo'", "UserFullName");
                                      echo " @ ";
                                      echo FETCH("SELECT * FROM users where UserId='$ComplaintAssignedTo'", "UserPhoneNumber");
                                      ?>
                                    </a>
                                    <br>
                                    <span class="text-small small">
                                      <?php
                                      $CompProSql = "SELECT * FROM complaint_products where MainComplaintId='$ComplaintId'";
                                      $CompProSerialNo = FETCH($CompProSql, "ComplaintProductSerialNo");
                                      $ProSql1 = "SELECT * FROM product_serial_no where ProductSerialNo='$CompProSerialNo'";
                                      $ProductId = FETCH($ProSql1, "ProductMainProId");
                                      $ProductName = FETCH("SELECT * FROM products where ProductID='$ProductId'", "ProductName");
                                      echo $ProductName;
                                      echo FETCH($CompProSql, "ComplaintProductName") . " - ";
                                      echo FETCH($CompProSql, "ComplaintProductModalNo") . " (";
                                      echo FETCH($CompProSql, "ComplaintProductSerialNo") . " )"; ?>

                                    </span>
                                  </td>
                                  <td>
                                    <?php echo FETCH("SELECT * FROM complaints where ComplaintsId='$ComplaintId'", "ComplaintType"); ?>
                                  </td>
                                  <td>
                                    <?php echo DATE_FORMATE("d M, Y", FETCH("SELECT * FROM complaints where ComplaintsId='$ComplaintId'", "ComplaintCreatedAt")); ?>
                                  </td>
                                  <td>
                                    <?php echo Price($Complaint->complaint_charge_amount, "", "Rs."); ?>
                                  </td>
                                  <td>
                                    <?php echo FETCH("SELECT * FROM complaints where ComplaintsId='$ComplaintId'", "ComplaintStatus"); ?>
                                  </td>
                                  <td>
                                    <?php echo PayStatus($Complaint->complaint_charge_status); ?>
                                  </td>
                                  <td>
                                    <a href="<?php echo ADMIN_URL; ?>/complaints/details?id=<?php echo $Complaint->complaint_main_id; ?>" class='btn btn-sm btn-success'>Details</a>
                                  </td>
                                </tr>
                            <?php
                              }
                            } else {
                              NoDataTableView("No Transactions found!", "10");
                            }
                            ?>
                          </tbody>
                        </table>

                      </div>
                    </div>
                    <?php

                    echo PaginationFooter(CountRecords($GetComplaints), "index.php"); ?>
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