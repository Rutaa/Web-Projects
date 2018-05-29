<?php
function Bolder($ob_prop,$id, $polaczenie) {
	$returner = '<td>';
	$nw_jaka_nazwa_zmiennej = $polaczenie->query("SELECT * FROM wp_users WHERE ID=$ob_prop");
	$nw_jaka_nazwa_zmiennej = $nw_jaka_nazwa_zmiennej->fetch_assoc();
	if($ob_prop==$id){
		$returner = $returner.'<b>'.$nw_jaka_nazwa_zmiennej['user_imie'].' '.$nw_jaka_nazwa_zmiennej['user_nazwisko'].'</b></td>';
	}
	else if ($ob_prop == 0) {
		$returner = $returner.'--brak sedziego--</td>';
	}
	else {
		$returner = $returner.$nw_jaka_nazwa_zmiennej['user_imie'].' '.$nw_jaka_nazwa_zmiennej['user_nazwisko'].'</td>';
	}
	return $returner;
	
	
} 
if(is_user_logged_in()) {
    echo "Lista meczy w których brałeś udział: ";
    $connect = new mysqli(); // You have to fullfil the informations for the database.
    $logged_user = wp_get_current_user();
    $id_usera = $logged_user->ID;
    $currentdate = date("Y-m-d");
    if($_POST['rok']) {
        $rok = $_POST['rok'];
    } 
    else {
        $rok = 'all';
    }
    for($i=date('Y')-1; $i>2000; $i--) {
        if($result = $connect->query("SELECT * FROM mecze WHERE data<'".$currentdate."' AND glowny_sedzia=".$id_usera." OR asystent_sedzia=".$id_usera." OR asystent_sedzia_2=".$id_usera)) {
            if($result->num_rows>0)
            {
                for($k=0;$k<$result->num_rows;$k++)
                {
                    $w = $result->fetch_assoc();
                    $ok=true;
                    if(($w['glowny_sedzia']==$id_usera&&$w['s']==0)){
                        $ok=false;
                    }
                    if(($w['asystent_sedzia']==$id_usera&&$w['as1']==0)) {
                        $ok=false;
                    }
                    if(($w['asystent_sedzia_2']==$id_usera&&$w['as2']==0)) {
                        $ok=false;
                    }
                    $w['data'] = substr($w['data'],0,4);
                    $i = "$i";
                    if($ok){
                    if($i==$w['data']) {
                        $dod = $dod.'<option value="'.$i.'">'.$i.'</option>';
                    }}
                }
            }
        }
    }
    echo '<form method="post"><select name="rok"><option value="all" selected>Wszystkie lata</option>'.$dod.'</select><input type="submit" value="WYBIERZ"></form>';
	echo '<table><tr><th>LP.</th><th>Klasa</th><th>Gospodarz</th><th>Gość</th><th>Data</th><th>Godzina</th><th>Sędzia</th><th>Asystent</th><th>Asystent 2</th><th>Twój ryczałt</th></tr>';
	if($result = $connect->query("SELECT * FROM mecze WHERE data<'".$currentdate."' AND glowny_sedzia=".$id_usera." OR asystent_sedzia=".$id_usera." OR asystent_sedzia_2=".$id_usera)) {
			if($result->num_rows>0)
			{
				for($k=1; $k<=$result->num_rows;$k++){
					$w= $result->fetch_assoc();
					$ok = true;
					if(($w['glowny_sedzia']==$id_usera&&$w['s']==0)){
                        $ok=false;
                    }
                    if(($w['asystent_sedzia']==$id_usera&&$w['as1']==0)) {
                        $ok=false;
                    }
                    if(($w['asystent_sedzia_2']==$id_usera&&$w['as2']==0)) {
                        $ok=false;
                    }
					if($rok!='all'){
						$data = substr($w['data'],0,4);
						$rok= "$rok";
						if($data!=$rok) {
							$ok=false;
						}
					}
					if($ok){
						$w['klasa'] = substr($w['klasa'], 0, 5);
						$k_result = $connect->query("SELECT * FROM klasy WHERE kod='".$w['klasa']."'");
						$k_result = $k_result->fetch_assoc();
						echo '<tr><td>'.$k.'</td><td>'.$k_result['skrot'].'</td><td>'.$w['gospodarz'].'</td><td>'.$w['gosc'].'</td><td>'.$w['data'].'</td><td>'.$w['godzina'].'</td>'.Bolder($w['glowny_sedzia'],$id_usera,$connect).Bolder($w['asystent_sedzia'],$id_usera,$connect).Bolder($w['asystent_sedzia_2'],$id_usera,$connect).'<td>0</td></tr>';
					}
				}
			}
		}
	echo '</table>';
	echo "<span><b>Ilość przesędziowanych spotkań:</b></span>";
	echo '<table><tr><th>Klasa</th><th>Jako Główny</th></tr>';
	if($result = $connect->query("SELECT * FROM mecze WHERE glowny_sedzia = ".$id_usera." AND s=1")){
		$klasy = $connect->query("SELECT * FROM klasy");
		$ogolnycounter_g = 0;
		$counter = 0;
		for($i=0; $i<$klasy->num_rows;$i++){
			$kw = $klasy->fetch_assoc();
			for($k=0;$k<$result->num_rows;$k++){
				$w = $result->fetch_assoc();
				$w['klasa'] = substr($w['klasa'],0,5);
				if($w['klasa']==$kw['kod']) {
					$counter++;
					$ogolnycounter_g++;
				}
			}
			if($counter>0){
				echo '<tr><td>'.$kw['skrot'].'</td><td>'.$counter.'</td></tr>';
			}
			
			$counter=0;
		}
		echo '<tr><td>Razem</td><td>'.$ogolnycounter_g.'</td></tr>';
		
	}
	echo '</table>';
	echo '<table><tr><th>Klasa</th><th>Jako Asystent</th></tr>';
	if($result = $connect->query("SELECT * FROM mecze WHERE ( asystent_sedzia = $id_usera AND as1 = 1 ) OR (asystent_sedzia_2= $id_usera AND as2 = 1	)")) {
		$klasy = $connect->query("SELECT * FROM klasy");
		$counter = 0;
		$ogolnycounter_a = 0;
		for($i=0; $i<$klasy->num_rows;$i++){
			$kw = $klasy->fetch_assoc();
			for($k=0;$k<$result->num_rows;$k++){
				$w = $result->fetch_assoc();
				$w['klasa'] = substr($w['klasa'],0,5);
				if($w['klasa']==$kw['kod']) {
					$counter++;
					$ogolnycounter_a++;
				}
			}
			if($counter>0){
				echo '<tr><td>'.$kw['skrot'].'</td><td>'.$counter.'</td></tr>';
			}
			$counter=0;
		}
		echo '<tr><td>Razem</td><td>'.$ogolnycounter_a.'</td></tr>';
	}
	echo '</table>';
	$ogolny = $ogolnycounter_a + $ogolnycounter_g;
	echo '<span>Ilość meczy, które przesędziowałeś wynosi: '.$ogolny;
}

?>