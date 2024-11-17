<?php
if (isset($_GET['view'])) {
  $view = $_GET['view'];
  if (LOGIN_UserType == "ADMIN" || LOGIN_UserType == "DIGITAL") {
    $TotalItems = TOTAL("SELECT LeadsId FROM leads WHERE LeadPersonStatus LIKE '%$view%'");
  } else {
    $LOGIN_UserId = LOGIN_UserId;
    $TotalItems = TOTAL("SELECT LeadsId FROM leads where LeadPersonStatus LIKE '%$view%' and LeadPersonManagedBy='$LOGIN_UserId'");
  }
} elseif (isset($_GET['search_true'])) {
  $LeadPersonSubStatus = $_GET['LeadPersonSubStatus'];
  $LeadPersonStatus = $_GET['LeadPersonStatus'];
  $LeadPersonFullname = $_GET['LeadPersonFullname'];
  $LeadPersonPhoneNumber = $_GET['LeadPersonPhoneNumber'];
  $LeadPersonSource = $_GET['LeadPersonSource'];
  $LeadPersonManagedBy = $_GET['LeadPersonManagedBy'];
  if ($LeadPersonManagedBy == null) {
    $Managed = "LeadPersonManagedBy like '%$LeadPersonManagedBy%' and";
  } else {
    $Managed = "LeadPersonManagedBy='$LeadPersonManagedBy' and";
  }
  if (LOGIN_UserType == "ADMIN" || LOGIN_UserType == "DIGITAL") {
    $TotalItems = TOTAL("SELECT LeadsId FROM leads WHERE $Managed LeadPersonSource like '%$LeadPersonSource%' and LeadPersonPhoneNumber like '%$LeadPersonPhoneNumber%' and LeadPersonFullname like '%$LeadPersonFullname%' and LeadPersonSubStatus like '%$LeadPersonSubStatus%' and LeadPersonStatus LIKE '%$LeadPersonStatus%'");
  } else {
    $LOGIN_UserId = LOGIN_UserId;
    $TotalItems = TOTAL("SELECT LeadsId FROM leads where $Managed LeadPersonSource like '%$LeadPersonSource%' and LeadPersonPhoneNumber like '%$LeadPersonPhoneNumber%' and LeadPersonFullname like '%$LeadPersonFullname%' and LeadPersonSubStatus like '%$LeadPersonSubStatus%' and LeadPersonStatus LIKE '%$LeadPersonStatus%' and LeadPersonManagedBy='$LOGIN_UserId'");
  }
} elseif (isset($_GET['sub_status'])) {
  $sub_status = $_GET['sub_status'];
  if (LOGIN_UserType == "ADMIN" || LOGIN_UserType == "DIGITAL") {
    $TotalItems = TOTAL("SELECT LeadsId FROM leads WHERE LeadPersonSubStatus like '%$sub_status%'");
  } else {
    $LOGIN_UserId = LOGIN_UserId;
    $TotalItems = TOTAL("SELECT LeadsId FROM leads where LeadPersonSubStatus like '%$sub_status%' and LeadPersonManagedBy='$LOGIN_UserId'");
  }
} else {
  if (LOGIN_UserType == "ADMIN" || LOGIN_UserType == "DIGITAL") {
    $TotalItems = TOTAL("SELECT LeadsId FROM leads");
  } else {
    $LOGIN_UserId = LOGIN_UserId;
    $TotalItems = TOTAL("SELECT LeadsId FROM leads where LeadPersonManagedBy='$LOGIN_UserId'");
  }
}
