<?php
/*
Hall of Fame Webgame Code
Copyright (C) TEKITO PROJECT for JUICE ( http://tekito.kanichat.com/ )
Remaked by https://github.com/remileon/hof

Code fixed by Kustale ( jinsu8527@gmail.com )

한국어 버전 by Kustale, FE
*/

function CanCreate2($user) {
	$create1	= array(1030,1127,1231,1232,1233,1234,1235,1306,1405,1506,1605,1724,1725,1726,1759,1760,1819,2027,2054,2112,2113,2114,2218,3016,3111,3415,3416,3417,5020,5114,5214,);
	$create2	= array_merge($create1,
	array(9021,9022,9023,9024,)
	);
	
	return $create2;
	
	
}
function HaveNeeds($item,$UserItem) {
	if(!$UserItem) return false;
	if(!$item["need"]) return false;
	foreach($item["need"] as $NeedNo => $Amount) {
		if($UserItem[$NeedNo] < $Amount)
			return false;
	}
	return true;
}

function ItemAbilityPossibility($type) {
	switch($type) {
		case "상태":
		case "바오판 주":
		case "쿠웨이":
		case "창편":
		case "정자오":
		case "자오":
		case "광":
		case "얀":
		case "니앙지판":
		case "판낭":
		case "하이낭":
		case "시안-판-낭":
		case "판자오":
		case "지안":
		case "낭":
		case "리":
		case "에르아이펜":
		case "슈 상태":
		case "판잔 상태":
		case "시웨이":
		case "구지안":
		case "위안펜쿤":
		case "바오주(시안)":
		case "바오 주(나)":
		case "페이":
		case "정-웨이-시-즈":
		case "징 주":
		case "콴신":
		case "시안저우":
		
			$low	= array(
			100,101,102,103,104,
			105,106,107,108,109,
			150,151,152,153,154,
			155,156,157,158,159,
			204,200,205,201,
			254,250,255,251,
			'H00','H01','H02',
			'HM0','HM1','HM2',
			'S00','S01',
			'SM0','SM1','SM2',
			'A00','A01','A02','A03','A04',
			);
			$high	= array(
			110,111,112,113,114,
			115,116,117,118,119,
			160,161,162,163,164,
			165,166,167,168,169,
			202,206,203,
			252,256,253,
			'H03','H04','H05',
			'HM3','HM4','HM5',
			'S02','S03',
			'SM3','SM4','SM5',
			'A05','A06','A07','A08','A09',
			);
			break;
		case "진":
		case "방창바이":
		case "뮤":
		case "Zhe":
		case "스주안":
		case "하이누어":
		case "쉬허":
		case "유미":
			$low	= array(
			300,301,
			350,351,
			'H00','H01','H02',
			'HM0','HM1','HM2',
			'S00','S01',
			'SM0','SM1','SM2',
			'A00','A01','A02','A03','A04',
			);
			$high	= array(
			302,303,304,
			352,353,354,
			'H03','H04','H05',
			'HM3','HM4','HM5',
			'S02','S03',
			'SM3','SM4','SM5',
			'A05','A06','A07','A08','A09',
			);
			break;
		
		case "Medal":
			$low	= array(
			202,206,203,
			252,256,253,
			);
			$high	= array(
			'HM5',
			'SM5',
			'A05','A09',
			304,354,
			);
			break;
	}
	return array($low,$high);
}
?>