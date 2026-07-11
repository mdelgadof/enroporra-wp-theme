<?php

function verifiedTickHTML(string $titleText, string $size) : string {
	return "<img src='".get_template_directory_uri()."/images/icons/verified.png' class='verified-".$size."' title='".$titleText."' />";
}

/**
 * @throws Exception
 */
function rankingHTML(EP_Competition $competition, array $betsTable, array $userBets, array $friendsBets = array()) {
    $user = new EP_User(get_current_user_id());
    $admin = ($user && $user->isAdmin());
    ?>
    <table>
        <thead>
        <th><?php _e("Porrista","enroporra"); ?></th>
        <th class="hide-mobile hide-tablet" style="padding-left:20px"><?php _e("Pichichi","enroporra") ?></th>
        <?php if ($competition->getStage()>=EP_Competition::PLAYOFF_PLAYING || $admin) { ?><th class="hide-mobile hide-tablet" style="padding-left:20px"><?php _e("Árbitro","enroporra") ?></th><?php } ?>
        <th class="hide-mobile" style="padding-left:20px"><?php _e("Próximas apuestas","enroporra") ?></th>
        </thead>
        <tbody>
        <tr><td colspan=4><hr></td></tr>
        <?php
        $positionBefore=0;
        $ballEmoji = get_template_directory_uri()."/images/emojis/ball.png";
        $refereeEmoji = get_template_directory_uri()."/images/emojis/a.png";
        $nextFixtures = $competition->getNextFixtures(4);
        $fixtureRealTeams = [];
        foreach ($nextFixtures as $nf) {
            $fixtureRealTeams[$nf->getFixtureNumber()] = [
                $nf->getTeam(1)->getId(),
                $nf->getTeam(2)->getId(),
            ];
        }
        foreach ($betsTable as $betRow) {
            $color = ($positionBefore==$betRow["position"]) ? "grey":"black";
            $size = ($betRow["position"]<=5) ? "big":"normal";
            //$verified = ($betRow["paid"]) ? "<img src='".get_template_directory_uri()."/images/icons/verified.png' class='verified-".$size."' title='".__("Pago verificado","enroporra")."' />":"";
            $verified = ($betRow["bet"]->isPlayoffFulfilled())  ? "<img src='".get_template_directory_uri()."/images/icons/verified.png' class='verified-".$size."' title='".__("Segunda fase completada","enroporra")."' />":"";
            //$verified = "";

            $classTr = (in_array($betRow["bet"]->getId(),$userBets)) ? "my-bet" : ( (in_array($betRow["bet"]->getId(),$friendsBets)) ? "my-friend" : "");
            $playerGoals = $competition->getGoalsByPlayer($betRow["bet"]->getPlayer());
            $playerGoalsString = ($playerGoals) ? " (".$playerGoals.")" : "";
	        $verifiedPlayer = (EP_Player::isPlayer($betRow["bet"]->getPlayer()) && !is_null($competition->getTopScorers()) && !empty($competition->getTopScorers()) && EP_Player::inArrayPlayers($betRow["bet"]->getPlayer(),$competition->getTopScorers())) ? verifiedTickHTML(__('Pichichi de la competición','enroporra'),$size) : "";
            $playerLine = $betRow["bet"]->getPlayer()->getName().$playerGoalsString.$verifiedPlayer;

            $showReferee = ($competition->getStage()>=EP_Competition::PLAYOFF_PLAYING || $admin);
            $refereeLine = "";
            if ($showReferee) {
	            $verifiedReferee = (!is_null($betRow["bet"]->getReferee()) && !is_null($competition->getReferee()) && $betRow["bet"]->getReferee()->getId()==$competition->getReferee()->getId()) ? verifiedTickHTML(__('Árbitro de la final','enroporra'),$size) : "";
	            $refereeName = (is_null($betRow["bet"]->getReferee())) ? "" : $betRow["bet"]->getReferee()->getName();
                $refereeLine = $refereeName.$verifiedReferee;
            }

            $nextBetsSpans = [];
            foreach ($nextFixtures as $nextFixture) {
                /** @var EP_Fixture $nextFixture */
                if ($nextFixture->getTournament()!="groups" && $competition->getStage()<EP_Competition::PLAYOFF_PLAYING && !$betRow["bet"]->getOwner()->isViewing() && !$admin)
                    continue;
                $betNext = $betRow["bet"]->getFixtureBet($nextFixture->getFixtureNumber());
                if (empty($betNext)) continue;
                $realTeams = $fixtureRealTeams[$nextFixture->getFixtureNumber()] ?? [0, 0];
                $winner    = $betNext['winner'] ?? 'X';
                $isDead    = false;
                if ($winner !== 'X') {
                    $winnerTeamId = isset($betNext['t' . $winner]) ? $betNext['t' . $winner]->getId() : 0;
                    if ($realTeams[0] && $realTeams[1]) {
                        $isDead = $winnerTeamId && !in_array($winnerTeamId, $realTeams);
                    } else {
                        // Cruce todavía no resuelto: solo podemos saber que la apuesta
                        // está muerta si el equipo pronosticado ya fue eliminado antes.
                        $isDead = $winnerTeamId && $competition->isTeamEliminated($winnerTeamId);
                    }
                }
                $deadClass = $isDead ? ' class="dead-bet"' : '';
                $isDrawPick = ($betNext["s1"] == $betNext["s2"]);
                $nextBetsSpans[] = '<span' . $deadClass . '>' . $betNext["t1"]->getFlagHTML(20, $isDrawPick && $winner === '1') . ' ' . $betNext["s1"] . '-' . $betNext["s2"] . ' ' . $betNext["t2"]->getFlagHTML(20, $isDrawPick && $winner === '2') . '</span>';
            }

            $substats = "<div class='ranking-substats'><span class='substat-item'><img src='".$ballEmoji."' class='substat-emoji' alt=''> ".$playerLine."</span>";
            if ($refereeLine !== "") {
                $substats .= "<span class='substat-item'><img src='".$refereeEmoji."' class='substat-emoji' alt=''> ".$refereeLine."</span>";
            }
            $substats .= "</div>";
            if (!empty($nextBetsSpans)) {
                $substats .= "<div class='ranking-next-mobile next-bets-wrap'>" . implode('', array_map(function($span) { return "<div class='next-bet-item'>{$span}</div>"; }, $nextBetsSpans)) . "</div>";
            }

            echo "<tr class='".$classTr."'><td><div class='".$size."-text black-link'><b class='".$color."-text'>".$betRow["position"]."</b> <a href='".$betRow["bet"]->getUrl()."'>".$betRow["bet"]->getName()."</a>".$verified." <span class='points-text'>".$betRow["points"]."</span></div>".$substats."</td>";
            echo "<td class='hide-mobile hide-tablet' style='padding-left:20px'>".$playerLine."</td>";
            if ($showReferee) {
                echo "<td class='hide-mobile hide-tablet' style='padding-left:20px'>".$refereeLine."</td>";
            }
            echo "<td class='hide-mobile' style='padding-left:20px'>";
            echo "<div class='next-bets-wrap'>" . implode('', array_map(function($span) { return "<div class='next-bet-item'>{$span}</div>"; }, $nextBetsSpans)) . "</div>";
            echo "</td>";
            echo "</tr><tr><td colspan=4><hr></td></tr>";

            $positionBefore=$betRow["position"];
        }
        ?>
        </tbody>
    </table>
    <?php
}

