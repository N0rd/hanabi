<form method="post">
    <label for="inputUsername">Felhasználónév</label>
    <input type="text" id="inputUsername" name="name" /><br />
    <label for="inputPassword">Jelszó</label>
    <input type="password" id="inputPassword" name="password" /><br /> 
    <button type="submit" >Belépés</button><br />
    <a href="?page=newuser" >Regisztráció</a>
  <?php if($error): ?>
  <div class="error">Nem megfelelő felhasználónév vagy jelszó</div>
  <?php endif; ?>
</form>
