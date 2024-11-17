<section class="popup-form" id="AddNewSerialNo">
    <div class="popup-form-container">
        <div class="sys-bg flex-s-b p-2 app-heading">
            <h5 class="mb-0 mt-1 ml-3">Add New Serial No</h5>
            <a href="#" onclick="Databar('AddNewSerialNo')" class='btn btn-danger btn-sm'><i class="fa fa-times"></i></a>
        </div>
        <div class="popup-form-body">
            <form action="<?php echo CONTROLLER; ?>/products.php" method="POST">
                <?php FormPrimaryInputs(true); ?>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Select Product/ModalNo</label>
                        <select name='ProductMainProId' class='form-control' required=''>
                            <?php
                            $AllProducts = FETCH_TABLE_FROM_DB("SELECT * FROM products where ProductStatus like '%ACTIVE%' ORDER BY ProductName ASC", true);
                            if ($AllProducts == null) {
                                echo "<option value='0'>No Product found!</option>";
                            } else {
                                foreach ($AllProducts as $Product) {
                                    echo "<option value='" . SECURE($Product->ProductID, "e") . "'>" . $Product->ProductName . " @ ModalNo:" . $Product->ProductModalNo . "</option>";
                                }
                            } ?>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Serial No</label>
                        <input type="text" tabindex="1" name="ProductSerialNo" list="ProductSerialNo" class="form-control" placeholder="S No: 98723E86">
                        <?php SUGGEST("product_serial_no", "ProductSerialNo", "ASC"); ?>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Mfg Date</label>
                        <input type="date" name="ProductMfgDate" value="<?php echo date("Y-m-d"); ?>" class="form-control">
                    </div>
                    <div class="col-md-3 mt-2 form-group">
                        <label class="mt-2">&nbsp;<br></label>
                        <button type="submit" name="SaveSerialNumbers" class="btn btn-md btn-success">Add Stock</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>