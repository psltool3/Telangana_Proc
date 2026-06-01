<?php
require('../util/Connection.php');
require('../structures/MillReplica.php');
require('../util/SessionFunction.php');
require('../structures/Login.php');
require('../util/Logger.php');
require('../util/Security.php');
require ('../util/Encryption.php');
$nonceValue = 'nonce_value';
ini_set('max_execution_time', 3000);
session_start();


require('Header.php');

$mapData = [
    "District" => "district",
    "To District" => "to_district",
    "Name of Mill Inter" => "name",
    "Mill Inter ID" => "id",
    "Mill Inter Type" => "type",
    "Latitude" => "latitude",
    "Longitude" => "longitude",
    "Incoming Minimum of Mota" => "incoming_min_mota",
    "Incoming Minimum of Patla" => "incoming_min_patla",
    "Incoming Minimum of Saran" => "incoming_min_saran",
    "Total Normal Rice (Qtl) Inventory" => "outgoing_min_mota",
    "Total State FRK Rice (Qtl) Inventory" => "outgoing_min_patla",
    "Total Central FRK Rice(Qtl) Inventory" => "outgoing_min_saran",
    "Milling Capacity Mota" => "milling_capacity",
    "Milling Capacity Patla" => "milling_capacity1",
    "Milling Capacity Saran" => "milling_capacity2",
	"Active/Not-Active" => "active"
];

// Reverse mapping
$reverseMapData = array_flip($mapData);

$person = new Login;
$person->setUsername($_POST["username"]);
$Encryption = new Encryption();
$person->setPassword($Encryption->decrypt($_POST["password"], $nonceValue));

if($_SESSION['district_user']!=$person->getUsername()){
	echo "User is logged in with different username and password";
	return;
}

$query = "SELECT * FROM login WHERE username='".$person->getUsername()."'";
$result = mysqli_query($con,$query);
$row = mysqli_fetch_assoc($result);

$dbHashedPassword = $row['password'];
if(password_verify($person->getPassword(), $dbHashedPassword)){
$districts = [];
$query = "SELECT name FROM districts WHERE 1";
$result = mysqli_query($con,$query);
$numrows = mysqli_num_rows($result);
    "To District" => "to_district",
    "Name of Mill Inter" => "name",
    "Mill Inter ID" => "id",
    "Mill Inter Type" => "type",
    "Latitude" => "latitude",
    "Longitude" => "longitude",
    "Incoming Minimum of Mota" => "incoming_min_mota",
    "Incoming Minimum of Patla" => "incoming_min_patla",
    "Incoming Minimum of Saran" => "incoming_min_saran",
    "Total Normal Rice (Qtl) Inventory" => "outgoing_min_mota",
    "Total State FRK Rice (Qtl) Inventory" => "outgoing_min_patla",
    "Total Central FRK Rice(Qtl) Inventory" => "outgoing_min_saran",
    "Milling Capacity Mota" => "milling_capacity",
    "Milling Capacity Patla" => "milling_capacity1",
    "Milling Capacity Saran" => "milling_capacity2",
	"Active/Not-Active" => "active"
];

// Reverse mapping
$reverseMapData = array_flip($mapData);

$person = new Login;
$person->setUsername($_POST["username"]);
$Encryption = new Encryption();
$person->setPassword($Encryption->decrypt($_POST["password"], $nonceValue));

if($_SESSION['district_user']!=$person->getUsername()){
	echo "User is logged in with different username and password";
	return;
}

$query = "SELECT * FROM login WHERE username='".$person->getUsername()."'";
$result = mysqli_query($con,$query);
$row = mysqli_fetch_assoc($result);

