<?php
/**
 * This file is useful if you want to connect RigBro to
 * third party SMS/email alert services, such as
 * alertbot, site24x7, pingdom, etc.
 *
 * When your rig array is in the alarm state (as defined in your status.php page),
 * this file will output HTTP status code 500, which should trigger
 * downtime for the service you're using. If 500 doesn't trigger an alert,
 * use a different service. Otherwise, this page will return status code 200
 * and output OK.
 */

ob_start();
include (dirname(__FILE__) . "/status.php");
// catch status page output for analysis
$html = ob_get_clean();

if ($in_alarm_state || strlen($html) == 0) {
    header("HTTP/1.1 500 Internal Server Error");
} else {
    echo "OK";
}

?>