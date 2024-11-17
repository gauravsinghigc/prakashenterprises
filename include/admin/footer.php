<div id="footer" class="app-footer m-0">
    <div class="time-block" hidden="">
        <span><i class="fa fa-clock-o pl-1"></i> </span>
        <span id="clock"> 8:10:45</span>
        <span> | </span>
        <span class="date"><?php echo date("d D M, Y"); ?></span>
    </div>
    <script>
        setInterval(showTime, 1000);

        function showTime() {
            let time = new Date();
            let hour = time.getHours();
            let min = time.getMinutes();
            let sec = time.getSeconds();
            am_pm = "AM ";

            if (hour > 12) {
                hour -= 12;
                am_pm = " PM";
            }
            if (hour == 0) {
                hr = 12;
                am_pm = " AM";
            }

            hour = hour < 10 ? "0" + hour : hour;
            min = min < 10 ? "0" + min : min;
            sec = sec < 10 ? "0" + sec : sec;

            let currentTime = hour + ":" +
                min + ":" + sec + " " + am_pm + "";

            document.getElementById("clock")
                .innerHTML = "&nbsp;" + currentTime + " ";

            //show reminder at reminder time
            var RunningTime = hour + ":" + min + am_pm;
            document.getElementsByClassName("showcurrenttimevalue").value = hour + ":" + min;
            document.getElementsByClassName("showcurrenttimehtml").innerHTML = hour + ":" + min;

            //birthday date checking
            const today = new Date();
            const yyyy = today.getFullYear();
            let mm = today.getMonth() + 1; // Months start at 0!
            let dd = today.getDate();

            if (dd < 10) dd = '0' + dd;
            if (mm < 10) mm = '0' + mm;

            const formattedToday = dd + '-' + mm;

            var Birthdaydate = "<?php echo DATE_FORMATE("d-m", FETCH("SELECT * FROM users where UserId='" . LOGIN_UserId . "'", "UserDateOfBirth")); ?>";
            var Runningdate = "" + formattedToday + "";
            if (Birthdaydate == "" + formattedToday + "") {
                document.getElementById("birthday_pop_up").style.display = "block";
                document.getElementById("birthday_sound").play();
            }
        }
        showTime();
    </script>
    <div class="birthday-list" id="BirthdayBox" style="display:none;">
        <h5 class="bold">Today Birthdays : <i class="fa fa-cake text-danger"></i> <?php echo DATE("d M, Y"); ?></h5>
        <div class="birth-scroll-area">
            <ul>
                <?php $fetchBirthdays = FETCH_TABLE_FROM_DB("SELECT * FROM users where DATE(UserDateOfBirth) like '%" . date('m-d') . "%'", true);
                if ($fetchBirthdays != null) {
                    $Birthdays = true;
                    foreach ($fetchBirthdays as $BirthdayUsers) {
                        $userid = $BirthdayUsers->UserId;
                ?>
                        <li class="flex-s-b">
                            <span class="w-15">
                                <img src="<?php echo STORAGE_URL_D; ?>/tool-img/cake-run.gif" class="img-fluid p-2">
                            </span>
                            <span class="w-85">
                                <H6 CLASS="mb-1 mt-1"> <?php echo $BirthdayUsers->UserFullName; ?></H6>
                            </span>
                        </li>
                    <?php }
                } else {
                    $Birthdays = false; ?>
                    <li style="list-style-image:url('<?php echo STORAGE_URL_D; ?>/tool-img/cake-run-2.gif');">No Birthday Found!</li>
                <?php } ?>
            </ul>
        </div>
        <h6 class="bold mt-2">Total Birthdays : <i class="fa fa-cake text-danger"></i> <?php echo TOTAL("SELECT * FROM users where DATE(UserDateOfBirth) like '%" . date('m-d') . "%'"); ?> Birthdays</h6>
    </div>
    <?php if ($Birthdays == true) { ?>
        <section class="birthday-box">
            <a onclick="Databar('BirthdayBox')">
                <img src="<?php echo STORAGE_URL_D; ?>/tool-img/cake-run.gif" class="cake">
                <img src="<?php echo STORAGE_URL_D; ?>/tool-img/text.gif" class="text">
            </a>
        </section>
    <?php } ?>
    <footer class=" main-footer">
        Copyrighted &copy; <?php echo date("Y"); ?> | <?php echo DEVELOPED_BY; ?>
    </footer>
</div>

<?php
function folderSize($path)
{

    $size = 0;
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $file) {
        $size += $file->getSize();
    }

    if ($size <= 1024) {
        $size = $size . " Bytes";
    } elseif (($size < 1048576) && ($size > 1023)) {
        $size = round($size / 1024, 2) . " KB";
    } elseif (($size < 1073741824) && ($size > 1048575)) {
        $size = round($size / 1048576, 2) . " MB";
    } else {
        $size = round($size / 1073741824, 2) . " GB";
    }

    return $size;
}
define("PROJECT_SIZE", folderSize(__DIR__ . "/../.."));

