<?php
header('Content-Type: application/json');

// Start the session
require_once('../../templates/startSession.php');
require_once('../../connectVars.php');

// Authenticate user
require_once('../../templates/auth.php');
checkUserRole('admin', $auth_error);

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
// fetch placement stats
$placedQuery = "SELECT D.degree_name AS degree_name, B.branch_name AS branch_name, "
			."placed_table.placed AS placed, remaining_table.remaining AS remaining "
			."FROM (SELECT db_id, 0 AS placed FROM degree_branch WHERE db_id NOT IN (SELECT db_id FROM students_data WHERE final_accepted_offer!='') "
			."UNION "
			."SELECT db_id, COUNT(roll_number) AS placed FROM students_data WHERE final_accepted_offer!='' GROUP BY db_id) AS placed_table, "
			."(SELECT db_id, 0 AS remaining FROM degree_branch WHERE db_id NOT IN (SELECT db_id FROM students_data WHERE final_accepted_offer IS NULL OR final_accepted_offer='') "
			."UNION "
			."SELECT db_id, COUNT(roll_number) AS remaining FROM students_data WHERE final_accepted_offer IS NULL OR final_accepted_offer='' GROUP BY db_id) AS remaining_table, "
			."degree AS D, branch AS B, degree_branch AS DB "
			."WHERE placed_table.db_id = remaining_table.db_id AND placed_table.db_id = DB.db_id "
			."AND D.degree_id = DB.degree_id AND B.branch_id = DB.branch_id";
$placedResult = mysqli_query($dbc, $placedQuery);

if(!$placedResult){
	die("QUERY FAILED ".mysqli_error($dbc));
}

$data = array();

while($row = mysqli_fetch_array($placedResult)){
	$entity = array();
	$entity['course'] = $row['degree_name'];
	$entity['branch'] = $row['branch_name'];
	$entity['placed'] = $row['placed'];
	$entity['remaining'] = $row['remaining'];
	$data[] = $entity;
}

mysqli_close($dbc);

echo json_encode($data);
?>
