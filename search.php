<html>
<head>
	<title>My Simple Search</title>
</head>

<body>

<h2>My Simple Search</h2>

<form action="search.php" method="post">
<input type="text" size=40 name="search_string" value="<?php echo $_POST["search_string"];?>"/>
<input type="submit" value="Search"/>
</form>

<?php

if (isset($_POST["search_string"]))
{
   $search_string = $_POST["search_string"];
   $qfile = fopen("query.py", "w");

   fwrite($qfile, "import pyterrier as pt\nif not pt.started():\n\tpt.init()\n\n");
   fwrite($qfile, "import pandas as pd\nqueries = pd.DataFrame([[\"q1\", \"$search_string\"]], columns=[\"qid\",\"query\"])\n");
   fwrite($qfile, "index = pt.IndexFactory.of(\"./cleaned/cdc_index/data.properties\")\n");
   fwrite($qfile, "bm25 = pt.BatchRetrieve(index, wmodel=\"BM25\", metadata=[\"docno\", \"filename\", \"title\"], num_results=15)\n");
   fwrite($qfile, "results = bm25.transform(queries)\n");
   fwrite($qfile, "for i in range(len(results)):\n\tprint(results[\"filename\"][i])\n\tprint(results[\"title\"][i])\n");
   
   fclose($qfile);

   exec("ls | nc -u 127.0.0.1 10104");
   sleep(3);

   $stream = fopen("output", "r");

   $line=fgets($stream);

   while(($line=fgets($stream))!=false)
   {
	$clean_line = preg_replace('/\s+/',',',$line);
	$record = explode("./", $clean_line);
	$line = fgets($stream);
	echo "<a href=\"http://$record[1]\">".$line."</a><br/>\n";
   }

   fclose($stream);
   
   exec("rm query.py");
   exec("rm output");
}
?>

</body>
</html>
