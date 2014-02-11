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