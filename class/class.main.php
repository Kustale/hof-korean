<?php
/*
Hall of Fame Webgame Code
Copyright (C) TEKITO PROJECT for JUICE ( http://tekito.kanichat.com/ )
Remaked by https://github.com/remileon/hof

Code fixed by Kustale ( jinsu8527@gmail.com )

한국어 버전 by Kustale, FE
*/

include(CLASS_USER);
include(GLOBAL_PHP);
class main extends user {

	var $islogin	= false;


	function __construct() {
		$this->SessionSwitch();
		$this->Set_ID_PASS();
		ob_start();
		$this->Order();
		$content	= ob_get_contents();
		ob_end_clean();

		$this->Head();
		print($content);
		$this->Debug();
		$this->Foot();
		
		if(!is_dir(USER)){
			mkdir(USER, 0705);
$st = <<<EOF
<?php
header('location:../');
?>
EOF;
			$fp = fopen(USER."index.php","w");
			fwrite($fp,$st);
			fclose($fp);
		}
		if(!is_dir(UNION)){
			mkdir(UNION, 0705);
$st = <<<EOF
<?php
header('location:../');
?>
EOF;
			$fp = fopen(UNION."index.php","w");
			fwrite($fp,$st);
			fclose($fp);
		}
		if(!is_dir(LOG_DIR)){
			mkdir(LOG_DIR,0705);
$st = <<<EOF
<?php
header('location:../');
?>
EOF;
			$fp = fopen(LOG_DIR."index.php","w");
			fwrite($fp,$st);
			fclose($fp);
		}
		if(!is_dir(LOG_BATTLE_NORMAL)){
			mkdir(LOG_BATTLE_NORMAL,0705);
$st = <<<EOF
<?php
header('location:../');
?>
EOF;
			$fp = fopen(LOG_BATTLE_NORMAL."index.php","w");
			fwrite($fp,$st);
			fclose($fp);
		}
		if(!is_dir(LOG_BATTLE_RANK)){
			mkdir(LOG_BATTLE_RANK,0705);
$st = <<<EOF
<?php
header('location:../');
?>
EOF;
			$fp = fopen(LOG_BATTLE_RANK."index.php","w");
			fwrite($fp,$st);
			fclose($fp);
		}
		if(!is_dir(LOG_BATTLE_UNION)){
			mkdir(LOG_BATTLE_UNION,0705);
$st = <<<EOF
<?php
header('location:../');
?>
EOF;
			$fp = fopen(LOG_BATTLE_UNION."index.php","w");
			fwrite($fp,$st);
			fclose($fp);
		}
	}


	function Order() {
		$result = "";
		switch(true) {
			case(__GET("menu") === "auction"):
				include(CLASS_AUCTION);
				$ItemAuction	= new Auction('item');
				$ItemAuction->AuctionHttpQuery("auction");
				$ItemAuction->ItemCheckSuccess(); 
				$ItemAuction->UserSaveData(); 
				break;

			case(__GET("menu") === "rank"):
				include(CLASS_RANKING);
				$Ranking	= new Ranking();
				break;
		}
		if( true === $message = $this->CheckLogin() ):
			include_once(DATA_ITEM);
			include(CLASS_CHAR);
			if($this->FirstLogin())
				return 0;

			switch(true) {

				case($this->OptionOrder()):	return false;

				case(__POST("delete")):
					if($this->DeleteMyData())
						return 0;

				case(__SERVER("QUERY_STRING") === "setting"):
					if($this->SettingProcess())
						$this->SaveData();

					$this->fpCloseAll();
					$this->SettingShow();
					return 0;

				case(__GET("menu") === "auction"):
					$this->LoadUserItem();
					$this->AuctionHeader();

					$ResultExhibit	= $this->AuctionItemExhibitProcess($ItemAuction);
					$ResultBidding	= $this->AuctionItemBiddingProcess($ItemAuction);
					$ItemAuction->ItemSaveData();
    
					if(__POST("ExhibitItemForm")) {
						$this->fpCloseAll();
						$this->AuctionItemExhibitForm($ItemAuction);

					} else if($ResultExhibit !== false) {

						if($ResultExhibit === true || $ResultBidding === true)
							$this->SaveData();

						$this->fpCloseAll();
						$this->AuctionItemBiddingForm($ItemAuction);

					} else {
						$this->fpCloseAll();
						$this->AuctionItemExhibitForm($ItemAuction);
					}

					$this->AuctionFoot($ItemAuction);
					return 0;

				case(__SERVER("QUERY_STRING") === "hunt"):
					$this->LoadUserItem();
					$this->fpCloseAll();
					$this->HuntShow();
					return 0;

				case(__SERVER("QUERY_STRING") === "town"):
					$this->LoadUserItem();
					$this->fpCloseAll();
					$this->TownShow();
					return 0;

				case(__SERVER("QUERY_STRING") === "simulate"):
					$this->CharDataLoadAll();
					if($this->SimuBattleProcess())
						$this->SaveData();

					$this->fpCloseAll();
					$this->SimuBattleShow($result);
					return 0;

				case(__GET("union")):
					$this->CharDataLoadAll();
					include(CLASS_UNION);
					include(DATA_MONSTER);
					if($this->UnionProcess()) {
						$this->SaveData();
						$this->fpCloseAll();
					} else {
						$this->fpCloseAll();
						$this->UnionShow();
					}
					return 0;

				case(__GET("common")):
					$this->CharDataLoadAll();
					$this->LoadUserItem();
					if($this->MonsterBattle()) {
						$this->SaveData();
						$this->fpCloseAll();
					} else {
						$this->fpCloseAll();
						$this->MonsterShow();
					}
					return 0;

				case(__GET("char")):
					$this->CharDataLoadAll();
					include(DATA_SKILL);
					include(DATA_JUDGE_SETUP);
					$this->LoadUserItem();
					$this->CharStatProcess();
					$this->fpCloseAll();
					$this->CharStatShow();
					return 0;

				case(__SERVER("QUERY_STRING") === "item"):
					$this->LoadUserItem();
					$this->fpCloseAll();
					$this->ItemShow();
					return 0;

				case(__GET("menu") === "refine"):
					$this->LoadUserItem();
					$this->SmithyRefineHeader();
					if($this->SmithyRefineProcess())
						$this->SaveData();

					$this->fpCloseAll();
					$result	= $this->SmithyRefineShow();
					return 0;

				case(__GET("menu") === "create"):
					$this->LoadUserItem();
					$this->SmithyCreateHeader();
					include(DATA_CREATE);
					if($this->SmithyCreateProcess())
						$this->SaveData();

					$this->fpCloseAll();
					$this->SmithyCreateShow();
					return 0;
				case(__SERVER("QUERY_STRING") === "shop"):
					$this->LoadUserItem();
					if($this->ShopProcess())
						$this->SaveData();
					$this->fpCloseAll();
					$this->ShopShow();
					return 0;
				case(__GET("menu") === "buy"):
					$this->LoadUserItem();
					$this->ShopHeader();
					if($this->ShopBuyProcess())
						$this->SaveData();
					$this->fpCloseAll();
					$this->ShopBuyShow();
					return 0;

				case(__GET("menu") === "sell"):
					$this->LoadUserItem();
					$this->ShopHeader();
					if($this->ShopSellProcess())
						$this->SaveData();
					$this->fpCloseAll();
					$this->ShopSellShow();
					return 0;

				case(__GET("menu") === "work"):
					$this->ShopHeader();
					if($this->WorkProcess())
						$this->SaveData();
					$this->fpCloseAll();
					$this->WorkShow();
					return 0;

				case(__GET("menu") === "rank"):
					$this->CharDataLoadAll();
					$RankProcess	= $this->RankProcess($Ranking);

					if ($RankProcess === "BATTLE") {
						$this->SaveData();
						$this->fpCloseAll();
					} else if ($RankProcess === true) {
						$this->SaveData();
						$this->fpCloseAll();
						$this->RankShow($Ranking);
					} else {
						$this->fpCloseAll();
						$this->RankShow($Ranking);
					}
					return 0;

				case(__SERVER("QUERY_STRING") === "recruit"):
					if($this->RecruitProcess())
						$this->SaveData();

					$this->fpCloseAll();
					$this->RecruitShow($result);
					return 0;

				default:
					$this->CharDataLoadAll();
					$this->fpCloseAll();
					$this->LoginMain();
					return 0;
			}
		else:
			$this->fpCloseAll();
			switch(true) {
				case($this->OptionOrder()):	return false;
				case(__POST("Make")):
					list($bool,$message) = $this->MakeNewData();
					if( true === $bool ) {
						$this->LoginForm($message);
						return false;
					}
				case(__SERVER("QUERY_STRING") === "newgame"):
					$this->NewForm($message);	return false;
				default:	$this->LoginForm($message);
			}
		endif;
	}


	function OptionOrder() {
		$this->fpCloseAll();
		switch(true) {
			case(__SERVER("QUERY_STRING") === "rank"):	RankAllShow();	return true;
			case(__SERVER("QUERY_STRING") === "update"):	ShowUpDate();	return true;
			case(__SERVER("QUERY_STRING") === "bbs"):	$this->bbs01();	return true;
			case(__SERVER("QUERY_STRING") === "manual"):	ShowManual();	return true;
			case(__SERVER("QUERY_STRING") === "manual2"):	ShowManual2();	return true;
			case(__SERVER("QUERY_STRING") === "tutorial"):	ShowTutorial();	return true;
			case(__SERVER("QUERY_STRING") === "log"):
				ShowLogList();
				return true;
			case(__SERVER("QUERY_STRING") === "clog"): LogShowCommon(); return true;
			case(__SERVER("QUERY_STRING") === "ulog"): LogShowUnion(); return true;
			case(__SERVER("QUERY_STRING") === "rlog"): LogShowRanking(); return true;
			case(__GET("gamedata")):
				ShowGameData();
				return true;
			case(__GET("log")):
				ShowBattleLog(__GET("log"));
				return true;
			case(__GET("ulog")):
				ShowBattleLog(__GET("ulog"),"UNION");
				return true;
			case(__GET("rlog")):
				ShowBattleLog(__GET("rlog"),"RANK");
				return true;
		}
	}


	function EnemyNumber($party) {
		$min	= __count($party);
		if($min == 5)
			return 5;
		$max	= $min + ENEMY_INCREASE;
		if($max>5)
			$max	= 5;
		mt_srand();
		return mt_rand($min,$max);
	}

	function SelectMonster($monster) {
		$max = 0;
		$upp = 0;
		foreach($monster as $val)
			$max	+= $val[0];
		$pos	= mt_rand(0,$max);
		foreach($monster as $monster_no => $val) {
			$upp	+= $val[0];
			if($pos <= $upp)
				return $monster_no;
		}
	}

	function EnemyParty($Amount,$MonsterList,$Specify=false) {

		if($Specify) {
			$MonsterNumbers	= $Specify;
		}

		$enemy	= array();
		if(!$Amount)
			return $enemy;
		mt_srand();
		for($i=0; $i<$Amount; $i++)
			$MonsterNumbers[]	= $this->SelectMonster($MonsterList);

		$overlap	= array_count_values($MonsterNumbers);

		include(CLASS_MONSTER);
		foreach($MonsterNumbers as $Number) {
			if(1 < $overlap[$Number])
				$enemy[]	= new monster(CreateMonster($Number,true));
			else
				$enemy[]	= new monster(CreateMonster($Number));
		}
		return $enemy;
	}

