<?php


namespace Home\Controller;
use \Common\webController;
class homeController extends webController{
	
	public function index(){
		
		$s = ModelIstance('home');
		
		$s->title = 'test title';
		echo $s->title.'<br>';
		$s->homeFetch();
		echo '<br>';
		$s->fetchAll();

		//test
		//$this->theme='mobile';
		$arr = [1,2,3,4,5];
		$one = 1;

		$this->assign('pageTitle','我是PC版title');
		$this->assign('one',$one);
		$this->assign('arr',$arr);
		$this->assign('content','我是PC版内容');
		$this->display();
	}

	public function test(){
	
		$id = 100;
		if($id>10){
			$this->halt('id 太大了');
		}

	}

	public function test1(){
		
		echo 'test<br>';

	}
	//私有 将不能被调用
	private function homeTest1(){
		echo 'class home - function homeTest1';
	}
}