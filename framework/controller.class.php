<?php

namespace Framework;

class Controller {
	public $theme = '';
	public $layout = '';
	private $_params = [];
	
	public function __construct(){
		$this->theme = config('defaultTheme');
		$this->layout = config('defaultLayout');
	}

	public function display($view='',$return = false){
		//默认为 方法名
		$viewName = ACTION_NAME;
		if($view){
			$viewName = $view;
		}
		//编译模板文件，并返回编译后的文件路径
		
		$viewFile = $this->fetchTemplate($viewName,$return);
		//从数组中将变量导入到当前的符号表,第二个参数，默认为 EXTR_OVERWRITE (就是覆盖)
		extract($this->_params);
		//已开启 ob_startob_start();
		ob_implicit_flush(false);//缓存输出，不立即flush到浏览器
		require($viewFile);

		//返回缓存的数据，并清空缓存数据
		$content = ob_get_clean();
		if($return){
			return $content;
		}else{
			@header('Content-Type: text/html; charset=utf-8');
			echo $content;
			exit();
		}
	}
	//为模版分配变量
	public function assign($key,$value){
		$this->_params[$key] = $value;
	}

	//自定义异常处理方法
	public function error($msg){
		throw new \Exception($msg);
	}
	
	public function ajaxReturn($msg,$data,$status=true){
		
		$return['status'] = $status;
		$return['msg'] = $msg;
		$return['data'] = $data;
		$res = json_encode($return,JSON_UNSCAPE_UNICODE);
		echo $res;
		exit();
	}

	public function fetchTemplate($viewName,$return = false){
		//模板文件路径(可自定义)
		//若有主题
		$prePath = '';
		if($this->theme){
			$prePath = $this->theme.'/';
		}
		$viewFile = APP_PATH.MODULE_NAME.'/view/'.$prePath.CONTROLLER_NAME.'/'.$viewName.'.html';
		//不存在，则抛出异常
		if(!is_file($viewFile)){
			$this->error("template does't exists!!!\n".$viewFile);
		}
		//编译后存放的路径
		$tplcachepath = __ROOT__.'data/tplcache/'.$this->theme.'-'.CONTROLLER_NAME.'-'.$viewName.'.tpl.php';
		//是否有布局模板
		$layoutpath='';
		if($return==false && $this->layout){
			$layoutpath = APP_PATH.MODULE_NAME.'/view/'.$prePath.$this->layout.'.html';
		}
		
		//编译模版
		$template = new \Framework\Template;
		$viewfile = $template->display($viewFile,$tplcachepath,$layoutpath);
		return $viewfile;
	}

	//自定义错误处理方法
	public function halt($errstr){
		//错误触发器
		trigger_error($errstr);
		exit();
	}

}