	function CharStatProcess() {
		$char	= &$this->char[__GET("char")];
		if(!$char) return false;
		switch(true):
			case(__POST("stup")):
				$Sum	= abs(__POST("upStr")) + abs(__POST("upInt")) + abs(__POST("upDex")) + abs(__POST("upSpd")) + abs(__POST("upLuk"));
				if($char->statuspoint < $Sum) {
					ShowError("상태 포인트가 너무 많습니다","margin15");
					return false;
				}

				if($Sum == 0)
					return false;

				$Stat	= array("Str","Int","Dex","Spd","Luk");
				foreach($Stat as $val) {
					if(MAX_STATUS < ($char->{strtolower($val)} + __POST("up".$val))) {
						ShowError("최대 상태를 초과(".MAX_STATUS.")","margin15");
						return false;
					}
				}
				$char->str	+= __POST("upStr");
				$char->int	+= __POST("upInt");
				$char->dex	+= __POST("upDex");
				$char->spd	+= __POST("upSpd");
				$char->luk	+= __POST("upLuk");
				$char->SetHpSp();

				$char->statuspoint	-= $Sum;
				print("<div class=\"margin15\">\n");
				if(__POST("upStr"))
					ShowResult("STR <span class=\"bold\">".__POST('upStr')."</span>이 증가합니다.".(($char->str) - ((int)__POST("upStr")))." -> ".$char->str."<br />\n");
				if(__POST("upInt"))
					ShowResult("INT <span class=\"bold\">".__POST('upInt')."</span>이 증가합니다.".(($char->int) - ((int)__POST("upInt")))." -> ".$char->int."<br />\n");
				if(__POST("upDex"))
					ShowResult("DEX <span class=\"bold\">".__POST('upDex')."</span>이 증가합니다.".(($char->dex) - ((int)__POST("upDex")))." -> ".$char->dex."<br />\n");
				if(__POST("upSpd"))
					ShowResult("SPD <span class=\"bold\">".__POST('upSpd')."</span>이 증가합니다.".(($char->spd) - ((int)__POST("upSpd")))." -> ".$char->spd."<br />\n");
				if(__POST("upLuk"))
					ShowResult("LUK <span class=\"bold\">".__POST('upLuk')."</span>이 증가합니다.".(($char->luk) - ((int)__POST("upLuk")))." -> ".$char->luk."<br />\n");
				print("</div>\n");
				$char->SaveCharData($this->id);
				return true;
			case(__POST("position")):
				if(__POST("position") == "front") {
					$char->position	= FRONT;
					$pos	= "전방(Front)";
				} else {
					$char->position	= BACK;
					$pos	= "후방(Back)";
				}

				$char->guard	= __POST("guard");
				switch(__POST("guard")) {
					case "never": $guard = "가드 포기"; break;
					case "life25": $guard = "체력이 25% 이상일 때 가드 보호"; break;
					case "life50": $guard = "체력이 50% 이상일 때 가드 보호"; break;
					case "life75": $guard = "체력이 75% 이상일 때 가드 보호"; break;
					case "prob25": $guard = "25% 확률로 가드 보호"; break;
					case "prob50": $guard = "50% 확률로 가드 보호"; break;
					case "prob75": $guard = "75% 확률로 가드 보호"; break;
					default: $guard = "항상 가드 보호"; break;
				}
				$char->SaveCharData($this->id);
				ShowResult($char->Name()." {$pos}의 구성입니다.<br />가드(guard)로 사용하는 경우 {$guard}로 설정합니다.\n","margin15");
				return true;
			case(__POST("ChangePattern")):
				$max	= $char->MaxPatterns();
				for($i=0; $i<$max; $i++) {
					$judge[]	= __POST("judge".$i);
					$quantity_post	= (int)__POST("quantity".$i);
					if(4 < strlen($quantity_post)) {
						$quantity_post	= substr($quantity_post,0,4);
					}
					$quantity[]	= $quantity_post;
					$action[]	= __POST("skill".$i);
				}
				if($char->PatternSave($judge,$quantity,$action)) {
					$char->SaveCharData($this->id);
					ShowResult("전투 설정이 저장되었습니다.","margin15");
					return true;
				}
				ShowError("저장에 실패하셨나요? 운영자에게 문의해보세요.","margin15");
				return false;
				break;
			case(__POST("TestBattle")):
					$max	= $char->MaxPatterns();
					for($i=0; $i<$max; $i++) {
						$judge[]	= __POST("judge".$i);
						$quantity_post	= (int)__POST("quantity".$i);
						if(4 < strlen($quantity_post)) {
							$quantity_post	= substr($quantity_post,0,4);
						}
						$quantity[]	= $quantity_post;
						$action[]	= __POST("skill".$i);
					}
					if($char->PatternSave($judge,$quantity,$action)) {
						$char->SaveCharData($this->id);
						$this->CharTestDoppel();
					}
				break;
			case(__POST("PatternMemo")):
				if($char->ChangePatternMemo()) {
					$char->SaveCharData($this->id);
					ShowResult("모드 전환 완료","margin15");
					return true;
				}
				break;
			case(__POST("AddNewPattern")):
				if(!__POST("PatternNumber"))
					return false;
				if($char->AddPattern(__POST("PatternNumber"))) {
					$char->SaveCharData($this->id);
					ShowResult("모드 추가 완료","margin15");
					return true;
				}
				break;
			case(__POST("DeletePattern")):
				if(!__POST("PatternNumber"))
					return false;
				if($char->DeletePattern(__POST("PatternNumber"))) {
					$char->SaveCharData($this->id);
					ShowResult("모드 삭제 완료","margin15");
					return true;
				}
				break;
			case(__POST("remove")):
				if(!__POST("spot")) {
					ShowError("제거할 아이템이 선택되지 않았습니다.","margin15");
					return false;
				}
				if(!$char->{__POST("spot")}) {
					ShowError("지정된 위치에 아이템이 없습니다","margin15");
					return false;
				}
				$item	= LoadItemData($char->{__POST("spot")});
				if(!$item) return false;
				$this->AddItem($char->{__POST("spot")});
				$this->SaveUserItem();
				$char->{__POST("spot")}	= NULL;
				$char->SaveCharData($this->id);
				SHowResult($char->Name()."의 {$item[name]}이 릴리스되었습니다.","margin15");
				return true;
				break;
			case(__POST("remove_all")):
				if($char->weapon || $char->shield || $char->armor || $char->item ) {
					if($char->weapon)	{ $this->AddItem($char->weapon);	$char->weapon	=NULL; }
					if($char->shield)	{ $this->AddItem($char->shield);	$char->shield	=NULL; }
					if($char->armor)	{ $this->AddItem($char->armor);		$char->armor	=NULL; }
					if($char->item)		{ $this->AddItem($char->item);		$char->item		=NULL; }
					$this->SaveUserItem();
					$char->SaveCharData($this->id);
					ShowResult("모든 ".$char->Name()." 아이템이 제거되었습니다.","margin15");
					return true;
				}	break;
			case(__POST("equip_item")):
				$item_no	= __POST("item_no");
				if($item_no != ""){
					if(!$this->item["$item_no"]) {
						ShowError("Item not exists.","margin15");
						return false;
					}
				}else{
					ShowError("Please select corrent item.","margin15");
					return false;
				}
				$JobData	= LoadJobData($char->job);
				$item	= LoadItemData($item_no);
				if( !in_array( $item["type"], $JobData["equip"]) ) {
					ShowError("{$char->job_name} can't equip {$item[name]}.","margin15");
					return false;
				}

				if(false === $return = $char->Equip($item)) {
					ShowError("장비가 너무 무겁습니다(손잡이가 부족함).","margin15");
					return false;
				} else {
					$this->DeleteItem($item_no);
					foreach($return as $no) {
						$this->AddItem($no);
					}
				}

				$this->SaveUserItem();
				$char->SaveCharData($this->id);
				ShowResult("{$char->name}의 {$item[name]} 항목.","margin15");
				return true;
				break;
			case(__POST("learnskill")):
				if(!__POST("newskill")) {
					ShowError("선택된 기술이 없습니다","margin15");
					return false;
				}

				$char->SetUser($this->id);
				list($result,$message)	= $char->LearnNewSkill(__POST("newskill"));
				if($result) {
					$char->SaveCharData();
					ShowResult($message,"margin15");
				} else {
					ShowError($message,"margin15");
				}
				return true;
			case(__POST("classchange")):
				if(!__POST("job")) {
					ShowError("선택된 직업이 없습니다","margin15");
					return false;
				}
				if($char->ClassChange(__POST("job"))) {
					if($char->weapon || $char->shield || $char->armor || $char->item ) {
						if($char->weapon)	{ $this->AddItem($char->weapon);	$char->weapon	=NULL; }
						if($char->shield)	{ $this->AddItem($char->shield);	$char->shield	=NULL; }
						if($char->armor)	{ $this->AddItem($char->armor);		$char->armor	=NULL; }
						if($char->item)		{ $this->AddItem($char->item);		$char->item		=NULL; }
						$this->SaveUserItem();
					}
					$char->SaveCharData($this->id);
					ShowResult("작업 이전 완료","margin15");
					return true;
				}
				ShowError("failed.","margin15");
				return false;
			case(__POST("rename")):
				$Name	= $char->Name();
				$temp_b = __GET("char");
				$message = <<< EOD
<form action="?char={}" method="post" class="margin15">
반각 영어로 16자 (전각으로 1자 = 반각으로 2자)<br />
<input type="text" name="NewName" style="width:160px" class="text" />
<input type="submit" class="btn" name="NameChange" value="Change" />
<input type="submit" class="btn" value="Cancel" />
</form>
EOD;
				print($message);
				return false;
			case(__POST("NewName")):
				list($result,$return)	= CheckString(__POST("NewName"),16);
				if($result === false) {
					ShowError($return,"margin15");
					return false;
				} else if($result === true) {
					if($this->DeleteItem("7500",1) == 1) {
						ShowResult($char->Name()." 는 ".$return." 로 이름 변경이 완료되었습니다.","margin15");
						$char->ChangeName($return);
						$char->SaveCharData($this->id);
						$this->SaveUserItem();
						return true;
					} else {
						ShowError("아이템이 없음.","margin15");
						return false;
					}
					return true;
				}
			case(__POST("showreset")):
				$Name	= $char->Name();
				print('<div class="margin15">'."\n");
				print("아이템 사용하기<br />\n");
				print('<form action="?char='.__GET("char").'" method="post">'."\n");
				print('<select name="itemUse">'."\n");
				$resetItem	= array(7510,7511,7512,7513,7520);
				foreach($resetItem as $itemNo) {
					if($this->item[$itemNo]) {
						$item	= LoadItemData($itemNo);
						print('<option value="'.$itemNo.'">'.$item[name]." x".$this->item[$itemNo].'</option>'."\n");
					}
				}
				print("</select>\n");
				print('<input type="submit" class="btn" name="resetVarious" value="다시 놓기">'."\n");
				print('<input type="submit" class="btn" value="취소">'."\n");
				print('</form>'."\n");
				print('</div>'."\n");
				break;

			case(__POST("resetVarious")):
				switch(__POST("itemUse")) {
					case 7510:
						$lowLimit	= 1;
						break;
					case 7511:
						$lowLimit	= 30;
						break;
					case 7512:
						$lowLimit	= 50;
						break;
					case 7513:
						$lowLimit	= 100;
						break;
					case 7520:
						$skillReset	= true;
						break;
				}
				if(__POST("itemUse") == 6000) {
					if($this->DeleteItem(6000) == 0) {
						ShowError("아이템이 없어요.","margin15");
						return false;
					}
					if(1 < $char->spd) {
						$dif	= $char->spd - 1;
						$char->spd	-= $dif;
						$char->statuspoint	+= $dif;
						$char->SaveCharData($this->id);
						$this->SaveUserItem();
						ShowResult("포인트 반환","margin15");
						return true;
					}
				}
				if($lowLimit) {
					if(!$this->item[__POST("itemUse")]) {
						ShowError("아이템이 없어요.","margin15");
						return false;
					}
					if($lowLimit < $char->str) {$dif = $char->str - $lowLimit; $char->str -= $dif; $pointBack += $dif;}
					if($lowLimit < $char->int) {$dif = $char->int - $lowLimit; $char->int -= $dif; $pointBack += $dif;}
					if($lowLimit < $char->dex) {$dif = $char->dex - $lowLimit; $char->dex -= $dif; $pointBack += $dif;}
					if($lowLimit < $char->spd) {$dif = $char->spd - $lowLimit; $char->spd -= $dif; $pointBack += $dif;}
					if($lowLimit < $char->luk) {$dif = $char->luk - $lowLimit; $char->luk -= $dif; $pointBack += $dif;}
					if($pointBack) {
						if($this->DeleteItem(__POST("itemUse")) == 0) {
							ShowError("아이템이 없어요.","margin15");
							return false;
						}
						$char->statuspoint	+= $pointBack;
						if($char->weapon || $char->shield || $char->armor || $char->item ) {
							if($char->weapon)	{ $this->AddItem($char->weapon);	$char->weapon	=NULL; }
							if($char->shield)	{ $this->AddItem($char->shield);	$char->shield	=NULL; }
							if($char->armor)	{ $this->AddItem($char->armor);		$char->armor	=NULL; }
							if($char->item)		{ $this->AddItem($char->item);		$char->item		=NULL; }
							ShowResult($char->Name()." 의 모든 아이템이 제거되었습니다.","margin15");
						}
						$char->SaveCharData($this->id);
						$this->SaveUserItem();
						ShowResult("포인트가 성공적으로 반환되었습니다","margin15");
						return true;
					} else {
						ShowError("포인트 반환에 실패했습니다","margin15");
						return false;
					}
				}
				if($skillReset) {
					if(!$this->item[__POST("itemUse")]) {
						ShowError("아이템이 없어요.","margin15");
						return false;
					}
					if($skillReset = true)
				if($char->job < 199) {$dif = ($char->level - 1) * 2 - $char->skillpoint; $char->skill ="1000<>1001"; $pointBack += $dif;}
				else if($char->job < 299) {$dif = ($char->level - 1) * 2 - $char->skillpoint; $char->skill ="1000<>1002<>3010"; $pointBack += $dif;}
				else if($char->job < 399) {$dif = ($char->level - 1) * 2 - $char->skillpoint; $char->skill ="1000<>3000<>3101"; $pointBack += $dif;}
				else {$dif = ($char->level - 1) * 2 - $char->skillpoint; $char->skill ="2300<>2310"; $pointBack += $dif;}
					if($pointBack) {
						if($this->DeleteItem(__POST("itemUse")) == 0) {
							ShowError("아이템이 없어요.","margin15");
							return false;
						}
						$char->skillpoint	+= $pointBack;
						$char->SaveCharData($this->id);
						$this->SaveUserItem();
						ShowResult("스킬 초기화 성공","margin15");
						return true;
					} else {
						ShowError("스킬 초기화에 실패했습니다.","margin15");
						return false;
					}
				}
				break;

			case(__POST("byebye")):
				$Name	= $char->Name();
				$temp_a = __GET("char");
				$message = <<< HTML_BYEBYE
<div class="margin15">
{$Name} 를 해고 하겠습니까?<br>
<form action="?char={$temp_a}" method="post">
<input type="submit" class="btn" name="kick" value="Yes">
<input type="submit" class="btn" value="No">
</form>
</div>
HTML_BYEBYE;
				print($message);
				return false;
			case(__POST("kick")):
				$char->DeleteChar();
				$host  = __SERVER('HTTP_HOST');
				$uri   = rtrim(dirname(__SERVER('PHP_SELF')));
				$extra = INDEX;
				header("Location: http://$host$uri/$extra");
				exit;
				break;
		endswitch;
	}
	
	function CharStatShow() {
		$CanChange = [];
		$char	= $this->char[__GET("char")];
		if(!$char) {
			print("Not exists");
			return false;
		}
		$char->SetBattleVariable();

		$JobData	= LoadJobData($char->job);

		if($JobData["change"]) {
			include_once(DATA_CLASSCHANGE);
			foreach($JobData["change"] as $job) {
				if(CanClassChange($char,$job))
					$CanChange[]	= $job;
			}
		}
		$temp1 = __GET("char");
print <<<P1
<form action="?char={$temp1}" method="post" style="padding:5px 0 0 15px">
P1;
		print('<div style="padding-top:5px">');
		foreach($this->char as $key => $val) {
			echo "<a href=\"?char={$key}\">{$val->name}</a>  ";
		}
		print("</div>");
print <<<P2
<h4>캐릭터 상태 <a href="?manual#charstat" target="_blank" class="a0">?</a></h4>
P2;
		$char->ShowCharDetail();
		if(isset($this->item["7500"]))
			print('<input type="submit" class="btn" name="rename" value="ChangeName">'."\n");
		if(isset($this->item["7510"]) ||
			isset($this->item["7511"]) ||
			isset($this->item["7512"]) ||
			isset($this->item["7513"]) ||
			isset($this->item["7520"])) {
			print('<input type="submit" class="btn" name="showreset" value="다시 놓기">'."\n");
		}
print <<<P3
<input type="submit" class="btn" name="byebye" value="제거">
</form>
P3;
	if(0 < $char->statuspoint) {
		$temp_ = __GET("char");
print <<< HTML
	<form action="?char={$temp_}" method="post" style="padding:0 15px">
	<h4>Status <a href="?manual#statup" target="_blank" class="a0">?</a></h4>
HTML;

		$Stat	= array("Str","Int","Dex","Spd","Luk");
		print("Point : {$char->statuspoint}<br />\n");
		foreach($Stat as $val) {
			print("{$val}:\n");
			print("<select name=\"up{$val}\" class=\"vcent\">\n");
			for($i=0; $i < $char->statuspoint + 1; $i++)
				print("<option value=\"{$i}\">+{$i}</option>\n");
			print("</select>");
		}
		print("<br />");
		print('<input type="submit" class="btn" name="stup" value="감사">');
		print("\n");

	print("</form>\n");
	}
	$temp2 = __GET("char");
	
print <<<P4
	<form action="?char={$temp2}" method="post" style="padding:0 15px">
	<h4>액션 모드 <a href="?manual#jdg" target="_blank" class="a0">?</a></h4>
P4;

		$list	= JudgeList();
		print("<table cellspacing=\"5\"><tbody>\n");
		for($i=0; $i<$char->MaxPatterns(); $i++) {
			print("<tr><td>");
			print( ($i+1)."</td><td>");
			print("<select name=\"judge".$i."\">\n");
			foreach($list as $val) {
				$exp	= LoadJudgeData($val);
				print("<option value=\"{$val}\"".((isset($char->judge[$i]) ? $char->judge[$i] : "") == $val ? " selected" : NULL).(isset($exp["css"])?' class="select0"':NULL).">".(isset($exp["css"])?' ':'   ')."{$exp['exp']}</option>\n");
			}
			print("</select>\n");
			print("</td><td>\n");
			print("<input type=\"text\" name=\"quantity".$i."\" maxlength=\"4\" value=\"".(isset($char->quantity[$i]) ? $char->quantity[$i] : "")."\" style=\"width:56px\" class=\"text\">");
			print("</td><td>\n");
			print("<select name=\"skill".$i."\">\n");
			foreach($char->skill as $val) {
				$skill	= LoadSkillData($val);
				print("<option value=\"{$val}\"".((isset($char->action[$i]) ? $char->action[$i] : "") == $val ? " selected" : NULL).">");
				print($skill["name"].(isset($skill["sp"])?" - (SP:{$skill['sp']})":NULL));
				print("</option>\n");
			}
			print("</select>\n");
			print("</td><td>\n");
			print('<input type="radio" name="PatternNumber" value="'.$i.'">');
			print("</td></tr>\n");
		}
		print("</tbody></table>\n");

		$temp3 = __GET("char");
		$temp4 = (($char->position=="front") ? " checked" : NULL);
		$temp5 = (($char->position=="back") ? " checked" : NULL);
print <<<P5
<input type="submit" class="btn" value="확인 모드" name="ChangePattern">
<input type="submit" class="btn" value="설정 및 테스트" name="TestBattle">
 <a href="?simulate">Simulate</a><br />
<input type="submit" class="btn" value="스위칭 모드" name="PatternMemo">
<input type="submit" class="btn" value="추가" name="AddNewPattern">
<input type="submit" class="btn" value="삭제" name="DeletePattern">
</form>
<form action="?char={$temp3}" method="post" style="padding:0 15px">
<h4>위치 및 보호<a href="?manual#posi" target="_blank" class="a0">?</a></h4>
<table><tbody>
<tr><td>위치(Position) :</td><td><input type="radio" class="vcent" name="position" value="front"
{$temp4}>전방(Front)</td></tr>
<tr><td></td><td><input type="radio" class="vcent" name="position" value="back"
{$temp5}>후방(Backs)</td></tr>
<tr><td>경비원(Guarding) :</td><td>
<select name="guard">
P5;

		$option	= array(
			"always" => "항상 보호",
			"never" => "보호 안 함",
			"life25" => "HP가 25% 이상일 때 보호",
			"life50" => "HP가 50% 이상일 때 보호",
			"life75" => "HP가 75% 이상일 때 보호",
			"prob25" => "25% 확률로 보호",
			"prpb50" => "50% 확률로 보호",
			"prob75" => "75% 확률로 보호",
		);
		foreach($option as $key => $val)
			print("<option value=\"{$key}\"".($char->guard==$key ? " selected" : NULL ).">{$val}</option>");
print <<<P6
	</select>
	</td></tr>
	</tbody></table>
	<input type="submit" class="btn" value="설정">
	</form>
P6;
		$weapon	= LoadItemData($char->weapon);
		$shield	= LoadItemData($char->shield);
		$armor	= LoadItemData($char->armor);
		$item	= LoadItemData($char->item);

		$handle	= 0;
		$handle	= (isset($weapon["handle"])?$weapon["handle"]:0) + (isset($shield["handle"])?$shield["handle"]:0) + (isset($armor["handle"])?$armor["handle"]:0) + (isset($item["handle"])?$item["handle"]:0);

		$temp6 = __GET("char");
print <<<P7_A
	<div style="margin:0 15px">
	<h4>장비<a href="?manual#equip" target="_blank" class="a0">?</a></h4>
	<div class="bold u">Current Equip's</div>
	<table>
	<tr><td class="dmg" style="text-align:right">Atk :</td><td class="dmg">{$char->atk[0]}</td></tr>
	<tr><td class="spdmg" style="text-align:right">Matk :</td><td class="spdmg">{$char->atk[1]}</td></tr>
	<tr><td class="recover" style="text-align:right">Def :</td><td class="recover">{$char->def[0]} + {$char->def[1]}</td></tr>
	<tr><td class="support" style="text-align:right">Mdef :</td><td class="support">{$char->def[2]} + {$char->def[3]}</td></tr>
	<tr><td class="charge" style="text-align:right">handle :</td><td class="charge">{$handle} / {$char->GetHandle()}</td></tr>
	</table>
	<form action="?char={$temp6}" method="post">
	<table>
	<tr><td class="align-right">
	무기:</td><td><input type="radio" class="vcent" name="spot" value="weapon">
	P7_A;
	ShowItemDetail(LoadItemData($char->weapon));
	print <<<P7_B
	</td></tr><tr><td class="align-right">
	방패:</td><td><input type="radio" class="vcent" name="spot" value="shield">
	P7_B;
	ShowItemDetail(LoadItemData($char->shield));
	print <<<P7_C
	</td></tr><tr><td class="align-right">
	갑옷:</td><td><input type="radio" class="vcent" name="spot" value="armor">
	P7_C;
	ShowItemDetail(LoadItemData($char->armor));
	print <<<P7_D
	</td></tr><tr><td class="align-right">
	아이템 : </td><td><input type="radio" class="vcent" name="spot" value="item">
	P7_D;
	ShowItemDetail(LoadItemData($char->item));
	print <<<P7_E
	</td></tr></tbody>
	</table>
	<input type="submit" class="btn" name="remove" value="장비해제">
	<input type="submit" class="btn" name="remove_all" value="전부해제">
	</form>
	</div>
P7_E;

		if($JobData["equip"])
			$EquipAllow	= array_flip($JobData["equip"]);
		else
			$EquipAllow	= array();
		$Equips		= array("Weapon"=>"2999","Shield"=>"4999","Armor"=>"5999","Item"=>"9999");

		print("<div style=\"padding:15px 15px 0 15px\">\n");
		print("\t<div class=\"bold u\">소유 및 허용 장비</div>\n");
		if($this->item) {
			include(CLASS_JS_ITEMLIST);
			$EquipList	= new JS_ItemList();
			$EquipList->SetID("equip");
			$EquipList->SetName("type_equip");
			if($this->no_JS_itemlist)
				$EquipList->NoJS();
			reset($this->item);
			foreach($this->item as $key => $val) {
				$item	= LoadItemData($key);
				if(!isset( $EquipAllow[ $item["type"] ] ))
					continue;
				$head	= '<input type="radio" name="item_no" value="'.$key.'" class="vcent">';
				$head	.= ShowItemDetail($item,$val,true)."<br />";
				$EquipList->AddItem($item,$head);
			}
			print($EquipList->GetJavaScript("list0"));
			print($EquipList->ShowSelect());
			print('<form action="?char='.__GET("char").'" method="post">'."\n");
			print('<div id="list0">'.$EquipList->ShowDefault().'</div>'."\n");
			print('<input type="submit" class="btn" name="equip_item" value="장비">'."\n");
			print("</form>\n");
		} else {
			print("아직 아이템이 없습니다.<br />\n");
		}
		print("</div>\n");

		$temp11 = __GET("char");
print <<<P8
	<form action="?char={$temp11}" method="post" style="padding:0 15px">
	<h4>기능<a href="?manual#skill" target="_blank" class="a0">?</a></h4>
P8;

		include_once(DATA_SKILL_TREE);
		if($char->skill) {
			print('<div class="u bold">마스터했다</div>');
			print("<table><tbody>");
			foreach($char->skill as $val) {
				print("<tr><td>");
				$skill	= LoadSkillData($val);
				ShowSkillDetail($skill);
				print("</td></tr>");
			}
			print("</tbody></table>");
			print('<div class="u bold">새로운 기술</div>');
			print("기술 포인트 : {$char->skillpoint}");
			print("<table><tbody>");
			$tree	= LoadSkillTree($char);
			foreach(array_diff($tree,$char->skill) as $val) {
				print("<tr><td>");
				$skill	= LoadSkillData($val);
				ShowSkillDetail($skill,1);
				print("</td></tr>");
			}
			print("</tbody></table>");
			print('<input type="submit" class="btn" name="learnskill" value="인수">'."\n");
			print('<input type="hidden" name="learnskill" value="1">'."\n");
		}
		if($CanChange) {
			$temp12 = __GET("char");
print <<<P9
	</form>
	<form action="?char={$temp12}" method="post" style="padding:0 15px">
	<h4>직업 변경</h4>
	<table><tbody><tr>
P9;
			foreach($CanChange as $job) {
				print("<td valign=\"bottom\" style=\"padding:5px 30px;text-align:center\">");
				$JOB	= LoadJobData($job);
				print('<img src="'.IMG_CHAR.$JOB["img_".($char->gender?"female":"male")].'">'."<br />\n");
				print('<input type="radio" value="'.$job.'" name="job">'."<br />\n");
				print($JOB["name_".($char->gender?"female":"male")]);
				print("</td>");
			}

print <<<P10
	</tr></tbody></table>
	<input type="submit" class="btn" name="classchange" value="직업 변경">
	<input type="hidden" name="classchange" value="1">
P10;
		}


		print("</form>");

		print('<div  style="padding:15px">');
		foreach($this->char as $key => $val) {
			echo "<a href=\"?char={$key}\">{$val->name}</a>  ";
		}
		print('</div>');
	}

