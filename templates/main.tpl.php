<div id="firstline">
	<div id="own">
    <h2 id="ownname" class="element"><?php echo $game->players[$game->currentplayer]->name; ?></h2>
    <div id="ownhand" class="actionbox">
      <?php echo $gamerender['ownhand']; ?>
    </div>
    <div id="actionhints" class="actionbox" style="display: none;">
    <?php foreach($game->players as $player) { 
			if ($player->id != $game->players[$game->currentplayer]->id) { ?>
        <input class="toplayer" type="button" value="<?php echo $player->name; ?>" data-playerplace="<?php echo $player->playerplace; ?>" />
      <?php 	}
		} ?>
      <div class="linebrake">&nbsp;</div>
    </div>
    <div id="actionhintwhat" class="actionbox" style="display: none;">
      <div id="hintcolors">
      <?php foreach($game->colors as $color => $data) { ?>
        <input class="hintselector" type="button" value="<?php echo $data['name']; ?>" data-id="<?php echo $color; ?>" /><br />
      <? } ?>
      </div>
      <div id="hintnumbers">
      <?php
      $maxnumber = Deck::getMaxNumber();
      for($number = 1; $number <= $maxnumber; $number++) { ?>
        <input class="hintselector" type="button" value="<?php echo $number; ?>" data-id="<?php echo $number; ?>" /><br />
      <? } ?>
      </div>
      <div class="linebrake">&nbsp;</div>
    </div>
    <div class="linebrake">&nbsp;</div>
    <input class="action" type="button" id="cancelButton" value="Mégsem" style="display: none;" />
    <input class="action" type="button" id="hintButton" value="Súgás" />
    <input class="action" type="button" id="fireButton" value="Lövés" />
    <input class="action" type="button" id="discardButton" value="Dobás" />
	</div>
  <div id="board">
    <div id="fireworks">
      <?php echo $gamerender['fireworks']; ?>
    </div>
	  <div id="hints">
      <?php echo $gamerender['hints']; ?>
	  </div>
	  <div id="lives">
      <?php echo $gamerender['lives']; ?>
	  </div>
		<div id="deckbox">
    	<?php echo $gamerender['deckbox']; ?>
		</div>    
  </div>
</div>
<div class="linebrake">&nbsp;</div>
<div id="#secoundline">
  <div id="logandchat">
  </div>
  <div id="players">
    <?php echo $gamerender['players']; ?>
  </div>
  <div id="discard">
    <?php echo $gamerender['discard']; ?>
  </div>
</div>