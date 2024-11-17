<?php
$Dir = "../../";
require $Dir . '/require/modules.php';
require $Dir . '/require/admin/access-control.php';
require $Dir . '/require/admin/sessionvariables.php';


//pagevariables
$PageName = "API & Keys";
$PageDescription = "Manage your application API & Keys";

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
      document.getElementById("configs").classList.add("active");
      document.getElementById("system_keys").classList.add("active");
    }
    window.onload = SidebarActive;
  </script>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <?php
    include $Dir . "/include/admin/loader.php";
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
                    <div class="col-md-12">
                      <h4 class="app-heading"><?php echo $PageName; ?></h4>
                    </div>
                  </div>
                  <form class="form" action="../../controller/configcontroller.php" method="POST">
                    <?php FormPrimaryInputs(true); ?>
                    <div class="row">
                      <div class="form-group col-md-6 col-lg-6 col-sm-6 col-12">
                        <label>Enable SMS Configurations </label>
                        <select class="form-control" onchange="enablesms()" id="configsms" name="CONTROL_SMS" required="">
                          <?php
                          $smsstatus = CONTROL_SMS;
                          if ($smsstatus == "true") { ?>
                            <option value="false">Disabled</option>
                            <option value="true" selected="">Enabled</option>
                          <?php } else { ?>
                            <option value="false" selected="">Disabled</option>
                            <option value="true">Enabled</option>
                          <?php  } ?>

                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <?php if ($smsstatus == "true") {
                        $status = ""; ?>
                      <?php } else {
                        $status = "style='display:none;'";  ?>
                      <?php } ?>
                      <div id="smsconfigs" <?php echo $status; ?>>
                        <div class="form-group col-md-6 col-lg-6 col-sm-6 col-12">
                          <label>Sender ID & API Key</label>
                          <textarea style="height: 100% !important;" class="form-control" name="SMS_SENDER_ID" required="" rows="2"><?php echo CONFIG_FIELDS("SMS_SENDER_ID", "configurationvalue"); ?></textarea>
                          <textarea style="height: 100% !important;" class="form-control" name="SMS_API_KEY" required="" rows="2"><?php echo SMS_API_KEY; ?></textarea>
                          <br>
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-6 col-12">
                          <label>Registration Templated ID & SMS Template</label>
                          <textarea style="height: 100% !important;" class="form-control" name="SMS_OTP_TEMP_ID_VALUE" required="" rows="2"><?php echo CONFIG_FIELDS("SMS_OTP_TEMP_ID", "configurationvalue"); ?></textarea>
                          <textarea style="height: 100% !important;" class="form-control" name="SMS_OTP_TEMP_ID_SUPPORTIVE_TEXT" required="" rows="2"><?php echo CONFIG_FIELDS("SMS_OTP_TEMP_ID", "configurationsupportivetext"); ?></textarea>
                          <br>
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-6 col-12">
                          <label>Password Reset Templated ID & SMS Template</label>
                          <textarea style="height: 100% !important;" class="form-control" name="PASS_RESET_OTP_TEMP_VALUE" required="" rows="2"><?php echo CONFIG_FIELDS("PASS_RESET_OTP_TEMP", "configurationvalue"); ?></textarea>
                          <textarea style="height: 100% !important;" class="form-control" name="PASS_RESET_OTP_TEMP_SUPPORTIVE_TEXT" required="" rows="2"><?php echo CONFIG_FIELDS("PASS_RESET_OTP_TEMP", "configurationsupportivetext"); ?></textarea>

                        </div>
                      </div>

                      <div class="col-md-12">
                        <button type="Submit" name="UpdateApi&Keys" class="btn btn-md btn-primary">Update Details</button>
                        <br><br>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <script>
                function enablesms() {
                  var configsms = document.getElementById("configsms");
                  if (configsms.value == "true") {
                    document.getElementById("smsconfigs").style.display = "block";
                  } else {
                    document.getElementById("smsconfigs").style.display = "none";
                  }
                }
              </script>
            </div>
          </div>
        </div>
      </section>
    </div>

    <?php include $Dir . "/include/admin/footer.php"; ?>
  </div>

  <?php include $Dir . "/include/admin/footer_files.php"; ?>

</body>

</html>