<?php
/*
Hall of Fame Webgame Code
Copyright (C) TEKITO PROJECT for JUICE ( http://tekito.kanichat.com/ )
Remaked by https://github.com/remileon/hof

Code fixed by Kustale ( jinsu8527@gmail.com )

한국어 버전 by Kustale, FE

php 7부터 $_GET $_POST $_COOKIE 같은것들이 값이 없으면 array로 잡아버리는 개뚹같은 현상...
*/


function __GET($name){
	return (isset($_GET[$name]) ? $_GET[$name] : NULL);
}

function __POST($name){
	return (isset($_POST[$name]) ? $_POST[$name] : NULL);
}

function __SERVER($name){
	return (isset($_SERVER[$name]) ? $_SERVER[$name] : NULL);
}

function __COOKIE($name){
	return (isset($_COOKIE[$name]) ? $_COOKIE[$name] : NULL);
}

function __SESSION($name){
	return (isset($_SESSION[$name]) ? $_SESSION[$name] : NULL);
}

function __count($value){
	if(isset($value))
		return count($value);
}

	function ShopList() {
		return array(
		1002,1003,1004,1100,1101,1200,
		1700,1701,1702,1703,1800,1801,2000,2001,
		3000,3001,3002,3100,3101,5000,5001,5002,5003,
		5100,5101,5102,5103,5200,5201,5202,5203,
		5500,5501,
		7000,7001,7500,
		7510,7511,7512,7513,7520,
		8000,8009,8012,
		);
	}

	function CanExhibitType() {
		return array(
		"Sword"	=> "1",
		"TwoHandSword"	=> "1",
		"Dagger"	=> "1",
		"Wand"	=> "1",
		"Staff"	=> "1",
		"Bow"	=> "1",
		"Whip"	=> "1",
		"Shield"	=> "1",
		"Book"	=> "1",
		"Armor"	=> "1",
		"Cloth"	=> "1",
		"Robe"	=> "1",
		"Item"	=> "1",
		"Material"	=> "1",
		);
	}

	function CanRefineType() {
		return array(
		"Sword","TwoHandSword","Dagger",
		"Wand","Staff","Bow",
		"Whip",
		"Shield","Book",
		"Armor","Cloth","Robe",
		);
	}

	function DeleteAbandonAccount() {
		$list	= glob(USER."*");
		$now	= time();
		foreach($list as $file) {
			$fileh = pathinfo($file);
			if($fileh["extension"] == "php") continue;
			if(!is_dir($file)) continue;
			$UserID	= substr($file,strrpos($file,"/")+1);
			$user	= new user($UserID,true);
			if($user->IsAbandoned())
			{
				if(!isset($Ranking))
				{
					include_once(CLASS_RANKING);
					$Ranking	= new Ranking();
					$RankChange	= false;
				}
				if( $Ranking->DeleteRank($UserID) ) {
					$RankChange	= true;
				}
				RecordManage(date("Y M d G:i:s",$now).": user ".$user->id." deleted.");
				$user->DeleteUser(false);
			}
				else
			{
				$user->fpCloseAll();
				unset($user);
			}
			
		}
		
		if($RankChange === true)
			$Ranking->SaveRanking();
		else if($RankChange === false)
			$Ranking->fpclose();
	}

	function RegularControl($value=null) {
		if(19 <= date("H") || date("H") <= 1)
			 return false;
		$now	= time();
		$fp		= FileLock(CTRL_TIME_FILE,true);
		if(!$fp)
			return false;
		$ctrltime	= trim(fgets($fp, 1024));
		if($now < $ctrltime)
		{
			fclose($fp);
			unset($fp);
			return false;
		}
		RecordManage(date("Y M d G:i:s",$now).": auto regular control by {$value}.");
		DeleteAbandonAccount();
		WriteFileFP($fp,$now + CONTROL_PERIOD);
		fclose($fp);
		unset($fp);
	}

	function is_registered($id) {
		if($registered = @file(REGISTER)):
			if(array_search($id."\n",$registered)!==false && !preg_match("/[\.\/]+/",$id) )
				return true;
			else
				return false;
		endif;
	}

	function FileLock($file,$noExit=false) {
		if(file_exists($file)){
			$fp	= @fopen($file,"r+") or die("Error!");
			if(!$fp)
				return false;
			$i=0;
			do{
				if(flock($fp, LOCK_EX | LOCK_NB)) {
					stream_set_write_buffer($fp, 0);
					return $fp;
				} else {
					usleep(10000);
					$i++;
				}
			}while($i<5);

			return $fp;
		} else
			return false;
	}

	function WriteFileFP($fp,$text,$check=false) {
		if($text){
			if(!$check && !trim($text))
				return false;
			ftruncate($fp,0);
			rewind($fp);
			fputs($fp,$text);
		}
	}

	function WriteFile($file,$text,$check=false) {
		if(!$check && !$text)
			return false;
		$fp	= fopen($file,"w+");
		flock($fp,LOCK_EX);
		fputs($fp,$text);
	}


	function ParseFileFP($fp) {

		if(isset($fp)){
			while( !feof($fp) ) {
				$str	= fgets($fp);
				$str	= trim($str);
				if(!$str) continue;
				$pos	= strpos($str,"=");
				if($pos === false)
					continue;
				$key	= substr($str,0,$pos);
				$val	= substr($str,++$pos);
				$data[$key]	= trim($val);
			}
			if($data)
				return $data;
			else
				return false;
		} else
			return false;
	}

	function ParseFile($file) {
		if(file_exists($file)){
			$fp		= fopen($file,"r+");
			flock($fp, LOCK_EX | LOCK_NB);
			while( !feof($fp) ) {
				$str	= fgets($fp);
				$str	= trim($str);
				if(!$str) continue;
				$pos	= strpos($str,"=");
				if($pos === false)
					continue;
				$key	= substr($str,0,$pos);
				$val	= substr($str,++$pos);
				$data[$key]	= trim($val);
			}
			if($data)
				return $data;
			else
				return false;
		} else
			return false;
	}

	function UserAmount() {
		static $amount;

		if($amount) {
			return $amount;
		} else {
			$amount	= __count(glob(USER."*"));
			return $amount - 1; //index.php 제외
		}
	}

	function JudgeList(){

		if(JUDGE_LIST_AUTO_LOAD) {
			for($i=1000; $i<2500; $i++) {
				if( LoadJudgeData($i) !== false)
					$list[]=$i;
			}
			return $list;
		} else {
		return array(
		1000, 1001, 1099, 1100, 1101,
		1105, 1106, 1110, 1111, 1121,
		1125, 1126, 1199, 1200, 1201,
		1205, 1206, 1210, 1211, 1221,
		1225, 1226, 1399, 1400, 1401,
		1405, 1406, 1410, 1449, 1450,
		1451, 1455, 1456, 1499, 1500,
		1501, 1505, 1506, 1510, 1511,
		1549, 1550, 1551, 1555, 1556,
		1560, 1561, 1599, 1600, 1610,
		1611, 1612, 1613, 
		1614, 1615, 1616, 1617, 1618,
		1699,
		1700, 1701, 1710, 1711, 1712,
		1715, 1716, 1717, 1749, 1750,
		1751, 1752, 1755, 1756, 1757,
		1799, 1800, 1801, 1805, 1819,
		1820, 1821, 1825, 1839, 1840,
		1841, 1845, 1849, 1850, 1851,
		1855, 1899, 1900, 1901, 1902,
		1919, 1920, 1939, 1940, 
		); 
		}

	}


	function MoneyFormat($number) {
		return '$&nbsp;'.number_format($number);
	}

	function ItemSellPrice($item) {
		$price	= (isset($item["sell"]) ? $item["sell"] : round($item["buy"]*SELLING_PRICE));
		return $price;
	}


