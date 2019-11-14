<?php
header('Content-Type: application/json');

// Start the session
require_once('../../templates/startSession.php');
require_once('../../connectVars.php');

// Authenticate user
require_once('../../templates/auth.php');
checkUserRole('admin', $auth_error);

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


// fetch $company stats
$companyQuery = "SELECT company_category.name AS category_name, COUNT(recruiters_data.company_id) AS count "
                ."FROM company_category, recruiters_data WHERE recruiters_data.company_category_id = company_category.id "
                ."GROUP BY recruiters_data.company_category_id";
$companyResult = mysqli_query($dbc, $companyQuery);

if(!$companyResult){
	die("QUERY FAILED ".mysqli_error($dbc));
}

$data = array();

while($row = mysqli_fetch_array($companyResult)){
	$entity = array();
	$entity['category_name'] = $row['category_name'];
	$entity['count'] = $row['count'];
	$data[] = $entity;
}

mysqli_close($dbc);

echo json_encode($data);
?>
