<?php 

if(is_user_logged_in()&&current_user_can('administrator')) {

    $connect = new mysqli(); // You have to fullfil the informations for the database.
    echo "<span>Administrator posiada prawo do odwołania lub zatwierdzenia urlopu.</span>";
    if(isset($_POST['urlop_p'])) {
    if($_POST['urlop_p']=="ZATWIERDZ"){
        $id_urlopu = $_POST['id_urlopu'];
        $connect->query("UPDATE urlopy SET potwierdzony = 1 WHERE id_urlopu=".$id_urlopu);
    }
        else {
        $id_urlopu = $_POST['id_urlopu'];
        $connect->query("UPDATE urlopy SET potwierdzony = 0 WHERE id_urlopu=".$id_urlopu);
        }
    }
	
    if($result = $connect->query("SELECT * FROM urlopy WHERE potwierdzony=-1")) {
        $ile_urlopow = $result->num_rows;
        if($ile_urlopow==0) {
            echo "<div>Nie ma urlopów do sprawdzania</div>";
        }
        else {
            echo "<table><tr><th>Imię i nazwisko</th><th>Typ urlopu</th><th>od</th><th>do</th><th>Powod</th><th>Decyzja</th>";
             for($i=0; $i<$ile_urlopow; $i++)
                {
                    echo "<tr>";
                    $wiersz = $result->fetch_assoc();
                    $sedzia = $connect->query("SELECT * FROM wp_users WHERE ID='".$wiersz['id_sedzi']."'")->fetch_assoc();
                    $sedzia = $sedzia['user_imie'].' '.$sedzia['user_nazwisko'];
                    echo "<td>".$sedzia."</td>";
                    if($wiersz['robocze']==1||$wiersz['weekendy']==1||$wiersz['wyznaczone']==1)
                    {
                        echo "<td>Stały";
                        if($wiersz['robocze']==1)
                    {
                        echo ", dni robocze</td>";
                    }
                    if($wiersz['weekendy']==1)
                    {
                        echo ", weekendy</td>";
                    }
                    if($wiersz['wyznaczone']==1)
                    {
                        echo ", dni wyznaczone</td>";
                    }
                    }
                    else 
                    {
                        echo "<td>Tymczasowy</td>";
                        echo "<td>".$wiersz['od']."</td>";
                        echo "<td>".$wiersz['do']."</td>";
                        echo "<td>".$wiersz['powod']."</td>";
                    }
                    if($wiersz['robocze']==1)
                    {
                        echo "<td>".$wiersz['us_odr']."</td>";
                        echo "<td>".$wiersz['us_dor']."</td>";
                        echo "<td>".$wiersz['powod']."</td>";
                    }
                    if($wiersz['weekendy']==1)
                    {
                        echo "<td>".$wiersz['us_odw']."</td>";
                        echo "<td>".$wiersz['us_dow']."</td>";
                        echo "<td>".$wiersz['powod']."</td>";
                    }
                    if($wiersz['wyznaczone']==1)
                    {
                        if($wiersz['dniod']==1)
                        {
                         $dzien = "poniedziałek";
                        }
                        if($wiersz['dniod']==2)
                        {
                         $dzien = "wtorek";
                        }
                        if($wiersz['dniod']==3)
                        {
                         $dzien = "środa";
                        }
                        if($wiersz['dniod']==4)
                        {
                         $dzien = "czwartek";
                        }
                        if($wiersz['dniod']==5)
                        {
                         $dzien = "piątek";
                        }
                        if($wiersz['dniod']==6)
                        {
                         $dzien = "sobota";
                        }
                        if($wiersz['dniod']==7)
                        {
                         $dzien = "niedziela";
                        }
                        echo "<td>".$dzien." ".$wiersz['us_odp']."</td>";
                        
                        if($wiersz['dnido']==1)
                        {
                         $dzien = "poniedziałek";
                        }
                        if($wiersz['dnido']==2)
                        {
                         $dzien = "wtorek";
                        }
                        if($wiersz['dnido']==3)
                        {
                         $dzien = "środa";
                        }
                        if($wiersz['dnido']==4)
                        {
                         $dzien = "czwartek";
                        }
                        if($wiersz['dnido']==5)
                        {
                         $dzien = "piątek";
                        }
                        if($wiersz['dnido']==6)
                        {
                         $dzien = "sobota";
                        }
                        if($wiersz['dnido']==7)
                        {
                         $dzien = "niedziela";
                        }
                        echo "<td>".$dzien." ".$wiersz['us_dop']."</td>";
                        echo "<td>".$wiersz['powod']."</td>";
                }
                 echo '<td><form method="post"><input type="submit" value="ZATWIERDZ" name="urlop_p"><input type="text" class="hidden" name="id_urlopu" value="'.$wiersz['id_urlopu'].'"><input type="submit" value="ODRZUC" name="urlop_p"></form></td>';
                 echo "</tr>";
        }
            echo "</table>";
			
			
			
    }
	
	
}
if(isset($_POST['urlop_d'])){
		$id_urlopu = $_POST['id_urlopu'];
		if($connect->query("DELETE FROM urlopy WHERE id_urlopu=".$id_urlopu)){
			echo '<div style="color: green">Pomyślnie anulowano urlop.</div>';
		}
		else {
			echo '<div style="color: red">Wystąpił błąd przy usuwaniu. Proszę spróbować ponownie!</div>';
		}
		
	}

if($result = $connect->query("SELECT * FROM urlopy")) {
        $ile_urlopow = $result->num_rows;
        if($ile_urlopow==0) {
            echo "<div>Żaden zawodnik nie ma urlopu aktualnie.</div>";
        }
        else {
			echo '<div>Wszystkie urlopy:</div>';
            echo "<table><tr><th>Imię i nazwisko</th><th>Typ urlopu</th><th>od</th><th>do</th><th>Powod</th><th>Akcja</th>";
             for($i=0; $i<$ile_urlopow; $i++)
                {
                    echo "<tr>";
                    $wiersz = $result->fetch_assoc();
                    $sedzia = $connect->query("SELECT * FROM wp_users WHERE ID='".$wiersz['id_sedzi']."'")->fetch_assoc();
                    $sedzia = $sedzia['user_imie'].' '.$sedzia['user_nazwisko'];
                    echo "<td>".$sedzia."</td>";
                    if($wiersz['robocze']==1||$wiersz['weekendy']==1||$wiersz['wyznaczone']==1)
                    {
                        echo "<td>Stały";
                        if($wiersz['robocze']==1)
                    {
                        echo ", dni robocze</td>";
                    }
                    if($wiersz['weekendy']==1)
                    {
                        echo ", weekendy</td>";
                    }
                    if($wiersz['wyznaczone']==1)
                    {
                        echo ", dni wyznaczone</td>";
                    }
                    }
                    else 
                    {
                        echo "<td>Tymczasowy</td>";
                        echo "<td>".$wiersz['od']."</td>";
                        echo "<td>".$wiersz['do']."</td>";
                        echo "<td>".$wiersz['powod']."</td>";
                    }
                    if($wiersz['robocze']==1)
                    {
                        echo "<td>".$wiersz['us_odr']."</td>";
                        echo "<td>".$wiersz['us_dor']."</td>";
                        echo "<td>".$wiersz['powod']."</td>";
                    }
                    if($wiersz['weekendy']==1)
                    {
                        echo "<td>".$wiersz['us_odw']."</td>";
                        echo "<td>".$wiersz['us_dow']."</td>";
                        echo "<td>".$wiersz['powod']."</td>";
                    }
                    if($wiersz['wyznaczone']==1)
                    {
                        if($wiersz['dniod']==1)
                        {
                         $dzien = "poniedziałek";
                        }
                        if($wiersz['dniod']==2)
                        {
                         $dzien = "wtorek";
                        }
                        if($wiersz['dniod']==3)
                        {
                         $dzien = "środa";
                        }
                        if($wiersz['dniod']==4)
                        {
                         $dzien = "czwartek";
                        }
                        if($wiersz['dniod']==5)
                        {
                         $dzien = "piątek";
                        }
                        if($wiersz['dniod']==6)
                        {
                         $dzien = "sobota";
                        }
                        if($wiersz['dniod']==7)
                        {
                         $dzien = "niedziela";
                        }
                        echo "<td>".$dzien." ".$wiersz['us_odp']."</td>";
                        
                        if($wiersz['dnido']==1)
                        {
                         $dzien = "poniedziałek";
                        }
                        if($wiersz['dnido']==2)
                        {
                         $dzien = "wtorek";
                        }
                        if($wiersz['dnido']==3)
                        {
                         $dzien = "środa";
                        }
                        if($wiersz['dnido']==4)
                        {
                         $dzien = "czwartek";
                        }
                        if($wiersz['dnido']==5)
                        {
                         $dzien = "piątek";
                        }
                        if($wiersz['dnido']==6)
                        {
                         $dzien = "sobota";
                        }
                        if($wiersz['dnido']==7)
                        {
                         $dzien = "niedziela";
                        }
                        echo "<td>".$dzien." ".$wiersz['us_dop']."</td>";
                        echo "<td>".$wiersz['powod']."</td>";
                }
                 echo '<td><form method="post"><input type="submit" value="USUŃ" name="urlop_d"><input type="text" class="hidden" name="id_urlopu" value="'.$wiersz['id_urlopu'].'"></form></td>';
                 echo "</tr>";
        }
            echo "</table>";
			
			
			
    }
	
}
}
?>