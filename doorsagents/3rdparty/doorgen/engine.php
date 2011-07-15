<?
{	//���������
	set_time_limit(0);
//	error_reporting(E_ALL^E_NOTICE);
//	ini_set("display_errors", 1); 
	ini_set("max_execution_time", 0);
	ini_set("memory_limit", -1);
	ob_implicit_flush(1); //����� �������� �� ����� �� ������
	$time_start = time(); //�������� ����� ������ ������
	require ("lib/func.lib.php");
	require ("lib/permissions.php");
}

{	//������ ��������� �� settings.xml
	$settings = simplexml_load_file("settings.xml") or die("<b>������</b>: �� �������� ��������� ������ ���� settings.xml");

	$job_file = (string)$settings->primary->job_file; //�������� ����� �������� �������
	$text_ancor_sitemap_links = (string)$settings->primary->text_ancor_sitemap_links; //������� ������ ������ ������ ���� ������
	$text_ancor_index_link = (string)$settings->primary->text_ancor_index_link; //����� ������ �� ������� �������� ����
	$count_ancor_in_map = (int)$settings->primary->count_ancor_in_map; //���-�� ������� �� ����� �����. ��� �������� �����
	$num_perc_spam = (float)$settings->primary->num_perc_spam; //����������� ���-�� BB ������ ��� �����, � ������ ���-�� ������� ������ ���� (0.6 = 60%)

	$compression_template = (bool)str_to_bool($settings->secondary->compression_template); //������� ������������ �������� ����
	$extension = (string)$settings->secondary->extension; //���������� � ���� ������ ����������� ����
	$keys_dir = (string)$settings->secondary->keys_dir; //����� � �������
	$templ_dir = (string)$settings->secondary->templ_dir; //����� � ������
	$out_dir = (string)$settings->secondary->out_dir; //����� � �������� ������
	$jobs_dir = (string)$settings->secondary->jobs_dir; //�����, ��� ����������� �������
	$text_dir = (string)$settings->secondary->text_dir; //������ ������ ����� ����� ������ �� ������, ��� ��������
	$insert_sitemaps_links_in_all_ancor_log = (bool)str_to_bool($settings->secondary->insert_sitemaps_links_in_all_ancor_log); //��������� ������ �� ����� ������
	$insert_sitemaps_links_in_ancor_log = (bool)str_to_bool($settings->secondary->insert_sitemaps_links_in_ancor_log); //��������� ������ �� ����� ������
	$insert_sitemaps_links_in_bbcode_log = (bool)str_to_bool($settings->secondary->insert_sitemaps_links_in_bbcode_log); //��������� ������ �� ����� ������
	$insert_sitemaps_links_in_bbcode_log_spam = (bool)str_to_bool($settings->secondary->insert_sitemaps_links_in_bbcode_log_spam); //��������� ������ �� ����� ������
	$generate_sitemap_xml = (bool)str_to_bool($settings->secondary->generate_sitemap_xml); //������������ sitemap.xml
	$generate_robots_txt = (bool)str_to_bool($settings->secondary->generate_robots_txt); //������������ robots.txt
	$generate_filezilla_project = (bool)str_to_bool($settings->secondary->generate_filezilla_project); //������������ ������� ��� ������� � filezilla
	$enable_packing = (bool)str_to_bool($settings->secondary->enable_packing); //��������� � zip
	$upload_ftp = (bool)str_to_bool($settings->secondary->upload_ftp); //��������� �� ftp

	$default_ftp_url = (string)$settings->secondary->default_ftp_url; //���� ��� ����������� �� ftp (�� ���������)
	$default_ftp_params = parse_url($default_ftp_url); //������ ������ ��� ��������� ��������� ���������� �����������

	$default_ftp_host = (string)$default_ftp_params["host"]; //���� ��� ����������� �� ftp (�� ���������)
	$default_ftp_port = (string)$default_ftp_params["port"]; //���� ��� ����������� �� ftp (�� ���������)
	if ($default_ftp_port=="")
		$default_ftp_port = 21;
	$default_ftp_login = (string)$default_ftp_params["user"]; //�������� ��� ����������� �� ftp (�� ���������)
	$default_ftp_password = (string)$default_ftp_params["pass"]; //������ ��� ����������� �� ftp (�� ���������)
	$default_ftp_path = (string)$default_ftp_params["path"]; //���� �� �����, ���� ���������� zip ��� / � ����� (�� ���������)
	$extract_zip_archive = (bool)str_to_bool($settings->secondary->extract_zip_archive); //���� �� �����, ���� ���������� zip ��� / � ����� (�� ���������)
	$text_ancor_sitemap_links = iconv("utf-8", "windows-1251", $text_ancor_sitemap_links); //����������� ������ � ��������� ����� �� utf-8 ������� � win1251
	$text_ancor_index_link = iconv("utf-8", "windows-1251", $text_ancor_index_link); //����������� ������ � ��������� ����� �� utf-8 ������� � win1251
}

