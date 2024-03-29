
<link rel="stylesheet" type="text/css" href="<?=PATH_H?>css/available_tables.css">
<link rel="stylesheet" type="text/css" href="<?=PATH_H?>css/table_lobby.css">

<?php


generalHeader($clubID, $clubName, $clubPhoto);

tableHeader( castStatus($tableStatus), $tableNum );

if($tableStatus == "Occupied")
{
	$query = "SELECT TV.Player1, TV.Player2, TV.photo1, TV.photo2,
            TV.player1Score, TV.player2Score, TV.bestOf,
			TV.tournamentName, TV.tournamentID, TV.youtube
            FROM tableView TV WHERE tableID=?";
    $data = query($query, $tableID);
	
	$plr1 = $data[0][0]; $plr2 = $data[0][1];
    $img1 = $data[0][2]; $img2 = $data[0][3];
	$pts1 = $data[0][4]; $pts2 = $data[0][5];
	$bestOf = $data[0][6];

	displayTable($plr1,$img1,$plr2,$img2,$pts1,$pts2,$bestOf);
}

tableFooter();


if( !strcmp($tableStatus, "Available") )
{
	showAvailable($tableID, $clubID);
}
else if( !strcmp($tableStatus, "Occupied") )
{
	showOccupied($tableID, $clubID);
}

generalFooter();


function castStatus($status)
{
    if($status=="Occupied")
        return "busy";
    if($status=="Available")
        return "free";
}



function generalHeader($clubID, $clubName, $clubPhoto)
{ ?>
	<div class="margin-b_30"></div>
    <div class="sub-container">
        <div class="section_header_700 header_border">
			<img class="circle_img_clb float_right" alt="logo"
			src="<?=CLUB_IMG.$clubPhoto?>">

			<div class="header_sign">
				<a href="lobby.php?id=<?=$clubID?>">
					<i class="fas fa-shield-alt"></i>
					<?=$clubName?>
				</a>
			</div>
        </div>
<?php }
function generalFooter()
{ ?>
    </div>
<?php }



function tableHeader($b_f, $number)
{ ?>
        <div class="stable_containers">
            <div id="<?=$b_f?>" class="header_box">
                <span class="stable_num_header">
                    Стіл #<?=$number?>
                </span>
            </div>
            <div class="<?=$b_f?>_stable_container">
                <div class="<?=$b_f?>_stable_box">
<?php if($b_f=="free") { ?>
                    <span class="<?=$b_f?>_stable_num">
                        #<?=$number?>
                    </span>
<?php }
}
function tableFooter()
{ ?>
                </div>
            </div>
        </div>
<?php }


function displayTable($plr1, $img1, $plr2, $img2, $pts1,$pts2,$bestOf)
{ ?>
                    <div class="boxFor_plName">
                        <span class="plName stable_plName_left">
                            <?=$plr1?>
                        </span>
                        <span class="plName stable_plName_right">
                            <?=$plr2?>
                        </span>
                    </div>
                    <div class="boxFor_imgs">
                        <div class="circle_img_box_left">
                            <img class="circle_player_img" src="<?=PLAYER_IMG.$img1?>" alt="фото гравця">
                        </div>
                        <div class="current_game_info">
                            <span class="points_num"><?=$pts1?></span>
                            <span id="frame_num">(<?=$bestOf?>)</span>
                            <span class="points_num"><?=$pts2?></span>
                        </div>
                        <div class="circle_img_box_right">
                            <img class="circle_player_img" src="<?=PLAYER_IMG.$img2?>" alt="фото гравця">
                        </div>
                    </div>
<?php }



