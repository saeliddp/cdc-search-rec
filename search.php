<html>
<head>
	<title>Covidsearch</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="html_components/styles.css">
	<link rel="stylesheet" href="style.php">
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
	echo "<img src=\"images/logo.png\" class=\"home-page-img\" alt=\"logo\">";

	echo "<div class=\"home\" style=\"text-align: center;\">";
		echo "<form action=\"search.php\" method=\"post\">";
			echo "<input type=\"text\" class=\"home-search-bar\" name=\"search_string\" value=\"{$_POST["search_string"]}\"/>";
			echo "<input type=\"submit\" class=\"home-search-submit\" value=\"Search\"/>";
		echo "</form>";
	echo "</div>";

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
<footer>
	<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">
		<img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png"/>
	</a>
	<p>This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>.</p>
</footer>
</body>
</html>