{	//������ �������, ��������� ����, ��������� ������ ��� ������������, ��������� ������ ���� ��� ����������� ���������
	$data_cache_file = array(); //������ - ��� ��������� �� ������ ��� ������� cache_file
	$a_dor_urand_number = array(); //������ ��� ������� DOR_RAND (�������� �������������� ��������� �������� ��� ��������� ����)

	$job_list = file($jobs_dir."/".$job_file) or die("<b>������</b>: �� ������ (��� ������) ���� �������. ��������� ".$jobs_dir."/".$job_file); //������ �������
//	$job_list = massTrim($job_list); //������ �� \n
	$job_list = del_comment_job_list($job_list); //������� �������� � ������������� � ������������ ������ ������
	$count_job = count($job_list);
	preg_match("/(.*?)\.txt/i", $job_file, $job_name); //�������� ��� ����� ������� ��� ���������� � ...
	$job_name = $job_name[1]."_".time(); //... �������� � ����������� timestamp
	echo "����� �������: <b>".$count_job."</b><br>";
	echo "���� ������� � ���������� ������ ��� �����. ��� ����� ������ ��������� �����<br><script>scroll(0,999999);document.title='������� � ���������� ������'</script>";
	$t4 = time();
	for($i=0;$i<$count_job;$i++) { //������� �������� �� �������� � ���������� *_log.txt ��� ���������� ������������ ���� �����. ��� ������� {RELINKS}. ����������� ��� ��� � �������� ������ �������
		$job_list[$i] = replace_macros_page($job_list[$i], $access_macros_jobs); //������������ ������� � ������� �������
		$tmp_job_list[$i] = explode(",", $job_list[$i]); //��������� �� ���������� �������
		list ($relinks_ids, $dor_file_keys, $dor_num_keys, $enable_shuffle, $dor_templates, $path_remote, $path_local) =  array(trim($tmp_job_list[$i][0]), trim($tmp_job_list[$i][1]), trim($tmp_job_list[$i][2]), trim($tmp_job_list[$i][3]), trim($tmp_job_list[$i][4]), trim($tmp_job_list[$i][5]), trim($tmp_job_list[$i][6]));
		if ($cached_dor_keys[$dor_file_keys]=="") { //���� ���� ������ �� ���������, �� ��������
			$dor_keys = file($keys_dir."/".$dor_file_keys); //������ ���� � �����
			$dor_keys = massTrim($dor_keys); //������ �� \n
			$dor_keys = array_unique_own($dor_keys); //������� ����� ������ � ������� ����������� �������� � ������ ��������������
			$cached_dor_keys[$dor_file_keys] = $dor_keys; //���������� � ��� ��������� ������� ����
		}
		else
			$dor_keys = $cached_dor_keys[$dor_file_keys]; // ��������� ������� ���� �� ����
		
		if ($enable_shuffle) { //���� �������� �������������
			do {
				shuffle($dor_keys); //������������
				$dor_keys = array_rand_values($dor_keys, $dor_num_keys); //����� ������ ����������� ���-�� ������ ��� ��������� ���������
			} while (in_array($dor_keys[0], (array)$a_primary_keys)); //���� ��������� ������� ��� �� ����� ����������, ����� ��������� ������� ������� ������
		}
		else {
			$dor_keys = array_slice($dor_keys, 0, $dor_num_keys); //��� ����� ����� dor_num_keys ���
		}
		$a_primary_keys[] = $dor_keys[0]; //������ ���������� ������� ����, ��� ��������� � ������� ������� ������

		$total_key_dor = count($dor_keys); //�������� ���-�� ����
		$name_dir = key_to_filename($dor_keys[0]); //���������� ���� �� ��� ������� ��������
		$path_remote = str_replace("{DOR}", $name_dir, $path_remote); //���� ���� ��������
		$path_local = str_replace("{DOR}", $name_dir, $path_local); //���� ���� ��������
		$job_list[$i] = $relinks_ids.",".$dor_file_keys.",".$dor_num_keys.",".$enable_shuffle.",".$dor_templates.",".$path_remote.",".$path_local;
		for ($x=0;$x<$total_key_dor;$x++) { //��������� �� ����� �������� ����
			if ($x == 0)
				$name_file = "index".$extension;
			else
				$name_file = key_to_filename($dor_keys[$x], TRUE); //��������������� ��� � �������� �����
			$a_all_dor_keys_filename[$i][$dor_keys[$x]] = $name_file; //��������� ������ �����_���� => (���� => ���_�����)
			$log_ancor_temp = "<a href=\"".$path_remote."/".$name_file."\">".$dor_keys[$x]."</a>"; 
			$log_bbcode_temp = "[URL=".$path_remote."/".$name_file."]".$dor_keys[$x]."[/URL]";
			$logs_ancor .= $log_ancor_temp."\n"; //��� ������ � ���� ���� ������ � ������� html
			$logs_bbcode .= $log_bbcode_temp."\n"; //��� ������ � ���� ���� ������ � ������� BB code
			$logs_ancor_array[$i][] = $log_ancor_temp; //��� ������������� � ������� relinks ������������
			$logs_bbcode_array[] = $log_bbcode_temp; //��� ������������� � % ����� ��� �����
		}

		$logs_bbcode_spam .= get_perc_array($logs_bbcode_array, $num_perc_spam, 1); //�������� ��� BB ����� ������� ���-��� % ��������� � $num_perc_spam
		unset($logs_bbcode_array); //�������, ��� ��� �����, ������������ � % ����� ��� �����
		unset($dor_keys);
		unset($name_file);
	}
}

