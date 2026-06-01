<?php
$dirs = [
    'c:/xampp/htdocs/Telangana_Procurement_15-05-2026_final/Telangana_Procurement_15-05-2026_final/pds_admin_chat/api/',
    'c:/xampp/htdocs/Telangana_Procurement_15-05-2026_final/Telangana_Procurement_15-05-2026_final/pds_district_chat/api/'
];

$addEditFiles = ['MillAdd.php', 'MillEdit.php', 'MillReplicaAdd.php', 'MillReplicaEdit.php'];
$bulkFiles = ['BulkMillData.php', 'BulkMillDataEdit.php', 'BulkMillReplicaData.php', 'BulkMillReplicaDataEdit.php'];

foreach ($dirs as $dir) {
    // 1. Patch Add/Edit files
    foreach ($addEditFiles as $file) {
        $path = $dir . $file;
        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            // Clean up my previous incorrect patch
            $content = preg_replace('/if \(is_numeric\(\$_POST\["milling_capacity"\]\) && is_numeric\(\$_POST\["incoming_min_paddy"\]\)\) \{\s*if \(\$_POST\["milling_capacity"\] <= \$_POST\["incoming_min_paddy"\]\) \{\s*\$errors\[\] = "Error : Milling Capacity must be greater than Incoming Min Paddy";\s*\}\s*\}/', '', $content);
            
            // Fix existing check if any
            $content = preg_replace('/\$_POST\["incoming_min_paddy"\] > \$_POST\["milling_capacity"\]/', '(float)$_POST["incoming_min_paddy"] >= (float)$_POST["milling_capacity"]', $content);
            $content = preg_replace('/Incoming Min Paddy must be less than or equal to Milling Capacity/', 'Milling Capacity must be greater than Incoming Min Paddy', $content);
            
            // If the file STILL doesn't have the check (like pds_admin_chat/api/MillAdd.php now after removing my bad patch), insert it correctly
            if (strpos($content, 'Milling Capacity must be greater than Incoming Min Paddy') === false) {
                // Insert after minimum_outgoing_rice
                $target = 'if (!is_numeric($_POST["minimum_outgoing_rice"])) {
    $errors[] = "Error : Invalid Minimum Outgoing Rice";
}';
                $replacement = 'if (!is_numeric($_POST["minimum_outgoing_rice"])) {
    $errors[] = "Error : Invalid Minimum Outgoing Rice";
}
if (is_numeric($_POST["milling_capacity"]) && is_numeric($_POST["incoming_min_paddy"])) {
    if ((float)$_POST["milling_capacity"] <= (float)$_POST["incoming_min_paddy"]) {
        $errors[] = "Error : Milling Capacity must be greater than Incoming Min Paddy";
    }
}';
                $content = str_replace($target, $replacement, $content);
            }
            
            file_put_contents($path, $content);
            echo "Processed $path\n";
        }
    }

    // 2. Patch Bulk files
    foreach ($bulkFiles as $file) {
        $path = $dir . $file;
        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            // Remove my bad patch
            $content = preg_replace('/if \(is_numeric\(\$column\[\$milling_capacity\]\) && is_numeric\(\$column\[\$incoming_min_paddy\]\)\) \{\s*if \(\$column\[\$milling_capacity\] <= \$column\[\$incoming_min_paddy\]\) \{\s*echo "Error : Milling Capacity must be greater than Incoming Min Paddy<br>";\s*\$redirect = 0;\s*\}\s*\}/', '', $content);
            
            // Fix existing checks (like in BulkMillReplicaData.php)
            $content = preg_replace('/\(float\)\$column\[\$incoming_min_paddy\] > \(float\)\$column\[\$milling_capacity\]/', '(float)$column[$incoming_min_paddy] >= (float)$column[$milling_capacity]', $content);
            $content = preg_replace('/Incoming Min Paddy cannot be greater than Milling Capacity\./', 'Milling Capacity must be greater than Incoming Min Paddy.', $content);
            
            // If the file STILL doesn't have the check (like BulkMillData.php), insert it correctly
            if (strpos($content, 'Milling Capacity must be greater than Incoming Min Paddy') === false) {
                 $pattern = '/(if \(!is_numeric\(\$column\[\$incoming_min_paddy\]\).*?\$redirect = 0;\s*\})/s';
                 if (preg_match($pattern, $content, $matches)) {
                    $target = $matches[0];
                    $replacement = $target . '
				if (is_numeric($column[$milling_capacity]) && is_numeric($column[$incoming_min_paddy])) {
				    if ((float)$column[$milling_capacity] <= (float)$column[$incoming_min_paddy]) {
					    echo "Error : Milling Capacity must be greater than Incoming Min Paddy<br>";
					    $redirect = 0;
				    }
				}';
                    $content = str_replace($target, $replacement, $content);
                 }
            }
            
            file_put_contents($path, $content);
            echo "Processed bulk $path\n";
        }
    }
}
?>
