<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">
<body>
	<h2>Fantasy Football Database: Team Stats</h2>
<nav class="w3-bar w3-black">
	<a href="index.php" method="post" class="w3-button w3-bar-item">Index</a>
	<a href="PlayerStats.php" method = "post" class="w3-button w3-bar-item">Player Stats</a>
	<a href="TeamStats.php" class="w3-button w3-bar-item">Team Stats</a>
	<a href="FantasyTeams.php" method = "post" class="w3-button w3-bar-item">Fantasy Teams</a>
</nav>
<div>
	<em>View Team Stats</em>
	<form action="TeamStats.php" method="post">
		<label for "team">Select a Conference or Division:</label><select name="team">
			<option value="all">All NFL</option>
			<option value="afc">AFC</option>
			<option value="nfc">NFC</option>
			<option value="afce">AFC East</option>
			<option value="afcn">AFC North</option>
			<option value="afcs">AFC South</option>
			<option value="afcw">AFC West</option>
			<option value="nfce">NFC East</option>
			<option value="nfcn">NFC North</option>
			<option value="nfcs">NFC South</option>
			<option value="nfcw">NFC West</option>
		</select>
		<br>
		<label for "week">Week:</label><select name="week">
			<option value="all">All</option>
			<option value="1">Week 1</option>
		</select>
		<input type="submit" value="Submit">
	</form>
</div>
<?php
	/*
	$usernumber= $_POST['username'];
	if($username)
	{
		echo "Error: There is no data passed";
		exit();
	}
	$len = strlen($usernumber);//获取长度
	//phpinfo();
	*/
	$connect=mysqli_connect("127.0.0.1","root","","fantasyfootball");
	if(!$connect) echo "Mysql Connect Error!";
	//else echo "MySQL OK!";
	echo "</br>";
	//If you want to gain division from DB, you may use the following commeted code.
	//$select_sql = "SELECT division FROM teams";
	//$division = ;
	//$select_sql = "SELECT division FROM teams WHERE division = '$division'";
	//The meaning of argument is the same with PlayerStats.php
	function searchTeam($division, $week)
	{
		global $connect;//TeamStats
		if($week == "all")
		{
			if($division == "all")
			{
				echo "Results for all NFL for all weeks:";
				$select_sql = "SELECT * FROM teams";
			}
			else
			{
				echo "Results for ".$division." for all weeks:";
				$select_sql = "SELECT * FROM teams WHERE division LIKE '$division%'";
				//echo $select_sql;
			}
		}
		else
		{
			if($division == "all")
			{
				echo "Results for all NFL for Week ".$week.":";
			$select_sql = "SELECT team_name, away_team AS 'opponent', home_rush AS 'rushing_yds', home_pass AS 'passing_yds', home_tds AS 'tds', away_rush AS 'rushing_against', away_pass AS 'passing_against', away_tds AS 'tds_against' FROM teams INNER JOIN games ON team_name = home_team WHERE week = '$week'
					UNION SELECT team_name, home_team AS 'opponent', away_rush AS 'rushing_yds', away_pass AS 'passing yds', away_tds AS 'tds', home_rush AS 'rushing_against', home_pass AS 'passing_against', home_tds AS 'tds_against' FROM teams INNER JOIN games ON team_name = away_team WHERE week = '$week'";
			}
			else
			{
				echo "Results for ".$division." for Week ".$week.":";
				$select_sql = "SELECT team_name, away_team AS 'opponent', home_rush AS 'rushing_yds', home_pass AS 'passing_yds', home_tds AS 'tds', away_rush AS 'rushing_against', away_pass AS 'passing_against', away_tds AS 'tds_against' FROM teams INNER JOIN games ON team_name = home_team WHERE week = '$week' AND division LIKE '$division%'
						UNION SELECT team_name, home_team AS 'opponent', away_rush AS 'rushing_yds', away_pass AS 'passing yds', away_tds AS 'tds', home_rush AS 'rushing_against', home_pass AS 'passing_against', home_tds AS 'tds_against' FROM teams INNER JOIN games ON team_name = away_team WHERE week = '$week' AND division LIKE '$division%'";
				//echo $select_sql;
			}
		}
		echo "<table border='1'>
		<tr>
		<th>Team Name</th>";
		if($week != 'all')
		{
			echo "<th>Opponent</th>";
		}
		echo "<th>Rushing Yards</th>
		<th>Passing Yards</th>
		<th>TDs</th>
		<th>Rushing Yards Against</th>
		<th>Passing Yards Against</th>
		<th>TDs Against</th>
		</tr>";
		
		$result1 = mysqli_query($connect, $select_sql);
		$rownum = mysqli_num_rows($result1);
		for($i = 0; $i < $rownum; $i++)
		{
			$row = mysqli_fetch_assoc($result1);
			//echo "</br>";
			//Here is the output for different week inputed(num or "all"), you may need them.
			//echo "Rushing YDS: "$row["rushing_yds"]."	Passing YDS: ".$row["passing_yds"];
			echo "<tr>";
			echo "<td>".$row["team_name"]."</td>";
			if(array_key_exists("opponent", $row))
			{
				echo "<td>".$row["opponent"]."</td>";
			}
			echo "<td>".$row["rushing_yds"]."</td>";
			echo "<td>".$row["passing_yds"]."</td>";
			echo "<td>".$row["tds"]."</td>";
			echo "<td>".$row["rushing_against"]."</td>";
			echo "<td>".$row["passing_against"]."</td>";
			echo "<td>".$row["tds_against"]."</td>";
		}
		mysqli_free_result($result1);
	}
	if (array_key_exists("team",$_POST) && array_key_exists("week", $_POST))
	{
		searchTeam($_POST["team"],$_POST["week"]);
	}
	mysqli_close($connect);
?>
</body>
</html>
