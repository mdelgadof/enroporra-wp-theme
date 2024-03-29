<?php

/**
 * @param EP_Fixture $fixture
 *
 * @throws Exception
 */
function fixtureHTML(EP_Fixture $fixture) {

	if ($fixture->isFuture()) {
		$title = weekDay(date("w",strtotime($fixture->getRawDate())))." ".$fixture->getDate();
        $subtitle=$fixture->getTournamentLabel();
        $stats = $fixture->getBetsStatsPre();
        $class = "future";
    }
    else if ($fixture->isLive()) {
        $title = __('En directo','enroporra').', '.$fixture->getLiveMinute().'\'';
        $subtitle = $fixture->getTournamentLabel();
        $class = "live";
    }
    else {
        $title = __('Terminado','enroporra').', '.explode(" ",$fixture->getDate())[0];
	    $subtitle = $fixture->getTournamentLabel();
        $class = "past";
    }

    $goals = (!$fixture->isFuture()) ? $fixture->getScorers():array();
    // Use class recent-goal on live events.
    $recent_goal1 = $recent_goal2 = "";
    $goals1 = ($fixture->isPlayed()) ? $fixture->getGoals(1) : (($fixture->isLive()) ? $fixture->getGoals(1,true) : '');
	$goals2 = ($fixture->isPlayed()) ? $fixture->getGoals(2) : (($fixture->isLive()) ? $fixture->getGoals(2,true) : '');
    // Prediction published only if bets are close
    if (in_array($fixture->getCompetition()->getStage(),array(EP_Competition::GROUP_STAGE_PLAYING,EP_Competition::PLAYOFF_PLAYING)) && $fixture->isFuture()) {
        $prediction = '<div class="">' . __( 'Nuestros apostantes dicen', 'enroporra' ) . '</div>';
        foreach ($stats["winners"] as $winner_id => $times)
	        $prediction .= (new EP_Team($winner_id))->getFlagHTML( 20 ) . ': <span class="number">' . round( $times * 100 / $stats["total"] ) . '%</span> &nbsp;&nbsp;';
	    if ($fixture->getTournament()=="groups") {
            $prediction.=__( 'Empate', 'enroporra' ) . ': <span class="number">' . round( $stats["winners"]["X"] * 100 / $stats["total"] ) . '%</span> &nbsp;';
        }
        $moreRepeatedResultData = explode("|",array_key_first( $stats["scores"] ));
	    $moreWeirdResultData = explode("|",array_key_last( $stats["scores"] ));
        $prediction.=
	                  '<br />' . __( 'Resultado más repetido', 'enroporra' ) . ': <span class="number">' . (new EP_Team($moreRepeatedResultData[0]))->getFlagHTML(20) . $moreRepeatedResultData[1] . (new EP_Team($moreRepeatedResultData[2]))->getFlagHTML(20) . ' (' . round( array_shift( $stats["scores"] ) * 100 / $stats["total"] ) . '%)</span>'.
	                  '<br />' .
	                  __( 'Resultado más raro', 'enroporra' ) . ': <span class="number">' . (new EP_Team($moreWeirdResultData[0]))->getFlagHTML(20) . $moreWeirdResultData[1] . (new EP_Team($moreWeirdResultData[2]))->getFlagHTML(20) .'</span> 
        ';
    }
    if ($fixture->isPlayed()) {
        $stats = $fixture->getBetsStatsPost();
        $results = '<br /><div class="">' . __('Puntuaron','enroporra') . '</div>' .
                '<span class="number">'.__('Acertantes del resultado').': '.$stats["results"].'</span><br />'.
                '<span class="number">'.__('Acertantes del ganador/empate').': '.$stats["winners"].'</span>';
    }
	?>
	<div class="score <?php echo $class ?>">
		<div class="score-title"><?php echo $title ?></div>
		<div class="score-leg"><?php echo $subtitle ?></div>
		<div class="score-teams">
			<table class="score-table <?php echo $class ?>">
				<tr>
					<td class="score-team1"><?php echo $fixture->getTeam(1)->getName() ?></td>
                    <td></td>
					<td class="score-team2"><?php echo $fixture->getTeam(2)->getName() ?></td>
				</tr>
				<tr>
					<td class="score-flag1"><img src="<?php echo $fixture->getTeam(1)->getFlagUrl(); ?>" /></td>
					<td></td>
					<td class="score-flag2"><img src="<?php echo $fixture->getTeam(2)->getFlagUrl(); ?>" /></td>
				</tr>
                <?php if (!$fixture->isFuture()) { ?>
				<tr>
					<td class="score-goals1 <?php echo $recent_goal1 ?>"><span><?php echo $goals1; ?></span></td>
					<td></td>
					<td class="score-goals2 <?php echo $recent_goal2 ?>"><span><?php echo $goals2; ?></span></td>
				</tr>
                <?php } ?>
            </table>
            <div class="underscore-desktop">
			<?php if ($fixture->isFuture()) {
                echo $prediction;
			} else { ?>
                <div class="score-scorers-wrapper">
					<?php
					foreach ($goals as $goal) {
						// Use class recent-scorer for recent goals on live events
						$recent = (false) ? " recent-scorer":"";
						?>
                        <div class="score-scorers-goal <?php echo $recent ?>">
                            <img src="<?php echo $goal["team_for"]->getFlagUrl() ?>" width="20"/>&nbsp;
							<?php echo $goal["player"]->getName(); ?>
							<?php if ($goal["type"]!="") echo ' ('.$goal["type"].') '; ?>
							<?php echo $goal["minute"] ?>'
                        </div>
					<?php } ?>
                </div>
                <?php echo $results ?>
			<?php } ?>
            </div>
        </div>
	</div>
<?php }