$dbHashedPassword = $row['password'];
if(password_verify($person->getPassword(), $dbHashedPassword)){
$districts = [];
$query = "SELECT name FROM districts WHERE 1";
$result = mysqli_query($con,$query);
$numrows = mysqli_num_rows($result);
if($numrows>0){
	while($row=mysqli_fetch_assoc($result)){
		array_push($districts,$row["name"]);
	}
}

function formatName($name) {
	$name = preg_replace('/[^a-zA-Z0-9_\- ]/', '', $name);
    $name = ucwords(strtolower($name));
    return trim($name);
}

function isValidCoordinate($value, $coordinateType) {
    // Check if the value is a number and not a string
    if (!is_numeric($value)) {
        return false;
    }
	
    // Convert the value to a float
    $coordinate = floatval($value);

    // Check if it's latitude or longitude and validate within the range
    switch ($coordinateType) {
        case 'latitude':
            return ($coordinate >= -90 && $coordinate <= 90);
        case 'longitude':
            return ($coordinate >= -180 && $coordinate <= 180);
        default:
            return false;
    }
}

function isStringNumber($stringValue) {
    //return is_numeric($stringValue);
    return true; // Relaxed validation as these might not be purely numeric or need strict number check for now
}

$redirect = 1;

try{
	$fileName = $_FILES["file"]["tmp_name"];
	if ($_FILES["file"]["size"] > 0) {
		$file = fopen($fileName, "r");
		$i = 0;
		$district = -1;
		$to_district = -100;
		$name = -1;
		$id = -2;
		$type = -3;
		$latitude = -5;
		$longitude = -6;
        $incoming_min_mota = -7;
        $incoming_min_patla = -8;
        $incoming_min_saran = -9;
        $outgoing_min_mota = -10;
        $outgoing_min_patla = -11;
        $outgoing_min_saran = -12;
        $milling_capacity = -13;
		$milling_capacity1 = -14;
		$milling_capacity2 = -15;

		$active = -14;
		while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
			if($i>0){
				if($district<0 or $to_district<0 or $name<0 or $id<0 or $type<0 or $latitude<0 or $longitude<0 or $incoming_min_mota<0 or $incoming_min_patla<0 or $incoming_min_saran<0 or $outgoing_min_mota<0 or $outgoing_min_patla<0 or $outgoing_min_saran<0 or $milling_capacity<0 or $milling_capacity1<0 or $milling_capacity2<0 or $active<0){
					echo "Error : You have modified Template Header, please check";
					exit();
				}
				if(!isValidCoordinate($column[$latitude],'latitude') or !isValidCoordinate($column[$longitude],'longitude')){
					echo "Error : Check Latitude and Longitude Value Latitude: ".$column[$latitude]." Longitude: ".$column[$longitude];
					echo "</br>";
					$redirect = 0;
				}

				if(!in_array($column[$district], $districts)){
					echo "Error : Check District Name: ".$column[$district];
					echo "</br>";
					$redirect = 0;
				}
				if(!in_array($column[$to_district], $districts)){
					echo "Error : Check To District Name: ".$column[$to_district];
					echo "</br>";
					$redirect = 0;
				) {
					echo "Error : Incoming Min Mota and Milling Capacity must be numeric. "
					   . "Given Incoming Min Mota: " . $column[$incoming_min_mota]
					   . ", Milling Capacity Mota: " . $column[$milling_capacity];
					echo "</br>";
					$redirect = 0;
				}

				// Check Incoming Min Mota <= Milling Capacity
				elseif ((float)$column[$incoming_min_mota] > (float)$column[$milling_capacity]) {
					echo "Error : Incoming Min Mota (" . $column[$incoming_min_mota] .
						 ") cannot be greater than Milling Capacity Mota (" . $column[$milling_capacity] . ")";
					echo "</br>";
					$redirect = 0;
				}
				
				
				
				if (
					!is_numeric($column[$incoming_min_patla]) ||
					!is_numeric($column[$milling_capacity1])
				) {
					echo "Error : Incoming Min Patla and Milling Capacity Patla must be numeric. "
					   . "Given Incoming Min Patla: " . $column[$incoming_min_patla]
					   . ", Milling Capacity Patla: " . $column[$milling_capacity1];
					echo "</br>";
					$redirect = 0;
				}

				// Check Incoming Min Mota <= Milling Capacity
				elseif ((float)$column[$incoming_min_patla] > (float)$column[$milling_capacity1]) {
					echo "Error : Incoming Min Patla (" . $column[$incoming_min_patla] .
						 ") cannot be greater than Milling Capacity Patla (" . $column[$milling_capacity1] . ")";
					echo "</br>";
					$redirect = 0;
				}
				
				
				// Check Incoming Min Mota & Milling Capacity are numeric
				if (
					!is_numeric($column[$incoming_min_saran]) ||
					!is_numeric($column[$milling_capacity2])
				) {
					echo "Error : Incoming Min Saran and Milling Capacity Saran must be numeric. "
					   . "Given Incoming Min Saran: " . $column[$incoming_min_saran]
					   . ", Milling Capacity Saran: " . $column[$milling_capacity2];
					echo "</br>";
					$redirect = 0;
				}

				// Check Incoming Min Mota <= Milling Capacity
				elseif ((float)$column[$incoming_min_saran] > (float)$column[$milling_capacity2]) {
					echo "Error : Incoming Min Saran (" . $column[$incoming_min_saran] .
						 ") cannot be greater than Milling Capacity Saran (" . $column[$milling_capacity2] . ")";
					echo "</br>";
					$redirect = 0;
				}
				
				
				if(!($column[$active]==0 || $column[$active]==1)){
					echo "Error : Check value of active/inactive column: ".$column[$active];
					echo "</br>";
					$redirect = 0;
				}
			}
			else{
				for($j=0;$j<count($column);$j++){
					switch($column[$j]){
						case $reverseMapData["district"]:
							$district = $j;
							break;
						case $reverseMapData["to_district"]:
							$to_district = $j;
							break;
						case $reverseMapData["latitude"]:
							$latitude = $j;
							break;
						case $reverseMapData["longitude"]:
							$longitude = $j;
							break;
						case $reverseMapData["name"]:
							$name = $j;
							break;
						case $reverseMapData["id"]:
							$id = $j;
							break;
						case $reverseMapData["type"]:
							$type = $j;
							break;
                        case $reverseMapData["incoming_min_mota"]:
                            $incoming_min_mota = $j;
                            break;
                        case $reverseMapData["incoming_min_patla"]:
                            $incoming_min_patla = $j;
                            break;
                        case $reverseMapData["incoming_min_saran"]:
                            $incoming_min_saran = $j;
                            break;
                        case $reverseMapData["outgoing_min_mota"]:
                            $outgoing_min_mota = $j;
                            break;
                        case $reverseMapData["outgoing_min_patla"]:
                            $outgoing_min_patla = $j;
                            break;
                        case $reverseMapData["outgoing_min_saran"]:
                            $outgoing_min_saran = $j;
                            break;
                        case $reverseMapData["milling_capacity"]:
                            $milling_capacity = $j;
                            break;
                        case $reverseMapData["milling_capacity1"]:
                            $milling_capacity1 = $j;
                            break;
                        case $reverseMapData["milling_capacity2"]:
                            $milling_capacity2 = $j;
                            break;
						case $reverseMapData["active"]:
							$active = $j;
							break;
					}
				}
			}
			$i = $i+1;
		}
	}
}
catch(Exception $e){
	echo "Error : Error Please check data in  .csv file";
	exit();
}

