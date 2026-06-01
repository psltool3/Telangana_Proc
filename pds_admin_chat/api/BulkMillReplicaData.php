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
    "Incoming Min Paddy" => "incoming_min_paddy",
    "Total Rice Inventory" => "total_rice_inventory",
    "Milling Capacity" => "milling_capacity",
    "Minimum Outgoing Rice" => "minimum_outgoing_rice",
	"Active/Not-Active" => "active"
];

// Reverse mapping
$reverseMapData = array_flip($mapData);

$person = new Login;
$person->setUsername($_POST["username"]);
$Encryption = new Encryption();
$person->setPassword($Encryption->decrypt($_POST["password"], $nonceValue));

if($_SESSION['user']!=$person->getUsername()){
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
        $incoming_min_paddy = -7;
        $total_rice_inventory = -8;
        $milling_capacity = -9;
        $minimum_outgoing_rice = -10;

		$active = -14;
		while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
			if($i>0){
				if($district<0 or $to_district<0 or $name<0 or $id<0 or $type<0 or $latitude<0 or $longitude<0 or $incoming_min_paddy<0 or $total_rice_inventory<0 or $milling_capacity<0 or $minimum_outgoing_rice<0 or $active<0){
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
				}
				if (!is_numeric($column[$latitude]) || $column[$latitude] >= 40) {
					echo "Error : Latitude must be less than 40. Given: " . $column[$latitude];
					echo "</br>";
					$redirect = 0;
				}

				// Longitude check (must be more than 65)
				if (!is_numeric($column[$longitude]) || $column[$longitude] <= 65) {
					echo "Error : Longitude must be more than 65. Given: " . $column[$longitude];
					echo "</br>";
					$redirect = 0;
				}
                if (
					!isset($column[$name]) ||
					!preg_match('/^[a-zA-Z0-9_\- ]+$/', $column[$name])
				) {
					echo "Error: Name should only contain characters, numbers, underscores, hyphens, and spaces: " . ($column[$name] ?? 'Missing');
					echo "<br>";
					$redirect = 0;
				}

                if (
					!isset($column[$id]) ||
					!preg_match('/^[a-zA-Z0-9_\-]+$/', $column[$id])
				) {
					echo "Error: ID should only contain characters, numbers, underscores, and hyphens (no spaces): " . ($column[$id] ?? 'Missing');
					echo "<br>";
					$redirect = 0;
				}	
				
                if (!is_numeric($column[$incoming_min_paddy]) || !is_numeric($column[$milling_capacity])) {
                    echo "Error : Incoming Min Paddy and Milling Capacity must be numeric.";
                    echo "</br>";
                    $redirect = 0;
                }
				 elseif ((float)$column[$incoming_min_paddy] >= (float)$column[$milling_capacity]) {
                    echo "Error : Milling Capacity must be greater than Incoming Min Paddy.";
                    echo "</br>";
                    $redirect = 0;
                }
                if (!is_numeric($column[$total_rice_inventory])) {
                    echo "Error : Total Rice Inventory must be numeric.";
                    echo "</br>";
                    $redirect = 0;
                }
                if (!is_numeric($column[$minimum_outgoing_rice])) {
                    echo "Error : Minimum Outgoing Rice must be numeric.";
                    echo "</br>";
                    $redirect = 0;
                }
			}
			else{
				$column[0] = preg_replace('/^\xEF\xBB\xBF/', '', $column[0]);
				for($j=0;$j<count($column);$j++){
					switch(trim($column[$j])){
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
                        case $reverseMapData["incoming_min_paddy"]:
                            $incoming_min_paddy = $j;
                            break;
                        case $reverseMapData["total_rice_inventory"]:
                            $total_rice_inventory = $j;
                            break;
                        case $reverseMapData["milling_capacity"]:
                            $milling_capacity = $j;
                            break;
                        case $reverseMapData["minimum_outgoing_rice"]:
                            $minimum_outgoing_rice = $j;
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
            $incoming_min_paddy = -7;
            $total_rice_inventory = -8;
            $milling_capacity = -9;
            $minimum_outgoing_rice = -10;
			$active = -14;
			while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
				if($i>0){
					if($district<0 or $to_district<0 or $name<0 or $id<0 or $type<0 or $latitude<0 or $longitude<0 or $incoming_min_paddy<0 or $total_rice_inventory<0 or $milling_capacity<0 or $minimum_outgoing_rice<0 or $active<0){
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
                    
                    $MillReplica->setIncomingMinPaddy($column[$incoming_min_paddy]);
                    $MillReplica->setTotalRiceInventory($column[$total_rice_inventory]);
                    $MillReplica->setMillingCapacity($column[$milling_capacity]);
                    $MillReplica->setMinimumOutgoingRice($column[$minimum_outgoing_rice]);
                    
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
						writeLog("User ->" ." Mill Inter Added -> ". $_SESSION['user'] . "| " . $MillReplica->getName());
						$query_add = $MillReplica->insert($MillReplica);
						mysqli_query($con, $query_add);
					}
					else{
						echo "Error : Mill Inter with id ".$MillReplica->getId()." Already Exist</br>";
						$redirect = 2;
					}
				}
				else{
					$column[0] = preg_replace('/^\xEF\xBB\xBF/', '', $column[0]);
					for($j=0;$j<count($column);$j++){
						switch(trim($column[$j])){
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
                            case $reverseMapData["incoming_min_paddy"]:
                                $incoming_min_paddy = $j;
                                break;
                            case $reverseMapData["total_rice_inventory"]:
                                $total_rice_inventory = $j;
                                break;
                            case $reverseMapData["milling_capacity"]:
                                $milling_capacity = $j;
                                break;
                            case $reverseMapData["minimum_outgoing_rice"]:
                                $minimum_outgoing_rice = $j;
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
