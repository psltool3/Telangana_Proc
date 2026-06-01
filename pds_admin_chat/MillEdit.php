<?php
require('util/Connection.php');
require('util/SessionCheck.php');
require('Header.php');

$district = "";
$name = "";
$id = "";
$type = "";
$latitude = "";
$longitude = "";

$incoming_min_paddy = "";
$total_rice_inventory = "";
$milling_capacity = "";
$minimum_outgoing_rice = "";

$active = "";

if(isset($_POST["uid"])){
	$uniqueid = $_POST["uid"];
	$query = "SELECT * FROM mill WHERE uniqueid='$uniqueid'";
	$result = mysqli_query($con,$query);
	$numrows = mysqli_num_rows($result);
	if($numrows!=0){
		$row = mysqli_fetch_assoc($result);
		$district = $row['district'];
		$name = $row['name'];
		$id = $row['id'];
		$type = $row['type'];
		$latitude = $row['latitude'];
		$longitude = $row['longitude'];
        
        $incoming_min_paddy = $row['incoming_min_paddy'];
        $total_rice_inventory = $row['total_rice_inventory'];
        $milling_capacity = $row['milling_capacity'];
        $minimum_outgoing_rice = $row['minimum_outgoing_rice'];
        
		$active = $row['active'];
	}
	else{
		header("Location:Mill.php");
	}
}
else{
	header("Location:Mill.php");
}

?>

<script src="crypto-js/crypto-js.js"></script>
<script src="js/Encryption.js"></script>

<script>
	function verifyCaptcha() {
		var readableString = document.getElementById("password").value;
		var nonceValue = "nonce_value";
		let encryption = new Encryption();
		var encrypted = encryption.encrypt(readableString, nonceValue);
		document.getElementById("password").value = encrypted;
	}
</script>

<script>
	function setSelectedValue(obj_value,valueToSet) {
		var obj = document.getElementById(obj_value);
		for (var i = 0; i < obj.options.length; i++) {
			if (obj.options[i].value== valueToSet) {
				obj.options[i].selected = true;
				return;
			}
		}
	}

	function setSelectedValue1(obj_value,valueToSet) {
		valueToSet = valueToSet.substring(0, valueToSet.length - 1);
		var obj = document.getElementById(obj_value);
		for (var i = 0; i < obj.options.length; i++) {
			if (obj.options[i].text== valueToSet) {
				obj.options[i].selected = true;
				return;
			}
		}
	}
