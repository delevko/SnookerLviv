<link rel="stylesheet" type="text/css" href="<?=PATH_H?>css/bracket.css">


<?php
//bracket, tournamentID

if(!strcmp($bracket, "K/O"))
{
	$data = query("select KO_Rounds, seeded_Round from tournament where id=?", $tournamentID);
    
	$KO_R = $data[0][0]; $seeded_R = $data[0][1];
	prepareRound("K/O", 0, $KO_R, $seeded_R, $tournamentID);
}

if(!strcmp($bracket, "D/E"))
{
	$data = query("select UP_Rounds,LOW_Rounds,KO_Rounds,seeded_Round from tournament where id=?", $tournamentID);
   
	$LOW_R = $data[0][1]; $KO_R = $data[0][2];
	$UP_R = $data[0][0]; $seeded_R = $data[0][3];

	prepareRound("LOW", 0, $LOW_R, $seeded_R, $tournamentID);
	prepareRound("UP", 0, $UP_R, $seeded_R, $tournamentID);
	prepareRound("K/O", $UP_R-1, $KO_R, $seeded_R, $tournamentID);
}

if(!strcmp($bracket, "GroupKO"))
{
	$data = query("select KO_Rounds, seeded_Round from tournament where id=?", $tournamentID);
    
	$KO_R = $data[0][0]; $seeded_R = $data[0][1];
	prepareRound("K/O", 0, $KO_R, $seeded_R, $tournamentID);
}


function prepareRound($roundType, $offset, $R, $seeded_R, $tournamentID)
{
	if( !strcmp($roundType,"LOW") )
	{
		for($i = $R; $i >= 1; $i--)
		{ ?>
			<div class="bracket_column <?=$roundType?>-<?=$i?>">
			<?php printRound($tournamentID, $i, $roundType, ($i==$R)?true:false);?>
			</div>
		<?php
		}
	}
	else
	{
		for($i = 1; $i < $seeded_R; $i++)
		{ ?>
			<div class="bracket_column <?=$roundType?>-1">
			<?php printRound($tournamentID, $i, $roundType, false);?>
			</div>
		<?php 
		} 

		for($i = $seeded_R; $i <= $R; $i++)
		{ ?>
			<div class="bracket_column <?=$roundType?>-<?=$i+$offset-$seeded_R+1?>">
			<?php printRound($tournamentID, $i, $roundType, false);?>
			</div>
		<?php 
		}
	}
}

function printRound($tournID, $Rno, $Rtype, $lowFlag) 
{ 
    $query = "SELECT TV.counter, TV.matchID, TV.player1Name, TV.player2Name,  
        TV.bestOf, TV.winnerMatchID, TV.loserPlaces, TV.loserMatchID, 
        TV.player1Score, TV.player2Score, TV.status, TV.youtube, 
		TV.player1Seed, TV.player2Seed
        FROM matchesTournamentView TV  
        WHERE TV.tournamentID=? AND TV.roundNo=? AND TV.roundType=? 
        ORDER BY TV.counter";

    $data = query($query, $tournID, $Rno, $Rtype);

    for($i = 0; $i < count($data); $i++)
    {
        $counter = $data[$i][0]; $matchID = $data[$i][1];
        $player1 = $data[$i][2]; $player2 = $data[$i][3];
		$seed1 = $data[$i][12]; $seed2 = $data[$i][13];
        $bestOf = $data[$i][4];
        $winnerMatch = $data[$i][5]; $loserMatch = $data[$i][7];
        $loserPlaces = $data[$i][6];

        $player1Score = $data[$i][8]; $player2Score = $data[$i][9];
        $status = $data[$i][10]; $youtube = $data[$i][11];

		$liveFlag = ($status=="Live");
		if( !strcmp($Rtype, "UP") && $Rno > 1 )
			$upFlag = true;
		else
			$upFlag = false;

		printBracketMatch($i, $matchID, $counter, $player1, $player1Score, $seed1, $player2, $player2Score, $seed2, $lowFlag, $upFlag, $loserMatch, $winnerMatch, $youtube, $liveFlag);
    }
}

function printBracketMatch($i, $matchID, $matchNum, $player1, $score1, $seed1, $player2, $score2, $seed2, $lowFlag, $upFlag, $loserID, $winnerID, $youtube, $liveFlag)
{ ?>
		<div class="bracket_item<?=($i==0)?" first-match":""?>">
		<div class="null"></div>
		<div class="match_number pointer"
		onclick="openMatchLobby(<?=$matchID?>);"> 
			<?=$matchNum?>
		</div>
		<div class="youtube_logo<?=(isset($youtube)?" pointer":"")?>">
			<?php if(isset($youtube)) { ?>
            <a href="<?=YT_HEADER.$youtube?>">
                <img src="<?=PATH_H?>img/youtube.png" alt="Youtube">
            </a>
            <?php } ?>
		</div>
		<div class="front_01">
			<?=$seed1?>
		</div>
		<div class="front_02">
			<?=$seed2?>
		</div>
		<div class="name_01">
			<?=$player1?>
		</div>
		<div class="name_02">
			<?=$player2?>
		</div>
		<div class="points_01">
			<?=$score1?>
		</div>
		<div class="points_02">
			<?=$score2?>
		</div>
<?php if($liveFlag){ ?>
		<div class="live_match_bracket">live</div>
<?php } ?>

		<div class="looser">
<?php if($upFlag){ ?>
		переможений на <b><?=$loserID?></b>
<?php } ?>
<?php if($lowFlag){ ?>
		переможець на <b><?=$winnerID?></b>
<?php } ?>
		</div>
	</div>
<?php } ?>
