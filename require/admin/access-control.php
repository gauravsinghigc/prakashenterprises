<?php

//session
if (!isset($_SESSION['SYSTEM_LOGIN_USER_ID'])) {
 LOCATION("warning", "You are not login, Please Login First", DOMAIN . "/auth/admin/");
}
