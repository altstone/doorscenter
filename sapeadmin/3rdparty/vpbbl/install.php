<?
ini_set("max_execution_time",3000);
include("confbd.php");
include("include/bd.php");
$file=file("sinonim.txt");
for ($i=0; $i<count($file); $i++) {
   $tt=$file[$i];
   list($word,$value)=explode(":",trim($tt));
	$sql="INSERT INTO `sin` ( `word` , `sinonim` )
	VALUES (
	'".$word."', '".$value."'
	);";
	  $result1 = $db->sql_query($sql);
	  if(ceil($i/1000)==($i/1000)){
	echo '�������������� ����� ����� '.($i+1).' ��������� � ��'."<br>\r\n";
	flush();
	}
}
?>