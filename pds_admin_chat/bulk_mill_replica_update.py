import re

files_to_update = [
    r'c:\xampp\htdocs\Telangana_Procurement\pds_admin_chat\api\BulkMillReplicaData.php',
    r'c:\xampp\htdocs\Telangana_Procurement\pds_admin_chat\api\BulkMillReplicaDataEdit.php'
]

for file_path in files_to_update:
    with open(file_path, 'r', encoding='utf-8') as f:
        text = f.read()

    # 1. Update mapData
    target_map = '''    "Incoming Minimum of Mota" => "incoming_min_mota",
    "Incoming Minimum of Patla" => "incoming_min_patla",
    "Incoming Minimum of Saran" => "incoming_min_saran",
    "Total Normal Rice (Qtl) Inventory" => "outgoing_min_mota",
    "Total State FRK Rice (Qtl) Inventory" => "outgoing_min_patla",
    "Total Central FRK Rice(Qtl) Inventory" => "outgoing_min_saran",
    "Milling Capacity Mota" => "milling_capacity",
    "Milling Capacity Patla" => "milling_capacity1",
    "Milling Capacity Saran" => "milling_capacity2",'''

    repl_map = '''    "Incoming Min Paddy" => "incoming_min_paddy",
    "Total Rice Inventory" => "total_rice_inventory",
    "Milling Capacity" => "milling_capacity",
    "Minimum Outgoing Rice" => "minimum_outgoing_rice",'''

    text = text.replace(target_map, repl_map)

    # 2. Update initial index definitions
    target_indices = '''        $incoming_min_mota = -7;
        $incoming_min_patla = -8;
        $incoming_min_saran = -9;
        $outgoing_min_mota = -10;
        $outgoing_min_patla = -11;
        $outgoing_min_saran = -12;
        $milling_capacity = -13;
		$milling_capacity1 = -14;
		$milling_capacity2 = -15;'''

    repl_indices = '''        $incoming_min_paddy = -7;
        $total_rice_inventory = -8;
        $milling_capacity = -9;
        $minimum_outgoing_rice = -10;'''

    text = text.replace(target_indices, repl_indices)
    
    target_indices2 = '''            $incoming_min_mota = -7;
            $incoming_min_patla = -8;
            $incoming_min_saran = -9;
            $outgoing_min_mota = -10;
            $outgoing_min_patla = -11;
            $outgoing_min_saran = -12;
            $milling_capacity = -13;
            $milling_capacity1 = -14;
            $milling_capacity2 = -15;'''

    repl_indices2 = '''            $incoming_min_paddy = -7;
            $total_rice_inventory = -8;
            $milling_capacity = -9;
            $minimum_outgoing_rice = -10;'''

    text = text.replace(target_indices2, repl_indices2)


    # 3. Validation if string missing headers
    text = re.sub(
        r'if\(\$district<0 or \$to_district<0 or \$name<0 or \$id<0 or \$type<0 or \$latitude<0 or \$longitude<0 or \$incoming_min_mota<0 or \$incoming_min_patla<0 or \$incoming_min_saran<0 or \$outgoing_min_mota<0 or \$outgoing_min_patla<0 or \$outgoing_min_saran<0 or \$milling_capacity<0 or \$milling_capacity1<0 or \$milling_capacity2<0 or \$active<0\)',
        r'if($district<0 or $to_district<0 or $name<0 or $id<0 or $type<0 or $latitude<0 or $longitude<0 or $incoming_min_paddy<0 or $total_rice_inventory<0 or $milling_capacity<0 or $minimum_outgoing_rice<0 or $active<0)',
        text
    )

    # 4. Remove mota patla saran input validations and add new validation
    val_mota_patla_saran = r'''				if \(
					!is_numeric\(\$column\[\$incoming_min_mota\]\) \|\|.*?\}\s*\}'''
                    
    repl_val_paddy = '''                if (!is_numeric($column[$incoming_min_paddy]) || !is_numeric($column[$milling_capacity])) {
                    echo "Error : Incoming Min Paddy and Milling Capacity must be numeric.";
                    echo "</br>";
                    $redirect = 0;
                } elseif ((float)$column[$incoming_min_paddy] > (float)$column[$milling_capacity]) {
                    echo "Error : Incoming Min Paddy cannot be greater than Milling Capacity.";
                    echo "</br>";
                    $redirect = 0;
                }
                if (!is_numeric($column[$total_rice_inventory])) {
                    echo "Error : Total Rice Inventory must be numeric.";
                    echo "</br>";
                    $redirect = 0;
                }
                if (!is_numeric($column[$minimum_outgoing_rice])) {
                    echo "Error : Minimum Outgoing Rice must be numeric.";
                    echo "</br>";
                    $redirect = 0;
                }'''

    text = re.sub(val_mota_patla_saran, repl_val_paddy, text, flags=re.DOTALL)

    # 5. Header checks in switch statements
    target_switch = '''                        case $reverseMapData["incoming_min_mota"]:
                            $incoming_min_mota = $j;
                            break;
                        case $reverseMapData["incoming_min_patla"]:
                            $incoming_min_patla = $j;
                            break;
                        case $reverseMapData["incoming_min_saran"]:
                            $incoming_min_saran = $j;
                            break;
                        case $reverseMapData["outgoing_min_mota"]:
                            $outgoing_min_mota = $j;
                            break;
                        case $reverseMapData["outgoing_min_patla"]:
                            $outgoing_min_patla = $j;
                            break;
                        case $reverseMapData["outgoing_min_saran"]:
                            $outgoing_min_saran = $j;
                            break;
                        case $reverseMapData["milling_capacity"]:
                            $milling_capacity = $j;
                            break;
                        case $reverseMapData["milling_capacity1"]:
                            $milling_capacity1 = $j;
                            break;
                        case $reverseMapData["milling_capacity2"]:
                            $milling_capacity2 = $j;
                            break;'''

    repl_switch = '''                        case $reverseMapData["incoming_min_paddy"]:
                            $incoming_min_paddy = $j;
                            break;
                        case $reverseMapData["total_rice_inventory"]:
                            $total_rice_inventory = $j;
                            break;
                        case $reverseMapData["milling_capacity"]:
                            $milling_capacity = $j;
                            break;
                        case $reverseMapData["minimum_outgoing_rice"]:
                            $minimum_outgoing_rice = $j;
                            break;'''

    text = text.replace(target_switch, repl_switch)
    
    target_switch2 = '''                            case $reverseMapData["incoming_min_mota"]:
                                $incoming_min_mota = $j;
                                break;
                            case $reverseMapData["incoming_min_patla"]:
                                $incoming_min_patla = $j;
                                break;
                            case $reverseMapData["incoming_min_saran"]:
                                $incoming_min_saran = $j;
                                break;
                            case $reverseMapData["outgoing_min_mota"]:
                                $outgoing_min_mota = $j;
                                break;
                            case $reverseMapData["outgoing_min_patla"]:
                                $outgoing_min_patla = $j;
                                break;
                            case $reverseMapData["outgoing_min_saran"]:
                                $outgoing_min_saran = $j;
                                break;
                            case $reverseMapData["milling_capacity"]:
                                $milling_capacity = $j;
                                break;
                            case $reverseMapData["milling_capacity1"]:
                                $milling_capacity1 = $j;
                                break;
                            case $reverseMapData["milling_capacity2"]:
                                $milling_capacity2 = $j;
                                break;'''

    repl_switch2 = '''                            case $reverseMapData["incoming_min_paddy"]:
                                $incoming_min_paddy = $j;
                                break;
                            case $reverseMapData["total_rice_inventory"]:
                                $total_rice_inventory = $j;
                                break;
                            case $reverseMapData["milling_capacity"]:
                                $milling_capacity = $j;
                                break;
                            case $reverseMapData["minimum_outgoing_rice"]:
                                $minimum_outgoing_rice = $j;
                                break;'''

    text = text.replace(target_switch2, repl_switch2)

    # 6. Object population
    target_pop = '''                    $MillReplica->setIncomingMinMota($column[$incoming_min_mota]);
                    $MillReplica->setIncomingMinPatla($column[$incoming_min_patla]);
                    $MillReplica->setIncomingMinSaran($column[$incoming_min_saran]);
                    $MillReplica->setOutgoingMinMota($column[$outgoing_min_mota]);
                    $MillReplica->setOutgoingMinPatla($column[$outgoing_min_patla]);
                    $MillReplica->setOutgoingMinSaran($column[$outgoing_min_saran]);
                    $MillReplica->setMillingCapacity($column[$milling_capacity]);
                    $MillReplica->setMillingCapacity1($column[$milling_capacity1]);
                    $MillReplica->setMillingCapacity2($column[$milling_capacity2]);'''

    repl_pop = '''                    $MillReplica->setIncomingMinPaddy($column[$incoming_min_paddy]);
                    $MillReplica->setTotalRiceInventory($column[$total_rice_inventory]);
                    $MillReplica->setMillingCapacity($column[$milling_capacity]);
                    $MillReplica->setMinimumOutgoingRice($column[$minimum_outgoing_rice]);'''

    text = text.replace(target_pop, repl_pop)

    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(text)
    print(f"Updated {file_path}")
