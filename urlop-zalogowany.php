<?php if(is_user_logged_in()){
echo '<div>Dodawanie urlopu:</div><form class="urlop-box" method="post">';
if(current_user_can('administrator')){
	$connect = new mysqli(); // You have to fullfil the informations for the database.
	echo '<div><b>Wybierz sędzie, któremu chcesz zapisać urlop:</b>';
	if($result = $connect->query("SELECT * FROM wp_users")){
		echo '<select name="wyb_sedzia">';
		for($i=0;$i<$result->num_rows;$i++){
			$w = $result->fetch_assoc();
			if(get_current_user_id() == $w['ID']){
				echo '<option selected value="'.$w['ID'].'">Dla siebie</option>';
			}
			else {
				echo '<option value="'.$w['ID'].'">'.$w['user_imie'].' '.$w['user_nazwisko'].'</option>';
			}
		}
		echo '</select>';
	}
	echo '</div>';
}
echo<<<END
<label><input class="l-tymczasowy" type="checkbox" />Urlop tymczasowy</label>
<div class="urlop-tymczasowy hidden"><label>Od: <input class="t-od" name="t_od" type="date" /></label>
<label>Do: <input class="t-do" name="t_do" type="date" /></label>
<input class="t-powod" name="powod" type="text" placeholder="Wpisz tutaj powód" />
<input class="t-submit" type="submit" value="Zatwierdź" /></div>
<label><input class="l-staly" type="checkbox" />Urlop stały</label>
<div class="urlop-staly hidden"><label class="label-r">
<input class="robocze-c" name="robocze" type="checkbox" />
Wszystkie dni robocze
</label>
<div class="robocze hidden"><label>Od: <input class="od" name="r_od" type="time" value="00:00" /></label>
<label>Do: <input class="do" name="r_do" type="time" value="23:59" /></label></div>
<label class="label-w">
<input class="weekendy-c" name="weekendy" type="checkbox" />
Wszystkie weekendy
</label>
<div class="weekendy hidden"><label>Od: <input class="od" name="w_od" type="time" value="00:00" /></label>
<label>Do: <input class="do" name="w_do" type="time" value="23:59" /></label></div>
<label>
<input class="wyznaczone-c" name="wyznaczone" type="checkbox" />
Poszczególne dni
</label>
<div class="wyznaczone hidden"><label>Od:<select name="wyzd_od">
<option value="1">Poniedziałek</option>
<option value="2">Wtorek</option>
<option value="3">Środa</option>
<option value="4">Czwartek</option>
<option value="5">Piątek</option>
<option value="6">Sobota</option>
<option value="7">Niedziela</option>
</select></label>
<label>Do:<select name="wyzd_do">
<option value="1">Poniedziałek</option>
<option value="2">Wtorek</option>
<option value="3">Środa</option>
<option value="4">Czwartek</option>
<option value="5">Piątek</option>
<option value="6">Sobota</option>
<option value="7">Niedziela</option>
</select></label>
<div></div>
<label>Od: <input class="od" name="wyzg_od" type="time" value="00:00" /></label>
<label>Do: <input class="do" name="wyzg_do" type="time" value="23:59" /></label>

</div>
<input class="t-powod" name="powod1" type="text" placeholder="Wpisz tutaj powód" />
<input class="s_submit" type="submit" value="Zatwierdź" />

</div>
</form>
<table>
<tr>
<th>Typ urlopu</th>
<th>Od:</th>
<th>Do:</th>
<th>Powód:</th>
<th>Czy zatwierdzony:</th>
</tr>
END;
}

?>