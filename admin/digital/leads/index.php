<?php
$Dir = "../../..";
require $Dir . '/require/modules.php';
require $Dir . '/require/admin/access-control.php';
require $Dir . '/require/admin/sessionvariables.php';

//pagevariables
$PageName = "All Leads";
$PageDescription = "Manage all Leads";
$btntext = "Add New Leads";
$DomainExpireInCurrentMonth = date("Y-m-d", strtotime("+1 month"));

include "../sections/pageHeader.php";

if (isset($_GET['type'])) {
  $type = $_GET['type'];
  $from = $_GET['from'];
  $to = $_GET['to'];
  $by = $_GET['by'];
  $level = $_GET['level'];
  $LeadPersonSource = $_GET['LeadPersonSource'];
} else {
  $type = "";
  $from = date("Y-m-d");
  $to = date("Y-m-d");
  $by = LOGIN_UserId;
  $level = "";
  $LeadPersonSource = "";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title><?php echo $PageName; ?> | <?php echo APP_NAME; ?></title>
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
  <meta name="keywords" content="<?php echo APP_NAME; ?>">
  <meta name="description" content="<?php echo SECURE(SHORT_DESCRIPTION, "d"); ?>">
  <?php include $Dir . "/include/admin/header_files.php"; ?> <script type="text/javascript">
    function SidebarActive() {
      document.getElementById("leads").classList.add("active");
      document.getElementById("all_leads").classList.add("active");
    }
    window.onload = SidebarActive;
  </script>
  <style>
    .card {
      box-shadow: 0px 0px 1px black !important;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini sidebar-collapse">
  <div class="wrapper"> <?php include $Dir . "/include/admin/loader.php"; ?>
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
                    <div class="col-sm-5 col-12">
                      <?php if (isset($_GET['type'])) {
                        $ListHeading = "All " . ucfirst(str_replace("_", " ", $_GET['type']))  . "";
                      } elseif (isset($_GET['view'])) {
                        $ListHeading = "All " . $_GET['view'];
                      } elseif (isset($_GET['sub_status'])) {
                        $ListHeading = "All " . $_GET['sub_status'];
                      } else {
                        $ListHeading = "All Leads";
                      } ?>
                      <h2 class="app-heading"><?php echo $ListHeading; ?> <small class="text-grey"> </small></h2>
                    </div>
                    <div class="col-sm-7 col-12 text-right">
                      <a href="add.php" class="btn btn-sm btn-dark m-1"><i class="fa fa-plus"></i> New Lead</a>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 col-12">
                      <form class="row">
                        <input type="text" hidden id="leascurrentstatus" name="LeadPersonSubStatus" value="">
                        <input type="text" hidden id="leasstatus" name="LeadPersonStatus" value="">
                        <div class="col-md-2 col-6 flex-s-b">
                          <select class="form-control form-control-sm " name="LeadFollowStatus" id="statustype" onchange="CallStatusFunction()">
                            <option value="">Select Lead Status</option>
                            <?php
                            $FetchCallStatus = FETCH_TABLE_FROM_DB("SELECT * FROM configs, config_values where configs.ConfigsId=config_values.ConfigValueGroupId and configs.ConfigGroupName='CALL_STATUS' ORDER BY ConfigValueId DESC", true);
                            if ($FetchCallStatus != null) {
                              foreach ($FetchCallStatus as $CallStatus) { ?> <option value="<?php echo $CallStatus->ConfigValueId; ?>"><?php echo $CallStatus->ConfigValueDetails; ?>
                                </option>
                            <?php
                              }
                            } ?>
                          </select>
                        </div>
                        <div class="col-md-2 col-6">
                          <?php
                          $FetchCallStatus = FETCH_TABLE_FROM_DB(CONFIG_DATA_SQL("CALL_STATUS"), true);
                          if ($FetchCallStatus != null) {
                            foreach ($FetchCallStatus as $Status) {
                              if ($Status->ConfigValueId == "50") {
                                $display = "block";
                              } else {
                                $display = "none";
                              } ?>
                              <select onchange="GetValue_<?php echo $Status->ConfigValueId; ?>()" class="form-control form-control-sm " id="view_<?php echo $Status->ConfigValueId; ?>" style="display:<?php echo $display; ?>;">
                                <option value="0">Select Call Status</option>
                                <?php
                                $FetchCallStatus = FETCH_TABLE_FROM_DB("SELECT * FROM configs, config_values where config_values.ConfigReferenceId='" . $Status->ConfigValueId . "' and configs.ConfigsId=config_values.ConfigValueGroupId and configs.ConfigGroupName='CALL_STATUS_SUB_FIELDS'", true);
                                if ($FetchCallStatus != null) {
                                  foreach ($FetchCallStatus as $CallStatus) { ?> <option value="<?php echo $CallStatus->ConfigValueDetails; ?>"> <?php echo $CallStatus->ConfigValueDetails; ?>
                                    </option>
                                <?php
                                  }
                                } ?>
                              </select>
                              <script>
                                function GetValue_<?php echo $Status->ConfigValueId; ?>() {
                                  var leascurrentstatus = document.getElementById("leascurrentstatus")
                                  leascurrentstatus.value = document.getElementById("view_<?php echo $Status->ConfigValueId; ?>").value;
                                }
                              </script>
                          <?php
                            }
                          } ?>
                        </div>
                        <div class="col-md-2 col-6">
                          <input type="text" name="LeadPersonFullname" list="LeadPersonFullname" class="form-control form-control-sm " placeholder="Enter Person name">
                        </div>
                        <div class="col-md-2 col-6">
                          <input type="text" name="LeadPersonPhoneNumber" list="LeadPersonPhoneNumber" class="form-control form-control-sm " placeholder="Enter Phone number">
                        </div>
                        <div class="col-md-2 col-6">
                          <input type="text" value="" name="LeadPriorityLevel" list="LeadPriorityLevel" class="form-control form-control-sm " placeholder="Priority Level">
                        </div>
                        <div class="col-md-2 col-6">
                          <input type="text" value="" name="LeadPersonSource" list="LeadPersonSource" class="form-control form-control-sm " placeholder="Lead Source"> <?php SUGGEST("leads", "LeadPersonSource", "ASC"); ?>
                        </div>
                        <div class="col-md-3 col-6">
                          <input type="text" value="" name="LeadPersonManagedBy" list="LeadPersonManagedBy" class="form-control form-control-sm " placeholder="Lead Managed By">
                        </div>
                        <div class="col-md-2 col-6">
                          <button type="submit" name="search_true" class="btn btn-sm btn-primary">Apply Filters</button>
                        </div>
                        <div class="col-md-2 col-6 flex-s-b mb-2"> <?php if (isset($_GET['type'])) { ?> <a href="index.php" class="btn btn-sm btn-danger">Clear Filters <i class="fa fa-times"></i></a> <?php } ?> </div>
                      </form>
                    </div>
                  </div> <?php if (isset($_GET['search_true'])) { ?> <div class="row">
                      <div class="col-md-12 mb-2 shadow-sm rounde-2 p-2">
                        <h6 class="mb-2"><i class="fa fa-filter text-warning"></i> Filter Applied</h6>
                        <p class="fs-11">
                          <span>
                            <span class="text-grey">Lead Status :</span>
                            <span class="bold"><?php echo IfRequested("GET", "LeadPersonStatus", "All", false);  ?></span>
                          </span>
                          <span>
                            <span class="text-grey">Sub Status :</span>
                            <span class="bold"><?php echo IfRequested("GET", "LeadPersonSubStatus", "All", false);  ?></span>
                          </span>
                          <span>
                            <span class="text-grey">Person Name :</span>
                            <span class="bold"><?php echo IfRequested("GET", "LeadPersonFullname", "All", false);  ?></span>
                          </span>
                          <span>
                            <span class="text-grey">Phone Number :</span>
                            <span class="bold"><?php echo IfRequested("GET", "LeadPersonPhoneNumber", "All", false);  ?></span>
                          </span>
                          <span>
                            <span class='text-grey'>Priority level :</span>
                            <span class='bold'><?php echo IfRequested("GET", "LeadPriorityLevel", "All", false);  ?></span>
                          </span>
                          <span>
                            <span class='text-grey'>Lead Source : </span>
                            <span class='bold'><?php echo IfRequested("GET", "LeadPersonSource", "All", false);  ?></span>
                          </span>
                          <span>
                            <span class='text-grey'>Managed By : </span>
                            <span class='bold'>
                              <?php $UserResponseId = IfRequested("GET", "LeadPersonManagedBy", "All", false);
                              echo FETCH("SELECT * FROM users where UserId='$UserResponseId'", "UserFullName") . " @ " . FETCH("SELECT * FROM users where UserId='$UserResponseId'", "UserPhoneNumber");  ?>
                            </span>
                          </span>
                        </p>
                        <a href="index.php" class="btn btn-xs btn-danger fs-11 pull-right" style="margin-top:-5.3em !important;">Clear Filter <i class="fa fa-times"></i></a>
                      </div>
                    </div>
                  <?php } ?>
                  <div class="row">
                    <?php
                    $listcounts = 15;

                    // Get current page number
                    if (isset($_GET["view_page"])) {
                      $page = $_GET["view_page"];
                    } else {
                      $page = 1;
                    }
                    $start = ($page - 1) * $listcounts;
                    $next_page = ($page + 1);
                    $previous_page = ($page - 1);
                    $NetPages = round(($TotalItems / $listcounts) + 0.5);
                    ?>
                    <?php
                    if (isset($_GET['view'])) {
                      $view = $_GET['view'];
                      if (LOGIN_UserType == "Admin") {
                        $GetLeads = FETCH_TABLE_FROM_DB("SELECT LeadPersonCreatedBy, LeadPersonPhoneNumber, LeadPersonEmailId, LeadPersonStatus, LeadPersonSubStatus, LeadSalutations, LeadPersonFullname, LeadPersonManagedBy, LeadPersonSource, LeadPriorityLevel, LeadPersonCreatedAt, LeadsId  FROM leads WHERE LeadPersonStatus LIKE '%$view%' GROUP BY LeadsId ORDER by LeadsId DESC limit $start, $listcounts", true);
                      } else {
                        $LOGIN_UserId = LOGIN_UserId;
                        $GetLeads = FETCH_TABLE_FROM_DB("SELECT LeadPersonCreatedBy, LeadPersonPhoneNumber, LeadPersonEmailId, LeadPersonStatus, LeadPersonSubStatus, LeadSalutations, LeadPersonFullname, LeadPersonManagedBy, LeadPersonSource, LeadPriorityLevel, LeadPersonCreatedAt, LeadsId  FROM leads where LeadPersonStatus LIKE '%$view%' and LeadPersonManagedBy='$LOGIN_UserId' GROUP BY LeadsId ORDER by LeadsId DESC limit $start, $listcounts", true);
                      }
                    } elseif (isset($_GET['search_true'])) {
                      $LeadPersonSubStatus = $_GET['LeadPersonSubStatus'];
                      $LeadPersonStatus = $_GET['LeadPersonStatus'];
                      $LeadPersonFullname = $_GET['LeadPersonFullname'];
                      $LeadPersonPhoneNumber = $_GET['LeadPersonPhoneNumber'];
                      $LeadPersonSource = $_GET['LeadPersonSource'];
                      $LeadPersonManagedBy = $_GET['LeadPersonManagedBy'];
                      $LeadPriorityLevel = $_GET['LeadPriorityLevel'];
                      $LeadPersonCreatedAtFrom = $_GET['LeadPersonCreatedAtFrom'];
                      $LeadPersonCreatedAtTo = $_GET['LeadPersonCreatedAtTo'];

                      if ($LeadPersonManagedBy == null) {
                        $Managed = "LeadPersonManagedBy like '%$LeadPersonManagedBy%' and";
                      } else {
                        $Managed = "LeadPersonManagedBy='$LeadPersonManagedBy' and";
                      }
                      if (LOGIN_UserType == "Admin") {
                        $GetLeads = FETCH_TABLE_FROM_DB("SELECT LeadPersonCreatedBy, LeadPersonSubStatus, LeadPersonEmailId, LeadPersonPhoneNumber, LeadPersonStatus, LeadSalutations, LeadPersonFullname, LeadPersonManagedBy, LeadPersonSource, LeadPriorityLevel, LeadPersonCreatedAt, LeadsId  FROM leads WHERE DATE(LeadPersonCreatedAt)>='$LeadPersonCreatedAtFrom' and DATE(LeadPersonCreatedAt)<='$LeadPersonCreatedAtTo' and LeadPriorityLevel like '%$LeadPriorityLevel%' and $Managed LeadPersonSource like '%$LeadPersonSource%' and LeadPersonPhoneNumber like '%$LeadPersonPhoneNumber%' and LeadPersonFullname like '%$LeadPersonFullname%' and LeadPersonSubStatus like '%$LeadPersonSubStatus%' and LeadPersonStatus LIKE '%$LeadPersonStatus%' GROUP BY LeadsId ORDER by LeadsId DESC limit $start, $listcounts", true);
                      } else {
                        $LOGIN_UserId = LOGIN_UserId;
                        $GetLeads = FETCH_TABLE_FROM_DB("SELECT LeadPersonCreatedBy, LeadPersonSubStatus, LeadPersonEmailId, LeadPersonPhoneNumber, LeadPersonStatus, LeadSalutations, LeadPersonFullname, LeadPersonManagedBy, LeadPersonSource, LeadPriorityLevel, LeadPersonCreatedAt, LeadsId  FROM leads where DATE(LeadPersonCreatedAt)>='$LeadPersonCreatedAtFrom' and DATE(LeadPersonCreatedAt)<='$LeadPersonCreatedAtTo' and LeadPriorityLevel like '%$LeadPriorityLevel%' and $Managed LeadPersonSource like '%$LeadPersonSource%' and LeadPersonPhoneNumber like '%$LeadPersonPhoneNumber%' and LeadPersonFullname like '%$LeadPersonFullname%' and LeadPersonSubStatus like '%$LeadPersonSubStatus%' and LeadPersonStatus LIKE '%$LeadPersonStatus%' and LeadPersonManagedBy='$LOGIN_UserId' GROUP BY LeadsId ORDER by LeadsId DESC limit $start, $listcounts", true);
                      }
                    } elseif (isset($_GET['sub_status'])) {
                      $sub_status = $_GET['sub_status'];
                      if (LOGIN_UserType == "Admin") {
                        $GetLeads = FETCH_TABLE_FROM_DB("SELECT LeadPersonCreatedBy, LeadPersonSubStatus, LeadPersonEmailId,  LeadPersonPhoneNumber, LeadPersonStatus, LeadSalutations, LeadPersonFullname, LeadPersonManagedBy, LeadPersonSource, LeadPriorityLevel, LeadPersonCreatedAt, LeadsId  FROM leads WHERE LeadPersonSubStatus like '%$sub_status%' GROUP BY LeadsId ORDER by LeadsId DESC limit $start, $listcounts", true);
                      } else {
                        $LOGIN_UserId = LOGIN_UserId;
                        $GetLeads = FETCH_TABLE_FROM_DB("SELECT LeadPersonCreatedBy, LeadPersonSubStatus, LeadPersonEmailId, LeadPersonPhoneNumber, LeadPersonStatus, LeadSalutations, LeadPersonFullname, LeadPersonManagedBy, LeadPersonSource, LeadPriorityLevel, LeadPersonCreatedAt, LeadsId  FROM leads where LeadPersonSubStatus like '%$sub_status%' and LeadPersonManagedBy='$LOGIN_UserId' GROUP BY LeadsId ORDER by LeadsId DESC limit $start, $listcounts", true);
                      }
                    } else {
                      if (LOGIN_UserType == "Admin") {
                        $GetLeads = FETCH_TABLE_FROM_DB("SELECT LeadPersonCreatedBy, LeadPersonSubStatus, LeadPersonEmailId,  LeadPersonPhoneNumber, LeadPersonStatus, LeadSalutations, LeadPersonFullname, LeadPersonManagedBy, LeadPersonSource, LeadPriorityLevel, LeadPersonCreatedAt, LeadsId FROM leads GROUP BY LeadsId ORDER by LeadsId DESC limit $start,$listcounts", true);
                      } else {
                        $LOGIN_UserId = LOGIN_UserId;
                        $GetLeads = FETCH_TABLE_FROM_DB("SELECT LeadPersonCreatedBy, LeadPersonSubStatus, LeadPersonEmailId, LeadPersonPhoneNumber, LeadPersonStatus, LeadSalutations, LeadPersonFullname, LeadPersonManagedBy, LeadPersonSource, LeadPriorityLevel, LeadPersonCreatedAt, LeadsId  FROM leads where LeadPersonManagedBy='$LOGIN_UserId' GROUP BY LeadsId ORDER by LeadsId DESC limit $start, $listcounts", true);
                      }
                    }
                    if ($GetLeads == null) { ?>
                      <div class="col-md-12">
                        <div class="card card-body border-0 shadow-sm">
                          <div class="text-left">
                            <h1><i class="fa fa-globe fa-spin display-4 text-success"></i></h1>
                            <h4 class="text-muted">No leads found</h4>
                            <p class="text-muted">You can add a new lead by clicking the button above.</p>
                            <a href="add.php" class="btn btn-md btn-primary">Add leads</a>
                          </div>
                        </div>
                      </div>
                    <?php } else {
                      $Count = 0;
                      if (isset($_GET['view_page'])) {
                        $view_page = $_GET['view_page'];
                        if ($view_page == 1) {
                          $Count = 0;
                        } else {
                          $Count = $listcounts * ($view_page - 1);
                        }
                      } else {
                        $Count = $Count;
                      }


                      if (DEVICE_TYPE == "Mobile") {
                        $flex = "";
                      } else {
                        $flex = "flex-s-b";
                      }

                      foreach ($GetLeads as $leads) {
                        $Count++;
                        $LeadPersonCreatedBy = $leads->LeadPersonCreatedBy;
                        $LeadsId = $leads->LeadsId;
                        $FollowUpsSQL = "SELECT * FROM lead_followups where LeadFollowMainId='$LeadsId'";
                        $LeadFollowUpDate = FETCH($FollowUpsSQL, "LeadFollowUpDate");
                        $LeadFollowUpTime = FETCH($FollowUpsSQL, "LeadFollowUpTime");
                        $lead_requirements = CHECK("SELECT * FROM lead_requirements where leadMainId='$LeadsId'");
                        include "../../../include/admin/common/lead-list.php";

                        include "../../../include/sections/Add-Instant-Feedback.php";
                      } ?> <?php } ?> <div class="col-md-12 flex-s-b mt-2 mb-1">
                      <div class="">
                        <h6 class="mb-0" style="font-size:0.75rem;color:grey;">Page
                          <b><?php echo IfRequested("GET", "view_page", $page, false); ?></b> from <b><?php echo $NetPages; ?> </b>
                          pages <br>Total <b><?php echo $TotalItems; ?></b> Entries
                        </h6>
                      </div>
                      <div class="flex">
                        <span class="mr-1">
                          <?php
                          if (isset($_GET['view'])) {
                            $viewcheck = "&view=" . $_GET['view'];
                          } else {
                            $viewcheck = "";
                          }

                          if (isset($_GET['sub_status'])) {
                            $sub_statuscheck = "&sub_status=" . $_GET['sub_status'];
                          } else {
                            $sub_statuscheck = "";
                          }

                          if (isset($_GET['LeadPersonSubStatus'])) {
                            $pagefilter = "&LeadPersonManagedBy=" . $_GET['LeadPersonManagedBy'] . "&LeadPersonSource=" . $_GET['LeadPersonSource'] . "&LeadPriorityLevel=" . $_GET['LeadPriorityLevel'] . "&LeadPersonSubStatus=" . $_GET['LeadPersonSubStatus'] . "&LeadPersonStatus=" . $_GET['LeadPersonStatus'] . "&LeadFollowStatus=" . $_GET['LeadFollowStatus'] . "&LeadPersonFullname=" . $_GET['LeadPersonFullname'] . "&search_true=" . $_GET['search_true'] . "&LeadPersonPhoneNumber=" . $_GET['LeadPersonPhoneNumber'];
                          } else {
                            $pagefilter = "";
                          } ?>
                          <a href="?view_page=<?php echo $previous_page; ?><?php echo $viewcheck; ?><?php echo $sub_statuscheck; ?><?php echo $pagefilter; ?>" class="btn btn-sm btn-default"><i class="fa fa-angle-double-left"></i></a>
                        </span>
                        <form style="padding:0.3rem !important;">
                          <input type="number" name="view_page" onchange="form.submit()" class="form-control form-control-sm  mb-0" min="1" max="<?php echo $NetPages; ?>" value="<?php echo IfRequested("GET", "view_page", 1, false); ?>">
                        </form>
                        <span class="ml-1">
                          <a href="?view_page=<?php echo $next_page; ?><?php echo $viewcheck; ?><?php echo $sub_statuscheck; ?><?php echo $pagefilter; ?>" class="btn btn-sm btn-default"><i class="fa fa-angle-double-right"></i></a>
                        </span>
                        <?php if (isset($_GET['view_page'])) { ?> <span class="ml-1">
                            <a href="index.php" class="btn btn-sm btn-danger mb-0"><i class="fa fa-times m-1"></i></a>
                          </span>
                        <?php } ?>
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
  <script>
    function CallStatusFunction() {
      var statustype = document.getElementById("statustype");
      <?php
      $FetchCallStatus = FETCH_TABLE_FROM_DB("SELECT * FROM configs, config_values where configs.ConfigsId=config_values.ConfigValueGroupId and configs.ConfigGroupName='CALL_STATUS' ORDER BY ConfigValueId DESC", true);
      if ($FetchCallStatus != null) {
        foreach ($FetchCallStatus as $CallStatus) { ?>
          if (statustype.value == <?php echo $CallStatus->ConfigValueId; ?>) {
            document.getElementById("view_<?php echo $CallStatus->ConfigValueId; ?>").style.display = "block";
            document.getElementById("leasstatus").value = "<?php echo $CallStatus->ConfigValueDetails; ?>";
          } else {
            document.getElementById("view_<?php echo $CallStatus->ConfigValueId; ?>").style.display = "none";
          }
      <?php }
      } ?>
    }
  </script>
  <?php include $Dir . "/include/admin/footer_files.php"; ?>
</body>

</html>