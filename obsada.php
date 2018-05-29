<?php
$connect = new mysqli("188.128.232.198","27171648_0000003","o3fKTCyYEdCTvA4iz6JFjVxz3Y3KlyH7","27171648_0000003");
if(current_user_can('administrator')){
	echo '<form method="post"><input type="submit" name="dodaj" value="Dodaj drużynę"></form>';
	if(isset($_POST['dodaj'])){
		echo '<form method="post"><input type="text" name="nazwad" placeholder="Nazwa drużyny"><input type="text" name="adresb" placeholder="Adres boiska"><input type="submit" value="Zatwierdź"></form>';
	}
	if(isset($_POST['adresb'])){
		
		$adres = $_POST['adresb'];
		$nazwa = $_POST['nazwad'];
		if($connect->query("INSERT INTO druzyny (nazwa_druzyny, adres_boiska) VALUES ('$nazwa', '$adres');")){
			echo '<div style="color:green">Pomyślnie dodano drużynę</div>';
		}
		else {
			echo '<div style="color:red">Nie udało się dodać drużyny</div>';
		}
	}
}
echo 'Drużyny dostępne w bazie:';
if($r = $connect->query("SELECT * FROM druzyny")){
	if($r->num_rows==0){
		echo '<b>Brak drużyn w bazie</b>';
	}
	else {
		echo '<table><tr><th>Lp</th><th>Nazwa Drużyny</th><th>Adres Boiska</th></tr>';
		for($i=1;$i<=$r->num_rows;$i++){
			$w = $r->fetch_assoc();
			echo '<tr><td>'.$i.'</td><td>'.$w['nazwa_druzyny'].'</td><td>'.$w['adres_boiska'].'</td></tr>';
			
		}
		echo '</table>';
	}
}
 
?>