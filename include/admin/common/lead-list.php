<div class="col-md-12">
  <div class='data-list flex-s-b'>
    <div class='w-pr-5'>
      <span><?php echo $Count; ?></span>
    </div>
    <div class="w-pr-35">
      <span class='text-primary'>

        <a class="w-100 text-primary" href="details/index.php?LeadsId=<?php echo SECURE($leads->LeadsId, "e"); ?>">
          <span class='h6 mt-2'>
            <i class='fa fa-user'></i> <?php echo $leads->LeadSalutations; ?>
            <?php echo LimitText($leads->LeadPersonFullname, 0, 37); ?><br>
          </span>
          <hr class="mb-1 mt-1">
          <span class='btn btn-xs btn-warning fs-10'>
            <i class='fa fa-hashtag'></i>
            <?php
            $ProjectId = FETCH("SELECT * FROM lead_requirements WHERE LeadMainId='$LeadsId'", "LeadRequirementDetails");
            $ProjectName = FETCH("SELECT * FROM products where ProductId='$ProjectId'", "ProductName");
            if ($ProjectId == null) {
              echo "No Requirement";
            } else {
              echo $ProjectName;
            }; ?>
          </span><br>
          <span class='text-black'><i class='fa fa-phone text-success'></i>
            <?php
            if ($leads->LeadPersonPhoneNumber == null) {
              echo "No-Phone";
            } else {
              echo $leads->LeadPersonPhoneNumber;
            } ?><br>
            <span class='text-black'>
              <i class='fa fa-envelope text-danger'></i>
              <?php
              if ($leads->LeadPersonEmailId == null) {
                echo "No-Email";
              } else {
                echo $leads->LeadPersonEmailId;
              } ?>
            </span>
          </span>
        </a>
      </span>
    </div>
    <div class='w-pr-15 text-right'>
      <span class='btn btn-xs btn-default m-1'><?php echo $leads->LeadPriorityLevel; ?></span><br>
      <span class='btn btn-xs btn-info m-1'><?php echo $leads->LeadPersonSource; ?></span>
    </div>
    <div class='w-pr-20'>
      <span class='btn btn-xs btn-success m-1'><?php echo $leads->LeadPersonStatus; ?></span><br>
      <span class='btn btn-xs btn-danger m-1'><?php echo $leads->LeadPersonSubStatus; ?></span>
    </div>
    <div class="w-pr-15" style="line-height:0.85rem !important;">
      <span class='text-grey small'>Managed By</span><br><br>
      <?php echo UserDetails($leads->LeadPersonManagedBy); ?>
    </div>
    <div class='w-pr-38'>
      <span class="flex-s-b p-1">
        <span class='w-100 text-center fs-13 p-1'>
          <i class='fa fa-phone text-success h5 mb-0'></i><br>
          <small><?php echo $CallCounts = TotalCalls($LeadsId); ?></small>
        </span>
        <span class='w-100 text-center fs-13 p-1'>
          <i class='fa fa-clock text-warning h5 mb-0'></i><br>
          <small>
            <?php
            $GetLeadsSeconds = GetLeadsCallDurations($LeadsId);
            $CallDurations = GetDurations($GetLeadsSeconds);
            echo $CallDurations; ?>
          </small>
        </span>
        <span class='w-100 text-center fs-13 p-1'>
          <i class='fa fa-refresh text-danger h5 mb-0'></i><br>
          <small>
            <?php
            echo TOTAL("SELECT * FROM lead_followups where LeadFollowMainId='$LeadsId' and LeadFollowStatus like '%Follow%'");
            ?> followups
          </small>
        </span>
        <span class='w-100 text-center p-1 bg-white'>
          <a href='#' onmouseover="GetInstantTime('displayTime_<?php echo $LeadsId; ?>', 'value')" onclick="Databar('Lead_Update_<?php echo $LeadsId; ?>')" class='btn btn-md btn-default'><i class='fa fa-plus'></i></a>
        </span>
      </span>
    </div>
  </div>
</div>