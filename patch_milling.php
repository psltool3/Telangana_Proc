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
            
            $pattern = '/if \(!is_numeric\(\$_POST\["minimum_outgoing_rice"\]\)\) \{\s*\$errors\[\] = "Error : Invalid Minimum Outgoing Rice";\s*\}/';
            
            $replacement = 'if (!is_numeric($_POST["minimum_outgoing_rice"])) {
    $errors[] = "Error : Invalid Minimum Outgoing Rice";
}
if (is_numeric($_POST["milling_capacity"]) && is_numeric($_POST["incoming_min_paddy"])) {
    if ($_POST["milling_capacity"] <= $_POST["incoming_min_paddy"]) {
        $errors[] = "Error : Milling Capacity must be greater than Incoming Min Paddy";
    }
}';
            
            if (strpos($content, 'Milling Capacity must be greater than Incoming Min Paddy') === false) {
                $newContent = preg_replace($pattern, $replacement, $content);
                if ($newContent !== $content) {
                    file_put_contents($path, $newContent);
                    echo "Successfully patched $path\n";
                } else {
                    echo "Failed to match in $path\n";
                }
            } else {
                echo "Already patched $path\n";
            }
        }
    }

    // 2. Patch Bulk files
    foreach ($bulkFiles as $file) {
        $path = $dir . $file;
        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            $pattern = '/(if \(!is_numeric\(\$column\[\$incoming_min_paddy\]\).*?\$redirect = 0;\s*\})/s';
            
            if (preg_match($pattern, $content, $matches)) {
                $target = $matches[0];
                $replacement = $target . '
				if (is_numeric($column[$milling_capacity]) && is_numeric($column[$incoming_min_paddy])) {
				    if ($column[$milling_capacity] <= $column[$incoming_min_paddy]) {
					    echo "Error : Milling Capacity must be greater than Incoming Min Paddy<br>";
					    $redirect = 0;
				    }
				}';
                
                if (strpos($content, 'Milling Capacity must be greater than Incoming Min Paddy') === false) {
                    $newContent = str_replace($target, $replacement, $content);
                    if ($newContent !== $content) {
                        file_put_contents($path, $newContent);
                        echo "Successfully patched bulk $path\n";
                    }
                } else {
                    echo "Already patched bulk $path\n";
                }
            } else {
                 echo "Failed to match bulk in $path\n";
            }
        }
    }
}
?>
