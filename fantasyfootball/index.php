<?php
/*
$connect=mysqli_connect("127.0.0.1","root","");
if(!$connect) echo "Mysql Connect Error!";
else echo "MySQL OK!";
mysqli_close($connect);
*/
#phpinfo();
?>


<!DOCTYPE html>

<?php
if(array_key_exists("rush_yd", $_GET))
{
	setcookie("rush_yd", $_GET["rush_yd"], time()+5*24*60*60, '/');
	$_COOKIE["rush_yd"] = $_GET["rush_yd"];
	echo $_COOKIE["rush_yd"];
}

if(array_key_exists("receiving_yd", $_GET))
{
	setcookie("receiving_yd", $_GET["receiving_yd"], time()+5*24*60*60, '/');
	$_COOKIE["receiving_yd"] = $_GET["receiving_yd"];
}

if(array_key_exists("ppr", $_GET))
{
	setcookie("ppr", $_GET["ppr"], time()+5*24*60*60, '/');
	$_COOKIE["ppr"] = $_GET["ppr"];
}
if(array_key_exists("pass_yd", $_GET))
{
	setcookie("pass_yd", $_GET["pass_yd"], time()+5*24*60*60, '/');
	$_COOKIE["pass_yd"] = $_GET["pass_yd"];
}
if(array_key_exists("completion", $_GET))
{
	setcookie("completion", $_GET["completion"], time()+5*24*60*60, '/');
	$_COOKIE["completion"] = $_GET["completion"];
}
if(array_key_exists("tds", $_GET))
{
	setcookie("tds", $_GET["tds"], time()+5*24*60*60, '/');
	$_COOKIE["tds"] = $_GET["tds"];
}
if(array_key_exists("int", $_GET))
{
	setcookie("int", $_GET["int"], time()+5*24*60*60, '/');
	$_COOKIE["int"] = $_GET["int"];
}
?>
<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">

<body>
	<h2>Fantasy Football Database</h2>
<nav class="w3-bar w3-black">
	<a href="index.php" method="post" class="w3-button w3-bar-item">Index</a>
	<a href="PlayerStats.php" method = "post" class="w3-button w3-bar-item">Player Stats</a>
	<a href="TeamStats.php" class="w3-button w3-bar-item">Team Stats</a>
	<a href="FantasyTeams.php" method = "post" class="w3-button w3-bar-item">Fantasy Teams</a>
</nav>
	
	<h3>Welcome to the Fantasy Football Database!</h3>
<div>
Input Your Fantasy scoring system (leave blank for defaults):
<form>
	<form action="index.php" method="POST">
		<label for "rush_yd">Points Per Rushing Yard: </label><input type="text" name="rush_yd"><br>
		<label for "receiving_yd">Points Per Receiving Yard: </label><input type="text" name="receiving_yd"><br>
		<label for "ppr">Points Per Reception: </label><input type="text" name="ppr"><br>
		<label for "pass_yd">Points Per Passing Yard: </label><input type="text" name="pass_yd"><br>
		<label for "completion">Points Per Completion: </label><input type="text" name="completion"><br>
		<label for "tds">Points Per Touchdown: </label><input type="text" name="tds"><br>
		<label for "int">Points Per Interception: </label><input type="text" name="int">
		<input type="submit" value="Submit">
	</form>
</div>

<?php
echo $_COOKIE["rush_yd"];
?>
</body>
</html>