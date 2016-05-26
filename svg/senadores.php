<?php
	$config=parse_ini_file('../config.ini', true);

	
	header("Access-Control-Allow-Origin: *");

		
	$connect=mssql_connect($config["host"], $config["username"], $config["password"]);
		
	$results=mssql_query("election.dbo.spSenadoresMapaBancas");
	

	header('Content-type: application/json');
	
	$counter=0;
	while ($data = mssql_fetch_array($results)) {
		$counter += 1;
 		$output[] = ['id' => $counter, 'winner' => $data['color']];
	}

	
	echo json_encode($output);

