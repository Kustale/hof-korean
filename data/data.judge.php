<?php
/*
Hall of Fame Webgame Code
Copyright (C) TEKITO PROJECT for JUICE ( http://tekito.kanichat.com/ )
Remaked by https://github.com/remileon/hof

Code fixed by Kustale ( jinsu8527@gmail.com )

한국어 버전 by Kustale, FE
*/

function DecideJudge($number,$My,$classBattle) {
	$sum = 0;
	$cnt = 0;
	if($My->team == TEAM_0) {
		$MyTeam	= $classBattle->team0;
		$EnemyTeam	= $classBattle->team1;
		$MyTeamMC	= $classBattle->team0_mc;
		$EnemyTeamMC	= $classBattle->team1_mc;
	} else {
		$MyTeam	= $classBattle->team1;
		$EnemyTeam	= $classBattle->team0;
		$MyTeamMC	= $classBattle->team1_mc;
		$EnemyTeamMC	= $classBattle->team0_mc;
	}
	$Judge		= $My->judge["$number"];
	$Quantity	= $My->quantity["$number"];
	switch($Judge) {
		case 1000:
			return true;
		case 1001:
			return false;
		case 1100:
			$hpp	= $My->HpPercent();
			if($Quantity <= $hpp) return true;
			break;
		case 1101:
			$hpp	= $My->HpPercent();
			if($hpp <= $Quantity) return true;
			break;
		case 1105:
			$hp		= $My->HP;
			if($Quantity <= $hp) return true;
			break;
		case 1106:
			$hp		= $My->HP;
			if($hp <= $Quantity) return true;
			break;
		case 1110:
			$mhp		= $My->MAXHP;
			if($Quantity <= $mhp) return true;
			break;
		case 1111:
			$mhp		= $My->MAXHP;
			if($mhp <= $Quantity) return true;
			break;
		case 1121:
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->HpPercent() <= $Quantity)
					return true;
			}
			break;
		case 1125:
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
					$sum	+= $char->HpPercent();
					$cnt++;
			}
			$ave	= $sum/$cnt;
			if($Quantity <= $ave) return true;
			break;
		case 1126:
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
					$sum	+= $char->HpPercent();
					$cnt++;
			}
			$ave	= $sum/$cnt;
			if($ave <= $Quantity) return true;
			break;
		case 1200:
			$spp	= $My->SpPercent();
			if($Quantity <= $spp) return true;
			break;
		case 1201:
			$spp	= $My->SpPercent();
			if($spp <= $Quantity) return true;
			break;
		case 1205:
			$sp		= $My->SP;
			if($Quantity <= $sp) return true;
			break;
		case 1206:
			$sp		= $My->SP;
			if($sp <= $Quantity) return true;
			break;
		case 1210:
			$msp		= $My->MAXSP;
			if($Quantity <= $msp) return true;
			break;
		case 1211:
			$msp		= $My->MAXSP;
			if($msp <= $Quantity) return true;
			break;
		case 1221:
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->MAXSP === 0) continue;
				if($char->SpPercent() <= $Quantity)
					return true;
			}
			break;
		case 1225:
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->MAXSP === 0) continue;					
					$sum	+= $char->SpPercent();
					$cnt++;
			}
			if(!$cnt)
				break;
			$ave	= $sum/$cnt;
			if($Quantity <= $ave) return true;
			break;
		case 1226:
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->MAXSP === 0) continue;
					$sum	+= $char->SpPercent();
					$cnt++;
			}
			if(!$cnt)
				break;
			$ave	= $sum/$cnt;
			if($ave <= $Quantity) return true;
			break;
		case 1300:
			break;
		case 1301:
			break;
		case 1310:
			break;
		case 1311:
			break;
		case 1320:
			break;
		case 1321:
			break;
		case 1330:
			break;
		case 1331:
			break;
		case 1340:
			break;
		case 1341:
			break;
		case 1350:
			break;
		case 1351:
			break;
		case 1360:
			break;
		case 1361:
			break;
		case 1370:
			break;
		case 1371:
			break;
		case 1380:
			break;
		case 1381:
			break;
		case 1400:
			foreach($MyTeam as $char) {
				if($char->STATE !== DEAD)
					$alive++;
			}
			if($Quantity <= $alive) return true;
			break;
		case 1401:
			foreach($MyTeam as $char) {
				if($char->STATE !== DEAD)
					$alive++;
			}
			if($alive <= $Quantity) return true;
			break;
		case 1405:
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD)
					$dead++;
			}
			if($Quantity <= $dead) return true;
			break;
		case 1406:
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD)
					$dead++;
			}
			if($dead <= $Quantity) return true;
			break;
		case 1410:
			$front_alive	= 0;
			foreach($MyTeam as $char) {
				if($char->STATE !== DEAD && $char->position == FRONT)
					$front_alive++;
			}
			if($Quantity <= $front_alive) return true;
			break;
		case 1450:
			foreach($EnemyTeam as $char) {
				if($char->STATE !== DEAD)
					$alive++;
			}
			if($Quantity <= $alive) return true;
			break;
		case 1451:
			foreach($EnemyTeam as $char) {
				if($char->STATE !== DEAD)
					$alive++;
			}
			if($alive <= $Quantity) return true;
			break;
		case 1455:
			foreach($EnemyTeam as $char) {
				if($char->STATE === DEAD)
					$dead++;
			}
			if($Quantity <= $dead) return true;
			break;
		case 1456:
			foreach($EnemyTeam as $char) {
				if($char->STATE === DEAD)
					$dead++;
			}
			if($dead <= $Quantity) return true;
			break;
		case 1500:
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->expect_type === CHARGE)
					$charge++;
			}
			if($Quantity <= $charge) return true;
			break;
		case 1501:
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->expect_type === CHARGE)
					$charge++;
			}
			if($charge <= $Quantity) return true;
			break;
		case 1505:
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->expect_type === CAST)
					$cast++;
			}
			if($Quantity <= $cast) return true;
			break;
		case 1506:
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->expect_type === CHARGE)
					$cast++;
			}
			if($cast <= $Quantity) return true;
			break;
		case 1510: 
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->expect_type === CAST || $char->expect_type === CHARGE)
					$expect++;
			}
			if($Quantity <= $expect) return true;
			break;
		case 1511: 
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->expect_type === CAST || $char->expect_type === CHARGE)
					$expect++;
			}
			if($expect <= $Quantity) return true;
			break;
		case 1550:
			foreach($EnemyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->expect_type === CHARGE)
					$charge++;
			}
			if($Quantity <= $charge) return true;
			break;
		case 1551:
			foreach($EnemyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->expect_type === CHARGE)
					$charge++;
			}
			if($charge <= $Quantity) return true;
			break;
		case 1555:
			foreach($EnemyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->expect_type === CAST)
					$cast++;
			}
			if($Quantity <= $cast) return true;
			break;
		case 1556:
			foreach($EnemyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->expect_type === CAST)
					$cast++;
			}
			if($cast <= $Quantity) return true;
			break;
		case 1560:
			foreach($EnemyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->expect_type === CAST || $char->expect_type === CHARGE)
					$expect++;
			}
			if($Quantity <= $expect) return true;
			break;
		case 1561:
			foreach($EnemyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->expect_type === CAST || $char->expect_type === CHARGE)
					$expect++;
			}
			if($expect <= $Quantity) return true;
			break;
		case 1600:
			if($My->STATE === POISON) return true;
			break;
		case 1610:
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->STATE === POISON)
					$poison++;
			}
			if($Quantity <= $poison) return true;
			break;
		case 1611:
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->STATE === POISON)
					$poison++;
			}
			if($poison <= $Quantity) return true;
			break;
		case 1612:
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
				$alive++;
				if($char->STATE === POISON)
					$poison++;
			}
			if(!$alive) return false;
			$Rate	= ($poison/$alive) * 100;
			if($Quantity <= $Rate) return true;
			break;
		case 1613:
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
				$alive++;
				if($char->STATE === POISON)
					$poison++;
			}
			if(!$alive) return false;
			$Rate	= ($poison/$alive) * 100;
			if($Rate <= $Quantity) return true;
			break;
		case 1615:
			foreach($EnemyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->STATE === POISON)
					$poison++;
			}
			if($Quantity <= $poison) return true;
			break;
		case 1616:
			foreach($EnemyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->STATE === POISON)
					$poison++;
			}
			if($poison <= $Quantity) return true;
			break;
		case 1612:
			foreach($EnemyTeam as $char) {
				if($char->STATE === DEAD) continue;
				$alive++;
				if($char->STATE === POISON)
					$poison++;
			}
			if(!$alive) return false;
			$Rate	= ($poison/$alive) * 100;
			if($Quantity <= $Rate) return true;
			break;
		case 1613:
			foreach($EnemyTeam as $char) {
				if($char->STATE === DEAD) continue;
				$alive++;
				if($char->STATE === POISON)
					$poison++;
			}
			if(!$alive) return false;
			$Rate	= ($poison/$alive) * 100;
			if($Rate <= $Quantity) return true;
			break;
		case 1700:
			if($My->POSITION == FRONT) return true;
			break;
		case 1701:
			if($My->POSITION == BACK) return true;
			break;
		case 1710:
			$front	= 0;
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->POSITION == FRONT)
					$front++;
			}
			if($Quantity <= $front) return true;
			break;
		case 1711:
			$front	= 0;
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->POSITION == FRONT)
					$front++;
			}
			if($front <= $Quantity) return true;
			break;
		case 1712:
			$front	= 0;
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->POSITION == FRONT)
					$front++;
			}
			if($front == $Quantity) return true;
			break;
		case 1715:
			$back	= 0;
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->POSITION == BACK)
					$back++;
			}
			if($Quantity <= $back) return true;
			break;
		case 1716:
			$back	= 0;
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->POSITION == BACK)
					$back++;
			}
			if($back <= $Quantity) return true;
			break;
		case 1717:
			$back	= 0;
			foreach($MyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->POSITION == BACK)
					$back++;
			}
			if($back == $Quantity) return true;
			break;
		case 1750:
			$front	= 0;
			foreach($EnemyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->POSITION == FRONT)
					$front++;
			}
			if($Quantity <= $front) return true;
			break;
		case 1751:
			$front	= 0;
			foreach($EnemyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->POSITION == FRONT)
					$front++;
			}
			if($front <= $Quantity) return true;
			break;
		case 1752:
			$front	= 0;
			foreach($EnemyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->POSITION == FRONT)
					$front++;
			}
			if($Quantity == $front) return true;
			break;
		case 1755:
			$back	= 0;
			foreach($EnemyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->POSITION == BACK)
					$back++;
			}
			if($Quantity <= $back) return true;
			break;
		case 1756:
			$back	= 0;
			foreach($EnemyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->POSITION == BACK)
					$back++;
			}
			if($back <= $Quantity) return true;
			break;
		case 1757:
			$back	= 0;
			foreach($EnemyTeam as $char) {
				if($char->STATE === DEAD) continue;
				if($char->POSITION == BACK)
					$back++;
			}
			if($Quantity == $back) return true;
			break;
		case 1800:
			$summon	= 0;
			foreach($MyTeam as $char) {
				if($char->summon)
					$summon++;
			}
			if($Quantity <= $summon) return true;
			break;
		case 1801:
			$summon	= 0;
			foreach($MyTeam as $char) {
				if($char->summon)
					$summon++;
			}
			if($summon <= $Quantity) return true;
			break;

		case 1805:
			$summon	= 0;
			foreach($MyTeam as $char) {
				if($char->summon)
					$summon++;
			}
			if($summon == $Quantity) return true;
			break;
		case 1820:
			$summon	= 0;
			foreach($EnemyTeam as $char) {
				if($char->summon)
					$summon++;
			}
			if($Quantity <= $summon) return true;
			break;
		case 1821:
			$summon	= 0;
			foreach($EnemyTeam as $char) {
				if($char->summon)
					$summon++;
			}
			if($summon <= $Quantity) return true;
			break;
		case 1825:
			$summon	= 0;
			foreach($EnemyTeam as $char) {
				if($char->summon)
					$summon++;
			}
			if($summon == $Quantity) return true;
			break;
		case 1840:
			if($Quantity <= $MyTeamMC)
				return true;
			break;
		case 1841:
			if($MyTeamMC <= $Quantity)
				return true;
			break;
		case 1845:
			if($Quantity == $MyTeamMC)
				return true;
			break;
		case 1850:
			if($Quantity <= $EnemyTeamMC)
				return true;
			break;
		case 1851:
			if($EnemyTeamMC <= $Quantity)
				return true;
			break;
		case 1855:
			if($Quantity == $EnemyTeamMC)
				return true;
			break;
		case 1900:
			if(($Quantity-1) <= $My->ActCount) return true;
			break;
		case 1901:
			if($My->ActCount <= ($Quantity-1)) return true;
			break;
		case 1902:
			if($My->ActCount == ($Quantity-1)) return true;
			break;
		case 1920:
			if($My->JdgCount[$number] < $Quantity) return true;
			break;
		case 1940:
			$prob	= mt_rand(1,100);
			if($prob <= $Quantity) return true;
			break;
		case 9000:
			foreach($EnemyTeam as $char) {
				if($Quantity <= $char->level)
					return true;
			}
			break;
	}
}

function &FuncTeamHpSpRate(&$TeamHpRate,$NO) {
	foreach($TeamHpRate as $key => $Rate) {
		if($Rate <= $NO)
			$target[]	= &$MyTeam[$key];
	}
	return $target ? $target : false;
}
?>