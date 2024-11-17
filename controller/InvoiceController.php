<?php
//add controller helper files
require 'helper.php';

//add aditional requirements
require '../require/admin/sessionvariables.php';


if (isset($_POST['AddToCart'])) {
    $ItemSQl = "SELECT * FROM products where ProductID='" . SECURE($_POST['InvoiceCartMainItemId'], "d") . "'";
    $InvoiceCartProductDescription = FETCH($ItemSQl, "ProductName") . " - " . FETCH($ItemSQl, "ProductModalNo") . " <br><span class='text-gray'> Sno: " . SECURE($_POST['InvoiceCartItemSerialNo'], "d") . "</span>";
    $InvoiceCartQuantity = 1;
    if (SECURE($_POST['InvoiceCartTaxPercentage'], "d") == "Null") {
        $Tax = 0;
    } else {
        $Tax = round(((int)SECURE($_POST['InvoiceCartSalePrice'], "d") * (int)$InvoiceCartQuantity) / 100 * (int)SECURE($_POST['InvoiceCartTaxPercentage'], "d"));
    }

    $InvoiceCartItems = [
        "InvoiceCartMainUserId" => SECURE($_POST['InvoiceCartMainUserId'], "d"),
        "InvoiceCartMainItemId" => SECURE($_POST['InvoiceCartMainItemId'], "d"),
        "InvoiceCartSalePrice" => SECURE($_POST['InvoiceCartSalePrice'], "d"),
        "InvoiceCartTaxPercentage" => SECURE($_POST['InvoiceCartTaxPercentage'], "d"),
        "InvoiceCartQuantity" => $InvoiceCartQuantity,
        "InvoiceCartNetPrice" => (int)SECURE($_POST['InvoiceCartSalePrice'], "d") * (int)$InvoiceCartQuantity + (int)$Tax,
        "InvoiceCartProductDescription" => $InvoiceCartProductDescription,
        "InvoiceCartProductAddedAt" => RequestDataTypeDateTime,
        "InvoiceCartItemSerialNo" => SECURE($_POST['InvoiceCartItemSerialNo'], "d")
    ];

    $CheckItem = CHECK("SELECT * FROM invoice_cart where InvoiceCartMainUserId='" . SECURE($_POST['InvoiceCartMainUserId'], "d") . "' and InvoiceCartItemSerialNo='" . SECURE($_POST['InvoiceCartItemSerialNo'], "d") . "'");
    if ($CheckItem == false) {
        $Response = INSERT("invoice_cart", $InvoiceCartItems);
        RESPONSE($Response, "Item saved into cart successfully!", "Unable to add item into the cart");
    } else {
        RESPONSE(false, "", "Item already exits in cart!");
    }

    // remove cart item
} elseif (isset($_GET['remove_cart_items'])) {
    $access_url = SECURE($_GET['access_url'], "d");
    $remove_cart_items = SECURE($_GET['remove_cart_items'], "d");

    if ($remove_cart_items == true) {
        $control_id = SECURE($_GET['control_id'], "d");
        $userid = SECURE($_GET['userid'], "d");
        $Response = DELETE_FROM("invoice_cart", "InvoiceCartMainUserId='$userid' and InvoiceCartItemSerialNo='$control_id'");
        $CheckPayments = TOTAL("SELECT * FROM invoice_cart where InvoiceCartMainUserId='$userid'");
        if ($CheckPayments == 0) {
            $Response = DELETE_FROM("invoice_cart_payments", "CartMainId='$userid'");
        }
    } else {
        $Response = false;
    }

    RESPONSE($Response, "Item removed from cart successfully!", "Unable to remove item from the cart");

    //create invoice
} elseif (isset($_POST['SaveInvoiceRecord'])) {

    //Customer Details
    $InvoiceMainCustomerId = SECURE($_POST['InvoiceMainCustomerId'], "d");
    $InvoiceCustomerDetails = "";
    $InvoiceCustomerDetails .= FETCH("SELECT * FROM users where UserId='$InvoiceMainCustomerId'", "UserFullName") . "<br>";
    $InvoiceCustomerDetails .= FETCH("SELECT * FROM users where UserId='$InvoiceMainCustomerId'", "UserPhoneNumber") . "<br>";
    $InvoiceCustomerDetails .= FETCH("SELECT * FROM users where UserId='$InvoiceMainCustomerId'", "UserEmailId") . "<br>";

    //billing address
    $BillingAddressSql = "SELECT * FROM user_addresses where UserAddressType='BILLING' and UserAddressUserId='" . $InvoiceMainCustomerId . "'";
    $BillingAddress = "";
    $BillingAddress .= SECURE(FETCH($BillingAddressSql, "UserStreetAddress"), "d") . " ";
    $BillingAddress .= FETCH($BillingAddressSql, "UserLocality") . "<br>";
    $BillingAddress .= FETCH($BillingAddressSql, "UserCity") . " ";
    $BillingAddress .= FETCH($BillingAddressSql, "UserState") . "<br>";
    $BillingAddress .= FETCH($BillingAddressSql, "UserCountry") . " - " . FETCH($BillingAddressSql, "UserPincode");

    //shippingsss address
    $ShippingAddressSql = "SELECT * FROM user_addresses where UserAddressType='SHIPPING' and UserAddressUserId='" . $InvoiceMainCustomerId . "'";
    $ShippingAddress = "";
    $ShippingAddress .= SECURE(FETCH($ShippingAddressSql, "UserStreetAddress"), "d") . " ";
    $ShippingAddress .= FETCH($ShippingAddressSql, "UserLocality") . "<br>";
    $ShippingAddress .= FETCH($ShippingAddressSql, "UserCity") . " ";
    $ShippingAddress .= FETCH($ShippingAddressSql, "UserState") . "<br>";
    $ShippingAddress .= FETCH($ShippingAddressSql, "UserCountry") . " - " . FETCH($ShippingAddressSql, "UserPincode");

    //save invoice record
    $invoices = [
        "InvoiceMainCustomerId" => $InvoiceMainCustomerId,
        "InvoiceCode" => $_POST['InvoiceCode'],
        "InvoiceRefNo" => $_POST['InvoiceRefNo'],
        "InvoiceDate" => $_POST['InvoiceDate'],
        "InvoiceBillingAddress" => SECURE($BillingAddress, "e"),
        "InvoiceShippingAddress" => SECURE($ShippingAddress, "e"),
        "InvoiceCustomerDetails" => $InvoiceCustomerDetails,
        "InvoiceSentAddress" => PRIMARY_ADDRESS,
        "InvoiceNotes" => SECURE($_POST['InvoiceNotes'], "e"),
        "InvoiceCreatedAt" => RequestDataTypeDateTime,
        "InvoiceStatus" => "Pending",
    ];
    $Response = INSERT("invoices", $invoices);
    $InvoiceId = FETCH("SELECT * FROM invoices ORDER BY InvoiceId DESC limit 1", "InvoiceId");

    //save invoice service types
    $invoice_service_type = [
        "invoice_service_main_id" => $InvoiceId,
        "invoice_service_type" => $_POST['invoice_service_type'],
        "invoice_service_type_charge" => $_POST['invoice_service_type_charge'],
        "invoice_service_charge_payable" => $_POST['invoice_service_charge_payable'],
    ];
    $SaveInvoiceServiceType = INSERT("invoice_service_type", $invoice_service_type);

    //save invoice items
    $AllInvoiceItems = FETCH_TABLE_FROM_DB("SELECT * FROM invoice_cart where InvoiceCartMainUserId='$InvoiceMainCustomerId'", true);
    foreach ($AllInvoiceItems as $Item) {
        $invoice_items = [
            "MainInvoiceId" => $InvoiceId,
            "InvoiceItemId" => $Item->InvoiceCartMainItemId,
            "InvoiceItemName" => $Item->InvoiceCartProductDescription,
            "InvoiceItemSerialNo" => $Item->InvoiceCartItemSerialNo,
            "InvoiceItemSalePrice" => $Item->InvoiceCartSalePrice,
            "InvoiceItemQty" => $Item->InvoiceCartQuantity,
            "InvoiceItemTax" => $Item->InvoiceCartNetPrice,
            "InvoiceItemNetCost" => $Item->InvoiceCartNetPrice,
        ];
        $Response = INSERT("invoice_items", $invoice_items);
    }

    //save payment details 
    $PaymentSql = "SELECT * FROM invoice_cart_payments where CartMainId='" . $InvoiceMainCustomerId . "'";
    $invoice_payments = [
        "MainInvoiceId" => $InvoiceId,
        "InvoicePaymentMode" => FETCH($PaymentSql, "CartPaymentMode"),
        "InvoicePaymentSource" => FETCH($PaymentSql, "CartPaymentSource"),
        "InvoicePaymentRefNo" => FETCH($PaymentSql, "CartPaymentRefNo"),
        "InvoicePaymentDate" => FETCH($PaymentSql, "CartPaymentDate"),
        "InvoicePaidAmount" => FETCH($PaymentSql, "CartPaidAmount"),
        "InvoicePaymentNotes" => FETCH($PaymentSql, "CartPaymentDetails"),
        "InvoicePaymentCreatedAt" => RequestDataTypeDateTime,
        "InvoicePaymentUpdatedAt" => RequestDataTypeDateTime,
        "InvoicePaymentStatus" => FETCH($PaymentSql, "CartPaymentStatus")
    ];
    $Response = INSERT("invoice_payments", $invoice_payments);

    //remove junk data
    if ($Response == true) {
        $Response = DELETE_FROM("invoice_cart", "InvoiceCartMainUserId='$InvoiceMainCustomerId'");
        $Response = DELETE_FROM("invoice_cart_payments", "CartMainId='$InvoiceMainCustomerId'");
        if ($Response == true) {
            $access_url = ADMIN_URL . "/invoices";
        }

        //update product details as sold
        $AllInvoiceItems = FETCH_TABLE_FROM_DB("SELECT * FROM invoice_cart where InvoiceCartMainUserId='$InvoiceMainCustomerId'", true);
        foreach ($AllInvoiceItems as $Item) {
            $SerialNo = $Item->InvoiceCartItemSerialNo;
            UPDATE("UPDATE product_serial_no SET ProuctSerialNoStatus='SOLD' where ProductSerialNo='$SerialNo'");
        }
    } else {
        $Response = false;
    }
    RESPONSE($Response, "Invoice Created Successfully!", "Unable to create invoice at the moment!");

    //update invoice record
} elseif (isset($_POST['UpdateInvoiceRecord'])) {
    $InvoiceId = SECURE($_POST['InvoiceId'], "d");

    //Customer Details
    $InvoiceMainCustomerId = $_POST['InvoiceMainCustomerId'];
    $InvoiceCustomerDetails = "";
    $InvoiceCustomerDetails .= FETCH("SELECT * FROM users where UserId='$InvoiceMainCustomerId'", "UserFullName") . "<br>";
    $InvoiceCustomerDetails .= FETCH("SELECT * FROM users where UserId='$InvoiceMainCustomerId'", "UserPhoneNumber") . "<br>";
    $InvoiceCustomerDetails .= FETCH("SELECT * FROM users where UserId='$InvoiceMainCustomerId'", "UserEmailId") . "<br>";

    RequestHandler(
        UPDATE_DATA("invoices", [
            "InvoiceMainCustomerId" => $InvoiceMainCustomerId,
            "InvoiceCode" => $_POST['InvoiceCode'],
            "InvoiceRefNo" => $_POST['InvoiceRefNo'],
            "InvoiceDate" => $_POST['InvoiceDate'],
            "InvoiceBillingAddress" => SECURE($_POST['InvoiceBillingAddress'], "e"),
            "InvoiceShippingAddress" => SECURE($_POST['InvoiceShippingAddress'], "e"),
            "InvoiceCustomerDetails" => $InvoiceCustomerDetails,
            "InvoiceSentAddress" => PRIMARY_ADDRESS,
            "InvoiceNotes" => SECURE($_POST['InvoiceNotes'], "e"),
            "InvoiceUpdateAt" => RequestDataTypeDateTime,
        ], "InvoiceId='$InvoiceId'"),
        [
            "true" => "Invoice details are updated successfully",
            "false" => "Unable to update invoice details at the moment!"
        ]
    );

    //remove invoice record
} elseif (isset($_GET['remove_invoice_record'])) {
    DeleteReqHandler("remove_invoice_record", [
        "invoices" => "InvoiceId='" . SECURE($_GET['InvoiceId'], "d") . "'",
        "invoice_items" => "MainInvoiceId='" . SECURE($_GET['InvoiceId'], "d") . "'",
        "invoice_payments" => "MainInvoiceId='" . SECURE($_GET['InvoiceId'], "d") . "'",
    ], [
        "true" => "Invoice details are deleted successfully!",
        "false" => "Unable to delete invoice details at the moment"
    ]);


    //update invoice charges
} elseif (isset($_POST['UpdateInvoiceCharges'])) {
    $invoice_service_main_id = SECURE($_POST['invoice_service_main_id'], "d");

    $invoice_service_type = [
        "invoice_service_main_id" => $invoice_service_main_id,
        "invoice_service_type" => $_POST['invoice_service_type'],
        "invoice_service_type_charge" => $_POST['invoice_service_type_charge'],
        "invoice_service_charge_payable" => $_POST['invoice_service_charge_payable']
    ];

    $Check = CHECK("SELECT * FROM invoice_service_type where invoice_service_main_id='$invoice_service_main_id'");

    if ($Check == null) {
        $Response = INSERT("invoice_service_type", $invoice_service_type);
    } else {
        $Response = UPDATE_DATA("invoice_service_type", $invoice_service_type, "invoice_service_main_id='$invoice_service_main_id'");
    }

    RESPONSE($Response, "Service charge details are updated successfully!", "Unable to update invoice charge details!");
}
