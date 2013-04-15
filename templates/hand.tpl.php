<div class="playerhand" id="player<?php echo $player->id; ?>hand">
  <h2 class="element"><?php echo $player->name; ?></h2>
  <?php foreach($player->hand as $card) { 
    //TMP hack:
    $color = substr($card, 0, 1);
  ?>
  <img class="cardhand <?php echo $color; ?>" alt="1" src="images/cards/<?php echo $card; ?>.jpg">
  <?php } ?>
</div>