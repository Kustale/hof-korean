<?php
/*
Hall of Fame Webgame Code
Copyright (C) TEKITO PROJECT for JUICE ( http://tekito.kanichat.com/ )
Remaked by https://github.com/remileon/hof

Code fixed by Kustale ( jinsu8527@gmail.com )

한국어 버전 by Kustale, FE
*/

class user {

	var $fp;
	var $file;

	var $id, $pass;
	var $name, $last, $login ,$start;
	var $money;
	var $char;
	var $time;
	var $wtime;
	var $ip;

	var $party_memo;
	var $party_rank;
	var $rank_set_time;
	var $rank_btl_time;
	var $rank_record;
	var $union_btl_time;

	var $record_btl_log;
	var $no_JS_itemlist;
	var $UserColor;

	var $fp_item;
	var $item;


	function user($id,$noExit=false) {
		if($id)
		{
			$this->id	= $id;
			if($data = $this->LoadData($noExit)) {
				$this->DataUpDate($data);
				$this->SetData($data);
			}
		}
	}

	function SetIp($ip) {
		$this->ip = $ip;
	}

	function LoadData($noExit=false) {
		$file	= USER.$this->id."/".DATA;
		if(file_exists($file)) {
			$this->file	= $file;
			$this->fp	= FileLock($file,$noExit);
			if(!isset($this->fp))
				return false;
			$data	= ParseFileFP($this->fp);
			return $data;
		} else {
			return false;
		}
	}


	function is_exist() {
		if($this->name)
			return true;
		else
			return false;
	}

	function Name($opt=false) {
		if($this->name) {
			if($opt)
				return '<span class="'.$opt.'">'.$this->name.'</span>';
			else
				return $this->name;
		} else {
			return false;
		}
	}

	function ChangeName($name) {

		if($this->name == $name)
			return false;

		$this->name	= $name;
		return true;
	}

	function UnionSetTime() {
		$this->union_btl_time	= time();
	}

	function CanUnionBattle() {
		$Now	= time();
		$Past	= $this->union_btl_time	+ UNION_BATTLE_NEXT;
		if($Past <= $Now) {
			return true;
		} else {
			return abs($Now - $Past);
		}
	}

	function RankParty() {
		if(!$this->name)
			return "NOID";
		if(!$this->party_rank)
			return false;

		$PartyRank	= explode("<>",$this->party_rank);
		foreach($PartyRank as $no) {
			$char	= $this->CharDataLoad($no);
			if($char)
				$party[]	= $char;
		}

		if($party)
			return $party;
		else
			return false;
	}

	function RankRecord($result,$side,$DefendMatch) {
		$record	= $this->RankRecordLoad();

		$record["all"]++;
		switch(true) {
			case ($result === 0):
				if($side == "CHALLENGER") {
					$record["win"]++;
				} else {
					$record["lose"]++;
				}
				break;
			case ($result === 1):
				if($side == "CHALLENGER") {
					$record["lose"]++;
				} else {
					$record["win"]++;
					if($DefendMatch)
						$record["defend"]++;
				}
				break;
			default:
				if($side != "CHALLENGER" && $DefendMatch)
					$record["defend"]++;
				break;
		}

		$this->rank_record	= $record["all"]."|".$record["win"]."|".$record["lose"]."|".$record["defend"];
	}

	function RankRecordLoad() {

		if(!$this->rank_record) {
			$record	= array(
						"all" => 0,
						"win" => 0,
						"lose" => 0,
						"defend" => 0,
						);
			return $record;
		}

		list(
			$record["all"],
			$record["win"],
			$record["lose"],
			$record["defend"],
		)	= explode("|",$this->rank_record);
		return $record;
	}

	function SetRankBattleTime($time) {
		$this->rank_btl_time	= $time;
	}


	function CanRankBattle() {
		$now	= time();
		if($this->rank_btl_time <= $now) {
			return true;
		} else if(!$this->rank_btl_time) {
			return true;
		} else {
			$left	= $this->rank_btl_time - $now;
			$hour		= floor($left/3600);
			$minutes	= floor(($left%3600)/60);
			$seconds	= floor(($left%3600)%60);
			return array($hour,$minutes,$seconds);
		}
	}


	function GetMoney($no) {
		$this->money	+= $no;
	}


	function TakeMoney($no) {
		if($this->money < $no) {
			return false;
		} else {
			$this->money	-= $no;
			return true;
		}
	}


	function WasteTime($time) {
		if($this->time < $time)
			return false;
		$this->time		-= $time;
		$this->wtime 	+= $time;
		return true;
	}

	function CharCount() {
		$dir	= USER.$this->id;
		$no		= 0;
		foreach(glob("$dir/*") as $adr) {
			$number	= basename($adr,".dat");
			if(is_numeric($number)) {
				$no++;
			}
		}
		return $no;
	}

	function CharDataLoadAll() {
		$dir	= USER.$this->id;
		foreach(glob("$dir/*") as $adr) {
			$number	= basename($adr,".dat");
			if(is_numeric($number)) {
				$this->char[$number]	= new char($adr);
				$this->char[$number]->SetUser($this->id);
			}
		}
	}

