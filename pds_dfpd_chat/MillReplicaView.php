<?php
require('util/Connection.php');
require('util/SessionCheck.php');
require('Header.php');

$id = $_POST['id'];
if (isset($_POST['step']) && $_POST['step'] == 'leg1') {
    $tablename = "mill_replica_leg1_".$id;
} else {
    $tablename = "mill_replica_".$id;
}

?>
<style>
     td {
            font-size: 16px; /* Increase font size for table headers and data cells */
        }
        .table thead tr th {
    background-color: #95b75d !important;
    /* border: 2px solid #777; */
    color: black;
    /* Optional: Font size for table header */
}
    </style>

                <!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li><a href="OptimisedDataAllLeg1.php">Home</a></li>
                    <li class="active">Mill Replica View</li>
                </ul>
                <!-- END BREADCRUMB -->


				<!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">

                    <div class="row">
                        <div class="col-md-12">

                            <!-- START SIMPLE DATATABLE -->
                            <div class="panel panel-default">
							<div class="panel-heading">
                                    <h3 class="panel-title">Mill Replica Data</h3>
                                </div>
								<div style="float:right; margin:10px">
									<button id="downloadCSV" class="btn btn-warning" style="margin-bottom: 10px;" type="button">Download CSV</button>
									<button id="downloadXLSX" class="btn btn-success" style="margin-bottom: 10px;" type="button">Download XLSX</button>
								</div>
                                <div class="panel-body">
                                 <div class="table-responsive">
                                    <table id="export_table" class="table datatable">
                                        <thead>
                                            <tr>
												<th style="font-size:15px">Unique ID</th>
												<th style="font-size:15px">District</th>
												<th style="font-size:15px">To District</th>
												<th style="font-size:15px">Name</th> 
												<th style="font-size:15px">Mill ID</th>
												<th style="font-size:15px">Type</th>
												<th style="font-size:15px">Latitude</th>
												<th style="font-size:15px">Longitude</th>
												<th style="font-size:15px">Incoming Mota</th>
												<th style="font-size:15px">Incoming Patla</th>
												<th style="font-size:15px">Incoming Saran</th>
												<th style="font-size:15px">Outgoing Mota</th>
												<th style="font-size:15px">Outgoing Patla</th>
												<th style="font-size:15px">Outgoing Saran</th>
												<th style="font-size:15px">Milling Capacity Mota</th>
												<th style="font-size:15px">Milling Capacity Patla</th>
												<th style="font-size:15px">Milling Capacity Saran</th>
												<th style="font-size:15px">Active</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										<?php
										
										$query = "SELECT * FROM ".$tablename." WHERE 1";
										
										$result = mysqli_query($con,$query);
										if($result && mysqli_num_rows($result) > 0){
											while($row = mysqli_fetch_array($result))
											{
												echo "<tr><td>{$row['uniqueid']}</td>".
												"<td>{$row['district']}</td>".
												"<td>{$row['to_district']}</td>".
												"<td>{$row['name']}</td>".
												"<td>{$row['id']}</td>".
												"<td>{$row['type']}</td>".
												"<td>{$row['latitude']}</td>".
												"<td>{$row['longitude']}</td>".
												"<td>{$row['incoming_min_mota']}</td>".
												"<td>{$row['incoming_min_patla']}</td>".
												"<td>{$row['incoming_min_saran']}</td>".
												"<td>{$row['outgoing_min_mota']}</td>".
												"<td>{$row['outgoing_min_patla']}</td>".
												"<td>{$row['outgoing_min_saran']}</td>".
												"<td>{$row['milling_capacity']}</td>".
												"<td>{$row['milling_capacity1']}</td>".
												"<td>{$row['milling_capacity2']}</td>".
												"<td>{$row['active']}</td></tr>";
											}
										}
										
										?>
                                        </tbody>
                                    </table>
                                  </div>
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



    <!-- START SCRIPTS -->
        <!-- START PLUGINS -->
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

		<script>
		function getDateString(){
			var currentDate = new Date();
			var year = currentDate.getFullYear();
			var month = currentDate.getMonth() + 1; // Month is zero-based, so we add 1
			var day = currentDate.getDate();
			var str = year + "-" + month + "-" + day;
			return str;
		}
		
		document.getElementById('downloadCSV').addEventListener('click', async function() {
			try {
				var tableName = '<?php echo $tablename ?>';
				const csvResponse = await fetch('api/DownloadOptimalDataMill.php?format=csv&tableName='+tableName);
				const csvBlob = await csvResponse.blob();
				downloadFile(csvBlob, 'Telangana_Mill_Replica_' + getDateString() + '.csv');
			} catch (error) {
				console.error('Error downloading CSV file:', error);
			}
		});

		// Event listener for downloading XLSX
		document.getElementById('downloadXLSX').addEventListener('click', async function() {
			try {
				var tableName = '<?php echo $tablename ?>';
				const excelResponse = await fetch('api/DownloadOptimalDataMill.php?format=xlsx&tableName='+tableName);
				const excelBlob = await excelResponse.blob();
				downloadFile(excelBlob, 'Telangana_Mill_Replica_' + getDateString() + '.xlsx');
			} catch (error) {
				console.error('Error downloading XLSX file:', error);
			}
		});

		// Functions for file download
		function downloadFile(blob, fileName) {
			const url = window.URL.createObjectURL(blob);
			const link = document.createElement('a');
			link.href = url;
			link.download = fileName;
			link.click();
			window.URL.revokeObjectURL(url);
		}


		</script>
    </body>
</html>