if($redirect==0){
	exit();
}

try{
	//if (isset($_POST["submit"])){
		$fileName = $_FILES["file"]["tmp_name"];
		if ($_FILES["file"]["size"] > 0) {
			
			$file = fopen($fileName, "r");
			$i = 0;
			$district = -1;
		$to_district = -100;
			$name = -1;
			$id = -2;
			$type = -3;
			$latitude = -5;
			$longitude = -6;
            $incoming_min_mota = -7;
            $incoming_min_patla = -8;
            $incoming_min_saran = -9;
            $outgoing_min_mota = -10;
            $outgoing_min_patla = -11;
            $outgoing_min_saran = -12;
            $milling_capacity = -13;
            $milling_capacity1 = -14;
            $milling_capacity2 = -15;
			$active = -14;
			while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
				if($i>0){
					if($district<0 or $to_district<0 or $name<0 or $id<0 or $type<0 or $latitude<0 or $longitude<0 or $incoming_min_mota<0 or $incoming_min_patla<0 or $incoming_min_saran<0 or $outgoing_min_mota<0 or $outgoing_min_patla<0 or $outgoing_min_saran<0 or $milling_capacity<0 or $milling_capacity1<0 or $milling_capacity2<0 or $active<0){
						echo "Error : You have modified Template Header, please check";
						exit();
					}
					$MillReplica = new MillReplica;
					$uniqueid = uniqid("MILL_",);
					$MillReplica->setUniqueid(substr($uniqueid,0,15));
					$MillReplica->setDistrict(ucwords(strtolower($column[$district])));
					$MillReplica->setToDistrict(ucwords(strtolower($column[$to_district])));
					$MillReplica->setLatitude($column[$latitude]);
					$MillReplica->setLongitude($column[$longitude]);
					$MillReplica->setName($column[$name]);
					$MillReplica->setId($column[$id]);
					$MillReplica->setType($column[$type]);
                    
                    $MillReplica->setIncomingMinMota($column[$incoming_min_mota]);
                    $MillReplica->setIncomingMinPatla($column[$incoming_min_patla]);
                    $MillReplica->setIncomingMinSaran($column[$incoming_min_saran]);
                    $MillReplica->setOutgoingMinMota($column[$outgoing_min_mota]);
                    $MillReplica->setOutgoingMinPatla($column[$outgoing_min_patla]);
                    $MillReplica->setOutgoingMinSaran($column[$outgoing_min_saran]);
                    $MillReplica->setMillingCapacity($column[$milling_capacity]);
                    $MillReplica->setMillingCapacity1($column[$milling_capacity1]);
                    $MillReplica->setMillingCapacity2($column[$milling_capacity2]);
                    
					$MillReplica->setActive($column[$active]);
					while(true){
						$query_check = $MillReplica->check($MillReplica);
						$query_result = mysqli_query($con, $query_check);
						$numrows = mysqli_num_rows($query_result);
						if($numrows==0){
							break;
						}
						else{
							$uniqueid = uniqid("MILL_",);
							$MillReplica->setUniqueid(substr($uniqueid,0,15));
						}
					}
					$query_insert_check = $MillReplica->checkInsert($MillReplica);
					$query_insert_result = mysqli_query($con, $query_insert_check);
					$numrows_insert = mysqli_num_rows($query_insert_result);
					if($numrows_insert==0){
						writeLog("User ->" ." Mill Inter Added -> ". $_SESSION['district_user'] . "| " . $MillReplica->getName());
						$query_add = $MillReplica->insert($MillReplica);
						mysqli_query($con, $query_add);
					}
					else{
						echo "Error : Mill Inter with id ".$MillReplica->getId()." Already Exist</br>";
						$redirect = 2;
					}
				}
				else{
					for($j=0;$j<count($column);$j++){
						switch($column[$j]){
							case $reverseMapData["district"]:
								$district = $j;
								break;
						case $reverseMapData["to_district"]:
							$to_district = $j;
							break;
							case $reverseMapData["latitude"]:
								$latitude = $j;
								break;
							case $reverseMapData["longitude"]:
								$longitude = $j;
								break;
							case $reverseMapData["name"]:
								$name = $j;
								break;
							case $reverseMapData["id"]:
								$id = $j;
								break;
							case $reverseMapData["type"]:
								$type = $j;
								break;
                            case $reverseMapData["incoming_min_mota"]:
                                $incoming_min_mota = $j;
                                break;
                            case $reverseMapData["incoming_min_patla"]:
                                $incoming_min_patla = $j;
                                break;
                            case $reverseMapData["incoming_min_saran"]:
                                $incoming_min_saran = $j;
                                break;
                            case $reverseMapData["outgoing_min_mota"]:
                                $outgoing_min_mota = $j;
                                break;
                            case $reverseMapData["outgoing_min_patla"]:
                                $outgoing_min_patla = $j;
                                break;
                            case $reverseMapData["outgoing_min_saran"]:
                                $outgoing_min_saran = $j;
                                break;
                            case $reverseMapData["milling_capacity"]:
                                $milling_capacity = $j;
                                break;
                            case $reverseMapData["milling_capacity1"]:
                                $milling_capacity1 = $j;
                                break;
                            case $reverseMapData["milling_capacity2"]:
                                $milling_capacity2 = $j;
                                break;
							case $reverseMapData["active"]:
								$active = $j;
								break;
						}
					}
				}
				$i = $i+1;
			}
			if($redirect==1){
				echo "<script>window.location.href = '../MillReplica.php';</script>";
			}
		}
	//}
	//else{
		//echo "Error Please Select .csv file";
	//}
}
catch(Exception $e){
	echo "Error : Please check data in  .csv file";
}
} 
else{
    echo "Error : Password or Username is incorrect";
}
?>
<?php require('Fullui.php');  ?>
