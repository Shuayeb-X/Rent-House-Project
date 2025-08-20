<?php
require_once __DIR__ . "/config.php";
header("Content-Type: application/json");

$lat = isset($_GET['lat']) ? floatval($_GET['lat']) : null;
$lon = isset($_GET['lon']) ? floatval($_GET['lon']) : null;
$location = $_GET['location'] ?? '';
$category = $_GET['category'] ?? '';
$max_price = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? floatval($_GET['max_price']) : null;

$params = [];
$wheres = [];
$selectDistance = "";

if ($lat !== null && $lon !== null) {
    $selectDistance = "(6371 * acos(cos(radians(?)) * cos(radians(COALESCE(latitude, 0))) * 
                       cos(radians(COALESCE(longitude, 0)) - radians(?)) + sin(radians(?)) * 
                       sin(radians(COALESCE(latitude, 0))))) AS distance,";
    $params[] = $lat; $params[] = $lon; $params[] = $lat;
}

if ($location !== '') { $wheres[] = "location LIKE ?"; $params[] = "%" . $location . "%"; }
if ($category !== '') { $wheres[] = "category = ?"; $params[] = $category; }
if ($max_price !== null) { $wheres[] = "price <= ?"; $params[] = $max_price; }

$whereSql = count($wheres) ? ("WHERE " . implode(" AND ", $wheres)) : "";

$sql = "SELECT id, owner_id, title, location, category, price, image_path, latitude, longitude" .
       ($selectDistance ? ", $selectDistance" : " ") . "FROM houses $whereSql";

$sql .= $selectDistance ? " ORDER BY distance ASC" : " ORDER BY id DESC";

$stmt = $conn->prepare($sql);

$types = str_repeat("d", $selectDistance ? 3 : 0);
foreach ($wheres as $w) {
    if (strpos($w, "price") !== false) { $types .= "d"; } else { $types .= "s"; }
}
if ($types) { $stmt->bind_param($types, ...$params); }

$stmt->execute();
$res = $stmt->get_result();

$out = [];
while ($row = $res->fetch_assoc()) { if (!isset($row['distance'])) $row['distance']=null; $out[] = $row; }
echo json_encode($out);
?>
