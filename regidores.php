<?php
	$config=parse_ini_file('./config.ini', true);


	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=regidores.csv');


	$output = fopen('php://output', 'w');
	fputcsv($output, array('Municipio', 'Circunscripcion', 'Codigo partido', 'Partido', 'Seats'));


	$connect=mssql_connect($config["host"], $config["username"], $config["password"]);
	mssql_select_db('election');
	$results=mssql_query("select * from dbo.dhondtMunicipal");

	while ($result = mssql_fetch_assoc($results)) fputcsv($output, $result);