	function CharTestDoppel() {
		if(!__POST("TestBattle")) return 0;

		$char	= $this->char[__GET("char")];
		$this->DoppelBattle(array($char));
	}

	function DoppelBattle($party,$turns=10) {

		foreach($party as $key => $char) {
			$enemy[$key]	= new char();
			$enemy[$key]->SetCharData(get_object_vars($char));
			
		}
		foreach($enemy as $key => $doppel) {
			$enemy[$key]->ChangeName("Nise".$doppel->name);
		}

		include(CLASS_BATTLE);
		$battle	= new battle($party,$enemy);
		$battle->SetTeamName($this->name,"Do-feru");
		$battle->LimitTurns($turns);
		$battle->NoResult();
		$battle->Process();
		return true;
	}

	function SimuBattleProcess() {
		if(__POST("simu_battle")) {
			$this->MemorizeParty();
			foreach($this->char as $key => $val) {
				if(__POST("char_".$key))
					$MyParty[]	= $this->char[$key];
			}
			if( __count($MyParty) === 0) {
				ShowError('전투에는 최소 한 명 이상이 참여해야 합니다.',"margin15");
				return false;
			} else if(5 < __count($MyParty)) {
				ShowError('최대 5명까지 전투에 참여할 수 있습니다.',"margin15");
				return false;
			}
			$this->DoppelBattle($MyParty,50);
			return true;
		}
	}

	function SimuBattleShow($message=false) {
		print('<div style="margin:15px">');
		ShowError($message);
		print('<span class="bold">시뮬레이션 전투</span>');
		print('<h4>Teams</h4></div>');
		print('<form action="'.INDEX.'?simulate" method="post">');
		$this->ShowCharacters($this->char,CHECKBOX,explode("<>",(isset($this->party_memo) ? $this->party_memo : '')));
print <<<P11
	<div style="margin:15px;text-align:center">
	<input type="submit" class="btn" name="simu_battle" value="대결!">
	<input type="reset" class="btn" value="다시 놓기"><br>
	이 팀을 기억하기 : <input type="checkbox" name="memory_party" value="1">
	</div></form>
P11;
	}

	function HuntShow() {
		include(DATA_LAND);
		include(DATA_LAND_APPEAR);
		print('<div style="margin:15px">');
		print('<h4>평범한 괴물들</h4>');
		print('<div style="margin:0 20px">');

		$mapList	= LoadMapAppear($this);
		foreach($mapList as $map) {
			list($land)	= LandInformation($map);
			print("<p style='display:inline;margin-right:32px;'><a href=\"?common={$map}\">{$land['name']}</a>");
			print("</p>");
		}

		print("</div>\n");
		$files	= glob(UNION."*");
		if($files) {
			include(CLASS_UNION);
			include(DATA_MONSTER);
			foreach($files as $file) {
				$fileh = pathinfo($file);
				if($fileh["extension"] == "php") continue;
				$UnionMons	= new union($file);
				if($UnionMons->is_Alive())
					$Union[]	= $UnionMons;
			}
		}
		if(isset($Union)) {
			print('<h4>BOSS</h4>');
			$result = $this->CanUnionBattle();
			if($result !== true) {
				$left_minute	= floor($result/60);
				$left_second	= $result%60;
				print('<div style="margin:0 20px">');
				print('다음 전투는 다음과 같습니다 : <span class="bold">'.$left_minute. ":".sprintf("%02d",$left_second)."</span>");
				print("</div>");
			}
			print("</div>");
			$this->ShowCharacters($Union);
		} else {
			print("</div>");
		}

		print("<div style=\"margin:0 15px\">\n");
		print("<h4>BOSS 배틀 레코드 <a href=\"?ulog\">전체 표현</a></h4>\n");
		print("<div style=\"margin:0 20px\">\n");
		$log	= @glob(LOG_BATTLE_UNION."*");
		foreach(array_reverse($log) as $file) {
			$fileh = pathinfo($file);
			if($fileh["extension"] == "php") continue;
			$limit++;
			BattleLogDetail($file,"UNION");
			if(15 <= $limit)
				break;
		}
		print("</div></div>\n");
	}

	function MonsterShow() {
		$message = "";
		$land_id	= __GET("common");
		include(DATA_LAND);
		include_once(DATA_LAND_APPEAR);
		if(!in_array(__GET("common"),LoadMapAppear($this))) {
			print('<div style="margin:15px">not appeared or not exist</div>');
			return false;
		}
		list($land,$monster_list)	= LandInformation($land_id);
		if(!$land || !$monster_list) {
			print('<div style="margin:15px">fail to load</div>');
			return false;
		}

		print('<div style="margin:15px">');
		ShowError($message);
		print('<span class="bold">'.$land["name"].'</span>');
		print('<h4>팀</h4></div>');
		print('<form action="'.INDEX.'?common='.__GET("common").'" method="post">');
		$this->ShowCharacters($this->char,"CHECKBOX",explode("<>",(isset($this->party_memo) ? $this->party_memo : '')));

print <<<P12
	<div style="margin:15px;text-align:center">
	<input type="submit" class="btn" name="monster_battle_multiply" value="대결! (테스트 중) (실패하면 클릭하지 마세요)">
	<input type="submit" class="btn" name="monster_battle" value="대결!">
	<input type="reset" class="btn" value="다시 놓기"><br>
	이 팀을 기억하기 : <input type="checkbox" name="memory_party" value="1">
	</div></form>
P12;
		include(DATA_MONSTER);
		include(CLASS_MONSTER);
		foreach($monster_list as $id =>$val) {
			if(isset($val[1]))
				$monster[]	= new monster(CreateMonster($id));
		}
		print('<div style="margin:15px"><h4>MonsterAppearance</h4></div>');
		$this->ShowCharacters($monster,"MONSTER",$land["land"]);
	}


	function TimeCostCalc() {
		return NORMAL_BATTLE_TIME;
	}


	function MonsterBattle() {
		$MyParty = array();
		if(__POST("monster_battle") || __POST("monster_battle_multiply")) {
			$this->MemorizeParty();
			include_once(DATA_LAND_APPEAR);
			$land	= LoadMapAppear($this);
			if(!in_array(__GET("common"),$land)) {
				ShowError("지도가 나타나지 않습니다","margin15");
				return false;
			}

			if($this->time < NORMAL_BATTLE_TIME) {
				ShowError("시간 부족(필요한 시간 : ".NORMAL_BATTLE_TIME.")","margin15");
				return false;
			}
			foreach($this->char as $key => $val) {
				if(__POST("char_".$key))
					$MyParty[]	= $this->char[$key];
			}
			if( __count($MyParty) === 0) {
				ShowError('전투에는 최소 한 명 이상이 참여해야 합니다.',"margin15");
				return false;
			} else if(5 < __count($MyParty)) {
				ShowError('최대 5명까지 전투에 참여할 수 있습니다.',"margin15");
				return false;
			}
			include(DATA_LAND);
			include(DATA_MONSTER);
			list($Land,$MonsterList)	= LandInformation(__GET("common"));
			$EneNum	= $this->EnemyNumber($MyParty);
			$EnemyParty	= $this->EnemyParty($EneNum,$MonsterList);

			$this->WasteTime(NORMAL_BATTLE_TIME);
			include(CLASS_BATTLE);
			$battle	= new battle($MyParty,$EnemyParty);
			$battle->SetBackGround($Land["land"]);
			$battle->SetTeamName($this->name,$Land["name"]);
			$battle->Process();
			$battle->SaveCharacters();
			list($UserMoney)	= $battle->ReturnMoney();
			$this->GetMoney($UserMoney);
			if($this->record_btl_log)
				$battle->RecordLog();

			if($itemdrop	= $battle->ReturnItemGet(0)) {
				$this->LoadUserItem();
				foreach($itemdrop as $itemno => $amount)
					$this->AddItem($itemno,$amount);
				$this->SaveUserItem();
			}
			return true;
		}
	}


	function ItemProcess() {
		// ㅇㅅㅇ...?
	}


	function ItemShow() {
print <<<P13
		<div style="margin:15px">
		<h4>아이템</h4>
		<div style="margin:0 20px">
P13;
		if($this->item) {
			include(CLASS_JS_ITEMLIST);
			$goods	= new JS_ItemList();
			$goods->SetID("my");
			$goods->SetName("type");
			if($this->no_JS_itemlist)
				$goods->NoJS();
			foreach($this->item as $no => $val) {
				$item	= LoadItemData($no);
				$string	= ShowItemDetail($item,$val,1)."<br />";
				$goods->AddItem($item,$string);
			}
			print($goods->GetJavaScript("list"));
			print($goods->ShowSelect());
			print('<div id="list">'.$goods->ShowDefault().'</div>');
		} else {
			print("No items.");
		}
		print("</div></div>");
	}

	function ShopHeader() {
		$temp13 = IMG_CHAR;
print <<<P14
<div style="margin:15px">
<h4>상점</h4>

<div style="width:600px">
<div style="float:left;width:50px;">
<img src="{$temp13}ori_002.gif" />
</div>
<div style="float:right;width:550px;">
상점에 오신걸 환영 합니다ㅡ<br />
<a href="?menu=buy">사기</a> / <a href="?menu=sell">팔기</a><br />
<a href="?menu=work">일하기</a>
</div>
<div style="clear:both"></div>
</div>

</div>
P14; 
	}

	function ShopProcess() {
		switch(true) {
			case(__POST("partjob")):
				if($this->WasteTime(100)) {
					$this->GetMoney(500);
					ShowResult("공작".MoneyFormat(500)." 굉장하다!(?)","margin15");
					return true;
				} else {
					ShowError("시간이 없다. 일하는 아무것도 아깝다. (?)","margin15");
					return false;
				}
			case(__POST("shop_buy")):
				$ShopList	= ShopList();
				if(__POST("item_no") && in_array(__POST("item_no"),$ShopList)) {
					if(preg_match("/^[0-9]/",__POST("Amount"))) {
						$amount	= (int)__POST("Amount");
						if($amount == 0)
							$amount	= 1;
					} else {
						$amount	= 1;
					}
					$item	= LoadItemData(__POST("item_no"));
					$need	= $amount * $item["buy"];
					if($this->TakeMoney($need)) {
						$this->AddItem(__POST("item_no"),$amount);
						$this->SaveUserItem();
						if(1 < $amount) {
							$img	= "<img src=\"".IMG_ICON.$item[img]."\" class=\"vcent\" />";
							ShowResult("{$img}{$item[name]}  {$amount}개 구매 (".MoneyFormat($item["buy"])." x{$amount} = ".MoneyFormat($need).")","margin15");
							return true;
						} else {
							$img	= "<img src=\"".IMG_ICON.$item[img]."\" class=\"vcent\" />";
							ShowResult("{$img}{$item[name]}개 구매 (".MoneyFormat($need).")","margin15");
							return true;
						}
					} else {
						ShowError("금액 부족(".MoneyFormat($need)." 필요)","margin15");
						return false;
					}
				}
				break;
			case(__POST("shop_sell")):
				if(__POST("item_no") && $this->item[__POST("item_no")]) {
					if(preg_match("/^[0-9]/",__POST("Amount"))) {
						$amount	= (int)__POST("Amount");
						if($amount == 0)
							$amount	= 1;
					} else {
						$amount	= 1;
					}
					$DeletedAmount	= $this->DeleteItem(__POST("item_no"),$amount);
					$item	= LoadItemData(__POST("item_no"));
					$price	= (isset($item["sell"]) ? $item["sell"] : round($item["buy"]*SELLING_PRICE));
					$this->GetMoney($price*$DeletedAmount);
					$this->SaveUserItem();
					if($DeletedAmount != 1)
						$add	= " x{$DeletedAmount}";
					$img	= "<img src=\"".IMG_ICON.$item[img]."\" class=\"vcent\" />";
					ShowResult("{$img}{$item[name]}{$add}".MoneyFormat($price*$DeletedAmount)." 에 판매","margin15");
					return true;
				}
				break;
		}
	}

	function ShopShow($message=NULL) {
		$temp14 = ShowError($message);
print <<<P15
	<div style="margin:15px">
	{$temp14}
	<h4>Goods List</h4>
	<div style="margin:0 20px">
P15;
		include(CLASS_JS_ITEMLIST);
		$ShopList	= ShopList();

		$goods	= new JS_ItemList();
		$goods->SetID("JS_buy");
		$goods->SetName("type_buy");
		if($this->no_JS_itemlist)
			$goods->NoJS();
		foreach($ShopList as $no) {
			$item	= LoadItemData($no);
			$string	= '<input type="radio" name="item_no" value="'.$no.'" class="vcent">';
			$string	.= "<span style=\"padding-right:10px;width:10ex\">".MoneyFormat($item["buy"])."</span>".ShowItemDetail($item,false,1)."<br />";
			$goods->AddItem($item,$string);
		}
		print($goods->GetJavaScript("list_buy"));
		print($goods->ShowSelect());

		print('<form action="?shop" method="post">'."\n");
		print('<div id="list_buy">'.$goods->ShowDefault().'</div>'."\n");
		print('<input type="submit" class="btn" name="shop_buy" value="사기">'."\n");
		print('Amount <input type="text" name="amount" style="width:60px" class="text vcent">(input if 2 or more)<br />'."\n");
		print('<input type="hidden" name="shop_buy" value="1">');
		print('</form></div>'."\n");

		print("<h4>My Items<a name=\"sell\"></a></h4>\n");
		print('<div style="margin:0 20px">'."\n");
		if($this->item) {
			$goods	= new JS_ItemList();
			$goods->SetID("JS_sell");
			$goods->SetName("type_sell");
			if($this->no_JS_itemlist)
				$goods->NoJS();
			foreach($this->item as $no => $val) {
				$item	= LoadItemData($no);
				$price	= (isset($item["sell"]) ? $item["sell"] : round($item["buy"]*SELLING_PRICE));
				$string	= '<input type="radio" class="vcent" name="item_no" value="'.$no.'">';
				$string	.= "<span style=\"padding-right:10px;width:10ex\">".MoneyFormat($price)."</span>".ShowItemDetail($item,$val,1)."<br />";
				$head	= '<input type="radio" name="item_no" value="'.$no.'" class="vcent">'.MoneyFormat($item["buy"]);
				$goods->AddItem($item,$string);
			}
			print($goods->GetJavaScript("list_sell"));
			print($goods->ShowSelect());
	
			print('<form action="?shop" method="post">'."\n");
			print('<div id="list_sell">'.$goods->ShowDefault().'</div>'."\n");
			print('<input type="submit" class="btn" name="shop_sell" value="Sell">');
			print('Amount <input type="text" name="amount" style="width:60px" class="text vcent">(input if 2 or more)'."\n");
			print('<input type="hidden" name="shop_sell" value="1">');
			print('</form>'."\n");
		} else {
			print("No items");
		}
		print("</div>\n");
		$temp15 = MoneyFormat("500");
print <<<P16
<form action="?shop" method="post">
<h4>일하기</h4>
<div style="margin:0 20px">
가게에서 타공하여 돈을 얻는다...<br />
<input type="submit" class="btn" name="partjob" value="일하기">
Get {$temp15} for 100Time.
</form></div></div>
P16;
	}


	function ShopBuyProcess() {
		if(!__POST("ItemBuy"))
			return false;

		print("<div style=\"margin:15px\">");
		print("<table cellspacing=\"0\">\n");
		print('<tr><td class="td6" style="text-align:center">가격</td>'.
		'<td class="td6" style="text-align:center">갯수</td>'.
		'<td class="td6" style="text-align:center">합계</td>'.
		'<td class="td6" style="text-align:center">아이템</td></tr>'."\n");
		$moneyNeed	= 0;
		$ShopList	= ShopList();
		foreach($ShopList as $itemNo) {
			if(!__POST("check_".$itemNo))
				continue;
			$item	= LoadItemData($itemNo);
			if(!$item) continue;
			$amount	= (int)__POST("amount_".$itemNo);
			if($amount < 0)
				$amount	= 0;
			
			$buyPrice	= $item["buy"];
			$Total	= $amount * $buyPrice;
			$moneyNeed	+= $Total;
			print("<tr><td class=\"td7\">");
			print(MoneyFormat($buyPrice)."\n");
			print("</td><td class=\"td7\">");
			print("x {$amount}\n");
			print("</td><td class=\"td7\">");
			print("= ".MoneyFormat($Total)."\n");
			print("</td><td class=\"td8\">");
			print(ShowItemDetail($item)."\n");
			print("</td></tr>\n");
			$this->AddItem($itemNo,$amount);
		}
		print("<tr><td colspan=\"4\" class=\"td8\">합계 : ".MoneyFormat($moneyNeed)."</td></tr>");
		print("</table>\n");
		print("</div>");
		if($this->TakeMoney($moneyNeed)) {
			$this->SaveUserItem();
			return true;
		} else {
			ShowError("당신은 충분한 돈이 없습니다.","margin15");
			return false;
		}
	}

