<?php foreach($player->hand as $place => $card) { ?>
  <div class="owncard">
  <?php
  foreach($keys as $k) { 
    if(isset($player->info[$place][$k])) {
      $info = $player->info[$place][$k];
    } else {
      $info = 'unk';
    }
  ?>
    <input class="memo" type="image" src="images/buttons/memo<?php echo $k.$info; ?>.gif" name="<?php echo $k; ?>" value="<?php echo $info; ?>" />
  <?php } ?>
  </div>
<?php } ?>
