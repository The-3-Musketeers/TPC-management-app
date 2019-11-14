<?php
header('Content-Type: application/json');

require_once('../connectVars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// fetch all Degrees and Branches
$placedQuery = "SELECT D.degree_name AS degree_name, B.branch_name AS branch_name "
			."FROM degree AS D, branch AS B, degree_branch AS DB "
			."WHERE D.degree_id = DB.degree_id AND B.branch_id = DB.branch_id";
$placedResult = mysqli_query($dbc, $placedQuery);

if(!$placedResult){
	die("QUERY FAILED ".mysqli_error($dbc));
}

$data = array();

while($row = mysqli_fetch_array($placedResult)){
	$data[$row['degree_name']][]=$row['branch_name'];
}

mysqli_close($dbc);

echo json_encode($data);
?>
