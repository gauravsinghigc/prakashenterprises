<?php
$Dir = "../../../..";
require $Dir . '/require/modules.php';
require $Dir . '/require/admin/access-control.php';
require $Dir . '/require/admin/sessionvariables.php';


//pagevariables
$PageName = "Edit Deal Requirements";
$PageDescription = "Manage all leads";

if (isset($_GET['dealsid'])) {
      $_SESSION['REQ_LeadsId'] = SECURE($_GET['dealsid'], "d");
      $REQ_LeadsId = $_SESSION['REQ_LeadsId'];
} else {
      $REQ_LeadsId = $_SESSION['REQ_LeadsId'];
}

$PageSqls = "SELECT * FROM leads where LeadsId='$REQ_LeadsId'";

?>
<!DOCTYPE html>
<html lang="en">

<head>
      <meta charset="utf-8" />
      <title><?php echo $PageName; ?> | <?php echo APP_NAME; ?></title>
      <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
      <meta name="keywords" content="<?php echo APP_NAME; ?>">
      <meta name="description" content="<?php echo SECURE(SHORT_DESCRIPTION, "d"); ?>">
      <?php include $Dir . "/include/admin/header_files.php"; ?>
      <script type="text/javascript">
            function SidebarActive() {
                  document.getElementById("leads").classList.add("active");
                  document.getElementById("all_leads").classList.add("active");
            }
            window.onload = SidebarActive;
      </script>
      <style>
            .form-group {
                  margin-bottom: 0px !important;
            }
      </style>
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
                                                      <h4><?php echo $PageName; ?></h4>
                                                      <form action="<?php echo CONTROLLER; ?>/LeadsController.php" method="POST">
                                                            <?php FormPrimaryInputs(true); ?>
                                                            <div class="row">
                                                                  <div class="col-md-12">
                                                                        <h4 class="app-heading">
                                                                              <?php echo GET_DATA("leads", "LeadPersonFullname", "LeadsId='$REQ_LeadsId'"); ?>
                                                                              : <?php echo LEADID($REQ_LeadsId); ?></h4>
                                                                        <div class="row mb-5px pt-3">
                                                                              <div class="col-md-6 mb-3">

                                                                                    <h5 class="bg-info p-2 rounded-1 text-white">
                                                                                          Add New Requirements</h5>
                                                                                    <?php $Requirement = FETCH_TABLE_FROM_DB("SELECT * FROM projects", true);
                                                                                    foreach ($Requirement as $List) {
                                                                                          $ProjectType = FETCH("SELECT * FROM config_values where ConfigValueId='" . $List->ProjectTypeId . "'", "ConfigValueDetails");
                                                                                          $LeadRequirementDetails = $List->ProjectsId;
                                                                                          $LeadMainId = $REQ_LeadsId;
                                                                                          $FetchRequiements = FETCH("SELECT * FROM lead_requirements where LeadMainId='$LeadMainId' and LeadRequirementDetails='$LeadRequirementDetails'", "LeadRequirementDetails");
                                                                                          if ($FetchRequiements == $LeadRequirementDetails) {
                                                                                                $Checked = "checked";
                                                                                          } else {
                                                                                                $Checked = "";
                                                                                          }
                                                                                          echo "
                  <div class='form-group col-md-12'>
                  <div class='form-check form-check-inline'>
                  <input class='form-check-input checkbox-list mt-0' type='checkbox' name='LeadRequirementDetails[]' value='" . $List->ProjectsId . "' $Checked>
                  <h6 class='form-check-label text-grey mb-0'>" . $List->ProjectName . " - <i class='text-grey'>$ProjectType</i></h6>
                  </div>
                  </div>";
                                                                                    } ?>
                                                                                    <a href="index.php" class="btn btn-sm btn-default mt-4" value="<?php echo SECURE($REQ_LeadsId, "e"); ?>"><i class="fa fa-angle-double-left"></i>
                                                                                          Back To Details</a>

                                                                                    <button class="btn btn-sm btn-success mt-4" name="UpdateLeadRequirements" value="<?php echo SECURE($REQ_LeadsId, "e"); ?>" TYPE="submit">Save New
                                                                                          Requirements</button>
                                                                                    <br>
                                                                              </div>
                                                                              <div class="col-md-6">
                                                                                    <h5 class="bg-info p-2 rounded-1 text-white">
                                                                                          Remove Old Requirements</h5>
                                                                                    <ul class="pl-0">
                                                                                          <?php
                                                                                          $FetchRequiements = FETCH_TABLE_FROM_DB("SELECT * FROM lead_requirements where LeadMainId='$REQ_LeadsId'", true);
                                                                                          if ($FetchRequiements != null) {

                                                                                                foreach ($FetchRequiements as $OldReq) {
                                                                                                      echo "
                 <li class='flex-s-b'>
                  <h6 class='form-check-label text-grey mb-0'><i class='fa fa-check-circle text-success'></i> " . FETCH("SELECT * FROM projects where ProjectsId='" . $OldReq->LeadRequirementDetails . "'", "ProjectName") . "</h6>
                  ";

                                                                                                      CONFIRM_DELETE_POPUP(
                                                                                                            "LeadReq",
                                                                                                            [
                                                                                                                  "delete_lead_requirements" => true,
                                                                                                                  "control_id" => $OldReq->LeadRequirementID
                                                                                                            ],
                                                                                                            "leads",
                                                                                                            "<i class='fa fa-trash'></i> Remove",
                                                                                                            "text-link text-danger"
                                                                                                      );

                                                                                                      echo "</li>";
                                                                                                }
                                                                                          } else {
                                                                                                echo "<span class='inline-list'>
          <h1><i class='fa fa-warning fs-1 text-warning mt-3'></i></h1>
          <h4>No Requirement Found!</h4>
          <p>Please add some requirements, then it will be display here...</p>
          </span>";
                                                                                          } ?>
                                                                                    </ul>
                                                                              </div>
                                                                        </div>


                                                                  </div>
                                                            </div>
                                                      </form>
                                                </div>
                                          </div>
                                          <script>
                                                function CheckCallStatus() {
                                                      var call_status = $("#call_status").val();
                                                      if (call_status == "FollowUp") {
                                                            $("#call_reminder").removeClass("hidden");
                                                      } else {
                                                            $("#call_reminder").addClass("hidden");
                                                      }
                                                }
                                          </script>
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