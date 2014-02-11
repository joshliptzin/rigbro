<?php
/**
 * RigBro by Josh Liptzin
 * https://github.com/joshliptzin/rigbro
 * MIT License
 *
 * If you like RigBro, feel free to donate!
 *
 * BTC: 1FRgq7BuM4WBR2N5BUfs8EBbqe2PG8VACf
 * LTC: LWRcxSRMLgHUDNoBYmf6EXvjtT8WtzRGx5
 * DOGE: DB4qMug8FydqpG9wRk7BDbvPg83ThBERFG
 */

////////////////////////////////////
////                            ////
////       Rig Functions        ////
////                            ////
////////////////////////////////////

/**
 * Finds rig data files in the current directory.
 * Opens and decodes all that are found, returning
 * the data in an array.
 */
function getRigData() {
    $data_dir = dirname(__FILE__) . "/rig-data";
    $rigs = array();
    foreach (glob($data_dir . "/*.txt") as $filename) {
        $data = file_get_contents($filename);
        try {
            $decoded = json_decode($data, true);
            if (is_array($decoded)) {
                $rigs[] = $decoded;
            }
        } catch (Exception $e) {
            echo "Failed to decode $filename, check " .
                "the file for valid JSON syntax.";
        }
    }
    return $rigs;
}

/**
 * Returns total Mh/s over 5s for all rigs
 * @param $rigs array rigs
 */
function totalMhs($rigs) {
    $total = 0;
    foreach ($rigs as $rig) {
        // Looks for 5s Mh/s value first
        $data = $rig["summary"]["SUMMARY"];
        if ($data["MHS 5s"] > 0) {
            $total += $data["MHS 5s"];
        } else if ($data["MHS av"]) {
            $total += $data["MHS av"];
        }
    }
    return $total;
}

/**
 * Returns the names of rigs whose status is not "S"
 * Empty array means all rigs appear to be up and alive
 * @param $rigs array rigs
 */
function getDeadRigs($rigs) {
    $dead = array();
    foreach ($rigs as $rig) {
        if ($rig["summary"]["STATUS"]["STATUS"] != "S") {
            $dead[] = $rig["name"];
        }
    }
    return $dead;
}

function isRigAlertStatus($rig) {
    return $rig["summary"]["STATUS"]["STATUS"] != "S";
}

/**
 * Returns the number of seconds since we've last gotten
 * an update from $rig
 * @param $rig
 * @return int seconds
 */
function getRigSecondsSinceUpdate($rig) {
    return time() - $rig["ts"];
}

/**
 * Returns number of seconds rig has been up
 * @param $rig
 * @return int seconds
 */
function getRigUptime($rig) {
    return $rig["summary"]["SUMMARY"]["Elapsed"];
}

/**
 * Returns true if it's been longer than $seconds_considered_down
 * since we've heard from this rig
 * @param $rig
 * @return bool
 */
function isRigDown($rig) {
    global $seconds_considered_down;
    return getRigSecondsSinceUpdate($rig) > $seconds_considered_down;
}

////////////////////////////////////
////                            ////
////       GPU Functions        ////
////                            ////
////////////////////////////////////

/**
 * Returns GPU data array at index $index.
 * Returns null if rig has no GPU for that index.
 * @param $rig
 * @param $index int
 * @return null
 */
function getRigGPUAtIndex($rig, $index) {
    $gpu = $rig["devs"]["GPU{$index}"];
    if (!$gpu) {
        return null;
    }
    return $gpu;
}

function getGPUTemp($gpu) {
    return floatval($gpu["Temperature"]);
}

function getGPUHWErrors($gpu) {
    return intval($gpu["Hardware Errors"]);
}

/**
 * @param $gpu
 * @return bool
 */
function isGPUDown($gpu) {
    return $gpu["Status"] != "Alive";
}

/**
 * @param $gpu
 * @return bool
 */
function isGPUAlarmTemp($gpu) {
    global $max_gpu_temp;
    return getGPUTemp($gpu) > $max_gpu_temp;
}

function isGPUAlarmHW($gpu) {
    return getGPUHWErrors($gpu) > 0;
}
?>