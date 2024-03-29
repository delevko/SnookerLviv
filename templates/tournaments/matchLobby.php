
<link rel="stylesheet" type="text/css" href="<?=PATH_H?>css/match_lobby.css"> 
<div class="sub-container">


<?php

	list($tournamentName,$tournamentID,$status,$name,$billiard,$details,$league) = 
		getMainData($matchID);

	tournamentHeader($tournamentID, $tournamentName, $billiard, $details, $league);
	lobby($matchID);



function lobby($matchID)
{
	list($counter, $roundType, $roundNo, $bestOF,
		$id1, $name1, $score1, $img1,
		$id2, $name2, $score2, $img2) = getMatchData($matchID);

	$roundType = castMatchHeader($roundType);	
	printLobby($counter, $roundType, $roundNo, $bestOF,
		$id1, $name1, $score1, $img1, $id2, $name2, $score2, $img2);

	if( isset($score1) )
	{
		framesHeader();

		printFrames($matchID);

		framesFooter();
	}
}



function tournamentHeader($id, $name, $billiard, $details, $league)
{ ?>
    <div class="tour_menu_box_700">
        <div class="tournament_header_700 pointer"
		onclick="openTournamentLobby(<?=$id?>)">
            <div class="nameOf_tour">
                <i class="fas fa-trophy"></i>
                <span style="margin-left:5px;"><?=$name?></span>
            </div>
            <div class="second_row">
                <div class="typeOf_tour">
                    <span><?=$billiard?> &nbsp;</span>
                    <span><?=$details?></span>
                </div>
                <div class="organOf_tour">
                    <span><?=$league?></span>
                </div>
            </div>
        </div>
	</div>
<?php }



function printLobby($counter, $roundType, $roundNo, $bestOF, 
	$id1, $name1, $score1, $img1, $id2, $name2, $score2, $img2)
{ ?>
	<div class="match_lobby">
		<div class="match_lobby_info">
			Зустріч #<?=$counter?>&emsp; | &emsp;<?=$roundType?><?=$roundNo?>
		</div>
		<div class="match_lobby_player-table">
			<div class="match_lobby_player pointer"
			onclick="openPlayerLobby(<?=$id1?>);">
				<span class="match_lobby_player-name float_left">
					<?=$name1?>
				</span>
				<p>
					<img class="match_lobby_player-img" alt="player01" src="<?=PLAYER_IMG.$img1?>"></img>
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
							<td colspan="3">
								Best of <?=$bestOF?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="match_lobby_player pointer"
			onclick="openPlayerLobby(<?=$id2?>);">
				<span class="match_lobby_player-name float_right">
					<?=$name2?>
				</span>
				<p>
					<img class="match_lobby_player-img" alt="player02" src="<?=PLAYER_IMG.$img2?>"></img>
				</p>
			</div>
		</div>
	</div>

<?php }


function framesHeader()
{ ?>

	<div class="">
		<table class="match_lobby_table">
			<colgroup>
				<col class="col-1">
				<col class="col-2">
				<col class="col-3">
				<col class="col-4">
				<col class="col-5">
			</colgroup>
			<thead>
				<tr>
					<th>
						<span>брейки</span>
					</th>
					<th>
						<span>очки</span>
					</th>
					<th>
						<span>фрейм</span>
					</th>
					<th>
						<span>очки</span>
					</th>
					<th>
						<span>брейки</span>
					</th>
				</tr>
			</thead>
			<tbody>
<?php }


function printFrame($counter, $score1, $score2, $breaks1, $breaks2, $BL, $BR, $TL, $TR)
{ 
	$e_o = ($counter%2) ? "odd" : "even";
?>

	<tr class="tbody_<?=$e_o?>">
		<td class="match_lobby_table_name_left <?=$BL?> <?=$TL?>">
			<?=$breaks1?>
		</td>
		<td class="match_lobby_table_name_left">
			<?=$score1?>
		</td>
		<td class="match_lobby_table_number <?=$e_o?>_num">
			<?=$counter?>
		</td>
		<td class="match_lobby_table_date_center">
			<?=$score2?>
		</td>
		<td class="match_lobby_table_date_left <?=$BR?> <?=$TR?>">
			<?=$breaks2?>
		</td>
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
	$data_count = count($data);

	for($i = 0; $i < $data_count; $i++)
	{
		$frame = $data[$i][0];
		$points1 = $data[$i][1]; $points2 = $data[$i][2];
		
		$query = "SELECT B.XorY, B.points FROM break B
			WHERE B.frameCounter=? AND B.matchID=? ORDER BY 1, 2 DESC";
		$breaks = query($query, $frame, $matchID);
		$breaks1 = ""; $breaks2 = "";

		$break_count = count($breaks);
		for($j = 0; $j < $break_count; $j++)
		{
			$xORy = $breaks[$j][0]; $points = $breaks[$j][1];
			if($xORy) $breaks1 .= ($points.", ");
			else $breaks2 .= ($points.", ");
		}
		$breaks1 = substr($breaks1, 0, -2);
		$breaks2 = substr($breaks2, 0, -2);

		
		$TL = ""; $TR = "";
		$BL = ""; $BR = "";
		if($i === 0){
			$TL = "radius_tl"; $TR = "radius_tr";
		}
		if($i+1 >= $data_count){
			$BL = "radius_bl"; $BR = "radius_br";
		}

		printFrame($i+1, $points1, $points2, $breaks1, $breaks2, $BL, $BR, $TL, $TR);
	}
	
	if( $data_count > 0 )
		framesFooter();
}


function getMatchData($matchID)
{
	$query = "SELECT MV.counter, MV.roundType, MV.roundNo, MV.bestOF,
		MV.player1ID, MV.Player1, MV.player1Score, MV.photo1,
		MV.player2ID, MV.Player2, MV.player2Score, MV.photo2
		FROM matchView MV WHERE matchID = ?"; 
	$data = query($query, $matchID);

	return array($data[0][0],$data[0][1],$data[0][2],$data[0][3],$data[0][4],$data[0][5],$data[0][6],$data[0][7],$data[0][8],$data[0][9],$data[0][10],$data[0][11]);
	
}

function getMainData($matchID)
{
//match + tournament data
	$query = "SELECT MV.tournamentID, MV.tournamentName, MV.status
    FROM matchView MV WHERE matchID = ?"; 
	$data = query($query, $matchID);

	$tournamentName = $data[0][1]; $tournamentID = $data[0][0];
	$status = $data[0][2];

//tournament header data
	$query = "SELECT TV.billiard, TV.age, TV.sex, TV.league 
        FROM generalTournamentView TV WHERE tournamentID=?";
    $data = query($query, $tournamentID);

    $billiard = $data[0][0]; $league = $data[0][3];
    $details = castDetails($data[0][1], $data[0][2]);


	return array($tournamentName,$tournamentID,$status,$name,$billiard,$details,$league);
}

function castDetails($age, $sex)
{
    $details = "";
    if( $age != "" )
    {
        $details .= "(".$age;
        if( $sex != "" )
            $details .= " ".$sex.")";
        else
            $details .= ")";
    }
    else if( $sex != "" )
    {
        $details = "(".$sex.")";
    }

    return $details;
} ?>

	</div>
