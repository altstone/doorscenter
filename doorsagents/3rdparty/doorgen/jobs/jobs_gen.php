<?
$job_file = "test.txt";
$templ = "test_macros";
$url = "http://test.com/{DOR}";
$path = "{DOR}";
for ($n=0;$n<=99;$n++) {
	$key = "keys".$n.".txt"; //-------------------���
	$out .= $key.",".$templ.",".$url.",".$path."\n";
}
$file_out = fopen($job_file, "w");
fwrite($file_out, $out);
fclose($file_out);
echo "���� ".$job_file." ��������";
?>