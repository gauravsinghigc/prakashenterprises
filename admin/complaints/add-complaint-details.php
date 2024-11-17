<?php
$Dir = "../..";
require $Dir . '/require/modules.php';
require $Dir . '/require/admin/access-control.php';
require $Dir . '/require/admin/sessionvariables.php';

//pagevariables
$PageName = "Add Product in Warranty";
$PageDescription = "Manage all customers";

if (isset($_GET['CreateWarrantyCard'])) {
  $ProductName = $_SESSION['ProductName'] = $_GET['ProductName'];
  $WarrantyProductSno = $_SESSION['WarrantyProductSno'] = $_GET['WarrantyProductSno'];
  $WarrantyProductModalNo = $_SESSION['WarrantyProductModalNo'] = $_GET['WarrantyProductModalNo'];
  $WarrantyProductMfgDate = $_SESSION['WarrantyProductMfgDate'] = $_GET['WarrantyProductMfgDate'];
  $WarrantyProductPurchasedate = $_SESSION['WarrantyProductPurchasedate'] = $_GET['WarrantyProductPurchasedate'];
  $WarrantyProductCapacity = $_SESSION['WarrantyProductCapacity'] = $_GET['WarrantyProductCapacity'];
  $WarrantyProductMonthWarranty = $_SESSION['WarrantyProductMonthWarranty'] = $_GET['WarrantyProductMonthWarranty'];
  $WarrantyProductLife = $_SESSION['WarrantyProductLife'] = $_GET['WarrantyProductLife'];
  $WarrantyExpireDate = $_SESSION['WarrantyExpireDate'] = $_GET['WarrantyExpireDate'];
  $WarrantyStatus = $_SESSION['WarrantyStatus'] = $_GET['WarrantyStatus'];
} else {
  $ProductName = $_SESSION['ProductName'];
  $WarrantyProductSno = $_SESSION['WarrantyProductSno'];
  $WarrantyProductModalNo = $_SESSION['WarrantyProductModalNo'];
  $WarrantyProductMfgDate = $_SESSION['WarrantyProductMfgDate'];
  $WarrantyProductPurchasedate = $_SESSION['WarrantyProductPurchasedate'];
  $WarrantyProductCapacity = $_SESSION['WarrantyProductCapacity'];
  $WarrantyProductMonthWarranty = $_SESSION['WarrantyProductMonthWarranty'];
  $WarrantyProductLife = $_SESSION['WarrantyProductLife'];
  $WarrantyExpireDate = $_SESSION['WarrantyExpireDate'];
  $WarrantyStatus = $_SESSION['WarrantyStatus'];
}
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
      document.getElementById("services").classList.add("active");
      document.getElementById("add_services").classList.add("active");
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
                    <div class="col-md-5">
                      <h5 class="app-heading">Customer Details</h5>
                      <?php
                      if (isset($_GET['customer_id'])) {
                        $customer_id = SECURE($_GET['customer_id'], "d");
                        $_SESSION['SaleCustomerId'] = $customer_id;
                      } else {
                        $customer_id = $_SESSION['SaleCustomerId'];
                      }
                      $FetchCustomers = FETCH_TABLE_FROM_DB("SELECT * FROM users where UserId='$customer_id'", true);
                      if ($FetchCustomers == null) {
                        NoData("No Customer Found!");
                      } else { ?>
                        <?php
                        foreach ($FetchCustomers as $Customers) {
                        ?>
                          <p class="shadow-sm p-2 rounded-1 pb-20px">
                            <span class="fs-16px bold text-grey"><?php echo $Customers->UserSalutation; ?></span>
                            <span class="fs-16px bold"><?php echo $Customers->UserFullName; ?></span>
                            <span class="pull-right text-grey italic">CUSTOMERID000<?php echo $Customers->UserId; ?></span><br>
                            <span class="fs-13px">
                              <span><b>Phone Number : </b><?php echo $Customers->UserPhoneNumber; ?></span><br>
                              <span><b>Email-ID : </b><?php echo $Customers->UserEmailId; ?></span><br>
                              <span><b>Hospital Name : </b><?php echo $Customers->UserCompanyName; ?></span><br>
                              <span><b class="text-grey">Address : </b>
                                <?php
                                $UserId = $Customers->UserId;
                                $CheckAddress = CHECK("SELECT * FROM user_addresses where UserAddressUserId='$UserId'");
                                if ($CheckAddress == null) {
                                  echo "No Address found!";
                                } else {
                                  $FetchAddress = FETCH_TABLE_FROM_DB("SELECT * FROM user_addresses where UserAddressUserId='$UserId'", true);
                                  if ($FetchAddress != null) {
                                    foreach ($FetchAddress as $Address) {
                                      $UserAddressId = $Address->UserAddressId;
                                      echo "<br><b>" . SECURE(FETCH("SELECT * FROM user_addresses where UserAddressId='$UserAddressId'", "UserAddressType"), "d") . "</b> - ";
                                      echo SECURE(FETCH("SELECT * FROM user_addresses where UserAddressId='$UserAddressId'", "UserStreetAddress"), "d");
                                      echo SECURE(FETCH("SELECT * FROM user_addresses where UserAddressId='$UserAddressId'", "UserLocality"), "d");
                                      echo SECURE(FETCH("SELECT * FROM user_addresses where UserAddressId='$UserAddressId'", "UserCity"), "d");
                                      echo SECURE(FETCH("SELECT * FROM user_addresses where UserAddressId='$UserAddressId'", "UserState"), "d");
                                      echo SECURE(FETCH("SELECT * FROM user_addresses where UserAddressId='$UserAddressId'", "UserCountry"), "d");
                                      echo "-";
                                      echo SECURE(FETCH("SELECT * FROM user_addresses where UserAddressId='$UserAddressId'", "UserPincode"), "d");
                                      echo SECURE(FETCH("SELECT * FROM user_addresses where UserAddressId='$UserAddressId'", "UserAddressContactPerson"), "d");
                                    }
                                  }
                                }
                                ?>
                              </span>
                            </span><br><br>
                            <a href="add-complaint.php" class="btn btn-sm btn-success"><i class="fa fa-angle-left"></i> Change Customer</a>
                          </p>
                      <?php
                        }
                      }
                      ?>
                      <h5 class="app-heading">Product Details</h5>
                      <table class="table table-striped">
                        <tr>
                          <th>Product name</th>
                          <td><?php echo $ProductName; ?></td>
                        </tr>
                        <tr>
                          <th>Serial No</th>
                          <td><?php echo $WarrantyProductSno; ?></td>
                        </tr>
                        <tr>
                          <th>Modal No</th>
                          <td><?php echo $WarrantyProductModalNo; ?></td>
                        </tr>
                        <tr>
                          <th>Speciality</th>
                          <td><?php echo $WarrantyProductCapacity; ?></td>
                        </tr>
                        <tr>
                          <th>MFG DATE</th>
                          <td><?php echo DATE_FORMATE("d M, Y", $WarrantyProductMfgDate); ?></td>
                        </tr>
                        <tr>
                          <th>Purchase date</th>
                          <td><?php echo DATE_FORMATE("d M, Y", $WarrantyProductPurchasedate); ?></td>
                        </tr>
                        <tr>
                          <th>Life (in Years)</th>
                          <td><?php echo $WarrantyProductLife; ?> Years </td>
                        </tr>
                        <tr>
                          <th>Warranty (in Months)</th>
                          <td><?php echo $WarrantyProductMonthWarranty; ?> Months</td>
                        </tr>
                        <tr>
                          <th>Expire Date</th>
                          <td><?php echo DATE_FORMATE("d M, Y", $WarrantyExpireDate); ?></td>
                        </tr>
                        <tr>
                          <th>Warranty Status</th>
                          <td>
                            <?php echo $WarrantyStatus; ?>
                          </td>
                        </tr>
                      </table>
                    </div>
                    <div class="col-md-7">
                      <h5 class="app-heading">Register New Complaint</h5>
                      <form action="<?php echo CONTROLLER("ComplaintController"); ?>" method="POST">
                        <?php
                        FormPrimaryInputs(true, [
                          "ComplaintsUserId" => $_SESSION['SaleCustomerId'],
                        ]) ?>
                        <div class="row">
                          <div class="col-md-3 form-group">
                            <label>Complaint No</label>
                          </div>
                          <div class="col-md-9 form-group">
                            <input type="text" class="form-control form-control-sm" name="ComplaintsCustomRefId" readonly value="<?php echo COMPLAINT_NO; ?>">
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 form-group">
                            <label>Compaint Status</label>
                          </div>
                          <div class="col-md-9 form-group">
                            <select name="ComplaintStatus" class="form-control form-control-sm">
                              <?php InputOptions(COMPLAINT_STATUS, "NEW COMPLAINT"); ?>
                            </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 form-group">
                            <label>Customer Name</label>
                          </div>
                          <div class="col-md-9 form-group">
                            <input type="text" class="form-control form-control-sm" value="<?php echo $Customers->UserFullName; ?>" name="ComplaintsName" placeholder="Full Name" required>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 form-group">
                            <label>Phone Number</label>
                          </div>
                          <div class="col-md-9 form-group">
                            <input type="text" class="form-control form-control-sm" value="<?php echo $Customers->UserPhoneNumber; ?>" name="ComplaintPhoneNumber" placeholder="+91" required>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 form-group">
                            <label>Alt Phone Number</label>
                          </div>
                          <div class="col-md-9 form-group">
                            <input type="text" class="form-control form-control-sm" name="ComplaintAltPhoneNumber" placeholder="+91">
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 form-group">
                            <label>Complaint Type</label>
                          </div>
                          <div class="col-md-9 form-group">
                            <select name="ComplaintType" class="form-control form-control-sm" required>
                              <?php echo InputOptions(["Select Complaint Type", "Pay Per Call", "WARRANTY", "AMC", "CMC", "NO Service"], "ComplaintType"); ?>
                            </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-md-3">
                            <label>Priority level</label>
                          </div>
                          <div class="col-md-9 form-group">
                            <select name="ComplaintPriorityLevel" class="form-control form-control-sm">
                              <?php InputOptions(["1-High", "2-Medium", "3-Low"], "2-Medium"); ?>
                            </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 form-group">
                            <label>Issue Description</label>
                          </div>
                          <div class="col-md-9 form-group">
                            <textarea class="form-control form-control-sm" name="ComplaintIssueDescriptions" rows="3" required></textarea>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 form-group">
                            <label>Complaint Address</label>
                          </div>
                          <div class="col-md-9 form-group">
                            <?php
                            $UserId = $Customers->UserId;
                            $CheckAddress = CHECK("SELECT * FROM user_addresses where UserAddressUserId='$UserId'");
                            if ($CheckAddress == null) {
                              echo "No Address found!";
                            } else {
                              $AddressCompleted = "";
                              $FetchAddress = FETCH_TABLE_FROM_DB("SELECT * FROM user_addresses where UserAddressUserId='$UserId'", true);
                              if ($FetchAddress != null) {
                                foreach ($FetchAddress as $Address) {
                                  $UserAddressId = $Address->UserAddressId;
                                  $AddressCompleted .= SECURE(FETCH("SELECT * FROM user_addresses where UserAddressId='$UserAddressId'", "UserAddressType"), "d") . " ";
                                  $AddressCompleted .= SECURE(FETCH("SELECT * FROM user_addresses where UserAddressId='$UserAddressId'", "UserStreetAddress"), "d") . " ";
                                  $AddressCompleted .= SECURE(FETCH("SELECT * FROM user_addresses where UserAddressId='$UserAddressId'", "UserLocality"), "d") . " ";
                                  $AddressCompleted .= SECURE(FETCH("SELECT * FROM user_addresses where UserAddressId='$UserAddressId'", "UserCity"), "d") . " ";
                                  $AddressCompleted .= SECURE(FETCH("SELECT * FROM user_addresses where UserAddressId='$UserAddressId'", "UserState"), "d") . " ";
                                  $AddressCompleted .= SECURE(FETCH("SELECT * FROM user_addresses where UserAddressId='$UserAddressId'", "UserCountry"), "d") . " ";
                                  $AddressCompleted .= "-";
                                  $AddressCompleted .= SECURE(FETCH("SELECT * FROM user_addresses where UserAddressId='$UserAddressId'", "UserPincode"), "d") . " ";
                                  $AddressCompleted .= SECURE(FETCH("SELECT * FROM user_addresses where UserAddressId='$UserAddressId'", "UserAddressContactPerson"), "d") . " ";
                                }
                              }
                            }
                            ?>
                            <textarea class="form-control form-control-sm" name="ComplaintAddress" rows="3" required><?php echo $AddressCompleted; ?></textarea>
                          </div>
                          <div class="col-md-12">
                            <div class="row">
                              <div class="col-md-12">
                                <h4 class='app-sub-heading'>Service Charges</h4>
                              </div>
                              <div class="form-group col-md-6">
                                <label>Charge Name</label>
                                <input type="text" name="complaint_charge_name" value="" class="form-control" placeholder="Charge Name">
                              </div>
                              <div class="form-group col-md-6">
                                <label>Applicable Tax (in %)</label>
                                <select name="complaint_charge_tax_amount" class="form-control" required>
                                  <?php InputOptions(["0", "5", "7", "10", "12", "15", "18", "20", "25", "30"], 0); ?>
                                </select>
                              </div>
                              <div class="form-group col-md-6">
                                <label>Charge Amount (in Rs.)</label>
                                <input type="text" name="complaint_charge_amount" value="" class="form-control" placeholder="Charge Amount">
                              </div>

                              <div class="form-group col-md-6">
                                <label>Payment Status</label>
                                <select name="complaint_charge_status" class="form-control" <?php echo $ChargeStatus; ?>>
                                  <?php echo InputOptions(["UN-PAID", "PAID"], "UN-PAID"); ?>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12 form-group text-right">
                            <button type="submit" name="SaveComplaints" class="btn btn-lg btn-success">Create Complaint <i class="fa fa-angle-right"></i></button>
                          </div>
                        </div>
                      </form>
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
    include $Dir . "/include/admin/footer.php"; ?>
  </div>

  <?php include $Dir . "/include/admin/footer_files.php"; ?>

</body>

</html>