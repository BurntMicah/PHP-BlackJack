<?php
 session_start();
 if($_SESSION['gameover']==1){
  session_unset();
  session_destroy();
  header('Location: .');
 }
 echo "<html>\n<body bgcolor='006600'>
Game doesn't end until you hit 'Draw'. Click on aces to change value<br>";
 function getval($z){
  for($i=5;$i<5+10;$i++){
   if($z<4*$i+1){$y = intval(-($z-17)/4)+10;}
  } 
  if($z < 5){ return 11;}//Ace
  if($y > 10){return 10;}//Face Card
  return $y;
 }
if(!$_SESSION){
 //A is House //// B is Player
 $a[0]=0;
 $b[0]=0;
 $aval[0]=0;
 $bval[0]=0;

 $a[1] = rand(1,52);
 $temp = rand(1,52);
 while(in_array($temp, $a)){$temp = rand(1,52);}//makes sure temp isnt in deck
 $a[2] = $temp;
 $aval[1] = getval($a[1]);
 $aval[2] = getval($a[2]);

 $b[1] = rand(1,52);
 $b[2] = rand(1,52); 
 while(in_array($temp, $b)||in_array($temp, $a)){$temp = rand(1,52);}
 $b[1] = $temp;
 while(in_array($temp, $b)||in_array($temp, $a)){$temp = rand(1,52);}
 $b[2] = $temp;
 $bval[1] = getval($b[1]);
 $bval[2] = getval($b[2]);

 echo "<img src='cards/b1pl.png'><img src='cards/".$a[2].".png'><br><br><br>";

 $_SESSION['a']=$a;
 $_SESSION['b']=$b;
 $_SESSION['aval']=$aval;
 $_SESSION['bval']=$bval;
}
if($_SESSION){
 $a = $_SESSION['a'];
 $b = $_SESSION['b'];
 $aval = $_SESSION['aval'];
 $bval = $_SESSION['bval'];
}

if($_GET['a']=="Reset"){
 session_unset();
 session_destroy();
 header('Location: .');
}
/*
if($_GET['a']=="Fold"){
 if(fold($a,$b,$aval,$bval)){
  echo "You Win!!!<br>";
 }else{echo "You lose<br>";}
}
*/
if($_GET['a']=="Hit"){
 $j = count($b);
 $temp = rand(1,52);
 while(in_array($temp, $a)||in_array($temp, $b)){$temp = rand(1,52);}
 $b[$j] = $temp;
 $bval[$j] = getval($b[$j]);
 echo "<img src='cards/b1pl.png'><img src='cards/".$a[2].".png'><br><br><br>";
 echo draw($b,$bval);
 $_SESSION['a']=$a;
 $_SESSION['b']=$b;
 $_SESSION['aval']=$aval;
 $_SESSION['bval']=$bval;
}//NOTE: MUST CHECK AFTER TO SEE IF BUST
//A is House //// B is Player

if($_GET['a']=="Fold"){
 $_SESSION['gameover'] = 1;
 if(array_sum($bval)<22){
  $j = count($a);
  while(array_sum($aval)<17){
   $j = count($a);
   $temp = rand(1,52);
   while(in_array($temp, $a)||in_array($temp, $b)){$temp = rand(1,52);}
   $a[$j] = $temp;
   $aval[$j] = getval($a[$j]);
   for($i=1;$i<count($a);$i++){
    if($aval[$i]==11&&array_sum($aval)>21){$aval[$i]==1;}
   }
  }
 }
 echo draw($a,$aval);
 echo draw($b,$bval);
 //Check for Winner and Return it// False=House//True=Player
 if(array_sum($bval)>21){
  echo "You lose!";
 }else if(array_sum($aval)>21){
 echo "You win!";
 } else if(array_sum($bval)>array_sum($aval)){echo "You win!";}
  else{echo "You lose!";}
}
function draw($b,$bval){
 //Draw Player Hand
 $output = "";
 for($i=1;$i<count($b);$i++){
  if($b[$i]<5){$output .= "<a href='./?b=".$i."'>";}
  $output .= "<img src='cards/".$b[$i].".png'>";
  if($b[$i]<5){$output .= "</a>";}
 }
 return $output.array_sum($bval)."<br><br><br>";
}
if($_GET['b']){
 if($b[$_GET['b']]<5){
  if($bval[$_GET['b']]==11){$bval[$_GET['b']]=1;}
  else{$bval[$_GET['b']]=11;}
  $_SESSION['a']=$a;
  $_SESSION['b']=$b;
  $_SESSION['aval']=$aval;
  $_SESSION['bval']=$bval;
 }
}
if($_GET['b']){echo "<img src='cards/b1pl.png'><img src='cards/".$a[2].".png'><br><br><br>";}
if(!$_GET['a']){echo draw($b,$bval);}
echo"
<form method='GET'>
<input type='submit' name='a' value='Fold'>
<input type='submit' name='a' value='Hit'><br>
<input type='submit' name='a'' value='Reset'>
</form>
</body>
</html>";
?>
