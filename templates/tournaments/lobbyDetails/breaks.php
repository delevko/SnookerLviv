<link rel="stylesheet" type="text/css" href="<?=PATH_H?>css/tournament_breaks.css"> 


<?php

    $query="SELECT BV.points,BV.matchID,BV.playerID,BV.playerName, 
		BV.opponentID,BV.opponentName,BV.playerPhoto,BV.opponentPhoto, 
		BV.roundType, BV.roundNo
		FROM breakView BV WHERE tournamentID=? ORDER BY 1 DESC, 4";
    $data = query($query, $tournamentID);
	$data_count = count($data);
  
	printHeader(); 

	for($i = 0; $i < $data_count; $i++)
	{
		$points = $data[$i][0]; $matchID = $data[$i][1];
		$plrID = $data[$i][2]; $plrName = $data[$i][3];
		$oppID = $data[$i][4]; $oppName = $data[$i][5];
		$plrPhoto = $data[$i][6]; $oppPhoto = $data[$i][7];
		$rndType = $data[$i][8]; $rndNo = $data[$i][9];

		$rndType = castBreakHeader($rndType);

		$BL = ($i+1 == $data_count) ? "radius_bl" : "";
		$BR = ($i+1 == $data_count) ? "radius_br" : "";
	
		printBreak($points, $i+1, $matchID, $plrName, $plrPhoto, $oppName, $oppPhoto, $BL,$BR, $rndType, $rndNo);
	}

	printFooter();



function printBreak($pts,$i,$mID,$plrName,$plrPhoto,$oppName,$oppPhoto,$BL,$BR, $rndType, $rndNo)
{
    $e_o = ($i%2) ? "odd" : "even";
?>
            <tr onclick="openMatchLobby(<?=$mID?>);"
            class="tbody_<?=$e_o?> pointer">
				<td class="<?=$BL?>">
					<div class="photo_name">
						<img class="circle_img" src="<?=PLAYER_IMG.$plrPhoto?>" alt="img">
						<span><?=$plrName?></span>
                	</div>
				</td>
                <td class="bold <?=$e_o?>_num">
					<?=$pts?>
				</td>
                <td class="uppercase">
					<?=$rndType?> <?=$rndNo?>
                </td>
				<td class="<?=$BR?>">
					<div class="photo_name">
						<img class="circle_img" src="<?=PLAYER_IMG.$oppPhoto?>" alt="img">
						<span><?=$oppName?></span>
                	</div>
				</td>
            </tr>
<?php
}

function printHeader()
{ ?>
	<div class="section_header">
		<div class="header_sign">Брейки</div>
	</div>
	<div class="list_container">
	<table class="list_table breaks_table">
		<colgroup>
			<col class="col-1">
			<col class="col-2">
			<col class="col-3">
			<col class="col-4">
		</colgroup>
		<thead>
			<tr>
                <th>
                    <i class="fas fa-user"></i>
                    <span>Гравець</span>
                </th>
                <th>
                    <i class="fas fa-star"></i>
                    <span>Очки</span>
                </th>
                <th>
					<i class="fas fa-clipboard"></i>
                    <span>Раунд</span>
                </th>
                <th>
                    <i class="fas fa-user-friends"></i>
                    <span>Суперник</span>
                </th>
            </tr>
		</thead>
		<tbody>
<?php
}

function printFooter()
{ ?>
		</tbody>
	</table>
	</div>

<?php
} ?>
