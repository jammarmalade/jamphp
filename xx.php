<?php


$arr = get_class_methods('ReflectionMethod');
echo "<pre>";
print_r($arr);
echo "</pre>";
echo '<hr>';

//php ����
class test{

	public function t1(){
		return 'function t1';
	}

	private function t2($arg1,$arg2=5){
		return 'arg1:'.$arg1.' . arg2:'.$arg2;
	}
	
}

//������÷���
$t = new test();
$ref1 = new ReflectionMethod($t,'t1');//���䷽������
echo $ref1->invoke($t);

echo '<hr>';
//����
$ti = new test();
$ref2 = new ReflectionMethod($ti,'t2');//���䷽������
echo '���� t2 �ж��ٲ���<br>';
echo $ref2->getNumberOfParameters();//��ȡ�÷����ж��ٸ�����

echo '<br>���� t2  ���� => Ĭ��ֵ<br>';

$paramsArr = $ref2->getParameters();
foreach($paramsArr as $k=>$param){
	echo $name = $param->getName();
	echo ' => ';
	echo $param->isDefaultValueAvailable() ? $param->getDefaultValue() : '0';
	echo '<br>';
}

echo '<hr>';
//�ǹ������� �� ���Ǿ�̬����
if($ref2->isPublic() && !$ref2->isStatic()){
	//��Ĭ�ϲ��������� invokeArgs 
	echo $ref2->invokeArgs($ti,array('11',22));
}else{
	echo 'method error ';
}




