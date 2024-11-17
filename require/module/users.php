<?php
//get user address 
function UserAddress($CustomerId)
{
  $UserStreetAddress = FETCH("SELECT * FROM user_addresses where UserAddressUserId='$CustomerId'", "UserStreetAddress");
  $UserLocality = FETCH("SELECT * FROM user_addresses where UserAddressUserId='$CustomerId'", "UserLocality");
  $UserCity = FETCH("SELECT * FROM user_addresses where UserAddressUserId='$CustomerId'", "UserCity");
  $UserState = FETCH("SELECT * FROM user_addresses where UserAddressUserId='$CustomerId'", "UserState");
  $UserCountry = FETCH("SELECT * FROM user_addresses where UserAddressUserId='$CustomerId'", "UserCountry");
  $UserPincode = FETCH("SELECT * FROM user_addresses where UserAddressUserId='$CustomerId'", "UserPincode");
  $UserAddressType = FETCH("SELECT * FROM user_addresses where UserAddressUserId='$CustomerId'", "UserAddressType");

  $CompleteAddress = "($UserAddressType)<br>$UserStreetAddress $UserLocality $UserCity $UserState $UserCountry $UserPincode";

  return $CompleteAddress;
}


//user image
function GetUserImage($UserId)
{
  $UserProfileImage = FETCH("SELECT UserProfileImage FROM users where UserId='$UserId'", "UserProfileImage");
  if ($UserProfileImage == "default.png") {
    $UserProfileImg = STORAGE_URL_D . "/default.png";
  } else {
    $FilePath = __DIR__ . "/../../storage/users/" . $UserId . "/img/" . $UserProfileImage;
    if (file_exists($FilePath)) {
      $UserProfileImg = STORAGE_URL_U . "/" . $UserId . "/img/" . $UserProfileImage;
    } else {
      UPDATE("UPDATE users SET UserProfileImage='default.png' where UserId='$UserId'");
      $UserProfileImage = FETCH("SELECT UserProfileImage FROM users where UserId='$UserId'", "UserProfileImage");
      $UserProfileImg = STORAGE_URL_U . "/" . $UserId . "/img/" . $UserProfileImage;
    }
  }
  return $UserProfileImg;
}


//user details
function GetUserDetails($UserId)
{
  $AllUsers = FETCH_TABLE_FROM_DB("SELECT UserId, UserFullName, UserPhoneNumber, UserEmailId FROM users where UserId='" . $UserId . "' and UserStatus='1' ORDER BY UserFullName ASC", true);
  if ($AllUsers == null) {
    NoData("No Users found!");
  } else {
    foreach ($AllUsers as $User) {
?>
      <label for="UserId34_<?php echo $User->UserId; ?>" class='data-list record-data-65 m-b-3'>
        <div class="flex-s-b">
          <div class="w-pr-15">
            <img src="<?php echo GetUserImage($User->UserId); ?>" class="img-fluid">
          </div>
          <div class="text-left w-pr-85 p-1">
            <p>
              <span class="h6 mt-0"><?php echo $User->UserFullName; ?></span><br>
              <span class="text-gray small">
                <span><?php echo $User->UserPhoneNumber; ?></span><br>
                <span><?php echo $User->UserEmailId; ?></span><br>
                <span>
                  <span class="text-gray"><?php echo GET_DATA("user_employment_details", "UserEmpJoinedId", "UserMainUserId='" . $User->UserId . "'"); ?></span>
                  (<span class="text-gray"><?php echo GET_DATA("user_employment_details", "UserEmpGroupName", "UserMainUserId='" . $User->UserId  . "'"); ?></span>)
                  |
                  <span class="text-gray"><?php echo GET_DATA("user_employment_details", "UserEmpType", "UserMainUserId='" . $User->UserId  . "'"); ?></span> -
                  <span class="text-gray"><?php echo GET_DATA("user_employment_details", "UserEmpLocations", "UserMainUserId='" . $User->UserId  . "'"); ?></span>
                </span>
              </span>
            </p>
          </div>
        </div>
      </label>
    <?php
    }
  }
}

//user details
function UserDetails($UserId)
{
  $AllUsers = FETCH_TABLE_FROM_DB("SELECT UserId, UserFullName, UserPhoneNumber, UserEmailId FROM users where UserId='" . $UserId . "' and UserStatus='1' ORDER BY UserFullName ASC", true);
  if ($AllUsers == null) {
    NoData("No Users found!");
  } else {
    foreach ($AllUsers as $User) {
    ?>
      <span class="">
        <span class="fs-13"><?php echo $User->UserFullName; ?></span><br>
        <span class="text-gray small">
          <span><?php echo $User->UserPhoneNumber; ?></span><br>
          <span><?php echo $User->UserEmailId; ?></span><br>
        </span>
      </span>
<?php
    }
  }
}
define("SALUTATION", [
  "Mr", "Mrs", "Miss", "Dr.", "Prof."
]);
