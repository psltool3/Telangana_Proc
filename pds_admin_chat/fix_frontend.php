<?php
$dir = 'c:/xampp/htdocs/Telangana_Procurement_15-05-2026_final/Telangana_Procurement_15-05-2026_final/pds_admin_chat/';
$files = ['MillAdd.php', 'MillEdit.php', 'MillReplicaAdd.php', 'MillReplicaEdit.php', 'WarehouseAdd.php', 'WarehouseEdit.php', 'PCAdd.php', 'PCEdit.php'];

foreach ($files as $file) {
    $path = $dir . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        
        // 1. Remove pattern and title from name
        $content = preg_replace('/pattern="\^\[a-zA-Z0-9_\\\\-\\\\s\]\+\\$"\s*title="Only characters, numbers, underscores, hyphens, and spaces are allowed"\s*/', '', $content);
        
        // 2. Remove pattern and title from id
        $content = preg_replace('/pattern="\^\[a-zA-Z0-9_\\\\-\]\+\\$"\s*title="Only characters, numbers, underscores, and hyphens are allowed \(no spaces\)"\s*/', '', $content);

        // 3. Remove JS validation
        $jsRegex = '/\s*var\s+(name|id)Regex\s*=\s*\/\[\^\[a-zA-Z0-9_\\\\-\\\\s\]\+\$\/;\s*if\s*\(!\1Regex\.test\(\1\)\)\s*\{\s*alert\(\'[^\']+\'\);\s*return\s+false;\s*\}/s';
        
        // Let's use string replace for the JS part to be safer as it is multi-line
        $jsBlock1 = "var nameRegex = /^[a-zA-Z0-9_\-\s]+$/;
            if (!nameRegex.test(name)) {
                alert('Name should only contain characters, numbers, underscores, hyphens, and spaces.');
                return false;
            }";
            
        $jsBlock2 = "var idRegex = /^[a-zA-Z0-9_\-]+$/;
            if (!idRegex.test(id)) {
                alert('ID should only contain characters, numbers, underscores, and hyphens (no spaces).');
                return false;
            }";

        // Normalize line endings to help str_replace
        $content = str_replace("\r\n", "\n", $content);
        
        // Build regex to match the js block instead, because indentation might differ
        $content = preg_replace('/var nameRegex = \/\^\[a-zA-Z0-9_\\\\-\\\\s\]\+\$\/;\s*if \(!nameRegex\.test\(name\)\) \{\s*alert\(\'Name should only contain characters, numbers, underscores, hyphens, and spaces\.\'\);\s*return false;\s*\}/', '', $content);

        $content = preg_replace('/var idRegex = \/\^\[a-zA-Z0-9_\\\\-\]\+\$\/;\s*if \(!idRegex\.test\(id\)\) \{\s*alert\(\'ID should only contain characters, numbers, underscores, and hyphens \(no spaces\)\.\'\);\s*return false;\s*\}/', '', $content);
        
        file_put_contents($path, $content);
        echo "Updated $file\n";
    }
}
?>
