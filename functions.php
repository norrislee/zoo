<?php

/* CONSTANTS */

/* ZOO CONSTANTS */
$defaultcash = 10000;

/* ANIMAL CONSTANTS */

/* Charizard */
$charizardBodySize = 4;
$charizardCost = 1000;

/* Snorlax */

$snorlaxBodySize = 5;
$snorlaxCost = 1500;

/* Witches */

$witchesBodySize = 2;
$witchesCost = 800;

/* Giraffes */

$giraffeBodySize = 3;
$giraffeCost = 300;

/* Ants */

$antBodySize = 1;
$antCost = 10;

function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
	//echo "<br>running ".$cmdstr."<br>";
	global $db_conn, $success;
	$statement = OCIParse($db_conn, $cmdstr); //There is a set of comments at the end of the file that describe some of the OCI specific functions and how they work

	if (!$statement) {
		echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
		$e = OCI_Error($db_conn); // For OCIParse errors pass the       
		// connection handle
		echo htmlentities($e['message']);
		$success = False;
	}

	$r = OCIExecute($statement, OCI_DEFAULT);
	if (!$r) {
		echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
		$e = oci_error($statement); // For OCIExecute errors pass the statementhandle
		echo htmlentities($e['message']);
		$success = False;
	} else {

	}
	return $statement;
}

function executeBoundSQL($cmdstr, $list) {
	/* Sometimes a same statement will be excuted for severl times, only
	 the value of variables need to be changed.
	 In this case you don't need to create the statement several times; 
	 using bind variables can make the statement be shared and just 
	 parsed once. This is also very useful in protecting against SQL injection. See example code below for how this functions is used */

	global $db_conn, $success;
	$statement = OCIParse($db_conn, $cmdstr);

	if (!$statement) {
		echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
		$e = OCI_Error($db_conn);
		echo htmlentities($e['message']);
		$success = False;
	}

	foreach ($list as $tuple) {
		foreach ($tuple as $bind => $val) {
			//echo $val;
			//echo "<br>".$bind."<br>";
			OCIBindByName($statement, $bind, $val);
			unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype

		}
		$r = OCIExecute($statement, OCI_DEFAULT);
		if (!$r) {
			echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
			$e = OCI_Error($statement); // For OCIExecute errors pass the statementhandle
			echo htmlentities($e['message']);
			echo "<br>";
			$success = False;
		}
	}

}

function printResult($result) { //prints results from a select statement
	echo "<br>Got data from table tab1:<br>";
	echo "<table>";
	echo "<tr><th>ID</th><th>Name</th></tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row["NID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; // or just use "echo $row[0]" 
	}
	echo "</table>";

}

function printZooTable($result) { //prints results from a select statement
	echo "<br>Got data from table tab1:<br>";
	echo "<table cellspacing='0'>";
	echo "<thead><tr><th>ID</th><th>Name</th></tr></thead><tbody>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row["NID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; // or just use "echo $row[0]" 
	}
	echo "</tbody></table>";

}

function printAnimal($result) {
	echo "<table cellspacing='0'>";
	echo "<thead><tr><th>Animal Name</th><th>Type</th><th>Hydration</th><th>Fullness</th><th>Hygiene</th><th>Happiness</th><th>Body Size</th></tr></thead><tbody>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row["NAME"] . "</td><td>" . $row["TYPE"] . "</td><td>" . $row["HYDRATION"] . "</td><td>" . $row["FULLNESS"] . "</td><td>" . $row["HYGIENE"] . "</td><td>" . $row["HAPPINESS"] . "</td><td>" . $row["BODYSIZE"] . "</td></tr>"; //or just use "echo $row[0]" 
	}
	echo "</tbody></table>";
}
    
    
    
function printItems($result) { //prints items from a select statement
    echo "<table cellspacing='0'>";
    echo "<thead><tr><th>Name</th><th>Hydration Effect</th><th>Fullness Effect</th><th>Hygiene Effect</th><th>Happiness Effect</th><th>Amount</th><th>Price</th><th></th></tr></thead><tbody>";
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["NAME"] . "</td><td>" . $row["HYDRATIONEFFECT"] . "</td><td>" . $row["FULLNESSEFFECT"] . "</td><td>" . $row["HYGIENEEFFECT"] . "</td><td>" . $row["HAPPINESSEFFECT"] . "</td><td>" . $row["AMOUNT"] . "</td><td>" . $row["PRICE"] . "</td><td><input type='submit' value='Buy' name='" . $row["NAME"] . "'></p></td></tr>";
    }
    echo "</tbody></table>";
}

function printAnimalsWithButtons($result) { //prints results from a select statement
	echo "<table cellspacing='0'>";
	echo "<thead><tr><th>Pen #</th><th>Animal Name</th><th>Type</th><th>Hydration</th><th>Fullness</th><th>Hygiene</th><th>Happiness</th><th>Body Size</th><th>Pen Quality</th><th>Current Population</th><th>Tend To</th><th>Slaughter!</th></tr></thead><tbody>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row["PEN_ID"] . "</td><td>" . $row["NAME"] . "</td><td>" . $row["TYPE"] . "</td><td>" . $row["HYDRATION"] . "</td><td>" . $row["FULLNESS"] . "</td><td>" . $row["HYGIENE"] . "</td><td>" . $row["HAPPINESS"] . "</td><td>" . $row["BODYSIZE"] . "</td><td>" . $row["QUALITY"] . "</td><td>" . $row["CURRENTPOPULATION"] . "</td><td>" . "<button id='" . $row['NAME'] . "' class='tendTo' type='button'>Tend To</button>" . "</td><td><form name='" . $row["NAME"] . "' action='zoo.php' method='post'><input type='hidden' name='delanimalname' value='" . $row["NAME"] . "'><input type='submit' name='delButton' value='Slaughter'></form></td></tr>"; //or just use "echo $row[0]"
	}
	echo "</tbody></table>";
}
    
function printReport($result) { //prints results from a select statement
    echo "<table cellspacing='0'>";
    echo "<thead><tr><th>Day</th><th>Cash</th></tr></thead><tbody>";
        
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["DAY"] . "</td><td>" . $row["CASH"] . "</td></tr>";
    }
    echo "</tbody></table>";
}
    
function printPens($result) {
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<div class='centered'># of pens: " . $row["PENS"] . "</div>";
    }
}
    
function printNestedAggregationTable($result) { //prints results from a select statement
    echo "<table cellspacing='0'>";
    echo "<thead><tr><th>Zoo Name</th></tr></thead><tbody>";
        
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["NAME"] . "</td></tr>";
    }
    echo "</tbody></table>";
}
    
function printAllZoos($result) { //prints results from a select statement
	echo "<table cellspacing='0'>";
	echo "<thead><tr><th></th><th>Zoo Name</th><th>Owner Name</th><th>Cash</th></tr></thead><tbody>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td> <button type='button' class='logon' id='" . $row["NAME"] . "'>Log On</button>" . "</td><td>" . $row["NAME"] . "</td><td>" . $row["OWNERNAME"] . "</td><td>" . $row["CASH"] . "</td><td><form name='" . $row["NAME"] . "' action='index.php' method='post'><input type='hidden' name='delzooname' value='" . $row["NAME"] . "'><input type='submit' name='delButton' value='Delete'></form>" . "</td></tr>";
	}
    echo "</tbody></table>";
}
?>