function ShowLogList() {
	print("<div style=\"margin:15px\">\n");

	print("<a href=\"?log\" class=\"a0\">전부</a> ");
	print("<a href=\"?clog\">보통</a> ");
	print("<a href=\"?ulog\">보스전</a> ");
	print("<a href=\"?rlog\">랭킹전</a>");

	print("<h4>최근의 전투 - <a href=\"?clog\">전부 표시</a>(Recent Battles)</h4>\n");
	$log	= @glob(LOG_BATTLE_NORMAL."*");
	$limit = 0;
	foreach(array_reverse($log) as $file) {
		$fileh = pathinfo($file);
		if($fileh["extension"] == "php") continue;
		BattleLogDetail($file);
		$limit++;
		if(30 <= $limit) {
			break;
		}
	}
	$limit	= 0;
	print("<h4>보스전 - <a href=\"?ulog\">전부 표시</a>(Union Battle Log)</h4>\n");
	$log	= @glob(LOG_BATTLE_UNION."*");
	foreach(array_reverse($log) as $file) {
		$fileh = pathinfo($file);
		if($fileh["extension"] == "php") continue;
		BattleLogDetail($file,"UNION");
		$limit++;
		if(30 <= $limit) {
			break;
		}
	}
	$limit	= 0;
	print("<h4>랭킹전 - <a href=\"?rlog\">전부 표시</a>(Rank Battle Log)</h4>\n");
	$log	= @glob(LOG_BATTLE_RANK."*");
	foreach(array_reverse($log) as $file) {
		$fileh = pathinfo($file);
		if($fileh["extension"] == "php") continue;
		BattleLogDetail($file,"RANK");
		$limit++;
		if(30 <= $limit) {
			break;
		}
	}

	print("</div>\n");
}

