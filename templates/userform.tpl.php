<form class="inputform" method="post">
  <legend class="inputlegend">Regisztráció</legend>
  <?php if($errors){ ?>
    <div class="inputerror">
      <?php foreach($errors as $error) { ?>
        <?php echo $error; ?><br />
      <?php } ?>
    </div>
  <?php } ?>
  <label class="inputlabel" for="inputusername">Név:</label>
    <input class="inputtext" id="inputusername" name="name" value="<?php echo $user->name; ?>" />
    <span class="inputjs beforjs" id="jsname">Így nem nevezhetlek!</span><br />
  <label class="inputlabel" for="inputemail">E-mail:</label>
    <input class="inputtext" id="inputemail" name="email" value="<?php echo $user->email; ?>" disabled="disabled" />
    <span class="inputjs beforjs" id="jsemail">Ez nem e-mail cím!</span><br />
  <label class="inputlabel" for="inputpassword1">Jelszó:</label>
    <input class="inputtext" id="inputpassword1" type="password" name="password1" value="" disabled="disabled" />
    <span class="inputjs beforjs" id="jspass1">gyenge</span><br />
  <label class="inputlabel" for="inputpassword2">Jelszó újra:</label>
    <input class="inputtext" id="inputpassword2" type="password" name="password2" value="" disabled="disabled" />
    <span class="inputjs beforjs" id="jspass2">nem ugyanaz</span><br />
  <label class="inputlabel" for="inputreg">&nbsp;</label>    
    <input class="inputbutton"  id="inputreg" type="submit" value="Regisztráció" />
</form>