</script>
                <!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li><a href="Mill.php">Home</a></li>
                    <li class="active">Mill Edit</li>
                </ul>
                <!-- END BREADCRUMB -->


				<!-- PAGE CONTENT WRAPPER -->
                 <div class="page-content-wrap">

                    <div class="row">
                        <div class="col-md-12">

                            <form action="api/MillEdit.php" method="POST" class="form-horizontal" enctype = "multipart/form-data">
                            <div class="panel panel-default">
                               <div class="panel-body">
                                    <p>Fill this form to edit mill.</p>
                                </div>

                             <div class="panel-body">

                                    <div class="row">

                                        <div class="col-md-6">
											
											<div class="form-group">
                                                <label class="col-md-3 control-label">Name of Mill*</label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-info"></span></span>
                                                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $name ?>" required />
                                                    </div>
                                                    <span class="help-block">Mill Name</span>
                                                </div>
                                            </div>
											
											<input type="hidden" id="uniqueid" name="uniqueid" value="<?php  echo $_POST["uid"] ?>" />
											<input type="hidden" id="active" name="active" value="<?php  echo $active ?>" />
											
											<div class="form-group">
                                                <label class="col-md-3 control-label">Mill Type</label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
												   <span class="input-group-addon"><span class="fa fa-arrow-down"></span></span>
                                                    <select class="form-control" id="type" name="type">
													<option value="Normal Rice">Normal Rice</option>
													<option value="State FRK Rice">State FRK Rice</option>
													<option value="Central FRK Rice">Central FRK Rice</option>
                                                    </select>
													</div>
                                                    <span class="help-block">Mill Type</span>
                                                </div>
                                            </div>
											
											<div class="form-group">
                                                <label class="col-md-3 control-label">Latitude*</label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-info"></span></span>
                                                        <input type="text" class="form-control" id="latitude" name="latitude" value="<?php echo $latitude ?>" required />
                                                    </div>
                                                    <span class="help-block">Latitude</span>
                                                </div>
                                            </div>
											
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Incoming Min Paddy*</label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-info"></span></span>
                                                        <input type="text" class="form-control" id="incoming_min_paddy" name="incoming_min_paddy" value="<?php echo $incoming_min_paddy ?>" required />
                                                    </div>
                                                    <span class="help-block">Incoming Min Paddy</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Total Rice Inventory*</label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-info"></span></span>
                                                        <input type="text" class="form-control" id="total_rice_inventory" name="total_rice_inventory" value="<?php echo $total_rice_inventory ?>" required />
                                                    </div>
                                                    <span class="help-block">Total Rice Inventory</span>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
										
											<div class="form-group">
                                                <label class="col-md-3 control-label">District*</label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
												   <span class="input-group-addon"><span class="fa fa-arrow-down"></span></span>
                                                    <select class="form-control" id="district" name="district">
                                                    </select>
													</div>
                                                    <span class="help-block">District</span>
                                                </div>
                                            </div>
										
											<div class="form-group">
                                                <label class="col-md-3 control-label">Mill Id*</label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-info"></span></span>
                                                        <input type="text" class="form-control" id="id" name="id" value="<?php echo $id ?>" style="color: black; font-weight: bold;" readonly required />
                                                    </div>
                                                    <span class="help-block">Mill ID</span>
                                                </div>
                                            </div>
											
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Milling Capacity*</label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-info"></span></span>
                                                        <input type="text" class="form-control" id="milling_capacity" name="milling_capacity" value="<?php echo $milling_capacity ?>" required />
                                                    </div>
                                                    <span class="help-block">Milling Capacity</span>
                                                </div>
                                            </div>
											
											<div class="form-group">
                                                <label class="col-md-3 control-label">Longitude*</label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-info"></span></span>
                                                        <input type="text" class="form-control" id="longitude" name="longitude" value="<?php echo $longitude ?>" required />
                                                    </div>
                                                    <span class="help-block">Longitude</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Minimum Outgoing Rice*</label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-info"></span></span>
                                                        <input type="text" class="form-control" id="minimum_outgoing_rice" name="minimum_outgoing_rice" value="<?php echo $minimum_outgoing_rice ?>" required />
                                                    </div>
                                                    <span class="help-block">Minimum Outgoing Rice</span>
                                                </div>
                                            </div>
										   
                                        </div>

                                    </div>

                                </div>
                                <div class="panel-footer">
                                    <button class="btn btn-primary pull-right" onclick="showPopup()" type="button">Submit</button>
                                </div>
								<div id="popup" class="popup">
										<a class="close" onclick="hidePopup()" style="font-size:25px">×</a>
										</br></br>
										
										<div class="col-md-6">
										
											<div class="form-group">
                                                <label class="col-md-3 control-label">Username*</label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-info"></span></span>
                                                        <input type="text" class="form-control" id="username" name="username" required />
                                                    </div>
                                                    <span class="help-block">Username</span>
                                                </div>
                                            </div>
											
											
                                        </div>
                                        <div class="col-md-6">
										
										
											<div class="form-group">
                                                <label class="col-md-3 control-label">Password*</label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-info"></span></span>
                                                        <input type="password" class="form-control" id="password" name="password" required />
                                                    </div>
                                                    <span class="help-block">Password</span>
                                                </div>
                                            </div>
											
											
                                        </div>
										
										<center><button class="btn btn-primary" onclick="verifyCaptcha()">Verify</button></center>
								</div>
                            </div>
                            </form>

                        </div>
                    </div>
					</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>
                </div>
                            </div>
                            <!-- END SIMPLE DATATABLE -->

                        </div>
                    </div>

                </div>
                <!-- PAGE CONTENT WRAPPER -->
            </div>
            <!-- END PAGE CONTENT -->
        </div>
        <!-- END PAGE CONTAINER -->

		<?php
			echo "<script>setSelectedValue('type','$type'); </script>";
		?>

    <!-- START SCRIPTS -->
        <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
        <!-- END PLUGINS -->

        <!-- THIS PAGE PLUGINS -->
        <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
        <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
        <script type="text/javascript" src="js/plugins/datatables/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="js/plugins/tableexport/tableExport.js"></script>
		<script type="text/javascript" src="js/plugins/tableexport/jquery.base64.js"></script>
		<script type="text/javascript" src="js/plugins/tableexport/html2canvas.js"></script>
		<script type="text/javascript" src="js/plugins/tableexport/jspdf/libs/sprintf.js"></script>
		<script type="text/javascript" src="js/plugins/tableexport/jspdf/jspdf.js"></script>
		<script type="text/javascript" src="js/plugins/tableexport/jspdf/libs/base64.js"></script>
		
        <script type="text/javascript" src="js/plugins.js"></script>
        <script type="text/javascript" src="js/actions.js"></script>
        <!-- END PAGE PLUGINS -->
		<?php
			require('DistrictAutocomplete.php');
			
			echo "<script>setSelectedValue('district','$district'); </script>";
		?>
		
		<script>
		function showPopup() {
            
			var name = document.getElementById('name').value;
            var type = document.getElementById('type').value;
			var latitude = document.getElementById('latitude').value;
            var longitude = document.getElementById('longitude').value;
			var id = document.getElementById('id').value;
            var district = document.getElementById('district').value;
            var milling_capacity = document.getElementById('milling_capacity').value;
            var incoming_min_paddy = document.getElementById('incoming_min_paddy').value;
            var total_rice_inventory = document.getElementById('total_rice_inventory').value;
            var minimum_outgoing_rice = document.getElementById('minimum_outgoing_rice').value;

            if (name === '' || type === '' || latitude === '' || longitude === '' || id === '' || district === '' || milling_capacity === '' || incoming_min_paddy === '' || total_rice_inventory === '' || minimum_outgoing_rice === '') {
                alert('Please enter all fields');
                return false;
            }

            
			
            document.getElementById('popup').style.display = 'block';
        }
		
		function hidePopup() {
            document.getElementById('popup').style.display = 'none';
        }
		
		</script>

    </body>
</html>
