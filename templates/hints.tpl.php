<h2 class="element">Utalások</h2>
<?php for($i = 0; $i < $available; $i++) { ?>
<img class="marker" src="images/hint.gif" alt="+" />
<?php } ?>
<?php for($i = 0; $i < $used; $i++) { ?>
<img class="marker" src="images/hintused.gif" alt="-" />
<?php } ?>