{	//���������� ������������ �����. ���������� ����� ���������� � ����� �����
	$count_log_ancor = count($logs_ancor_array);

	@mkdir($out_dir."/".$job_name, 0777, true); //������� ����� � ������ �������, ���� ����� ���������� ��� ���� � ���� �������

	unset ($a_primary_keys, $cached_dor_keys);
	echo "���������� ���������. ������: ".(time()-$t4)." ���.<br>������ ���������<br><script>scroll(0,999999);document.title='������ ���������'</script>";
}
{	//���������� ��� ����
	for($i=0;$i<$count_job;$i++) {
		$t1 = time();
		$a_rand_number = array(); //������� ������ ���������� �������� ������� URAND
		$a_rand_ancor = array(); //������� ������ ���������� �������� ������� RAND_ANCOR
		$a_rand_text = array(); //������� ������ ���������� �������� ������� RAND_TEXT
		$a_rand_url = array(); //������� ������ ���������� �������� ������� RAND_URL
		$a_mem_text = array(); //������� ������ �������� ������� MEM
		$a_page_rand = array(); //������� ������ ���������� �������� ������� PAGE_RAND
		$a_counter_text = array(); //������� ������ �������� ����� ������� CNT
		$a_dor_mem_text = array(); //������� ������ �������� ������� DOR_MEM
		$relinks_ids_array = array(); //������� ������ �������� id ����� ��� ������� ������������ ��� ������� relinks
		unset($dor_rand_number); //������� ��������� �������� ������� DOR__RAND, ������� �������������� ��� ��������� ����������� ����
		unset($dor_urand_number); //������� ��������� �������� ������� DOR__URAND, ������� �������������� ��� ��������� ����������� ����
		unset($maps); //������� ������ �� �������� �� ����� ����
		list ($relinks_ids, $dor_file_keys, $dor_num_keys, $enable_shuffle, $templ_this, $path_remote, $path_local) = explode(",", $job_list[$i]); //������ ��������� �������
		if ($relinks_ids == '') { //���� ������ �������� ������������, �� ��������� �������� ������������ ����� ��������� ����� ��������
			for ($xxx=1;$xxx<=$count_job;$xxx++) {
				if ($xxx != ($i + 1))
					$relinks_ids_array[] = $xxx;
			}
		} else
			$relinks_ids_array = explode(" ", $relinks_ids); //��������� �������� ������������ �� �������
		echo "<b>���������� ��� #".($i+1)."</b> (".$dor_file_keys.", <font color=\"#FF0000\">".$templ_this."</font>, <font color=\"#32CD32\">".$path_remote."</font>, <font color=\"#000080\">".$path_local."</font>)<script>scroll(0,999999);document.title='��� #".($i+1)." / ".$count_job."'</script>";
//		echo "������������ �: <pre>";
//		print_r($relinks_ids_array);
//		echo "</pre>";
		echo "<p style=\"margin-left: 3em;\">����� ������: ".$templ_this."<br>";
		if (!include ($templ_dir."/".$templ_this."/custom_macros.php"))
			die ('<b>������</b>: � ����� � �������� '.$templ_dir."/".$templ_this.' ���������� ������������ ���� custom_macros.php');
		$templ_index = file_get_contents($templ_dir."/".$templ_this."/index.txt") or die("<b>������</b>: �� ������ ���� �������. ���������: ".$templ_dir."/".$templ_this."/index.txt"); 
		$templ_page = file_get_contents($templ_dir."/".$templ_this."/page.txt") or die("<b>������</b>: �� ������ ���� �������. ���������: ".$templ_dir."/".$templ_this."/page.txt");
		$templ_map = file_get_contents($templ_dir."/".$templ_this."/map.txt") or die("<b>������</b>: �� ������ ���� �������. ���������: ".$templ_dir."/".$templ_this."/map.txt");
		$dor_keys = array_keys($a_all_dor_keys_filename[$i]); //����� ��� �� ������� ���������� �� ������� ����
		$a_key_filename = $a_all_dor_keys_filename[$i]; //����� ������ ��� => ��� ����� ��� �������� ����
		$total_key_dor = count($dor_keys); //������� ���-�� ���� ������ �������� ����
		echo "����� ������: ".$total_key_dor."<br>";
		$name_dir = key_to_filename($dor_keys[0]); //��� ����� ����
		$keyword_main = $dor_keys[0]; //��� ������� �������� � ���������
		$bkeyword_main = ucfirst($dor_keys[0]); //��� ������� �������� � ������� 
		$index = "<a href=\"".$a_key_filename[$keyword_main]."\">".$text_ancor_index_link."</a>"; //������ �� ������� �������� ����
		$path_local = $out_dir."/".$job_name."/".$path_local.($path_local!="" ? "/" : null); //���� ���� ��������
		echo "<a href=\"".$path_remote."\" target=\"_blank\">URL</a> ����<br>"; //���� ���� ��������
		echo "��������� ���� ����: <a href=\"".$path_local."\" target=\"_blank\">�����</a><br>";
		@mkdir($path_local, 0777, true); //������� ����� �������� ����, ���� ��������� ��� ��������. ����������

		{	//���������� ����� � ���������� �� ����, � ����� �������� ������ � ������� �� �����
			generate_map($path_local, $count_ancor_in_map);
			echo "������������� ����� ����<br>";
		}

		{	 //�������� ��� ������� �� ����� � ����� � ����� � �����, ��� ���� �� ������� ����� �������� � p_*.txt
			recurse_copy($templ_dir."/".$templ_this, $path_local);
			echo "��� ������� �� ".$templ_this." ����������� � ����� � �����<br>";
		}

		if ($generate_sitemap_xml) {
			create_sitemap_xml($path_local); //������� sitemap.xml
			echo "���� <a href=\"".$path_local."sitemap.xml\" target=\"_blank\">sitemap.xml</a> �����<br>";
		}

		if ($generate_robots_txt) {
			create_robots_txt($path_local); //������� robots.txt
			echo "���� <a href=\"".$path_local."robots.txt\" target=\"_blank\">robots.txt</a> �����<br>";
		}
		unset($links_map); //������� ������ ������ �� sitemap
		
		{	//���������� ��������� ����
			$cp = create_custom_page($path_local);
			echo "��������� ���������������� ������� ���������: ".$cp."<br>";
		}

		for ($cur_page_num=0;$cur_page_num<$total_key_dor;$cur_page_num++) {  //��������� ������� ����
			$a_rand_number = array(); //������� ������ ���������� �������� ������� URAND
			$a_rand_ancor = array(); //������� ������ ���������� �������� ������� RAND_ANCOR
			$a_rand_text = array(); //������� ������ ���������� �������� ������� RAND_TEXT
			$a_rand_url = array(); //������� ������ ���������� �������� ������� RAND_URL
			$a_mem_text = array(); //������� ������ �������� ������� MEM
			$keyword = $dor_keys[$cur_page_num]; //��� ��������
			$name_file = $a_key_filename[$dor_keys[$cur_page_num]]; //��� ����� ����� �� �������: ���� => ��� �����. ������������ �������
			if ($cur_page_num == 0) //���� 0 ��� - ��� ������� ��������
				$out_page = replace_macros_page($templ_index, $access_macros_index); //���� �������� ��������� ���������� index.txt ������
			else
				$out_page = replace_macros_page($templ_page, $access_macros_page); //���� �������� ��������� ���������� page.txt ������
			if ($compression_template) //���� �������� ���������� ������� ������� �� ������� ������� �������� ����� �����������
				$out_page = compression_text($out_page);
			file_put_contents($path_local.$name_file, $out_page, FILE_APPEND); //��������� �������� � ����� ����
		}
		echo "��� �����. ������������ ��: ".(time()-$t1)." ������</p><script>scroll(0,999999);</script></p>";
	}
}

