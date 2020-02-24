<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.

defined('IN_MET') or exit('No permission');

class inc {
	function ini($file){
		global $_M;		
		$query = "DELETE FROM {$_M['table']['templates']} WHERE no='{$file}'";
		DB::query($query);
		foreach ($_M['langlist']['web'] as $key=>$val) {
			$this->oldtminiment($file, $val['mark']);
		}
	}
	/*获取配置文件内容*/
	function iniget($file, $lang){
		global $_M;
		if(file_exists(PATH_WEB."templates/{$file}/lang/language_{$lang}.ini")){
			$file_name=PATH_WEB."templates/{$file}/lang/language_{$lang}.ini";
			$fp = @fopen($file_name,'r') or die("{$_M[word][fileerr1]} {$file_name}");
			$i=0;
			$j=0;
			while ($conf_line = @fgets($fp, 1024)){
				if($i<4 && substr($conf_line,0,1)=="#"){
					$i++;  
					$linetop=$linetop.$conf_line;
					$lineno = ereg_replace("#.*$", "", $conf_line);
					$line="";
				}else{
					$line=$conf_line;
				}
				if(trim($line) == "") continue;
				if(substr($line,0,1)=="#"){
					$langarray[$j]=substr($line,1);
					$j++;
				}else{
					$linearray=explode ('=', $line);
					$linenum=count($linearray);
					if($linenum==2){
						list($name, $value) = explode ('=', $line);
					}else{
						for($n=0;$n<$linenum;$n++){
							$linetra=$n?$linetra."=".$linearray[$n]:$linearray[$n].'metinfo_';
						}
						list($name, $value) = explode ('metinfo_=', $linetra);
					}
					$value=str_replace("\"","&quot;",$value);
					list($value, $valueinfo)=explode ('/*', $value);
					list($valueinfo)=explode ('*/', $valueinfo);
					$name = daddslashes(trim($name),1);
					$value = trim($value);
					$langtext[]=array('name'=>$name,'value'=>$value,'valueinfo'=>$valueinfo);
				}
			}
			$langtext['linetop'] = $linetop;//获取版权文字
		}		
		return $langtext;
	}
	/*获取ini配置的描述文字*/
	function skin_desc($txt,$type){
		global $_M;
		$metcms=$type?$txt:'';
		if(strstr($txt,'$DESC$')){
			$metcmsx=explode('$DESC$',$txt);
			$metcms=$type?$metcmsx[0]:$metcmsx[1];
		}
		return $metcms;
	}
	/*整理配置文件，为页面展示所用*/
	function oldtminiment($file, $lang){
		global $_M;
		$langtextx=array();
		$langtext=$this->iniget($file, $lang);
		$array = column_sorting(2);
		$met_class1 = $array['class1'];
		$met_class2 = $array['class2'];
		$met_class3 = $array['class3'];
		$no_order = 0;
		foreach($langtext as $key=>$val){
		$no_order++;
		$val['no'] = $file;
		$val['pos'] = 0;
		$val['no_order'] = $no_order;
		if($key!='linetop'||$key=='0'){
			if(strstr($val[valueinfo],'$TYPE$')){
				$metcmsx=explode('$TYPE$',$val[valueinfo]);
				$mtypex=explode('$R$',$metcmsx[1]);
				$mtype=$mtypex[0];
				$val[valueinfo]=$metcmsx[0];
				$val[inputhtm]='';
				$val[tips] = $this->skin_desc($val[valueinfo]);
				switch($mtype){
					case 1:
						$val[type] = 2;
						$val[style] = 0;
						if($val[valueinfo])$val[valueinfo]=$this->skin_desc($val[valueinfo],1);
					break;
					case 2:
						$val[type] = 6;
						$val[style] = 0;
						$val[selectd] = $mtypex[1];
						if($val[valueinfo])$val[valueinfo]=$this->skin_desc($val[valueinfo],1);
					break;
					case 3:
						$val[type] = 4;
						$val[style] = 0;
						$val[selectd] = $mtypex[1];
						if($val[valueinfo])$val[valueinfo]=$this->skin_desc($val[valueinfo],1);
					break;
					case 4:
						$val[type] = 7;
						$val[style] = 0;
						if($val[valueinfo])$val[valueinfo]=$this->skin_desc($val[valueinfo],1);
					break;
					case 5:
						$val[type] = 6;
						$val[style] = $mtypex[1];
						if($val[valueinfo])$val[valueinfo]=$this->skin_desc($val[valueinfo],1);
					break;
					case 6:
						$val[type] = 9;
						$val[style] = 0;
						if($val[valueinfo])$val[valueinfo]=$this->skin_desc($val[valueinfo],1);
					break;
					case 7:
						$val[type] = 9;
						$val[style] = 0;
						if($val[valueinfo])$val[valueinfo]=$this->skin_desc($val[valueinfo],1);
					break;
				}
			}else{
				$val[type] = 2;
				$val[style] = 0;
				$val[tips] = $this->skin_desc($val[valueinfo]);
				switch(trim($val[value])){
					case '#MetInfo':  //分类设置			
						$val[type] = 1;
						$val[style] = 0;
						$val[valueinfo] = $val[name];
						$val[name] = '';
						$val[value] = '';
					break;
					case '#MetInfoBlock':  //区块设置	
						$val[type] = 1;
						$val[style] = 1;
						$val[valueinfo] = $val[name];
						$val[name] = '';
						$val[value] = '';
					break;
					case '#ConfigEditor': //系统变量编辑器
						$val[type] = 8;
						$val[style] = 1;
						$val[value] = '';
					break;
					case '#ConfigUpload': //系统变量上传方式
						$val[type] = 7;
						$val[style] = 1;
						$val[value] = '';
					break;
					case '#ConfigText': //系统变量简短输入框				
						$val[type] = 2;
						$val[style] = 1;
						$val[value] = '';
					break;
					case '#ConfigTextarea': //系统变量文本输入框				
						$val[type] = 3;
						$val[style] = 1;
						$val[value] = '';
					break;
				}
				if($val[valueinfo] && $val[type]!=1)$val[valueinfo]=$this->skin_desc($val[valueinfo],1);
			}
			$langtextx[]=$val;
		}
		}
		foreach($langtextx as $key => $val){
			if($val[type]==1&&$val['style']==1&&$val['name']!='wap_index_content')continue;
			$query = "INSERT INTO {$_M['table']['templates']} SET no='{$val['no']}',pos='{$val['pos']}',no_order='{$val['no_order']}',type='{$val['type']}',style='{$val['style']}',selectd='{$val['selectd']}',name='{$val['name']}',value='{$val['value']}',defaultvalue='{$val['value']}',valueinfo='{$val['valueinfo']}',tips='{$val['tips']}',lang='{$lang}'";
			DB::query($query);
		}
		return $langtextx;
	}
	
