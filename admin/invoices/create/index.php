<?php
$Dir = "../../..";
require $Dir . '/require/modules.php';
require $Dir . '/require/admin/access-control.php';
require $Dir . '/require/admin/sessionvariables.php';

//pagevariables
$PageName = "Create Invoices";
$PageDescription = "Manage all customers";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?php echo $PageName; ?> | <?php echo APP_NAME; ?></title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta name="keywords" content="<?php echo APP_NAME; ?>">
    <meta name="description" content="<?php echo SHORT_DESCRIPTION; ?>">
    <?php include $Dir . "/include/admin/header_files.php"; ?>
    <script type="text/javascript">
        function SidebarActive() {
            document.getElementById("customers").classList.add("active");
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

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="flex-s-b">
                                                <h6 class="app-heading mb-0 w-pr-60 m-r-3">Select Customers</h6>
                                                <a href="#" onclick="Databar('AddNewCustomers')" class='btn btn-sm bg-danger w-pr-40'><i class='fa fa-plus'></i> Customer</a>
                                            </div>
                                            <form class="mt-3">
                                                <input type="search" id="searching" oninput="SearchData('searching', 'customer-data')" class="form-control form-control-sm" placeholder="Search Customers">
                                            </form>
                                            <div class="data-box mt-2 pt-1">
                                                <?php
                                                $AllData = FETCH_TABLE_FROM_DB("SELECT * FROM users ORDER by UserFullName ASC", true);
                                                if ($AllData != null) {
                                                    foreach ($AllData as $Data) {
                                                ?>
                                                        <a href="?customer_id=<?php echo $Data->UserId; ?>" class="customer-data text-black">
                                                            <div class='flex-s-b shadow-sm'>
                                                                <div class='w-pr-20 text-center pl-1 pt-1'>
                                                                    <img src="https://icons.veryicon.com/png/o/miscellaneous/two-color-icon-library/user-286.png" class="img-fluid d-flex mx-auto mt-3">
                                                                </div>
                                                                <p class="p-2 mb-1 w-pr-80">
                                                                    <span class="bold"><?php echo $Data->UserFullName; ?></span><br>
                                                                    <span class="text-danger small"><i class='fa fa-hospital'></i> <?php echo $Data->UserCompanyName; ?></span><br>
                                                                    <span class="">
                                                                        <span><?php echo $Data->UserPhoneNumber; ?></span><br>
                                                                        <span><?php echo $Data->UserEmailId; ?></span><br>
                                                                        <span>
                                                                        </span>
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        </a>
                                                <?php
                                                    }
                                                } else {
                                                    NoData("No Customer Found!");
                                                } ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="flex-s-b mb-3">
                                                <h6 class="app-heading mb-0 w-pr-30 m-r-3">Products</h6>
                                                <a href="#" onclick="Databar('AddNewItems')" class='btn btn-sm bg-danger w-pr-30'><i class='fa fa-plus'></i> Products</a>
                                                <a href="#" onclick="Databar('AddNewSerialNo')" class='btn btn-sm bg-danger w-pr-30'><i class='fa fa-plus'></i> SerialNo</a>
                                            </div>
                                            <?php if (isset($_GET['customer_id'])) {
                                                $customerid = $_GET['customer_id']; ?>
                                                <form>
                                                    <input type="search" id="pro-searching" oninput="SearchData('pro-searching', 'product-data')" class="form-control form-control-sm" placeholder="Search Products...">
                                                </form>
                                                <div class="data-box mt-2 pt-1">
                                                    <?php
                                                    $AllSerialNo = FETCH_TABLE_FROM_DB("SELECT * FROM product_serial_no where ProuctSerialNoStatus='ACTIVE' ORDER BY ProductSerialNoId DESC", true);
                                                    $SerialNo = SERIAL_NO;
                                                    if ($AllSerialNo != null) {
                                                        foreach ($AllSerialNo as $SerialNo) {
                                                    ?>
                                                            <form class="product-data shadow-sm p-2 mb-2 small" action="<?php echo CONTROLLER; ?>/InvoiceController.php" method="POST">
                                                                <?php FormPrimaryInputs(true, [
                                                                    "InvoiceCartMainUserId" => $customerid,
                                                                    "InvoiceCartMainItemId" => $SerialNo->ProductMainProId,
                                                                    "InvoiceCartSalePrice" => FETCH("SELECT * FROM products where ProductID='" . $SerialNo->ProductMainProId . "'", "ProductSalePrice"),
                                                                    "InvoiceCartTaxPercentage" => FETCH("SELECT * FROM products where ProductID='" . $SerialNo->ProductMainProId . "'", "ProductApplicableTaxes"),
                                                                    "InvoiceCartItemSerialNo" => $SerialNo->ProductSerialNo
                                                                ]); ?>
                                                                <p class="mb-0">
                                                                    <span class="bold h5"><?php echo FETCH("SELECT * FROM products where ProductID='" . $SerialNo->ProductMainProId . "'", "ProductName"); ?></span><br>
                                                                    <span class="text-gray">Serial No :</span> <?php echo $SerialNo->ProductSerialNo; ?><br>
                                                                    <span class="text-gray">Category :</span> <?php echo FETCH("SELECT * FROM products where ProductID='" . $SerialNo->ProductMainProId . "'", "ProductCapacity"); ?><br>
                                                                    <span class="text-gray">Type :</span> <?php echo FETCH("SELECT * FROM products where ProductID='" . $SerialNo->ProductMainProId . "'", "ProductType"); ?><br>
                                                                    <span class="mt-2 mb-4 text-right w-100">
                                                                        <span class="bold fs-15"><?php echo Price($Price = FETCH("SELECT * FROM products where ProductID='" . $SerialNo->ProductMainProId . "'", "ProductSalePrice"), "text-primary", "Rs."); ?></span> |
                                                                        <span class="fs-12">GST : +<?php echo FETCH("SELECT * FROM products where ProductID='" . $SerialNo->ProductMainProId . "'", "ProductApplicableTaxes"); ?>%</span>
                                                                        <span class='pull-right'>
                                                                            <?php
                                                                            if (FETCH("SELECT * FROM products where ProductID='" . $SerialNo->ProductMainProId . "'", "ProductApplicableTaxes") == "Null") {
                                                                                echo Price($Price, "text-success fs-17 bold", "Rs.");
                                                                            } else {
                                                                                $TaxAmount = $Price / 100 * FETCH("SELECT * FROM products where ProductID='" . $SerialNo->ProductMainProId . "'", "ProductApplicableTaxes");
                                                                                echo Price($Price + $TaxAmount, "text-success fs-17 bold", "Rs.");
                                                                            } ?>
                                                                        </span>
                                                                    </span>
                                                                </p>
                                                                <div class="flex-s-b mt-1">
                                                                    <?php
                                                                    $CheckItem = CHECK("SELECT * FROM invoice_cart where InvoiceCartMainUserId='" . $customerid . "' and InvoiceCartItemSerialNo='" . $SerialNo->ProductSerialNo . "'");
                                                                    if ($CheckItem == false) {
                                                                    ?>
                                                                        <button name="AddToCart" class="btn btn-sm btn-success w-100"><i class='fa fa-shopping-cart'></i> Add to cart</button>
                                                                    <?php } else { ?>
                                                                        <span class="btn btn-sm btn-warning w-50"><i class='fa fa-check'></i> Saved into cart</span>
                                                                    <?php
                                                                        CONFIRM_DELETE_POPUP(
                                                                            "item_list",
                                                                            [
                                                                                "remove_cart_items" => true,
                                                                                "control_id" => $SerialNo->ProductSerialNo,
                                                                                "userid" => $customerid
                                                                            ],
                                                                            "InvoiceController",
                                                                            "<i class='fa fa-trash'></i>",
                                                                            "btn btn-sm btn-danger m-l-5 w-50"
                                                                        );
                                                                    } ?>
                                                                </div>
                                                            </form>
                                                    <?php
                                                            include $Dir . "/include/forms/UpdateItemDetails.php";
                                                        }
                                                    } ?>
                                                </div>
                                            <?php } else { ?>
                                                <?php NoData("<i class='fa fa-warning text-danger'></i> Please Select Customer first!"); ?>
                                            <?php } ?>
                                        </div>
                                        <div class=" col-md-6">
                                            <h6 class="app-heading">Invoice Preview</h6>
                                            <?php if (isset($_GET['customer_id'])) {
                                                $customerid = $_GET['customer_id']; ?>
                                                <h6 class="bold mb-1">Billing To :</h6>
                                                <hr class="mb-1 mt-1">
                                                <?php
                                                $AllData = FETCH_TABLE_FROM_DB("SELECT * FROM users where UserId='$customerid' ORDER by UserFullName ASC", true);
                                                if ($AllData != null) {
                                                    foreach ($AllData as $Data) {
                                                        $BillingAddressSql = "SELECT * FROM user_addresses where UserAddressType='BILLING' and UserAddressUserId='" . $Data->UserId . "'";
                                                        $ShippingAddressSql = "SELECT * FROM user_addresses where UserAddressType='SHIPPING' and UserAddressUserId='" . $Data->UserId . "'";
                                                ?>
                                                        <div class="text-black">
                                                            <div class="p-2 shadow-sm mb-0">
                                                                <p class='mb-1'>
                                                                    <span class="bold h4"><?php echo $Data->UserFullName; ?></span><br>
                                                                    <span>
                                                                        <span><i class='fa fa-phone text-success'></i> <?php echo $Data->UserPhoneNumber; ?></span><br>
                                                                        <span><i class='fa fa-envelope text-danger'></i> <?php echo $Data->UserEmailId; ?></span><br>
                                                                        <span>
                                                                            <span class='mb-1'><b>Billing Address:</b>
                                                                                <?php
                                                                                echo SECURE(FETCH($BillingAddressSql, "UserStreetAddress"), "d") . " ";
                                                                                echo FETCH($BillingAddressSql, "UserLocality") . " ";
                                                                                echo FETCH($BillingAddressSql, "UserCity") . " ";
                                                                                echo FETCH($BillingAddressSql, "UserState") . " ";
                                                                                echo FETCH($BillingAddressSql, "UserCountry") . " - " . FETCH($BillingAddressSql, "UserPincode");
                                                                                ?>
                                                                            </span>
                                                                        </span><br>
                                                                        <span>
                                                                            <span class='mb-1'><b>Shipping Address:</b>
                                                                                <?php
                                                                                echo SECURE(FETCH($ShippingAddressSql, "UserStreetAddress"), "d") . " ";
                                                                                echo FETCH($ShippingAddressSql, "UserLocality") . " ";
                                                                                echo FETCH($ShippingAddressSql, "UserCity") . " ";
                                                                                echo FETCH($ShippingAddressSql, "UserState") . " ";
                                                                                echo FETCH($ShippingAddressSql, "UserCountry") . " - " . FETCH($ShippingAddressSql, "UserPincode");
                                                                                ?>
                                                                            </span>
                                                                        </span>
                                                                    </span>
                                                                </p>
                                                                <a href="#" onclick="Databar('ucpid')" class="btn btn-xs btn-primary m-1"><i class='fa fa-edit'></i> Edit Details</a>
                                                                <a href="#" onclick="Databar('ucad')" class="btn btn-xs btn-primary m-1"><i class='fa fa-edit'></i> Update Address</a>
                                                            </div>
                                                        </div>
                                                <?php
                                                        include $Dir . "/include/forms/UpdateCustomerAddressDetails.php";
                                                        include $Dir . "/include/forms/UpdateCustomerPrimaryInfo.php";
                                                    }
                                                } else {
                                                    NoData("No Customer Found!");
                                                } ?>
                                                <h6 class="bold mt-2 mb-1">Selected Items :</h6>
                                                <hr class="mb-1 mt-1">
                                                <div class="mt-1">
                                                    <?php
                                                    $CartItems = FETCH_TABLE_FROM_DB("SELECT * FROM invoice_cart where InvoiceCartMainUserId='$customerid'", true);
                                                    if ($CartItems != null) {
                                                        $TotalPayable = 0;
                                                        foreach ($CartItems as $Item) {
                                                            $TotalPayable += $Item->InvoiceCartNetPrice;
                                                    ?>
                                                            <p class="data-list flex-s-b">
                                                                <span class="w-pr-40">
                                                                    <span class='title'>ItemName</span><br>
                                                                    <span class=""><?php echo html_entity_decode($Item->InvoiceCartProductDescription); ?></span>
                                                                </span>
                                                                <span>
                                                                    <span class="title">SalePrice</span><br>
                                                                    <span><?php echo Price($Item->InvoiceCartSalePrice, "text-black", "Rs."); ?></span>
                                                                </span>
                                                                <span>
                                                                    <span class="title">Qty</span><br>
                                                                    <span>x <?php echo $Item->InvoiceCartQuantity; ?></span>
                                                                </span>
                                                                <span>
                                                                    <span class="title">Total</span><br>
                                                                    <span>= Rs.<?php echo (int)$Item->InvoiceCartQuantity * (int)$Item->InvoiceCartSalePrice; ?></span>
                                                                </span>
                                                                <span>
                                                                    <span class="title">GST</span><br>
                                                                    <span>
                                                                        <?php
                                                                        $Tax = $Item->InvoiceCartTaxPercentage;
                                                                        if ($Tax == "Null") {
                                                                            echo "0";
                                                                        } else {
                                                                            echo "+" . $Tax . "% <span class='text-gray'>";
                                                                            echo " (Rs." . (int)round($Item->InvoiceCartSalePrice / 100 * $Tax) * (int)$Item->InvoiceCartQuantity . ") </span>";
                                                                        } ?>
                                                                    </span>
                                                                </span>
                                                                <span>
                                                                    <span class="title">NetPrice</span><br>
                                                                    <span><?php echo Price($Item->InvoiceCartNetPrice, "text-success", "Rs."); ?></span>
                                                                </span>
                                                            </p>
                                                        <?php
                                                        }
                                                        $CheckExistingPaymentRecord = CHECK("SELECT * FROM invoice_cart_payments where CartMainId='" . $customerid . "'");
                                                        ?>
                                                        <p class="data-list flex-s-b bg-warning">
                                                            <span></span>
                                                            <span class='h6 mb-0 bold p-1'>
                                                                <span>Net Payable :</span>
                                                                <span><?php echo Price($TotalPayable, "bold text-success", "Rs."); ?></span>
                                                            </span>
                                                        </p>
                                                        <h6 class="bold mt-3">Payment Details :
                                                            <?php if ($CheckExistingPaymentRecord == true) { ?>
                                                                <a href="#" onclick="Databar('UpdatePaymentRecord')" class="btn-dark btn-xs btn pull-right mt-1"><i class='fa fa-edit'></i> Edit Details</a>
                                                            <?php } else { ?>
                                                                <a href="#" onclick="Databar('AddPaymentDetails')" class="btn-danger btn-xs btn pull-right mt-1"><i class='fa fa-plus'></i> Add Payment Details</a>
                                                            <?php } ?>
                                                        </h6>
                                                        <hr>
                                                        <?php if ($CheckExistingPaymentRecord == true) {
                                                            $PaymentSql = "SELECT * FROM invoice_cart_payments where CartMainId='" . $customerid . "'"; ?>
                                                            <p class="data-list flex-s-b">
                                                                <span>
                                                                    <span class="title">Payment Mode:</span><br>
                                                                    <span><?php echo FETCH($PaymentSql, "CartPaymentMode"); ?></span>
                                                                </span>
                                                                <span>
                                                                    <span class="title">Payment Source:</span><br>
                                                                    <span><?php echo FETCH($PaymentSql, "CartPaymentSource"); ?></span>
                                                                </span>
                                                                <span>
                                                                    <span class="title">Ref No:</span><br>
                                                                    <span><?php echo FETCH($PaymentSql, "CartPaymentRefNo"); ?></span>
                                                                </span>
                                                                <span>
                                                                    <span class="title">Payment Date:</span><br>
                                                                    <span><?php echo DATE_FORMATE("d M, Y", FETCH($PaymentSql, "CartPaymentDate")); ?></span>
                                                                </span>
                                                                <span>
                                                                    <span class="title">Paid Amount:</span><br>
                                                                    <span><?php echo Price($Paid = FETCH($PaymentSql, "CartPaidAmount"), "text-success", "Rs."); ?></span>
                                                                </span>
                                                                <span>
                                                                    <span class="title">Balance Amount:</span><br>
                                                                    <span><?php echo Price($Balance = (int)$TotalPayable - (int)$Paid, "text-danger", "Rs."); ?></span>
                                                                </span>
                                                            </p>
                                                            <p class="text-right small">
                                                                <span class="small bold">Paying <?php echo PriceInWords($Paid); ?></span>
                                                            </p>

                                                            <div class="row">
                                                                <div class="col-md-12 text-right">
                                                                    <a href="#" onclick="Databar('AddNewInvoice')" class="btn btn-md btn-success"><i class='fa fa-check'></i> Create Invoice</a>
                                                                </div>
                                                            </div>
                                                        <?php
                                                            include $Dir . "/include/forms/UpdateCartPaymentRecord.php";
                                                        }  ?>

                                                    <?php
                                                    } else {
                                                        NoData("Shopping cart is empty!");
                                                    } ?>
                                                </div>
                                            <?php } else { ?>
                                                <?php NoData("<i class='fa fa-warning text-danger'></i> Please Select Customer first!"); ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
        </div>

        <?php
        include $Dir . "/include/forms/AddCustomer.php";
        include $Dir . "/include/forms/AddCartPaymentRecord.php";
        include $Dir . "/include/forms/AddInvoiceRecord.php";
        include $Dir . "/include/forms/AddNewItem.php";
        include $Dir . "/include/forms/AddNewSerialNo.php";
        include $Dir . "/include/admin/footer.php"; ?>
    </div>

    <?php include $Dir . "/include/admin/footer_files.php"; ?>

</body>

</html>