function LogShowCommon() {
	print("<div style=\"margin:15px\">\n");
	
	print("<a href=\"?log\">전부</a> ");
	print("<a href=\"?clog\" class=\"a0\">보통</a> ");
	print("<a href=\"?ulog\">보스전</a> ");
	print("<a href=\"?rlog\">랭킹전</a>");
	print("<h4>최근 전투 - 전체 기록(Recent Battles)</h4>\n");
	$log	= @glob(LOG_BATTLE_NORMAL."*");
	foreach(array_reverse($log) as $file) {
		$fileh = pathinfo($file);
		if($fileh["extension"] == "php") continue;
		BattleLogDetail($file);
	}
	print("</div>\n");
}

function LogShowUnion() {
	print("<div style=\"margin:15px\">\n");

	print("<a href=\"?log\">전부</a> ");
	print("<a href=\"?clog\">보통</a> ");
	print("<a href=\"?ulog\" class=\"a0\">보스전</a> ");
	print("<a href=\"?rlog\">랭킹전</a>");
	print("<h4>보스전 - 전체 기록(Union Battle Log)</h4>\n");
	$log	= @glob(LOG_BATTLE_UNION."*");
	foreach(array_reverse($log) as $file) {
		$fileh = pathinfo($file);
		if($fileh["extension"] == "php") continue;
		BattleLogDetail($file,"UNION");
	}
	print("</div>\n");
}

function LogShowRanking() {
	print("<div style=\"margin:15px\">\n");

	print("<a href=\"?log\">전부</a> ");
	print("<a href=\"?clog\">보통</a> ");
	print("<a href=\"?ulog\">보스전</a> ");
	print("<a href=\"?rlog\" class=\"a0\">랭킹전</a>");
	print("<h4>랭킹전 - 전체기록(Rank Battle Log)</h4>\n");
	$log	= @glob(LOG_BATTLE_RANK."*");
	foreach(array_reverse($log) as $file) {
		$fileh = pathinfo($file);
		if($fileh["extension"] == "php") continue;
		BattleLogDetail($file,"RANK");
	}
	print("</div>\n");
}

function BattleLogDetail($log,$type=false) {
	$fp	= fopen($log,"r");

	$time	= fgets($fp);
	$team	= explode("<>",fgets($fp));
	$number	= explode("<>",trim(fgets($fp)));
	$avelv	= explode("<>",trim(fgets($fp)));
	$win	= trim(fgets($fp));
	$act	= trim(fgets($fp));
	fclose($fp);

	$date	= date("m/d H:i:s",substr($time,0,10));
	if($type == "RANK")
		print("[ <a href=\"?rlog={$time}\">{$date}</a> ]&nbsp;\n");
	else if($type == "UNION")
		print("[ <a href=\"?ulog={$time}\">{$date}</a> ]&nbsp;\n");
	else
		print("[ <a href=\"?log={$time}\">{$date}</a> ]&nbsp;\n");
	print("<span class=\"bold\">$act</span>turns&nbsp;\n");
	if($win === "0")
		print("<span class=\"recover\">{$team[0]}</span>");
	else if($win === "1")
		print("<span class=\"dmg\">{$team[0]}</span>");
	else
		print("{$team[0]}");

	print("({$number[0]}:{$avelv[0]})");

	print(" vs ");

	if($win === "0")
		print("<span class=\"dmg\">{$team[1]}</span>");
	else if($win === "1")
		print("<span class=\"recover\">{$team[1]}</span>");
	else
		print("{$team[1]}");

	print("({$number[1]}:{$avelv[1]})<br />");
}

