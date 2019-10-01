<?php list($tournamentName, $tournamentID, $status) = getMainData($matchID);?>

<div class="sub-container">
	<a href="lobby.php?id=<?=$tournamentID?>"><h1><?=$tournamentName?></h1></a>

<?php

lobby($matchID);


function lobby($matchID)
{
	list($counter,$roundType,$roundNo,$bestOF,$id1,$name1,$score1,$id2,$name2,$score2) = getMatchData($matchID);
	printLobby($counter,$roundType,$roundNo,$bestOF,$id1,$name1,$score1,$id2,$name2,$score2);
	printFrames($matchID);
}


function printLobby($counter, $roundType, $roundNo, $bestOF, $id1, $name1, $score1, $id2, $name2, $score2)
{ ?>

	<div class="match_lobby">
		<h3 class="match_lobby_info">Зустріч #<?=$counter?> | Раунд <?=$roundNo?>(<?=$roundType?>) </h3>
		<div class="match_lobby_player-table">
			<div class="match_lobby_player01">
				<span class="match_lobby_player01-name"><?=$name1?></span>
				<p>
					<img class="match_lobby_player01-img" alt="player01" src="../../img/lev.jpg"></img>
				</p>
			</div>
			<div class="match_lobby_frame-section">
				<table class="match_lobby_frame-table">
					<tbody>
						<tr>
							<td><?=$score1?></td>
							<th>v</th>
							<td><?=$score2?></td>
						</tr>
						<tr class="match_lobby_frame-details">
							<td colspan="3">Best of <?=$bestOF?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="match_lobby_player02">
				<span class="match_lobby_player02-name"><?=$name2?></span>
				<p>
					<img class="match_lobby_player02-img" alt="player02" src="../../img/dan02.jpg"></img>
				</p>
			</div>
		</div>
	</div>
	<br>

<?php }


function framesHeader()
{ ?>

<div class="match_lobby_framesBreaksPoints_table">
	<table class="match_lobby_table">
		<thead class="match_lobby_table_thead">
			<tr>
				<th><span>breaks</span></th>
				<th><span>points</span></th>
				<th><span>frame</span></th>
				<th><span>points</span></th>
				<th><span>breaks</span></th>
			</tr>
		</thead>
		<tbody class="match_lobby_table_tbody">
<?php }


function printFrame($counter, $score1, $score2, $breaks1, $breaks2)
{ 
	$e_o = ($counter%2) ? "odd" : "even"
?>

	<tr class="match_lobby_table_tbody_<?=$e_o?>">
		<td class="match_lobby_table_name_left"><?=$breaks1?></td>
		<td class="match_lobby_table_name_left"><?=$score1?></td>
		<td class="match_lobby_table_number_<?=$e_o?>"><?=$counter?></td>
		<td class="match_lobby_table_date_center"><?=$score2?></td>
		<td class="match_lobby_table_date_left"><?=$breaks2?></td>
	</tr>

<?php }

function framesFooter()
{ ?>

		</tbody>
	</table>
</div>

<?php }

function printFrames($matchID)
{
	$query = "SELECT F.counter, F.points1, F.points2 
		FROM frame F WHERE F.matchID=? ORDER BY F.counter";
	$data = query($query, $matchID);
	if( count($data) > 0 )
		framesHeader();

	for($i = 0; $i < count($data); $i++)
	{
		$frame = $data[$i][0];
		$points1 = $data[$i][1]; $points2 = $data[$i][2];
		
		$query = "SELECT B.XorY, B.points FROM break B
			WHERE B.frameCounter=? AND B.matchID=? ORDER BY 1, 2 DESC";
		$breaks = query($query, $frame, $matchID);
		$breaks1 = ""; $breaks2 = "";
		for($j = 0; $j < count($breaks); $j++)
		{
			$xORy = $breaks[$j][0]; $points = $breaks[$j][1];
			if($xORy) $breaks1 .= ($points.", ");
			else $breaks2 .= ($points.", ");
		}
		$breaks1 = substr($breaks1, 0, -2);
		$breaks2 = substr($breaks2, 0, -2);

		printFrame($i+1, $points1, $points2, $breaks1, $breaks2);
	}
	
	if( count($data) > 0 )
		framesFooter();
}


function getMatchData($matchID)
{
	$query = "SELECT MV.counter, MV.roundType, MV.roundNo, MV.bestOF,
		MV.player1ID, MV.Player1, MV.player1Score,
		MV.player2ID, MV.Player2, MV.player2Score
		FROM matchView MV WHERE matchID = ?"; 
	$data = query($query, $matchID);

	return array($data[0][0],$data[0][1],$data[0][2],$data[0][3],$data[0][4],$data[0][5],$data[0][6],$data[0][7],$data[0][8],$data[0][9]);
	
}

function getMainData($matchID)
{
	$query = "SELECT MV.tournamentID, MV.tournamentName, MV.status
    FROM matchView MV WHERE matchID = ?"; 
	$data = query($query, $matchID);

	$tournamentName = $data[0][1]; $tournamentID = $data[0][0];
	$status = $data[0][2];
	return array($tournamentName, $tournamentID, $status);
}

?> </div>
