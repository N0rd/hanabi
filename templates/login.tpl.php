<form class="inputform" method="post">
  <legend class="inputlegend">Bejelentkezés</legend>
  <label class="inputlabel" for="inputusername">Felhasználónév</label>
    <input class="inputtext" type="text" id="inputusername" name="name" /><br />
  <label class="inputlabel" for="inputpassword">Jelszó</label>
    <input class="inputtext" type="password" id="inputpassword" name="password" /><br /> 
  <label class="inputlabel" for="inputbelep">&nbsp;</label>
    <button class="inputbutton" id="inputbelep" type="submit" >Belépés</button><br />
  <?php if($error): ?>
    <div class="inputerror">A felhasználóinév vagy a jelszó el lett gépelve!</div>
 	  <a class="inputerror" href="#" >Azt sem tudod ki vagy?</a>   
 	  <a class="inputerror" href="#" >Jelszó helyett, inkább emaileznél egyet, hogy bejuss?</a>   
 	  <a class="inputerror" href="?page=newuser" >Nem vagy még beregisztrálva?</a>   
  <?php else: ?>
	  <a class="inputlink" href="?page=newuser" >Regisztráció</a>    
  <?php endif; ?>
</form>
