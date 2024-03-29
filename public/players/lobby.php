<?php

	require("../../includes/config.php");

	
	if($_SERVER["REQUEST_METHOD"] == "GET")
	{
		$playerID = $_GET["id"];
		if( !exists("player", $playerID) )
			redirect("");


		list($fName, $lName, $img, $birthday, $highestBreak, $country, $city)
			 = getPlayer($playerID); 
		
		list($tournamentCtr, $breakCtr) = cntTournamentAndBreak($playerID);
		render("players/lobby.php",
		["title"=>$lName." ".$fName, "fName"=>$fName, "lName"=>$lName,
		"img"=>$img, "playerID"=>$playerID, "birthday"=>$birthday,
		"highestBreak"=>$highestBreak, "tournamentCtr"=>$tournamentCtr,
		"breakCtr"=>$breakCtr, "country"=>$country, "city"=>$city]);
	}
	else
	{
		redirect("");
	}


function cntTournamentAndBreak($playerID)
{
	$query = "SELECT count(id) FROM break WHERE playerID=?";
	$breaks_cnt = query($query, $playerID);

	$query = "SELECT count(tournamentID) FROM playerTournamentView
			WHERE playerID=? AND points IS NOT NULL";
	$tournaments_cnt = query($query, $playerID);
	return array($tournaments_cnt[0][0], $breaks_cnt[0][0]);
}

function getPlayer($id)
{
	$query="SELECT firstName,lastName,photo,birthday,highestBreak,
			country, city FROM player WHERE id=?";

	$data = query($query, $id);
	$birthday = date('Y-m-d', strtotime($data[0][3]));
	return array($data[0][0],$data[0][1],$data[0][2],$birthday,$data[0][4],$data[0][5],$data[0][6]);
}

?>
