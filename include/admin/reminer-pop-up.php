<?php
$LeadFollowUpHandleBy = LOGIN_UserId;
$GetReminderdate = FETCH("SELECT * FROM lead_followups where LeadFollowUpRemindStatus='ACTIVE' and LeadFollowUpHandleBy='$LeadFollowUpHandleBy' ORDER BY LeadFollowUpId DESC", "LeadFollowUpDate");
$GetRemindertime = FETCH("SELECT * FROM lead_followups where LeadFollowUpRemindStatus='ACTIVE' and LeadFollowUpHandleBy='$LeadFollowUpHandleBy' ORDER BY LeadFollowUpId DESC", "LeadFollowUpTime");

if ($GetReminderdate == date("Y-m-d")) {
?>
  <section class="follow-up-reminder" style="display:none;" id="reminder_pop_up">
    <div class="reminder-box">
      <div class="container w-pr-70">
        <div class="card p-2">
          <div class="row">
            <div class="col-md-12">
              <h4 class="app-heading">You have <b class='text-white'><?php echo TOTAl("SELECT * FROM lead_followups where LeadFollowUpRemindStatus='ACTIVE' and LeadFollowUpHandleBy='$LeadFollowUpHandleBy' and DATE(LeadFollowUpDate)='" . date('Y-m-d') . "' ORDER BY LeadFollowUpId DESC"); ?></b> Follows Up at the moment!</h4>
              <div style="overflow-y: scroll;height:28vw;" class='p-2'>
                <h5 class="app-sub-heading">Current Follow Up</h5>
                <ul class="calling-list">
                  <?php
                  if (LOGIN_UserType == "ADMIN") {
                    $fetclFollowUps = FETCH_TABLE_FROM_DB("SELECT * FROM lead_followups where LeadFollowUpRemindStatus='ACTIVE' and LeadFollowUpHandleBy='$LeadFollowUpHandleBy' and DATE(LeadFollowUpDate)='" . date('Y-m-d') . "' ORDER BY LeadFollowUpId DESC", true);
                  } else {
                    $LoginId = LOGIN_UserId;
                    $fetclFollowUps = FETCH_TABLE_FROM_DB("SELECT * FROM lead_followups where LeadFollowUpRemindStatus='ACTIVE' and LeadFollowUpHandleBy='$LeadFollowUpHandleBy' and DATE(LeadFollowUpDate)='" . date('Y-m-d') . "' and LeadFollowUpHandleBy='$LoginId' ORDER BY LeadFollowUpId DESC", true);
                  }
                  if ($fetclFollowUps != null) {
                    foreach ($fetclFollowUps as $F) {
                      $LeadsId = $F->LeadFollowMainId; ?>
                      <li>
                        <span class='text-center bg-light text-black rounded'>
                          <span class=''>
                            <?php if (DATE_FORMATE("h:i A", $F->LeadFollowUpUpdatedAt) == "NA") { ?>
                              <span class='h5'>No Call</span><br>
                            <?php } else { ?>
                              <span class="p-t-3">

                                <span class='h4 text-success'><i class='fa fa-phone'></i></span><br>
                                <span class='small'>calling done at</span><br>
                                <span class='h5'> <?php echo DATE_FORMATE("h:i A", $F->LeadFollowUpUpdatedAt); ?></span><br>
                                <span> <?php echo DATE_FORMATE("d M, Y", $F->LeadFollowUpUpdatedAt); ?></span><br>
                                <span>
                                  <?php
                                  $GetSeconds = GetLeadsFollowUpDurations($F->LeadFollowUpId);
                                  $CallDuration = GetDurations($GetSeconds);
                                  echo $CallDuration; ?>
                                </span>
                              </span>
                            <?php } ?>
                          </span>
                        </span>
                        <p style='line-height:1.1rem !important;margin-top:0.7rem !important;'>
                          <a href="<?php echo DOMAIN; ?>/admin/digital/leads/details/index.php?LeadsId=<?php echo SECURE($F->LeadFollowMainId, "e"); ?>&alert=false">
                            <span>
                              <span style="font-size:1.1rem !important;font-weight:600 !important;">
                                <?php echo FETCH("SELECT * FROM leads where LeadsId='" . $F->LeadFollowMainId . "'", "LeadSalutations"); ?>
                                <?php echo FETCH("SELECT * FROM leads where LeadsId='" . $F->LeadFollowMainId . "'", "LeadPersonFullname"); ?><br>
                                <span style="font-size:0.95rem !important;font-weight:500 !important;" class='text-info'>
                                  <i class='fa fa-phone-square'></i> <?php echo FETCH("SELECT * FROM leads where LeadsId='" . $F->LeadFollowMainId . "'", "LeadPersonPhoneNumber"); ?>
                                </span>
                              </span><br>
                              <span class='text-danger bold h6'><?php echo $F->LeadFollowStatus; ?></span>
                              <br>
                              <?php if ($F->LeadFollowStatus == "Follow Up" or $F->LeadFollowStatus == "follow Up" || $F->LeadFollowStatus == "FollowUp" || $F->LeadFollowStatus == "FOLLOW UP") { ?>
                                <?php if (DATE_FORMATE("d M, Y", $F->LeadFollowUpDate) != "No Update") { ?>
                                  <span class='fs-11 text-grey'>
                                    <?php echo $F->LeadFollowCurrentStatus; ?> @
                                    <span class="text-success"><?php echo DATE_FORMATE("d M, Y", $F->LeadFollowUpDate); ?>
                                      <?php echo $F->LeadFollowUpTime; ?>
                                    </span>
                                  </span>
                                <?php } ?>
                                <span class="text-grey">
                                <?php } else { ?>
                                  <span class="text-grey"><?php echo $F->LeadFollowStatus; ?>
                                  <?php } ?>
                                  </span>
                                </span><br>
                                <span style="font-size:0.9rem;">
                                  <span class="text-black"><?php echo $F->LeadFollowUpDescriptions; ?></span>
                                  <br>
                                  <i style="font-size:0.8rem;" class='text-warning pull-right'>By
                                    <?php echo FETCH("SELECT * FROM users where UserId='" . $F->LeadFollowUpHandleBy . "'", "UserFullName"); ?> -
                                  </i>
                                </span>
                            </span>
                          </a>
                        </p>
                      </li>
                  <?php
                    }
                  }
                  ?>
                </ul>
                <h5 class="app-sub-heading">Pending Follow Ups</h5>
                <ul class=" calling-list">
                  <?php
                  if (LOGIN_UserType == "ADMIN") {
                    $fetclFollowUps = FETCH_TABLE_FROM_DB("SELECT * FROM lead_followups where LeadFollowUpRemindStatus='ACTIVE' and DATE(LeadFollowUpDate)>='" . date('Y-m-d') . "' ORDER BY LeadFollowUpId DESC", true);
                  } else {
                    $LoginId = LOGIN_UserId;
                    $fetclFollowUps = FETCH_TABLE_FROM_DB("SELECT * FROM lead_followups where LeadFollowUpRemindStatus='ACTIVE' and DATE(LeadFollowUpDate)>='" . date('Y-m-d') . "' and LeadFollowUpHandleBy='$LoginId' ORDER BY LeadFollowUpId DESC", true);
                  }
                  if ($fetclFollowUps != null) {
                    foreach ($fetclFollowUps as $F) { ?>
                      <li>
                        <span class='text-center bg-light text-black rounded'>
                          <span class=''>
                            <?php if (DATE_FORMATE("h:i A", $F->LeadFollowUpUpdatedAt) == "NA") { ?>
                              <span class='h5'>No Call</span><br>
                            <?php } else { ?>
                              <span class="p-t-3">

                                <span class='h4 text-success'><i class='fa fa-phone'></i></span><br>
                                <span class='small'>calling done at</span><br>
                                <span class='h5'> <?php echo DATE_FORMATE("h:i A", $F->LeadFollowUpUpdatedAt); ?></span><br>
                                <span> <?php echo DATE_FORMATE("d M, Y", $F->LeadFollowUpUpdatedAt); ?></span><br>
                                <span>
                                  <?php
                                  $GetSeconds = GetLeadsFollowUpDurations($F->LeadFollowUpId);
                                  $CallDuration = GetDurations($GetSeconds);
                                  echo $CallDuration; ?>
                                </span>
                              </span>
                            <?php } ?>
                          </span>
                        </span>
                        <p style='line-height:1.1rem !important;margin-top:0.7rem !important;'>
                          <a href="<?php echo DOMAIN; ?>/admin/digital/leads/details/index.php?LeadsId=<?php echo SECURE($F->LeadFollowMainId, "e"); ?>&alert=false">
                            <span>
                              <span style="font-size:1.1rem !important;font-weight:600 !important;">
                                <?php echo FETCH("SELECT * FROM leads where LeadsId='" . $F->LeadFollowMainId . "'", "LeadSalutations"); ?>
                                <?php echo FETCH("SELECT * FROM leads where LeadsId='" . $F->LeadFollowMainId . "'", "LeadPersonFullname"); ?><br>
                                <span style="font-size:0.95rem !important;font-weight:500 !important;" class='text-info'>
                                  <i class='fa fa-phone-square'></i> <?php echo FETCH("SELECT * FROM leads where LeadsId='" . $F->LeadFollowMainId . "'", "LeadPersonPhoneNumber"); ?>
                                </span>
                              </span><br>
                              <span class='text-danger bold h6'><?php echo $F->LeadFollowStatus; ?></span>
                              <br>
                              <?php if ($F->LeadFollowStatus == "Follow Up" or $F->LeadFollowStatus == "follow Up" || $F->LeadFollowStatus == "FollowUp" || $F->LeadFollowStatus == "FOLLOW UP") { ?>
                                <?php if (DATE_FORMATE("d M, Y", $F->LeadFollowUpDate) != "No Update") { ?>
                                  <span class='fs-11 text-grey'>
                                    <?php echo $F->LeadFollowCurrentStatus; ?> @
                                    <span class="text-success"><?php echo DATE_FORMATE("d M, Y", $F->LeadFollowUpDate); ?>
                                      <?php echo $F->LeadFollowUpTime; ?>
                                    </span>
                                  </span>
                                <?php } ?>
                                <span class="text-grey">
                                <?php } else { ?>
                                  <span class="text-grey"><?php echo $F->LeadFollowStatus; ?>
                                  <?php } ?>
                                  </span>
                                </span><br>
                                <span style="font-size:0.9rem;">
                                  <span class="text-black"><?php echo $F->LeadFollowUpDescriptions; ?></span>
                                  <br>
                                  <i style="font-size:0.8rem;" class='text-warning pull-right'>By
                                    <?php echo FETCH("SELECT * FROM users where UserId='" . $F->LeadFollowUpHandleBy . "'", "UserFullName"); ?> -
                                  </i>
                                </span>
                            </span>
                          </a>
                        </p>
                      </li>
                  <?php
                    }
                  }
                  ?>
                </ul>
              </div>
              <br>
              <a href="?alert=true" class="btn btn-sm btn-danger pull-right">Skip & Continue <i class="fa fa-angle-right"></i></a>
            </div>
            <div class='col-md-12'>
              <audio src="<?php echo STORAGE_URL_D; ?>/sys-tone/info.mp3" hidden="" id="alert_sound" loop="loop">
              </audio>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php } else {
  $GetRemindertime = "";
} ?>