function showAvailable($tableID, $clubID)
{ 
	$query="SELECT MV.matchID, MV.counter, MV.Player1, MV.Player2
		FROM matchView MV
		WHERE MV.status=? AND MV.clubID=?
		AND MV.player1ID != -2 AND MV.player2ID != -2 ORDER BY 2";
	
	$data = query($query, "Announced", $clubID);
	if(count($data)>0)
	{
		?>
		<form action="tableLobby.php" method="post">
		<input type="hidden" name="id" value="<?=$tableID?>"/>
		<select name="matchID"><?php
		for($i=0; $i<count($data); $i++)
		{
			$matchID = $data[$i][0]; $counter = $data[$i][1];
			$player1 = $data[$i][2]; $player2 = $data[$i][3];
		?><option value="<?=$matchID?>"><?=$counter?>: <?=$player1?>-<?=$player2?></option><?php
		}
		?><input type="submit" name="match" value="Почати матч"/><?php
		?></form><?php 
	}
}


function showOccupied($tableID, $clubID)
{ 
	$query = "SELECT TV.matchCounter, TV.matchStatus, 
		TV.tournamentName, TV.youtube, TV.matchID
        FROM tableView TV WHERE TV.tableID=?";
    $data = query($query, $tableID);
	
	$tournamentName = $data[0][2]; $matchCounter = $data[0][0];
	$matchStatus = $data[0][1]; $matchID = $data[0][4];
	$youtube = $data[0][3];


	occupiedHeader($tournamentName." - Зустріч #".$matchCounter);

	displayLiveTableLink($tableID, $matchID);
	
	
	if( isset($youtube) )
	{
		displayYoutube($youtube);
	}
	else
	{
		setYoutube($matchID, $tableID);
	}
	

	if(!strcmp($matchStatus, "Live"))
		liveForm($tableID);
	else if(!strcmp($matchStatus, "Finished"))
		finishedForm($tableID, $tournamentID);

	occupiedFooter();
}

function displayLiveTableLink($tableID, $matchID)
{ ?>
	<a href="live-match-lobby.php?tableID=<?=$tableID?>
		&matchID=<?=$matchID?>">
		<div class="available_form">
			<button>ЖИВИЙ МАТЧ</button>
		</div>
	</a>

<?php }


function setYoutube($matchID, $tableID)
{ ?>
		<div class="margin-b_30"></div>
		<div class="margin-b_30"></div>
		<form class="available_form" action="YTstart.php"
		method="post">
			<input type="hidden" name="matchID" value="<?=$matchID?>">
			<input type="hidden" name="tableID" value="<?=$tableID?>">
			<input type="text" name="URL" placeholder="Youtube URL">
			<button type="submit">ДОДАТИ ТРАНСЛЯЦІЮ</button>
		</form>
<?php }

function displayYoutube($youtube)
{ ?>
		<div class="margin-b_30"></div>
		<div class="margin-b_30"></div>
		<a href=<?=(YT_HEADER.$youtube)?>>
			<div class="available_form">
				<button>YOUTUBE</button>
			</div>
		</a>
<?php }

function liveForm($tableID)
{ ?>
	<div class="margin-b_30"></div>
	<div class="margin-b_30"></div>
	<div class="margin-b_30"></div>
	<form class="available_form" action="tableLobby.php"
	method="post">
		<input type="hidden" name="id" value="<?=$tableID?>"/>
		ВИДАЛИТИ ВСІ ДАНІ МАТЧУ
		<button type="submit" name="reset" class="red">
			ЗУПИНИТИ МАТЧ
		</button>
		ОБЕРЕЖНО
	</form>

<?php }

function finishedForm($tableID, $tournamentID)
{ ?>
	<div class="margin-b_30"></div>
	<div class="margin-b_30"></div>
	<form class="available_form" action="tableLobby.php"
	method="post">
		<input type="hidden" name="id" value="<?=$tableID?>"/>
		<input type="hidden" name="tournament" value="<?=$tournamentID?>"/>	
		<button type="submit" name="exit" class="red">ВИЙТИ</button>
		<button type="submit" name="next">НАСТУПНИЙ МАТЧ</button>
	</form>

<?php }

function occupiedHeader($name)
{ ?>
    <div class="sub-container">
        <div class="margin-b_30"></div>
        <div class="available_box">
            <div class="available_header">
                <span><?=$name?></span>
            </div>

<?php }
function occupiedFooter()
{ ?>
		</div>
	</div>
<?php }
?>

