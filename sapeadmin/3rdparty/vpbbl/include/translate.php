<?
if($_GET['trans']=="yes"){
	foreach($outcontent as $key=>$value){
	$outcontent[$key]=translate($value,"ru","en");
	echo "������� ������ ����� ".($key+1)." ��������.<br>\r\n";
	    echo '<script language="JavaScript">scrl(30)</script>';
		flush();
	}
}
?>