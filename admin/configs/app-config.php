<?php
$Dir = "../../";
require $Dir . '/require/modules.php';
require $Dir . '/require/admin/access-control.php';
require $Dir . '/require/admin/sessionvariables.php';

//pagevariables
$PageName = "App Configurations";
$PageDescription = "Manage System Profile, address, logo";
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
      document.getElementById("system_profile").classList.add("active");
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
                      <?php include "common.php"; ?>
                    </div>

                    <div class="col-md-12">
                      <h4 class="app-heading"><?php echo $PageName; ?></h4>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <h5 class="app-sub-heading">Available Configurations</h5>
                      <form action="../../controller/configcontroller.php" method="POST">
                        <?php FormPrimaryInputs(true); ?>
                        <div class="form-group flex-s-b">
                          <input type="text" name="ConfigGroupName" class="form-control mb-0 mr-2" placeholder="Configuration Group Name">
                          <button class="btn btn-sm btn-success" name="SaveConfigurations" type="submit">Save</button>
                        </div>
                      </form>
                      <ul class="config-menu">
                        <?php
                        $Configs = FETCH_TABLE_FROM_DB("SELECT * FROM configs ORDER By ConfigsId DESC", true);
                        if ($Configs != null) {
                          $Count = 0;
                          foreach ($Configs as $Data) {
                            $Count++;
                            if (isset($_GET['id'])) {
                              if ($Data->ConfigsId == $_GET['id']) {
                                $selected = "active";
                              } else {
                                $selected = "";
                              }
                            } else {
                              if ($Count == 1) {
                                $selected = "active";
                              } else {
                                $selected = "";
                              }
                            }
                            $id = $Data->ConfigsId;
                        ?>
                            <li><a href="app-config.php?id=<?php echo $Data->ConfigsId; ?>" class="<?php echo $selected; ?>"><?php echo $Data->ConfigGroupName; ?> <i class="fa fa-angle-right"></i></a></li>
                        <?php
                          }
                        } else {
                          NoData("No Configuration Group Found!");
                          $id = 0;
                        } ?>
                      </ul>
                    </div>
                    <div class="col-md-8">
                      <h5 class="app-sub-heading">Configurations Values</h5>
                      <form action="../../controller/configcontroller.php" method="POST">
                        <div class="row">
                          <?php
                          if (isset($_GET['id'])) {
                            $id = $_GET['id'];
                            $ConfigSql = "SELECT * FROM configs, config_values where configs.ConfigsId=config_values.ConfigValueGroupId and configs.ConfigsId='$id' ORDER BY configs.ConfigsId DESC";
                          } else {
                            $id = FETCH("SELECT * FROM configs ORDER BY ConfigsId DESC limit 1", "ConfigsId");
                            $ConfigSql = "SELECT * FROM configs, config_values where configs.ConfigsId=config_values.ConfigValueGroupId and configs.ConfigsId='$id' ORDER BY configs.ConfigsId ASC";
                            $id = FETCH($ConfigSql, "ConfigsId");
                          }

                          FormPrimaryInputs(true, [
                            "ConfigValueGroupId" => $id
                          ]);
                          if ($id == 10) { ?>
                            <div class="col-md-4">
                              <div class="form-group">
                                <select name="ConfigReferenceId" class="form-control" required>
                                  <option value="0">Select Call Response</option>
                                  <?php $ConfigsOptions = FETCH_TABLE_FROM_DB("SELECT * FROM config_values where ConfigValueGroupId='7'", true);
                                  if ($ConfigsOptions != null) {
                                    foreach ($ConfigsOptions as $Options) {
                                      echo "<option value='" . $Options->ConfigValueId  . "'>" . $Options->ConfigValueDetails . "</option>";
                                    }
                                  } else {
                                    echo "<option value='0'>Please add one Call Status</option>";
                                  } ?>
                                </select>
                              </div>
                            </div>
                          <?php }  ?>
                          <div class="col-md-8">
                            <div class="form-group flex-s-b mb-2">
                              <input type="text" name="ConfigValueDetails" class="form-control w-75 mb-0 mr-2" placeholder="Enter New Value">
                              <button type="submit" name="SaveNewConfigValue" class="btn btn-sm btn-success w-25">Save Details</button>
                            </div>
                          </div>
                        </div>
                      </form>
                      <hr>
                      <?php
                      $CValues = FETCH_TABLE_FROM_DB($ConfigSql, true);
                      if ($CValues != null) {
                        foreach ($CValues as $Values) {
                      ?>
                          <p class="data-list flex-s-b">
                            <span><?php echo $Values->ConfigValueDetails; ?> |
                              <span class="text-grey"> <?php echo FETCH("SELECT * FROM config_values where ConfigValueId='" . $Values->ConfigReferenceId . "'", "ConfigValueDetails"); ?></span>
                            </span>
                            <span>
                              <a href="#" onclick="Databar('update_<?php echo $Values->ConfigValueId; ?>')" class="text-info">Edit Details</a>
                              <span>
                                <?php CONFIRM_DELETE_POPUP(
                                  "delete_values",
                                  [
                                    "delete_configs_records" => true,
                                    "control_id" => $Values->ConfigValueId,
                                  ],
                                  "configcontroller",
                                  "Remove",
                                  "text-danger"
                                ); ?>
                              </span>
                            </span>
                          <form action="../../controller/configcontroller.php" class="hidden" id="update_<?php echo $Values->ConfigValueId; ?>" method="POST">
                            <div class="row">
                              <?php FormPrimaryInputs(true, [
                                "ConfigValueId" => $Values->ConfigValueId
                              ]);
                              if ($id == 10) { ?>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <select name="ConfigReferenceId" class="form-control" required>
                                      <option value="0">Select Call Response</option>
                                      <?php $ConfigsOptions = FETCH_TABLE_FROM_DB("SELECT * FROM config_values where ConfigValueGroupId='7'", true);
                                      if ($ConfigsOptions != null) {
                                        foreach ($ConfigsOptions as $Options) {
                                          if ($Options->ConfigValueDetails == FETCH("SELECT * FROM config_values where ConfigValueId='" . $Values->ConfigReferenceId . "'", "ConfigValueDetails")) {
                                            $selected = "selected";
                                          } else {
                                            $selected = "";
                                          }
                                          echo "<option value='" . $Options->ConfigValueId  . "' $selected>" . $Options->ConfigValueDetails . "</option>";
                                        }
                                      } else {
                                        echo "<option value='0'>Please add one Call Status</option>";
                                      } ?>
                                    </select>
                                  </div>
                                </div>
                              <?php } ?>
                              <div class="col-md-8">
                                <div class="form-group flex-s-b mb-2">
                                  <input type="text" name="ConfigValueDetails" value="<?php echo $Values->ConfigValueDetails; ?>" class="form-control w-75 mb-0 mr-2" placeholder="Enter New Value">
                                  <button type="submit" name="UpdateConfigValues" class="btn btn-sm btn-success w-25">Update Details</button>
                                </div>
                              </div>
                              <hr>
                            </div>
                          </form>
                          </p>
                      <?php
                        }
                      } else {
                        NoData(
                          "<br>No Details Found!"
                        );
                        if (isset($_GET['app-config']) == true) {
                          CONFIRM_DELETE_POPUP(
                            "delete_data",
                            [
                              "delete_configs_group" => true,
                              "control_id" => $id,
                            ],
                            "configcontroller",
                            "Remove Configuration Group",
                            "btn btn-sm btn-danger d-flex mx-auto"
                          );
                        }
                      }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
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