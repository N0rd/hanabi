<h2 class="element">Tüzijáték</h2>
<?php foreach($fireworks as $color => $number) { ?>
  <img class="cardplace <?php echo $color; ?>" src="images/cards/<?php echo $color.$number; ?>.jpg" alt="<?php echo $number; ?>" />
<?php } ?>