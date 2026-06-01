<?php
require('c:/xampp/htdocs/Chattisgarh_Procurement/pds_district_chat/util/Connection.php');

$tables = ['optimised_table', 'optimised_table_leg1'];
foreach ($tables as $table) {
    echo "Columns for $table:\n";
    $result = mysqli_query($con, "DESCRIBE $table");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo " - " . $row['Field'] . "\n";
        }
    } else {
        echo " Error describing $table: " . mysqli_error($con) . "\n";
    }
}
?>
