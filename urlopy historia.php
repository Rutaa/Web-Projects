<?php
           if(is_user_logged_in()){
            $connect = new mysqli(); // You have to fullfil the informations for the database.
            $user_logged = wp_get_current_user();
			if(isset($_POST['usun'])){
				if($connect->query("DELETE FROM urlopy WHERE id_urlopu=".$_POST['usun'])){
					echo '<div style = "color:green">Pomyślnie anulowano urlop</div>';
				}
				else {
					echo '<div style= "color:red">Nie udało się anulować urlopu</div>';
				}
			}
            if($result = $connect->query("SELECT * FROM urlopy WHERE id_sedzi = '$user_logged->ID'"))
              {
               $ile = $result->num_rows;
               if ($ile == 0)
               {
               echo '<span style="font-weight: bold;">Brak aktywnych urlopów</span>';
               }
               else {
                for($i=0; $i<$ile; $i++)
                {
                    echo "<tr>";
                    $wiersz = $result->fetch_assoc();
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
                    if ($wiersz['potwierdzony']=='0')
                    {
                        echo "<td>Nie";
                    }
                    if ($wiersz['potwierdzony']=='-1')
                    {
                        echo "<td>W trakcie";
                    }
                    if ($wiersz['potwierdzony']=='1')
                    {
                        echo "<td>Tak";
                    }
					echo '<form method="post"><input type="text" class="hidden" name="usun" value="'.$wiersz['id_urlopu'].'"><input type="submit" value="ANULUJ"></form></td>';
                    echo "</tr>";
                }
               }
              }
               }
          ?>