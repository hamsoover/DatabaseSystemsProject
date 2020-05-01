<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">
<body>
	<h2>Fantasy Football Database: Fantasy Teams</h2>
<nav class="w3-bar w3-black">
	<a href="index.php" method="post" class="w3-button w3-bar-item">Index</a>
	<a href="PlayerStats.php" method = "post" class="w3-button w3-bar-item">Player Stats</a>
	<a href="TeamStats.php" class="w3-button w3-bar-item">Team Stats</a>
	<a href="FantasyTeams.php" method = "post" class="w3-button w3-bar-item">Fantasy Teams</a>
</nav>
<div>
	<em>Input New Fantasy Team</em>
	<form action="FantasyTeams.php" method="post">
		<label for "new_team">Team Name:</label><input type="text" name="new_team">
		<input type="submit" value="Submit">
	</form>
</div>

<div>
	<em>Display Fantasy Teams</em>
	<form action="FantasyTeams.php" method="post">
		<label for "week">Choose a week:</label><select name="week">
			<option value="all">All</option>
			<option value="1">Week 1</option>
		</select>
		<input type="submit" value="Submit">
	</form>
</div>

<?php
	session_start();
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
	//The standard of calculating is here, you may need to change them as wish.
	$coe_1 = 0.1;
	$coe_2 = 0.04;
	$coe_3 = 6;
	$coe_4 = -2;
	$connect=mysqli_connect("127.0.0.1","root","","fantasyfootball");
	if(!$connect) echo "Mysql Connect Error!";
	//else echo "MySQL OK!";
	//echo "</br>";
	
	function calculatePlayerPoints($player_id, $week)
	{
		global $connect;
		
		if (array_key_exists("rush_yd", $_SESSION))
		{
			$rushpt = ($_SESSION["rush_yd"] == "") ? 0.1 : $_SESSION["rush_yd"];
			$recpt = ($_SESSION["receiving_yd"] == "") ? 0.1 : $_SESSION["receiving_yd"];
			$ppr = ($_SESSION["ppr"] == "") ? 0 : $_SESSION["ppr"];
			$passpt = ($_SESSION["pass_yd"] == "") ? 0.04 : $_SESSION["pass_yd"];
			$comppt = ($_SESSION["completion"] == "") ? 0 : $_SESSION["completion"];
			$tdpt = ($_SESSION["tds"] == "") ? 6 : $_SESSION["tds"];
			$intpt = ($_SESSION["int"] == "") ? -2 : $_SESSION["int"];
		}
		else
		{
			$rushpt = 0.1;
			$recpt = 0.1;
			$ppr = 0;
			$passpt = 0.04;
			$comppt = 0;
			$tdpt = 6;
			$intpt = -2;
		}
		
		if($week = "all")
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
	
		
	function calculateTeamPoints($team_name, $week)
	{
		global $connect;
		
		$select_sql = "SELECT player_id FROM players WHERE fantasy_id = '$team_name'";
		$result = mysqli_query($connect, $select_sql);
		$rownum = mysqli_num_rows($result);
		$total_points = 0.0;
		
		for($i = 0; $i < $rownum; $i++)
		{
			$row = mysqli_fetch_assoc($result);
			$total_points += calculatePlayerPoints($row["player_id"], $week);
		}
		mysqli_free_result($result);
		return $total_points;
	}
		
	function displayFTeams($week)
	{
		echo "<table border='1'>
		<tr>
		<th>Team Name</th>
		<th>Fantasy Points</th>
		<th>See Players</th>
		<th>Delete Team</th>
		</tr>";
		
		global $connect, $coe_1, $coe_2, $coe_3, $coe_4;
		if($week == "all")
		{
			echo "Results for all weeks:";
			$select_sql = "SELECT * FROM fantasyteams";
			//echo $select_sql;
			$result1 = mysqli_query($connect, $select_sql);
			$rownum = mysqli_num_rows($result1);
			for($i = 0; $i < $rownum; $i++)
			{
				$row = mysqli_fetch_assoc($result1);
				echo "<tr>";
				echo "<td>".$row["team_name"]."</td>";
				echo "<td>".calculateTeamPoints($row["team_name"], $week)."</td>";
				echo "<td><form action='FantasyPlayers.php' method='post'><button name='team' value='".$row["team_name"]."' type='submit'>See Players</button></form></td>";
				echo "<td><form action='FantasyTeams.php' method='post'><button name='delteam' value='".$row["team_name"]."' type='submit'>Delete Team</button></form></td>";
				echo "</tr>";
				//echo "</br>";
				//echo "Team name: ".$row["team_name"]."	Fantasy points: ".$row["fantasy_points"];
			}
			mysqli_free_result($result1);
		}
		else
		{
			echo "Results for week ".$week.":";
			$select_sql = "SELECT * FROM fantasyteams";
			//echo $select_sql;
			$result1 = mysqli_query($connect, $select_sql);
			$rownum = mysqli_num_rows($result1);
			for($i = 0; $i < $rownum; $i++)
			{
				$row = mysqli_fetch_assoc($result1);
				$temp = $row["team_name"];
				$select2_sql = "SELECT playergamestats.*, fantasy_id FROM playergamestats INNER JOIN players ON players.player_id = playergamestats.player_id WHERE fantasy_id = '$temp' AND game_id IN (SELECT game_id FROM games WHERE week = '$week')";
				$result1_second = mysqli_query($connect, $select2_sql);
				$row_num = mysqli_num_rows($result1_second);
				$total_points = 0;
				for($j = 0; $j < $row_num; $j++)
				{
					//echo "</br>";
					$row = mysqli_fetch_assoc($result1_second);
					$points = (($row["rushing_yds"] + $row["receiving_yds"]) * $coe_1) 
							+ ($row["passing_yards"] * $coe_2) 
							+ (($row["rushing_tds"] + $row["passing_tds"] + $row["receiving_tds"]) * $coe_3) 
							+ ($row["interceptions"] * $coe_4);
					$total_points += $points;
				}
				echo "<tr>";
				echo "<td>".$temp."</td>";
				echo "<td>".$total_points."</td>";
				echo "<td><form action='FantasyPlayers.php' method='post'><button name='team' value='".$temp."' type='submit'>See Players</button></form></td>";
				echo "<td><form action='FantasyTeams.php' method='post'><button name='delteam' value='".$temp."' type='submit'>Delete Team</button></form></td>";
				echo "</tr>";
				//echo "Team name: ".$temp."	Fantasy points: ".$total_points;
			}
			mysqli_free_result($result1);
		}
	}
	//Insert a new team.
	function newTeam($name)
	{
		global $connect;
		if(strlen($name) > 30)
		{
			echo "Error: team name too long.";
			return;
		}
		
		$insert_sql = "INSERT INTO fantasyteams(team_name) VALUES ('$name')";
		//echo $insert_sql;
		if (mysqli_query($connect, $insert_sql))
		{
			//echo "New record created successfully";
		} 
		else 
		{
			echo "Error: " . $insert_sql . "<br>" . mysqli_error($connect);
		}
	}
	//Given a team name, search for all members in table Players.
	/*function displayPlayers($team_name)
	{
		echo "Showing players for ".$team_name.":";
		echo "<table border='1'>
		<tr>
		<th>Player Name</th>
		<th>Fantasy Points</th>
		<th>Remove Player</th>
		</tr>";
		global $connect, $coe_1, $coe_2, $coe_3, $coe_4;
		$select_sql = "SELECT * FROM players WHERE fantasy_id = '$team_name'";
		$result1 = mysqli_query($connect, $select_sql);
		$rownum = mysqli_num_rows($result1);
		for($i = 0; $i < $rownum; $i++)
		{
			$row = mysqli_fetch_assoc($result1);
			//echo "</br>";
			//echo "Player name: ".$row["player"]."	Fantasy points: ".$points;
			echo "<tr>";
			echo "<td>".$row["player"]."</td>";
			echo "<td>".calculatePlayerPoints($row["player_id"], "all")."</td>";
		}
	}*/
	//Given a team name and detailed player name. You may need to use or copy fuzzy query I write on PlayerStats.php.
	/*function addNewPlayers($player_name, $fantasy_team)
	{
		global $connect;
		$update_sql = "UPDATE players SET fantasy_id='$fantasy_team' WHERE player = '$player_name'";
		if (mysqli_query($connect, $update_sql))
		{
			echo "Table players updated successfully.";
		} 
		else 
		{
			echo "Error: " . $update_sql . "<br>" . mysqli_error($connect);
		}
		echo "</br>";
		$update_sql = "UPDATE playergamestats SET fantasy_team='$fantasy_team' WHERE player LIKE '$player_name%'";
		if (mysqli_query($connect, $update_sql))
		{
			echo "Table PlayerGameStats updated successfully.";
		} 
		else 
		{
			echo "Error: " . $update_sql . "<br>" . mysqli_error($connect);
		}	
	}*/
	function deleteTeam($team_name)
	{
		global $connect;
		$delete_sql = "DELETE FROM FantasyTeams WHERE team_name = '$team_name'";
		if (mysqli_query($connect, $delete_sql))
		{
			echo "Fantasy team $team_name successfully deleted.<br>";
		}
		else
		{
			echo "Error: " . $delete_sql . "<br>" . mysqli_error($connect);
			echo "<br>";
		}
	}
	//Test code here. I ran once hence you may want to change it.
	//searchFTeam("1");
	//newTeam("test");
	//seePlayers("FF14");
	//addNewPlayers("Tom Brady", "FF15");
	if(array_key_exists("new_team", $_POST))
	{
		newTeam($_POST["new_team"]);
	}
	if(array_key_exists("delteam", $_POST))
	{
		deleteTeam($_POST["delteam"]);
	}
	if(array_key_exists("fteam", $_POST))
	{
		displayPlayers($_POST["fteam"]);
	}
	else if(array_key_exists("week", $_POST))
	{
		displayFTeams($_POST["week"]);
	}
	else
	{
		displayFTeams("all");
	}
	
	mysqli_close($connect);
?>
</body>
</html>