	function ShopBuyShow() {
		print('<div style="margin:15px">'."\n");
		print("<h4>구매하기</h4>\n");

print <<< JS_HTML
<script type="text/javascript">
<!--
function toggleCSS(id) {
Element.toggleClassName('i'+id+'a', 'tdToggleBg');
Element.toggleClassName('i'+id+'b', 'tdToggleBg');
Element.toggleClassName('i'+id+'c', 'tdToggleBg');
Element.toggleClassName('i'+id+'d', 'tdToggleBg');
Field.focus('text_'+id);
}
function toggleCheckBox(id) {
if($('check_'+id).checked) {
  $('check_'+id).checked = false;
} else {
  $('check_'+id).checked = true;
  Field.focus('text_'+id);
}
toggleCSS(id);
}
// -->
</script>
JS_HTML;

		print('<form action="?menu=buy" method="post">'."\n");
		print("<table cellspacing=\"0\">\n");
		print('<tr><td class="td6"></td>'.
		'<td style="text-align:center" class="td6">가격</td>'.
		'<td style="text-align:center" class="td6">갯수</td>'.
		'<td style="text-align:center" class="td6">아이템</td></tr>'."\n");
		$ShopList	= ShopList();
		foreach($ShopList as $itemNo) {
			$item	= LoadItemData($itemNo);
			if(!$item) continue;
			print("<tr><td class=\"td7\" id=\"i{$itemNo}a\">\n");
			print('<input type="checkbox" name="check_'.$itemNo.'" value="1" onclick="toggleCSS(\''.$itemNo.'\')">'."\n");
			print("</td><td class=\"td7\" id=\"i{$itemNo}b\" onclick=\"toggleCheckBox('{$itemNo}')\">\n");
			$price	= $item["buy"];
			print(MoneyFormat($price));
			print("</td><td class=\"td7\" id=\"i{$itemNo}c\">\n");
			print('<input type="text" id="text_'.$itemNo.'" name="amount_'.$itemNo.'" value="1" style="width:60px" class="text">'."\n");
			print("</td><td class=\"td8\" id=\"i{$itemNo}d\" onclick=\"toggleCheckBox('{$itemNo}')\">\n");
			print(ShowItemDetail($item));
			print("</td></tr>\n");
		}
		print("</table>\n");
		print('<input type="submit" name="ItemBuy" value="구입" class="btn">'."\n");
		print("</form>\n");

		print("</div>\n");
	}

	function ShopSellProcess() {
		if(!__POST("ItemSell"))
			return false;

		$getMoney	= 0;
		$item = array();
		$Deleted = 0;
		$sellPrice = 0;
		$Total = 0;
		
		print("<div style=\"margin:15px\">");
		print("<table cellspacing=\"0\">\n");
		print('<tr><td class="td6" style="text-align:center">가격</td>'.
		'<td class="td6" style="text-align:center">갯수</td>'.
		'<td class="td6" style="text-align:center">합계</td>'.
		'<td class="td6" style="text-align:center">아이템</td></tr>'."\n");
		foreach($this->item as $itemNo => $amountHave) {
			if(!__POST("check_".$itemNo))
				continue;
			$item	= LoadItemData($itemNo);
			if(!$item) continue;
			$amount	= (int)__POST("amount_".$itemNo);
			if($amount < 0)
				$amount	= 0;
			$Deleted	= $this->DeleteItem($itemNo,$amount);
			$sellPrice	= ItemSellPrice($item);
			$Total	= $Deleted * $sellPrice;
			$getMoney	+= $Total;
			print("<tr><td class=\"td7\">");
			print(MoneyFormat($sellPrice)."\n");
			print("</td><td class=\"td7\">");
			print("x {$Deleted}\n");
			print("</td><td class=\"td7\">");
			print("= ".MoneyFormat($Total)."\n");
			print("</td><td class=\"td8\">");
			print(ShowItemDetail($item)."\n");
			print("</td></tr>\n");
		}
		print("<tr><td colspan=\"4\" class=\"td8\">합계 : ".MoneyFormat($getMoney)."</td></tr>");
		print("</table>\n");
		print("</div>");
		$this->SaveUserItem();
		$this->GetMoney($getMoney);
		return true;
	}

	function ShopSellShow() {
		print('<div style="margin:15px">'."\n");
		print("<h4>매각하기</h4>\n");

print <<< JS_HTML
<script type="text/javascript">
<!--
function toggleCSS(id) {
Element.toggleClassName('i'+id+'a', 'tdToggleBg');
Element.toggleClassName('i'+id+'b', 'tdToggleBg');
Element.toggleClassName('i'+id+'c', 'tdToggleBg');
Element.toggleClassName('i'+id+'d', 'tdToggleBg');
Field.focus('text_'+id);
}
function toggleCheckBox(id) {
if($('check_'+id).checked) {
  $('check_'+id).checked = false;
} else {
  $('check_'+id).checked = true;
  Field.focus('text_'+id);
}
toggleCSS(id);
}
// -->
</script>
JS_HTML;

		print('<form action="?menu=sell" method="post">'."\n");
		print("<table cellspacing=\"0\">\n");
		print('<tr><td class="td6"></td>'.
		'<td style="text-align:center" class="td6">가격</td>'.
		'<td style="text-align:center" class="td6">갯수</td>'.
		'<td style="text-align:center" class="td6">아이템</td></tr>'."\n");
		foreach($this->item as $itemNo => $amount) {
			$item	= LoadItemData($itemNo);
			if(!$item) continue;
			print("<tr><td class=\"td7\" id=\"i{$itemNo}a\">\n");
			print('<input type="checkbox" name="check_'.$itemNo.'" value="1" onclick="toggleCSS(\''.$itemNo.'\')">'."\n");
			print("</td><td class=\"td7\" id=\"i{$itemNo}b\" onclick=\"toggleCheckBox('{$itemNo}')\">\n");
			$price	= ItemSellPrice($item);
			print(MoneyFormat($price));
			print("</td><td class=\"td7\" id=\"i{$itemNo}c\">\n");
			print('<input type="text" id="text_'.$itemNo.'" name="amount_'.$itemNo.'" value="'.$amount.'" style="width:60px" class="text">'."\n");
			print("</td><td class=\"td8\" id=\"i{$itemNo}d\" onclick=\"toggleCheckBox('{$itemNo}')\">\n");
			print(ShowItemDetail($item,$amount));
			print("</td></tr>\n");
		}
		print("</table>\n");
		print('<input type="submit" name="ItemSell" value="Sell" class="btn" />'."\n");
		print('<input type="hidden" name="ItemSell" value="1" />'."\n");
		print("</form>\n");

		print("</div>\n");
	}

	function WorkProcess() { //이거 왜 삭제된거
		if(__POST("amount")) {
			$amount	= (int)__POST("amount");
			if(0 < $amount && $amount < 11) {
				$time	= $amount * 100;
				$money	= $amount * 500;
				if($this->WasteTime($time)) {
					ShowResult(MoneyFormat($money)." 를 얻었다!","margin15");
					$this->GetMoney($money);
					return true;
				} else {
					ShowError("시간이 부족합니다.","margin15");
					return false;
				}
			}
		}
	}

	function WorkShow() {
		$temp16 = MoneyFormat(500);
print <<<P17
<div style="margin:15px">
<h4>파트타임 일자리!</h4>
<form method="post" action="?menu=work">
<p>1회 100Time<br />
급부 : {$temp16}/회</p>
<select name="amount">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
</select><br />
<input type="submit" value="일하기" class="btn"/>
</form>
</div>
P17;
	}

	function RankProcess(&$Ranking) {

		if(__POST("ChallengeRank")) {
			if(!$this->party_rank) {
				ShowError("팀 설정 안 함","margin15");
				return false;
			}
			$result	= $this->CanRankBattle();
			if(is_array($result)) {
				ShowError("아직 기다려야 (?)","margin15");
				return false;
			}

			$Result	= $Ranking->Challenge($this);

			return $Result;
		}

		if(__POST("SetRankTeam")) {
			$now	= time();
			$checked = [];
			if(($now - $this->rank_set_time) < RANK_TEAM_SET_TIME) {
				$left	= RANK_TEAM_SET_TIME - ($now - $this->rank_set_time);
				$day	= floor($left / 3600 / 24);
				$hour	= floor($left / 3600)%24;
				$min	= floor(($left % 3600)/60);
				$sec	= floor(($left % 3600)%60);
				ShowError("팀을 재설정하는 데 {$day}일, {$hour}시간, {$min}분, {$sec}초가 소요됩니다.","margin15");
				return false;
			}
			foreach($this->char as $key => $val) {
				if(__POST("char_".$key))
					$checked[]	= $key;
			}
			if(__count($checked) == 0 || 5 < __count($checked)) {
				ShowError("팀 규모는 1명 이상 5명 이하로 구성되어야 합니다.","margin15");
				return false;
			}

			$this->party_rank	= implode("<>",$checked);
			$this->rank_set_time	= $now;
			ShowResult("팀 설정 완료","margin15");
			return true;
		}
	}

	function RankShow(&$Ranking) {
		$message = "";
		$disableRB = false;
		$left_mes = "";
		$disable = "";
		$now	= time();
		if( ($now - $this->rank_set_time) < RANK_TEAM_SET_TIME) {
			$left	= RANK_TEAM_SET_TIME - ($now - $this->rank_set_time);
			$hour	= floor($left / 3600);
			$min	= floor(($left % 3600)/60);
			$left_mes	= "<div class=\"bold\">{$hour}Hour {$min}minutes left to set again.</div>\n";
			$disable	= " disabled";
		}
		$temp17 = ShowError($message);
print <<<P18
	<div style="margin:15px">
	{$temp17}
	<form action="?menu=rank" method="post">
	<h4>랭킹(Ranking) - <a href="?rank">순위 보기</a> <a href="?manual#ranking" target="_blank" class="a0">?</a></h4>
P18;

		$CanRankBattle	= $this->CanRankBattle();
		if($CanRankBattle !== true) {
			print('<p>Time left to Next : <span class="bold">');
			print($CanRankBattle[0].":".sprintf("%02d",$CanRankBattle[1]).":".sprintf("%02d",$CanRankBattle[2]));
			print("</span></p>\n");
			$disableRB	= " disabled";
		}

		print("<div style=\"width:100%;padding-left:30px\">\n");
		print("<div style=\"float:left;width:50%\">\n");
		print("<div class=\"u\">TOP 5</div>\n");
		$Ranking->ShowRanking(0,4);
		print("</div>\n");
		print("<div style=\"float:right;width:50%\">\n");
		print("<div class=\"u\">NEAR 5</div>\n");
		$Ranking->ShowRankingRange($this->id,5);
		print("</div>\n");
		print("<div style=\"clear:both\"></div>\n");
		print("</div>\n");

		$temp19 = floor(RANK_TEAM_SET_TIME/(60*60));
print <<<P19_A
	<input type="submit" class="btn" value="도전!!" name="ChallengeRank" style="width:160px"{$disableRB} />
	</form>
	<form action="?menu=rank" method="post">
	<h4>팀 설정(Team Setting)</h4>
	<p>순위전대 설정.<br />
	여기에 순위전대를 배치합니다.</p>
	</div>
	P19_A;
	if(isset($this->party_rank)) $this->ShowCharacters($this->char,'CHECKBOX',explode("<>",$this->party_rank));
print <<<P19_B
	<div style="margin:15px">
	{$left_mes}
	<input type="submit" class="btn" style="width:160px" value="대열 설정" {$disable} />
	<input type="hidden" name="SetRankTeam" value="1" />
	<p>설정 후 {$temp19}시간 후에 재설정이 가능합니다.<br />Team setting disabled after {$temp19}hours once set.</p>
	</form>
	</div>
P19_B;
	}

	function RecruitProcess() {

		if( MAX_CHAR <= __count($this->char) )
			return false;

		include(DATA_BASE_CHAR);
		if(__POST("recruit")) {
			switch(__POST("recruit_no")) {
				case "1": $hire = 2000; $charNo	= 1; break;
				case "2": $hire = 2000; $charNo	= 2; break;
				case "3": $hire = 2500; $charNo	= 3; break;
				case "4": $hire = 4000; $charNo	= 4; break;
				default:
					ShowError("선택 반전","margin15");
					return false;
			}
			if(__POST("recruit_name")) {
				if(is_numeric(strpos(__POST("recruit_name"),"\t")))
					return "error.";
				$name	= trim(__POST("recruit_name"));
				$name	= stripslashes($name);
				$len	= strlen($name);
				if ( 0 == $len || 16 < $len ) {
					ShowError("이름이 너무 짧거나 깁니다","margin15");
					return false;
				}
				$name	= htmlspecialchars($name,ENT_QUOTES);
			} else {
				ShowError("이름은 비워 둘 수 없습니다.","margin15");
				return false;
			}
			if( !__POST("recruit_gend") ) {
				ShowError("선택되지 않은 성별","margin15");
				return false;
			} else {
				$Gender	= __POST("recruit_gend")?"♀":"♂";
			}
			
			$plus	= array("name"=>"$name","gender"=>__POST("recruit_gend"));
			$char	= new char();
			$char->SetCharData(array_merge(BaseCharStatus($charNo),$plus));
			if($hire <= $this->money) {
				$this->TakeMoney($hire);
			} else {
				ShowError("당신은 충분한 돈이 없습니다.","margin15");
				return false;
			}
			$char->SaveCharData($this->id);
			ShowResult($char->Name()."($char->job_name:{$Gender}) 동반자 추가!","margin15");
			return true;
		}
	}


	function RecruitShow() {
		if( MAX_CHAR <= $this->CharCount() ) {

print <<<P20
	<div style="margin:15px">
	<p>Maximum characters.<br>
	Need to make a space to recruit new character.</p>
	<p>글자 수 제한에 도달했습니다.<br>
	새로운 사람을 채용하기 위한 공간을 추가하고 있습니다(?).</p>
	</div>
P20;
			return false;
		}
		include_once(CLASS_MONSTER);
		$char[0]	= new char();
		$char[0]->SetCharData(array_merge(BaseCharStatus("1"),array("gender"=>"0")));
		$char[1]	= new char();
		$char[1]->SetCharData(array_merge(BaseCharStatus("1"),array("gender"=>"1")));
		$char[2]	= new char();
		$char[2]->SetCharData(array_merge(BaseCharStatus("2"),array("gender"=>"0")));
		$char[3]	= new char();
		$char[3]->SetCharData(array_merge(BaseCharStatus("2"),array("gender"=>"1")));
		$char[4]	= new char();
		$char[4]->SetCharData(array_merge(BaseCharStatus("3"),array("gender"=>"0")));
		$char[5]	= new char();
		$char[5]->SetCharData(array_merge(BaseCharStatus("3"),array("gender"=>"1")));
		$char[6]	= new char();
		$char[6]->SetCharData(array_merge(BaseCharStatus("4"),array("gender"=>"0")));
		$char[7]	= new char();
		$char[7]->SetCharData(array_merge(BaseCharStatus("4"),array("gender"=>"1")));

		$temp22 = MoneyFormat(2000);
		$temp25 = MoneyFormat(2000);
		$temp28 = MoneyFormat(2500);
		$temp31 = MoneyFormat(4000);
print <<<P21_A
	<form action="?recruit" method="post" style="margin:15px">
	<h4>새로운 인물의 직업</h4>
	<table cellspacing="0"><tbody><tr>
	<td class="td1" style="text-align:center">
	P21_A;
	$char[0]->ShowImage();
	$char[1]->ShowImage();
	print <<<P21_B
	<br>
	<input type="radio" name="recruit_no" value="1" style="margin:3px"><br>
	{$temp22}</td>
	<td class="td1" style="text-align:center">
	P21_B;
	$char[2]->ShowImage();
	$char[3]->ShowImage();
	print <<<P21_C
	<br>
	<input type="radio" name="recruit_no" value="2" style="margin:3px"><br>
	{$temp25}</td>
	<td class="td1" style="text-align:center">
	P21_C;
	$char[4]->ShowImage();
	$char[5]->ShowImage();
	print <<<P21_D
	<br>
	<input type="radio" name="recruit_no" value="3" style="margin:3px"><br>
	{$temp28}</td>
	<td class="td1" style="text-align:center">
	P21_D;
	$char[6]->ShowImage();
	$char[7]->ShowImage();
	print <<<P21_E
	<br>
	<input type="radio" name="recruit_no" value="4" style="margin:3px"><br>
	{$temp31}</td>
	</tr><tr>
	<td class="td4" style="text-align:center">
	전사</td>
	<td class="td5" style="text-align:center">
	법사</td>
	<td class="td4" style="text-align:center">
	성직자</td>
	<td class="td5" style="text-align:center">
	사냥꾼</td>
	</tr>
	</tbody></table>

	<h4>새로운 인물의 성별</h4>
	<table><tbody><tr><td valign="top">
	<input type="text" class="text" name="recruit_name" style="width:160px" maxlength="16"><br>
	<div style="margin:5px 0px">
	<input type="radio" class="vcent" name="recruit_gend" value="0">남
	<input type="radio" class="vcent" name="recruit_gend" value="1" style="margin-left:15px;">여</div>
	<input type="submit" class="btn" name="recruit" value="고용하기">
	<input type="hidden" class="btn" name="recruit" value="Recruit">
	</td><td valign="top">
	<p>1 to 16 letters.<br>
	Other characters count as 2.<br>
	1문자 = 2 letter.
	</p>
	</td></tr></tbody></table>
	</form>
P21_E;

	}

