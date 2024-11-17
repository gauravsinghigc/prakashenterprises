<section class="popup-form" id="AddNewItems">
  <div class="popup-form-container">
    <div class="sys-bg flex-s-b p-2 app-heading">
      <h5 class="mb-0 mt-1 ml-3">Add New Items</h5>
      <a href="#" onclick="Databar('AddNewItems')" class='btn btn-danger btn-sm'><i class="fa fa-times"></i></a>
    </div>
    <div class="popup-form-body">
      <form action="<?php echo CONTROLLER; ?>/products.php" method="POST" enctype="multipart/form-data">
        <?php
        $ProductLabel = "Product";
        FormPrimaryInputs(true); ?>
        <div class="row">
          <div class="col-md-7">
            <div class="row">
              <div class="form-group col-lg-7 col-md-7 col-sm-7 col-12">
                <label><?php echo $ProductLabel; ?> Name</label>
                <input type="text" name="ProductName" class="form-control" required="">
              </div>
              <div class="form-group col-lg-5 col-md-5 col-sm-5 col-6">
                <label> Manufacturer/Brand</label>
                <input type="text" name="ProductBrandName" list="ProductBrandName" class="form-control">
                <?php SUGGEST("products", "ProductBrandName", "ASC"); ?>
              </div>
            </div>
            <div class="row mb-5px">
              <div class="form-group col-lg-4 col-md-4 col-sm-6 col-6">
                <label> Modal No</label>
                <input type="modalno" name="ProductModalNo" class="form-control">
              </div>
              <div class="form-group col-lg-4 col-md-4 col-sm-6 col-6">
                <label> Type</label>
                <input type="text" name="ProductType" list="ProductType" class="form-control">
                <?php SUGGEST("products", "ProductType", "ASC"); ?>
              </div>
              <div class="form-group col-lg-4 col-md-4 col-sm-6">
                <label>Speciality</label>
                <input type="text" name="ProductCapacity" class="form-control">
              </div>
            </div>

            <div class="row mb-5px">
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-6">
                <label> Life (In Years)</label>
                <input type="number" name="ProductLife" class="form-control">
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-6">
                <label> Warranty in Months</label>
                <input type="number" name="ProductWarrantyinMonths" class="form-control">
              </div>
            </div>

            <div class="row mb-5px">
              <div class="form-group col-lg-4 col-md-4 col-sm-4 col-6">
                <label> Sale Price</label>
                <input type="text" name="ProductSalePrice" id="ProductSalePrice" oninput="CalculateGSTPrice()" class="form-control">
              </div>
              <div class="form-group col-lg-4 col-md-4 col-sm-4 col-6">
                <label> MRP</label>
                <input type="text" name="ProductMrp" id="mrp" class="form-control">
              </div>
              <div class="form-group col-lg-4 col-md-4 col-sm-4 col-6">
                <label> Application GST</label>
                <select class="form-control" name="ProductApplicableTaxes" id="GstValue" onchange="CalculateGSTPrice()">
                  <?php InputOptions(["0", "5", "7", "10", "12", "15", "18", "20", "25", "28", "30"], "28"); ?>
                </select>
              </div>
              <div class="form-group col-lg-4 col-md-4 col-sm-4 col-6">
                <label> Net Price With GST</label>
                <input type="text" name="ProductNetPayable" id="Netprice" class="form-control" readonly="">
              </div>
            </div>

            <div class="row mb-5px">
              <div class="form-group col-md-12">
                <label>Other Information</label>
                <textarea name="ProductDescription" class="form-control editor" rows="5"></textarea>
              </div>
            </div>



          </div>
          <div class="col-lg-5">

            <div class="p-2 row">
              <label for="UploadFiles" class="pointer">
                <img src="<?php echo STORAGE_URL_D; ?>/tool-img/upload-img.png" class="img-fluid">
              </label>
              <input type="FILE" id="UploadFiles" name="ProductImages[]" value="null" hidden="" accept="image/*" multiple="">
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="gallery"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="row mb-10px mb-20px">
          <div class="form-group col-lg-12 col-md-12 col-12 text-right">
            <button class="btn btn-md btn-success" type="submit" name="SaveProducts"><i class="fa fa-check-circle"></i> Save Products</button>
            <button class="btn btn-md btn-default" type="reset"><i class="fa fa-refresh"></i> Reset</button>
            <button class="btn btn-md btn-default" onclick="Databar('AddNewItems')" type="button"><i class="fa fa-refresh"></i> Cancel</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
<script>
  function CalculateGSTPrice() {
    var GstValue = document.getElementById("GstValue");
    var Netprice = document.getElementById("Netprice");
    var ProductSalePrice = document.getElementById("ProductSalePrice");
    var mrp = document.getElementById("mrp");

    if (GstValue.value == 0) {
      Netprice.value = ProductSalePrice.value;
      mrp.value = +ProductSalePrice.value + 2599;
    } else {
      Netprice.value = (+ProductSalePrice.value * (+GstValue.value / 100)) + +ProductSalePrice.value;
      mrp.value = +ProductSalePrice.value + 2599;
    }
  }
</script>
<script>
  $(function() {
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {

      if (input.files) {
        var filesAmount = input.files.length;

        for (i = 0; i < filesAmount; i++) {
          var reader = new FileReader();

          reader.onload = function(event) {
            $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
          }

          reader.readAsDataURL(input.files[i]);
        }
      }

    };

    $('#UploadFiles').on('change', function() {
      imagesPreview(this, 'div.gallery');
    });
  });
</script>