<?php
header('Content-Type: application/json');

include_once("db.php");

$conn = mysqli_connect($sbhost, $dbuser, $dbpasswd, $dbname);

// Check connection
if (mysqli_connect_errno()) {
   die("Failed to connect to 'statistics' database: " . mysqli_connect_error());
}

$sps_names = array();
$result = mysqli_query($conn,"SELECT sp, name FROM sps");
while($row = mysqli_fetch_row($result)) {
   $sps_names[$row[0]] = $row[1];
}
mysqli_free_result($result);

$dati = isset($_GET['dati']) ? $_GET['dati'] : "sp";
$item = $_GET['item'];
if ($dati == "sp") {
   $curitem = array_search($item, $sps_names);
   if ($curitem != NULL) $item = $curitem;
}
$data = $_GET['data'];

if ($dati == "sp") {
   $query = "SELECT user, logins FROM logins WHERE data = '" . $data . "' and sp = '" . $item . "'";
} else {
   $query = "SELECT sp, logins FROM logins WHERE data = '" . $data . "' and user = '" . $item . "'";
}

$datatable = array();
$datatable['values'] = array();

$result = mysqli_query($conn,$query);
$fields_num = mysqli_num_fields($result);
while($row = mysqli_fetch_row($result)) {
   $itemname = $row[0];
   if ($dati != "sp" and array_key_exists($itemname, $sps_names)) $itemname = $sps_names[$itemname];
   $datatable['values'][] = array($itemname, intval($row[1]));
}
mysqli_free_result($result);

echo json_encode($datatable);
?>