{	//����������� ��������
	echo "����� �������������<br><script>scroll(0,999999);document.title='����� �������������'</script>";
	if ($enable_packing) { //���� �������� ��������� � zip
		$t2 = time();
		include ("lib/pclzip.lib.php");
		try {
			$zip_filename = $job_name.".zip"; //��������� ������ ��� ����� zip
//			@unlink($out_dir."/".$zip_filename); //�������������� ������� zip ����, ��� ��� �� ���������� � ����, ��� �����������
			$zip = new PclZip($out_dir."/".$zip_filename); //������� ��������� ������ ������ ��� �������� ����
			if ($zip->create($out_dir."/".$job_name, PCLZIP_OPT_REMOVE_PATH, $out_dir."/".$job_name)) //������� ��� �� ����� �������, ������ ��������� ���� �� out/[��������_�������]
				echo "Zip ������. <a href=\"".$out_dir."/".$zip_filename."\" target=\"_blank\">".$zip_filename."</a>. ";
			else
				echo "<u>������ �������� zip.</u> ";
			echo "������: ".(time()-$t2)." ������<br>";
			if ($extract_zip_archive) { //���� �������� ����� ��� ��������������� ���������������� �� �������, ��..
				$extract_filename = create_extract_php($out_dir, $job_name, $zip_filename); //..������� php ���� ��� �������������������� �� ������� � �������� ��� �����, ��� ���������� � ������� �������� �� ftp
				echo "���� ��� ��������������� ���������������� �����������<br>";
			}

			if ($upload_ftp) { //���� ���������, �� � ���������� ��� �� ftp
				$t3 = time();
				echo "�������� �������� ".$zip_filename." �� ftp. �� ���������� ����. ��� ����� ������ ��������� �����<br><script>scroll(0,999999);</script>";
				$conn_id = ftp_connect($default_ftp_host, $default_ftp_port, 10); //����������� � ftp ������, 10 ������ �������
				if (!$conn_id)
					throw new Exception("��� �������� � ��������� ������: ".$default_ftp_host."<br>");
				else
					echo "����������� ������� � ������: ".$default_ftp_host."<br>";
				if (!@ftp_login($conn_id, $default_ftp_login, $default_ftp_password)) //��������� � �����
					throw new Exception("������ �����������, ����: ".$default_ftp_login."<br>");
				else
					echo "��������������, ����: ".$default_ftp_login."<br>���������, �����... <script>scroll(0,999999);</script>";
				ftp_pasv($conn_id, true); //��������� � ��������� �����
				if ((ftp_put($conn_id, $default_ftp_path."/".$zip_filename, $out_dir."/".$zip_filename, FTP_BINARY)) and (ftp_put($conn_id, $default_ftp_path."/".$extract_filename, $out_dir."/".$extract_filename, FTP_BINARY))) //�������� ���������� zip �� ftp, ��������� ��� �� ��� � �������� ����
					echo "����� ��������� �������<br>";
				else
					echo "<u>����� �� ���������. ��������� ����</u><br>";
				ftp_close($conn_id); //������� ���������� � ftp
				echo "�������� �� ftp ������ ���������. ������: ".(time()-$t3)." ������<br>";
				if ($extract_zip_archive) { //���� �������� ����� ��� ��������������� ���������������� �� �������, �� �������� ���������� �� �������
					echo "������� ������������� ����������� ����� �� ������: http://".$default_ftp_host."/".$extract_filename."..";
					$answer = @file_get_contents("http://".$default_ftp_host."/".$extract_filename);
					echo $answer;
				}
			}
		} catch (Exception $e) {
			echo $e->getMessage(); //���� ��, ���������
		}
	}

	$all_logs_ancor = $logs_ancor;

	if ($insert_sitemaps_links_in_all_ancor_log)
		$all_logs_ancor = $maps_ancor.$all_logs_ancor;
	if ($insert_sitemaps_links_in_ancor_log)
		$logs_ancor = $maps_ancor.$logs_ancor;
	if ($insert_sitemaps_links_in_bbcode_log)
		$logs_bbcode = $maps_bb.$logs_bbcode;
	if ($insert_sitemaps_links_in_bbcode_log_spam)
		$logs_bbcode_spam = $maps_bb.$logs_bbcode_spam;

	$log_all_ancor = "all_ancor_log.txt"; //��� � �������� ���� ����� ���� �����
	$log_ancor_file_name = $job_name."_ancor_log.txt"; //��� � ��������
	$log_bbcode_file_name = $job_name."_bbcode_log.txt"; //��� � BB ������ ��� �������
	$log_bbcode_file_name_spam = $job_name."_bbcode_log_spam.txt"; //��� � BB ������ ��� ������� � ��������� ������������
	echo "���� ��� �����: <b><a target=\"_blank\" href=\"".$out_dir."/".$log_bbcode_file_name_spam."\">".$log_bbcode_file_name_spam."</a></b> (".($num_perc_spam*100)."% BB ���) � <a target=\"_blank\" href=\"".$out_dir."/".$log_ancor_file_name."\">".$log_ancor_file_name."</a> � <a target=\"_blank\" href=\"".$out_dir."/".$log_bbcode_file_name."\">".$log_bbcode_file_name."</a><br>";

	file_put_contents($out_dir."/".$log_all_ancor, $all_logs_ancor, FILE_APPEND); //��������� all_ancor_log.txt
	file_put_contents($out_dir."/".$log_ancor_file_name, $logs_ancor, FILE_APPEND); //��������� *_ancor_log.txt
	file_put_contents($out_dir."/".$log_bbcode_file_name, $logs_bbcode, FILE_APPEND); //��������� *_bbcode_log.txt
	file_put_contents($out_dir."/".$log_bbcode_file_name_spam, $logs_bbcode_spam, FILE_APPEND); //��������� *_bbcode_log_spam.txt

	echo "����� ����� ������. ������ ".(time()-$time_start)." ������. �����: <b><a href=\"".$out_dir."/\" target=\"_blank\">".$out_dir."</a></b><br>";
 	echo "<b>������� �� ������������� ����� �������. ������ ������ ������ �� ������ ����� �� ����� <a href='http://seodor.ru/dorgen/' target='_blank'>http://seodor.ru/dorgen/</a></b><script>scroll(0,999999);document.title='��� ������! '</script>";
}
?>