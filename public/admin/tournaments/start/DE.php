<?php

require("../../../../includes/adminConfig.php");

if($_SERVER["REQUEST_METHOD"] == "POST")
{
	list($seeding, $KO_matches, $id) = checkData($_POST["seeding"], $_POST["matches"], $_POST["id"]);
	list($N, $LOW_R, $UP_R, $KO_R) = getData($KO_matches, $id);


	query("CALL DoubleElimGenerate(?,?,?,?,?,?,?)", $id, $N, $LOW_R, $UP_R, $KO_R, $KO_matches, $seeding);
	
	seedPlayers($id, $N/2, 1);
	
	redirect("../lobby.php?id=$id");
}
else
{
	redirect("../");
}


function seedPlayers($id, $seeded)
{
	$seed_array = getSeedingArray(LOG($seeded*2, 2), 1, $seeded*2);
	for($i=0; $i<$seeded; $i++)
	{
		$query = "CALL seedPlayer(?,?,?,?)";
		query($query, $id, $i+1, "UP", $seed_array[$i*2]);
		query($query, $id, $i+1, "UP", $seed_array[$i*2+1]);
	}

}

function checkData($seeding, $KO_matches, $id)
{
    if( !nonEmpty($seeding, $id) )
        redirect("../");
	if( !nonEmpty($KO_matches) )
	{
		adminApology(INPUT_ERROR,"\"Matches in KO\" required for DOUBLE ELIMINATION");
		exit;
	}
    if( !exists("tournament", $id) )
        redirect("../");

    return array($seeding, $KO_matches, $id);
}

function getPlayers($id)
{
    $data = query("SELECT registeredPlayers FROM tournament WHERE id = ?", $id);
    return $data[0][0];
}

function getData($KO_matches, $id)
{
    $registered = getPlayers($id);
	$N = firstGreaterPowerOf2($registered);

    if( $KO_matches > $N/2 )
    {
        adminApology(INPUT_ERROR, "KO matches cannot exceed (number of players in first round)/2");
        exit;
    }

	$KO_R = LOG( $KO_matches*2, 2 );
	$UP_R = LOG( $N/$KO_matches, 2 );
	$LOW_R = 2 * ($UP_R-1);

    return array($N, $LOW_R, $UP_R, $KO_R);
}


?>
