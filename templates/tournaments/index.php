<link rel="stylesheet" type="text/css" href="<?=PATH_H?>css/tournament_list.css"> 

<?php

generalHeader();

printList("Live");

printList("Registration");

printList("Announced");

printList("Standby");

printList("Finished");

generalFooter();


function castHeader($header)
{
	if($header == "Live")
		return "Наживо";
	else if($header == "Registration")
		return "Триває Реєстрація";
	else if($header == "Announced")
		return "Оголошені";
	else if($header == "Standby")
		return "Очікують на початок";
	else if($header == "Finished")
		return "Завершені";
}

function printList($status)
{
	$data = query("SELECT TV.tournamentID, TV.tournament, TV.billiard, 
				TV.age, TV.sex, TV.clubName
				FROM generalTournamentView TV WHERE TV.status=?
				ORDER BY 2, 3 DESC, 4, 5", $status);

	$data_count = count($data);
	
	listHeader( castHeader($status) );

	for($i=0; $i < $data_count; $i++)
	{
		$id = $data[$i][0]; $billiard = $data[$i][2];
		$age = $data[$i][3]; $sex = $data[$i][4];
		$clubName = $data[$i][5];
		
		$name = $data[$i][1] . "(" . $billiard . ")";
		if( strcmp($age, "") || strcmp($sex, "") )
			$name = $name . "(" . $age . " " . $sex . ")";
		$isLast = ($i+1==$data_count);

		printTournament($i+1, $id, $name, $clubName, $isLast);
	}

	listFooter();
}

function printTournament($i, $id, $name, $clubName, $isLast)
{
	$e_o = ($i%2) ? "odd" : "even";
?>
			<tr onclick="openTournamentLobby(<?=$id?>);"
			class="tbody_<?=$e_o?> pointer">
				<td class="bold <?=$e_o?>_num<?=($isLast)?" radius_bl":""?>">
					<?=$i?>
				</td>
				<td>
					<?=$name?>
				</td>
				<td>
					<?=$clubName?>
				</td>
				<td class="<?=($isLast)?"radius_br":""?>">
					DATE TMP
				</td>
			</tr>
<?php
}

function generalHeader()
{ ?>
	<div class="sub-container">
		<div class="section_header">
			<div class="header_sign">
				<img class="header_icon" alt="calendar" src="<?=PATH_H?>img/web/calendar.png"> 
				Календар
			</div>
		</div>
<?php
}

function generalFooter()
{ ?>
	</div>
<?php
}

function listHeader($status)
{
?>
	<div class="section_header">
		<div class="header_sign">
			<?=$status?>
		</div>
	</div>

	<div class="list_container margin-b_30">
	<table class="list_table calendar_table">
		<colgroup>
			<col class="col-1">
			<col class="col-2">
			<col class="col-3">
			<col class="col-4">
		</colgroup>
		<thead>
			<tr>
				<th>#</th>
				<th>
					<img class="thead_icon" alt="trophy_image" src="<?=PATH_H?>img/web/trophy.png"> 
					<span>Турнір</span>
				</th>
				<th>
					<img class="thead_icon" alt="location_image" src="<?=PATH_H?>img/web/location.png"> 
					<span>Місце</span>
				</th>
				<th>
					<img class="thead_icon" alt="calendar_image" src="<?=PATH_H?>img/web/calendar.png"> 
					<span>Дата</span>
				</th>
			</tr>
		</thead>
		<tbody>
<?php
}

function listFooter()
{
?>
		</tbody>	
	</table>
	</div>
<?php
}
?>