	function CharDataLoad($CharNo) {
		if($this->char[$CharNo])
			return $this->char[$CharNo];
		$file	= USER.$this->id."/".$CharNo.".dat";
		if(!file_exists($file)){
			return false;
		} else {
			$this->char[$CharNo]	= new char($file);
			$this->char[$CharNo]->SetUser($this->id);
			return $this->char[$CharNo];
		}
	}

	function AddItem($no,$amount=false) {
		if(!isset($this->item))
			$this->LoadUserItem();
		if($amount)
			if(isset($this->item[$no])) $this->item[$no]	+= $amount;
		else
			if(isset($this->item[$no])) $this->item[$no]++;
	}


	function DeleteItem($no,$amount=false) {
		if(!isset($this->item))
			$this->LoadUserItem();

		if($this->item[$no] < $amount) {
			$amount	= $this->item[$no];
			if(!$amount)
				$amount = 0;
		}
		if(!is_numeric($amount))
			$amount	= 1;

		$this->item[$no]	-= $amount;
		if($this->item[$no] < 1)
			unset($this->item[$no]);

		return $amount;
	}


	function LoadUserItem() {
		if(isset($this->item))
			return false;

		$file	= USER.$this->id."/".ITEM;

		if(file_exists($file)) {
			$this->fp_item	= FileLock($file);
			$this->item	= ParseFileFP($this->fp_item);
			if($this->item === false)
				$this->item	= array();
		} else {
			$this->item	= array();
		}
	}


	function SaveUserItem() {
		$text = "";
		$dir	= USER.$this->id;
		if(!file_exists($dir))
			return false;

		$file	= USER.$this->id."/".ITEM;

		if(!is_array($this->item))
			return false;

		ksort($this->item,SORT_STRING);

		foreach($this->item as $key => $val) {
			$text	.= "$key=$val\n";
		}

		if(file_exists($file) && $this->fp_item) {
			WriteFileFP($this->fp_item,$text,1);
			fclose($this->fp_item);
			unset($this->fp_item);
		} else {
			WriteFile($file,$text,1);
		}
	}


	function DataUpDate(&$data) {
		$now	= time();
		$diff	= $now - $data["last"];
		$data["last"]	= $now;
		$gain	= $diff / (24*60*60) * TIME_GAIN_DAY;
		$data["time"]	+= $gain;
		if(MAX_TIME < $data["time"])
			$data["time"]	= MAX_TIME;
	}


	function SetData(&$data) {
		foreach($data as $key => $val) {
			$this->{$key}	= $val;
		}
	}


	function CryptPassword($pass) {
		return substr(crypt($pass,CRYPT_KEY),strlen(CRYPT_KEY));
	}


	function DeleteName() {
		$this->name	= NULL;
	}


	function DataSavingFormat() {
		$text = "";
		$Save	= array(
			"id", "pass", "ip", "name", "last", "login",
			"start", "money", "time", "wtime", "party_memo",
			"party_rank", "rank_set_time", "rank_btl_time", "rank_record", "union_btl_time",
			"record_btl_log", "no_JS_itemlist", "UserColor",
			);
		foreach($Save as $val) {
			if($this->{$val})
				$text	.= "$val=".(is_array($this->{$val}) ? implode("<>",$this->{$val}) : $this->{$val})."\n";
		}
		
		return $text;
	}


	function SaveData() {
		$file	= USER.$this->id."/".DATA;

		if(file_exists($this->file) && isset($this->fp)) {
			WriteFileFP($this->fp,$this->DataSavingFormat());
			fclose($this->fp);
			unset($this->fp);
		} else {
			if(file_exists($file))
				WriteFile($file,$this->DataSavingFormat());
		}
	}

	function fpCloseAll() {
		if(isset($this->fp)){
			if(is_resource($this->fp))
			{
				fclose($this->fp);
				unset($this->fp);
			}
		}
		if(isset($this->fp_item)){
			if(is_resource($this->fp_item))
			{
				fclose($this->fp_item);
				unset($this->fp_item);
			}
		}
		
		if($this->char)
		{
			foreach($this->char as $key => $var)
			{
				if(method_exists($this->char[$key],"fpclose"))
					$this->char[$key]->fpclose();
			}
		}

	}

	function DeleteUser($DeleteFromRank=true) {
		if($DeleteFromRank) {
			include_once(CLASS_RANKING);
			$Ranking	= new Ranking();
			if( $Ranking->DeleteRank($this->id) )
				$Ranking->SaveRanking();
		}

		$dir	= USER.$this->id;
		$files	= glob("$dir/*");
		$this->fpCloseAll();
		foreach($files as $val)
			unlink($val);
		rmdir($dir);
	}

	function IsAbandoned() {
		$now	= time();
		if(strlen($this->login) !== 10) {
			return false;
		}
		if( ($this->login + ABANDONED) < $now) {
			return true;
		} else {
			return false;
		}
	}

	function DeleteChar($no) {
		$file	= USER.$this->id."/".$no.".dat";
		if($this->char[$no]) {
			$this->char[$no]->fpclose();
		}
		if(file_exists($file))
			unlink($file);
	}
}
?>
