<?php

$query = "SELECT P.id, P.playerName FROM (SELECT @getVal:=?) d,
            remainingPlayersForTournament P ORDER BY 2";
$data = query($query, $tournamentID);
if(count($data))
{
?>
	<form action="playerRegister.php" method="post">
		Register: <select name="player">
			<?php
				
			for($i=0; $i<count($data); $i++)
			{
				$playerID = $data[$i][0];
				$playerName = $data[$i][1];
?>
				<option value="<?=$playerID?>"><?=$playerName?></option>
	  <?php } ?>
		</select>
		</br>
		<input type="hidden" name="tournament" value="<?=$tournamentID?>"/>
		<button type="submit">Register</button>
	</form>
<?php
}
?>
