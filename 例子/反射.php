<?php


$arr = get_class_methods('ReflectionMethod');
echo "<pre>";
print_r($arr);
echo "</pre>";
echo '<hr>';

//php 反射
class test{

	public function t1(){
		return 'function t1';
	}

	private function t2($arg1,$arg2=5){
		return 'arg1:'.$arg1.' . arg2:'.$arg2;
	}
	
}

//对象调用方法
$t = new test();
$ref1 = new ReflectionMethod($t,'t1');//反射方法对象
echo $ref1->invoke($t);

echo '<hr>';
//参数
$ti = new test();
$ref2 = new ReflectionMethod($ti,'t2');//反射方法对象
echo '方法 t2 有多少参数<br>';
echo $ref2->getNumberOfParameters();//获取该方法有多少个参数

echo '<br>方法 t2  参数 => 默认值<br>';

$paramsArr = $ref2->getParameters();
foreach($paramsArr as $k=>$param){
	echo $name = $param->getName();
	echo ' => ';
	echo $param->isDefaultValueAvailable() ? $param->getDefaultValue() : '0';
	echo '<br>';
}

echo '<hr>';
//是公共方法 且 不是静态方法
if($ref2->isPublic() && !$ref2->isStatic()){
	//有默认参数，则用 invokeArgs 
	echo $ref2->invokeArgs($ti,array('11',22));
}else{
	echo 'method error ';
}


