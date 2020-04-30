<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">
<body>
	<h2>Fantasy Football Database: Update Stats</h2>
<nav class="w3-bar w3-black">
	<a href="index.php" method="post" class="w3-button w3-bar-item">Index</a>
	<a href="PlayerStats.php" method = "post" class="w3-button w3-bar-item">Player Stats</a>
	<a href="TeamStats.php" class="w3-button w3-bar-item">Team Stats</a>
	<a href="FantasyTeams.php" method = "post" class="w3-button w3-bar-item">Fantasy Teams</a>
</nav>



<?php
			
	$connect=mysqli_connect("127.0.0.1","root","","fantasyfootball");
	
	function displayStats($player_row)
	{
		echo "<table border='1'>
		<tr>
		<th>Player Name</th>
		<th>Passing Attempts</th>
		<th>Completions</th>
		<th>Passing Yards</th>
		<th>Passing TDs</th>
		<th>Interceptions</th>
		<th>Rushing Attempts</th>
		<th>Rushing Yards</th>
		<th>Rushing TDs</th>
		<th>Catches</th>
		<th>Receiving Yards</th>
		<th>Receiving TDs</th>
		</tr>";
		
		echo "<tr>
		<td>".$player_row["player"]."</td>
		<td>".$player_row["passing_attempts"]."</td>
		<td>".$player_row["completions"]."</td>
		<td>".$player_row["passing_yards"]."</td>
		<td>".$player_row["passing_tds"]."</td>
		<td>".$player_row["interceptions"]."</td>
		<td>".$player_row["rushing_attempts"]."</td>
		<td>".$player_row["rushing_yds"]."</td>
		<td>".$player_row["rushing_tds"]."</td>
		<td>".$player_row["catches"]."</td>
		<td>".$player_row["receiving_yds"]."</td>
		<td>".$player_row["receiving_tds"]."</td>
		</tr></table>";
	}
	
	function updateStats($player_id, $week)
	{
		global $connect;
		
		$pass_attempts = ($_POST["pass_attempts"] == "") ? "" : ", passing_attempts = ";
		$completions = ($_POST["completions"] == "") ? "" : ", completions = ";
		$passing_yards = ($_POST["pass_yds"] == "") ? "" : ", passing_yards = ";
		$passing_tds = ($_POST["pass_tds"] == "") ? "" : ", passing_tds = ";
		$interceptions = ($_POST["ints"] == "") ? "" : ", interceptions = ";
		$rushing_attempts = ($_POST["rush_attempts"] == "") ? "" : ", rushing_attempts = ";
		$rushing_yds = ($_POST["rush_yds"] == "") ? "" : ", rushing_yds = ";
		$rushing_tds = ($_POST["rush_tds"] == "") ? "" : ", rushing_tds = ";
		$catches = ($_POST["catches"] == "") ? "" : ", catches = ";
		$receiving_yds = ($_POST["rec_yds"] == "") ? "" : ", receiving_yds = ";
		$receiving_tds = ($_POST["rec_tds"] == "") ? "" : ", receiving_tds = ";
		
		if($week == "all")
		{
			$select_sql = "SELECT * FROM players WHERE player_id = '$player_id'";
			$result = mysqli_query($connect, $select_sql);
			$row = mysqli_fetch_assoc($result);
			mysqli_free_result($result);
			
			
			
		$update_sql = "UPDATE players SET player = '".$row["player"]."'$pass_attempts".$_POST["pass_attempts"]."$completions".$_POST["completions"]."$passing_yards".$_POST["pass_yds"]."$passing_tds".$_POST["pass_tds"]."$interceptions".$_POST["ints"]."$rushing_attempts".$_POST["rush_attempts"]."$rushing_yds".$_POST["rush_yds"]."$rushing_tds".$_POST["rush_tds"]."$catches".$_POST["catches"]."$receiving_yds".$_POST["rec_yds"]."$receiving_tds".$_POST["rec_tds"]." WHERE player_id = $player_id";
		//echo $update_sql;
		if (mysqli_query($connect, $update_sql))
		{
			echo $row["player"]." updated successfully.<br>";
		}
		else{
			echo "Error: failed to update player.";
		}
		}
		
		else
		{
			$select_sql = "SELECT * FROM playergamestats WHERE player_id = '$player_id' AND game_id IN (SELECT game_id FROM games WHERE week = '$week')";
			$result = mysqli_query($connect, $select_sql);
			$row = mysqli_fetch_assoc($result);
			mysqli_free_result($result);
			
			$update_sql = "UPDATE playergamestats SET player = '".$row["player"]."'$pass_attempts".$_POST["pass_attempts"]."$completions".$_POST["completions"]."$passing_yards".$_POST["pass_yds"]."$passing_tds".$_POST["pass_tds"]."$interceptions".$_POST["ints"]."$rushing_attempts".$_POST["rush_attempts"]."$rushing_yds".$_POST["rush_yds"]."$rushing_tds".$_POST["rush_tds"]."$catches".$_POST["catches"]."$receiving_yds".$_POST["rec_yds"]."$receiving_tds".$_POST["rec_tds"]." WHERE player_id = $player_id AND game_id = ".$row["game_id"];
			//echo $update_sql;
			if(mysqli_query($connect, $update_sql))
			{
				echo $row["player"]." updated successfully.<br>";
			}
		}
		
	}
	
	if(array_key_exists("pass_attempts", $_POST))
	{
		updateStats($_POST["player_id"], $_POST["week"]);
	}
	
	
	echo "<br>";
	
	if(array_key_exists("player_id", $_POST))
	{
		$player_id = $_POST["player_id"];
		$week = $_POST["week"];
		
		if(!$connect) echo "Mysql Connect Error!";
		
		if($week == "all")
		{	
			$select_sql = "SELECT * FROM players WHERE player_id = '$player_id'";
			$result = mysqli_query($connect, $select_sql);
			$row = mysqli_fetch_assoc($result);
			
			echo "Current total stats for ".$row["player"].":";
			displayStats($row);
			mysqli_free_result($result);
		}
		
		else
		{
			$select_sql = "SELECT * FROM playergamestats WHERE player_id = '$player_id' AND game_id IN (SELECT game_id FROM games WHERE week = '$week')";
			$result = mysqli_query($connect, $select_sql);
			$row = mysqli_fetch_assoc($result);
			
			echo "Current stats for ".$row["player"]." Week $week:";
			displayStats($row);
			mysqli_free_result($result);
		}
		
		echo 
		"<br><form action='UpdateStats.php' method='post'>
			<label for 'pass_attempts'>New Passing Attempts: <input type='text' name='pass_attempts'><br>
			<label for 'completions'>New Completions: <input type='text' name='completions'><br>
			<label for 'pass_yds'>New Passing Yards: <input type='text' name='pass_yds'><br>
			<label for 'pass_tds'>New Passing TDs: <input type='text' name='pass_tds'><br>
			<label for 'ints'>New Interceptions: <input type='text' name='ints'><br>
			<label for 'rush_attempts'>New Rushing Attempts: <input type='text' name='rush_attempts'><br>
			<label for 'rush_yds'>New Rushing Yards: <input type='text' name='rush_yds'><br>
			<label for 'rush_tds'>New Rushing TDs: <input type='text' name='rush_tds'><br>
			<label for 'catches'>New Catches: <input type='text' name='catches'><br>
			<label for 'rec_yds'>New Receiving Yards: <input type='text' name='rec_yds'><br>
			<label for 'rec_tds'>New Receiving TDs: <input type='text' name='rec_tds'>
			<input type='hidden' name='player_id' value='$player_id'>
			<input type='hidden' name='week' value='$week'>
			<input type='submit' value='Submit'>
		</form>";
		
	
	}
	
	else
	{
		echo "Error: missing player to update.";
	}
	
	mysqli_close($connect);


?>

</body>
</html>