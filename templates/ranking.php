<?php

function verifiedTickHTML(string $titleText, string $size) : string {
	return "<img src='".get_template_directory_uri()."/images/icons/verified.png' class='verified-".$size."' title='".$titleText."' />";
}

/**
 * @throws Exception
 */
function rankingHTML(EP_Competition $competition, array $betsTable, array $userBets, array $friendsBets = array()) {

    ?>
    <table>
        <thead>
        <th><?php _e("Porrista","enroporra") ?></th>
        <th class="hide-mobile" style="padding-left:20px"><?php _e("Pichichi","enroporra") ?></th>
        <?php if ($competition->getStage()>=EP_Competition::PLAYOFF_PLAYING) { ?><th class="hide-mobile" style="padding-left:20px"><?php _e("Árbitro","enroporra") ?></th><?php } ?>
        <th class="hide-mobile" style="padding-left:20px"><?php _e("Próximas apuestas","enroporra") ?></th>
        </thead>
        <tbody>
        <tr><td colspan=4><hr></td></tr>
        <?php
        $positionBefore=0;
        $nextFixtures = $competition->getNextFixtures(4);
        foreach ($betsTable as $betRow) {
            $color = ($positionBefore==$betRow["position"]) ? "grey":"black";
            $size = ($betRow["position"]<=5) ? "big":"normal";
            //$verified = ($betRow["paid"]) ? "<img src='".get_template_directory_uri()."/images/icons/verified.png' class='verified-".$size."' title='".__("Pago verificado","enroporra")."' />":"";
            //$verified = ($betRow["bet"]->isPlayoffFulfilled())  ? "<img src='".get_template_directory_uri()."/images/icons/verified.png' class='verified-".$size."' title='".__("Segunda fase completada","enroporra")."' />":"";
            $verified = "";

            $classTr = (in_array($betRow["bet"]->getId(),$userBets)) ? "my-bet" : ( (in_array($betRow["bet"]->getId(),$friendsBets)) ? "my-friend" : "");
            $playerGoals = $competition->getGoalsByPlayer($betRow["bet"]->getPlayer());
            $playerGoalsString = ($playerGoals) ? " (".$playerGoals.")" : "";
	        $verifiedPlayer = (EP_Player::isPlayer($betRow["bet"]->getPlayer()) && !is_null($competition->getTopScorers()) && !empty($competition->getTopScorers()) && EP_Player::inArrayPlayers($betRow["bet"]->getPlayer(),$competition->getTopScorers())) ? verifiedTickHTML(__('Pichichi de la competición','enroporra'),$size) : "";
            echo "<tr class='".$classTr."'><td><div class='".$size."-text black-link'><b class='".$color."-text'>".$betRow["position"]."</b> <a href='".$betRow["bet"]->getUrl()."'>".$betRow["bet"]->getName()."</a>".$verified." <span class='points-text'>".$betRow["points"]."</span></div></td>";
            echo "<td class='hide-mobile' style='padding-left:20px'>".$betRow["bet"]->getPlayer()->getName().$playerGoalsString.$verifiedPlayer."</td>";
            if ($competition->getStage()>=EP_Competition::PLAYOFF_PLAYING) {
	            $verifiedReferee = (!is_null($betRow["bet"]->getReferee()) && !is_null($competition->getReferee()) && $betRow["bet"]->getReferee()->getId()==$competition->getReferee()->getId()) ? verifiedTickHTML(__('Árbitro de la final','enroporra'),$size) : "";
	            $refereeName = (is_null($betRow["bet"]->getReferee())) ? "" : $betRow["bet"]->getReferee()->getName();
                echo "<td class='hide-mobile' style='padding-left:20px'>".$refereeName.$verifiedReferee."</td>";
            }
            echo "<td class='hide-mobile' style='padding-left:20px'>";

            foreach ($nextFixtures as $nextFixture) {
                /** @var EP_Fixture $nextFixture */
                if ($nextFixture->getTournament()!="groups" && $competition->getStage()<EP_Competition::PLAYOFF_PLAYING && !$betRow["bet"]->getOwner()->isViewing())
                    continue;
                $betNext = $betRow["bet"]->getFixtureBet($nextFixture->getFixtureNumber());
                if (empty($betNext)) continue;
                echo $betNext["t1"]->getFlagHTML(20)." ".$betNext["s1"]."-".$betNext["s2"]." ".$betNext["t2"]->getFlagHTML(20)."&nbsp;&nbsp;&nbsp;";
            }

            echo "</td>";
            echo "</tr><tr><td colspan=4><hr></td></tr>";

            $positionBefore=$betRow["position"];
        }
        ?>
        </tbody>
    </table>
    <?php
}
