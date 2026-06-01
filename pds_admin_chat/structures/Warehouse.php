<?php

class Warehouse {
    public $district;
    public $name;
    public $id;
    public $warehousetype;
    public $type;
    public $latitude;
    public $longitude;
    public $requirement;
    public $storage_capacity;
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

    public function getWarehousetype() {
        return $this->warehousetype;
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

    public function getRequirement() {
        return $this->requirement;
    }

    public function getStorageCapacity() {
        return $this->storage_capacity;
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

    public function setWarehousetype($warehousetype) {
        $this->warehousetype = $warehousetype;
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

    public function setRequirement($requirement) {
        $this->requirement = $requirement;
    }

    public function setStorageCapacity($storage_capacity) {
        $this->storage_capacity = $storage_capacity;
    }
	
	public function setUniqueid($uniqueid) {
        $this->uniqueid = $uniqueid;
    }
	
	public function setActive($active) {
        $this->active = $active;
    }
	
	function insert(Warehouse $warehouse){
        return "INSERT INTO warehouse (district, name, id, warehousetype, type, latitude, longitude, requirement, storage_capacity, uniqueid, active) VALUES ('".$warehouse->getDistrict()."','".$warehouse->getName()."','".$warehouse->getId()."','".$warehouse->getWarehousetype()."','".$warehouse->getType()."','".$warehouse->getLatitude()."','".$warehouse->getLongitude()."','".$warehouse->getRequirement()."','".$warehouse->getStorageCapacity()."','".$warehouse->getUniqueid()."','".$warehouse->getActive()."')";
    }

    function delete(Warehouse $warehouse){
        return "DELETE FROM warehouse WHERE uniqueid='".$warehouse->getUniqueid()."'";
    }
	
	function deleteall(Warehouse $warehouse){
        return "DELETE FROM warehouse WHERE 1";
    }
	
	function logname(Warehouse $warehouse){
        return "SELECT name FROM warehouse WHERE uniqueid='".$warehouse->getUniqueid()."'";
    }
	
	function check(Warehouse $warehouse){
        return "SELECT * FROM warehouse WHERE uniqueid='".$warehouse->getUniqueid()."'";
    }
	
	function checkInsert(Warehouse $warehouse){
        return "SELECT * FROM warehouse WHERE LOWER(id)=LOWER('".$warehouse->getId()."')";
    }
	
	function checkEdit(Warehouse $warehouse){
        return "SELECT * FROM warehouse WHERE LOWER(id)=LOWER('".$warehouse->getId()."')";
    }

    function update(Warehouse $warehouse){
      return  "UPDATE warehouse SET district = '".$warehouse->getDistrict()."',name = '".$warehouse->getName()."',id = '".$warehouse->getId()."',warehousetype = '".$warehouse->getWarehousetype()."',type = '".$warehouse->getType()."',latitude = '".$warehouse->getLatitude()."',longitude = '".$warehouse->getLongitude()."',requirement = '".$warehouse->getRequirement()."',storage_capacity = '".$warehouse->getStorageCapacity()."',active = '".$warehouse->getActive()."' WHERE uniqueid = '".$warehouse->getUniqueid()."'";
    }
	
	function updateEdit(Warehouse $warehouse){
      return  "UPDATE warehouse SET district = '".$warehouse->getDistrict()."',name = '".$warehouse->getName()."',warehousetype = '".$warehouse->getWarehousetype()."',type = '".$warehouse->getType()."',latitude = '".$warehouse->getLatitude()."',longitude = '".$warehouse->getLongitude()."',requirement = '".$warehouse->getRequirement()."',storage_capacity = '".$warehouse->getStorageCapacity()."',active = '".$warehouse->getActive()."' WHERE id = '".$warehouse->getId()."'";
    }
}

?>