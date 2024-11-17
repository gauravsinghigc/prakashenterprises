<div class="col-md-12 flex-s-b mt-2 mb-1">
 <div class="">
  <h6 class="mb-0" style="font-size:0.75rem;color:grey;">Page <b><?php echo IfRequested("GET", "view_page", $page, false); ?></b> from <b><?php echo $NetPages; ?> </b> pages <br>Total <b><?php echo $TotalItems; ?></b> Entries</h6>
 </div>
 <div class="flex">
  <span class="mr-1">
   <?php
   if (isset($_GET['view'])) {
    $viewcheck = "&view=" . $_GET['view'];
   } else {
    $viewcheck = "";
   }

   if (isset($_GET['sub_status'])) {
    $sub_statuscheck = "&sub_status=" . $_GET['sub_status'];
   } else {
    $sub_statuscheck = "";
   }

   if (isset($_GET['LeadPersonSubStatus'])) {
    $pagefilter = "&LeadPersonManagedBy=" . $_GET['LeadPersonManagedBy'] . "&LeadPersonSource=" . $_GET['LeadPersonSource'] . "&LeadPriorityLevel=" . $_GET['LeadPriorityLevel'] . "&LeadPersonSubStatus=" . $_GET['LeadPersonSubStatus'] . "&LeadPersonStatus=" . $_GET['LeadPersonStatus'] . "&LeadFollowStatus=" . $_GET['LeadFollowStatus'] . "&LeadPersonFullname=" . $_GET['LeadPersonFullname'] . "&search_true=" . $_GET['search_true'] . "&LeadPersonPhoneNumber=" . $_GET['LeadPersonPhoneNumber'];
   } else {
    $pagefilter = "";
   } ?>
   <a href="?view_page=<?php echo $previous_page; ?><?php echo $viewcheck; ?><?php echo $sub_statuscheck; ?><?php echo $pagefilter; ?>" class="btn btn-sm btn-default"><i class="fa fa-angle-double-left"></i></a>
  </span>
  <form>
   <input type="number" name="view_page" onchange="form.submit()" class="form-control form-control-sm  mb-0" min="1" max="<?php echo $NetPages; ?>" value="<?php echo IfRequested("GET", "view_page", 1, false); ?>">
  </form>
  <span class="ml-1">
   <a href="?view_page=<?php echo $next_page; ?><?php echo $viewcheck; ?><?php echo $sub_statuscheck; ?><?php echo $pagefilter; ?>" class="btn btn-sm btn-default"><i class="fa fa-angle-double-right"></i></a>
  </span>
  <?php if (isset($_GET['view_page'])) { ?>
   <span class="ml-1">
    <a href="index.php" class="btn btn-sm btn-danger mb-0"><i class="fa fa-times m-1"></i></a>
   </span>
  <?php } ?>
 </div>
</div>