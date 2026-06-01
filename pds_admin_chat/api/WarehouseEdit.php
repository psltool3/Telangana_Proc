<?php

require('../util/Connection.php');
require('../structures/Warehouse.php');
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

if(!isStringNumber($_POST["requirement"])){
	echo "Error : Check Requirement Value";
	exit();
}

if(!isStringNumber($_POST["storage_capacity"])){
	echo "Error : Check Storage Capacity Value";
	exit();
}
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



if (!empty($errors)) {
	echo implode("</br>", $errors);
	exit();
}
$errors = [];

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


$allowed_motorable = ['motorable', 'non motorable', 'nonmotorable', 'non-motorable'];
if (!in_array(strtolower(trim($_POST["type"])), $allowed_motorable)) {
    echo "Error : Motorable/Non-Motorable should be either Motorable or Non Motorable.";
    exit();
}
$dbHashedPassword = $row['password'];
if(password_verify($person->getPassword(), $dbHashedPassword)){
    
    $district = formatName($_POST["district"]);
    $latitude = $_POST["latitude"];
    $longitude = $_POST["longitude"];
    $name = formatName($_POST["name"]);
    $id = $_POST["id"];
    $type = $_POST["type"];
    $requirement = $_POST["requirement"];
    $storage_capacity = $_POST["storage_capacity"];
    $warehousetype = $_POST["warehousetype"];
    $uniqueid = $_POST["uniqueid"];
    $active = $_POST["active"];

    $Warehouse = new Warehouse;
    $Warehouse->setUniqueid($uniqueid);
    $Warehouse->setDistrict($district);
    $Warehouse->setLatitude($latitude);
    $Warehouse->setLongitude($longitude);
    $Warehouse->setName($name);
    $Warehouse->setId($id);
    $Warehouse->setType($type);
    $Warehouse->setRequirement($requirement);
    $Warehouse->setStorageCapacity($storage_capacity);
    $Warehouse->setWarehousetype($warehousetype);
    $Warehouse->setActive($active);

    $query_check = $Warehouse->checkInsert($Warehouse);
    $query_result = mysqli_query($con, $query_check);
    $numrows = mysqli_num_rows($query_result);
    if($numrows!=0){
        $row = mysqli_fetch_assoc($query_result);
        $uniqueid_check = $row["uniqueid"];
        if($uniqueid!=$uniqueid_check){
            echo "Error : in updating data as Warehouse id already exist ID: ".$id;
            echo "</br>";
            exit();
        }
    }

    $query = $Warehouse->update($Warehouse);
    mysqli_query($con, $query);

    mysqli_close($con);
	
	$filteredPost = $_POST;
	unset($filteredPost['username'], $filteredPost['password']);
	writeLog("User ->" ." Warehouse Edit ->". $_SESSION['user'] . "| Requested JSON -> " . json_encode($filteredPost));

    echo "<script>window.location.href = '../Warehouse.php';</script>";
} 
else{
    echo "Error : Password or Username is incorrect";
}

?>
<?php require('Fullui.php');  ?>