	/*配置文件保存*/
	function oldtminisave(){
		global $_M;
		$langtext=$this->oldtminiget();
		/*写入配置文件*/
		$file_name=PATH_WEB."templates/{$_M['form']['met_skin_user']}/lang/language_{$_M[lang]}.ini";
		$config_save="";
		$config_save=$config_save."#".$langarray[$m]."\n";
		$config_list='';
		foreach($langtext as $key=>$val){
			if($key!='linetop'||$key=='0'){
				$namelist=$val[name]."_metinfo";
				$namemetinfo=$_M[form][$namelist];
				if($namemetinfo!="")$namemetinfo=stripslashes($namemetinfo);
				$val[value]=($namemetinfo==""&&$val[valueinfo]=="")?$val[value]:$namemetinfo;
				$nameinfolist=$val[name]."_info_metinfo";
				$nameinfometinfo=$$nameinfolist;
				if($nameinfometinfo!="")$nameinfometinfo=stripslashes($nameinfometinfo);
				$val[valueinfo]=($nameinfometinfo=="")?$val[valueinfo]:$nameinfometinfo;
				$val[valueinfo]=($val[valueinfo]=="")?"":"/*".$val[valueinfo]."*/";
				//if($val[valueinfo]=="" and $nameinfometinfo=="" and $namemetinfo!="")$val[valueinfo]="\n";
				$config_list.=$val[name]."=".$val[value].$val[valueinfo]."\n";
			}
		}
		$config_save=$config_save.$config_list."\n";
		$config_save=$langtext['linetop']."\n".$config_save;
		$fp = fopen($file_name,w);
		fputs($fp, $config_save);
		fclose($fp);
	}

}

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>