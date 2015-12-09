<?php

namespace Framework;
class Template {
	
	public $tplpath = '';
	public $tplcachepath = '';
	public $layoutpath = '';
	public $replacecode = array('search' => array(), 'replace' => array());

	private function compile_tpl() {
		if($fp = fopen($this->tplpath, 'r')) {
			$tpl = fread($fp, filesize($this->tplpath));
			fclose($fp);
			if($this->layoutpath && ($fpl = fopen($this->layoutpath, 'r'))){
				$layouttpl = fread($fpl, filesize($this->layoutpath));
				$tpl = str_replace('{__CONTENT__}',$tpl,$layouttpl);
			}
			
		}else{
			$this->error('模版不存在:'.$this->tplpath);
		}
		
		$replace_var = "((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(\->)?[a-zA-Z0-9_\x7f-\xff]*)(\[[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\])*)";//php变量
		$replace_const = "([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)";//php常量,必须用大括号包裹

		$tpl = preg_replace("/<\!\-\-\{(.+?)\}\-\->/s", "{\\1}", $tpl);//所有html注释类转换为大括号(主要为结构语言)
		$tpl = preg_replace("/\{(\\\$[a-zA-Z0-9_\->\[\]\'\"\$\.\x7f-\xff]+)\}/s", "<?=\\1?>", $tpl);//有大括号的情况
		//替换eval 字符串
		$tpl = preg_replace("/[\n\r\t]*\{eval\}\s*(<\!\-\-)*(.+?)(\-\->)*\s*\{\/eval\}[\n\r\t]*/ies", "self::evaltags('\\2')", $tpl);
		$tpl = preg_replace("/[\n\r\t]*\{eval\s+(.+?)\s*\}[\n\r\t]*/ies", "self::evaltags('\\1')", $tpl);
		
		$tpl = preg_replace("/$replace_var/es", "self::addquote('<?=\\1?>')", $tpl);//所有php变量添加php短标记
		$tpl = preg_replace("/<\?\=<\?\=$replace_var\?>\?>/es", "self::addquote('<?=\\1?>')", $tpl);//若有两个短标记改为一个
		$tpl = preg_replace("/[\n\r\t]*\{echo\s+(.+?)\}[\n\r\t]*/ies", "self::stripvtags('<? echo \\1; ?>')", $tpl);//大括号里的echo
		$tpl = preg_replace("/[\n\r\t]*\{display\s+([a-z0-9_]+)\s*(\d*)\}[\n\r\t]*/ies", "self::stripvtags('<? include display(\'\\1\',\'\\2\'); ?>')", $tpl);//引入模版的函数
		//--------if elseif else ------模版中结构语言可不加小括号，但必须要用空格分隔
		$tpl = preg_replace("/([\n\r\t]*)\{if\s+(.+?)\}([\n\r\t]*)/ies", "self::stripvtags('\\1<? if(\\2) { ?>\\3')", $tpl);
		$tpl = preg_replace("/([\n\r\t]*)\{elseif\s+(.+?)\}([\n\r\t]*)/ies", "self::stripvtags('\\1<? } elseif(\\2) { ?>\\3')", $tpl);
		$tpl = preg_replace("/\{else\}/i", "<? } else { ?>", $tpl);
		$tpl = preg_replace("/\{\/if\}/i", "<? } ?>", $tpl);
		//--------foreach 循环转换（自动先验证是否为数组） 1为数组 2为键名 3为键值---------
		$tpl = preg_replace("/[\n\r\t]*\{foreach\s+(\S+)\s+(\S+)\s+(\S+)\}[\n\r\t]*/ies", "self::stripvtags('<? if(is_array(\\1)) foreach(\\1 as \\2 => \\3) { ?>')", $tpl);
		$tpl = preg_replace("/\{\/foreach\}/i", "<? } ?>", $tpl);

		$tpl = preg_replace("/\{$replace_const\}/s", "<?=\\1?>", $tpl);//转换常量

		$tpl = preg_replace("/ \?>[\n\r]*<\? /s", " ", $tpl);//转换意外的php标记
		//替换eval 字符串
		if(!empty($this->replacecode)) {
			$tpl = str_replace($this->replacecode['search'], $this->replacecode['replace'], $tpl);
		}
		$tpl = preg_replace("/<\?(\s{1})/is", "<?php\\1", $tpl);//为结构语句加上php
		$tpl = preg_replace("/<\?\=(.+?)\?>/is", "<?php echo \\1;?>", $tpl);//转换php短标记

		
		//写入缓存模版
		if(!$fp = fopen($this->tplcachepath, 'w')) {
			$this->error('目录不存在或目录不可写:'.$this->tplcachepath);
		}
		flock($fp, 2);//写入锁定
		fwrite($fp, $tpl);
		fclose($fp);
	}

	public function display($tplpath,$tplcachepath,$layout=''){
		$this->tplpath=$tplpath;
		$this->tplcachepath=$tplcachepath;
		if($layout){
			$this->layoutpath = $layout;
			$layouttplmtime = filemtime($this->layoutpath);
		}
		$tplmtime = filemtime($this->tplpath);
		$tplcachemtime = filemtime($this->tplcachepath);
		if(!file_exists($this->tplcachepath) || $tplmtime > $tplcachemtime || $layouttplmtime > $tplcachemtime) {
			$this->compile_tpl();
		}
		return $this->tplcachepath;
	}
	//转义特殊字符，允许数组变量不加引号的写法
	private function addquote($var) {
		return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
	}
	private function stripvtags($expr) {
		$expr = str_replace("\\\"", "\"", preg_replace("/<\?\=(\\\$.+?)\?>/s", "\\1", $expr));
		return $expr;
	}

	private function error($msg) {
		throw new \Exception($msg);
	}

	private function evaltags($php) {
		$php = str_replace('\"', '"', $php);
		$i = count($this->replacecode['search']);
		$this->replacecode['search'][$i] = $search = "<!--EVAL_TAG_$i-->";
		$this->replacecode['replace'][$i] = "<? $php?>";
		return $search;
	}
}