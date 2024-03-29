<?php

/** @var EP_Competition $competition */
$competition = $GLOBALS['ep_competition'];

if ($_GET["winnerRaro"]==1) {
	$bets = $competition->getBets();
	foreach ($bets as $bet) {
		if ($bet->isPlayoffFulfilled()) {
			$bet->calculatePoints();
		}
	}
}
if ($_GET["listamails"]==1) {
	$bets = $competition->getBets();
	foreach ($bets as $bet) {
		echo $bet->getOwner()->getEmail().", ";
	}
}

if ($_GET["listarbitros"]==1) {
	$refs = EP_Referee::getAllReferees();
	foreach ($refs as $referee) {
		/** @var EP_Referee $referee */
		echo $referee->getName()." ".$referee->getTeam()->getName()."<br>";
	}
}
if ($_GET["setarbitros"]==1) {

	function process($string) {
		$data = explode("/",$string);
		if ($team = EP_Team::getTeamByName($data[1])) {
			try {
				$referee = EP_Referee::createReferee(array("name"=>$data[0],"team"=>$team));
			}
			catch (Exception $e) {
				echo "<span style='color:red'>".$e->getMessage()."</span><br>";
				return;
			}
			echo "<span style='color:green'>".$referee->getName()." (".$referee->getTeam()->getName().") OK!</span><br>";
		}
		else echo "<span style='color:red'>".$data[1]." no existe como equipo</span><br>";
	}

process("Ivan Barton/El Salvador");
process("Mario Escobar/Guatemala");
process("Bakary Gassama/Gambia");
process("Mustapha Ghorbal/Argelia");
process("Victor Gomes/Sudáfrica");
process("Ning Ma/China");
process("Said Martínez/Honduras");
process("Salima Mukansanga/Ruanda");
process("Janny Sikazwe/Zambia");
process("Jesús Valenzuela/Venezuela");
process("Slavko Vincic/Eslovenia");
}

if ($_GET["completadas"]==1) {
	$bets = $competition->getBets();
	$i=0;
	foreach ($bets as $bet) {

		if ($bet->isPlayoffFulfilled()) {
			$i++;
			echo $i.") ".$bet->getName()." ".$bet->getPoints()."<br>";
		}

	}
}