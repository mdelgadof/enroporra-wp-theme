<?php

/**
 * @param EP_Fixture $fixture
 *
 * @throws Exception
 */
function fixtureHTML(EP_Fixture $fixture, array $userBets = []) {

	if ($fixture->isFuture()) {
		$title = $fixture->getDateHTML();
        $subtitle=$fixture->getTournamentLabel();
        $stats = $fixture->getBetsStatsPre();
        $class = "future";
    }
    else if ($fixture->isLive()) {
        $title = __('En directo','enroporra').', '.$fixture->getLiveMinuteLabel();
        $subtitle = $fixture->getTournamentLabel();
        $stats = $fixture->getBetsStatsPre();
        $class = "live";
    }
    else {
        $title = __('Terminado','enroporra').', '.$fixture->getDateHTML('date');
	    $subtitle = $fixture->getTournamentLabel();
        $class = "past";
    }

    $goals = (!$fixture->isFuture()) ? $fixture->getScorers():array();
    // Use class recent-goal on live events.
    $recent_goal1 = $recent_goal2 = "";
    $goals1 = ($fixture->isPlayed()) ? $fixture->getGoals(1) : (($fixture->isLive()) ? $fixture->getGoals(1,true) : '');
	$goals2 = ($fixture->isPlayed()) ? $fixture->getGoals(2) : (($fixture->isLive()) ? $fixture->getGoals(2,true) : '');
    // Prediction published only if bets are close
    $prediction = '';
    $myBetsHtml = '';
    if (in_array($fixture->getCompetition()->getStage(),array(EP_Competition::GROUP_STAGE_PLAYING,EP_Competition::PLAYOFF_PLAYING)) && ($fixture->isFuture() || $fixture->isLive())) {
        if (!empty($userBets) && is_user_logged_in()) {
            $realT1 = $fixture->getTeam(1)->getId();
            $realT2 = $fixture->getTeam(2)->getId();
            foreach ($userBets as $bet) {
                $betScore = $bet->getFixtureBet($fixture->getFixtureNumber());
                if (empty($betScore)) continue;
                $winner = $betScore['winner'] ?? 'X';
                $isDead = false;
                if ($realT1 && $realT2 && $winner !== 'X') {
                    $winnerTeamId = $betScore['t' . $winner]->getId();
                    $isDead = $winnerTeamId && !in_array($winnerTeamId, [$realT1, $realT2]);
                }
                $deadClass = $isDead ? ' class="dead-bet"' : '';
                $myBetsHtml .= '<span' . $deadClass . '>'
                    . esc_html($bet->getName()) . ': '
                    . $betScore['t1']->getFlagHTML(20) . ' ' . $betScore['s1'] . '-' . $betScore['s2'] . ' ' . $betScore['t2']->getFlagHTML(20)
                    . '</span><br />';
            }
            if ($myBetsHtml) {
                $myBetsHtml = '<div class="">' . __('Mis apuestas', 'enroporra') . '</div>' . $myBetsHtml;
            }
        }
        $prediction = '<div class="">' . __( 'Nuestros apostantes dicen', 'enroporra' ) . '</div>';
        $t1id = $fixture->getTeam(1)->getId();
        $t2id = $fixture->getTeam(2)->getId();
        $unqualified_count = 0;
        foreach ($stats["winners"] as $winner_id => $times) {
            if ($winner_id !== $t1id && $winner_id !== 'X' && $winner_id !== $t2id) {
                $unqualified_count += $times;
            }
        }
        foreach ([$t1id, 'X', $t2id] as $winner_id) {
            if (!isset($stats["winners"][$winner_id])) continue;
            $times = $stats["winners"][$winner_id];
            $label = ($winner_id === 'X')
                ? __('Empate', 'enroporra')
                : (new EP_Team(intval($winner_id)))->getFlagHTML(20);
            $prediction .= $label . ': <span class="number">' . round( $times * 100 / $stats["total"] ) . '%</span> &nbsp;&nbsp;';
        }
        if ($unqualified_count > 0) {
            $prediction .= __('No clasificados', 'enroporra') . ': <span class="number">' . round( $unqualified_count * 100 / $stats["total"] ) . '%</span> &nbsp;&nbsp;';
        }
        $moreRepeatedResultData = !empty($stats["scores"]) ? explode("|",array_key_first( $stats["scores"] )) : [];
        $moreWeirdResultData = [];
        if (!empty($stats["scores"])) {
            $minCount = min($stats["scores"]);
            $maxGoals = -1;
            foreach ($stats["scores"] as $_key => $_count) {
                if ($_count === $minCount) {
                    $_parts = explode("|", $_key);
                    $_goals = explode("-", $_parts[1]);
                    $_totalGoals = (int)$_goals[0] + (int)$_goals[1];
                    if ($_totalGoals > $maxGoals) {
                        $maxGoals = $_totalGoals;
                        $moreWeirdResultData = explode("|", $_key);
                    }
                }
            }
        }
        if (is_numeric($moreRepeatedResultData[0]) && is_numeric($moreRepeatedResultData[2])) {
            $prediction .=
                '<br />' . __('Resultado más repetido', 'enroporra') . ': <span class="number">' . (new EP_Team($moreRepeatedResultData[0]))->getFlagHTML(20) . $moreRepeatedResultData[1] . (new EP_Team($moreRepeatedResultData[2]))->getFlagHTML(20) . ' (' . round(array_shift($stats["scores"]) * 100 / $stats["total"]) . '%)</span>' .
                '<br />' .
                __('Resultado más raro', 'enroporra') . ': <span class="number">' . (new EP_Team($moreWeirdResultData[0]))->getFlagHTML(20) . $moreWeirdResultData[1] . (new EP_Team($moreWeirdResultData[2]))->getFlagHTML(20) . '</span>';

            // Media de goles pronosticados
            $sum1 = $sum2 = 0;
            foreach ($stats["scores"] as $key => $count) {
                $parts = explode("|", $key);
                $goals = explode("-", $parts[1]);
                $sum1 += (int)$goals[0] * $count;
                $sum2 += (int)$goals[1] * $count;
            }
            $prediction .= '<br />' . __('Media pronosticada', 'enroporra') . ': <span class="number">' .
                $fixture->getTeam(1)->getFlagHTML(20) . ' ' . round($sum1 / $stats["total"], 1) .
                ' – ' .
                round($sum2 / $stats["total"], 1) . ' ' . $fixture->getTeam(2)->getFlagHTML(20) . '</span>';

            // Apostantes con pichichi en juego
            $pichichi_count = array_sum($stats["players"]);
            if ($pichichi_count > 0) {
                $prediction .= '<br />' . sprintf(
                    _n('%d apostante tiene su pichichi jugando este partido',
                       '%d apostantes tienen su pichichi jugando este partido',
                       $pichichi_count, 'enroporra'),
                    $pichichi_count
                );
            }
        }
    }
    if ($fixture->isPlayed()) {
        $stats = $fixture->getBetsStatsPost();
        $winner_label = ($fixture->getWinner() === "X") ? __('Acertantes del empate','enroporra') : __('Acertantes del ganador','enroporra');
        $exactResultsText = '<span class="number">'.__('Acertantes del resultado','enroporra').': '.$stats["results"].'</span>';
        if ($stats["total"] > 0 && $stats["results"] > 0 && ($stats["results"] / $stats["total"]) <= 0.01) {
            foreach ($fixture->getBetsExactResultBets()['bets'] as $exactBet) {
                $exactResultsText .= '<br /><span class="number"><a href="'.$exactBet->getUrl().'">'.$exactBet->getName().'</a></span>';
            }
        }
        $results = '<br /><div class="">' . __('Puntuaron','enroporra') . '</div>' .
                $exactResultsText.'<br />'.
                '<span class="number">'.$winner_label.': '.$stats["winners"].'</span>';
    }
	?>
	<div class="score <?php echo $class ?>" data-fixture-id="<?php echo $fixture->getId() ?>">
		<?php if ($fixture->isLive()): ?>
			<span class="live-badge"><?php echo strtoupper(__('En directo','enroporra')) ?></span>
		<?php endif; ?>
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
			<?php if ($fixture->isFuture() || $fixture->isLive()) {
                echo $myBetsHtml;
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
        echo "<div class='bet2-matchtitle'><strong>".$fixture->getTournamentLabel()."</strong> ".$fixture->getDateHTML()."</div>";
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