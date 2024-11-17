<?php
$Dir = "../../../..";
require $Dir . '/require/modules.php';
require $Dir . '/require/admin/access-control.php';
require $Dir . '/require/admin/sessionvariables.php';

//pagevariables
$PageName = "Edit Deal Details";
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

                                    <form action="<?php echo CONTROLLER; ?>/LeadsController.php" method="POST">
                                        <?php FormPrimaryInputs(true, [
                                            "ManagedBy" => GET_DATA("leads", "LeadPersonManagedBy", "LeadsId='$REQ_LeadsId'")
                                        ]); ?>
                                        <div class="row">
                                            <div class="col-md-7">
                                                <h4 class="app-heading"><?php echo GET_DATA("leads", "LeadPersonFullname", "LeadsId='$REQ_LeadsId'"); ?> : <?php echo LEADID($REQ_LeadsId); ?></h4>
                                                <div class="row mb-2px">
                                                    <div class="form-group col-md-3">
                                                        <label>Salutation</label>
                                                        <select name="LeadSalutations" class="form-control form-control-sm">
                                                            <?php InputOptions(["Mr.", "Mrs.", "Miss.", "Ms.", "Dr.", "Prof.", "Sir"], GET_DATA("leads", "LeadSalutations", "LeadsId='$REQ_LeadsId'")); ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-9">
                                                        <label>Full Name</label>
                                                        <input type="text" name="LeadPersonFullname" value="<?php echo GET_DATA("leads", "LeadPersonFullname", "LeadsId='$REQ_LeadsId'"); ?>" list="LeadPersonFullname" class="form-control form-control-sm" placeholder="Gaurav Singh" required="">
                                                        <?php SUGGEST("leads", "LeadPersonFullname", "ASC") ?>
                                                    </div>
                                                </div>

                                                <div class="row mb-2px">
                                                    <div class="form-group col-md-5">
                                                        <label>Phone Number</label>
                                                        <input type="phone" name="LeadPersonPhoneNumber" value="<?php echo GET_DATA("leads", "LeadPersonPhoneNumber", "LeadsId='$REQ_LeadsId'"); ?>" list="LeadPersonPhoneNumber" placeholder="without +91" class="form-control form-control-sm" required="">
                                                        <?php SUGGEST("leads", "LeadPersonPhoneNumber", "ASC") ?>
                                                    </div>
                                                    <div class="form-group col-md-7">
                                                        <label>Email</label>
                                                        <input type="email" name="LeadPersonEmailId" value="<?php echo GET_DATA("leads", "LeadPersonEmailId", "LeadsId='$REQ_LeadsId'"); ?>" list="LeadPersonEmailId" class="form-control form-control-sm" placeholder="example@domain.tld">
                                                        <?php SUGGEST("leads", "LeadPersonEmailId", "ASC") ?>
                                                    </div>
                                                </div>
                                                <div class="row mb-2px">
                                                    <div class="form-group col-md-4">
                                                        <label>Lead Stage </label>
                                                        <select class="form-control form-control-sm" name="LeadPersonStatus">
                                                            <?php
                                                            CONFIG_VALUES("LEAD_STAGES", GET_DATA("leads", "LeadPersonStatus", "LeadsId='$REQ_LeadsId'")); ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Lead Priority level </label>
                                                        <select class="form-control form-control-sm" name="LeadPriorityLevel">
                                                            <?php CONFIG_VALUES("LEAD_PERIORITY_LEVEL", GET_DATA("leads", "LeadSalutations", "LeadsId='$REQ_LeadsId'")); ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Lead Source</label>
                                                        <select class="form-control form-control-sm" name="LeadPersonSource">
                                                            <?php CONFIG_VALUES("LEAD_SOURCES", GET_DATA("leads", "LeadPersonSource", "LeadsId='$REQ_LeadsId'")); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-2px">
                                                    <div class="form-group col-md-12">
                                                        <label>Address</label>
                                                        <textarea name="LeadPersonAddress" row="3" class="form-control form-control-sm" placeholder="Address"><?php echo GET_DATA("leads", "LeadPersonAddress", "LeadsId='$REQ_LeadsId'"); ?></textarea>
                                                    </div>
                                                </div>

                                                <?php if (LOGIN_UserType == "ADMIN") { ?>
                                                    <div class="row mb-2px">
                                                        <div class="form-group col-md-6">
                                                            <label>Lead Assigned To</label>
                                                            <select class="form-control form-control-sm" name="LeadPersonManagedBy">
                                                                <?php
                                                                $Users = FETCH_TABLE_FROM_DB("SELECT * FROM users ORDER BY UserFullName ASC", true);
                                                                foreach ($Users as $User) {
                                                                    if ($User->UserId == GET_DATA("leads", "LeadPersonManagedBy", "LeadsId='$REQ_LeadsId'")) {
                                                                        $selected = "selected";
                                                                    } else {
                                                                        $selected = "";
                                                                    }
                                                                    echo "<option value='" . $User->UserId . "' $selected>" . $User->UserFullName . " @ " . $User->UserPhoneNumber . "</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                                <div class="row mb-2px">
                                                    <div class="form-group col-md-12">
                                                        <label>Notes/Remarks</label>
                                                        <textarea name="LeadPersonNotes" class="form-control form-control-sm" rows="3"><?php echo SECURE(GET_DATA("leads", "LeadPersonNotes", "LeadsId='$REQ_LeadsId'"), "d"); ?></textarea>
                                                    </div>
                                                </div>

                                                <div class="row mb-2px">
                                                    <div class="col-md-12">
                                                        <a href="index.php" class="btn btn-sm btn-default mt-4" value="<?php echo SECURE($REQ_LeadsId, "e"); ?>"><i class="fa fa-angle-double-left"></i> Back To Details</a>
                                                        <button class="btn btn-sm btn-success mt-4" name="UpdateLeads" value="<?php echo SECURE($REQ_LeadsId, "e"); ?>" TYPE="submit">Update Lead Details</button>
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