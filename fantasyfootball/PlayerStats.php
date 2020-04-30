<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">
<body>
	<h2>Fantasy Football Database: Player Stats</h2>
<nav class="w3-bar w3-black">
	<a href="index.php" method="post" class="w3-button w3-bar-item">Index</a>
	<a href="PlayerStats.php" method = "post" class="w3-button w3-bar-item">Player Stats</a>
	<a href="TeamStats.php" class="w3-button w3-bar-item">Team Stats</a>
	<a href="FantasyTeams.php" method = "post" class="w3-button w3-bar-item">Fantasy Teams</a>
</nav>

<div>
	<em>Search for a Player</em>
	<form action="PlayerStats.php" method="post">
		<label for "pname">Player Name (Leave blank for all players):</label><input type="text" name="pname"><br>
		<label for "week">Week:</label><select name="week">
			<option value="all">All</option>
			<option value="1">Week 1</option>
		</select>
		<input type="submit" value="Submit" method="post">
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
	// Input player name and week. Applied fuzzy query. If week is a number, search for week. If week == all, entire.
	function calculatePlayerPoints($player_id, $week)
	{
		global $connect;
		
		if (array_key_exists("rush_yd", $_COOKIE))
		{
			echo "COOKIE EXISTS";
			$rushpt = ($_COOKIE["rush_yd"] == "") ? 0.1 : $_COOKIE["rush_yd"];
			$recpt = ($_COOKIE["receiving_yd"] == "") ? 0.1 : $_COOKIE["receiving_yd"];
			$ppr = ($_COOKIE["ppr"] == "") ? 0 : $_COOKIE["ppr"];
			$passpt = ($_COOKIE["pass_yd"] == "") ? 0.04 : $_COOKIE["pass_yd"];
			$comppt = ($_COOKIE["completion"] == "") ? 0 : $_COOKIE["completion"];
			$tdpt = ($_COOKIE["completion"] == "") ? 6 : $_COOKIE["completion"];
			$intpt = ($_COOKIE["int"] == "") ? -2 : $_COOKIE["int"];
		}
		else
		{
			echo "COOKIE DOES NOT EXIST";
			$rushpt = 0.1;
			$recpt = 0.1;
			$ppr = 0;
			$passpt = 0.04;
			$comppt = 0;
			$tdpt = 6;
			$intpt = -2;
		}
		
		if($week == "all")
		{
			$select_sql = "SELECT * FROM players WHERE player_id = '$player_id'";	
		}
		else
		{
			$select_sql = "SELECT * FROM playergamestats WHERE player_id = '$player_id'";
		}
		$result = mysqli_query($connect, $select_sql);
		$row = mysqli_fetch_assoc($result);
		$points = $rushpt * $row["rushing_yds"] + $recpt * $row["receiving_yds"] + $ppr * $row["catches"]
		+ $passpt * $row["passing_yards"] + $comppt * $row["completions"] 
		+ $tdpt * ($row["rushing_tds"] + $row["receiving_tds"] + $row["passing_tds"]) + $intpt * $row["interceptions"];
		mysqli_free_result($result);
		return $points;	
	}
		
	function searchPlayer($player_name, $week)
	{
		global $connect;
		if($week == "all")
		{
			$select_sql = "SELECT * FROM players WHERE player LIKE '%$player_name%'";
			if($player_name == "")
			{
				echo "Results for all players, all weeks:";
			}
			else
			{
				echo "Results for \"".$player_name."\", all weeks:";
			}
		}
		else
		{
			$select_sql = "SELECT * FROM playergamestats WHERE player LIKE '%$player_name%' AND game_id IN (SELECT game_id FROM games WHERE week = '$week')";
			//echo $select_sql;
			if($player_name == "")
			{
				echo "Results for all players Week ".$week.":";
			}
			else
			{
				echo "Results for \"".$player_name."\" Week ".$week.":";
			}
		}
		$result1 = mysqli_query($connect, $select_sql);
		$rownum = mysqli_num_rows($result1);
		echo "<table border='1'>
		<tr>
		<th>Player Name</th>
		<th>Passing Attempts</th>
		<th>Completions</th>
		<th>Completion %</th>
		<th>Passing Yards</th>
		<th>Passing TDs</th>
		<th>Interceptions</th>
		<th>Rushing Attempts</th>
		<th>Rushing Yards</th>
		<th>Rushing TDs</th>
		<th>Catches</th>
		<th>Receiving Yards</th>
		<th>Receiving TDs</th>
		<th>Fantasy Points</th>
		<th>Update Stats</th>
		</tr>";
		for($i = 0; $i < $rownum; $i++)
		{
			$row = mysqli_fetch_assoc($result1);
			//echo "</br>";
			/* If you want everything in it.
			foreach($row as $temp)
			{
				echo $temp; 
			}
			Or part of it.*/ 
			$completion_percent = ($row["passing_attempts"] == 0) ? 0 : number_format($row["completions"]/$row["passing_attempts"]*100,2);
			echo "<tr>";
			echo "<td>".$row["player"]."</td>";
			echo "<td>".$row["passing_attempts"]."</td>";
			echo "<td>".$row["completions"]."</td>";
			echo "<td>$completion_percent</td>";
			echo "<td>".$row["passing_yards"]."</td>";
			echo "<td>".$row["passing_tds"]."</td>";
			echo "<td>".$row["interceptions"]."</td>";
			echo "<td>".$row["rushing_attempts"]."</td>";
			echo "<td>".$row["rushing_yds"]."</td>";
			echo "<td>".$row["rushing_tds"]."</td>";
			echo "<td>".$row["catches"]."</td>";
			echo "<td>".$row["receiving_yds"]."</td>";
			echo "<td>".$row["receiving_tds"]."</td>";
			echo "<td>".calculatePlayerPoints($row["player_id"], $week)."</td>";
			echo "<td><form action='UpdateStats.php' method='post'><input type='hidden' name='player_id' value='".$row["player_id"]."'><button type='submit' name='week' value='$week'>Update Stats</button></form></td>";						
			echo "</tr>";
		}
		echo "</table>";
		mysqli_free_result($result1);
	}
	
	function insertPlayer($player_name, $team)
	{
		global $connect;
		$insert_sql = "INSERT INTO players(player, team) VALUES ('$player_name', '$team')";
		if(mysqli_query($connect, $insert_sql))
		{
			echo "$player_name added to table.";
		}
		else
		{
			echo "Error adding player to table.";
		}
	}
	if(array_key_exists("player", $_POST))
	{
		insertPlayer($_POST["player"], $_POST["team"]);
	}
	if(array_key_exists("pname",$_POST) && array_key_exists("week",$_POST))
	{
		searchPlayer($_POST["pname"],$_POST["week"]);
	}
	echo "
	<div>
		<em>Add Player to Database:</em>
		<form action='PlayerStats.php' method='post'>
			<label for 'player'>Player Name: </label><input type='text' name='player'>
			<label for 'team'>Team: </label><input type='text' name='team'>
			<input type='submit' value='Submit'>
		</form>
	</div>
	";
		
	
	mysqli_close($connect);
?>
</body>
</html>