if (CONTROL_WORK_ENV == "DEV") {
    //development mode and report hanlder
    //check dev data
    $CheckDevStartTime = CHECK("SELECT * FROM configurations where configurationname='DEV_START_TIME'");
    if ($CheckDevStartTime == null) {
        $data = array(
            "configurationname" => "DEV_START_TIME",
            "configurationvalue" => RequestDataTypeDateTime,
        );
        $Save = INSERT("configurations", $data);
    } else {
        define("DEV_START_TIME", CONFIG("DEV_START_TIME"));
    }

    $CheckDevRunTime = CHECK("SELECT * FROM configurations where configurationname='DEV_RUN_TIME'");
    if ($CheckDevRunTime == null) {
        $data = array(
            "configurationname" => "DEV_RUN_TIME",
            "configurationvalue" => RequestDataTypeDateTime,
        );
        $Save = INSERT("configurations", $data);
    } else {
        define("DEV_RUN_TIME", CONFIG("DEV_RUN_TIME"));
    }

    //update run time
    $UpdateDevRunTime = UPDATE("UPDATE configurations SET configurationvalue='" . RequestDataTypeDateTime . "' where configurationname='DEV_RUN_TIME'");

    //Run dev track reports on daily basis
    $SQL = "CREATE TABLE dev_track_logs (
    DevTrackId INT(11) NOT NULL AUTO_INCREMENT,
    DevTrackIpAddress VARCHAR(255) NOT NULL,
    DevTrackDate DATE NOT NULL,
    DevTrackStartTime TIME NOT NULL,
    DevTrackEndTime TIME NOT NULL,
    PRIMARY KEY (DevTrackId)
    )";

    $CHECK_SQL = CHECK("SHOW TABLES LIKE 'dev_track_logs'");
    if ($CHECK_SQL == null) {
        $Save = SELECT($SQL);
    } else {
        $CheckCurrentDayTrackEntry = CHECK("SELECT * FROM dev_track_logs where DevTrackIpAddress='" . IP_ADDRESS . "' and DATE(DevTrackDate)='" . RequestDataDate . "'");
        if ($CheckCurrentDayTrackEntry == null) {
            $data = array(
                "DevTrackIpAddress" => IP_ADDRESS,
                "DevTrackDate" => RequestDataTypeDate,
                "DevTrackStartTime" => RequestDataTime,
                "DevTrackEndTime" => RequestDataTime
            );
            $Insert = INSERT("dev_track_logs", $data);
        } else {
            $data = array(
                "DevTrackEndTime" => RequestDataTime
            );

            $Update = UPDATE_DATA("dev_track_logs", $data, "DATE(DevTrackDate)='" . RequestDataDate . "'");
        }
    }

    //get total development hours
    //current development hours 
    $TodayStartTime = FETCH("SELECT * FROM dev_track_logs where DATE(DevTrackDate)='" . RequestDataDate . "'", "DevTrackStartTime");
    $TodayEndTime = FETCH("SELECT * FROM dev_track_logs where DATE(DevTrackDate)='" . RequestDataDate . "'", "DevTrackEndTime");

    define("TODAY_START_TIME", $TodayStartTime);
    define("TODAY_END_TIME", $TodayEndTime);
}

if (CONTROL_WORK_ENV == "DEV") {
?>
    <div style="position:fixed; width:100%;bottom:0px;z-index:99999; right:0px;border-top-style:groove;border-width:thin;line-height:0rem !important;">
        <div class="col-md-12 pt-2 pb-1 bg-white" style="line-height:0rem !important;">
            <p class="fs-10 text-center text-danger pb-0 mb-2" style="line-height:0rem !important;"><i class='fa fa-gear fa-spin'></i> Development mode is <b>enabled</b>, for hiding this change development mode into production. you can change it from <b>configuration</b>. dev mode keeps development time and some reports about development.</p>
            <p class='text-center fs-11 pb-0 mb-2' style="line-height:0rem !important;">
                <span><b>Started On:</b> <?php echo DATE_FORMATE("d M, Y h:i A", DEV_START_TIME); ?></span> |
                <span><b>Today Dev Time:</b> <?php echo GetHours(TODAY_START_TIME, TODAY_END_TIME); ?> hr</span> |
                <span><b>Total Dev Time:</b> <?php echo GetDays(DEV_START_TIME); ?> Days <?php echo GetHours(DATE_FORMATE("H:i:s", TODAY_START_TIME), date("H:i:s")); ?> hr </span> |
                <span><b>Latest :</b> <?php echo DATE_FORMATE("d M, Y h:i A", DEV_RUN_TIME); ?></span> |
                <span><b>Storage :</b> <?php echo PROJECT_SIZE; ?></span>
            </p>
        </div>
    </div>
<?php
} ?>