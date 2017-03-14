<?php
header('Content-Type: application/json');

include_once("db.php");

if (!mysql_connect($sbhost, $dbuser, $dbpasswd))
	die("Impossibile connettersi al database");
if (!mysql_select_db($dbname))
	die("Impossibile selezionare il database");

$sps_names = array();
$result = mysql_query("SELECT sp, name FROM sps");
while($row = mysql_fetch_row($result)) {
	$sps_names[$row[0]] = $row[1];
}
mysql_free_result($result);

$dati = $_GET['dati'];
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

$result = mysql_query($query);
$fields_num = mysql_num_fields($result);
while($row = mysql_fetch_row($result)) {
	$itemname = $row[0];
	if ($dati != "sp" and array_key_exists($itemname, $sps_names)) $itemname = $sps_names[$itemname];
	$datatable['values'][] = array($itemname, intval($row[1]));
}
mysql_free_result($result);

echo json_encode($datatable);
?>
