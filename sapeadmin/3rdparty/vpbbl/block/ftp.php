<p align="left"><font size="3" face="Verdana">��������� � �������� �� FTP<br></font></p>
<form name="form1" method="post">
   <div align="left">
     <table width="445" cellpadding="0" cellspacing="0" height="324">
        <tr>
           <td width="214" height="30">
                <p align="left"><font size="2" face="Verdana">������� �������� �����:</font></p>
           </td>
           <td width="231" height="30">
                <p align="left"><input type="text" name="q" size="29" style="padding-left:3pt; border-width:1pt; border-color:rgb(185,203,220); border-style:solid;" value="������� �������" maxlength="60"></p>
           </td>
         </tr>
         <tr>
            <td width="214" height="30">
                <p align="left"><font size="2" face="Verdana">������� ���� ���&nbsp;��������:</font></p>
            </td>
            <td width="231" height="30">
                <p align="left"><input type="text" name="nn" size="29" style="padding-left:3pt; border-width:1pt; border-color:rgb(185,203,220); border-style:solid;" value="�������" maxlength="60"></p>
            </td>
          </tr>
          <tr>
             <td width="214" height="30">
                 <p align="left"><font size="2" face="Verdana">������� ���������� �������:</font></p>
             </td>
             <td width="231" height="30">
                 <p align="left"><input type="text" name="count" size="3" style="padding-left:3pt; border-width:1pt; border-color:rgb(185,203,220); border-style:solid;" value="20" maxlength="3"></p>
             </td>
           </tr>
           <tr>
              <td width="214" height="30">
                  <p align="left"><font size="2" face="Verdana">������������ ������������:</font></p>
              </td>
              <td width="231" height="30">
                  <p align="left"><font size="2" face="Verdana"><input type="radio" name="sin" value="yes">�� &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="sin" value="no" checked>���</font></p>
              </td>
           </tr>
           <tr>
               <td width="214" height="40">
                  <p align="left"><font size="2" face="Verdana">������������ ������� <br> (ru-&gt;eng, eng-&gt;ru)</font></p>
               </td>
               <td width="231" height="40">
                  <p align="left"><font size="2" face="Verdana"><input type="radio" name="trans" value="yes">�� &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="trans" value="no" checked>���</font></p>
               </td>
           </tr>
           <tr>
                <td width="214" height="30">
                   <p align="left"><font size="2" face="Verdana">��������� ��������:</font></p>
                </td>
                <td width="231" height="30">
                   <p align="left">
		                <select name="picture" size="1">
		                   <option value="po12">�� 1-2 (��������) �� ������</option>
		                   <option value="po02">�� 0-2 (��������) �� ������</option>
		                   <option value="po03">po 0-3 (��������) �� ������</option>
		                   <option selected value="all">-�� ������-</option>
		                   <option value="on2">�� ������ 2-� ��������</option>
		                   <option value="on3">�� ������ 3-� ��������</option>
		                   <option value="po2">�� 2 �� ������</option>
		                   <option value="no">-��� ��������-</option>
						</select>
				   </p>
                </td>
           </tr>
           <tr>
                 <td width="214" height="30">
                       <p align="left"><font size="2" face="Verdana">����� ������:</font></p>
                 </td>
                 <td width="231" height="30">
                        <p align="left">
                              <select name="names" size="1">
                                 <option selected value="own">���� ����� � ���������</option>
                                 <option value="title">��������� � ���������</option>
                                 <option value="num">���������� �����</option>
                                 <option value="rand">��������� ����� (0-9999999)</option>
                                 <option value="randrazdel">��������� ������ + ����� (0-99)</option>
							  </select>
						</p>
                   </td>
           </tr>
           <tr>
                  <td width="214" height="30">
                       <p align="left"><font size="2" face="Verdana">��� ����������:</font></p>
                  </td>
                  <td width="231" height="30">
                       <p align="left"><input type="radio" name="type" value="php"><font size="2" face="Verdana">PHP &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="type" value="html" checked>HTML</font></p>
                  </td>
            </tr>
            <tr>
                  <td width="214" height="30">
                       <p align="left"><font size="2" face="Verdana">������ ��� ��������</font></p>
                  </td>
                  <td width="231" height="30">
                       <p align="left">
                            <? ddmenu(); ?> <span onclick="na_open_window('Template', 'include/changetpl.php', 200, 50, 350, 650, 0, 0, 0, 1, 0);" style="cursor:pointer;"><font color="#7DA8D3">�������</font></span>
					   </p>
                   </td>
             </tr>
            <tr>
                  <td width="214" height="30">
                       <p align="left"><font size="2" face="Verdana">FTP SERVER</font></p>
                  </td>
                  <td width="231" height="30">
                       <p align="left">
                           <input name="ftpserver" type="text" value="domen.ru">
					   </p>
                   </td>
             </tr>
            <tr>
                  <td width="214" height="30">
                       <p align="left"><font size="2" face="Verdana">FTP USER</font></p>
                  </td>
                  <td width="231" height="30">
                       <p align="left">
                           <input name="ftpuser" type="text" value="login">
					   </p>
                   </td>
             </tr>
            <tr>
                  <td width="214" height="30">
                       <p align="left"><font size="2" face="Verdana">FTP PASSWORD</font></p>
                  </td>
                  <td width="231" height="30">
                       <p align="left">
                           <input name="ftppass" type="text" value="password">
					   </p>
                   </td>
             </tr>
            <tr>
                  <td width="214" height="30">
                       <p align="left"><font size="2" face="Verdana">FTP PATCH</font></p>
                  </td>
                  <td width="231" height="30">
                       <p align="left">
                           <input name="ftppatch" type="text" value="/public_html">
					   </p>
                   </td>
             </tr>

             <tr>
                   <td width="445" height="44" colspan="2">&nbsp;</td>
             </tr>
             <tr>
                   <td width="445" height="44" colspan="2">
                        <p align="center"><input type="submit" value="������������"></p>
                   </td>
             </tr>
      </table>
   </div>
</form>