function ShowBattleLog($no,$type=false) {
	if($type == "RANK")
		$file	= LOG_BATTLE_RANK.$no.".dat";
	else if($type == "UNION")
		$file	= LOG_BATTLE_UNION.$no.".dat";
	else
		$file	= LOG_BATTLE_NORMAL.$no.".dat";
	if(!file_exists($file)) {
		print("log doesnt exists");
		return false;
	}

	$log	= file($file);
	$row	= 6;
	$time	= substr($log[0],0,10);

	print('<div style="padding:15px 0;width:100%;text-align:center" class="break">');
	print("<h2>battle log*</h2>");
	print("\nthis battle starts at<br />");
	print(date("m/d H:i:s",substr($time,0,10)));
	print("</div>\n");

	while(isset($log["$row"])) {
		print($log["$row"]);
		$row++;
	}
}

	function ShowSkillDetail($skill,$radio=false) {
		if(!$skill) return false;
		
		if($radio)
			print('<input type="radio" name="newskill" value="'.$skill["no"].'" class="vcent" />');

		print('<img src="'.IMG_ICON.$skill["img"].'" class="vcent">');
		print("{$skill['name']}");

		if($radio)
			print(" / <span class=\"bold\">{$skill['learn']}</span>pt");

		if(isset($skill['target'][0]) == "all")
			print(" / <span class=\"charge\">{$skill['target'][0]}</span>");
		else if(isset($skill['target'][0]) == "enemy")
			print(" / <span class=\"dmg\">{$skill['target'][0]}</span>");
		else if(isset($skill['target'][0]) == "friend")
			print(" / <span class=\"recover\">{$skill['target'][0]}</span>");
		else if(isset($skill['target'][0]) == "self")
			print(" / <span class=\"support\">{$skill['target'][0]}</span>");
		else if(isset($skill['target'][0]))
			print(" / {$skill['target'][0]}");

		if(isset($skill['target'][1]) == "all")
			print(" - <span class=\"charge\">{$skill['target'][1]}</span>");
		else if(isset($skill['target'][1]) == "individual")
			print(" - <span class=\"recover\">{$skill['target'][1]}</span>");
		else if(isset($skill['target'][1]) == "multi")
			print(" - <span class=\"spdmg\">{$skill['target'][1]}</span>");
		else if(isset($skill['target'][1]))
			print(" - {$skill['target'][1]}");

		if(isset($skill["sacrifice"]))
			print(" / <span class=\"dmg\">Sacrifice:{$skill['sacrifice']}%</span>");
		if(isset($skill["sp"]))
			print(" / <span class=\"support\">{$skill['sp']}sp</span>");
		if(isset($skill["MagicCircleDeleteTeam"]))
			print(" / <span class=\"support\">MagicCircle x".$skill["MagicCircleDeleteTeam"]."</span>");
		if(isset($skill["pow"])) {
			print(" / <span class=\"".(isset($skill["support"])?"recover":"dmg")."\">{$skill['pow']}%</span>x");
			if(isset($skill["target"])) print(( $skill["target"][2] ? $skill["target"][2] : "1" ) );
		}
		if((isset($skill["type"]) ? $skill["type"] : 0) == 1)
			print(" / <span class=\"spdmg\">Magic</span>");
		if(isset($skill["quick"]))
			print(" / <span class=\"charge\">Quick</span>");
		if(isset($skill["invalid"]))
			print(" / <span class=\"charge\">invalid</span>");
		if((isset($skill["priority"]) ? $skill["priority"] : "") == "Back")
			print(" / <span class=\"support\">BackAttack</span>");
		if(isset($skill["CurePoison"]))
			print(" / <span class=\"support\">CurePoison</span>");

		if(isset($skill["delay"]))
			print(" / <span class=\"support\">Delay-".$skill['delay']."%</span>");

		if(isset($skill["UpMAXHP"]))
			print(" / <span class=\"charge\">MaxHP+".$skill['UpMAXHP']."%</span>");
		if(isset($skill["UpMAXSP"]))
			print(" / <span class=\"charge\">MaxSP+".$skill['UpMAXSP']."%</span>");
		if(isset($skill["UpSTR"]))
			print(" / <span class=\"charge\">Str+".$skill['UpSTR']."%</span>");
		if(isset($skill["UpINT"]))
			print(" / <span class=\"charge\">Int+".$skill['UpINT']."%</span>");
		if(isset($skill["UpDEX"]))
			print(" / <span class=\"charge\">Dex+".$skill['UpDEX']."%</span>");
		if(isset($skill["UpSPD"]))
			print(" / <span class=\"charge\">Spd+".$skill['UpSPD']."%</span>");
		if(isset($skill["UpLUK"]))
			print(" / <span class=\"charge\">Luk+".$skill['UpLUK']."%</span>");
		if(isset($skill["UpATK"]))
			print(" / <span class=\"charge\">Atk+".$skill['UpATK']."%</span>");
		if(isset($skill["UpMATK"]))
			print(" / <span class=\"charge\">Matk+".$skill['UpMATK']."%</span>");
		if(isset($skill["UpDEF"]))
			print(" / <span class=\"charge\">Def+".$skill['UpDEF']."%</span>");
		if(isset($skill["UpMDEF"]))
			print(" / <span class=\"charge\">Mdef+".$skill['UpMDEF']."%</span>");

		if(isset($skill["DownMAXHP"]))
			print(" / <span class=\"dmg\">MaxHP-".$skill['DownMAXHP']."%</span>");
		if(isset($skill["DownMAXSP"]))
			print(" / <span class=\"dmg\">MaxSP-".$skill['DownMAXSP']."%</span>");
		if(isset($skill["DownSTR"]))
			print(" / <span class=\"dmg\">Str-".$skill['DownSTR']."%</span>");
		if(isset($skill["DownINT"]))
			print(" / <span class=\"dmg\">Int-".$skill['DownINT']."%</span>");
		if(isset($skill["DownDEX"]))
			print(" / <span class=\"dmg\">Dex-".$skill['DownDEX']."%</span>");
		if(isset($skill["DownSPD"]))
			print(" / <span class=\"dmg\">Spd-".$skill['DownSPD']."%</span>");
		if(isset($skill["DownLUK"]))
			print(" / <span class=\"dmg\">Luk-".$skill['DownLUK']."%</span>");
		if(isset($skill["DownATK"]))
			print(" / <span class=\"dmg\">Atk-".$skill['DownATK']."%</span>");
		if(isset($skill["DownMATK"]))
			print(" / <span class=\"dmg\">Matk-".$skill['DownMATK']."%</span>");
		if(isset($skill["DownDEF"]))
			print(" / <span class=\"dmg\">Def-".$skill['DownDEF']."%</span>");
		if(isset($skill["DownMDEF"]))
			print(" / <span class=\"dmg\">Mdef-".$skill['DownMDEF']."%</span>");

		if(isset($skill["PlusSTR"]))
			print(" / <span class=\"charge\">Str+".$skill['PlusSTR']."</span>");
		if(isset($skill["PlusINT"]))
			print(" / <span class=\"charge\">Int+".$skill['PlusINT']."</span>");
		if(isset($skill["PlusDEX"]))
			print(" / <span class=\"charge\">Dex+".$skill['PlusDEX']."</span>");
		if(isset($skill["PlusSPD"]))
			print(" / <span class=\"charge\">Spd+".$skill['PlusSPD']."</span>");
		if(isset($skill["PlusLUK"]))
			print(" / <span class=\"charge\">Luk+".$skill['PlusLUK']."</span>");

		if(isset($skill["charge"]["0"]) || isset($skill["charge"]["1"])) {
			print(" / (".(isset($skill["charge"]["0"])?$skill["charge"]["0"]:"0").":");
			print((isset($skill["charge"]["1"])?$skill["charge"]["1"]:"0").")");
		}

		if(isset($skill["limit"])) {
			$Limit	= " / Limit:";
			foreach($skill["limit"] as $type => $bool) {
				$Limit .= $type.", ";
			}
			print(substr($Limit,0,-2));
		}
		if(isset($skill["exp"]))
			print(" / {$skill['exp']}");
		print("\n");
	}

	function ShowItemDetail($item,$amount=false,$text=false,$need=false) {
		$html = "";
		if(isset($item)){
			if(isset($item["img"]))
				$html	.= "<img src=\"".IMG_ICON.$item["img"]."\" class=\"vcent\">";
			if(isset($item["refine"]))
				$html	.= "+{$item['refine']} ";
			if(isset($item["AddName"]))
				$html	.= "{$item['AddName']} ";
			if(isset($item['base_name']))
				$html	.= "{$item['base_name']}";

			if(isset($item["type"]))
				$html	.= "<span class=\"light\"> ({$item['type']})</span>";
			if($amount) {
				$html	.= " x<span class=\"bold\" style=\"font-size:80%\">{$amount}</span>";
			}
			if(isset($item["atk"]["0"]))
				$html	.= ' / <span class="dmg">Atk:'.$item['atk'][0].'</span>';
			if(isset($item["atk"]["1"]))
				$html	.= ' / <span class="spdmg">Matk:'.$item['atk'][1].'</span>';
			if(isset($item["def"])) {
				$html	.= " / <span class=\"recover\">Def:{$item['def'][0]}+{$item['def'][1]}</span>";
				$html	.= " / <span class=\"support\">Mdef:{$item['def'][2]}+{$item['def'][3]}</span>";
			}
			if(isset($item["P_SUMMON"]))
				$html	.= ' / <span class="support">Summon+'.$item["P_SUMMON"].'%</span>';
			if(isset($item["handle"]))
				$html	.= ' / <span class="charge">h:'.$item['handle'].'</span>';
			if(isset($item["option"]))
				$html	.= ' / <span style="font-size:80%">'.substr($item["option"],0,-2)."</span>";

			if($need && isset($item["need"])) {
				$html	.= " /";
				foreach($item["need"] as $M_itemNo => $M_amount) {
					$M_item	= LoadItemData($M_itemNo);
					$html	.= "<img src=\"".IMG_ICON.$M_item["img"]."\" class=\"vcent\">";
					$html	.= "".$M_item['base_name']."";
					$html	.= " x<span class=\"bold\" style=\"font-size:80%\">{$M_amount}</span>";
					if($need["$M_itemNo"])
					$html	.= "<span class=\"light\">(".$need["$M_itemNo"].")</span>";
				}
			}

			if($text)
				return $html;

			print($html);
		} else
			 return false;
	}


	function ShowResult($message,$add=false) {
		if($add)
			$add	= " ".$add;
		if(is_string($message))
			print('<div class="result'.$add.'">'.$message.'</div>'."\n");
	}

	function ShowError($message,$add=false) {
		if($add)
			$add	= " ".$add;
		if(is_string($message))
			print('<div class="error'.$add.'">'.$message.'</div>'."\n");
	}

	function ShowManual() {
		include(MANUAL);
		return true;
	}

	function ShowManual2() {
		include(MANUAL_HIGH);
		return true;
	}

	function ShowTutorial() {
		include(TUTORIAL);
		return true;
	}

	function ShowUpDate() {
		print('<div style="margin:15px">');
		print("<p><a href=\"?\">Back</a><br><a href=\"#btm\">to bottom</a></p>");

		if(__POST("updatetext")) {
			$update	= htmlspecialchars(__POST("updatetext"),ENT_QUOTES);
			$update	= stripslashes($update);
		} else
			$update	= @file_get_contents(UPDATE);

		print('<form action="?update" method="post">');
		if(__POST("updatepass") == UP_PASS) {
			print('<textarea class="text" rows="12" cols="60" name="updatetext">');
			print("$update");
			print('</textarea><br>');
			print('<input type="submit" class="btn" value="update">');
			print('<a href="?update">새로 고침<br>');
		}

		print(nl2br($update)."\n");
		print('<br><a name="btm"></a>');
		if(__POST("updatepass") == UP_PASS && __POST("updatetext")) {
			$fp	= fopen(UPDATE,"w");
			$text	= htmlspecialchars(__POST("updatetext"),ENT_QUOTES);
			$text	= stripslashes($text);
			flock($fp,2);
			fputs($fp,$text);
			fclose($fp);
		}
		$temp = __POST('updatepass');
print <<< EOD
	<input type="password" class="text" name="updatepass" style="width:100px" value="{$temp}">
	<input type="submit" class="btn" value="update">
	</form>
EOD;
		print("<p><a href=\"?\">Back</a></p></div>");
	}

	function ShowGameData() {
print <<<P1
<div style="margin:15px">
<h4>GameData</h4>
<div style="margin:0 20px">
| <a href="?gamedata=job">직업(Job)</a> | 
<a href="?gamedata=item">아이템(item)</a> | 
<a href="?gamedata=judge">판정</a> | 
</div>
</div>
P1; 
	switch(__GET("gamedata")) {
		case "job": include(GAME_DATA_JOB); break;
		case "item": include(GAME_DATA_ITEM); break;
		case "judge": include(GAME_DATA_JUDGE); break;
		case "monster": include(GAME_DATA_MONSTER); break;
		default: include(GAME_DATA_JOB); break;
	}

	}


	function userNameLoad() {
		$name	= @file(USER_NAME);
		if($name) {
			foreach($name as $key => $var) {
				$name[$key]	= trim($name[$key]);
				if($name[$key] === "")
					unset($name[$key]);
			}
			return $name;
		} else {
			return array();
		}
	}


	function userNameAdd($add) {
		$string = "";
		foreach(userNameLoad() as $name) {
			$string	.= $name."\n";
		}
		$string .= $add."\n";
		$fp	= fopen(USER_NAME,"w+");
		flock($fp, LOCK_EX);
		fwrite($fp,$string);
		fclose($fp);
	}


	function RankAllShow() {
		print('<div style="margin:15px">'."\n");
		print('<h4>Ranking - '.date("Y년n월j일 G:i:s").'</h4>'."\n");
		include(CLASS_RANKING);
		$Rank	= new Ranking();
		$Rank->ShowRanking();
		print('</div>'."\n");
	}


	function RecordManage($string) {
		$file	= MANAGE_LOG_FILE;

		$fp	= @fopen($file,"r+") or die("File can not read.");
		$text	= fread($fp,2048);
		ftruncate($fp,0);
		rewind($fp);
		fwrite($fp,$string."\n".$text);
	}


	function CheckString($string,$maxLength=16) {
		$string	= trim($string);
		$string	= stripslashes($string);
		if(is_numeric(strpos($string,"\t"))) {
			return array(false,"잘못된 문자");
		}
		if(is_numeric(strpos($string,"\n"))) {
			return array(false,"잘못된 문자");
		}
		if (!$string) {
			return array(false,"공허할 수 없음");
		}
		$length	= strlen($string);
		if ( 0 == $length || $maxLength < $length) {
			return array(false,"너무 짧거나 너무 길거나");
		}
		$string	= htmlspecialchars($string,ENT_QUOTES);
		return array(true,$string);
	}

	function isMobile() {
		if(strstr(__SERVER('HTTP_USER_AGENT'),"DoCoMo")){
			$env = 'i';
		}elseif(strstr(__SERVER('HTTP_USER_AGENT'),"Vodafone")){
			$env = 'i';
		}elseif(strstr(__SERVER('HTTP_USER_AGENT'),"SoftBank")){
			$env = 'i';
		}elseif(strstr(__SERVER('HTTP_USER_AGENT'),"MOT-")){
			$env = 'i';
		}elseif(strstr(__SERVER('HTTP_USER_AGENT'),"J-PHONE")){
			$env = 'i';
		}elseif(strstr(__SERVER('HTTP_USER_AGENT'),"KDDI")){
			$env = 'ez';
		}elseif(strstr(__SERVER('HTTP_USER_AGENT'),"UP.Browser")){
			$env = 'i';
		}elseif(strstr(__SERVER('HTTP_USER_AGENT'),"WILLCOM")){
			$env = 'ez';
		}else{
			$env = 'pc';
		}
		return $env;
	}


	if(!function_exists("dump"))  {
		function dump($array) {
			print("<pre>".print_r($array,1)."</pre>");
		}
	}
?>