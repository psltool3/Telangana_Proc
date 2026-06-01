<?php
require('util/Connection.php');
require('util/SessionCheck.php');
require('Header.php');
?>

<head>
	<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,300' rel='stylesheet' type='text/css'>
</head>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
	.multiselect {
	  width: 200px;
	  z-index: 2;
	}

	.selectBox {
	  position: relative;
	  z-index: 3;
	}

	.selectBox select {
	  width: 100%;
	  font-weight: bold;
	  z-index: 4;
	}

	.overSelect {
	  position: absolute;
	  left: 0;
	  right: 0;
	  top: 0;
	  bottom: 0;
	  z-index: 5;
	}

	#checkboxes {
	  display: none;
	  border: 1px #dadada solid;
	  color:#000;
	  z-index: 6;
	}

	#checkboxes label {
	  display: block;
	  color:#000;
	  z-index: 7;
	}

	#checkboxes label:hover {
	  background-color: #1e90ff;
	  z-index: 7;
	}

	#processingPopup {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(255, 255, 255, 0.8);
		align-items: center;
		justify-content: center;
		z-index: 9999;
	}

	#processingPopup .spinner {
		border: 6px solid #3498db;
		border-top: 6px solid #f39c12;
		border-radius: 50%;
		width: 40px;
		height: 40px;
		animation: spin 1s linear infinite;
	}

	#optimisedtable {
		border-collapse: collapse;
		width: 100%;
		margin-top: 0px;
	}

	#optimisedtable th,
	#optimisedtable td {
		border: 1px solid #ddd;
		padding: 8px;
		text-align: center;
	}

	#optimisedtable th {
		background-color: #5E35B1;
		color: white;
	}

	#optimisedtable tbody tr:nth-child(even) {
		background-color: #f2f2f2;
	}

	#optimisedtable tbody tr:hover {
		background-color: #ddd;
	}

	.help-block b {
		font-weight: bold;
	}

	*,
	*:before,
	*:after {
		box-sizing: border-box;
	}

	.toggle {
		position: relative;
		display: block;
		margin: 0;
		width: 140px;
		height: 50px;
		color: black;
		outline: 0;
		text-decoration: none;
		border-radius: 60px;
		border: 2px solid #546E7A;
		background-color: white;
		transition: all 500ms;
		cursor: pointer;
	}

	.toggle:active {
		background-color: darken(red, 5%);
	}

	.toggle:hover:not(.toggle--moving):after {
		background-color: green;
	}

	.toggle:after {
		content: attr(data-content);
		display: block;
		position: absolute;
		top: 0px;
		bottom: 1px;
		left: 1px;
		width: calc(50% - 4px);
		line-height: 52px;
		text-align: center;
		text-transform: uppercase;
		font-size: 20px;
		color: white;
		background-color: red;
		border: 2px solid;
		transition: all 500ms;
		border-radius: 50px;
	}

	.toggle--on:after {
		transform: translate(100%, 0);
		color: whitesmoke;
		background-color: green;
	}

	.toggle--off:after {
		color: whitesmoke;
		background-color: red;
	}

	.toggle--moving {
		background-color: darken(#263238, 5%);
	}

	.toggle--moving:after {
		color: transparent;
		border-color: darken(#546E7A, 8%);
		background-color: darken(white, 10%);
		transition: color 0s, transform 500ms, border-radius 500ms, background-color 500ms;
	}

	@keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
	}

	.btn {
		border-radius: 20px;
	}

	.upload_button_class {
		background-color: #F2F3F5;
		border-radius: 30px;
		box-shadow: -10px -10px 15px 0 #f6f6f6, 10px 10px 15px 0 #cecece;
		color: #676767;
		height: 50;
		margin: auto;
		padding: 0;
		text-align: center;
		transition: all .2s ease;
		width: auto;
		border: none;
		cursor: pointer;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.panel-footer {
		padding: 0;
	}

	.panel-footer .btn {
		padding: 2px 10px;
	}

	#optimisedtable th {
		text-align: center;
	}

	* {
		box-sizing: border-box;
	}

	button {
		outline: none;
		cursor: pointer;
	}

	.icon {
		display: inline-block;
		width: 1em;
		height: 1em;
		fill: currentColor;
	}

	body {
		font-family: 'Open Sans', sans-serif;
		font-size: 16px;
		color: #fff;
		background: linear-gradient(to right, #566a39 0%, #75986f 100%);
	}

	.button-wrapper {
		position: relative;
		display: inline-block;
		padding: 2px 3px;
		min-width: 10px;
		min-height: 40px;
		border-radius: 15px;
		box-shadow: 0px -1px 1px rgba(255, 255, 255, 0.22), inset 0px -1px 3px rgba(0, 0, 0, 0.2);
	}

	.button {
		position: relative;
		height: 40px;
		min-width: 5px;
		padding: 0 5px;
		border-radius: 15px;
		background: #ff005a;
		background: linear-gradient(#ff4184 0%, #ff005a 100%, #ff005a);
		border: none;
		font-size: 10px;
		color: white;
		line-height: 40px;
		font-weight: 700;
	}
</style>

<!-- START BREADCRUMB -->
<ul class="breadcrumb">
	<li><a href="#">Home</a></li>
	<li class="active">Telangana PDS Movement Optimization</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap"
	style="background-image: url('img/1 (2).png'); background-repeat: no-repeat; background-size: cover;">

	<div class="row">
		<div class="col-md-12">

			<div class="panel panel-default">
				<div class="panel-heading" style="text-align: center;">
					<h1 style="font-weight: bold; color: #335566;">Telangana PDS Movement Optimisation</h1>
					<h1 style="font-weight: bold; color: #ff7066;">Kindly Optimised the Leg2-Mill to Warehouse</h1>
				</div>
			</div> 

			<div class="row" style="margin-top:150px">
				<div class="col-md-4">
					<div class="form-group">
						<div class="col-md-2"></div>
						<div class="col-md-9">
							<div class="input-group" style="width:100%;">
								<select class="form-control" id="type" name="type" style="border-radius:5px;font-weight:bold">
									<option value='' style="font-weight:bold;color:#000;">Select</option>
									<option value='inter' style="font-weight:bold;color:#000;">Intra District</option>
								</select>
							</div>
							<span class="help-block">Select scenario for Optimisation</span>
						</div>
					</div>
				</div>
				<input type="hidden" id="username" name="username" value="<?php echo $_SESSION["user"] ?>" />
				<div class="col-md-4">
					<div class="form-group">
						<div class="col-md-2"></div>
						<div class="col-md-9">
							<div class="input-group" style="width:100%;">
								<input
									type="date"
									class="form-control"
									id="today_date"
									name="today_date"
									style="border-radius:5px;font-weight:bold"
									value="<?php echo date('Y-m-d'); ?>"
								>
							</div>
							<span class="help-block">Selected Date</span>
						</div>
					</div>
				</div>
			</div>
			</br>

			<div class="row">
				<div class="col-md-12">
					<div class="panel-body">
						<form action="" method="POST" class="form-horizontal" enctype="multipart/form-data" id="upload_button">
							<div class="row">
								<div class="col-md-8">
									<input style="font-size: 18px; padding: 10px 16px;" type="button" id="fetchButton" class="btn btn-success pull-right" onclick="fetchFromDb()" value="Fetch Data from Database" />
								</div>
							</div>
						</form>

						<div id="processingPopup">
							<div class="spinner"></div>
							<button type="button" style="margin-top:100px;margin-left:-80px;display:none" id="cancel-request" class="btn btn-danger" onClick="cancelRequest()">Cancel Request</button>
						</div>
						&nbsp;

						<!-- Pre-Analysis Cards -->
						<div class="row">
							<div style="font-size: 20px; font-weight: 700; margin-top: 0px; padding: 5px; margin-bottom: 20px;">
								<i class="fa fa-info-circle" aria-hidden="true"></i> Pre-Analysis
							</div>
							<div class="row">
								<div class="col-md-3 mb-3">
									<div class="card h-100" style="background-color:#4A90E2; color:white; padding:12px; font-weight: bold;">
										<div style="font-size:20px" id="total_mills"></div>
										<div style="font-size:14px">Total Mills</div>
									</div>
								</div>
								<div class="col-md-3 mb-3">
									<div class="card h-100" style="background-color:#28A745; color:white; padding:12px; font-weight: bold;">
										<div style="font-size:20px" id="total_supply"></div>
										<div style="font-size:14px">Total Supply (Qtl)</div>
									</div>
								</div>
								<div class="col-md-3 mb-3">
									<div class="card h-100" style="background-color:#E74C3C; color:white; padding:12px; font-weight: bold;">
										<div style="font-size:20px" id="total_demand"></div>
										<div style="font-size:14px">Total Demand (Qtl)</div>
									</div>
								</div>
								<div class="col-md-3 mb-3">
									<div class="card h-100" style="background-color:#FF5722; color:white; padding:12px; font-weight: bold;">
										<div style="font-size:20px" id="capacity"></div>
										<div style="font-size:14px">Warehouse Capacity</div>
									</div>
								</div>
								<br><br><br><br><br>
								<div class="col-md-3 mb-3">
									<div class="card h-100" style="background-color:#F39C12; color:white; padding:12px; font-weight: bold;">
										<div style="font-size:20px" id="total_warehouse"></div>
										<div style="font-size:14px">Total Warehouses</div>
									</div>
								</div>
								<div class="col-md-3 mb-3">
									<div class="card h-100" style="background-color:#F39C12; color:white; padding:12px; font-weight: bold;">
										<div style="font-size:20px" id="districts_no"></div>
										<div style="font-size:14px">Total Districts</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					&nbsp;
				</div>

				<!-- Chart -->
				<div class="col-md-9">
					</br></br></br>
					<center>
						<div style="width:80%"><canvas id="myChart" width="400" height="300"></canvas></div>
					</center>
				</div>

				<!-- Sidebar -->
				<div class="col-md-3" id="sidebar" style="display:none; border-radius: 20px;">
					<div style="border: 2px solid #DC8686; padding: 15px; background-color: #DC8686; color: white; border-radius: 20px; margin-top:100px; margin-bottom: 10px;">
						<div class="card">
							<div class="row">
								<center style="margin-top:20px">
									<h2><b><span style="color: white">Progress Bar</span></b></h2>
								</center>
								<center style="margin-top:20px">
									<h2><b><span style="color: white;">File Upload Successfully</span></b></h2>
								</center>
								<center><img src="img\Analysis-icon-1.png" style="width:45%" /></center>
								<center style="margin-top:20px">
									<h2><b><span style="color: white;">Pre-Analysis</span></b></h2>
								</center>
								<center style="margin-top:20px">
									<h4><b><span style="color: white;">State-Wise &nbsp <input type="checkbox" id="statewiseCheckbox" onchange="handleStateCheckboxChange()" checked /></span></b></h4>
								</center>

								<!-- Single-commodity summary -->
								<center style="margin-top: 20px; font-weight: 500; color: white;">
									<h4><b id="totalFciSupply"></b></h4>
								</center>
								<center style="margin-top:20px">
									<h4><b id="totalFciDemand"></b></h4>
								</center>
								<center style="margin-top:20px">
									<h4><b id="totalFcicapacity"></b></h4>
								</center>

								<center style="margin-top:20px">
									<h4><b id="selectedMonth"></b></h4>
								</center>
								<center style="margin-top:20px">
									<h4><b id="result"></b></h4>
								</center>

								<div id="districtcheckbox" style="display:none">
									<center style="margin-top:20px;">
										<h4><b><span style="color: white;">District-wise Supply and Demand &nbsp <input type="checkbox" id="districtwiseCheckbox" onchange="handleDistrictCheckboxChange()" /></span></b></h4>
									</center>
									<center style="margin-top:20px">
										<h4><b id="resultdistrict"></b></h4>
									</center>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			&nbsp;
			</br>

			<div id="generateoptinizedplanbutton" style="display:none; overflow: hidden;">
				<center>
					<div style="font-size: 20px; font-weight: 700; margin-top: 10px; margin-bottom: 20px; color:#000">
						<i class="fa fa-info-circle" aria-hidden="true"></i> Optimization
					</div>
				</center>
				<button class="upload_button_class" id="upload_button" name="submit">
					<span style="text-align: center; font-weight: bold;">Generate Optimized Plan</span>
					<a href="#" class="toggle toggle--off" data-content="Off" onclick="toggleState(this)"></a>
				</button>
				<div style="margin-top: 13px; margin-left: 1300px;">
					<div class="pen-wrapper">
						<div class="button-wrapper"></div>
					</div>
				</div>
				<br><br>
				<table class="table" id="optimisedtable" style="display: none; width: 100%; text-align: center;">
					<thead>
						<tr>
							<th>Scenario</th>
							<th>WH_Used</th>
							<th>FPS_Used</th>
							<th>Total_Allocation</th>
							<th>Total_QKM</th>
							<th>Average Distance</th>
						</tr>
					</thead>
					<tbody id="table_body"></tbody>
				</table>
			</div>
			&nbsp;<br><br><br>

		</div>
	</div>
</div>
<!-- END PAGE CONTENT WRAPPER -->

<!-- START SCRIPTS -->
<script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
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

<script>
	var isJobRunning = false;

	function checkServerStatus() {
		var xhr = new XMLHttpRequest();
		xhr.open("GET", pythonUrl, true);
		xhr.onload = function () {
			if (xhr.status == 200) {
				document.getElementById('pythonStatus').innerHTML = "Server is Working";
				document.getElementById('statusBlock').style.backgroundColor = "green";
			} else {
				document.getElementById('pythonStatus').innerHTML = "Server is not Working";
				document.getElementById('statusBlock').style.backgroundColor = "red";
				alert("Disconnected with Python Server");
			}
		};
		xhr.onerror = function () {
			document.getElementById('pythonStatus').innerHTML = "Server is not Working";
			document.getElementById('statusBlock').style.backgroundColor = "red";
		};
		xhr.send();
	}
	checkServerStatus();
	setInterval(checkServerStatus, 10000);

	function formatNumberWithCommas(value) {
		const formattedNumber = Number(value).toFixed(2);
		const parts = formattedNumber.split('.');
		let integerPart = parts[0];
		const decimalPart = parts[1] || '';
		integerPart = integerPart.replace(/\B(?=(\d{2})+(?!\d))/g, ',');
		return integerPart + '.' + decimalPart;
	}

	function formatNumberWithCommasWithoutDecimal(value) {
		const roundedNumber = Math.round(value);
		const parts = roundedNumber.toString().split('.');
		let integerPart = parts[0];
		integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
		return integerPart;
	}

	function post(params, file) {
		var form = document.createElement("form");
		form.setAttribute("method", "post");
		form.setAttribute("action", file);
		for (var key in params) {
			if (params.hasOwnProperty(key)) {
				var hiddenField = document.createElement("input");
				hiddenField.setAttribute("type", "hidden");
				hiddenField.setAttribute("name", key);
				hiddenField.setAttribute("value", params[key]);
				form.appendChild(hiddenField);
			}
		}
		document.body.appendChild(form);
		form.submit();
	}

	function edit_entry(temp_id) {
		post({ uid: temp_id }, "FPSEdit.php");
	}

	// -------------------------------------------------------
	// Chart initialisation — 3 datasets matching API response
	// -------------------------------------------------------
	var ctx = document.getElementById('myChart').getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: [],
			datasets: [
				{ label: 'Supply',   backgroundColor: '#27AE60', data: [] },
				{ label: 'Demand',   backgroundColor: '#E74C3C', data: [] },
				{ label: 'Capacity', backgroundColor: '#4A90E2', data: [] }
			]
		},
		options: {
			scales: { y: { beginAtZero: true } }
		}
	});

	// district_names holds { District_Name_All: [...] } from API
	var district_names = { District_Name_All: [] };

	// Safe helper — always returns an array regardless of API shape
	function getInfeasibleDistricts() {
		if (!district_names) return [];
		var val = district_names["District_Name_All"];
		if (!val) return [];
		return Array.isArray(val) ? val : [];
	}

	// -------------------------------------------------------
	// fetchFromDb
	// -------------------------------------------------------
	function fetchFromDb() {
		var applicableLength = 1;
		$.ajax({
			type: "POST",
			url: "api/fetchTableDataAll.php",
			data: "",
			cache: false,
			error: function () { alert("timeout"); },
			timeout: 120000,
			success: function (result) {
				try {
					applicableLength = Math.max(applicableLength, 1);

					// Reset sidebar text
					document.getElementById("districtcheckbox").style.display = "none";
					document.getElementById("result").innerHTML = "";
					document.getElementById("totalFciDemand").innerHTML = "";
					document.getElementById("totalFciSupply").innerHTML = "";
					document.getElementById("totalFcicapacity").innerHTML = "";
					document.getElementById("districtwiseCheckbox").checked = false;
					document.getElementById("statewiseCheckbox").checked = false;
					document.getElementById("generateoptinizedplanbutton").style.display = "none";
					document.getElementById("processingPopup").style.display = "flex";
					document.getElementById("sidebar").style.display = "block";

					const formData = new FormData();
					formData.append('applicable', applicableLength);
					fetch(pythonUrl + 'extract_data', {
						method: 'POST',
						body: formData,
						timeout: 14400000
					})
					.then(response => response.json())
					.then(data => {
						const fd2 = new FormData();
						fetch(pythonUrl + 'getfcidataleg1', {
							method: 'POST',
							body: fd2,
							timeout: 14400000
						})
						.then(response => response.json())
						.then(data => {
							document.getElementById("total_mills").innerHTML    = formatNumberWithCommasWithoutDecimal(data["Warehouse_No"]);
							document.getElementById("total_supply").innerHTML   = formatNumberWithCommasWithoutDecimal(data["Total_Supply"]);
							document.getElementById("total_warehouse").innerHTML = formatNumberWithCommasWithoutDecimal(data["FPS_No"]);
							document.getElementById("total_demand").innerHTML   = formatNumberWithCommasWithoutDecimal(data["Total_Demand"]);
							document.getElementById("capacity").innerHTML       = formatNumberWithCommasWithoutDecimal(data["Warehouse_Capacity"]);
							document.getElementById("districts_no").innerHTML       = formatNumberWithCommasWithoutDecimal(data["District_Count"]);


							if (!isJobRunning) {
								document.getElementById("processingPopup").style.display = "none";
							}
							if (firstStart == 0) {
								document.getElementById("statewiseCheckbox").checked = true;
								handleStateCheckboxChange();
							}
						})
						.catch(error => {
							console.error('Error:', error);
							alert("Error in Fetching Data");
							if (!isJobRunning) {
								document.getElementById("processingPopup").style.display = "none";
							}
						});
					})
					.catch(error => {
						console.error('Error:', error);
						if (!isJobRunning) {
							document.getElementById("processingPopup").style.display = "none";
						}
					});
				} catch (error) {
					console.log(error);
				}
			}
		});
	}

	// -------------------------------------------------------
	// handleDistrictCheckboxChange
	// -------------------------------------------------------
	function handleDistrictCheckboxChange() {
		var checkbox = document.getElementById("districtwiseCheckbox");

		if (checkbox.checked) {
			var infeasibleDistricts = getInfeasibleDistricts();

			if (infeasibleDistricts.length > 0) {
				document.getElementById("resultdistrict").innerHTML =
					"Intra district movement is infeasible - " + infeasibleDistricts.join(', ');
				document.getElementById("resultdistrict").style.color = "#ADFF2F";
				// document.getElementById("generateoptinizedplanbutton").style.display = "none";     removed none to display button 
				document.getElementById("generateoptinizedplanbutton").style.display = "";
			} else {
				document.getElementById("resultdistrict").innerHTML =
					"Intra scenario in every district is feasible";
				document.getElementById("resultdistrict").style.color = "#1111BB";
				document.getElementById("generateoptinizedplanbutton").style.display = "";
			}

			document.getElementById("resultdistrict").style.fontSize   = "18px";
			document.getElementById("resultdistrict").style.fontWeight  = "bold";
		} else {
			document.getElementById("resultdistrict").innerHTML = "";
			document.getElementById("generateoptinizedplanbutton").style.display = "none";
		}
	}

	// -------------------------------------------------------
	// handleOptimizationResult
	// -------------------------------------------------------
	function handleOptimizationResult(data) {
		isJobRunning = false;
		document.getElementById("optimisedtable").innerHTML = "";
		document.getElementById("optimisedtable").style.display = "";
		document.getElementById("processingPopup").style.display = "none";
		document.getElementById("cancel-request").style.display = "none";

		var thead = document.createElement("thead");
		var headerRow = document.createElement("tr");
		["Scenario", "Mill_Used", "Warehouse_Used", "Total_Allocation", "Total_QKM", "Average Distance"]
			.forEach(function (headerText) {
				var th = document.createElement("th");
				th.textContent = headerText;
				headerRow.appendChild(th);
			});
		thead.appendChild(headerRow);
		var table = document.getElementById("optimisedtable");
		table.appendChild(thead);

		var newRow1 = table.insertRow();
		newRow1.insertCell(0).innerHTML = data["Scenario"] === "Inter" ? "Intra" : data["Scenario"];
		newRow1.insertCell(1).innerHTML = data["WH_Used"];
		newRow1.insertCell(2).innerHTML = data["FPS_Used"];
		newRow1.insertCell(3).innerHTML = formatNumberWithCommas(data["Demand"]);
		newRow1.insertCell(4).innerHTML = formatNumberWithCommas(data["Total_QKM"]);
		newRow1.insertCell(5).innerHTML = formatNumberWithCommas(data["Average_Distance"]);

		var newRow2 = table.insertRow();
		newRow2.insertCell(0).innerHTML = data["Scenario_Baseline"];
		newRow2.insertCell(1).innerHTML = data["WH_Used_Baseline"];
		newRow2.insertCell(2).innerHTML = data["FPS_Used_Baseline"];
		newRow2.insertCell(3).innerHTML = data["Demand_Baseline"];
		newRow2.insertCell(4).innerHTML = data["Total_QKM_Baseline"];
		newRow2.insertCell(5).innerHTML = data["Average_Distance_Baseline"];

		table.style.cssText = "width:100%;padding:20px;margin-bottom:50px;font-size:20px;margin-left:20px;color:black;text-align:center;";
		Array.from(table.getElementsByTagName('th')).forEach(th => th.style.fontSize = "20px");

		resetUIState();
	}

	function resetUI() {
		isJobRunning = false;
		document.getElementById("processingPopup").style.display = "none";
		document.getElementById("cancel-request").style.display = "none";
		resetUIState();
	}

	function resetUIState() {
		var toggleButton = document.querySelector('.toggle');
		toggleButton.classList.replace('toggle--on', 'toggle--off');
		toggleButton.setAttribute('data-content', 'Off');
	}

	function pollJobStatus(jobId) {
		fetch(pythonUrl + 'job_status/' + jobId)
			.then(response => response.json())
			.then(data => {
				if (data.status == 1) {
					if (data.job.status === 'completed') {
						fetch(pythonUrl + 'job_result/' + jobId)
							.then(response => response.json())
							.then(resultData => handleOptimizationResult(resultData));
					} else if (data.job.status === 'failed') {
						alert("Optimization failed: " + (data.job.error || data.job.message));
						resetUI();
					} else {
						setTimeout(() => pollJobStatus(jobId), 3000);
					}
				}
			})
			.catch(err => {
				console.error("Polling error:", err);
				setTimeout(() => pollJobStatus(jobId), 5000);
			});
	}

	function generateoptimizedplan() {
		const formData = new FormData();
		var today_date = document.getElementById("today_date").value;
		var parts = today_date.split("-");
		var year  = parts[0];
		var monthNumber = parseInt(parts[1]);
		var day   = parseInt(parts[2]) + "-" + monthNumber + "-" + year;
		var monthNames = ['jan','feb','march','april','may','june','july','aug','sept','oct','nov','dec'];
		var month = monthNames[monthNumber - 1];

		formData.append('month', month);
		formData.append('year', year);
		formData.append('day', day);
		formData.append('type', document.getElementById("type").value);
		formData.append('async', '1');
		formData.append('user', document.getElementById("username").value);

		controller = new AbortController();
		isJobRunning = true;
		document.getElementById("processingPopup").style.display = "flex";
		document.getElementById("cancel-request").style.display = "flex";

		fetch(pythonUrl + 'processFileleg1', {
			method: 'POST',
			body: formData,
			signal: controller.signal
		})
		.then(response => response.json())
		.then(data => {
			if (data.status == 1 && data.job_id) {
				pollJobStatus(data.job_id);
			} else {
				alert(data.message || "Failed to start optimization");
				resetUI();
			}
		})
		.catch(error => {
			console.error('Error:', error);
			alert("Error in starting optimization");
			resetUI();
		});
	}

	function checkActiveJob() {
		var user = document.getElementById("username").value;
		fetch(pythonUrl + 'active_job?client_id=' + encodeURIComponent(user) + '&endpoint=/processFileleg1')
			.then(response => response.json())
			.then(data => {
				if (data.status == 1 && data.job) {
					isJobRunning = true;
					document.getElementById("processingPopup").style.display = "flex";
					document.getElementById("cancel-request").style.display = "flex";
					var toggleButton = document.querySelector('.toggle');
					toggleButton.classList.replace('toggle--off', 'toggle--on');
					toggleButton.setAttribute('data-content', 'On');
					pollJobStatus(data.job.job_id);
				}
			});
	}

	function cancelRequest() {
		if (controller) {
			controller.abort();
			const formData = new FormData();
			fetch(pythonUrl + 'processCancel', { method: 'POST', body: formData })
				.then(response => response.json())
				.then(data => {});
		}
	}

	function toggleState(element) {
		if (element.classList.contains('toggle--off')) {
			element.classList.replace('toggle--off', 'toggle--on');
			element.setAttribute('data-content', 'On');
			generateoptimizedplan();
		} else {
			element.classList.replace('toggle--on', 'toggle--off');
			element.setAttribute('data-content', 'Off');
			document.getElementById("optimisedtable").style.display = "none";
		}
	}

	function capitalizeFirstLetter(string) {
		return string.charAt(0).toUpperCase() + string.slice(1);
	}

	// -------------------------------------------------------
	// handleStateCheckboxChange  — simplified single-commodity
	// API response keys: District_Supply, District_Demand,
	//                    District_Capacity, District_Name
	// -------------------------------------------------------
	function handleStateCheckboxChange() {
		var checkbox = document.getElementById("statewiseCheckbox");
		document.getElementById("districtwiseCheckbox").checked = false;

		if (checkbox.checked) {
			const formData = new FormData();
			document.getElementById("processingPopup").style.display = "flex";

			fetch(pythonUrl + 'getGraphDataleg1', { method: 'POST', body: formData })
				.then(response => response.json())
				.then(data => {
					try {
					const round2 = num => Math.round(num * 100) / 100;

					// --- Read the three keys the API actually returns ---
					var districtSupply   = data.District_Supply   || {};
					var districtDemand   = data.District_Demand   || {};
					var districtCapacity = data.District_Capacity || {};

					var totalSupply   = round2(Object.values(districtSupply).reduce((a, b) => a + b, 0));
					var totalDemand   = round2(Object.values(districtDemand).reduce((a, b) => a + b, 0));
					var totalCapacity = round2(Object.values(districtCapacity).reduce((a, b) => a + b, 0));

					// District_Name holds { District_Name_All: [...] }
					// Handle both shapes: the object wrapper OR a bare array
					var rawName = data.District_Name;
					if (rawName && typeof rawName === 'object' && !Array.isArray(rawName)) {
						district_names = rawName;                         // { District_Name_All: [...] }
					} else if (Array.isArray(rawName)) {
						district_names = { District_Name_All: rawName };  // bare array — wrap it
					} else {
						district_names = { District_Name_All: [] };
					}

					// --- Update sidebar summary (single commodity) ---
					var styleSpan = (label, val, unit) =>
						`<span style='color:white;font-size:14px'>${label}: ${val} (${unit})</span>`;

					document.getElementById("totalFciSupply").innerHTML   = styleSpan("Total Supply",   totalSupply,   "Qtl");
					document.getElementById("totalFciDemand").innerHTML   = styleSpan("Total Demand",   totalDemand,   "Qtl");
					document.getElementById("totalFcicapacity").innerHTML = styleSpan("Total Capacity", totalCapacity, "Qtl");

					// Selected month label
					var monthParts = document.getElementById("today_date").value.split("-");
					var monthNames = ['jan','feb','march','april','may','june','july','aug','sept','oct','nov','dec'];
					var month = monthNames[parseInt(monthParts[1]) - 1] || "";
					document.getElementById("selectedMonth").innerHTML =
						`<span style='color:white;font-size:14px'>Selected Month: ${capitalizeFirstLetter(month)}</span>`;

					// --- Feasibility check (single commodity) ---
					var infeasibleDistricts = getInfeasibleDistricts();

					if (
						totalSupply  >= 0 &&
						totalDemand  >= 0 &&
						totalCapacity >= 0 &&
						totalSupply  >= totalDemand &&
						totalDemand  <= totalCapacity 
						// &&
						// infeasibleDistricts.length === 0
					) {
						document.getElementById("result").innerHTML =
							"<span style='font-weight:bold;font-size:20px;color:green;'>Optimization can be done.</span>";
						document.getElementById("districtcheckbox").style.display = "block";
					} else {
						document.getElementById("result").innerHTML =
							"<span style='font-weight:bold;font-size:20px;color:red;'>Optimization cannot be done — infeasible solution.</span>";
						document.getElementById("districtcheckbox").style.display = "none";
						document.getElementById("generateoptinizedplanbutton").style.display = "none";
					}

					// --- Update chart with single-commodity district data ---
					var districtNames  = [
						...new Set([
							...Object.keys(districtSupply),
							...Object.keys(districtDemand),
							...Object.keys(districtCapacity)
						])
					];

					myChart.data = {
						labels: districtNames,
						datasets: [
							{
								label: 'Supply',
								backgroundColor: '#27AE60',
								data: districtNames.map(d => districtSupply[d]   || 0)
							},
							{
								label: 'Demand',
								backgroundColor: '#E74C3C',
								data: districtNames.map(d => districtDemand[d]   || 0)
							},
							{
								label: 'Capacity',
								backgroundColor: '#4A90E2',
								data: districtNames.map(d => districtCapacity[d] || 0)
							}
						]
					};
					myChart.update();

					if (!isJobRunning) {
						document.getElementById("processingPopup").style.display = "none";
					}

					if (firstStart == 0) {
						document.getElementById("districtwiseCheckbox").checked = true;
						handleDistrictCheckboxChange();
						firstStart = 1;
					}
					} catch (innerErr) {
						console.error('handleStateCheckboxChange inner error:', innerErr);
						if (!isJobRunning) {
							document.getElementById("processingPopup").style.display = "none";
						}
					}
				})
				.catch(error => {
					console.error('Error:', error);
					alert("Error in Fetching Data");
					if (!isJobRunning) {
						document.getElementById("processingPopup").style.display = "none";
					}
				});

		} else {
			// Checkbox unchecked — clear everything
			document.getElementById("result").innerHTML = "";
			document.getElementById("totalFciSupply").innerHTML   = "";
			document.getElementById("totalFciDemand").innerHTML   = "";
			document.getElementById("totalFcicapacity").innerHTML = "";
			document.getElementById("districtwiseCheckbox").checked = false;
			document.getElementById("districtcheckbox").style.display = "none";
			document.getElementById("processingPopup").style.display = "none";
			document.getElementById("generateoptinizedplanbutton").style.display = "none";
		}
	}

	// -------------------------------------------------------
	// Boot
	// -------------------------------------------------------
	var today_date  = document.getElementById("today_date").value;
	var parts       = today_date.split("-");
	var year        = parts[0];
	var monthNumber = parts[1];
	var day         = parts[2];
	var monthNames  = ['jan','feb','march','april','may','june','july','aug','sept','oct','nov','dec'];
	var month       = monthNames[parseInt(monthNumber) - 1];

	var firstStart = 0;
	fetchFromDb();
	checkActiveJob();

	var expanded = false;
	function showCheckboxes() {
		var checkboxes = document.getElementById("checkboxes");
		checkboxes.style.display = expanded ? "none" : "block";
		expanded = !expanded;
	}
</script>
</body>
</html>
