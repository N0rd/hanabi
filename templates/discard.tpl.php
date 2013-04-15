<h2 class="element">Kuka</h2>
<div id="discardrows">
<?php foreach($discard as $color => $row) { ?>
<div class="discardrow">
  <?php foreach($row as $card) { ?>
  <img class="cardtrash <?php echo $color; ?>" src="images/cards/<?php echo $card; ?>.jpg" />
  <?php } ?>
</div>
<?php } ?>
</div>