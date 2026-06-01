<?php

class Mill {
    public $district;
    public $name;
    public $id;
    public $type;
    public $latitude;
    public $longitude;
    public $incoming_min_paddy;
    public $total_rice_inventory;
    public $milling_capacity;
    public $minimum_outgoing_rice;
    public $uniqueid;
    public $active;

    // Getter methods

    public function getDistrict() {
        return $this->district;
    }

    public function getName() {
        return $this->name;
    }

    public function getId() {
        return $this->id;
    }

    public function getType() {
        return $this->type;
    }

    public function getLatitude() {
        return $this->latitude;
    }

    public function getLongitude() {
        return $this->longitude;
    }

    public function getIncomingMinPaddy() {
        return $this->incoming_min_paddy;
    }

    public function getTotalRiceInventory() {
        return $this->total_rice_inventory;
    }

    public function getMillingCapacity() {
        return $this->milling_capacity;
    }

    public function getMinimumOutgoingRice() {
        return $this->minimum_outgoing_rice;
    }

    public function getUniqueid() {
        return $this->uniqueid;
    }

    public function getActive() {
        return $this->active;
    }


    // Setter methods

    public function setDistrict($district) {
        $this->district = $district;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setLatitude($latitude) {
        $this->latitude = $latitude;
    }

    public function setLongitude($longitude) {
        $this->longitude = $longitude;
    }

    public function setIncomingMinPaddy($incoming_min_paddy) {
        $this->incoming_min_paddy = $incoming_min_paddy;
    }

    public function setTotalRiceInventory($total_rice_inventory) {
        $this->total_rice_inventory = $total_rice_inventory;
    }

    public function setMillingCapacity($milling_capacity) {
        $this->milling_capacity = $milling_capacity;
    }

    public function setMinimumOutgoingRice($minimum_outgoing_rice) {
        $this->minimum_outgoing_rice = $minimum_outgoing_rice;
    }

    public function setUniqueid($uniqueid) {
        $this->uniqueid = $uniqueid;
    }

    public function setActive($active) {
        $this->active = $active;
    }

    function insert(Mill $mill){
        return "INSERT INTO mill (district, name, id, type, latitude, longitude, incoming_min_paddy, total_rice_inventory, milling_capacity, minimum_outgoing_rice, uniqueid, active) VALUES ('".$mill->getDistrict()."','".$mill->getName()."','".$mill->getId()."','".$mill->getType()."','".$mill->getLatitude()."','".$mill->getLongitude()."','".$mill->getIncomingMinPaddy()."','".$mill->getTotalRiceInventory()."','".$mill->getMillingCapacity()."','".$mill->getMinimumOutgoingRice()."','".$mill->getUniqueid()."','".$mill->getActive()."')";
    }

    function delete(Mill $mill){
        return "DELETE FROM mill WHERE uniqueid='".$mill->getUniqueid()."'";
    }

    function deleteall(Mill $mill){
        return "DELETE FROM mill WHERE 1";
    }

    function logname(Mill $mill){
        return "SELECT name FROM mill WHERE uniqueid='".$mill->getUniqueid()."'";
    }

    function check(Mill $mill){
        return "SELECT * FROM mill WHERE uniqueid='".$mill->getUniqueid()."'";
    }

    function checkInsert(Mill $mill){
        return "SELECT * FROM mill WHERE LOWER(id)=LOWER('".$mill->getId()."')";
    }

    function checkEdit(Mill $mill){
        return "SELECT * FROM mill WHERE LOWER(id)=LOWER('".$mill->getId()."')";
    }

    function update(Mill $mill){
        return "UPDATE mill SET district = '".$mill->getDistrict()."',name = '".$mill->getName()."',id = '".$mill->getId()."',type = '".$mill->getType()."',latitude = '".$mill->getLatitude()."',longitude = '".$mill->getLongitude()."',incoming_min_paddy = '".$mill->getIncomingMinPaddy()."',total_rice_inventory = '".$mill->getTotalRiceInventory()."',milling_capacity = '".$mill->getMillingCapacity()."',minimum_outgoing_rice = '".$mill->getMinimumOutgoingRice()."',active = '".$mill->getActive()."' WHERE uniqueid = '".$mill->getUniqueid()."'";
    }

    function updateEdit(Mill $mill){
        return "UPDATE mill SET district = '".$mill->getDistrict()."',name = '".$mill->getName()."',type = '".$mill->getType()."',latitude = '".$mill->getLatitude()."',longitude = '".$mill->getLongitude()."',incoming_min_paddy = '".$mill->getIncomingMinPaddy()."',total_rice_inventory = '".$mill->getTotalRiceInventory()."',milling_capacity = '".$mill->getMillingCapacity()."',minimum_outgoing_rice = '".$mill->getMinimumOutgoingRice()."',active = '".$mill->getActive()."' WHERE id = '".$mill->getId()."'";
    }
}

?>
