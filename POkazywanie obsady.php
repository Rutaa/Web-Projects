<?php 
//Pokazywanie obsady
$connect = new mysqli(); // You have to fullfil the informations for the database.
//if(current_user_can('administrator')) echo '<form method="post"><input type="submit" name="edycja" value="Edytuj Obsadę"></form>';
$currentdate = date("Y-m-d");
if(current_user_can('administrator')) echo '<form method="post"><input type="submit" name="edycja" value="Edytuj Obsadę"></form>';
if(current_user_can('administrator')) echo '<form method="post"><input type="submit" name="pelna_edycja" value="Pelna Edycja Obsady"></form>';
if(current_user_can('administrator')) {
    if(isset($_POST['accept'])) {
    $id_usera = $_POST['id_sedzi'];
    $id_meczu = $_POST['id_meczu'];
    if($result = $connection->query("SELECT * FROM mecze WHERE id_meczu='".$id_meczu."'"))
    {
    $w = $result->fetch_assoc();
    
    
    if($id_usera==$w['glowny_sedzia']){
        if($w['s']==0||$w['s']==-1) {
            $connection->query("UPDATE mecze SET s=1 WHERE id_meczu='".$id_meczu."'");
        }
        if($w['s']==1) {
            $connection->query("UPDATE mecze SET s=0 WHERE id_meczu='".$id_meczu."'");
        }
    }
    if($id_usera==$w['asystent_sedzia']){
        if($w['as1']==0||$w['as1']==-1) {
            $connection->query("UPDATE mecze SET as1=1 WHERE id_meczu='".$id_meczu."'");
        }
        else {
            $connection->query("UPDATE mecze SET as1=0 WHERE id_meczu='".$id_meczu."'");
        }
    }
    if($id_usera==$w['asystent_sedzia_2']){
        if($w['as2']==0||$w['as2']==-1) {
            $connection->query("UPDATE mecze SET as2=1 WHERE id_meczu='".$id_meczu."'");
        }
        else {
            $connection->query("UPDATE mecze SET as2=0 WHERE id_meczu='".$id_meczu."'");
        }
    }
}
}
}
else {
    if(isset($_POST['accept'])) {
    $id_usera = get_current_user_id();
    $id_meczu = $_POST['id_meczu'];
    if($result = $connection->query("SELECT * FROM mecze WHERE id_meczu='".$id_meczu."'"))
    {
    $w = $result->fetch_assoc();
    
    
    if($id_usera==$w['glowny_sedzia']){
        if($w['s']==0) {
            $connection->query("UPDATE mecze SET s=1 WHERE id_meczu='".$id_meczu."'");
        }
        if($w['s']==1) {
            $connection->query("UPDATE mecze SET s=-1 WHERE id_meczu='".$id_meczu."'");
        }
    }
    if($id_usera==$w['asystent_sedzia']){
        if($w['as1']==0) {
            $connection->query("UPDATE mecze SET as1=1 WHERE id_meczu='".$id_meczu."'");
        }
        else {
            $connection->query("UPDATE mecze SET as1=-1 WHERE id_meczu='".$id_meczu."'");
        }
    }
    if($id_usera==$w['asystent_sedzia_2']){
        if($w['as2']==0) {
            $connection->query("UPDATE mecze SET as2=1 WHERE id_meczu='".$id_meczu."'");
        }
        else {
            $connection->query("UPDATE mecze SET as2=-1 WHERE id_meczu='".$id_meczu."'");
        }
    }
}
}
}

