<?php
/*
Hall of Fame Webgame Code
Copyright (C) TEKITO PROJECT for JUICE ( http://tekito.kanichat.com/ )
Remaked by https://github.com/remileon/hof

Code fixed by Kustale ( jinsu8527@gmail.com )

한국어 버전 by Kustale, FE
*/

function LoadJobData($no) {
	switch($no) {
		case "100":
$job	= array(
"name_male"		=> "전사",
"name_female"	=> "전사",
"img_male"		=> "mon_079.gif",
"img_female"	=> "mon_080r.gif",
"equip"			=> array("검","양손검","방패","갑","의류","장포","아이템"),
"coe"			=> array(3, 0.5),
"change"		=> array(101,102,103),
); break;
		case "101":
$job	= array(
"name_male"		=> "왕실 경비대",
"name_female"	=> "왕실 경비대",
"img_male"		=> "mon_199r.gif",
"img_female"	=> "mon_234r.gif",
"equip"			=> array("검","양손검","방패","갑","의류","장포","아이템"),
"coe"			=> array(4, 0.7),
"change"		=> array(111,112,113),
); break;
		case "111":
$job	= array(
"name_male"		=> "왕실 십자군",
"name_female"	=> "왕실 십자군",
"img_male"		=> "mon_111ma.gif",
"img_female"	=> "mon_111fe.gif",
"equip"			=> array("검","방패","갑","의류","장포","아이템"),
"coe"			=> array(6, 1),
); break;
		case "112":
$job	= array(
"name_male"		=> "왕실 기사",
"name_female"	=> "왕실 기사",
"img_male"		=> "mon_112ma.gif",
"img_female"	=> "mon_112fe.gif",
"equip"			=> array("검","양손검","방패","갑","의류","장포","아이템"),
"coe"			=> array(5, 1.5),
); break;
		case "102":
$job	= array(
"name_male"		=> "광전사",
"name_female"	=> "광전사",
"img_male"		=> "mon_100r.gif",
"img_female"	=> "mon_012.gif",
"equip"			=> array("검","양손검","방패","의류","장포","아이템"),
"coe"			=> array(5.0, 0.2),
"change"		=> array(121,122,123),
); break;
		case "121":
$job	= array(
"name_male"		=> "피의 악마",
"name_female"	=> "피의 악마",
"img_male"		=> "mon_121ma.gif",
"img_female"	=> "mon_121fe.gif",
"equip"			=> array("검","양손검","방패","의류","장포","아이템"),
"coe"			=> array(7, 0.5),
); break;
		case "122":
$job	= array(
"name_male"		=> "피에 굶주린 검사",
"name_female"	=> "피에 굶주린 기사",
"img_male"		=> "mon_122ma.gif",
"img_female"	=> "mon_122fe.gif",
"equip"			=> array("검","양손검","방패","갑","의류","장포","아이템"),
"coe"			=> array(6.5, 0.5),
); break;
		case "103":
$job	= array(
"name_male"		=> "마녀사냥",
"name_female"	=> "마녀사냥",
"img_male"		=> "mon_150.gif",
"img_female"	=> "mon_234.gif",
"equip"			=> array("검","단검","방패","갑","의류","장포","아이템"),
"coe"			=> array(3.7, 1),
"change"		=> array(131,132,133),
); break;
		case "131":
$job	= array(
"name_male"		=> "마법 검사",
"name_female"	=> "마법 검사",
"img_male"		=> "mon_131ma.gif",
"img_female"	=> "mon_131fe.gif",
"equip"			=> array("검","책","단검","방패","의류","장포","아이템"),
"coe"			=> array(4, 2),
); break;
		case "132":
$job	= array(
"name_male"		=> "블레이드 댄서",
"name_female"	=> "블레이드 댄서",
"img_male"		=> "mon_132ma.gif",
"img_female"	=> "mon_132fe.gif",
"equip"			=> array("검","양손검","단검","방패","갑","의류","장포","아이템"),
"coe"			=> array(4.5, 1.5),
); break;
		case "200":
$job	= array(
"name_male"		=> "마법사",
"name_female"	=> "마법사",
"img_male"		=> "mon_106.gif",
"img_female"	=> "mon_018.gif",
"equip"			=> array("마법의 지팡이","막대","책","의류","장포","아이템"),
"coe"			=> array(1.5, 1),
"change"		=> array(201,202,203),
); break;
		case "201":
$job	= array(
"name_male"		=> "요술 장이",
"name_female"	=> "요술 장이",
"img_male"		=> "mon_196z.gif",
"img_female"	=> "mon_246r.gif",
"equip"			=> array("마법의 지팡이","막대","책","의류","장포","아이템"),
"coe"			=> array(2.1, 2),
"change"		=> array(211,212,213),
); break;
		case "211":
$job	= array(
"name_male"		=> "대마법사",
"name_female"	=> "대마법사",
"img_male"		=> "mon_211ma.gif",
"img_female"	=> "mon_211fe.gif",
"equip"			=> array("마법의 지팡이","막대","책","의류","장포","아이템"),
"coe"			=> array(2.5, 4),
); break;
		case "212":
$job	= array(
"name_male"		=> "리치",
"name_female"	=> "리치",
"img_male"		=> "mon_212ma.gif",
"img_female"	=> "mon_212fe.gif",
"equip"			=> array("마법의 지팡이","막대","책","의류","장포","아이템"),
"coe"			=> array(3, 3),
); break;
		case "202":
$job	= array(
"name_male"		=> "소환사",
"name_female"	=> "소환사",
"img_male"		=> "mon_196y.gif",
"img_female"	=> "mon_246z.gif",
"equip"			=> array("마법의 지팡이","막대","책","장포","아이템"),
"coe"			=> array(1.5, 2.5),
"change"		=> array(221,222,223),
); break;
		case "221":
$job	= array(
"name_male"		=> "드래곤 소환사",
"name_female"	=> "드래곤 소환사",
"img_male"		=> "mon_221ma.gif",
"img_female"	=> "mon_221fe.gif",
"equip"			=> array("마법의 지팡이","막대","책","의류","장포","아이템"),
"coe"			=> array(2, 4),
); break;
		case "222":
$job	= array(
"name_male"		=> "악마 지킴이",
"name_female"	=> "악마 지킴이",
"img_male"		=> "mon_222ma.gif",
"img_female"	=> "mon_222fe.gif",
"equip"			=> array("마법의 지팡이","책","의류","장포","아이템"),
"coe"			=> array(3, 3),
); break;
		case "203":
$job	= array(
"name_male"		=> "점쟁이",
"name_female"	=> "점쟁이",
"img_male"		=> "mon_196x.gif",
"img_female"	=> "mon_246y.gif",
"equip"			=> array("마법의 지팡이","막대","책","의류","장포","아이템"),
"coe"			=> array(2.1, 1.5),
"change"		=> array(231,232,233),
); break;
		case "231":
$job	= array(
"name_male"		=> "주술사",
"name_female"	=> "주술사",
"img_male"		=> "mon_231ma.gif",
"img_female"	=> "mon_231fe.gif",
"equip"			=> array("마법의 지팡이","막대","책","의류","장포","아이템"),
"coe"			=> array(3, 2.5),
); break;
		case "232":
$job	= array(
"name_male"		=> "악령",
"name_female"	=> "악령",
"img_male"		=> "mon_232ma.gif",
"img_female"	=> "mon_232fe.gif",
"equip"			=> array("마법의 지팡이","막대","책","의류","장포","아이템"),
"coe"			=> array(3.5, 2),
); break;
		case "300":
$job	= array(
"name_male"		=> "목사",
"name_female"	=> "여사제",
"img_male"		=> "mon_213.gif",
"img_female"	=> "mon_214.gif",
"equip"			=> array("마법의 지팡이","책","의류","장포","아이템"),
"coe"			=> array(2, 0.8),
"change"		=> array(301,302),
); break;
		case "301":
$job	= array(
"name_male"		=> "주교",
"name_female"	=> "주교",
"img_male"		=> "mon_213r.gif",
"img_female"	=> "mon_214r.gif",
"equip"			=> array("마법의 지팡이","책","의류","장포","아이템"),
"coe"			=> array(2.7, 1.4),
"change"		=> array(311,312,313),
); break;
		case "311":
$job	= array(
"name_male"		=> "선생",
"name_female"	=> "선생",
"img_male"		=> "mon_311ma.gif",
"img_female"	=> "mon_311fe.gif",
"equip"			=> array("마법의 지팡이","책","의류","장포","아이템"),
"coe"			=> array(3.5, 2.5),
); break;
		case "312":
$job	= array(
"name_male"		=> "신사",
"name_female"	=> "신사",
"img_male"		=> "mon_312ma.gif",
"img_female"	=> "mon_312fe.gif",
"equip"			=> array("마법의 지팡이","책","의류","장포","아이템"),
"coe"			=> array(3, 3),
); break;
		case "302":
$job	= array(
"name_male"		=> " 드루이드",
"name_female"	=> " 드루이드",
"img_male"		=> "mon_213rz.gif",
"img_female"	=> "mon_214rz.gif",
"equip"			=> array("마법의 지팡이","책","의류","장포","아이템"),
"coe"			=> array(2.5, 1.2),
"change"		=> array(321,322,323),
); break;
		case "321":
$job	= array(
"name_male"		=> "자연의 수호자",
"name_female"	=> "자연의 수호자",
"img_male"		=> "mon_321ma.gif",
"img_female"	=> "mon_321fe.gif",
"equip"			=> array("마법의 지팡이","책","의류","장포","아이템"),
"coe"			=> array(4, 1.5),
); break;
		case "322":
$job	= array(
"name_male"		=> "생명의 파괴자",
"name_female"	=> "생명의 파괴자",
"img_male"		=> "mon_322ma.gif",
"img_female"	=> "mon_322fe.gif",
"equip"			=> array("마법의 지팡이","단검","책","의류","장포","아이템"),
"coe"			=> array(3, 2.5),
); break;
		case "400":
$job	= array(
"name_male"		=> " 사냥꾼",
"name_female"	=> " 사냥꾼",
"img_male"		=> "mon_219rr.gif",
"img_female"	=> "mon_219r.gif",
"equip"			=> array("활","의류","장포","아이템"),
"coe"			=> array(2.2, 0.7),
"change"		=> array(401,402,403),
); break;
		case "401":
$job	= array(
"name_male"		=> "명사수",
"name_female"	=> "명사수",
"img_male"		=> "mon_076z.gif",
"img_female"	=> "mon_042z.gif",
"equip"			=> array("활","의류","장포","아이템"),
"coe"			=> array(3.0, 0.8),
"change"		=> array(411,412),
); break;
		case "411":
$job	= array(
"name_male"		=> "저격수",
"name_female"	=> "저격수",
"img_male"		=> "mon_411ma.gif",
"img_female"	=> "mon_411fe.gif",
"equip"			=> array("활","의류","장포","아이템"),
"coe"			=> array(4.5, 1),
); break;
		case "412":
$job	= array(
"name_male"		=> "사살자",
"name_female"	=> "사살자",
"img_male"		=> "mon_412ma.gif",
"img_female"	=> "mon_412fe.gif",
"equip"			=> array("활","의류","장포","아이템"),
"coe"			=> array(4, 1.5),
); break;
		case "402":
$job	= array(
"name_male"		=> "조련사",
"name_female"	=> "조련사",
"img_male"		=> "mon_216z.gif",
"img_female"	=> "mon_217z.gif",
"equip"			=> array("활","채찍","의류","장포","아이템"),
"coe"			=> array(3.2, 1.0),
"change"		=> array(421,422),
); break;
		case "421":
$job	= array(
"name_male"		=> "비스트 마스터",
"name_female"	=> "비스트 마스터",
"img_male"		=> "mon_421ma.gif",
"img_female"	=> "mon_421fe.gif",
"equip"			=> array("채찍","의류","장포","아이템"),
"coe"			=> array(4, 2),
); break;
		case "422":
$job	= array(
"name_male"		=> "도둑",
"name_female"	=> "도둑",
"img_male"		=> "mon_422ma.gif",
"img_female"	=> "mon_422fe.gif",
"equip"			=> array("단검","채찍","의류","장포","아이템"),
"coe"			=> array(3.5, 1.5),
); break;
		case "403":
$job	= array(
"name_male"		=> "자객",
"name_female"	=> "자객",
"img_male"		=> "mon_216y.gif",
"img_female"	=> "mon_217rz.gif",
"equip"			=> array("단검","활","갑","의류","아이템"),
"coe"			=> array(3.6, 0.7),
"change"		=> array(431,432),
); break;
		case "431":
$job	= array(
"name_male"		=> "암살자",
"name_female"	=> "암살자",
"img_male"		=> "mon_431ma.gif",
"img_female"	=> "mon_431fe.gif",
"equip"			=> array("단검","갑","의류","아이템"),
"coe"			=> array(4, 1.5),
); break;
		case "432":
$job	= array(
"name_male"		=> "바람둥이",
"name_female"	=> "바람둥이",
"img_male"		=> "mon_432ma.gif",
"img_female"	=> "mon_432fe.gif",
"equip"			=> array("단검","활","의류","장포","아이템"),
"coe"			=> array(4.5, 1),
); break;
	}
	return $job;
}
?>