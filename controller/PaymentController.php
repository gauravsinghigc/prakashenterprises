<?php
//add controller helper files
require 'helper.php';

//add aditional requirements
require '../require/admin/sessionvariables.php';


//save cart payment record
if (isset($_POST['SaveCartPaymentDetails'])) {
    $CartMainId = SECURE($_POST['CartMainId'], "d");

    $cart_payments  = [
        "CartMainId" => $CartMainId,
        "CartPaymentMode" => $_POST['CartPaymentMode'],
        "CartPaidAmount" => $_POST['CartPaidAmount'],
        "CartPaymentRefNo" => $_POST['CartPaymentRefNo'],
        "CartPaymentStatus" => $_POST['CartPaymentStatus'],
        "CartPaymentDetails" => SECURE($_POST['CartPaymentDetails'], "e"),
        "CartPaymentDate" => $_POST['CartPaymentDate'],
        "CartPaymentSource" => $_POST['CartPaymentSource']
    ];

    $CheckExistingPaymentRecord = CHECK("SELECT * FROM invoice_cart_payments where CartMainId='$CartMainId'");
    if ($CheckExistingPaymentRecord == false) {
        $Response = INSERT("invoice_cart_payments", $cart_payments);
    } else {
        $Response = false;
    }
    RESPONSE($Response, "Payment record saved successfully!", "Unable to save payment record at the moment!");

    //update payment record
} elseif (isset($_POST['UpdatePaymentDetails'])) {
    $CartPaymentId = SECURE($_POST['CartPaymentId'], "d");

    $cart_payments  = [
        "CartPaymentMode" => $_POST['CartPaymentMode'],
        "CartPaidAmount" => $_POST['CartPaidAmount'],
        "CartPaymentRefNo" => $_POST['CartPaymentRefNo'],
        "CartPaymentStatus" => $_POST['CartPaymentStatus'],
        "CartPaymentDetails" => SECURE($_POST['CartPaymentDetails'], "e"),
        "CartPaymentDate" => $_POST['CartPaymentDate'],
        "CartPaymentSource" => $_POST['CartPaymentSource']
    ];
    $Response = UPDATE_DATA("invoice_cart_payments", $cart_payments, "CartPaymentId='$CartPaymentId'");
    RESPONSE($Response, "Payment updated successfully!", "Unabl to update payment record at the moment!");

    //save payment record for invoices
} elseif (isset($_POST['SavePaymentDetails'])) {

    RequestHandler(
        INSERT("invoice_payments", [
            "MainInvoiceId" => SECURE($_POST['MainInvoiceId'], "d"),
            "InvoicePaymentMode" => $_POST['InvoicePaymentMode'],
            "InvoicePaymentSource" => $_POST['InvoicePaymentSource'],
            "InvoicePaymentRefNo" => $_POST['InvoicePaymentRefNo'],
            "InvoicePaymentDate" => $_POST['InvoicePaymentDate'],
            "InvoicePaidAmount" => $_POST['InvoicePaidAmount'],
            "InvoicePaymentNotes" => SECURE($_POST['InvoicePaymentNotes'], "e"),
            "InvoicePaymentUpdatedAt" => RequestDataTypeDateTime,
            "InvoicePaymentStatus" => $_POST['InvoicePaymentStatus']
        ]),
        [
            "true" => "Payment record added successfully!",
            "false" => "Unable to add payment record at the moment!"
        ]
    );

    //update payment record for invoices
} elseif (isset($_POST['UpdateInvoicePaymentDetails'])) {
    RequestHandler(
        UPDATE_DATA("invoice_payments", [
            "InvoicePaymentMode" => $_POST['InvoicePaymentMode'],
            "InvoicePaymentSource" => $_POST['InvoicePaymentSource'],
            "InvoicePaymentRefNo" => $_POST['InvoicePaymentRefNo'],
            "InvoicePaymentDate" => $_POST['InvoicePaymentDate'],
            "InvoicePaidAmount" => $_POST['InvoicePaidAmount'],
            "InvoicePaymentNotes" => SECURE($_POST['InvoicePaymentNotes'], "e"),
            "InvoicePaymentUpdatedAt" => RequestDataTypeDateTime,
            "InvoicePaymentStatus" => $_POST['InvoicePaymentStatus']
        ], "InvoicePaymentId='" . SECURE($_POST['InvoicePaymentId'], "d") . "'"),
        [
            "true" => "Payment record updated successfully!",
            "false" => "Unable to update payment record at the moment!"
        ],
    );
}
