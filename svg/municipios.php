<?php
	$config=parse_ini_file('../config.ini', true);

	
	header("Access-Control-Allow-Origin: *");	


	$connect=mssql_connect($config["host"], $config["username"], $config["password"]);
	
	$results=mssql_query("election.dbo.spSindicosMapa");


	header('Content-type: application/json');
	
	while ($data = mssql_fetch_array($results)) {
 		$output[] = ['id' => $data['cod_muni'], 'winner' => $data['color']];
	}
	
	
	echo json_encode($output);


