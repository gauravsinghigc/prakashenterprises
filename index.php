<?php
//require modules;
require 'require/modules.php';
require 'require/web-modules.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="<?php echo APP_NAME; ?>">
    <meta name="description" content="<?php echo SHORT_DESCRIPTION; ?>">
    <?php include "include/admin/header_files.php"; ?>
    <title><?php echo APP_NAME; ?> | Home </title>
</head>

<body>
    <?php
    //fetch
    $FetchData = FETCH_TABLE_FROM_DB("SELECT * FROM complaints ORDER BY ComplaintsId DESC", true);
    if ($FetchData != null) {
        foreach ($FetchData as $Data) {
            echo $Data->ComplaintProductId . "-";
            $WarnSql = "SELECT * FROM warranty_products where WarrantyProductSno='" . $Data->ComplaintProductId . "'";
            $pROsQL = "SELECT * FROM product_serial_no where ProductSerialNo='" . $Data->ComplaintProductId . "'";
            $ProductsQL = "SELECT * FROM products where ProductID='" . FETCH($pROsQL, "ProductMainProId") . "'";


            //complaint products
            $complaint_products = [
                "MainComplaintId" => $Data->ComplaintsId,
                "ComplaintProductName" => FETCH($ProductsQL, "ProductName"),
                "ComplaintProductSerialNo" => FETCH($WarnSql, 'WarrantyProductSno'),
                "ComplaintProductModalNo" => FETCH($WarnSql, 'WarrantyProductModalNo'),
                "ComplaintProductMfgDate" => FETCH($WarnSql, 'WarrantyProductMfgDate'),
                "ComplaintProductPurchaseDate" => FETCH($WarnSql, 'WarrantyProductPurchasedate'),
                "ComplaintProductSpeciality" => FETCH($WarnSql, 'WarrantyProductCapacity'),
                "ComplaintProductWarrantyInMonths" => FETCH($WarnSql, 'WarrantyProductMonthWarranty'),
                "ComplaintProductLifeInYears" => FETCH($WarnSql, 'WarrantyProductLife'),
                "ComplaintProductWarrantyExpireAt" => FETCH($WarnSql, 'WarrantyExpireDate'),
                "ComplaintProductWarrantyStatus" => FETCH($WarnSql, 'WarrantyStatus'),
                "ComplaintProductAddedAt" => FETCH($WarnSql, "WarrantyProductCreatedAt"),
                "ComplaintProductUpdatedAt" => FETCH($WarnSql, "WarrantyProductUpdatedAt"),
            ];

            if (CHECK("SELECT * FROM complaint_products where ComplaintProductSerialNo='" . $Data->ComplaintProductId . "'") == null) {
                $Response = INSERT("complaint_products", $complaint_products);
                $Response = "Saved<br>";
                echo $Response;
            } else {
                $Response = "Exits<br>";
                echo $Response;
            }
        }
    }
    header("location: admin");
    ?>
    <?php include "include/admin/footer_files.php"; ?>
</body>

</html>