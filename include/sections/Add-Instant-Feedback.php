<section class="pop-section hidden" id="Lead_Update_<?php echo $LeadsId; ?>">
    <div class="action-window">
        <div class='container'>
            <div class='row'>
                <div class='col-md-12'>
                    <h4 class='app-heading'>Add Feedback</h4>
                </div>
            </div>
            <form action="<?php echo CONTROLLER; ?>/LeadsController.php" method="POST">
                <?php FormPrimaryInputs(true, [
                    "LeadFollowMainId" => $LeadsId
                ]);
                $PageSqls = "SELECT * FROM leads where LeadsId='$LeadsId'"; ?>
                <input type="text" hidden id="leascurrentstatus_<?php echo $LeadsId; ?>" name="LeadFollowCurrentStatus" value="">
                <input type="text" hidden id='displayTime_<?php echo $LeadsId; ?>' name="StartTime" value=''>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Lead Status</label>
                                <select class="form-control form-control-sm" name="LeadFollowStatus" id="statustype_<?php echo $LeadsId; ?>" onchange="CallStatusFunction_<?php echo $LeadsId; ?>()">
                                    <option value="Null">Select Status</option>
                                    <?php
                                    $FetchCallStatus = FETCH_TABLE_FROM_DB("SELECT * FROM configs, config_values where configs.ConfigsId=config_values.ConfigValueGroupId and configs.ConfigGroupName='CALL_STATUS' ORDER BY ConfigValueId DESC", true);
                                    if ($FetchCallStatus != null) {
                                        foreach ($FetchCallStatus as $CallStatus) { ?>
                                            <option value="<?php echo $CallStatus->ConfigValueId; ?>"><?php echo $CallStatus->ConfigValueDetails; ?></option>
                                    <?php
                                        }
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Call Status <span id="display"></span></label>
                                <?php
                                $FetchCallStatus = FETCH_TABLE_FROM_DB(CONFIG_DATA_SQL("CALL_STATUS"), true);
                                if ($FetchCallStatus != null) {
                                    foreach ($FetchCallStatus as $Status) {
                                        if ($Status->ConfigValueId == "52") {
                                            $display = "none";
                                        } else {
                                            $display = "none";
                                        } ?>
                                        <select onchange="GetValue_<?php echo $Status->ConfigValueId; ?>_<?php echo $LeadsId; ?>()" class="form-control form-control-sm" id="view_<?php echo $Status->ConfigValueId; ?>_<?php echo $LeadsId; ?>" style="display:<?php echo $display; ?>;">
                                            <option value="0">Select Call Status</option>
                                            <?php
                                            $FetchCallStatus = FETCH_TABLE_FROM_DB("SELECT * FROM configs, config_values where config_values.ConfigReferenceId='" . $Status->ConfigValueId . "' and configs.ConfigsId=config_values.ConfigValueGroupId and configs.ConfigGroupName='CALL_STATUS_SUB_FIELDS'", true);
                                            if ($FetchCallStatus != null) {
                                                foreach ($FetchCallStatus as $CallStatus) { ?>
                                                    <option value="<?php echo $CallStatus->ConfigValueDetails; ?>"><?php echo $CallStatus->ConfigValueDetails; ?></option>
                                            <?php
                                                }
                                            } ?>
                                        </select>
                                        <script>
                                            function GetValue_<?php echo $Status->ConfigValueId; ?>_<?php echo $LeadsId; ?>() {
                                                var leascurrentstatus_<?php echo $LeadsId; ?> = document.getElementById("leascurrentstatus_<?php echo $LeadsId; ?>")
                                                leascurrentstatus_<?php echo $LeadsId; ?>.value = document.getElementById("view_<?php echo $Status->ConfigValueId; ?>_<?php echo $LeadsId; ?>").value;
                                            }
                                        </script>
                                <?php
                                    }
                                } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="calldesc_<?php echo $LeadsId; ?>">
                                    <div class="form-group">
                                        <label>Call description</label>
                                        <textarea class="form-control form-control-sm" name="LeadFollowUpDescriptions" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Call Type</label>
                                    <select name="LeadFollowUpCallType" id="follow_<?php echo $LeadsId; ?>" class="form-control form-control-sm" required="">
                                        <option value="null">Select Call Type</option>
                                        <option value="incoming">Incoming</option>
                                        <option value="outgoing" selected>Outgoing</option>
                                        <option value="followup">FollowUp</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Lead Priority level </label>
                                    <select class="form-control form-control-sm" name="LeadPriorityLevel">
                                        <?php CONFIG_VALUES("LEAD_PERIORITY_LEVEL", FETCH($PageSqls, "LeadPriorityLevel")); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="call_schedule_<?php echo $LeadsId; ?>" style="display:none;">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>Date</label>
                                    <input type="date" name="LeadFollowUpDate" class="form-control form-control-sm" value="<?php echo date("Y-m-d"); ?>">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Time</label>
                                    <input type="time" name="LeadFollowUpTime" value="<?php echo DATE("H:i", strtotime("+5 min")); ?>" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-12">
                                    <label>Remind Note</label>
                                    <textarea class="form-control form-control-sm" id='remindnote_<?php echo $LeadsId; ?>' name="LeadFollowUpRemindNotes" rows="2"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12 text-right">
                        <button type="submit" name="AddLeadStatus" class="btn btn-md btn-success">Add Status</button>
                        <a href="#" onclick="Databar('Lead_Update_<?php echo $LeadsId; ?>')" class="btn btn-md btn-default mt-3">cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
    function CallStatusFunction_<?php echo $LeadsId; ?>() {
        var statustype_<?php echo $LeadsId; ?> = document.getElementById("statustype_<?php echo $LeadsId; ?>");
        <?php
        $FetchCallStatus = FETCH_TABLE_FROM_DB("SELECT * FROM configs, config_values where configs.ConfigsId=config_values.ConfigValueGroupId and configs.ConfigGroupName='CALL_STATUS' ORDER BY ConfigValueId DESC", true);
        if ($FetchCallStatus != null) {
            foreach ($FetchCallStatus as $CallStatus) { ?>
                if (statustype_<?php echo $LeadsId; ?>.value == <?php echo $CallStatus->ConfigValueId; ?>) {
                    document.getElementById("view_<?php echo $CallStatus->ConfigValueId; ?>_<?php echo $LeadsId; ?>").style.display = "block";

                } else {
                    document.getElementById("view_<?php echo $CallStatus->ConfigValueId; ?>_<?php echo $LeadsId; ?>").style.display = "none";
                }

                if (statustype_<?php echo $LeadsId; ?>.value == "50") {
                    document.getElementById("call_schedule_<?php echo $LeadsId; ?>").style.display = "block";
                    document.getElementById("calldesc_<?php echo $LeadsId; ?>").style.display = "none";
                    document.getElementById("remindnote_<?php echo $LeadsId; ?>").setAttribute("required", true);
                } else {
                    document.getElementById("call_schedule_<?php echo $LeadsId; ?>").style.display = "none";
                    document.getElementById("calldesc_<?php echo $LeadsId; ?>").style.display = "block";
                    document.getElementById("remindnote_<?php echo $LeadsId; ?>").removeAttribute("required");
                }
        <?php }
        } ?>
    }
</script>