	function SmithyRefineHeader() {
		$temp32 = IMG_CHAR;
print <<<P22
<div style="margin:15px">
<h4>정련 공방(Refine)</h4>

<div style="width:600px">
<div style="float:left;width:80px;">
<img src="{$temp32}mon_053r.gif" />
</div>
<div style="float:right;width:520px;">
여기서 아이템 정제가 가능합니다!<br />
정련이 필요한 물건과 정련 횟수를 선택합니다.<br />
하지만 가공이 망가지면 저희는 책임지지 않습니다.<br />
동생이 관리하고 있는 <span class="bold">제작 공방</span>은 <a href="?menu=create">이쪽</a>에 있습니다.
</div>
<div style="clear:both"></div>
</div>
<h4>정제 도구<a name="refine"></a></h4>
<div style="margin:0 20px">
P22;
	}

	function SmithyRefineProcess() {
		if(!__POST("refine"))
			return false;
		if(!__POST("item_no")) {
			ShowError("Select Item.");
			return false;
		}
		if(!$item	= LoadItemData(__POST("item_no"))) {
			ShowError("Failed to load item data.");
			return false;
		}
		if(!$this->item[__POST("item_no")]) {
			ShowError("Item \"{$item[name]}\" doesn't exists.");
			return false;
		}
		if(__POST("timesA") < __POST("timesB"))
			$times	= __POST("timesB");
		else
			$times	= __POST("timesA");
		if(!$times || $times < 1 || (REFINE_LIMIT) < $times ) {
			ShowError("times?");
			return false;
		}
		include(CLASS_SMITHY);
		$obj_item	= new Item(__POST("item_no"));
		if(!$obj_item->CanRefine()) {
			ShowError("Cant refine \"{$item[name]}\"");
			return false;
		}
		$this->DeleteItem(__POST("item_no"));
		$Price	= round($item["buy"]/2);
		if( REFINE_LIMIT < ($item["refine"] + $times) ) {
			$times	= REFINE_LIMIT - $item["refine"];
		}
		$Trys	= 0;
		for($i=0; $i<$times; $i++) {
			if($this->TakeMoney($Price)) {
				$MoneySum	+= $Price;
				$Trys++;
				if(!$obj_item->ItemRefine()) {
					break;
				}
			} else {
				ShowError("Not enough money.<br />\n");
				$this->AddItem($obj_item->ReturnItem());
				break;
			}
			if($i == ($times - 1)) {
				$this->AddItem($obj_item->ReturnItem());
			}
		}
		print("Money Used : ".MoneyFormat($Price)." x ".$Trys." = ".MoneyFormat($MoneySum)."<br />\n");
		$this->SaveUserItem();
		return true;
		
	}

	function SmithyRefineShow() {
		if($this->item) {
			include(CLASS_JS_ITEMLIST);
			$possible	= CanRefineType();
			$possible	= array_flip($possible);
			$possible[key($possible)]++;

			$goods	= new JS_ItemList();
			$goods->SetID("my");
			$goods->SetName("type");

			$goods->ListTable("<table cellspacing=\"0\">");
			$goods->ListTableInsert("<tr><td class=\"td9\"></td><td class=\"align-center td9\">정련비</td><td class=\"align-center td9\">Item</td></tr>"); 

			if($this->no_JS_itemlist)
				$goods->NoJS();
			foreach($this->item as $no => $val) {
				$item	= LoadItemData($no);
				if(!$possible[$item["type"]])
					continue;
				$price	= $item["buy"]/2;

				$string	= '<tr>';
				$string	.= '<td class="td7"><input type="radio" class="vcent" name="item_no" value="'.$no.'">';
				$string	.= '</td><td class="td7">'.MoneyFormat($price).'</td><td class="td8">'.ShowItemDetail($item,$val,1)."<td>";
				$string	.= "</tr>";

				$goods->AddItem($item,$string);
			}
			print($goods->GetJavaScript("list"));
			print('정제할 수 있는 리스트');
			print($goods->ShowSelect());
			print('<form action="?menu=refine" method="post">'."\n");
			print('<input type="submit" value="Refine" name="refine" class="btn">'."\n");
			print('횟수 : <select name="timesA">'."\n");
			for($i=1; $i<11; $i++) {
				print('<option value="'.$i.'">'.$i.'</option>');
			}
			print('</select>'."\n");
			print('<div id="list">'.$goods->ShowDefault().'</div>'."\n");
			print('<input type="submit" value="Refine" name="refine" class="btn">'."\n");
			print('<input type="hidden" value="1" name="refine">'."\n");
			print('횟수 : <select name="timesB">'."\n");
			for($i=1; $i<(REFINE_LIMIT+1); $i++) {
				print('<option value="'.$i.'">'.$i.'</option>');
			}
			print('</select>'."\n");
			print('</form>'."\n");
		} else {
			print("No items<br />\n");
		}
		print("</div>\n");
		print("</div>");
	}

	function SmithyCreateHeader() {
		$temp33 = IMG_CHAR;
print <<<P23
<div style="margin:15px">
<h4>제작 공방(Create)<a name="sm"></a></h4>
<div style="width:600px">
<div style="float:left;width:80px;">
<img src="{$temp33}mon_053rz.gif" />
</div>
<div style="float:right;width:520px;">
물품 제작은 여기서 가능합니다!<br />
소재만 있으면 장비를 만들 수 있습니다.<br />
특수 소재를 넣으면 특수 무기를 만들 수 있습니다.<br />
제 동생이 관리하는 <span class="bold">정련 공방</span>은 <a href="?menu=refine">여기</a>에 있습니다.<br />
<a href="#mat">소지 소재 일람</a>
</div>
<div style="clear:both"></div>
</div>
<h4>장비 제작<a name="refine"></a></h4>
<div style="margin:0 15px">
P23;
	}

	function SmithyCreateProcess() {
		if(!__POST("Create")) return false;

		if(!__POST("ItemNo")) {
			ShowError("아이템 하나를 선택하여 만드십시오");
			return false;
		}

		if(!$item	= LoadItemData(__POST("ItemNo"))) {
			ShowError("error12291703");
			return false;
		}

		if(!HaveNeeds($item,$this->item)) {
			ShowError($item["name"]." 는 충분한 원료를 생산하지 못했습니다.");
			return false;
		}

		if(__POST("AddMaterial")) {
			if(!$this->item[__POST("AddMaterial")]) {
				ShowError("해당 소재는 추가할 수 없습니다.");
				return false;
			}
			$ADD	= LoadItemData(__POST("AddMaterial"));
			$this->DeleteItem(__POST("AddMaterial"));
		}

		$Price	= 0;
		if(!$this->TakeMoney($Price)) {
			ShowError("당신은 충분한 돈이 없습니다.".MoneyFormat($Price)." 가 필요합니다.");
			return false;
		}
		foreach($item["need"] as $M_item => $M_amount) {
			$this->DeleteItem($M_item,$M_amount);
		}
		include(CLASS_SMITHY);
		$item	= new item(__POST("ItemNo"));
		$item->CreateItem();
		if($ADD["Add"])
			$item->AddSpecial($ADD["Add"]);
		$done	= $item->ReturnItem();
		$this->AddItem($done);
		$this->SaveUserItem();

		print("<p>");
		print(ShowItemDetail(LoadItemData($done)));
		
		print("\n<br />완료되었습니다!</p>\n");
		return true;
	}

	function SmithyCreateShow() {
		$result = "";
		$head = "";
		$CanCreate	= CanCreate($this);
		include(CLASS_JS_ITEMLIST);
		$CreateList	= new JS_ItemList();
		$CreateList->SetID("create");
		$CreateList->SetName("type_create");

		$CreateList->ListTable("<table cellspacing=\"0\">");
		$CreateList->ListTableInsert("<tr><td class=\"td9\"></td><td class=\"align-center td9\">생산 비용</td><td class=\"align-center td9\">Item</td></tr>"); 

		if($this->no_JS_itemlist)
			$CreateList->NoJS();
		foreach($CanCreate as $item_no) {
			$item	= LoadItemData($item_no);
			if(!HaveNeeds($item,$this->item))
				continue;
			$CreatePrice	= 0;
			$head	= '<tr><td class="td7"><input type="radio" name="ItemNo" value="'.$item_no.'"></td>';
			$head	.= '<td class="td7">'.MoneyFormat($CreatePrice).'</td><td class="td8">'.ShowItemDetail($item,false,1,$this->item)."</td>";
			$CreateList->AddItem($item,$head);
		}
		if($head) {
			print($CreateList->GetJavaScript("list"));
			print($CreateList->ShowSelect());
			
			$temp34 = $CreateList->ShowDefault();
print <<<P24
<form action="?menu=create" method="post">
<div id="list">{$temp34}</div>
<input type="submit" class="btn" name="Create" value="만들다">
<input type="reset" class="btn" value="다시 놓기">
<input type="hidden" name="Create" value="1"><br />
P24;
		print('<div class="bold u" style="margin-top:15px">추가 자료</div>'."\n");
		for($item_no=7000; $item_no<7200; $item_no++) {
			if(!$this->item["$item_no"])
				continue;
			if($item	= LoadItemData($item_no)) {
				print('<input type="radio" name="AddMaterial" value="'.$item_no.'" class="vcent">');
				print(ShowItemDetail($item,$this->item["$item_no"],1)."<br />\n");
			}
		}
print <<<P25
<input type="submit" class="btn" name="Create" value="만들다">
<input type="reset" class="btn" value="다시 놓기">
</form>
P25;
		} else {
			print("현재 우리가 가지고 있는 자료로는 아무것도 할 수 없습니다.");
		}

		print("</div>\n");
		print("<h4>소지 소재 일람<a name=\"mat\"></a> <a href=\"#sm\">↑</a></h4>");
		print("<div style=\"margin:0 15px\">");
		for($i=6000; $i<7000; $i++) {
			if(!isset($this->item["$i"]))
				continue;
			$item	= LoadItemData($i);
			ShowItemDetail($item,$this->item["$i"]);
			print("<br />\n");
		}
		
print <<<P26
</div>
</div>
P26;
		return $result;
	}

	function AuctionJoinMember() {
		if(!__POST("JoinMember"))
			return false;
		if($this->item["9000"]) {
			return false;
		}
		if(!$this->TakeMoney(round(START_MONEY * 1.10))) {
			ShowError("당신은 충분한 돈이 없습니다.<br />\n");
			return false;
		}
		$this->AddItem(9000);
		$this->SaveUserItem();
		$this->SaveData();
		ShowResult("경매장 회원들.<br />\n");
		return true;
	}

	function AuctionEnter() {
		if(isset($this->item["9000"]))
			return true;
		else
			return false;
	}

	function AuctionHeader() {
		$temp35 = IMG_CHAR;
print <<<P27
<div style="margin:15px 0 0 15px">
<h4>경매(Auction)</h4>
<div style="margin-left:20px">

<div style="width:500px">
<div style="float:left;width:50px;">
<img src="{$temp35}ori_003.gif" />
</div>
<div style="float:right;width:450px;">
P27;

		$this->AuctionJoinMember();
		if($this->AuctionEnter()) {
			print("회원 카드를 가지고 계십니까.<br />\n");
			print("경매장에 오신 것을 환영합니다.<br />\n");
			print("<a href=\"#log\">회고 기록</a>\n");
		} else {
			print("경매에서 경매하려면 회원가입을 하셔야 합니다.<br />\n");
			print("회원 가입비는 ".MoneyFormat(round(START_MONEY * 1.10))."입니다.<br />\n");
			print("입회하시겠습니까?<br />\n");
			print('<form action="" method="post">'."\n");
			print('<input type="submit" value="가입하기" name="JoinMember" class="btn"/>'."\n");
			print("</form>\n");
		}
		if(!AUCTION_TOGGLE)
			ShowError("기능 일시 정지");
		if(!AUCTION_EXHIBIT_TOGGLE)
			ShowError("경매중지");

print <<<P28
</div>
<div style="clear:both"></div>
</div>
</div>
<h4>아이템 경매(Item Auction)</h4>
<div style="margin-left:20px">
P28;
	}

	function AuctionFoot(&$ItemAuction) {
print <<<P29_A
</div>
<a name="log"></a>
<h4>경매기록(AuctionLog)</h4>
<div style="margin-left:20px">
P29_A;
$ItemAuction->ShowLog();
print <<<P29_B
</div>
P29_B;
	}

	function AuctionItemBiddingProcess(&$ItemAuction) {
		if(!$this->AuctionEnter())
			return false;
		if(!__POST("ArticleNo"))
			return false;

		$ArticleNo	= __POST("ArticleNo");
		$BidPrice	= (int)__POST("BidPrice");
		if($BidPrice < 1) {
			ShowError("잘못된 가격을 입력했습니다.");
			return false;
		}
		if(!$ItemAuction->ItemArticleExists($ArticleNo)) {
			ShowError("이 경매품의 판매자는 확인할 수 없습니다.");
			return false;
		}
		if(!$ItemAuction->ItemBidRight($ArticleNo,$this->id)) {
			ShowError("No.".$ArticleNo." 판매자가 이미 입찰했습니까?");
			return false;
		}
		$Bottom	= $ItemAuction->ItemBottomPrice($ArticleNo);
		if($BidPrice < $Bottom) {
			ShowError("최저입찰가이하");
			ShowError("현재 입찰가 : ".MoneyFormat($BidPrice)." 최저입찰가 : ".MoneyFormat($Bottom));
			return false;
		}
		if(!$this->TakeMoney($BidPrice)) {
			ShowError("당신의 자금이 부족합니다.");
			return false;
		}

		if($ItemAuction->ItemBid($ArticleNo,$BidPrice,$this->id,$this->name)) {
			ShowResult("No:{$ArticleNo}  ".MoneyFormat($BidPrice)." 가 인수되었습니다.<br />\n");
			return true;
		}
	}

	function AuctionItemBiddingForm(&$ItemAuction) {

		if(!AUCTION_TOGGLE)
			return false;

		if($this->AuctionEnter()) {
		if(AUCTION_EXHIBIT_TOGGLE) {
				print("<form action=\"?menu=auction\" method=\"post\">\n");
				print('<input type="submit" value="경매 품목" name="ExhibitItemForm" class="btn" style="width:160px">'."\n");
				print("</form>\n");
			}
			$ItemAuction->ItemSortBy(__GET("sort"));
			$ItemAuction->ItemShowArticle2(true);

			if(AUCTION_EXHIBIT_TOGGLE) {
				print("<form action=\"?menu=auction\" method=\"post\">\n");
				print('<input type="submit" value="경매 품목" name="ExhibitItemForm" class="btn" style="width:160px">'."\n");
				print("</form>\n");
			}

		} else {
			$ItemAuction->ItemShowArticle2(false);
		}
	}

	function AuctionItemExhibitProcess(&$ItemAuction) {

		if(!AUCTION_EXHIBIT_TOGGLE)
			return "BIDFORM";

		if(!$this->AuctionEnter())
			return "BIDFORM";
		if(!__POST("PutAuction"))
			return "BIDFORM";

		if(!__POST("item_no")) {
			ShowError("Select Item.");
			return false;
		}
		$SessionLeft	= 30 - (time() - __SESSION("AuctionExhibit"));
		if(__SESSION("AuctionExhibit") && 0 < $SessionLeft) {
			ShowError("Wait {$SessionLeft}seconds to ReExhibit.");
			return false;
		}
		if(AUCTION_MAX <= $ItemAuction->ItemAmount()) {
			ShowError("경매 수량이 이미 한계에 도달했습니다.(".$ItemAuction->ItemAmount()."/".AUCTION_MAX.")");
			return false;
		}
		if(!$this->TakeMoney(500)) {
			ShowError("Need ".MoneyFormat(500)." to exhibit auction.");
			return false;
		}
		if(!$item	= LoadItemData(__POST("item_no"))) {
			ShowError("Failed to load item data.");
			return false;
		}
		if(!$this->item[__POST("item_no")]) {
			ShowError("Item \"{$item[name]}\" doesn't exists.");
			return false;
		}
		$possible	= CanExhibitType();
		if(!$possible[$item["type"]]) {
			ShowError("Cant put \"{$item[name]}\" to the Auction");
			return false;
		}
		if(	!(	__POST("ExhibitTime") === '1' ||
				__POST("ExhibitTime") === '3' ||
				__POST("ExhibitTime") === '6' ||
				__POST("ExhibitTime") === '12' ||
				__POST("ExhibitTime") === '18' ||
				__POST("ExhibitTime") === '24') ) {
			var_dump($_POST);
			ShowError("time?");
			return false;
		}
		if(preg_match("/^[0-9]/",__POST("Amount"))) {
			$amount	= (int)__POST("Amount");
			if($amount == 0)
				$amount	= 1;
		} else {
			$amount	= 1;
		}
		$_SESSION["AuctionExhibit"]	= time();
		$amount	= $this->DeleteItem(__POST("item_no"),$amount);
		$this->SaveUserItem();

		$ItemAuction->ItemAddArticle(__POST("item_no"),$amount,$this->id,__POST("ExhibitTime"),__POST("StartPrice"),__POST("Comment"));
		print($item["name"]."에서 {$amount}개 전시되었습니다.");
		return true;
	}

