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

require('Header.php');

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
    return is_numeric($stringValue);
}

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
$numrows = mysqli_num_rows($result);


if(!isValidCoordinate($_POST["latitude"],'latitude') or !isValidCoordinate($_POST["longitude"],'longitude')){
	echo "Error : Check Latitude and Longitude Value";
	exit();
}

if (
    !isValidCoordinate($_POST["latitude"], 'latitude') ||
    !isValidCoordinate($_POST["longitude"], 'longitude') ||
    $_POST["latitude"] >= 40 ||
    $_POST["longitude"] <= 65
) {
    echo "Error : Latitude must be less than 40 and Longitude must be greater than 65";
    exit();
}

$errors = [];

// Incoming Min Paddy
if (
    !is_numeric($_POST["incoming_min_paddy"]) ||
    !is_numeric($_POST["milling_capacity"]) ||
    $_POST["incoming_min_paddy"] >= $_POST["milling_capacity"]
) {
    $errors[] = "Error : Milling Capacity must be greater than Incoming Min Paddy";
}

// Total Rice Inventory
if (!is_numeric($_POST["total_rice_inventory"])) {
    $errors[] = "Error : Total Rice Inventory must be numeric";
}

// Minimum Outgoing Rice
if (!is_numeric($_POST["minimum_outgoing_rice"])) {
    $errors[] = "Error : Minimum Outgoing Rice must be numeric";
}

// If any error exists, print all and stop
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo $error . "<br>";
    }
    exit();
}

// Validate Name
if (!preg_match('/^[a-zA-Z0-9_\- ]+$/', $_POST["name"])) {
    echo "Error : Name should only contain characters, numbers, underscores, hyphens, and spaces.";
    exit();
}

// Validate ID
if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $_POST["id"])) {
    echo "Error : ID should only contain characters, numbers, underscores, and hyphens (no spaces).";
    exit();
}






$dbHashedPassword = $row['password'];
if(password_verify($person->getPassword(), $dbHashedPassword)){
    
    $district = formatName($_POST["district"]);
    $to_district = formatName($_POST["to_district"]);
    $latitude = $_POST["latitude"];
    $longitude = $_POST["longitude"];
    $name = formatName($_POST["name"]);
    $id = $_POST["id"];
    $type = $_POST["type"];
    
    $incoming_min_paddy = $_POST["incoming_min_paddy"];
    $total_rice_inventory = $_POST["total_rice_inventory"];
    $milling_capacity = $_POST["milling_capacity"];
    $minimum_outgoing_rice = $_POST["minimum_outgoing_rice"];
    
    $uniqueid = $_POST["uniqueid"];
    $active = $_POST["active"];

    $MillReplica = new MillReplica;
    $MillReplica->setUniqueid($uniqueid);
    $MillReplica->setDistrict($district);
    $MillReplica->setToDistrict($to_district);
    $MillReplica->setLatitude($latitude);
    $MillReplica->setLongitude($longitude);
    $MillReplica->setName($name);
    $MillReplica->setId($id);
    $MillReplica->setType($type);
    
    $MillReplica->setIncomingMinPaddy($incoming_min_paddy);
    $MillReplica->setTotalRiceInventory($total_rice_inventory);
    $MillReplica->setMillingCapacity($milling_capacity);
    $MillReplica->setMinimumOutgoingRice($minimum_outgoing_rice);
    
    $MillReplica->setActive($active);

    $query_check = $MillReplica->checkEdit($MillReplica);
    $query_result = mysqli_query($con, $query_check);
    $numrows = mysqli_num_rows($query_result);
    if($numrows!=0){
        $row = mysqli_fetch_assoc($query_result);
        $uniqueid_check = $row["uniqueid"];
        if($uniqueid!=$uniqueid_check){
            echo "Error : in updating data as MillReplica id already exist ID: ".$id;
            echo "</br>";
            exit();
        }
    }

    $query = $MillReplica->update($MillReplica);
    mysqli_query($con, $query);

    mysqli_close($con);
	
	$filteredPost = $_POST;
	unset($filteredPost['username'], $filteredPost['password']);
	writeLog("User ->" ." MillReplica Edit ->". $_SESSION['user'] . "| Requested JSON -> " . json_encode($filteredPost));

    echo "<script>window.location.href = '../MillReplica.php';</script>";
} 
else{
    echo "Error : Password or Username is incorrect";
}

?>
<?php require('Fullui.php');  ?>
