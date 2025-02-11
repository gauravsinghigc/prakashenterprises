<?php
$Dir = "../../..";
require $Dir . '/require/modules.php';
require $Dir . '/require/admin/access-control.php';
require $Dir . '/require/admin/sessionvariables.php';

//pagevariables
$PageName = "All Customers";
$PageDescription = "Manage all customers";

if (isset($_GET['uid'])) {
  $_SESSION['REQ_UserId'] = SECURE($_GET['uid'], "d");
  $REQ_UserId = $_SESSION['REQ_UserId'];
} else {
  $REQ_UserId = $_SESSION['REQ_UserId'];
}

$PageSqls = "SELECT * FROM users where UserId='$REQ_UserId'";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title><?php echo FETCH($PageSqls, "UserSalutation"); ?> <?php echo FETCH($PageSqls, "UserFullName"); ?> | <?php echo APP_NAME; ?></title>
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
                      <div class="user-dash">
                        <div class="user-img">
                          <img src="<?php echo STORAGE_URL; ?>/users/img/profile/<?php echo FETCH($PageSqls, "UserProfileImage"); ?>" alt="<?php echo FETCH($PageSqls, "UserFullName"); ?>" title="<?php echo FETCH($PageSqls, "UserFullName"); ?>" />
                        </div>
                        <div class="user-details">
                          <h4 class="user-name mb-0"><?php echo FETCH($PageSqls, "UserSalutation"); ?> <?php echo FETCH($PageSqls, "UserFullName"); ?></h4>
                        </div>
                        <div>
                          <h5 class="user-name mb-0"><i class='fa fa-hospital text-danger fs-15'></i> <?php echo FETCH($PageSqls, "UserCompanyName"); ?></h4>
                        </div>
                        <p class="mt-0 mb-2">
                          <a href="&?send_mail_to=<?php echo FETCH($PageSqls, "UserEmailId"); ?>"><i class="fa fa-envelope"></i> <?php echo FETCH($PageSqls, "UserEmailId"); ?></a><br>
                          <a href="tel:<?php echo FETCH($PageSqls, "UserPhoneNumber"); ?>"><i class="fa fa-phone-square"></i> <?php echo FETCH($PageSqls, "UserPhoneNumber"); ?></a><br>
                        </p>
                        <div class="user-contact-info">
                          <?php
                          $GetAddress = FETCH_TABLE_FROM_DB("SELECT * FROM user_addresses WHERE UserAddressUserId='$REQ_UserId'", true);
                          if ($GetAddress != null) {
                            foreach ($GetAddress as $Address) { ?>
                              <p class="flex-s-b mt-0 mb-0">
                                <span class="info-details">
                                  <i class="fa fa-map-location"></i> <span>
                                    <?php echo SECURE($Address->UserStreetAddress, "d"); ?>
                                    <?php echo SECURE($Address->UserLocality, "d"); ?>
                                    <?php echo SECURE($Address->UserCity, "d"); ?>
                                    <?php echo SECURE($Address->UserState, "d"); ?>
                                    <?php echo SECURE($Address->UserCountry, "d"); ?>
                                    <?php echo SECURE($Address->UserPincode, "d"); ?><br>
                                    <?php echo SECURE($Address->UserAddressContactPerson, "d"); ?><br>
                                    <?php echo SECURE($Address->UserAddressNotes, "d"); ?><br>
                                    <a href=" <?php echo SECURE($Address->UserAddressMapUrl, "d"); ?>" class="btn btn-sm btn-success">View Location On Map</a>
                                  </span>
                                </span>
                              </p>
                          <?php }
                          } ?>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-8">
                      <a href="index.php" class="btn btn-sm btn-default">All Complaints</a>
                      <a href="replacement.php" class="btn btn-sm btn-default">All Replacement</a>
                      <a href="warranty.php" class="btn btn-sm btn-default">All Warranty Cards</a>
                      <h4 class="app-heading">All Complaints</h4>
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <tr>
                            <th>Sno</th>
                            <th>ComplaintNo</th>
                            <th>SerialNo</th>
                            <th>Createdate</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                          <?php
                          $Complaints = FETCH_TABLE_FROM_DB("SELECT * FROM complaints where ComplaintsUserId='$REQ_UserId' ORDER BY DATE(ComplaintCreatedAt) DESC", true);
                          if ($Complaints == null) {
                            NoDataTableView("No Complaints Found!", 7);
                          } else {
                            $sNO = 0;
                            foreach ($Complaints as $Complaint) {
                              $sNO++; ?>
                              <tr>
                                <td><?php echo $sNO; ?></td>
                                <td>
                                  <a target="_blank" href="../../complaints/details/?id=<?php echo $Complaint->ComplaintsId; ?>">
                                    <?php echo $Complaint->ComplaintsCustomRefId; ?>
                                  </a>
                                </td>
                                <td>
                                  <?php echo $Complaint->ComplaintProductId; ?>
                                </td>
                                <td>
                                  <?php echo DATE_FORMATE("d M, Y", $Complaint->ComplaintCreatedAt); ?>
                                </td>
                                <td>
                                  <?php echo $Complaint->ComplaintStatus; ?></span>
                                </td>
                                <td>
                                  <a target="_blank" href="../../complaints/details/?id=php echo $Complaint->ComplaintsId; ?>" class="btn btn-sm btn-success">
                                    View Details
                                  </a>
                                </td>
                              </tr>
                          <?php }
                          } ?>
                        </table>
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
    include $Dir . "/include/admin/footer.php"; ?>
  </div>

  <?php include $Dir . "/include/admin/footer_files.php"; ?>

</body>

</html>