	function AuctionItemExhibitForm() {

		if(!AUCTION_EXHIBIT_TOGGLE)
			return false;

		include(CLASS_JS_ITEMLIST);
		$possible	= CanExhibitType();

print <<<P30
<div class="u bold">참여 방법</div>
<ol>
<li>아이템 하나를 선택해서 경매.</li>
<li>두 개 이상 경매하려면 수량을 입력해야 합니다.</li>
<li>경매 시간을 지정합니다.</li>
<li>경매 시작가 지정(입력하지 않으면 0)</li>
<li>설명을 입력하십시오.</li>
<li>발송합니다.</li>
</ol>
<div class="u bold">주의사항</div>
<ul>
<li>경매는 $500의 수수료를 내야 합니다.</li>
<li>경매 담당자가 열심히 도와주지 않을 모양입니다.</li>
</ul>
<a href="?menu=auction">모든 경매물건 보기</a>
</div>
<h4>매각하기</h4>
<div style="margin-left:20px">
<div class="u bold">경매할 수 있는 소품</div>
P30;
		if(!$this->item) {
			print("No items<br />\n");
			return false;
		}
		$ExhibitList	= new JS_ItemList();
		$ExhibitList->SetID("auc");
		$ExhibitList->SetName("type_auc");
		if($this->no_JS_itemlist)
			$ExhibitList->NoJS();
		foreach($this->item as $no => $amount) {
			$item	= LoadItemData($no);
			if(!$possible[$item["type"]])
				continue;
			$head	= '<input type="radio" name="item_no" value="'.$no.'" class="vcent">';
			$head	.= ShowItemDetail($item,$amount,1)."<br />";
			$ExhibitList->AddItem($item,$head);
		}
		print($ExhibitList->GetJavaScript("list"));
		print($ExhibitList->ShowSelect());
		$temp37 = $ExhibitList->ShowDefault();
print <<<P31
<form action="?menu=auction" method="post">
<div id="list">{$temp37}</div>
<table><tr><td style="text-align:right">
수량(Amount) :</td><td><input type="text" name="Amount" class="text" style="width:60px" value="1" /><br />
</td></tr><tr><td style="text-align:right">
시간(Time) :</td><td>
<select name="ExhibitTime">
<option value="24" selected>24 hour</option>
<option value="18">18 hour</option>
<option value="12">12 hour</option>
<option value="6">6 hour</option>
<option value="3">3 hour</option>
<option value="1">1 hour</option>
</select>
</td></tr><tr><td>
시작 가격(Start Price) :</td><td><input type="text" name="StartPrice" class="text" style="width:240px" maxlength="10"><br />
</td></tr><tr><td style="text-align:right">
설명(Comment) :</td><td>
<input type="text" name="Comment" class="text" style="width:240px" maxlength="40">
</td></tr><tr><td></td><td>
<input type="submit" class="btn" value="Put Auction" name="PutAuction" style="width:240px"/>
<input type="hidden" name="PutAuction" value="1">
</td></tr></table>
</form>
P31;

		
	}

	function UnionProcess() {

		if($this->CanUnionBattle() !== true) {
			$host  = __SERVER('HTTP_HOST');
			$uri   = rtrim(dirname(__SERVER('PHP_SELF')));
			$extra = INDEX;
			header("Location: http://$host$uri/$extra?hunt");
			exit;
		}

		if(!__POST("union_battle"))
			return false;
		$Union	= new union();
		if(!$Union->UnionNumber(__GET("union")) || !$Union->is_Alive()) {
			return false;
		}
		$UnionMob	= CreateMonster($Union->MonsterNumber);
		$this->MemorizeParty();
		foreach($this->char as $key => $val) {
			if(__POST("char_".$key)) {
				$MyParty[]	= $this->char[$key];
				$TotalLevel	+= $this->char[$key]->level;
			}
		}
		if($UnionMob["LevelLimit"] < $TotalLevel) {
			ShowError('합계 레벨('.$TotalLevel.'/'.$UnionMob["LevelLimit"].')',"margin15");
			return false;
		}
		if( __count($MyParty) === 0) {
			ShowError('전투는 적어도 한 사람은 참가해야 합니다.',"margin15");
			return false;
		} else if(5 < __count($MyParty)) {
			ShowError('전투는 기껏해야 다섯 명만 할 수 있습니다.',"margin15");
			return false;
		}
		if(!$this->WasteTime(UNION_BATTLE_TIME)) {
			ShowError('Time Shortage.',"margin15");
			return false;
		}

		if($UnionMob["SlaveAmount"])
			$EneNum	= $UnionMob["SlaveAmount"] + 1;
		else
			$EneNum	= 5;

		if($UnionMob["SlaveSpecify"])
			$EnemyParty	= $this->EnemyParty($EneNum-1, $Union->Slave, $UnionMob["SlaveSpecify"]);
		else
			$EnemyParty	= $this->EnemyParty($EneNum-1, $Union->Slave, $UnionMob["SlaveSpecify"]);

		array_splice($EnemyParty,floor(__count($EnemyParty)/2),0,array($Union));

		$this->UnionSetTime();

		include(CLASS_BATTLE);
		$battle	= new battle($MyParty,$EnemyParty);
		$battle->SetUnionBattle();
		$battle->SetBackGround($Union->UnionLand);
		$battle->SetTeamName($this->name,$UnionMob["UnionName"]);
		$battle->Process();

		$battle->SaveCharacters();
			list($UserMoney)	= $battle->ReturnMoney();
			$this->GetMoney($UserMoney);
			$battle->RecordLog("UNION");
			if($itemdrop	= $battle->ReturnItemGet(0)) {
				$this->LoadUserItem();
				foreach($itemdrop as $itemno => $amount)
					$this->AddItem($itemno,$amount);
				$this->SaveUserItem();
			}

		return true;
	}

	function UnionShow() {
		if($this->CanUnionBattle() !== true) {
			$host  = __SERVER('HTTP_HOST');
			$uri   = rtrim(dirname(__SERVER('PHP_SELF')));
			$extra = INDEX;
			header("Location: http://$host$uri/$extra?hunt");
			exit;
		}
		print('<div style="margin:15px">'."\n");
		print("<h4>Union Monster</h4>\n");
		$Union	= new union();
		if(!$Union->UnionNumber(__GET("union")) || !$Union->is_Alive()) {
			ShowError("Defeated or not Exists.");
			return false;
		}
		print('</div>');
		$this->ShowCharacters(array($Union),false,"sea");
		print('<div style="margin:15px">'."\n");
		print("<h4>Teams</h4>\n");
		print("</div>");
		print('<form action="'.INDEX.'?union='.__GET("union").'" method="post">');
		$this->ShowCharacters($this->char,CHECKBOX,explode("<>",(isset($this->party_memo) ? $this->party_memo : '')));

print <<<P32
	<div style="margin:15px;text-align:center">
	<input type="submit" class="btn" value="대결!">
	<input type="hidden" name="union_battle" value="1">
	<input type="reset" class="btn" value="다시 놓기"><br>
	이 팀을 기억하기 : <input type="checkbox" name="memory_party" value="1">
	</div></form>
P32;
	}

	function TownShow() {
		include(DATA_TOWN);
		print('<div style="margin:15px">'."\n");
		print("<h4>거리</h4>");
		print('<div class="town">'."\n");
		print("<ul>\n");
		$PlaceList	= TownAppear($this);
		if($PlaceList["Shop"]) {

print <<<P33
<li>상점(Shop)
<ul>
<li><a href="?menu=buy">사기(Buy)</a></li>
<li><a href="?menu=sell">팔기(Sell)</a></li>
<li><a href="?menu=work">일하기</a></li>
</ul>
</li>
P33;
		}
		if($PlaceList["Recruit"])
			print("<li><p><a href=\"?recruit\">인재중재소(Recruit)</a></p></li>");
		if($PlaceList["Smithy"]) {

print <<<P34
<li>제작소(Smithy)
<ul>
<li><a href="?menu=refine">정련 공방(Refine)</a></li>
<li><a href="?menu=create">제작 공방(Create)</a></li>
</ul>
</li>
P34;
		}
		if($PlaceList["Auction"] && AUCTION_TOGGLE)
			print("<li><a href=\"?menu=auction\">경매장(Auction)</li>");
		if($PlaceList["Colosseum"])
			print("<li><a href=\"?menu=rank\">경기장(Colosseum)</a></li>");
		print("</ul>\n");
		print("</div>\n");
		print("<h4>광장</h4>");
		$this->TownBBS();
		print("</div>\n");
	}


	function TownBBS() {
		$file	= BBS_TOWN;
print <<<P35
<form action="?town" method="post">
<input type="text" maxlength="60" name="message" class="text" style="width:300px"/>
<input type="submit" value="post" class="btn" style="width:100px" />
</form>
P35;

		if(!file_exists($file))
			return false;
		$log	= file($file);
		if(__POST("message") && strlen(__POST("message")) < 121) {
			$_POST["message"]	= htmlspecialchars(__POST("message"),ENT_QUOTES);
			$_POST["message"]	= stripslashes(__POST("message"));

			$name	= "<span class=\"bold\">{$this->name}</span>";
			$message	= $name." > ".__POST("message");
			if($this->UserColor)
				$message	= "<span style=\"color:{$this->UserColor}\">".$message."</span>";
			$message	.= " <span class=\"light\">(".date("Mj G:i").")</span>\n";
			array_unshift($log,$message);
			while(50 < __count($log))
				array_pop($log);
			WriteFile($file,implode(null,$log));
		}
		foreach($log as $mes)
			print(nl2br($mes));
	}

	function SettingProcess() {
		if(__POST("NewName")) {
			$NewName	= __POST("NewName");
			if(is_numeric(strpos($NewName,"\t"))) {
				ShowError('error1');
				return false;
			}
			$NewName	= trim($NewName);
			$NewName	= stripslashes($NewName);
			if (!$NewName) {
				ShowError('Name is blank.');
				return false;
			}
			$length	= strlen($NewName);
			if ( 0 == $length || 16 < $length) {
				ShowError('1 to 16 letters?');
				return false;
			}
			$userName	= userNameLoad();
			if(in_array($NewName,$userName)) {
				ShowError("해당 이름은 이미 사용되고 있습니다.","margin15");
				return false;
			}
			if(!$this->TakeMoney(NEW_NAME_COST)) {
				ShowError('money not enough');
				return false;
			}
			$OldName	= $this->name;
			$NewName	= htmlspecialchars($NewName,ENT_QUOTES);
			if($this->ChangeName($NewName)) {
				ShowResult("Name Changed ({$OldName} -> {$NewName})","margin15");
				userNameAdd($NewName);
				return true;
			} else {
				ShowError("?");
				return false;
			}
		}

		if(__POST("setting01")) {
			if(__POST("record_battle_log"))
				$this->record_btl_log	= 1;
			else
				$this->record_btl_log	= false;

			if(__POST("no_JS_itemlist"))
				$this->no_JS_itemlist	= 1;
			else
				$this->no_JS_itemlist	= false;
		}
		if(__POST("color")) {
			if(	strlen(__POST("color")) != 6 &&
				!preg_match("/^[0369cf]{6}/",__POST("color")))
				return "error 12072349";
			$this->UserColor	= __POST("color");
			ShowResult("Setting changed.","margin15");
			return true;
		}
	}

