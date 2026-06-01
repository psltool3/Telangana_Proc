<?php
require('../util/Connection.php');
require('../structures/MillReplica.php');
require('../util/SessionFunction.php');
require('../structures/Login.php');
require('../util/Security.php');
require ('../util/Encryption.php');
require('../util/Logger.php');
$nonceValue = 'nonce_value';

if(!SessionCheck()){
	return;
}

$mapData = [
    "District" => "district",
     "To District" => "to_district",
    "Name of Mill Inter" => "name",
    "Mill Inter ID" => "id",
    "Mill Inter Type" => "type",
    "Latitude" => "latitude",
    "Longitude" => "longitude",
    "Incoming Min Paddy" => "incoming_min_paddy",
    "Total Rice Inventory" => "total_rice_inventory",
    "Milling Capacity" => "milling_capacity",
    "Minimum Outgoing Rice" => "minimum_outgoing_rice",
	"Active/Not-Active" => "active"
];

// Reverse mapping
$reverseMapData = array_flip($mapData);

// Excel file name for download 
$fileName = "MillReplicaTemplate_" . date('d-m-Y') . ".csv"; 

// Headers for download 
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $fileName . '"');
header('Cache-Control: max-age=0'); // no cache

// Display column names as first row 
echo implode(",", array_keys($mapData)) . "\n";

exit();
?>
