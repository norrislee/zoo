<html>
	<head>
		<title>ZOO // CS 304</title>
		<meta name="description" content="Our cool zoo! Made by Sebastian Kazenbroot-Guppy, Norris Lee and Harlen Bains."
		<link rel="stylesheet" type="text/css" href="main.css">
	</head>
	<body> 
		<div id="mycontainer">
			<button type="button" name="logout">Log Out</btn>

			<?php
				$success = True; //keep track of errors so it redirects the page only if there are no errors
				$db_conn = OCILogon("ora_w8x7", "a67961045", "ug");

				require ('functions.php');

				if ($db_conn) {
					$result = executePlainSQL("select * from zoo");
					printAllZoos($result);
				
						//Commit to save changes...
					OCILogoff($db_conn);
				} else {
					echo "cannot connect";
					$e = OCI_Error(); // For OCILogon errors pass no handle
					echo htmlentities($e['message']);
				}
			?>

			<div id="log">ourLog</div>
		</div>

		<script type="text/javascript" src="http://gridster.net/assets/js/libs/jquery-1.7.2.min.js"></script>
		<script src="application.js" type="text/javascript"></script>
	</body>
</html>