	function SettingShow() {
		$record_btl_log = "";
		$no_JS_itemlist = "";
		$Result = "";
		print('<div style="margin:15px">'."\n");
		if($this->record_btl_log) $record_btl_log	= " checked";
		if($this->no_JS_itemlist) $no_JS_itemlist	= " checked";
		
		$temp38 = INDEX;
		$temp39 = MoneyFormat(NEW_NAME_COST);
print <<<P36
<h4>설정</h4>
<form action="?setting" method="post">
<table><tbody>
<tr><td><input type="checkbox" name="record_battle_log" value="1" {$record_btl_log}></td><td>전투 기록</td></tr>
<tr><td><input type="checkbox" name="no_JS_itemlist" value="1" {$no_JS_itemlist}></td><td>자바스크립트 없이 아이템 목록 만들기</td></tr>
</tbody></table>
색상 : 
<SELECT class=bgcolor name=color>
<OPTION style="COLOR: #ffffff" value=ffffff selected>SampleColor</OPTION>
<OPTION style="COLOR: #ffffcc" value=ffffcc>SampleColor</OPTION>
<OPTION style="COLOR: #ffff99" value=ffff99>SampleColor</OPTION>
<OPTION style="COLOR: #ffff66" value=ffff66>SampleColor</OPTION>
<OPTION style="COLOR: #ffff33" value=ffff33>SampleColor</OPTION>
<OPTION style="COLOR: #ffff00" value=ffff00>SampleColor</OPTION>
<OPTION style="COLOR: #ffccff" value=ffccff>SampleColor</OPTION>
<OPTION style="COLOR: #ffcccc" value=ffcccc>SampleColor</OPTION>
<OPTION style="COLOR: #ffcc99" value=ffcc99>SampleColor</OPTION>
<OPTION style="COLOR: #ffcc66" value=ffcc66>SampleColor</OPTION>
<OPTION style="COLOR: #ffcc33" value=ffcc33>SampleColor</OPTION>
<OPTION style="COLOR: #ffcc00" value=ffcc00>SampleColor</OPTION>
<OPTION style="COLOR: #ff99ff" value=ff99ff>SampleColor</OPTION>
<OPTION style="COLOR: #ff99cc" value=ff99cc>SampleColor</OPTION>
<OPTION style="COLOR: #ff9999" value=ff9999>SampleColor</OPTION>
<OPTION style="COLOR: #ff9966" value=ff9966>SampleColor</OPTION>
<OPTION style="COLOR: #ff9933" value=ff9933>SampleColor</OPTION>
<OPTION style="COLOR: #ff9900" value=ff9900>SampleColor</OPTION>
<OPTION style="COLOR: #ff66ff" value=ff66ff>SampleColor</OPTION>
<OPTION style="COLOR: #ff66cc" value=ff66cc>SampleColor</OPTION>
<OPTION style="COLOR: #ff6699" value=ff6699>SampleColor</OPTION>
<OPTION style="COLOR: #ff6666" value=ff6666>SampleColor</OPTION>
<OPTION style="COLOR: #ff6633" value=ff6633>SampleColor</OPTION>
<OPTION style="COLOR: #ff6600" value=ff6600>SampleColor</OPTION>
<OPTION style="COLOR: #ff33ff" value=ff33ff>SampleColor</OPTION>
<OPTION style="COLOR: #ff33cc" value=ff33cc>SampleColor</OPTION>
<OPTION style="COLOR: #ff3399" value=ff3399>SampleColor</OPTION>
<OPTION style="COLOR: #ff3366" value=ff3366>SampleColor</OPTION>
<OPTION style="COLOR: #ff3333" value=ff3333>SampleColor</OPTION>
<OPTION style="COLOR: #ff3300" value=ff3300>SampleColor</OPTION>
<OPTION style="COLOR: #ff00ff" value=ff00ff>SampleColor</OPTION>
<OPTION style="COLOR: #ff00cc" value=ff00cc>SampleColor</OPTION>
<OPTION style="COLOR: #ff0099" value=ff0099>SampleColor</OPTION>
<OPTION style="COLOR: #ff0066" value=ff0066>SampleColor</OPTION>
<OPTION style="COLOR: #ff0033" value=ff0033>SampleColor</OPTION>
<OPTION style="COLOR: #ff0000" value=ff0000>SampleColor</OPTION>
<OPTION style="COLOR: #ccffff" value=ccffff>SampleColor</OPTION>
<OPTION style="COLOR: #ccffcc" value=ccffcc>SampleColor</OPTION>
<OPTION style="COLOR: #ccff99" value=ccff99>SampleColor</OPTION>
<OPTION style="COLOR: #ccff66" value=ccff66>SampleColor</OPTION>
<OPTION style="COLOR: #ccff33" value=ccff33>SampleColor</OPTION>
<OPTION style="COLOR: #ccff00" value=ccff00>SampleColor</OPTION>
<OPTION style="COLOR: #ccccff" value=ccccff>SampleColor</OPTION>
<OPTION style="COLOR: #cccccc" value=cccccc>SampleColor</OPTION>
<OPTION style="COLOR: #cccc99" value=cccc99>SampleColor</OPTION>
<OPTION style="COLOR: #cccc66" value=cccc66>SampleColor</OPTION>
<OPTION style="COLOR: #cccc33" value=cccc33>SampleColor</OPTION>
<OPTION style="COLOR: #cccc00" value=cccc00>SampleColor</OPTION>
<OPTION style="COLOR: #cc99ff" value=cc99ff>SampleColor</OPTION>
<OPTION style="COLOR: #cc99cc" value=cc99cc>SampleColor</OPTION>
<OPTION style="COLOR: #cc9999" value=cc9999>SampleColor</OPTION>
<OPTION style="COLOR: #cc9966" value=cc9966>SampleColor</OPTION>
<OPTION style="COLOR: #cc9933" value=cc9933>SampleColor</OPTION>
<OPTION style="COLOR: #cc9900" value=cc9900>SampleColor</OPTION>
<OPTION style="COLOR: #cc66ff" value=cc66ff>SampleColor</OPTION>
<OPTION style="COLOR: #cc66cc" value=cc66cc>SampleColor</OPTION>
<OPTION style="COLOR: #cc6699" value=cc6699>SampleColor</OPTION>
<OPTION style="COLOR: #cc6666" value=cc6666>SampleColor</OPTION>
<OPTION style="COLOR: #cc6633" value=cc6633>SampleColor</OPTION>
<OPTION style="COLOR: #cc6600" value=cc6600>SampleColor</OPTION>
<OPTION style="COLOR: #cc33ff" value=cc33ff>SampleColor</OPTION>
<OPTION style="COLOR: #cc33cc" value=cc33cc>SampleColor</OPTION>
<OPTION style="COLOR: #cc3399" value=cc3399>SampleColor</OPTION>
<OPTION style="COLOR: #cc3366" value=cc3366>SampleColor</OPTION>
<OPTION style="COLOR: #cc3333" value=cc3333>SampleColor</OPTION>
<OPTION style="COLOR: #cc3300" value=cc3300>SampleColor</OPTION>
<OPTION style="COLOR: #cc00ff" value=cc00ff>SampleColor</OPTION>
<OPTION style="COLOR: #cc00cc" value=cc00cc>SampleColor</OPTION>
<OPTION style="COLOR: #cc0099" value=cc0099>SampleColor</OPTION>
<OPTION style="COLOR: #cc0066" value=cc0066>SampleColor</OPTION>
<OPTION style="COLOR: #cc0033" value=cc0033>SampleColor</OPTION>
<OPTION style="COLOR: #cc0000" value=cc0000>SampleColor</OPTION>
<OPTION style="COLOR: #99ffff" value=99ffff>SampleColor</OPTION>
<OPTION style="COLOR: #99ffcc" value=99ffcc>SampleColor</OPTION>
<OPTION style="COLOR: #99ff99" value=99ff99>SampleColor</OPTION>
<OPTION style="COLOR: #99ff66" value=99ff66>SampleColor</OPTION>
<OPTION style="COLOR: #99ff33" value=99ff33>SampleColor</OPTION>
<OPTION style="COLOR: #99ff00" value=99ff00>SampleColor</OPTION>
<OPTION style="COLOR: #99ccff" value=99ccff>SampleColor</OPTION>
<OPTION style="COLOR: #99cccc" value=99cccc>SampleColor</OPTION>
<OPTION style="COLOR: #99cc99" value=99cc99>SampleColor</OPTION>
<OPTION style="COLOR: #99cc66" value=99cc66>SampleColor</OPTION>
<OPTION style="COLOR: #99cc33" value=99cc33>SampleColor</OPTION>
<OPTION style="COLOR: #99cc00" value=99cc00>SampleColor</OPTION>
<OPTION style="COLOR: #9999ff" value=9999ff>SampleColor</OPTION>
<OPTION style="COLOR: #9999cc" value=9999cc>SampleColor</OPTION>
<OPTION style="COLOR: #999999" value=999999>SampleColor</OPTION>
<OPTION style="COLOR: #999966" value=999966>SampleColor</OPTION>
<OPTION style="COLOR: #999933" value=999933>SampleColor</OPTION>
<OPTION style="COLOR: #999900" value=999900>SampleColor</OPTION>
<OPTION style="COLOR: #9966ff" value=9966ff>SampleColor</OPTION>
<OPTION style="COLOR: #9966cc" value=9966cc>SampleColor</OPTION>
<OPTION style="COLOR: #996699" value=996699>SampleColor</OPTION>
<OPTION style="COLOR: #996666" value=996666>SampleColor</OPTION>
<OPTION style="COLOR: #996633" value=996633>SampleColor</OPTION>
<OPTION style="COLOR: #996600" value=996600>SampleColor</OPTION>
<OPTION style="COLOR: #9933ff" value=9933ff>SampleColor</OPTION>
<OPTION style="COLOR: #9933cc" value=9933cc>SampleColor</OPTION>
<OPTION style="COLOR: #993399" value=993399>SampleColor</OPTION>
<OPTION style="COLOR: #993366" value=993366>SampleColor</OPTION>
<OPTION style="COLOR: #993333" value=993333>SampleColor</OPTION>
<OPTION style="COLOR: #993300" value=993300>SampleColor</OPTION>
<OPTION style="COLOR: #9900ff" value=9900ff>SampleColor</OPTION>
<OPTION style="COLOR: #9900cc" value=9900cc>SampleColor</OPTION>
<OPTION style="COLOR: #990099" value=990099>SampleColor</OPTION>
<OPTION style="COLOR: #990066" value=990066>SampleColor</OPTION>
<OPTION style="COLOR: #990033" value=990033>SampleColor</OPTION>
<OPTION style="COLOR: #990000" value=990000>SampleColor</OPTION>
<OPTION style="COLOR: #66ffff" value=66ffff>SampleColor</OPTION>
<OPTION style="COLOR: #66ffcc" value=66ffcc>SampleColor</OPTION>
<OPTION style="COLOR: #66ff99" value=66ff99>SampleColor</OPTION>
<OPTION style="COLOR: #66ff66" value=66ff66>SampleColor</OPTION>
<OPTION style="COLOR: #66ff33" value=66ff33>SampleColor</OPTION>
<OPTION style="COLOR: #66ff00" value=66ff00>SampleColor</OPTION>
<OPTION style="COLOR: #66ccff" value=66ccff>SampleColor</OPTION>
<OPTION style="COLOR: #66cccc" value=66cccc>SampleColor</OPTION>
<OPTION style="COLOR: #66cc99" value=66cc99>SampleColor</OPTION>
<OPTION style="COLOR: #66cc66" value=66cc66>SampleColor</OPTION>
<OPTION style="COLOR: #66cc33" value=66cc33>SampleColor</OPTION>
<OPTION style="COLOR: #66cc00" value=66cc00>SampleColor</OPTION>
<OPTION style="COLOR: #6699ff" value=6699ff>SampleColor</OPTION>
<OPTION style="COLOR: #6699cc" value=6699cc>SampleColor</OPTION>
<OPTION style="COLOR: #669999" value=669999>SampleColor</OPTION>
<OPTION style="COLOR: #669966" value=669966>SampleColor</OPTION>
<OPTION style="COLOR: #669933" value=669933>SampleColor</OPTION>
<OPTION style="COLOR: #669900" value=669900>SampleColor</OPTION>
<OPTION style="COLOR: #6666ff" value=6666ff>SampleColor</OPTION>
<OPTION style="COLOR: #6666cc" value=6666cc>SampleColor</OPTION>
<OPTION style="COLOR: #666699" value=666699>SampleColor</OPTION>
<OPTION style="COLOR: #666666" value=666666>SampleColor</OPTION>
<OPTION style="COLOR: #666633" value=666633>SampleColor</OPTION>
<OPTION style="COLOR: #666600" value=666600>SampleColor</OPTION>
<OPTION style="COLOR: #6633ff" value=6633ff>SampleColor</OPTION>
<OPTION style="COLOR: #6633cc" value=6633cc>SampleColor</OPTION>
<OPTION style="COLOR: #663399" value=663399>SampleColor</OPTION>
<OPTION style="COLOR: #663366" value=663366>SampleColor</OPTION>
<OPTION style="COLOR: #663333" value=663333>SampleColor</OPTION>
<OPTION style="COLOR: #663300" value=663300>SampleColor</OPTION>
<OPTION style="COLOR: #6600ff" value=6600ff>SampleColor</OPTION>
<OPTION style="COLOR: #6600cc" value=6600cc>SampleColor</OPTION>
<OPTION style="COLOR: #660099" value=660099>SampleColor</OPTION>
<OPTION style="COLOR: #660066" value=660066>SampleColor</OPTION>
<OPTION style="COLOR: #660033" value=660033>SampleColor</OPTION>
<OPTION style="COLOR: #660000" value=660000>SampleColor</OPTION>
<OPTION style="COLOR: #33ffff" value=33ffff>SampleColor</OPTION>
<OPTION style="COLOR: #33ffcc" value=33ffcc>SampleColor</OPTION>
<OPTION style="COLOR: #33ff99" value=33ff99>SampleColor</OPTION>
<OPTION style="COLOR: #33ff66" value=33ff66>SampleColor</OPTION>
<OPTION style="COLOR: #33ff33" value=33ff33>SampleColor</OPTION>
<OPTION style="COLOR: #33ff00" value=33ff00>SampleColor</OPTION>
<OPTION style="COLOR: #33ccff" value=33ccff>SampleColor</OPTION>
<OPTION style="COLOR: #33cccc" value=33cccc>SampleColor</OPTION>
<OPTION style="COLOR: #33cc99" value=33cc99>SampleColor</OPTION>
<OPTION style="COLOR: #33cc66" value=33cc66>SampleColor</OPTION>
<OPTION style="COLOR: #33cc33" value=33cc33>SampleColor</OPTION>
<OPTION style="COLOR: #33cc00" value=33cc00>SampleColor</OPTION>
<OPTION style="COLOR: #3399ff" value=3399ff>SampleColor</OPTION>
<OPTION style="COLOR: #3399cc" value=3399cc>SampleColor</OPTION>
<OPTION style="COLOR: #339999" value=339999>SampleColor</OPTION>
<OPTION style="COLOR: #339966" value=339966>SampleColor</OPTION>
<OPTION style="COLOR: #339933" value=339933>SampleColor</OPTION>
<OPTION style="COLOR: #339900" value=339900>SampleColor</OPTION>
<OPTION style="COLOR: #3366ff" value=3366ff>SampleColor</OPTION>
<OPTION style="COLOR: #3366cc" value=3366cc>SampleColor</OPTION>
<OPTION style="COLOR: #336699" value=336699>SampleColor</OPTION>
<OPTION style="COLOR: #336666" value=336666>SampleColor</OPTION>
<OPTION style="COLOR: #336633" value=336633>SampleColor</OPTION>
<OPTION style="COLOR: #336600" value=336600>SampleColor</OPTION>
<OPTION style="COLOR: #3333ff" value=3333ff>SampleColor</OPTION>
<OPTION style="COLOR: #3333cc" value=3333cc>SampleColor</OPTION>
<OPTION style="COLOR: #333399" value=333399>SampleColor</OPTION>
<OPTION style="COLOR: #333366" value=333366>SampleColor</OPTION>
<OPTION style="COLOR: #333333" value=333333>SampleColor</OPTION>
<OPTION style="COLOR: #333300" value=333300>SampleColor</OPTION>
<OPTION style="COLOR: #3300ff" value=3300ff>SampleColor</OPTION>
<OPTION style="COLOR: #3300cc" value=3300cc>SampleColor</OPTION>
<OPTION style="COLOR: #330099" value=330099>SampleColor</OPTION>
<OPTION style="COLOR: #330066" value=330066>SampleColor</OPTION>
<OPTION style="COLOR: #330033" value=330033>SampleColor</OPTION>
<OPTION style="COLOR: #330000" value=330000>SampleColor</OPTION>
<OPTION style="COLOR: #00ffff" value=00ffff>SampleColor</OPTION>
<OPTION style="COLOR: #00ffcc" value=00ffcc>SampleColor</OPTION>
<OPTION style="COLOR: #00ff99" value=00ff99>SampleColor</OPTION>
<OPTION style="COLOR: #00ff66" value=00ff66>SampleColor</OPTION>
<OPTION style="COLOR: #00ff33" value=00ff33>SampleColor</OPTION>
<OPTION style="COLOR: #00ff00" value=00ff00>SampleColor</OPTION>
<OPTION style="COLOR: #00ccff" value=00ccff>SampleColor</OPTION>
<OPTION style="COLOR: #00cccc" value=00cccc>SampleColor</OPTION>
<OPTION style="COLOR: #00cc99" value=00cc99>SampleColor</OPTION>
<OPTION style="COLOR: #00cc66" value=00cc66>SampleColor</OPTION>
<OPTION style="COLOR: #00cc33" value=00cc33>SampleColor</OPTION>
<OPTION style="COLOR: #00cc00" value=00cc00>SampleColor</OPTION>
<OPTION style="COLOR: #0099ff" value=0099ff>SampleColor</OPTION>
<OPTION style="COLOR: #0099cc" value=0099cc>SampleColor</OPTION>
<OPTION style="COLOR: #009999" value=009999>SampleColor</OPTION>
<OPTION style="COLOR: #009966" value=009966>SampleColor</OPTION>
<OPTION style="COLOR: #009933" value=009933>SampleColor</OPTION>
<OPTION style="COLOR: #009900" value=009900>SampleColor</OPTION>
<OPTION style="COLOR: #0066ff" value=0066ff>SampleColor</OPTION>
<OPTION style="COLOR: #0066cc" value=0066cc>SampleColor</OPTION>
<OPTION style="COLOR: #006699" value=006699>SampleColor</OPTION>
<OPTION style="COLOR: #006666" value=006666>SampleColor</OPTION>
<OPTION style="COLOR: #006633" value=006633>SampleColor</OPTION>
<OPTION style="COLOR: #006600" value=006600>SampleColor</OPTION>
<OPTION style="COLOR: #0033ff" value=0033ff>SampleColor</OPTION>
<OPTION style="COLOR: #0033cc" value=0033cc>SampleColor</OPTION>
<OPTION style="COLOR: #003399" value=003399>SampleColor</OPTION>
<OPTION style="COLOR: #003366" value=003366>SampleColor</OPTION>
<OPTION style="COLOR: #003333" value=003333>SampleColor</OPTION>
<OPTION style="COLOR: #003300" value=003300>SampleColor</OPTION>
<OPTION style="COLOR: #0000ff" value=0000ff>SampleColor</OPTION>
<OPTION style="COLOR: #0000cc" value=0000cc>SampleColor</OPTION>
<OPTION style="COLOR: #000099" value=000099>SampleColor</OPTION>
<OPTION style="COLOR: #000066" value=000066>SampleColor</OPTION>
<OPTION style="COLOR: #000033" value=000033>SampleColor</OPTION>
<OPTION style="COLOR: #000000" value=000000>SampleColor</OPTION>
</SELECT><br />
<input type="submit" class="btn" name="setting01" value="수정" style="width:100px">
<input type="hidden" name="setting01" value="1">
</form>
<h4>로그아웃</h4>
<form action="{$temp38}" method="post">
<input type="submit" class="btn" name="logout" value="로그아웃" style="width:100px">
</form>
<h4>팀 이름 변경</h4>
<form action="?setting" method="post">
수수료 : {$temp39}<br />
16자 (전각 문자 = 2자)<br />
새로운 이름 : <input type="text" class="text" name="NewName" size="20">
<input type="submit" class="btn" value="변경" style="width:100px">
</form>
<h4>세상의 끝</h4>
<div class="u">※내 존재를 없앱니다...(부활 불가능)</div>
<form action="?setting" method="post">
PassWord : <input type="text" class="text" name="deletepass" size="20">
<input type="submit" class="btn" name="delete" value="잘있어라..." style="width:100px">
</form>
</div>
P36;
		return $Result;
	}

	function MemorizeParty() {
		$PartyMemo = [];
		if(__POST("memory_party")) {
			foreach($this->char as $key => $val) {
				if(__POST("char_".$key))
					$PartyMemo[]	= $key;
			}
			if(0 < __count($PartyMemo) && __count($PartyMemo) < 6)
				$this->party_memo	= implode("<>",$PartyMemo);
		}
	}

	function LoginMain() {
		$this->ShowTutorial();
		$this->ShowMyCharacters();
		RegularControl($this->id);
	}

	function ShowTutorial() {
		$last	= $this->last;
		$start	= substr($this->start,0,10);
		$term	= 60*60*1;
		if( ($last - $start) < $term) {

print <<<P37
	<div style="margin:5px 15px">
	<a href="?tutorial">튜토리얼</a> - 전투의 기본 (로그인 후 1시간 이내에 표시)
	</div>
P37;

		}
	}


	function ShowMyCharacters($array=NULL) {
		$i = 0;
		if(!$this->char) return false;
		$divide	= (__count($this->char)<CHAR_ROW ? count($this->char) : CHAR_ROW);
		$width	= floor(100/$divide);

		print('<table cellspacing="0" style="width:100%"><tbody><tr>');
		foreach($this->char as $val) {
			if( $i%CHAR_ROW==0 && $i != 0 )
				print("\t</tr><tr>\n");
			print("\t<td valign=\"bottom\" style=\"width:{$width}%\">");
			$val->ShowCharLink($array);
			print("</td>\n");
			$i++;
		}
		print("</tr></tbody></table>");
	}

	function ShowCharacters($characters,$type=null,$checked=null) {
		$i = 0;
		if(!$characters) return false;
		$divide	= (__count($characters)<CHAR_ROW ? count($characters) : CHAR_ROW);
		$width	= floor(100/$divide);

		if($type == "CHECKBOX") {
print <<< HTML
<script type="text/javascript">
<!--
function toggleCheckBox(id) {
id0 = "box" + id;
\$("box" + id).checked = \$("box" + id).checked?false:true;
Element.toggleClassName("text"+id,'unselect');
}
// -->
</script>
HTML;
		}

		print('<table cellspacing="0" style="width:100%"><tbody><tr>');
		foreach($characters as $char) {
			if( $i%CHAR_ROW==0 && $i != 0 )
				print("\t</tr><tr>\n");
			print("\t<td valign=\"bottom\" style=\"width:{$width}%\">");

			switch(1) {
				case ($type === "MONSTER"):
					$char->ShowCharWithLand($checked); break;
				case ($type === "CHECKBOX"):
					if(!is_array($checked)) $checked = array();
					if(in_array($char->birth,$checked))
						$char->ShowCharRadio($char->birth," checked");
					else
						$char->ShowCharRadio($char->birth);
					break;
				default:
					$char->ShowCharLink();
			}

			print("</td>\n");
			$i++;
		}
		print("</tr></tbody></table>");
	}


	function DeleteMyData() {
		if($this->pass == $this->CryptPassword(__POST("deletepass")) ) {
			$this->DeleteUser();
			$this->name	= NULL;
			$this->pass	= NULL;
			$this->id	= NULL;
			$this->islogin= false;
			unset($_SESSION["id"]);
			unset($_SESSION["pass"]);
			setcookie("NO","");
			$this->LoginForm();
			return true;
		}
	}


	function Debug() {
		if(DEBUG)
			print("<pre>".print_r(get_object_vars($this),1)."</pre>");
	}


	function ShowSession() {
		echo "this->id:{$this->id}<br>";
		echo "this->pass:{$this->pass}<br>";
		echo "SES[id]:".__SESSION('id')."<br>";
		echo "SES[pass]:".__SESSION('pass')."<br>";
		echo "SES[pass]:".$this->CryptPassword(__SESSION('pass'))."(crypted)<br>";
		echo "CK[NO]:".__COOKIE('NO')."<br>";
		echo "SES[NO]:".session_id();
		dump($_COOKIE);
		dump($_SESSION);
	}


	function RenewLoginTime() {
		$this->login	= time();
	}


	function CheckLogin() {
		if(__POST("logout")) {
			unset($_SESSION["pass"]);
			return false;
		}
		if ($data = $this->LoadData()) {
			if($this->pass == NULL)
				return false;
			if ($data["pass"] === $this->pass) {
				$this->DataUpDate($data);
				$this->SetData($data);
				if(RECORD_IP)
					$this->SetIp(__SERVER('REMOTE_ADDR'));
				$this->RenewLoginTime();

				$pass	= (__POST("pass"))?__POST("pass"):__GET("pass");
				if ($pass) {
					$_SESSION["id"]	= $this->id;
					$_SESSION["pass"]	= $pass;
					setcookie("NO",session_id(),time()+COOKIE_EXPIRE);
				}

				$this->islogin	= true;
				return true;
			} else
				return "Wrong password!";
		} else {
			if(__POST("id"))
				return "ID \"{$this->id}\" doesnt exists.";
		}
	}


	function RecordRegister($id) {
		$fp=fopen(REGISTER,"a");
		flock($fp,2);
		fputs($fp,"$id\n");
		fclose($fp);
	}


	function Set_ID_PASS() {
		$id	= (__POST("id"))?__POST("id"):__GET("id");
		if($id) {
				$this->id	= $id;
			if (is_registered(__POST("id"))) {
				$_SESSION["id"]	= $this->id;
			}
		} else if(__SESSION("id"))
			$this->id	= __SESSION("id");

		$pass	= (__POST("pass"))?__POST("pass"):__GET("pass");
		if($pass)
			$this->pass	= $pass;
		else if(__SESSION("pass"))
			$this->pass	= __SESSION("pass");

		if($this->pass)
			$this->pass	= $this->CryptPassword($this->pass);
	}


	function SessionSwitch() {
		session_cache_expire(COOKIE_EXPIRE/60);
		if(__COOKIE("NO"))
			session_id(__COOKIE("NO"));

		session_start();
		if(!SESSION_SWITCH)
			return false;
		$OldID	= session_id();
		$temp	= serialize($_SESSION);

		session_regenerate_id();
		$NewID	= session_id();
		setcookie("NO",$NewID,time()+COOKIE_EXPIRE);
		$_COOKIE["NO"]=$NewID;

		if (session_status() == PHP_SESSION_NONE) {
			session_id($OldID);
			session_start();
		}

		if($_SESSION):
			foreach($_SESSION as $key => $val)
				unset($_SESSION["$key"]);
		endif;

		if (session_status() == PHP_SESSION_NONE) {
			session_id($NewID);
			session_start();
		}
		$_SESSION	= unserialize($temp);
	}

