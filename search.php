<html>
<head>
	<title>Covidsearch</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="html_components/styles.css">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link href="images/favicon.png" type="image/gif" rel="shortcut icon">
</head>

<body>

<?php

if (!isset($_POST["search_string"]) && !(isset($_GET["age"]) && isset($_GET["symptoms"]) && isset($_GET["conditions"])))
{

	$stream = fopen("html_components/questionnaire.html", "r");
	$line="";
	while(($line=fgets($stream))!=false)
	{
		echo $line;
	}
	fclose($stream);
} else
{
	echo "<form action=\"search.php\" method=\"post\">";
	echo "<input type=\"text\" size=40 name=\"search_string\" value=\"{$_POST["search_string"]}\"/>";
	echo "<input type=\"submit\" value=\"Search\"/>";
	echo "</form>";
	if (isset($_POST["search_string"]))
	{
		$search_string = $_POST["search_string"];
		$qfile = fopen("session/query", "w");
		fwrite($qfile, $search_string);
		fclose($qfile);

		$logfile = fopen("query_log.txt", "a");
		$rightnow = date("Y-m-d H:i:s");
		fwrite($logfile, "$rightnow $search_string\n");
		fclose($logfile);

   		exec("echo {$search_string} | nc -u 127.0.0.1 10174");
   		sleep(3);
		
   		$stream = fopen("session/results", "r");

   		$line=fgets($stream);

   		while(($line=fgets($stream))!=false)
   		{
			echo $line;
   		}

   		fclose($stream);
	} else
	{
		$groupfile = fopen("session/group", "w");
		fwrite($groupfile, $_GET["age"]);
		fwrite($groupfile, $_GET["symptoms"]);
		fwrite($groupfile, $_GET["conditions"]);
		fclose($groupfile);
	}
}
?>

</body>
</html>
