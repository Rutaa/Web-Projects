<?php 
        session_start();
if(is_user_logged_in()){
    $user_logged =  wp_get_current_user();
       if(isset($_POST['powod']))
       {
		   if(current_user_can('administrator')){
			   $user_logged->ID = $_POST['wyb_sedzia'];
		   }
           $connect = new mysqli(); // You have to fullfil the informations for the database.
           $t_od = $_POST['t_od'];
           $t_do = $_POST['t_do'];
           $powod = $_POST['powod'];
		   $powod1 = $_POST['powod1'];
           $robocze = $_POST['robocze'];
           $r_od = $_POST['r_od'];
           $r_do = $_POST['r_do'];
           $weekendy = $_POST['weekendy'];
           $w_od = $_POST['w_od'];
           $w_do = $_POST['w_do'];
           $wyznaczone = $_POST['wyznaczone'];
           $wyzd_od = $_POST['wyzd_od'];
           $wyzd_do = $_POST['wyzd_do'];
           $wyzg_od = $_POST['wyzg_od'];
           $wyzg_do = $_POST['wyzg_do'];
           $ok = true;
		   if(strlen($powod)==0&&strlen($powod1)>0){
			   $powod = $powod1;
		   }
		   if(strlen($powod)==0&&strlen($powod1)==0){
			   $powod = "Brak powodu";
		   }
           if (strtotime($t_do)-strtotime($t_od)<0)
           {
               $ok = false;
               $_SESSION['error'] = '<span style="color:red">ERROR:Proszę wybrać daty od do. </span>';
           }
           if (strtotime($wyzd_do)-strtotime($wyzd_od)<0)
           {
               $ok = false;
               $_SESSION['error'] = '<span style="color:red">ERROR:Proszę wybrać daty od do. </span>';
           }
           if (strtotime($t_od)-strtotime(date('Y-m-d'))<1209600)
           {
               $ok = false;
               $_SESSION['error'] = '<span style="color:red">ERROR:Odstęp przed następnym wolnym musi wynosić co najmniej 2 tygodnie.</span>';
           }
           if ($ok == true)
           {
               if(isset($_POST['robocze']))
               {
                   $robocze=1;
               }
               else $robocze = 0;
               if(isset($_POST['weekendy']))
               {
                   $weekendy=1;
               }
               if(isset($_POST['wyznaczone']))
               {
                 $wyznaczone = 1;
               }
               else $wyznaczone = 0;
               //Variable for taking logged user id
               
               if($result = $connect->query("INSERT INTO urlopy (robocze, us_odr, us_dor, weekendy, us_odw, us_dow, wyznaczone, dniod, dnido, us_odp, us_dop, od, do, powod, id_sedzi, potwierdzony) VALUES ('$robocze', '$r_od', '$r_do', '$weekendy', '$w_od', '$w_do', '$wyznaczone', '$wyzd_od', '$wyzd_do', '$wyzg_od', '$wyzg_do', '$t_od', '$t_do', '$powod', '$user_logged->ID', '-1') "))
               {
                   echo '<span style="color:green;">Pomyślnie dodano prośbę o urlop. Proszę poczekać na decyzję admina.</span>';
               }
               else echo '<span style="color:red;">Niestety nie udało się dodać prośby o urlop. Spróbuj ponownie.</span>';
           }
           else echo '<span style="color:red;">Niestety nie udało się dodać prośby o urlop. Spróbuj ponownie.</span><br>';
        }
}
else {
    echo '<span style="color:red">Nie jesteś zalogowany.</span>';
}
   ?>