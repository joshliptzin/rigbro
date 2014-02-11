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
 * Seconds between status page auto-refresh.
 * Set to 0 for no auto-refresh.
 */
$refresh_interval = 60;

////////////////////////////////////
////                            ////
////    ALARM STATE CONFIG      ////
////                            ////
////////////////////////////////////

/**
 * Number of seconds a rig is down until alarm state is triggered
 */
$seconds_considered_down = 300;

/**
 * The expected MH/s rate for your pool of rigs.
 * Any reading below this value will trigger the alarm state.
 * Set to 0 if you want to turn this alarm trigger off.
 */
$expected_mhs = 1.0;

/**
 * Temps above this value for a GPU will cause its cell
 * to turn red in the table.
 */
$max_gpu_temp = 80;

/**
 * Enable audio alerts when in alarm state?
 */
$enable_audio_alerts = true;

/**
 * Randomly picks an audio alert from this array to play.
 * Only works if $enable_audio_alerts above is true.
 * Use mine or your own. I may have F-bombed in one of them
 * so don't use at work.
 */
$audio_alert_files = array(
    "audio/yomanwhisper.mp3",
    "audio/yomanrigs.mp3"
);


?>