if(isset($_POST['pelna_e']))
{
    $ile_meczy = count($_POST['miejsce_pe']);
    for($i=0;$i<$ile_meczy;$i++){
        $gosc = $_POST['gosc_pe'][$i];
        $gospodarz = $_POST['gospodarz_pe'][$i];
        $data = $_POST['data_pe'][$i];
        $godzina = $_POST['godzina_pe'][$i];
        $id_meczu = $_POST['id_meczu'][$i];
        $miejsce = $_POST['miejsce_pe'][$i];
        $klasa = $_POST['klasa_pe'][$i];
        $klasa = $connection->query("SELECT * FROM klasy WHERE id_klasy='".$klasa."'");
        $nowaklasa = $klasa->fetch_assoc();
        $klasa = $nowaklasa['kod'].' '.$nowaklasa['skrot'];
        $ok = true;
        $sedzia = $_POST['glowny_sedzia_pe'][$i];
        $sedziaas = $_POST['asystent_sedzia_pe'][$i];
        $sedziaas2 = $_POST['asystent_sedzia_2_pe'][$i];
        if(!(($sedzia==0&&$sedziaas==0)||($sedzia==0&&$sedziaas2==0)||($sedziaas2==0&&$sedziaas==0)))
        {
        if($sedzia==$sedziaas||$sedziaas==$sedziaas2||$sedzia==$sedziaas2) {
            $ok= false;
            echo '<div style="color:red">Sędzia nie może być na raz asystentem i sędzią głównym! ID meczu: '.($i+1).' </div>';
        }
        }
        $nr = $i+1;
        if(isset($_POST["usun_pe_$nr"][0])){
            $connection->query("DELETE FROM mecze WHERE id_meczu=".$id_meczu);
        }
        
        if($ok){
        if($connection->query("UPDATE mecze SET klasa='".$klasa."', gospodarz='".$gospodarz."', gosc='".$gosc."', data='".$data."', godzina='".$godzina."', glowny_sedzia='".$sedzia."', asystent_sedzia='".$sedziaas."', asystent_sedzia_2='".$sedziaas2."', miejsce='".$miejsce."', s=0, as2=0, as1=0, uwagi='".$uwagi."' WHERE id_meczu='".$id_meczu."'"))
        {
        }
        else {
            echo "Nie udało się";
        }}
        }
}

