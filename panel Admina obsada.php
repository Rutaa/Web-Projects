<?php 
session_start();
    $connect = new mysqli(); // You have to fullfil the informations for the database.
        if(is_user_logged_in())
        {
            if(current_user_can('administrator'))
            {
				
                if($result = $connect->query("SELECT * FROM klasy"))
                {
                    $count = $result->num_rows;
                    $wstaw = '<select name="klasa-dm">';
                    for($i=0; $i<$count;$i++)
                    {
                        $wiersz = $result->fetch_assoc();
                        $wstaw .= '<option value="'.$wiersz['id_klasy'].'" title="'.$wiersz['pelna_nazwa'].'">'.$wiersz['skrot'].' '.$wiersz['kod'].'</option>';
                    }
                    $wstaw .='</select>';
                }
				if($result = $connect->query("SELECT * FROM druzyny")) {
					$druzyny = '<select name="gospodarze-dm-s"><option value="0">-Druzyna Wpisana-</option>';
					for($i=0;$i<$result->num_rows;$i++){
						$w = $result->fetch_assoc();
						$druzyny .= '<option value="'.$w['nazwa_druzyny'].'">'.$w['nazwa_druzyny'].'</option>';
					}
					$druzyny .='</select>';
				}
				if($result = $connect->query("SELECT * FROM druzyny")) {
					$druzyny1 = '<select name="gosc-dm-s"><option value="0">-Druzyna Wpisana-</option>';
					for($i=0;$i<$result->num_rows;$i++){
						$w = $result->fetch_assoc();
						$druzyny1 .= '<option value="'.$w['nazwa_druzyny'].'">'.$w['nazwa_druzyny'].'</option>';
					}
					$druzyny1 .='</select>';
				}
				if($result = $connect->query("SELECT * FROM druzyny")){
					$miejscowa = '<select name="miejsce-s"><option value="0">U gospodarza</option>';
					for($i=0;$i<$result->num_rows;$i++){
						$w = $result->fetch_assoc();
						$miejscowa .= '<option value="'.$w['adres_boiska'].'">'.$w['adres_boiska'].'</option>';
					}
					$miejscowa .='</select>';
				}
                if (isset($_SESSION['error']))
                {
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                }
                echo "<div>Jesteś administratorem. Przysługują Ci dodatkowe prawa.</div>";
                echo '<div><form method="post" enctype="multipart/form-data"><b>Dodaj obsadę.</b><br><label>Dodaj do kolejki: <input type="text" style="width:50px;" name="nr_kolejki_plik"></label><input type="file" name="plik"><input type="submit" value="Prześlij" name="submit"></form></div>';
                echo '<div><form method="post"><button type="button" class="button-dm">Dodaj osobny mecz</button><div class="dm hidden"><label>Dodaj do kolejki: <input type="text" name="nr_kolejka" style="width: 50px;"></label><label>Klasa: '.$wstaw.'</label><input type="text" placeholder="Gospodarze" name="gospodarze-dm">'.$druzyny.'<input type="text" placeholder="Goście" name="goscie-dm">'.$druzyny1.'<label>Data meczu: <input type="date" name="data-dm" value="'.date('Y-m-d').'"></label><label>Godzina: <input type="time" name="godzina-dm"></label><input type="text" placeholder="Miejsce" name="miejsce-dm">'.$miejscowa.'<input type="text" placeholder="Uwagi"  name="uwagi-dm"><input type="submit" value="Dodaj"></div></form></div>';
                if(isset($_POST['submit']))
                {
                    require_once('excel_reader2.php');
                    move_uploaded_file($_FILES["plik"]["tmp_name"], 'Downloads/obsada.xls');
                    $file = 'Downloads/obsada.xls';
                    $plik = new Spreadsheet_Excel_Reader($file, false);
                    for($i=5;$i<$plik->rowcount($sheet_index=0); $i++) {
                        $klasa = $plik->val($i,2);
                        $gospodarze = $plik->val($i,3);
                        $goscie = $plik->val($i,4);
                        $data = $plik->val($i,5);
                        $godzina = $plik->val($i,6);
                        $miejsce = $plik->val($i,7);
						$kolejka = $_POST['nr_kolejki_plik'];
                        if($result = $connect->query("INSERT INTO mecze (klasa, gospodarz, gosc, data, godzina, miejsce, potwierdzony, kolejka) VALUES ('$klasa', '$gospodarze', '$goscie', '$data', '$godzina', '$miejsce', 1, '$kolejka')"))
                        {
							echo "Pomyslnie";
                        }

                    }
                }
                if(isset($_POST['gospodarze-dm']))
                {
                    $klasa_dm = $_POST['klasa-dm'];
                    if($result = $connect->query("SELECT * FROM klasy WHERE id_klasy = $klasa_dm"))
                    {
                        $wiersz = $result->fetch_assoc();
                        $klasa_dm = $wiersz['kod']." ".$wiersz['pelna_nazwa'];
                    }
                    $gospodarze_dm = $_POST['gospodarze-dm'];
					$gospodarze_dm_s = $_POST['gospodarze-dm-s'];
					$goscie_dm_s = $_POST['gosc-dm-s'];
					$goscie_dm = $_POST['goscie-dm'];
					if($gospodarze_dm_s){
						$gospodarze_dm = $gospodarze_dm_s;
					}
					if($goscie_dm_s) {
						$goscie_dm = $goscie_dm_s;
					}
					echo $goscie_dm;
					$miejsce_dm_s = $_POST['miejsce-s'];
                   
                    $data_dm = $_POST['data-dm'];
                    $godzina_dm = $_POST['godzina-dm'];
                    $miejsce_dm = $_POST['miejsce-dm'];
					$kolejka = $_POST['nr_kolejka'];
					if($miejsce_dm_s!=0)
					{
						$miejsce_dm = $miejsce_dm_s;
					}
                    $uwagi_dm = $_POST['uwagi-dm'];
                    $ok = true;
                    if($ok==true)
                    {
                        if($result = $connect->query("INSERT INTO mecze (klasa, gospodarz, gosc, data, godzina, miejsce, uwagi, potwierdzony, kolejka) VALUES ('$klasa_dm', '$gospodarze_dm', '$goscie_dm', '$data_dm', '$godzina_dm', '$miejsce_dm', '$uwagi_dm', '1', '$kolejka')")) {
                            echo "Sukces !";
                        }
                        else echo "Błąd, Spróbuj ponownie !";
                    }
                    else echo "Error";
                }
            }
        }
    ?>