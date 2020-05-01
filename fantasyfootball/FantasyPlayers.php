<!DOCTYPE html>
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

<?php
session_start();
$team = $_POST["team"];
echo "
<div>
	<em>Search for player to add</em>
	<form action='FantasyPlayers.php' method='post'>
		<label for 'pname'>Player Name:</label><input type='text' name='pname'>
		<input type='hidden' name='team' value='".$team."'>
		<input type='submit' value='Submit'>
	</form>
	<form action='FantasyTeams.php' method='post'>
		<button type='submit'>Back to Fantasy Teams</button>
	</form>
</div>
";


	
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
	//Given a team name, search for all members in table Players.
	function displayPlayers($team_name)
	{
		echo "Showing players for ".$team_name.":";
		echo "<table border='1'>
		<tr>
		<th>Player Name</th>
		<th>Fantasy Points</th>
		<th>Remove Player</th>
		</tr>";
		global $connect;
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
			echo "<td><form action='FantasyPlayers.php' method='post'><input type='hidden' name='team' value='$team_name'><button type='submit' name='delname' value='".$row["player"]."'>Remove</button></form></td>";
			echo "</tr>";
		}
		mysqli_free_result($result1);
	}
	function searchPlayers($player_name)
	{
		
		global $connect;
		global $team;
		echo "Results for \"".$player_name."\" to add to ".$team.": <form action='FantasyPlayers.php' method='post'><button name='team' value='$team' type='submit'>Cancel</button></form>";
		$select_sql = "SELECT * FROM players WHERE player LIKE '%$player_name%'";
		if (!mysqli_query($connect, $select_sql))
		{
			echo "Error: " . $select_sql . "<br>" . mysqli_error($connect);
		}
		else
		{
			$result = mysqli_query($connect, $select_sql);
			$rownum = mysqli_num_rows($result);
			echo "<table border='1'>
			<tr>
			<th>Add Player</th>
			<th>Player Name</th>
			</tr>";
			
			for($i = 0; $i < $rownum; $i++)
			{
				$row = mysqli_fetch_assoc($result);
				echo "<tr>";
				if($row["fantasy_id"] == NULL)
				{
					echo "<td><form action='FantasyPlayers.php' method='post'><input type='hidden' name='team' value='".$team."'><button type='submit' name='addname' value='".$row["player"]."'>Add Player</button></form></td>";
				}
				else
				{
					echo "<td>Player already <br> on a team</td>";
				}
				echo "<td>".$row["player"]."</td>";
			}
		}
		mysqli_free_result($result);
	}
				
	//Given a team name and detailed player name. You may need to use or copy fuzzy query I write on PlayerStats.php.
	function addNewPlayers($player_name)
	{
		global $connect;
		global $team;
		$update_sql = "UPDATE players SET fantasy_id='$team' WHERE player = '$player_name'";
		if (mysqli_query($connect, $update_sql))
		{
			echo "Table players updated successfully.";
		} 
		else 
		{
			echo "Error: " . $update_sql . "<br>" . mysqli_error($connect);
		}
		echo "</br>";

	}
	function deletePlayer($player_name)
	{
		global $connect;
		$delete_sql = "UPDATE players SET fantasy_id = NULL WHERE player = '$player_name'";
		if (mysqli_query($connect, $delete_sql))
		{
			echo "$player_name successfully removed.<br>";
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
	if(array_key_exists("delname", $_POST))
	{
		deletePlayer($_POST["delname"]);
	}
	if(array_key_exists("addname", $_POST))
	{
		addNewPlayers($_POST["addname"]);
	}
	if(array_key_exists("pname", $_POST))
	{
		searchPlayers($_POST["pname"]);
	}
	else
	{
		displayPlayers($team);
	}
	mysqli_close($connect);
?>
</body>
</html>