/**
 * @throws Exception
 */
function teamBoxfixturePlayOffHTML(EP_Fixture $fixture, int $teamNumber) : string {
    $opponentNumber = ($teamNumber==1) ? 2:1;
    return "
        <div class='bet2-team' id='teamdiv_".$fixture->getLabelTeam($teamNumber)."' data-opponent-id='teamdiv_".$fixture->getLabelTeam($opponentNumber)."' data-team-number='".$teamNumber."' data-team-id='".$fixture->getTeam($teamNumber)->getId()."'>
           <div class='bet2-team-name betTeamName'>".$fixture->getTeam($teamNumber)->getFlagHTML(25)." ".$fixture->getTeam($teamNumber)->getName()."</div>
           <div class='bet2-team-result'><input class='betTeamResult' type='number' name='".$fixture->getFixtureNumber()."_team".$teamNumber."result' required /></div>
           <input class='bet2-team-id' type='hidden' name='".$fixture->getFixtureNumber()."_team".$teamNumber."id' value='".$fixture->getTeam($teamNumber)->getId()."' />
        </div>
    ";
}

/**
 * @throws Exception
 */
function fixturePlayOffHTML(EP_Fixture $fixture) {
    echo "<div class='bet2-matchdiv' id='matchdiv_".$fixture->getFixtureNumber()."' data-fixture-number='".$fixture->getFixtureNumber()."'>";
        echo "<div class='bet2-matchtitle'><strong>".$fixture->getTournamentLabel()."</strong> ".$fixture->getDate()."</div>";
        echo teamBoxfixturePlayOffHTML($fixture,1);
	    echo teamBoxfixturePlayOffHTML($fixture,2);
        echo "<div class='bet2-resolve-draw'>";
            echo __('Penaltis','enroporra').": ";
            echo "<div class='bet2-penalties-winner' data-winner='1' id='penalties1_".$fixture->getFixtureNumber()."'>&nbsp;</div><div class='bet2-penalties-flag bet2-penalties-flag-1' data-winner='1'>".$fixture->getTeam(1)->getFlagHTML(20)."</div><div>&nbsp;&nbsp;&nbsp;</div>";
	        echo "<div class='bet2-penalties-winner' data-winner='2' id='penalties2_".$fixture->getFixtureNumber()."'>&nbsp;</div><div class='bet2-penalties-flag bet2-penalties-flag-2' data-winner='2'>".$fixture->getTeam(2)->getFlagHTML(20)."</div>";
	        echo "<input type='hidden' name='".$fixture->getFixtureNumber()."_winner' value='' />";
        echo "</div>";

	echo "</div>";
}