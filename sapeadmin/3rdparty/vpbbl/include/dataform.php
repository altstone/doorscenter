<?
//������ ��������� �����
	remove_dir('../out',true);
	//��������� ����������
	require_once('common.php');
	// ��������� ����������
	$morphy =& new phpMorphy(
		new phpMorphy_FilesBundle()
	);

//������ ���� �� ����� ������
$tt=0;
	foreach ($outcontent as $key=>$line){
	echo '������� ������ �: [<b>'.($key+1).'</b>]'. "</b><br>\r\n";
	    echo '<script language="JavaScript">scrl(30)</script>';
		flush();
	//������������ ������ ��������:
	//�������� �� �����
	$outtitle[$key]=str_replace("���","",strip_tags($outtitle[$key]));
	$outcontent[$key]=str_replace("���","",substr($outcontent[$key],0,$config['maxlen']));
	$array[$tt]['title']=$outtitle[$key];
	$textos=obtext($outcontent[$key],$outtitle[$key],$tt);
	$array[$tt]['text']=$textos;
	 		//��������� �������� �����
	$array[$tt]['keywords2']=keywords($outcontent[$key]);
	$array[$tt]['keywords']=join(", ",$array[$tt]['keywords2']);
	if((count($array[$tt]['keywords'])>0)){
				//��������� ���������
		$array[$tt]['description']=description($outcontent[$key]);
		 		//��������� ������ ����
		$array[$tt]['oblako']=$array[$tt]['keywords2'];
		 		//��������� ������ ����
		$array[$tt]['time']=time()-(count($outcontent)-$tt)*rand(70,100)*1000+rand(0,999);
		 		//��������� ��������� ��������
		$array[$tt]['news']=news($outcontent[$key]);
		 		//��������� �������� ��������

		  		$word=@checkword($array[$tt]['keywords2'],$wordas,$key);
				$wordas[]=$word;
				if($_GET['mymenu']!="true"){
		$array[$tt]['smalltitle']=$word;
		}else{
		if($tt<5){		$array[$tt]['smalltitle']=$_GET['word'.($tt+1)];		}else{		$array[$tt]['smalltitle']=$word;
		}		}
				//��������� �������� ���
		 		if($tt===0){
		$array[$tt]['name']="index";
		 			}else{
		 			switch ($_GET['names']) {
        case "own":
        $array[$tt]['name']=translit($array[$tt]['smalltitle']);

          break;
        case "title":
        $array[$tt]['name']=translit($array[$tt]['title']);
          break;
        case "num":
        $array[$tt]['name']=$tt;
          break;
        case "rand":
        $array[$tt]['name']=rand(0,9999999);
          break;
         case "randrazdel":
        $array[$tt]['name']=$config['razdel'][rand(0,(count($config['razdel'])-1))]."-".rand(0,9999);
          break;
      }
		}

		$tt++;
	}
	}
 echo '����������� ������ ��� ������������ ��������'. "<br>\r\n";
		    echo '<script language="JavaScript">scrl(30)</script>';
	flush();


?>