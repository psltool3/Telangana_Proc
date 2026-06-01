<?php
require('../util/Connection.php');
require('../structures/District.php');
require('../util/SessionFunction.php');
require('../structures/Login.php');

if(!SessionCheck()){
	return;
}

$warehouse = array();
$fps = array();
$warehouse_optimised = array();

$allocation = 0;
$qkm = 0;
$qkm_optimised = 0;
$averagedistance = 0;

function addUnique($value, &$array) {
    if (!in_array($value, $array)) {
        $array[] = $value;
    }
	return;
}

$month_full = $_POST['month'];
$district = $_POST['district'];

$parts = explode('_', $month_full);

$year = $parts[0]; 
$month = $parts[1];
$day = $parts[2];
$query = "SELECT * FROM optimised_table WHERE month='$month' AND year='$year' AND day='$day'";
$result = mysqli_query($con,$query);
$numrow = mysqli_num_rows($result);
$id = "";
if($numrow>0){
	$row = mysqli_fetch_assoc($result);
	$id = $row['id'];
}

$tablename = "optimiseddata_".$id;

$query = "SHOW TABLES LIKE '$tablename'";
$result = $con->query($query);

if ($result && $result->num_rows > 0) {
	$warehouse_cache = array();
	$query_all_warehouses = "SELECT id, latitude, longitude, district FROM warehouse";
	$result_all_warehouses = mysqli_query($con, $query_all_warehouses);
	if($result_all_warehouses) {
		while($wh_row = mysqli_fetch_assoc($result_all_warehouses)) {
			$warehouse_cache[$wh_row['id']] = $wh_row;
		}
	}

	$query = "SELECT * FROM ".$tablename." WHERE to_district='$district' AND status='implemented'";
	$result = mysqli_query($con,$query);
	$numrows = mysqli_num_rows($result);
	while($row = mysqli_fetch_assoc($result))
	{
		if($row['new_id_admin']!=null or $row['new_id_admin']!=""){
			$wid = $row['new_id_admin'];
			if(isset($warehouse_cache[$wid])){
				$row["from_lat"] = $warehouse_cache[$wid]['latitude'];
				$row["from_long"] = $warehouse_cache[$wid]['longitude'];
				$row["from_district"] = $warehouse_cache[$wid]['district'];
			}
			$row["from_id"] = $row['new_id_admin'];
			$row["from_name"] = $row['new_name_admin'];
			$row["distance"] = $row['new_distance_admin'];
		}
		else if(($row['new_id_district']!=null or $row['new_id_district']!="") and $row['approve_admin']=="yes"){
			$wid = $row['new_id_district'];
			if(isset($warehouse_cache[$wid])){
				$row["from_lat"] = $warehouse_cache[$wid]['latitude'];
				$row["from_long"] = $warehouse_cache[$wid]['longitude'];
				$row["from_district"] = $warehouse_cache[$wid]['district'];
			}
			$row["from_id"] = $row['new_id_district'];
			$row["from_name"] = $row['new_name_district'];
			$row["distance"] = $row['new_distance_district'];
		}
		$data[] = $row;			
	}
	if($numrows==0){
		$data = "";
	}
	$query = "SELECT * FROM ".$tablename." WHERE status='implemented'";
	$result = mysqli_query($con,$query);
	$numrows = mysqli_num_rows($result);
	while($row = mysqli_fetch_assoc($result))
	{		
		addUnique($row["from_id"],$warehouse_optimised);
		$qkm_optimised = $qkm_optimised + ((float)$row["quantity"]) * (float)$row["distance"];
		if($row['new_id_admin']!=null or $row['new_id_admin']!=""){
			$wid = $row['new_id_admin'];
			if(isset($warehouse_cache[$wid])){
				$row["from_lat"] = $warehouse_cache[$wid]['latitude'];
				$row["from_long"] = $warehouse_cache[$wid]['longitude'];
				$row["from_district"] = $warehouse_cache[$wid]['district'];
			}
			$row["from_id"] = $row['new_id_admin'];
			$row["from_name"] = $row['new_name_admin'];
			$row["distance"] = $row['new_distance_admin'];
		}
		else if(($row['new_id_district']!=null or $row['new_id_district']!="") and $row['approve_admin']=="yes"){
			$wid = $row['new_id_district'];
			if(isset($warehouse_cache[$wid])){
				$row["from_lat"] = $warehouse_cache[$wid]['latitude'];
				$row["from_long"] = $warehouse_cache[$wid]['longitude'];
				$row["from_district"] = $warehouse_cache[$wid]['district'];
			}
			$row["from_id"] = $row['new_id_district'];
			$row["from_name"] = $row['new_name_district'];
			$row["distance"] = $row['new_distance_district'];
		}		
		addUnique($row["from_id"],$warehouse);
		addUnique($row["to_id"],$fps);
		$allocation = $allocation + (float)$row["quantity"];
		$qkm = $qkm + ((float)$row["quantity"]) * (float)$row["distance"];
	}
	$averagedistance = $qkm/$allocation;
	$averagedistanceoptimised = $qkm_optimised/$allocation;
	$tableData = array();
	$tableData["WH_Used"] = count($warehouse);
	$tableData["FPS_Used"] = count($fps);
	$tableData["Demand"] = $allocation;
	$tableData["Total_QKM"] = $qkm;
	$tableData["Average_Distance"] = $averagedistance;
	$tableData["Scenario"] = "State Suggested";
	
	$tableData["WH_Used_Optimised"] = count($warehouse_optimised);
	$tableData["Total_QKM_Optimised"] = $qkm_optimised;
	$tableData["Average_Distance_Optimised"] = $averagedistanceoptimised;
	$tableData["Scenario_optimised"] = "Optimised";
	
	$tableData["WH_Used_Baseline"] = '11';
	$tableData["FPS_Used_Baseline"] = '447';
	$tableData["Demand_Baseline"] = '38,183';
	$tableData["Total_QKM_Baseline"] = '5,01,021';
	$tableData["Average_Distance_Baseline"] = '13.122';
	$tableData["Scenario_Baseline"] = "Baseline";
	
	$resultarray["data"] = $data;
	$resultarray["table"] = $tableData;
	echo json_encode($resultarray);
} else {
	$resultarray = [];
	$resultarray["data"] = array();
	$resultarray["table"] = array();
	echo json_encode($resultarray);
}
?>