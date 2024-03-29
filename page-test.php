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

if ($_GET["setpartidos"]==1) {
    $fixtures = $competition->getFixtures();
    if (count($fixtures)) {
        echo "<span style='color:red'>ERROR: Ya existen partidos en esta competición</span>";
    }
    else {
        $competition->setCompetitionFixtures();
        echo "OK, partidos generados para ".$competition->getName();
    }
}

if ($_GET["setfechas"]==1) {
    if ($_POST) {
        foreach ($_POST as $key => $date) {
            $fixtureId = explode('_', $key)[1];
            try {
                $fixture = new EP_Fixture($fixtureId);
                $fixture->setDate($date);
            }
            catch (Exception $e) {
                echo "No se ha podido crear el partido con id ".$fixtureId." o su fecha: ".$e->getMessage();
            }
        }
        echo "OK, fechas creadas";
    }
    else {
        $args['tournament'] = 'groups';
        $fixtures = $competition->getFixtures($args);
        echo "<form action='test.php?setfechas=1' method='post'>";
        foreach ($fixtures as $fixture) {
            echo "<input type='text' name='date_" . $fixture->getId() . "' value='2024-06-14 21:00:00' /> " . $fixture->getTeam(1)->getName() . " - " . $fixture->getTeam(2)->getName() . "<br>";
        }
        echo "<input type='submit' value='Enviar' />";
        echo "</form>";
    }
}