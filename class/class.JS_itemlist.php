<?php
/*
Hall of Fame Webgame Code
Copyright (C) TEKITO PROJECT for JUICE ( http://tekito.kanichat.com/ )
Remaked by https://github.com/remileon/hof

Code fixed by Kustale ( jinsu8527@gmail.com )

한국어 버전 by Kustale, FE
*/

class JS_ItemList {
	var $ID;
	var $name;

	var $weapon	= array();
	var $armor	= array();
	var $item	= array();
	var $other	= array();

	var $Table	= false;
	var $TableInsert;

	var $NoJS;

	function SetID($ID) {
		$this->ID	= $ID;
	}

	function SetName($name) {
		$this->name	= $name;
	}

	function NoJS() {
		$this->NoJS	= true;
	}

	function AddItem($item,$string) {
		switch($item["type"]) {
			case "검":
			case "양손검":
			case "단검":
			case "창":
			case "지팡이":
			case "마법의 지팡이":
			case "활":
			case "막대":
			case "망치":
			case "채찍":
				array_push($this->weapon,$string);
				break;
			case "방패":
			case "책":
			case "갑":
			case "의류":
			case "장포":
				array_push($this->armor,$string);
				break;
			case "아이템":
				array_push($this->item,$string);
				break;
			default:
				array_push($this->other,$string);
		}
	}

	function ListTable($HTML) {
		$this->Table	= $HTML;
	}

	function ListTableInsert($string) {
		$this->TableInsert	= $string;
	}

	function GetJavaScript($Id) {
			
		$JS_other = "";
		$JS_weapon = "";
		$JS_armor = "";
		$JS_item = "";
		$insert = "";
		$Table0 = "";
		$Table1 = "";
		
		if($this->NoJS)
			return NULL;

		foreach ($this->weapon as $str)
			$JS_weapon	.= ($JS_weapon?" + \n'":"'").$str."'";
		foreach ($this->armor as $str)
			$JS_armor	.= ($JS_armor?" + \n'":"'").$str."'";
		foreach ($this->item as $str)
			$JS_item	.= ($JS_item?" + \n'":"'").$str."'";
		foreach ($this->other as $str)
			$JS_other	.= ($JS_other?" + \n'":"'").$str."'";

		if(!$JS_weapon)
			$JS_weapon	= "''";
		if(!$JS_armor)
			$JS_armor	= "''";
		if(!$JS_item)
			$JS_item	= "''";
		if(!$JS_other)
			$JS_other	= "''";

if($this->Table) {
	$insert	= "insert = '".$this->TableInsert."'";
	$Table0	= "html = '".$this->Table."' + insert + html;";
	$Table1	= "html += insert + '</table>';";
} else {
	$None	= 'html = (html?"":"None.") + html;';
}
$js = <<< _JS_
<script type="text/javascript"><!--
function List{$this->name}(mode) {
switch(mode) {
case "weapon":
html = {$JS_weapon}; break;
case "armor":
html = {$JS_armor}; break;
case "item":
html = {$JS_item}; break;
case "other":
html = {$JS_other}; break;
}
return(html);
}
function ChangeType{$this->ID}() {
mode = document.getElementById('{$this->ID}').{$this->name}.options[document.getElementById('{$this->ID}').{$this->name}.selectedIndex].value
if(mode == 'all') {
html = List{$this->name}('weapon') + List{$this->name}('armor') + List{$this->name}('item') + List{$this->name}('other');
{$None}
hidden = '<input type="hidden" name="list_type" value="all">';
} else {
html = List{$this->name}(mode);
{$None}
hidden = '<input type="hidden" name="list_type" value="' + mode + '">';
}
{$insert}
{$Table0}
{$Table1}
html += hidden;
document.getElementById("{$Id}").innerHTML = html;
}
//--></script>
_JS_;
		return $js;
	}

	function ShowDefault() {
		$open = "";
		
		if($this->NoJS) {
			$list	= array_merge($this->weapon,$this->armor,$this->item,$this->other);
			foreach($list as $str)
				$open	.= $str."\n";
			if($this->Table) {
				$open	= $this->Table.$this->TableInsert.$open;
				$open	.= $this->TableInsert."</table>";
			}
			return $open;
		}

		switch(__POST("list_type")) {
			default:
			case "weapon":
				$list	= $this->weapon;
				break;
			case "armor": $list	= $this->armor; break;
			case "item": $list	= $this->item; break;
			case "other": $list	= $this->other; break;
			case "all": $list	= array_merge($this->weapon,$this->armor,$this->item,$this->other); break;
		}
		foreach($list as $str)
			$open	.= $str."\n";

		switch(__POST("list_type")) {
			case "armor": $open	.= "<input type=\"hidden\" name=\"list_type\" value=\"armor\">\n"; break;
			case "item": $open	.= "<input type=\"hidden\" name=\"list_type\" value=\"item\">\n"; break;
			case "other": $open	.= "<input type=\"hidden\" name=\"list_type\" value=\"other\">\n"; break;
			case "all": $open	.= "<input type=\"hidden\" name=\"list_type\" value=\"all\">\n"; break;
		}

		if($this->Table) {
			$open	= $this->Table.$this->TableInsert.$open;
			$open	.= $this->TableInsert."</table>";
		}

		return $open;
	}

	function ShowSelect() {
		$armor = "";
		$item = "";
		$other = "";
		$all = "";

		if($this->NoJS)
			return NULL;

		switch(__POST("list_type")) {
			case "armor":	$armor	= " selected"; break;
			case "item":	$item	= " selected"; break;
			case "other":	$other	= " selected"; break;
			case "all":	$all	= " selected"; break;
		}

$html = <<< HTML
<form id="{$this->ID}"><select onchange="ChangeType{$this->ID}()" name="{$this->name}" style="margin-bottom:10px">
  <option value="weapon">무기(weapon)</option>
  <option value="armor"{$armor}>방패(armor)</option>
  <option value="item"{$item}>아이템(---)</option>
  <option value="other"{$other}>기타(other)</option>
  <option value="all"{$all}>전체(all)</option>
</select></form>
HTML;

	return $html;
	}
}

?>

