<?php
$dir = 'c:/xampp/htdocs/Telangana_Procurement_15-05-2026_final/Telangana_Procurement_15-05-2026_final/pds_admin_chat/api/';
$files = ['MillAdd.php', 'MillEdit.php', 'MillReplicaAdd.php', 'MillReplicaEdit.php', 'WarehouseAdd.php', 'WarehouseEdit.php', 'PCAdd.php', 'PCEdit.php'];

foreach ($files as $file) {
    $path = $dir . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        
        // Replace short name error
        $content = str_replace('echo "Error : Invalid Name";', 'echo "Error : Name should only contain characters, numbers, underscores, hyphens, and spaces.";', $content);
        
        // Replace short id error
        $content = str_replace('echo "Error : Invalid ID";', 'echo "Error : ID should only contain characters, numbers, underscores, and hyphens (no spaces).";', $content);
        
        // Replace long name error
        $content = str_replace('echo "Error : Invalid Name (only characters, numbers, underscores, hyphens, and spaces are allowed)";', 'echo "Error : Name should only contain characters, numbers, underscores, hyphens, and spaces.";', $content);
        
        // Replace long id error
        $content = str_replace('echo "Error : Invalid ID (only characters, numbers, underscores, and hyphens are allowed, with no spaces)";', 'echo "Error : ID should only contain characters, numbers, underscores, and hyphens (no spaces).";', $content);
        
        file_put_contents($path, $content);
        echo "Updated $file\n";
    }
}
?>
