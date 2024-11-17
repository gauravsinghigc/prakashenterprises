<?php


//function for lead call reminders
function DisplayReminder($REQ_LeadsId)
{
}

//function display lead id
function LEADID($id)
{
    echo "LEADID00" . $id;
}

//FUNCTION get Leads call duration in seconds
function GetLeadsCallDurations($LeadId)
{
    $fetchAllFollowUpTimes = FETCH_TABLE_FROM_DB("SELECT * FROM lead_followup_durations, lead_followups where lead_followup_durations.LeadCallFollowUpMainId=lead_followups.LeadFollowUpId and lead_followups.LeadFollowMainId='$LeadId'", true);
    if ($fetchAllFollowUpTimes == null) {
        $NetSeconds = 0;
    } else {
        $NetSeconds = 0;
        foreach ($fetchAllFollowUpTimes as $CallTime) {

            //get duration in seconds
            $timestamp1 = strtotime($CallTime->leadcallendat);
            $timestamp2 = strtotime($CallTime->leadcallstartat);
            $GetSeconds = $timestamp1 - $timestamp2;
            $GetSeconds = round(abs($GetSeconds));

            $NetSeconds += $GetSeconds;
        }
    }
    return $NetSeconds;
}

//FUNCTION get Leads call duration in seconds
function GetLeadsFollowUpDurations($FollowUpId)
{
    $fetchAllFollowUpTimes = FETCH_TABLE_FROM_DB("SELECT * FROM lead_followup_durations where lead_followup_durations.LeadCallFollowUpMainId='$FollowUpId'", true);
    if ($fetchAllFollowUpTimes == null) {
        $NetSeconds = 0;
    } else {
        $NetSeconds = 0;
        foreach ($fetchAllFollowUpTimes as $CallTime) {

            //get duration in seconds
            $timestamp1 = strtotime($CallTime->leadcallendat);
            $timestamp2 = strtotime($CallTime->leadcallstartat);
            $GetSeconds = $timestamp1 - $timestamp2;
            $GetSeconds = round(abs($GetSeconds));

            $NetSeconds += $GetSeconds;
        }
    }
    return $NetSeconds;
}

//totoal calls
function TotalCalls($REQ_LeadsId)
{
    $Calls =   TOTAL("SELECT * FROM lead_followups where LeadFollowMainId='$REQ_LeadsId'");
    if ($Calls == 0) {
        $results = "0 Calls";
    } else {
        $results = $Calls . " Calls";
    }

    return $results;
}