if($_POST['zmien']=='ZMIEN'){
    $gosc = $_POST['gosc_up'];
    $gospodarz = $_POST['gospodarz_up'];
    $data = $_POST['data_up'];
    $godzina = $_POST['godzina_up'];
    $uwagi = $_POST['uwagi'];
    $id_meczu = $_POST['id'] ;
    $miejsce = $_POST['miejsce'];
    $klasa = $_POST['klasa'];
    $klasa = $connection->query("SELECT * FROM klasy WHERE id_klasy='".$klasa."'");
    $nowaklasa = $klasa->fetch_assoc();
    $klasa = $nowaklasa['kod'].' '.$nowaklasa['skrot'];
    $ok = true;
    $sedzia = $_POST['sedzia'];
    $sedziaas = $_POST['sedzia_asystent'];
    $sedziaas2 = $_POST['sedzia_asystent2'];
    if(!(($sedzia==0&&$sedziaas==0)||($sedzia==0&&$sedziaas2==0)||($sedziaas2==0&&$sedziaas==0)))
    {
    if($sedzia==$sedziaas||$sedziaas==$sedziaas2||$sedzia==$sedziaas2) {
        $ok= false;
        echo "Błąd.";
    }
    }
    if($ok){
    if($connection->query("UPDATE mecze SET klasa='".$klasa."', gospodarz='".$gospodarz."', gosc='".$gosc."', data='".$data."', godzina='".$godzina."', glowny_sedzia='".$sedzia."', asystent_sedzia='".$sedziaas."', asystent_sedzia_2='".$sedziaas2."', miejsce='".$miejsce."', s=0, as2=0, as1=0, uwagi='".$uwagi."' WHERE id_meczu='".$id_meczu."'"))
    {
        echo "sukces";
    }
    else {
        echo "Nie udało się";
    }}
}
if($_POST['usun']=='USUN') {
    $id_meczu = $_POST['id'];
    if($connection->query("DELETE FROM mecze WHERE id_meczu='".$id_meczu."'")) {
        echo "Pomyślnie usunieto.";
    }
}
if(isset($_POST['edycja'])||isset($_POST['pelna_edycja']))
{
    if(isset($_POST['pelna_edycja'])) {
        echo '<form method="post"><table><tr><th>LP</th><th>Klasa</th><th>Gospodarz</th><th>Gość</th><th>Data</th><th>Godzina</th><th>Główny sędzia</th><th>Sędzia asystent</th><th>Sędzia asystent 2</th><th>Miejsce</th><th>Uwagi</th><th>Akcja</th></tr>';
        if($result = $connection->query("SELECT * FROM mecze WHERE data>='$currentdate'")) {
            $ile = $result->num_rows;
        if($ile>0)
        {
           for($i=1;$i<=$ile;$i++)
           {
               $wiersz = $result->fetch_assoc();
               $id_meczu = $wiersz['id_meczu'];
               $klasa = substr($wiersz['klasa'], 0, 5);
               $klasyresult = $connection->query("SELECT pelna_nazwa FROM klasy WHERE kod='$klasa'");
               $wiersz_2 = $klasyresult->fetch_assoc();
               $selectresult = $connection->query("SELECT * FROM klasy");
               $sr_ile = $selectresult->num_rows;
               $select = '<select name="klasa_pe[]">';
               for($j=0; $j<$sr_ile;$j++)
               {
                   $w = $selectresult->fetch_assoc();
                   if($w['pelna_nazwa']==$wiersz_2['pelna_nazwa'])
                   {
                       $select .= '<option value="'.$w['id_klasy'].'" title="'.$w['pelna_nazwa'].'" selected="selected">'.$w['skrot'].' '.$w['kod'].'</option>';
                   }
                   else $select .= '<option value="'.$w['id_klasy'].'" title="'.$w['pelna_nazwa'].'">'.$w['skrot'].' '.$w['kod'].'</option>';
                
               }
               $select .= '</select>'; // klasy
               $sedziowieresult = $connection->query("SELECT * FROM wp_users WHERE user_biegi=1 AND user_badania>'$currentdate'");
               $datameczu = date('N', strtotime($wiersz['data']));
               $godzinameczu = $wiersz['godzina'];
               $select_sedzia  = '<select name="glowny_sedzia_pe[]"><option value=0>-Brak sedzi-</option>';
               $select_sedzia_2 = '<select name="asystent_sedzia_pe[]"><option value=0>-Brak sedzi-</option>';
               $select_sedzia_3 = '<select name="asystent_sedzia_2_pe[]"><option value=0>-Brak sedzi-</option>';
               for($j=0;$j<$sedziowieresult->num_rows;$j++) {
                   $w = $sedziowieresult->fetch_assoc();
                   $urlopyresult = $connection->query("SELECT * FROM urlopy WHERE id_sedzi = ".$w['ID']);
                   $ok = true;
                   if($urlopyresult->num_rows>0){
                       for($k=0;$k<$urlopyresult->num_rows;$k++)
                       {
                           $urlop_wiersz = $urlopyresult->fetch_assoc();
                           //seria warunkow
                           if($urlop_wiersz['potwierdzony']==1)
                           {
                               if(($datameczu>=1)&&($datameczu<=5))
                               {
                                   if($urlop_wiersz['robocze']==1)
                                   {
                                        if(($godzinameczu>=$urlop_wiersz['us_odr'])&&($godzinameczu<=$urlop_wiersz['us_dor']))
                                        {
                                            $ok = false;
                                        }
                                   }
                               }
                               if(($datameczu>=6)&&($datameczu<=7)){
                                   if($urlop_wiersz['weekendy']==1) 
                                   {
                                       if(($godzinameczu>=$urlop_wiersz['us_odw'])&&($godzinameczu<=$urlop_wiersz['us_dow'])) {
                                           $ok = false;
                                       }
                                   }
                               }
                               if($urlop_wiersz['wyznaczone']==1) {
                                   if(($datameczu>=$urlop_wiersz['dniod'])&&($datameczu<=$urlop_wiersz['dnido'])){
                                       if(($godzinameczu>=$urlop_wiersz['us_odp'])&&($godzinameczu<=$urlop_wiersz['us_dop'])) {
                                           $ok = false;
                                       }
                                   }
                               }
                               if(($urlop_wiersz['robocze']==0)&&($urlop_wiersz['weekendy']==0)&&($urlop_wiersz['wyznaczone']==0))
                               {
                                   if(($wiersz['data']>=$urlop_wiersz['od'])&&($wiersz['data']<=$urlop_wiersz['do']))
                                   {
                                       $ok = false;
                                   }
                               }
                           }
                       }
                       if($ok==true)
                       {
                           if($w['ID']==$wiersz['glowny_sedzia'])
                           {
                               $select_sedzia .='<option value="'.$w['ID'].'" selected>'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia_2 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia_3 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                           }
                           if($w['ID']==$wiersz['asystent_sedzia'])
                           {
                               $select_sedzia_2 .='<option value="'.$w['ID'].'" selected>'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia_3 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                           }
                           if($w['ID']==$wiersz['asystent_sedzia_2'])
                           {
                               $select_sedzia_3 .='<option value="'.$w['ID'].'" selected>'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia_2 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                           }
                           if($w['ID']!=$wiersz['glowny_sedzia']&&$w['ID']!=$wiersz['asystent_sedzia']&&$w['ID']!=$wiersz['asystent_sedzia_2']) {
                            $select_sedzia .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                            $select_sedzia_2 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                            $select_sedzia_3 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                           }
                       }
                   }
                   else {
                       if($w['ID']==$wiersz['glowny_sedzia'])
                           {
                               $select_sedzia .='<option value="'.$w['ID'].'" selected>'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia_2 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia_3 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                           }
                        if($w['ID']==$wiersz['asystent_sedzia'])
                           {
                               $select_sedzia_2 .='<option value="'.$w['ID'].'" selected>'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia_3 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                           }
                        if($w['ID']==$wiersz['asystent_sedzia_2'])
                           {
                                $select_sedzia_3 .='<option value="'.$w['ID'].'" selected>'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia_2 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                           }
                           if($w['ID']!=$wiersz['glowny_sedzia']&&$w['ID']!=$wiersz['asystent_sedzia']&&$w['ID']!=$wiersz['asystent_sedzia_2']) {
                            $select_sedzia .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                            $select_sedzia_2 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                            $select_sedzia_3 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                           }
                   }
               }
               $select_sedzia .= '</select>';
               $select_sedzia_2 .='</select>';
               $select_sedzia_3 .='</select>';
               echo '<tr><td>'.$i.'</td><td>'.$select.'</td><td><input type="text" name="gospodarz_pe[]" value="'.$wiersz['gospodarz'].'"></td><td><input type="text" name =" gosc_pe[]" value="'.$wiersz['gosc'].'"></td><td><input type="date" name="data_pe[]" value="'.$wiersz['data'].'"></td><td><input type="time" name="godzina_pe[]" value="'.$wiersz['godzina'].'"></td><td>'.$select_sedzia.'</td><td>'.$select_sedzia_2.'</td><td>'.$select_sedzia_3.'</td><td><input type="text" name="miejsce_pe[]" value="'.$wiersz['miejsce'].'"></td><td><input type="text" value="'.$wiersz['uwagi'].'" name="uwagi_pe[]"></td><td><label><input type="checkbox" name="usun_pe_'.$i.'[]"> Usuń</label><input type="text" class="hidden" name="id_meczu[]" value="'.$id_meczu.'"></tr>';
        }
        echo '</table><input type="submit" value="ZATWIERDŹ ZMIANY" name="pelna_e"></form>';
        }
        else {
            echo 'Brak meczy!';
        }
    }
    }
    
    if(isset($_POST['edycja'])){
    echo '<div class="tabela"><div class="tabela-wiersz"><div class="tabela-kolumna h">LP</div><div class="tabela-kolumna h">Klasa</div><div class="tabela-kolumna h">Gospodarz</div><div class="tabela-kolumna h">Gość</div><div class="tabela-kolumna h">Data</div><div class="tabela-kolumna h">Godzina</div><div class="tabela-kolumna h">Glowny Sedzia</div><div class="tabela-kolumna h">Sedzia Asystent</div><div class="tabela-kolumna h">Sedzia Asystent 2</div><div class="tabela-kolumna h">Miejsce</div><div class="tabela-kolumna h">Uwagi</div><div class="tabela-kolumna h">Opcja</div></div>';
    if ($result = $connection->query("SELECT * FROM mecze WHERE data>='$currentdate'"))
    {
        $ile = $result->num_rows;
        if($ile>0)
        {
           for($i=1;$i<=$ile;$i++)
           {
               $wiersz = $result->fetch_assoc();
               $klasa = substr($wiersz['klasa'], 0, 5);
               $klasyresult = $connection->query("SELECT pelna_nazwa FROM klasy WHERE kod='$klasa'");
               $wiersz_2 = $klasyresult->fetch_assoc();
               $selectresult = $connection->query("SELECT * FROM klasy");
               $sr_ile = $selectresult->num_rows;
               $select = '<select name="klasa">';
               for($j=0; $j<$sr_ile;$j++)
               {
                   $w = $selectresult->fetch_assoc();
                   if($w['pelna_nazwa']==$wiersz_2['pelna_nazwa'])
                   {
                       $select .= '<option value="'.$w['id_klasy'].'" title="'.$w['pelna_nazwa'].'" selected="selected">'.$w['skrot'].' '.$w['kod'].'</option>';
                   }
                   else $select .= '<option value="'.$w['id_klasy'].'" title="'.$w['pelna_nazwa'].'">'.$w['skrot'].' '.$w['kod'].'</option>';
                
               }
               $select .= '</select>'; // klasy
               $sedziowieresult = $connection->query("SELECT * FROM wp_users WHERE user_biegi=1 AND user_badania>'$currentdate'");
               $datameczu = date('N', strtotime($wiersz['data']));
               $godzinameczu = $wiersz['godzina'];
               $select_sedzia  = '>';
               $select_sedzia_2 = '>';
               $select_sedzia_3 = '>';
               for($j=0;$j<$sedziowieresult->num_rows;$j++) {
                   $w = $sedziowieresult->fetch_assoc();
                   $urlopyresult = $connection->query("SELECT * FROM urlopy WHERE id_sedzi = ".$w['ID']);
                   $ok = true;
                   if($urlopyresult->num_rows>0){
                       for($k=0;$k<$urlopyresult->num_rows;$k++)
                       {
                           $urlop_wiersz = $urlopyresult->fetch_assoc();
                           //seria warunkow
                           if($urlop_wiersz['potwierdzony']==1)
                           {
                               if(($datameczu>=1)&&($datameczu<=5))
                               {
                                   if($urlop_wiersz['robocze']==1)
                                   {
                                        if(($godzinameczu>=$urlop_wiersz['us_odr'])&&($godzinameczu<=$urlop_wiersz['us_dor']))
                                        {
                                            $ok = false;
                                        }
                                   }
                               }
                               if(($datameczu>=6)&&($datameczu<=7)){
                                   if($urlop_wiersz['weekendy']==1) 
                                   {
                                       if(($godzinameczu>=$urlop_wiersz['us_odw'])&&($godzinameczu<=$urlop_wiersz['us_dow'])) {
                                           $ok = false;
                                       }
                                   }
                               }
                               if($urlop_wiersz['wyznaczone']==1) {
                                   if(($datameczu>=$urlop_wiersz['dniod'])&&($datameczu<=$urlop_wiersz['dnido'])){
                                       if(($godzinameczu>=$urlop_wiersz['us_odp'])&&($godzinameczu<=$urlop_wiersz['us_dop'])) {
                                           $ok = false;
                                       }
                                   }
                               }
                               if(($urlop_wiersz['robocze']==0)&&($urlop_wiersz['weekendy']==0)&&($urlop_wiersz['wyznaczone']==0))
                               {
                                   if(($wiersz['data']>=$urlop_wiersz['od'])&&($wiersz['data']<=$urlop_wiersz['do']))
                                   {
                                       $ok = false;
                                   }
                               }
                           }
                       }
                       if($ok==true)
                       {
                           if($w['ID']==$wiersz['glowny_sedzia'])
                           {
                               $select_sedzia .='<option value="'.$w['ID'].'" selected>'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia_2 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia_3 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                           }
                           if($w['ID']==$wiersz['asystent_sedzia'])
                           {
                               $select_sedzia_2 .='<option value="'.$w['ID'].'" selected>'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia_3 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                           }
                           if($w['ID']==$wiersz['asystent_sedzia_2'])
                           {
                               $select_sedzia_3 .='<option value="'.$w['ID'].'" selected>'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia_2 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                           }
                           if($w['ID']!=$wiersz['glowny_sedzia']&&$w['ID']!=$wiersz['asystent_sedzia']&&$w['ID']!=$wiersz['asystent_sedzia_2']) {
                            $select_sedzia .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                            $select_sedzia_2 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                            $select_sedzia_3 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                           }
                       }
                   }
                   else {
                       if($w['ID']==$wiersz['glowny_sedzia'])
                           {
                               $select_sedzia .='<option value="'.$w['ID'].'" selected>'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia_2 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia_3 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                           }
                        if($w['ID']==$wiersz['asystent_sedzia'])
                           {
                               $select_sedzia_2 .='<option value="'.$w['ID'].'" selected>'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia_3 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                           }
                        if($w['ID']==$wiersz['asystent_sedzia_2'])
                           {
                                $select_sedzia_3 .='<option value="'.$w['ID'].'" selected>'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia_2 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                               $select_sedzia .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                           }
                           if($w['ID']!=$wiersz['glowny_sedzia']&&$w['ID']!=$wiersz['asystent_sedzia']&&$w['ID']!=$wiersz['asystent_sedzia_2']) {
                            $select_sedzia .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                            $select_sedzia_2 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                            $select_sedzia_3 .='<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
                           }
                   }
               }
               $select_sedzia .= '</select>';
               $select_sedzia_2 .='</select>';
               $select_sedzia_3 .='</select>';
               
               if(strlen($wiersz['gospodarz'])>0)
               {
                   print '<form class="tabela-wiersz" method="post"><div class="tabela-kolumna">'.$i.'<input type="text" value="'.$wiersz['id_meczu'].'" name="id" class="hidden"></div><div class="tabela-kolumna">'.$select.'</div><div class="tabela-kolumna"><input type="text" value="'.$wiersz['gospodarz'].'" name="gospodarz_up"></div><div class="tabela-kolumna"><input type="text" name="gosc_up" value="'.$wiersz['gosc'].'"></div><div class="tabela-kolumna"><input type="date" name="data_up" value="'.$wiersz['data'].'"></div><div class="tabela-kolumna"><input type="time" value="'.$wiersz['godzina'].'" name="godzina_up"></div><div class="tabela-kolumna"><select name="sedzia">'.'<option value="0">--Bez Sedzi--</option'.$select_sedzia.'</div><div class="tabela-kolumna"><select name="sedzia_asystent">'.'<option value="0">--Bez Sedzi--</option'.$select_sedzia_2.'</div><div class="tabela-kolumna"><select name="sedzia_asystent2">'.'<option value="0">--Bez Sedzi--</option'.$select_sedzia_3.'</div><div class="tabela-kolumna"><input type="text" value="'.$wiersz['miejsce'].'" name="miejsce"></div><div class="tabela-kolumna"><input type="text" value="'.$wiersz['uwagi'].'" name = "uwagi"></div><div class="tabela-kolumna"><input type="submit" name="usun" value="USUN"><input type="submit" name="zmien" value="ZMIEN"></div></form>';
               }
           }
        }
        else echo "brak meczy";
    }
    echo "</div>";
}
}
else {
if($result = $connection->query("SELECT * FROM mecze WHERE data>='$currentdate' AND potwierdzony=1"))
{
    $ile = $result->num_rows;
    if($ile>0)
    {
        echo '<table><tr><th>Lp.</th><th>Klasa</th><th>Gospodarz</th><th>Gość</th><th>Data</th><th>Godzina</th><th>Główny sędzia</th><th>Asystent Sędzi</th><th>2 Asystent Sędzi</th><th>Miejsce</th><th>Uwagi</th></tr>';
        for($i=1; $i<=$ile; $i++)
        {
            
        $wiersz = $result->fetch_assoc();
        $id_usera = get_current_user_id();
            $as1=false;//asystent sedzi
            $as2=false;
            $s = false;
            if($wiersz['glowny_sedzia']==$id_usera) {
                $s = true;
            }
            if($wiersz['asystent_sedzia']==$id_usera) {
                $as1 = true;
            }
            if($wiersz['asystent_sedzia_2']==$id_usera) {
                $as2 = true;
            }
            if(current_user_can('administrator')) {
                $s = true;
                $as1 = true;
                $as2 = true;
                if($wiersz['glowny_sedzia']==0)
                {
                    $s = false;
                }
                if($wiersz['asystent_sedzia']==0) {
                    $as1 = false;
                }
                if($wiersz['asystent_sedzia_2']==0) {
                    $as2 = false;
                }
            }
            $id_s = $wiersz['glowny_sedzia'];
            $id_as = $wiersz['asystent_sedzia'];
            $id_as2 = $wiersz['asystent_sedzia_2'];
            if(!(get_current_user_id())) {
                $as2 = false;
                $s = false;
                $as1 = false;
            }
        if(strlen($wiersz['gospodarz'])>0)
        {
        $klasa = substr($wiersz['klasa'], 0, 5);
        if($wiersz['glowny_sedzia']==0)
        {
            $wiersz['glowny_sedzia'] = "--brak sedzi--";
        }
        else{
        $wiersz['glowny_sedzia'] = $connection->query("SELECT * FROM wp_users WHERE ID='".$wiersz['glowny_sedzia']."'");
        $wiersz['glowny_sedzia'] = $wiersz['glowny_sedzia']->fetch_assoc();
        $wiersz['glowny_sedzia'] = $wiersz['glowny_sedzia']['user_imie'].' '.$wiersz['glowny_sedzia']['user_nazwisko'];
        }
        if($wiersz['asystent_sedzia']==0) {
            $wiersz['asystent_sedzia'] = "--brak sedzi--";
        }
        else{
        $wiersz['asystent_sedzia'] = $connection->query("SELECT * FROM wp_users WHERE ID='".$wiersz['asystent_sedzia']."'");
            $wiersz['asystent_sedzia'] = $wiersz['asystent_sedzia']->fetch_assoc();
            $wiersz['asystent_sedzia'] = $wiersz['asystent_sedzia']['user_imie'].' '.$wiersz['asystent_sedzia']['user_nazwisko'];
        }
        if($wiersz['asystent_sedzia_2']==0) {
            $wiersz['asystent_sedzia_2'] = "--brak sedzi--";
        }
        else {
        $wiersz['asystent_sedzia_2'] = $connection->query("SELECT * FROM wp_users WHERE ID='".$wiersz['asystent_sedzia_2']."'");
        $wiersz['asystent_sedzia_2'] = $wiersz['asystent_sedzia_2']->fetch_assoc();
        $wiersz['asystent_sedzia_2'] = $wiersz['asystent_sedzia_2']['user_imie'].' '.$wiersz['asystent_sedzia_2']['user_nazwisko'];
        }
        $klasyresult = $connection->query("SELECT skrot FROM klasy WHERE kod='$klasa'");
        $wiersz_2 = $klasyresult->fetch_assoc();
            if($s){
                
                if($wiersz['s']==0||$wiersz['s']==-1)
                {
                $wiersz['glowny_sedzia'] = '<form method="post"><input type="submit" style="background-color:red;" name="accept" value="'.$wiersz['glowny_sedzia'].'"><input type="text" value="'.$id_s.'" name="id_sedzi" class="hidden"><input type="text" name="id_meczu" value="'.$wiersz['id_meczu'].'" class="hidden"></form>';
                }
                else {
                   $wiersz['glowny_sedzia'] = '<form method="post"><input type="submit" style="background-color:green;" name="accept" value="'.$wiersz['glowny_sedzia'].'"><input type="text" value="'.$id_s.'" name="id_sedzi" class="hidden"><input type="text" name="id_meczu" value="'.$wiersz['id_meczu'].'" class="hidden"></form>'; 
                }
            }
            if($as1){
                
                if($wiersz['as1']==0||$wiersz['as1']==-1)
                {
                $wiersz['asystent_sedzia'] = '<form method="post"><input type="submit" style="background-color:red;" name="accept" value="'.$wiersz['asystent_sedzia'].'"><input type="text" value="'.$id_as.'" name="id_sedzi" class="hidden"><input type="text" name="id_meczu" value="'.$wiersz['id_meczu'].'" class="hidden"></form>';
                }
                else {
                   $wiersz['asystent_sedzia'] = '<form method="post"><input type="submit" style="background-color:green;" name="accept" value="'.$wiersz['asystent_sedzia'].'"><input type="text" value="'.$id_as.'" name="id_sedzi" class="hidden"><input type="text" name="id_meczu" value="'.$wiersz['id_meczu'].'" class="hidden"></form>'; 
                }
            }
            if($as2){
                
                if($wiersz['as2']==0||$wiersz['as2']==-1)
                {
                $wiersz['asystent_sedzia_2'] = '<form method="post"><input type="submit" style="background-color:red;" name="accept" value="'.$wiersz['asystent_sedzia_2'].'"><input type="text" value="'.$id_as2.'" name="id_sedzi" class="hidden"><input type="text" name="id_meczu" value="'.$wiersz['id_meczu'].'" class="hidden"></form>';
                }
                else {
                   $wiersz['asystent_sedzia_2'] = '<form method="post"><input type="submit" style="background-color:green;" name="accept" value="'.$wiersz['asystent_sedzia_2'].'"><input type="text" value="'.$id_as2.'" name="id_sedzi" class="hidden"><input type="text" name="id_meczu" value="'.$wiersz['id_meczu'].'" class="hidden"></form>'; 
                }
            }
			if(!($id_usera==$id_s||$id_usera==$id_as||$id_usera==$id_as2||current_user_can('administrator'))) {
				$wiersz['glowny_sedzia'] = '';
				$wiersz['asystent_sedzia'] = '';
				$wiersz['asystent_sedzia_2'] = '';
			}
			if(!(get_current_user_id())) {
                $wiersz['glowny_sedzia'] = '';
				$wiersz['asystent_sedzia'] = '';
				$wiersz['asystent_sedzia_2'] = '';
            }
        echo '<tr><td>'.$i.'</td><td>'.$wiersz_2['skrot'].'</td><td>'.$wiersz['gospodarz'].'</td><td>'.$wiersz['gosc'].'</td><td>'.$wiersz['data'].'</td><td>'.$wiersz['godzina'].'</td><td>'.$wiersz['glowny_sedzia'].'</td><td>'.$wiersz['asystent_sedzia'].'</td><td>'.$wiersz['asystent_sedzia_2'].'</td><td>'.$wiersz['miejsce'].'</td><td>'.$wiersz['uwagi'].'</td></tr>';
        }
        }
        echo '</table>';
        
    }
    else {
        echo "Brak meczy";
    }
    
}else echo "Błąd, proszę skontaktować się z administratorem.";}
?>