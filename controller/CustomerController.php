<?php
//add controller helper files
require 'helper.php';

//add aditional requirements
require '../require/admin/sessionvariables.php';


//save customer record
if (isset($_POST['SaveCustomerRecord'])) {
    $Response = INSERT("users", [
        "UserFullName" => $_POST['UserFullName'],
        "UserCompanyName" => $_POST['UserCompanyName'],
        "UserPhoneNumber" => $_POST['UserPhoneNumber'],
        "UserEmailId" => $_POST['UserEmailId'],
        "UserDateOfBirth" => $_POST['UserDateOfBirth'],
        "UserCreatedAt" => RequestDataTypeDateTime,
        "UserUpdatedAt" => RequestDataTypeDateTime,
        "UserType" => "Customer"
    ]);
    $Response = INSERT("user_addresses", [
        "UserAddressUserId" => FETCH("SELECT * FROM users where UserPhoneNumber='" . $_POST['UserPhoneNumber'] . "' ORDER BY UserId DESC limit 1", "UserId"),
        "UserStreetAddress" => SECURE($_POST['CustomerStreetAddress'], "e"),
        "UserLocality" => $_POST['CustomerAreaLocality'],
        "UserCity" => $_POST['CustomerCity'],
        "UserState" => $_POST['CustomerState'],
        "UserCountry" => $_POST['CustomerCountry'],
        "UserPincode" => $_POST['CustomerPincode'],
        "UserAddressType" => "BILLING"
    ]);
    $Response = INSERT("user_addresses", [
        "UserAddressUserId" => FETCH("SELECT * FROM users where UserPhoneNumber='" . $_POST['UserPhoneNumber'] . "' ORDER BY UserId DESC limit 1", "UserId"),
        "UserStreetAddress" => SECURE($_POST['CustomerStreetAddress1'], "e"),
        "UserLocality" => $_POST['CustomerAreaLocality1'],
        "UserCity" => $_POST['CustomerCity1'],
        "UserState" => $_POST['CustomerState1'],
        "UserCountry" => $_POST['CustomerCountry1'],
        "UserPincode" => $_POST['CustomerPincode1'],
        "UserAddressType" => "SHIPPING"
    ]);

    $Msg = [
        "true" => "<b>" . $_POST['CustomerName'] . "</b> details are saved successfully!",
        "false" => "Unable to save customer record at the momemnt!"
    ];

    RESPONSE($Response, $Msg['true'], $Msg['false']);
}

//update customer primary data
if (isset($_POST['UpdateCustomerRecord'])) {
    $Response = UPDATE_TABLE("users", [
        "UserFullName" => $_POST['UserFullName'],
        "UserCompanyName" => $_POST['UserCompanyName'],
        "UserPhoneNumber" => $_POST['UserPhoneNumber'],
        "UserEmailId" => $_POST['UserEmailId'],
        "UserDateOfBirth" => $_POST['UserDateOfBirth'],
        "UserUpdatedAt" => RequestDataTypeDate
    ], "UserId='" . SECURE($_POST['UserId'], "d") . "'");

    $Msg = [
        "true" => "<b>" . $_POST['CustomerName'] . "</b> details are updated successfully!",
        "false" => "Unable to update <b>" . $_POST['CustomerName'] . "</b> details at the moment!"
    ];
    RESPONSE($Response, $Msg['true'], $Msg['false']);
}


