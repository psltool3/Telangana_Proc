<?php

class MillReplica {
    public $district;
    public $to_district;
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

    public function getToDistrict() {
        return $this->to_district;
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

    public function setToDistrict($to_district) {
        $this->to_district = $to_district;
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

    function insert(MillReplica $mill_replica){
        return "INSERT INTO mill_replica (district, to_district, name, id, type, latitude, longitude, incoming_min_paddy, total_rice_inventory, milling_capacity, minimum_outgoing_rice, uniqueid, active) VALUES ('".$mill_replica->getDistrict()."','".$mill_replica->getToDistrict()."','".$mill_replica->getName()."','".$mill_replica->getId()."','".$mill_replica->getType()."','".$mill_replica->getLatitude()."','".$mill_replica->getLongitude()."','".$mill_replica->getIncomingMinPaddy()."','".$mill_replica->getTotalRiceInventory()."','".$mill_replica->getMillingCapacity()."','".$mill_replica->getMinimumOutgoingRice()."','".$mill_replica->getUniqueid()."','".$mill_replica->getActive()."')";
    }

    function delete(MillReplica $mill_replica){
        return "DELETE FROM mill_replica WHERE uniqueid='".$mill_replica->getUniqueid()."'";
    }

    function deleteall(MillReplica $mill_replica){
        return "DELETE FROM mill_replica WHERE 1";
    }

    function logname(MillReplica $mill_replica){
        return "SELECT name FROM mill_replica WHERE uniqueid='".$mill_replica->getUniqueid()."'";
    }

    function check(MillReplica $mill_replica){
        return "SELECT * FROM mill_replica WHERE uniqueid='".$mill_replica->getUniqueid()."'";
    }

    function checkInsert(MillReplica $mill_replica){
        return "SELECT * FROM mill_replica WHERE LOWER(id)=LOWER('".$mill_replica->getId()."')";
    }

    function checkEdit(MillReplica $mill_replica){
        return "SELECT * FROM mill_replica WHERE LOWER(id)=LOWER('".$mill_replica->getId()."')";
    }

    function update(MillReplica $mill_replica){
        return "UPDATE mill_replica SET district = '".$mill_replica->getDistrict()."',to_district = '".$mill_replica->getToDistrict()."',name = '".$mill_replica->getName()."',id = '".$mill_replica->getId()."',type = '".$mill_replica->getType()."',latitude = '".$mill_replica->getLatitude()."',longitude = '".$mill_replica->getLongitude()."',incoming_min_paddy = '".$mill_replica->getIncomingMinPaddy()."',total_rice_inventory = '".$mill_replica->getTotalRiceInventory()."',milling_capacity = '".$mill_replica->getMillingCapacity()."',minimum_outgoing_rice = '".$mill_replica->getMinimumOutgoingRice()."',active = '".$mill_replica->getActive()."' WHERE uniqueid = '".$mill_replica->getUniqueid()."'";
    }

    function updateEdit(MillReplica $mill_replica){
        return "UPDATE mill_replica SET district = '".$mill_replica->getDistrict()."',to_district = '".$mill_replica->getToDistrict()."',name = '".$mill_replica->getName()."',type = '".$mill_replica->getType()."',latitude = '".$mill_replica->getLatitude()."',longitude = '".$mill_replica->getLongitude()."',incoming_min_paddy = '".$mill_replica->getIncomingMinPaddy()."',total_rice_inventory = '".$mill_replica->getTotalRiceInventory()."',milling_capacity = '".$mill_replica->getMillingCapacity()."',minimum_outgoing_rice = '".$mill_replica->getMinimumOutgoingRice()."',active = '".$mill_replica->getActive()."' WHERE id = '".$mill_replica->getId()."'";
    }
}

?>