	function MakeNewData() {
		if(MAX_USERS <= __count(glob(USER."*")) - 1)
			return array(false,"Maximum users.<br />최대 가입자 수에 도달했습니다.");
		if(__POST("Newid"))
			trim($_POST["Newid"]);
		if(empty(__POST("Newid")))
			return array(false,"Enter ID.");

		if(!preg_match("/[0-9a-zA-Z]{4,16}/",__POST("Newid"))||
			preg_match("/[^0-9a-zA-Z]+/",__POST("Newid")))
			return array(false,"Bad ID");

		if(strlen(__POST("Newid")) < 4 || 16 < strlen(__POST("Newid")))
			return array(false,"Bad ID");

		if(is_registered(__POST("Newid")))
			return array(false,"This ID has been already used.");

		$file = USER.__POST("Newid")."/".DATA;
		if(empty(__POST("pass1")) || empty(__POST("pass2")))
			return array(false,"Enter both Password.");

		if(!preg_match("/[0-9a-zA-Z]{4,16}/",__POST("pass1")) || preg_match("/[^0-9a-zA-Z]+/",__POST("pass1")))
			return array(false,"Bad Password 1");
		if(strlen(__POST("pass1")) < 4 || 16 < strlen(__POST("pass1")))
			return array(false,"Bad Password 1");
		if(!preg_match("/[0-9a-zA-Z]{4,16}/",__POST("pass2")) || preg_match("/[^0-9a-zA-Z]+/",__POST("pass2")))
			return array(false,"Bad Password 2");
		if(strlen(__POST("pass2")) < 4 || 16 < strlen(__POST("pass2")))
			return array(false,"Bad Password 2");

		if(__POST("pass1") !== __POST("pass2"))
			return array(false,"Password dismatch.");

		$pass = $this->CryptPassword(__POST("pass1"));
		if(!file_exists($file)){
			mkdir(USER.__POST("Newid"), 0705);
			$this->RecordRegister(__POST("Newid"));
			$fp=fopen("$file","w");
			flock($fp,LOCK_EX);
				$now	= time();
				fputs($fp,"id=".__POST('Newid')."\n");
				fputs($fp,"pass=$pass\n");
				fputs($fp,"last=".$now."\n");
				fputs($fp,"login=".$now."\n");
				fputs($fp,"start=".$now.substr(microtime(),2,6)."\n");
				fputs($fp,"money=".START_MONEY."\n");
				fputs($fp,"time=".START_TIME."\n");
				fputs($fp,"record_btl_log=1\n");
			fclose($fp);
$st = <<<EOF
<?php
header('location:../');
?>
EOF;
			$fp2 = fopen(USER.__POST("Newid")."/index.php","w");
			fwrite($fp2,$st);
			fclose($fp2);
			$_SESSION["id"]=__POST("Newid");
			setcookie("NO",session_id(),time()+COOKIE_EXPIRE);
			$success	= "<div class=\"recover\">ID : ".__POST('Newid')." 가입 완료. 로그인 해주세요</div>";
			return array(true,$success);
		}
	}


	function NewForm($error=NULL) {
		if(MAX_USERS <= __count(glob(USER."*")) - 1) {

print <<<P38
	<div style="margin:15px">
	Maximum users.<br />
	가입자 수가 최대에 도달했습니다.
	</div>
P38;
			return false;
		}
		$idset=(__POST("Newid") ? " value=".__POST('Newid')."" : NULL);

		$temp40 = ShowError($error);
		$temp41 = INDEX;
print <<<P39
	<div style="margin:15px">
	{$temp40}
	<h4>등록하기</h4>
	<form action="{$temp41}" method="post">

	<table><tbody>
	<tr><td colspan="2">ID & PASS must be 4 to 16 letters.<br />letters allowed a-z,A-Z,0-9<br />
	ID와 PASS는 4~16자여야 합니다. 반각 영숫자를 사용하세요.</td></tr>
	<tr><td><div style="text-align:right">ID:</div></td>
	<td><input type="text" maxlength="16" class="text" name="Newid" style="width:240px"{$idset}></td></tr>
	<tr><td colspan="2"><br />Password,Re-enter.<br />PASS를 위해 다시 한번 확인을 눌러주세요.</td></tr>
	<tr><td><div style="text-align:right">PASS:</div></td>
	<td><input type="password" maxlength="16" class="text" name="pass1" style="width:240px"></td></tr>

	<tr><td></td>
	<td><input type="password" maxlength="16" class="text" name="pass2" style="width:240px">(verify)</td></tr>

	<tr><td></td><td><input type="submit" class="btn" name="Make" value="가입" style="width:160px"></td></tr>

	</tbody></table>
	</form>
	</div>
P39;
	}
	function LoginForm($message = NULL) {
		
		$temp42 = INDEX;
		$temp43 = ((__SESSION("id")) ? " value=\"".__SESSION('id')."\"" : NULL);
print <<<P40
<div style="width:730px;">
<div style="width:350px;float:right">
<h4 style="width:350px">로그인</h4>
{$message}
<form action="{$temp42}" method="post" style="padding-left:20px">
<table><tbody>
<tr>
<td><div style="text-align:right">ID:</div></td>
<td><input type="text" maxlength="16" class="text" name="id" style="width:160px"{$temp43}></td>
</tr>
<tr>
<td><div style="text-align:right">PASS:</div></td>
<td><input type="password" maxlength="16" class="text" name="pass" style="width:160px"></td>
</tr>
<tr><td></td><td>
<input type="submit" class="btn" name="Login" value="로그인" style="width:80px"> 
<a href="?newgame">새로운 플레이어?</a>
</td></tr>
</tbody></table>
</form>

<h4 style="width:350px">랭킹</h4>
P40;
	include_once(CLASS_RANKING);
	$Rank	= new Ranking();
	$Rank->ShowRanking(0,4);

	$temp44 = UserAmount();
	$temp45 = MAX_USERS;
print <<<P41
</div>
<div style="width:350px;padding:5px;float:left;">
<div style="width:350px;text-align:center">
<img src="./image/top01.gif" style="margin-bottom:20px" />
</div>
<div style="margin-left:20px">
<DIV class=u>이게 도대체 무슨 게임입니까?</DIV>
<UL>
<LI>게임의 목적은 1등을 하고 <BR>1등을 유지하는 것입니다.  
<LI>모험적인 요소는 없지만 <BR>약간 심오한 전투 시스템입니다. </LI></UL>
<DIV class=u>전투 기분은 어떻습니까?</DIV>
<UL>
<LI>5인의 인물로 대오를 이루다. 
<LI>각 인물은 각기 다른 모드를 가지고 있으며,<BR> 전투 상황에 따라 기술을 사용합니다.
<LI><A class=a0 href="?log">이쪽</A>에서 전투 기록을 회람할 수 있습니다. </LI></UL></DIV></DIV>

<div class="c-both"></div>
</div>

<div style="margin:15px">
<h4>힌트</h4>
사용자 수 : {$temp44} / {$temp45}<br />
P41;
	$Abandon	= ABANDONED;
	print(floor($Abandon/(60*60*24))."일 중 데이터 변화가 없으면 데이터가 사라집니다.(자동 세상의 끝...)");
print("</div>\n");
	}


	function MyMenu() {
		if($this->name && $this->islogin) { 
			print('<div id="menu">'."\n");
			print('<a href="'.INDEX.'">첫 페이지</a><span class="divide"></span>');
			print('<a href="?hunt">전투</a><span class="divide"></span>');
			print('<a href="?item">아이템</a><span class="divide"></span>');
			print('<a href="?town">마을</a><span class="divide"></span>');
			print('<a href="?setting">설정</a><span class="divide"></span>');
			print('<a href="?log">기록</a><span class="divide"></span>');
			if(BBS_OUT)
				print('<a href="'.BBS_OUT.'" target="_balnk">BBS</a><span class="divide"></span>'."\n");
			print('</div><div id="menu2">'."\n");
				
			$temp46 = MoneyFormat($this->money);
			$temp47 = floor($this->time);
			$temp48 = MAX_TIME;
print <<<P42
	<div style="width:100%">
	<div style="width:30%;float:left">{$this->name}</div>
	<div style="width:60%;float:right">
	<div style="width:40%;float:left"><span class="bold">자금</span> : {$temp46}</div>
	<div style="width:40%;float:right"><span class="bold">시간</span> : {$temp47}/{$temp48}</div>
	</div>
	<div class="c-both"></div>
	</div>
P42;
			print('</div>');
		} else if(!$this->name && $this->islogin) {
			print('<div id="menu">');
			print("First login. Thankyou for the entry.");
			print('</div><div id="menu2">');
			print("fill the blanks. 자, 작성해주세요.");
			print('</div>');
		} else { 
			print('<div id="menu">');
			print('<a href="'.INDEX.'">첫 페이지</a><span class="divide"></span>'."\n");
			print('<a href="?newgame">신규등록</a><span class="divide"></span>'."\n");
			print('<a href="?manual">규칙과 매뉴얼</a><span class="divide"></span>'."\n");
			print('<a href="?gamedata=job">게임 데이터</a><span class="divide"></span>'."\n");
			print('<a href="?log">전투 기록</a><span class="divide"></span>'."\n");
			if(BBS_OUT)
				print('<a href="'.BBS_OUT.'" target="_balnk">BBS</a><span class="divide"></span>'."\n");			
			print('</div><div id="menu2">');
			print("어서 오세요 [ ".TITLE." ]");
			print('</div>');
		}
	}


	function Head() {
		$temp50 = TITLE;
print <<<P43_A
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
P43_A;
$this->HtmlScript();
print <<<P43_B
<title>{$temp50}</title>
</head>
<body><a name="top"></a>
<div id="main_frame">
<div id="title"><img src="./image/title03.gif"></div>
P43_B;
$this->MyMenu();
print <<<P43_C
<div id="contents">
P43_C;
	}


	function HtmlScript() {
print <<<P44
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="./basis.css" type="text/css">
<link rel="stylesheet" href="./style.css" type="text/css">
<script type="text/javascript" src="prototype.js"></script>
P44;
	}


	function Foot() {

print <<<P45
</div>
<div style="clear: both;"></div>
<div id="foot">
<a href="?update">UpDate</a> - 
P45;
	if(BBS_BOTTOM_TOGGLE)
		print('<a href="'.BBS_OUT.'" target="_blank">BBS</a> - '."\n");

print <<<P46
<a href="?manual">메뉴얼</a> - 
<a href="?tutorial">튜토리얼</a> - 
<a href="?gamedata=job">게임 데이터</a> - 
<a href="#top">Top</a><br>
Copy Right <a href="http://tekito.kanichat.com/">Tekito</a> 2007-2008.<br>
汉化 By <a href="http://www.firingsquad.com.cn/">FiringSquad中文网</a> 2006-2008.<br>
한국어와 약간의 개조 by <a href="http://blog.naver.com/dk00007">Kustale</a> 1993-2026 ^-^.<br>
</div>
</div>
</body>
</html>
P46;
	}


	function FirstLogin() {
		$error = '';
		if (!$this->name){
			//print("* {$this->name} not found");
			do {
				if (!__POST("Done"))
					break;
				if(is_numeric(strpos(__POST("name"),"\t"))) {
					$error	= 'error1';
					break;
				}
				if(is_numeric(strpos(__POST("name"),"\n"))) {
					$error	= 'error';
					break;
				}
				$_POST["name"]	= trim(__POST("name"));
				$_POST["name"]	= stripslashes(__POST("name"));
				if (!__POST("name")) {
					$error	= 'Name is blank.';
					break;
				}
				$length	= strlen(__POST("name"));
				if ( 0 == $length || 16 < $length) {
					$error	= '1 to 16 letters?';
					break;
				}
				$userName	= userNameLoad();
				if(in_array(__POST("name"),$userName)) {
					$error	= '이 이름은 이미 사용되고 있습니다.';
					break;
				}
				$_POST["first_name"]	= trim(__POST("first_name"));
				$_POST["first_name"]	= stripslashes(__POST("first_name"));
				if(is_numeric(strpos(__POST("first_name"),"\t"))) {
					$error	= 'error';
					break;
				}
				if(is_numeric(strpos(__POST("first_name"),"\n"))) {
					$error	= 'error';
					break;
				}
				if (!__POST("first_name")) {
					$error	= 'Character name is blank.';
					break;
				}
				$length	= strlen(__POST("first_name"));
				if ( 0 == $length || 16 < $length) {
					$error	= '1 to 16 letters?';
					break;
				}
				if(!__POST("fjob")) {
					$error	= 'Select characters job.';
					break;
				}
				$_POST["name"]	= htmlspecialchars(__POST("name"),ENT_QUOTES);
				$_POST["first_name"]	= htmlspecialchars(__POST("first_name"),ENT_QUOTES);

				$this->name	= __POST("name");
				userNameAdd($this->name);
				$this->SaveData();
				switch(__POST("fjob")){
					case "1":
						$job = 1; $gend = 0; break;
					case "2":
						$job = 1; $gend = 1; break;
					case "3":
						$job = 2; $gend = 0; break;
					default:
						$job = 2; $gend = 1;
				}
				include(DATA_BASE_CHAR);
				$char	= new char();
				$char->SetCharData(array_merge(BaseCharStatus($job),array("name"=>__POST('first_name'),"gender"=>"$gend")));
				$char->SaveCharData($this->id);
				return false;
			}while(0);

			include(DATA_BASE_CHAR);
			
			$mr1 = array("gender"=>"0");
			$mr2 = array_merge(BaseCharStatus("1"),$mr1);
			$war_male	= new char();
			$war_male->SetCharData($mr2);
			
			$mr3 = array("gender"=>"1");
			$mr4 = array_merge(BaseCharStatus("1"),$mr3);
			$war_female	= new char();
			$war_female->SetCharData($mr4);
			
			$mr5 = array("gender"=>"0");
			$mr6 = array_merge(BaseCharStatus("2"),$mr5);
			$sor_male	= new char();
			$sor_male->SetCharData($mr6);
			
			$mr7 = array("gender"=>"1");
			$mr8 = array_merge(BaseCharStatus("2"),$mr7);
			$sor_female	= new char();
			$sor_female->SetCharData($mr8);

			$temp52 = INDEX;
			$temp54 = (__POST("name")?"value=\"".__POST('name')."\"":"");
			$temp55 = $war_male->ShowImage();
			$temp56 = $war_female->ShowImage();
			$temp57 = $sor_male->ShowImage();
			$temp58 = $sor_female->ShowImage();
	print <<<P47_A
		<form action="{$temp52}" method="post" style="margin:15px">
		P47_A;
		ShowError($error);
		print <<<P47_B
		<h4>Name of Team</h4>
		<p>Decide the Name of the team.<br />
		It should be more than 1 and less than 16 letters.<br />
		Other characters count as 2 letters.</p>
		<p>1-16자의 팀명입니다.<br /></p>
		<div class="bold u">TeamName</div>
		<input class="text" style="width:160px" maxlength="16" name="name" {$temp54}>
		<h4>First Character</h4>
		<p>Decide the name of Your First Charactor.<br>
		more than 1 and less than 16 letters.</p>
		<p>첫 번째 인물의 명칭.</p>
		<div class="bold u">CharacterName</div>
		<input class="text" type="text" name="first_name" maxlength="16" style="width:160px;margin-bottom:10px">
		<table cellspacing="0" style="width:400px"><tbody>
		<tr><td class="td1" valign="bottom"><div style="text-align:center">{$temp55}<br><input type="radio" name="fjob" value="1" style="margin:3px"></div></td>
		<td class="td1" valign="bottom"><div style="text-align:center">{$temp56}<br><input type="radio" name="fjob" value="2" style="margin:3px"></div></td>
		<td class="td1" valign="bottom"><div style="text-align:center">{$temp57}<br><input type="radio" name="fjob" value="3" style="margin:3px"></div></td>
		<td class="td1" valign="bottom"><div style="text-align:center">{$temp58}<br><input type="radio" name="fjob" value="4" style="margin:3px"></div></td></tr>
		<tr><td class="td2"><div style="text-align:center">male</div></td><td class="td3"><div style="text-align:center">female</div></td>
		<td class="td2"><div style="text-align:center">male</div></td><td class="td3"><div style="text-align:center">female</div></td></tr>
		<tr><td colspan="2" class="td4"><div style="text-align:center">Warrior</div></td><td colspan="2" class="td4"><div style="text-align:center">Socerer</div></td></tr>
		</tbody></table>
		<p>Choose your first character's job & Gender.</p>
		<p>최초의 성별과 직업</p>
		<input class="btn" style="width:160px" type="submit" value="Done" name="Done">
		<input type="hidden" value="1" name="Done">
		<input class="btn" style="width:160px" type="submit" value="logout" name="logout"></form>
	P47_B;

			return true;
		}else
			return false;
	}

	function bbs01() {
		if(!BBS_BOTTOM_TOGGLE)
			return false;
		$file	= BBS_BOTTOM;

print <<<P48
<div style="margin:15px">
<h4>one line bbs</h4>
잘못된 보고나 의견, 여기에 대한 개발 건의
<form action="?bbs" method="post">
<input type="text" maxlength="60" name="message" class="text" style="width:300px"/>
<input type="submit" value="post" class="btn" style="width:100px" />
</form>
P48;
		if(!file_exists($file))
			return false;
		$log	= file($file);
		if(__POST("message") && strlen(__POST("message")) < 121) {
			$_POST["message"]	= htmlspecialchars(__POST("message"),ENT_QUOTES);
			$_POST["message"]	= stripslashes(__POST("message"));

			$name	= ($this->name ? "<span class=\"bold\">{$this->name}</span>":"이름 없는");
			$message	= $name." > ".__POST("message");
			if($this->UserColor)
				$message	= "<span style=\"color:{$this->UserColor}\">".$message."</span>";
			$message	.= " <span class=\"light\">(".date("Mj G:i").")</span>\n";
			array_unshift($log,$message);
			while(150 < __count($log))
				array_pop($log);
			WriteFile($file,implode(null,$log));
		}
		foreach($log as $mes)
			print(nl2br($mes));
		print('</div>');
	}
}
?>
