<?php
/**
 * RigBro by Josh Liptzin
 * https://github.com/joshliptzin/rigbro
 * MIT License
 *
 * If you like RigBro, feel free to donate!
 *
 * BTC: 1898o5diGApYksUkRygUCwVMm9TGwajk8Z
 * LTC: LWRcxSRMLgHUDNoBYmf6EXvjtT8WtzRGx5
 * DOGE: DB4qMug8FydqpG9wRk7BDbvPg83ThBERFG
 */

$path = dirname(__FILE__);
require_once($path . "/config.php");
require_once($path . "/parser.php");

$rigs = getRigData();

$total_mhs = totalMhs($rigs);
$dead_rig_names = getDeadRigs($rigs);

$mhs_alarm = $total_mhs < $expected_mhs;
$dead_rig_alarm = sizeof($dead_rig_names) > 0;

/**
 * The default alarm state is triggered when the MH/s rate
 * is less than expected, or when one rig fails to post data
 * for longer than $seconds_considered_down.
 *
 * You can add code here to add additional alarm triggers, such as a GPU
 * producing HW error or reaching a high temperature.
 */
$in_alarm_state = $mhs_alarm || $dead_rig_alarm;
?>
<!DOCTYPE html>
<html>
<head>
 <title>Rigbro Monitor</title>
<style>
.alarm {
  background-color: red;
  color: white;
  font-weight: bold;
 }
.space {
    height: 6px;
}
</style>
</head>
<body>

<h2>RigBro Array</h2>
<!-- Low MH/s Alert -->
<div class="<?= $mhs_alarm ? "alarm" : "" ?>">
    <h3>Total MH/s: <?= $total_mhs ?> (expected: <?= $expected_mhs ?> MH/s)</h3>
</div>

<!-- Dead Rig Alert -->
<? if ($dead_rig_alarm) { ?>
 <div class="alarm">
  <? foreach ($dead_rig_names as $dr) { ?>
      <h3><?= $dr ?> is probably down - go check it now</h3>
  <? } ?>
 </div>
<? } ?>

<!-- Audio Alert -->
<? if ($enable_audio_alerts && $in_alarm_state) { ?>
    <audio autoplay="autoplay">
        <source src="<?= $audio_alert_files[array_rand($audio_alert_files)] ?>">
    </audio>
<? } ?>

<!-- Rig status table -->
<? foreach($rigs as $rig) { ?>
  <h2><?= $rig["name"] ?></h2>
  <hr/>
 <table width="600" border="1">
 <tr>
  <th>Status</th>
  <th>Last Update</th>
  <th>Uptime</th>
  <th>MH/s 5s</th>
  <th>Mh/s avg</th>
 </tr>
 <tr>
  <td class=""<?= isRigAlertStatus($rig) ? "alarm":"" ?>>
    <?= $rig["summary"]["STATUS"]["STATUS"] ?>
  </td>
  <td class="<?= isRigDown($rig) ? "alarm":"" ?>">
    <?= number_format(getRigSecondsSinceUpdate($rig)) ?> sec
  </td>
  <td><?= number_format(getRigUptime($rig)) ?> sec</td>
  <td><?= $rig["summary"]["SUMMARY"]["MHS 5s"] ?></td>
  <td><?= $rig["summary"]["SUMMARY"]["MHS av"] ?></td>
 </tr>
 </table>
 <div class="space"></div>
 <table width="800" border="1" style="margin-left: 30px;">
  <tr>
   <th>GPU</th>
   <th>Status</th>
   <th>Mh/s 5s</th>
   <th>Mh/s avg</th>
   <th>Temp</th>
   <th>Fan %</th>
   <th>HW Error</th>
   <th>Intensity</th>
  </tr>
  <? for($i = 0;; $i++) {
        $gpu = getRigGPUAtIndex($rig, $i);
         if (!$gpu) break;
  ?>
  <tr>
   <td>GPU <?= $i ?></td>
   <td class="<?= isGPUDown($gpu) ? "alarm":"" ?>">
    <?= $gpu["Status"] ?>
   </td>
   <td><?= $gpu["MHS 5s"] ?></td>
   <td><?= $gpu["MHS av"] ?></td>
   <td class="<?= isGPUAlarmTemp($gpu) ? "alarm":"" ?>">
       <?= getGPUTemp($gpu) ?>
   </td>
   <td><?= $gpu["fan_percent"] ?>%</td>
   <td class="<?= isGPUAlarmHW($gpu) ? "alarm":"" ?>">
       <?= getGPUHWErrors($gpu) ?>
   </td>
   <td><?= $gpu["Intensity"] ?></td>
  </tr>
  <? } ?>
 </table>
<? } ?>

<!-- Auto Refresh -->
<? if ($refresh_interval > 0) { ?>
<script type="text/javascript">
  setTimeout(function() { location.reload(true); }, <?= $refresh_interval ?> * 1000);
</script>
<? } ?>
<hr/>
<h2>Donations Appreciated</h2>
<div>
    If you like RigBro, feel free to donate!
    <br/><br/>
    BTC: 1898o5diGApYksUkRygUCwVMm9TGwajk8Z
    <br/>
    LTC: LWRcxSRMLgHUDNoBYmf6EXvjtT8WtzRGx5
    <br/>
    DOGE: DB4qMug8FydqpG9wRk7BDbvPg83ThBERFG
</div>

</body>
</html>