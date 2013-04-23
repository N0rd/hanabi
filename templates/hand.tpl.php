<div class="playerhand" id="player<?php echo $player->playerplace; ?>hand">
  <h2 class="element"><?php echo $player->name; ?></h2>
  <?php foreach($player->hand as $card) { 
    //Imageless help hack:
    $color = substr($card, 0, 1);
    $number = substr($card, 1, 1);
  ?>
  <img class="cardhand <?php echo $color; ?>" alt="<?php echo $number; ?>" src="images/cards/<?php echo $card; ?>.jpg">
  <?php } ?>
</div>