//UPDATE customer address details
if (isset($_POST['UpdateAddress'])) {
    $UserAddressType = SECURE($_POST['UserAddressType'], "d");

    //create address if there is no address for the user
    $CheckAddress1 = CHECK("SELECT * FROM user_addresses where UserAddressUserId='" . SECURE($_POST['UserAddressUserId'], "d") . "' AND UserAddressType='BILLING'");
    if ($CheckAddress1 == null) {
        INSERT("user_addresses", [
            "UserAddressUserId" => SECURE($_POST['UserAddressUserId'], "d"),
            "UserStreetAddress" => "",
            "UserLocality" => "",
            "UserCity" => "",
            "UserState" => "",
            "UserCountry" => "",
            "UserPincode" => "",
            "UserAddressType" => "BILLING"
        ]);
    }
    $CheckAddress2 = CHECK("SELECT * FROM user_addresses where UserAddressUserId='" . SECURE($_POST['UserAddressUserId'], "d") . "' AND UserAddressType='SHIPPING'");
    if ($CheckAddress2 == null) {
        INSERT("user_addresses", [
            "UserAddressUserId" => SECURE($_POST['UserAddressUserId'], "d"),
            "UserStreetAddress" => "",
            "UserLocality" => "",
            "UserCity" => "",
            "UserState" => "",
            "UserCountry" => "",
            "UserPincode" => "",
            "UserAddressType" => "SHIPPING"
        ]);
    }

    $Response = UPDATE_DATA("user_addresses", [
        "UserStreetAddress" => SECURE($_POST['CustomerStreetAddress'], "e"),
        "UserLocality" => $_POST['CustomerAreaLocality'],
        "UserCity" => $_POST['CustomerCity'],
        "UserState" => $_POST['CustomerState'],
        "UserCountry" => $_POST['CustomerCountry'],
        "UserPincode" => $_POST['CustomerPincode'],
        "UserAddressUserId" => SECURE($_POST['UserAddressUserId'], "d"),
    ], "UserAddressId='" . SECURE($_POST['UserAddressId'], "d") . "'");
    $Msg = [
        "true" => "Customer address details are updated successfully!",
        "false" => "Unable to update customer address details at the moment!"
    ];
    RESPONSE($Response, $Msg['true'], $Msg['false']);

    //other activity
} else if (isset($_POST['SaveCustomer'])) {
    $UserSalutation = $_POST['UserSalutation'];
    $UserFullName = $_POST["UserFirstName"] . " " . $_POST['UserLastName'];
    $UserPhoneNumber = $_POST['UserPhoneNumber'];
    $UserEmailId = $_POST['UserEmailId'];
    $UserCompanyName = $_POST["UserCompanyName"];
    $UserWorkFeilds = $_POST["UserWorkFeilds"];
    $UserDepartment = $_POST["UserDepartment"];
    $UserDesignation = $_POST["UserDesignation"];
    $UserNotes = "";
    $UserStatus = $_POST["UserStatus"];
    $UserCreatedAt = date("Y-m-d h:i A");
    $UserType = $_POST["UserType"];
    $UserPassword = $_POST['UserPassword'];

    //address requests 
    $UserStreetAddress = POST("UserStreetAddress");
    $UserLocality = POST("UserLocality");
    $UserCity = POST("UserCity");
    $UserState = POST("UserState");
    $UserCountry = POST("UserCountry");
    $UserPincode = POST("UserPincode");
    $UserAddressType = POST("UserAddressType");
    $UserAddressContactPerson = POST("UserAddressContactPerson");
    $UserAddressNotes = POST("UserAddressNotes");
    $UserAddressMapUrl = POST("UserAddressMapUrl");

    //check if phone or email-id is already registered or not
    $CheckifPhone = CHECK("SELECT * FROM users where UserPhoneNumber='$UserPhoneNumber'");
    $CheckifMail = CHECK("SELECT * FROM users where UserEmailId='$UserEmailId'");
    if ($CheckifPhone != null) {;
        LOCATION("warning", "Phone Number is already registered!", $access_url);
    } elseif ($CheckifMail != null) {
        LOCATION("warning", "Email-id is already registered", $access_url);
    } else {
        $Save = SAVE("users", ["UserFullName", "UserSalutation", "UserType", "UserPassword", "UserPhoneNumber", "UserEmailId", "UserCompanyName", "UserWorkFeilds", "UserDepartment", "UserDesignation", "UserNotes", "UserStatus", "UserCreatedAt"]);
    }

    //GET registered customer id
    if ($_POST['UserCity'] != null) {
        $UserAddressUserId = FETCH("SELECT * FROM users where UserPhoneNumber='$UserPhoneNumber' AND UserEmailId='$UserEmailId' ORDER BY UserId DESC limit 0, 1", "UserId");

        //save customer address
        $Save = SAVE("user_addresses", ["UserAddressUserId", "UserStreetAddress", "UserLocality", "UserCity", "UserState", "UserCountry", "UserPincode", "UserAddressType", "UserAddressContactPerson", "UserAddressNotes", "UserAddressMapUrl"], false);
    } else {
        $Save = $Save;
    }

    //check url response
    if (isset($_POST['success_url'])) {
        $access_url = SECURE($_POST['success_url'], "d") . "?customer_id=" . SECURE($UserAddressUserId, "e");
    }

    //generate response
    RESPONSE($Save, "New Customer Details saved successfully!", "Unable to save customer details at the moment!");

    //remove address
} elseif (isset($_GET['remove_address_list'])) {
    DeleteReqHandler("remove_address_list", [
        "user_addresses" => "UserAddressId='" . SECURE($_GET['UserAddressId'], 'd') . "'",
    ], [
        "true" => "Address details are removed successfully!",
        "false" => "Address details are not removed"
    ]);
}
