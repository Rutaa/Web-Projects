<?php 
session_start();
 	if(is_user_logged_in())
 	{
 		$user_logged =  wp_get_current_user();
 		//->login // kto jest zalogowany
 		$connect = new mysqli(); // You have to fullfil the informations for the database.
		$formularz = '<div>Wybierz 3 najbliżej odpowiadające Ci boiska:';
		if($connect->query("SELECT * FROM druzyny")){
			for ($k=1; $k<=3; $k++){
				$sel = '<select name="'.$k.'">';
				for($i=0;$i<$connect->query("SELECT * FROM druzyny")->num_rows;$i++){
					$r = $connect->query("SELECT * FROM druzyny");
					$w = $r->fetch_assoc();
					$xd = $connect->query("SELECT * FROM wp_users WHERE ID=$user_logged->ID");
					$xd = $xd->fetch_assoc();
					if($k==1){
						if($xd['prop_boisko']==$w['id_druzyny']){
							$sel .= '<option value='.$xd['prop_boisko'].'selected>'.$w['adres_boiska'].'</option>';
						}
						else{
							$sel .= '<option value='.$w['id_druzyny'].'>'.$w['adres_boiska'].'</option>';
						}
					}
					elseif($k==2) {
						if($xd['prop_boisko1']==$w['id_druzyny']){
							$sel .= '<option value='.$xd['prop_boisko1'].'selected>'.$w['adres_boiska'].'</option>';
						}
						else{
							$sel .= '<option value='.$w['id_druzyny'].'>'.$w['adres_boiska'].'</option>';
						}
					}
					else {
						if($xd['prop_boisko2']==$w['id_druzyny']){
							$sel .= '<option value='.$xd['prop_boisko2'].'selected>'.$w['adres_boiska'].'</option>';
						}
						else{
							$sel .= '<option value='.$w['id_druzyny'].'>'.$w['adres_boiska'].'</option>';
						}
					}
					$sel .='</select>';
				}
				$formularz .= $sel;
				
			}
			$formularz .= '</div>';
		}
		
		if(isset($_POST['adres'])){
			$tel = $_POST['numertel'];
			$email = $_POST['email'];
			$adres = $_POST['adres'];
			$passed = true;
			
			$p1 = $_POST['1'];
			echo $p1;
			$p2 = $_POST['2'];
			$p3 = $_POST['3'];
			if($connect->query("UPDATE wp_users SET prop_boisko = $p1, prop_boisko1 = $p2, prop_boisko2 = $p3 WHERE ID = $user_logged->ID")){}
			else {
				$passed = false;
			}	
			if(strlen($tel)!=9){
				$passed=false;
				$_SESSION['e_tel'] = '<span style="color:red;">Wprowadzony numer telefonu jest nieprawidłowy !</span>';
			}
			if($passed == true) {
				if($update = $connect->query("UPDATE wp_users SET user_telefon = ".$tel." , user_email = '".$email."' , user_adres = '".$adres."' WHERE user_login = '".$user_logged->user_login."'"))
				{
					$allok = '<span style="color:green;">Wszystko zaaktualizowane</span>';
			}}
				else $allok = '<span style="color:red;">Błąd, proszę spróbować ponownie!</span>';
		}
 		if($result = $connect->query("SELECT * FROM wp_users WHERE user_login = '".$user_logged->user_login."'"))
        {
            $wiersz = $result->fetch_assoc();
			if($wiersz['user_biegi'] == 1) {
				$zal = '<span style="color:green;">Zaliczone</span>';
			}
			else $zal = '<span style="color:red;">Nie zaliczone</span>';
			if($wiersz['user_platnosci'] == 1) {
				$urg = '<span style="color:green;">Uregulowane</span>';
			}
			else $urg = '<span style="color:red;">Nieuregulowane</span>';
		if((date('Y-m-d')<$wiersz['user_badania'])&&($wiersz['user_platnosci']==1)&&$wiersz['user_biegi']==1){
				$up = '<span style="color:green;"><b><u>Zdolny</u></b></span>';
			}
			else $up = '<span style="color:red;"><b><u>Nie zdolny</u></b></span>';
            echo '<div class="content-box"><form method="post"><p><span style="color:black;"><b>Imię: </b></span>'.$wiersz["user_imie"].'</p><p><span style="color:black;"><b>Nazwisko: </b></span>'.$wiersz["user_nazwisko"].'</p><label><span style="color:black;"><b>Adres: </b></span><input type="text" value = "'.$wiersz["user_adres"].'" name="adres"></label><label><span style="color:black;"><b>Nr tel: </b></span><input type="text" value = "'.$wiersz["user_telefon"].'" name="numertel"></label>'.$formularz.'<p><span style="color:black;"><b>Uprawnienie: </b></span><span style="color:blue;">'.$wiersz["user_uprawnienie"].'</span></p><label><span style="color:black;"><b>E-mail: </b></span><input type="text" value = "'.$wiersz["user_email"].'" name="email"></label><p><span style="color:black;"><b>Data ważności badań: </b></span>'.$wiersz["user_badania"].'</p><p><span style="color:black;"><b>Biegi: </b></span>'.$zal.'</p><p><span style="color:black;"><b>Płatności: </b></span>'.$urg.'</p><input type="submit" value="Aktualizuj"><p>'.$up.'</p><p>'.$allok.'</p></form></div>';
        }
        else echo $connect->error;
 	}
 	else	echo '<span style="color:red">Nie jesteś zalogowany! <a href="http://obsadakatowice.pl/wp-login.php">Zaloguj się!</a></span>';