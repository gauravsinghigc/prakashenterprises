<?php
//CONFIG data sql
function CONFIG_DATA_SQL($DATA_TYPE)
{
  global $DBConnection;
  $Sql = "SELECT * FROM configs, config_values where configs.ConfigsId=config_values.ConfigValueGroupId and configs.ConfigGroupName='$DATA_TYPE' ORDER BY ConfigValueId ASC";
  $mysqli_query = mysqli_query($DBConnection, $Sql);
  if ($mysqli_query == true) {
    return $Sql;
  } else {
    return false;
  }
}

//function get config valaus as option for select input
function CONFIG_VALUES($CONFIG_GROUP_NAME, $default = null)
{
  $leadStages = FETCH_TABLE_FROM_DB(CONFIG_DATA_SQL($CONFIG_GROUP_NAME), true);
  if ($leadStages != null) {
    foreach ($leadStages as $lstages) {
      if ($lstages->ConfigValueDetails == $default) {
        $selected = "selected";
      } else {
        $selected = "";
      }
      echo '<option value="' . $lstages->ConfigValueDetails . '"' . $selected . '>' . $lstages->ConfigValueDetails . '</option>';
    }
  } else {
    echo "<option value='Null'>No Data Found!</option>";
  }
}
