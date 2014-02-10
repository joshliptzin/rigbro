<?php
/**
 * Retrieves data about each rig listed in the config file
 * directly from CGMiner api, then posts the data to your server
 */
$path = dirname(__FILE__);
include_once($path . '/config.php');
include_once($path . '/cgminer-api.php');


foreach ($rigs as &$rig) {
    $rig['summary'] = request('summary', $rig['ip'], $rig['port']);
    if ($rig['summary'] != null) {
        $rig['devs'] = request('devs', $rig['ip'], $rig['port']);
    }
}

$encoded_data = json_encode($rigs);

// Post the data to your server
$ch = curl_init($post_url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $encoded_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
		'Content-Length: ' . strlen($encoded_data))
);
curl_exec($ch);

?>