/**
 * Renderiza el ranking desde el caché precomputado (wp_option).
 * Misma salida HTML que rankingHTML() pero sin queries de post_meta por apuesta.
 */
function rankingHTMLCached(EP_Competition $competition, array $cache, array $userBets, array $friendsBets = []): void {
    $currentUser      = new EP_User(get_current_user_id());
    $admin            = ($currentUser && $currentUser->isAdmin());
    $currentUserId    = get_current_user_id();
    $stage            = $competition->getStage();
    $topScorerIds     = $cache['top_scorer_ids'] ?? [];
    $compRefereeId    = $cache['competition_referee_id'] ?? null;
    $nextFixturesData = $cache['next_fixtures_data'] ?? [];
    ?>
    <table>
        <thead>
        <th><?php _e("Porrista","enroporra"); ?></th>
        <th class="hide-mobile hide-tablet" style="padding-left:20px"><?php _e("Pichichi","enroporra") ?></th>
        <?php if ($stage >= EP_Competition::PLAYOFF_PLAYING || $admin) { ?><th class="hide-mobile hide-tablet" style="padding-left:20px"><?php _e("Árbitro","enroporra") ?></th><?php } ?>
        <th class="hide-mobile" style="padding-left:20px"><?php _e("Próximas apuestas","enroporra") ?></th>
        </thead>
        <tbody>
        <tr><td colspan=4><hr></td></tr>
        <?php
        $positionBefore = 0;
        $ballEmoji = get_template_directory_uri()."/images/emojis/ball.png";
        $refereeEmoji = get_template_directory_uri()."/images/emojis/a.png";
        foreach ($cache['rows'] as $row) {
            $color = ($positionBefore === $row['position']) ? 'grey' : 'black';
            $size  = ($row['position'] <= 5) ? 'big' : 'normal';

            $verified = $row['playoff_fulfilled']
                ? "<img src='".get_template_directory_uri()."/images/icons/verified.png' class='verified-{$size}' title='".__("Segunda fase completada","enroporra")."' />"
                : '';

            $classTr = in_array($row['id'], $userBets) ? 'my-bet'
                     : (in_array($row['id'], $friendsBets) ? 'my-friend' : '');

            $playerGoalsString = $row['player_goals'] ? ' (' . $row['player_goals'] . ')' : '';
            $verifiedPlayer    = ($row['player_id'] && in_array($row['player_id'], $topScorerIds))
                ? "<img src='".get_template_directory_uri()."/images/icons/verified.png' class='verified-{$size}' title='".__('Pichichi de la competición','enroporra')."' />"
                : '';
            $playerLine = $row['player_name'].$playerGoalsString.$verifiedPlayer;

            $showReferee = ($stage >= EP_Competition::PLAYOFF_PLAYING || $admin);
            $refereeLine = '';
            if ($showReferee) {
                $isRefereeHit    = ($row['referee_id'] && $row['referee_id'] === $compRefereeId);
                $verifiedReferee = $isRefereeHit
                    ? "<img src='".get_template_directory_uri()."/images/icons/verified.png' class='verified-{$size}' title='".__('Árbitro de la final','enroporra')."' />"
                    : '';
                $refereeLine = ($row['referee_name'] ?? '') . $verifiedReferee;
            }

            $isViewing = ($row['owner_id'] === $currentUserId);
            $nextBetsSpans = [];
            foreach ($nextFixturesData as $fixtureData) {
                if ($fixtureData['tournament'] !== 'groups'
                    && $stage < EP_Competition::PLAYOFF_PLAYING
                    && !$isViewing
                    && !$admin) {
                    continue;
                }
                $betNext = $row['next_bets'][$fixtureData['number']] ?? null;
                if (!$betNext) continue;
                $deadClass = ($betNext['is_dead'] ?? false) ? ' class="dead-bet"' : '';
                $nextBetsSpans[] = '<span' . $deadClass . '>' . $betNext['t1_flag'] . ' ' . $betNext['s1'] . '-' . $betNext['s2'] . ' ' . $betNext['t2_flag'] . '</span>';
            }

            $substats = "<div class='ranking-substats'><span class='substat-item'><img src='".$ballEmoji."' class='substat-emoji' alt=''> ".$playerLine."</span>";
            if ($refereeLine !== "") {
                $substats .= "<span class='substat-item'><img src='".$refereeEmoji."' class='substat-emoji' alt=''> ".$refereeLine."</span>";
            }
            $substats .= "</div>";
            if (!empty($nextBetsSpans)) {
                $substats .= "<div class='ranking-next-mobile next-bets-wrap'>" . implode('', array_map(function($span) { return "<div class='next-bet-item'>{$span}</div>"; }, $nextBetsSpans)) . "</div>";
            }

            echo "<tr class='{$classTr}'><td><div class='{$size}-text black-link'><b class='{$color}-text'>{$row['position']}</b> <a href='{$row['url']}'>{$row['name']}</a>{$verified} <span class='points-text'>{$row['points']}</span></div>{$substats}</td>";
            echo "<td class='hide-mobile hide-tablet' style='padding-left:20px'>{$playerLine}</td>";

            if ($showReferee) {
                echo "<td class='hide-mobile hide-tablet' style='padding-left:20px'>{$refereeLine}</td>";
            }

            echo "<td class='hide-mobile' style='padding-left:20px'>";
            echo "<div class='next-bets-wrap'>" . implode('', array_map(function($span) { return "<div class='next-bet-item'>{$span}</div>"; }, $nextBetsSpans)) . "</div>";
            echo "</td>";
            echo "</tr><tr><td colspan=4><hr></td></tr>";

            $positionBefore = $row['position'];
        }
        ?>
        </tbody>
    </table>
    <?php
}
