<?php
$dirs = [
    'c:/xampp/htdocs/Telangana_Procurement_15-05-2026_final/Telangana_Procurement_15-05-2026_final/pds_admin_chat/api/',
    'c:/xampp/htdocs/Telangana_Procurement_15-05-2026_final/Telangana_Procurement_15-05-2026_final/pds_district_chat/api/'
];

$addEditFiles = ['WarehouseAdd.php', 'WarehouseEdit.php', 'StoragePointAdd.php', 'StoragePointEdit.php'];
$bulkFiles = ['BulkWarehouseData.php', 'BulkWarehouseDataEdit.php', 'BulkStoragePointData.php', 'BulkStoragePointDataEdit.php'];

foreach ($dirs as $dir) {
    // 1. Patch Add/Edit files
    foreach ($addEditFiles as $file) {
        $path = $dir . $file;
        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            if (strpos($content, 'Motorable/Non-Motorable should be either') === false) {
                // Find a good place to insert.
                // Usually after ID validation or Latitude validation.
                $pattern = '/\/\/ Validate ID\s*if \(!preg_match\(\'\/\[\^a-zA-Z0-9_\\\\-\]\+\/\', \$_POST\["id"\]\)\) \{\s*echo "Error : ID should only contain[^"]+";\s*exit\(\);\s*\}/s';
                
                $replacement_code = '
$allowed_motorable = [\'motorable\', \'non motorable\', \'nonmotorable\', \'non-motorable\'];
if (!in_array(strtolower(trim($_POST["type"])), $allowed_motorable)) {
    echo "Error : Motorable/Non-Motorable should be either Motorable or Non Motorable.";
    exit();
}
';

                // In StoragePoint, there is no ID validation block sometimes. Let's just insert before `$dbHashedPassword = $row['password'];`
                $pattern2 = '/\$dbHashedPassword = \$row\[\'password\'\];/';
                
                if (preg_match($pattern2, $content, $matches)) {
                    $newContent = str_replace($matches[0], $replacement_code . $matches[0], $content);
                    if ($newContent !== $content) {
                        file_put_contents($path, $newContent);
                        echo "Patched $path\n";
                    }
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
            
            if (strpos($content, 'Motorable/Non-Motorable should be either') === false) {
                // Insert after Longitude check
                $pattern = '/if \(!is_numeric\(\$column\[\$longitude\]\) \|\| \$column\[\$longitude\] <= 65\) \{\s*echo "Error : Longitude must be more than 65\. Given: " \. \$column\[\$longitude\];\s*echo "<\/br>";\s*\$redirect = 0;\s*\}/s';
                
                $replacement_code = '
                $allowed_motorable = [\'motorable\', \'non motorable\', \'nonmotorable\', \'non-motorable\'];
                if (!in_array(strtolower(trim($column[$type])), $allowed_motorable)) {
                    echo "Error : Motorable/Non-Motorable should be either Motorable or Non Motorable. Given: " . $column[$type];
                    echo "</br>";
                    $redirect = 0;
                }
';

                if (preg_match($pattern, $content, $matches)) {
                    $target = $matches[0];
                    $newContent = str_replace($target, $target . $replacement_code, $content);
                    if ($newContent !== $content) {
                        file_put_contents($path, $newContent);
                        echo "Patched bulk $path\n";
                    }
                } else {
                     echo "Failed to match bulk pattern in $path\n";
                }
            } else {
                echo "Already patched bulk $path\n";
            }
        }
    }
}
?>
