<?php
$stream = fopen("session/group", "r");
$group = fgets($stream);
fclose($stream);
$docno = intval(substr($_GET['docno'], 1), 10);

$csv_lines = array();
$csv_stream = fopen("matrix.csv", "r");
while (($line = fgetcsv($csv_stream)) !== False)
{
	if (strcmp($line[0], $group) === 0) {
		$line[$docno] = strval((intval($line[$docno], 10) + 1));
	}
	array_push($csv_lines, $line);	
}
fclose($csv_stream);

$csv_writer = fopen("matrix.csv", "w");
foreach ($csv_lines as $value) {
	fputcsv($csv_writer, $value);
}
fclose($csv_writer);

header("Location: {$_GET['real_url']}");
exit();
?>
