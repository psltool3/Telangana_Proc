<?php

class PC {
    public $district;
    public $name;
    public $id;
    public $pctype;
    public $type;
    public $latitude;
    public $longitude;
    public $quantity_arrival;
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

    public function getPctype() {
        return $this->pctype;
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

    public function getQuantityArrival() {
        return $this->quantity_arrival;
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

    public function setPctype($pctype) {
        $this->pctype = $pctype;
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

    public function setQuantityArrival($quantity_arrival) {
        return $this->quantity_arrival = $quantity_arrival;
    }
	
	public function setUniqueid($uniqueid) {
        $this->uniqueid = $uniqueid;
    }
	
	public function setActive($active) {
        $this->active = $active;
    }
	
	function insert(PC $pc){
        return "INSERT INTO pc (district, name, id, latitude, longitude, quantity_arrival, uniqueid, active) VALUES ('".$pc->getDistrict()."','".$pc->getName()."','".$pc->getId()."','".$pc->getLatitude()."','".$pc->getLongitude()."','".$pc->getQuantityArrival()."','".$pc->getUniqueid()."','".$pc->getActive()."')";
    }

    function delete(PC $pc){
        return "DELETE FROM pc WHERE uniqueid='".$pc->getUniqueid()."'";
    }
	
	function deleteall(PC $pc){
        return "DELETE FROM pc WHERE 1";
    }
	
	function logname(PC $pc){

        return "SELECT name FROM pc WHERE uniqueid='".$pc->getUniqueid()."'";

    }
	
	function check(PC $pc){
        return "SELECT * FROM pc WHERE uniqueid='".$pc->getUniqueid()."'";
    }
	
	function checkInsert(PC $pc){
        return "SELECT * FROM pc WHERE LOWER(id)=LOWER('".$pc->getId()."')";
    }
	
	function checkEdit(PC $pc){
        return "SELECT * FROM pc WHERE LOWER(id)=LOWER('".$pc->getId()."')";
    }

    function update(PC $pc){
      return  "UPDATE pc SET district = '".$pc->getDistrict()."',name = '".$pc->getName()."',id = '".$pc->getId()."',latitude = '".$pc->getLatitude()."',longitude = '".$pc->getLongitude()."',quantity_arrival = '".$pc->getQuantityArrival()."',active = '".$pc->getActive()."' WHERE uniqueid = '".$pc->getUniqueid()."'";
    }
	
	function updateEdit(PC $pc){
      return  "UPDATE pc SET district = '".$pc->getDistrict()."',name = '".$pc->getName()."',latitude = '".$pc->getLatitude()."',longitude = '".$pc->getLongitude()."',quantity_arrival = '".$pc->getQuantityArrival()."',active = '".$pc->getActive()."' WHERE id = '".$pc->getId()."'";
    }
}

?>
