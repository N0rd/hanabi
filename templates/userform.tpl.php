<form method="post">
  <legend>Regisztráció</legend>
  <?php if($errors){ ?>
    <div class="errors">
      <?php foreach($errors as $e) { ?>
        <?php echo $e; ?><br />
      <?php } ?>
    </div>
  <?php } ?>
  Név: <input name="name" value="<?php echo $user->name; ?>" /><br />
  E-mail: <input name="email" value="<?php echo $user->email; ?>" /><br />
  Jelszó: <input type="password" name="password1" value="" /><br />
  Jelszó újra: <input type="password" name="password2" value="" /><br />
  <input type="submit" value="Regisztráció" />
</form>