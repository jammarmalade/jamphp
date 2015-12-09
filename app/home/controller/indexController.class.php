<?php


namespace Home\Controller;
use \Common\webController;
use Home\Model\homeModel;
class indexController extends webController{
	
	public function index(){
		
		$homeM = ModelIstance('home');
		//---------------------查询-------------------------
		
		//数组
		//echo '------查询 多条-------';
		$where['id'] = array('<',16);
		$res = $homeM->where($where)->field('label_id,label_name,id')->order('label_id DESC')->limit(1,5)->fetchAll('label_id');
		/*printarr($res);
		//是否用的同一个数据库实例,同一个链接符号?
		//字串
		echo '------查询 单条-------';
		$res = $homeM->where('id = 12')->field('label_id,label_name')->order('label_id DESC')->fetch();
		printarr($res);
		//model 方法查询
		$homeM = new homeModel();
		$res[] = $homeM->getById(1);
		
		//原生查询
		$res = $homeM->query('SELECT * FROM %t WHERE label_id IN(%n)',array('home',array(1,2,3,4)));
		*/
		//---------------------增加-------------------------
		/*
		//对象方式
		$homeM->label_name = '马拉松';
		$homeM->id = '2';
		$insertid = $homeM->add();
		echo $insertid.'<br>';
		*/
		//---------------------更新-------------------------
		/*
		$homeM->label_name = '短跑';
		$homeM->id = 66;
		$affectrow = $homeM->update(['label_name'=>'马拉松']);
		echo $affectrow.'<br>';
		*/
		//---------------------删除-------------------------
		/*
		$affectrow = $homeM->where(['label_name'=>'短跑'])->delete();
		echo $affectrow.'<br>';
		*/

		
		$data = $res;

		//手机版主题
	//	$this->theme='mobile';
		 

		$this->assign('pageTitle','我是PC版title');
		$this->assign('data',$data);
		$this->assign('content','我是PC版内容');
		$this->display();
	}

	public function allData(){
		$homeM = new homeModel();
		$all = $homeM->query("SELECT * FROM %t",array('home'));
		$i = 1;
		$html = '<table><th>label_id&nbsp;&nbsp;</th><th>label_name&nbsp;&nbsp;</th><th>id&nbsp;&nbsp;</th><th>arrt_type</th>';
		foreach($all as $data){
			$html .= <<<TEXT
<tr>
	<td>$data[label_id]</td>
	<td>$data[label_name]</td>
	<td>$data[id]</td>
	<td>$data[arrt_type]</td>
</tr>
TEXT;
			$i++;
		}
		$html .= '</table>';
		echo $html;
		exit();
	}

	public function t1(){
	
		
		$this->assign('content','我是方法 t1 的内容');
		$this->display('test');
	}

	public function test1(){
		
		$this->assign('pageTitle','我是PC版title - test1');

		$this->assign('content','我是方法 test1 的内容');
		$this->display('test');

	}
	
	public function fetch(){

		$this->assign('content','我是方法 fetch 的内容');
		$html = $this->display('fetch',true);
		
		$this->ajaxReturn('成功',$html);
	}

	//私有 将不能被调用
	private function ptest(){
		echo 'class home - function ptest';
	}
}