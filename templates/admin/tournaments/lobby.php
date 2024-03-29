
<?php

list($name, $billiard, $details, $league, $bracket) =
    getFullName($tournamentID);

tournamentHeader($name, $billiard, $details, $league);



if( !strcmp($status, "Announced") )
	announcedLobby($tournamentID, $onClick);

else if( !strcmp($status, "Registration") )
	registrationLobby($tournamentID, $onClick);

else if( !strcmp($status, "Standby") )
	standbyLobby($tournamentID, $onClick);

else
	redirect("");


function tournamentHeader($name, $billiard, $details, $league)
{ ?>
    <div class="tour_menu_box">
        <div class="tournament_header">
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

<?php }



function announcedLobby($tournamentID, $onClick)
{
//lobby navigation
	require("navigations/announced.php");


//lobby block to show data
	?><div class="sub-container"><?php


//close lobby block
	?></div><?php
}

function registrationLobby($tournamentID, $onClick)
{
//lobby navigation
	require("navigations/registration.php");


//lobby block to show data
	?><div class="sub-container"><?php


//show appropriate data
	if( !strcmp($onClick, "players") )
		require("lobbyDetails/registeredPlayersListSmall.php");
	else if( !strcmp($onClick, "default") || !strcmp($onClick, "register") ) {
		require("lobbyDetails/playerRegisterForm.php");
		require("lobbyDetails/registeredPlayersListSmall.php");
	}	
	else
		redirect("");


//close lobby block
	?></div><?php
}

function standbyLobby($tournamentID, $onClick)
{
//lobby navigation
	require("navigations/standby.php");


//lobby block to show data
	?><div class="sub-container"><?php


//show appropriate data
	if( !strcmp($onClick, "default") || !strcmp($onClick, "players") )
		require("lobbyDetails/registeredPlayersListSmall.php");
	else if( !strcmp($onClick, "KO") )
		require("forms/KO.php");
	else if( !strcmp($onClick, "DE") )
		require("forms/DE.php");
	else if( !strcmp($onClick, "GR-KO") )
		require("forms/GR-KO.php");
	else
		redirect("");


//close lobby block
	?></div><?php
}

function getFullName($tournamentID)
{
    $query = "SELECT TV.bracket, TV.billiard, TV.age, TV.sex,
        TV.tournament, TV.league 
        FROM generalTournamentView TV WHERE tournamentID=?";
    $data = query($query, $tournamentID);

    $bracket = $data[0][0]; $billiard = $data[0][1];
    $name = $data[0][4]; $league = $data[0][5];

    $details = castDetails($data[0][2], $data[0][3]);

    return array($name, $billiard, $details, $league, $bracket);
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
}

?>
