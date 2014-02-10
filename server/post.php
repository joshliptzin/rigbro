<?php
/**
 * Parses posted rig data, sets the received timestamp
 * and saves a file to disk for each rig contained in
 * the post data.
 *
 * Filename is of the form <rig name>.txt and is saved
 * in rig-data/ directory.
 */
$rig_data_dir = dirname(__FILE__) . "/rig-data";
$posted = file_get_contents("php://input");
$decoded = json_decode($posted, true);
if (is_array($decoded)) {
    foreach ($decoded as &$rig) {
        if ($rig["name"]) {
            $rig["ts"] = time();
            file_put_contents(
                $rig_data_dir."/".$rig["name"].".txt", json_encode($rig));
        }
    }
}

?>