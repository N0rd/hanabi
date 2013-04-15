<div id="firstline">
	<div id="own">
    <h2 id="ownname" class="element">Player 1</h2>
    <div id="ownhand">
      <?php echo $gamerender['ownhand']; ?>
    </div>
		<form id="actions" method="post">
        	<div id="actionhints" class="displaynone">
                <input class="toplayer" type="button" id="player2" name="player2" value="Player 2" onclick="return towhom(2, 'Player 2');" />
                <input class="toplayer" type="button" id="player3" name="player3" value="Player 3" onclick="return towhom(3, 'Player 3');" />
                <input class="toplayer" type="button" id="player4" name="player4" value="Player 4" onclick="return towhom(4, 'Player 4');" />
                <input class="toplayer" type="button" id="player5" name="player5" value="Player 5" onclick="return towhom(5, 'Player 5');" />
	            <div class="linebrake">&nbsp;</div>
            </div>
        	<div id="actionhintwhat" class="displaynone">
            	<input type="hidden" id="whom" name="whom" value="" />
                <div id="hintcolors">
                   	<input class="hintcolor" type="submit" id="cred" name="cred" value="Piros" onclick="return beforesubmit(this)" />
                    <label for="red" class="hintlabel">
                    <?php hinthelp(); ?>
	                </label><br />
                	<input class="hintcolor" type="submit" id="cwhite" name="cwhite" value="Fehér" onclick="return beforesubmit(this)" />
    	                <label for="white" class="hintlabel"><?php echo ('4.'); ?></label><br />
                	<input class="hintcolor" type="submit" id="cgreen" name="cgreen" value="Zöld" onclick="return beforesubmit(this)" />
	                    <label for="green" class="hintlabel"><?php echo ('nincs'); ?></label><br />
                   	<input class="hintcolor" type="submit" id="cyellow" name="cyellow" value="Sárga" onclick="return beforesubmit(this)" />
                    	<label for="yellow" class="hintlabel"><?php echo ('2.'); ?></label><br />
	               	<input class="hintcolor" type="submit" id="cblue" name="cblue" value="Kék" onclick="return beforesubmit(this)" />
       	                <label for="blue" class="hintlabel"><?php echo ('5.'); ?></label><br />
                </div>
                <div id="hintnumbers">
                   	<input class="hintnumber" type="submit" id="num1" name="num1" value="1" onclick="return beforesubmit(this)" />
	                    <label for="num1" class="hintlabel"><?php echo ('1.'); ?></label><br />
                	<input class="hintnumber" type="submit" id="num2" name="num2" value="2" onclick="return beforesubmit(this)" />
                        <label for="num2" class="hintlabel"><?php echo ('2.'); ?></label><br />
                	<input class="hintnumber" type="submit" id="num3" name="num3" value="3" onclick="return beforesubmit(this)" />
       	                <label for="num3" class="hintlabel"><?php echo ('3.'); ?></label><br />
	               	<input class="hintnumber" type="submit" id="num4" name="num4" value="4" onclick="return beforesubmit(this)" />
      	                <label for="num4" class="hintlabel"><?php echo ('4., 5.'); ?></label><br />
                	<input class="hintnumber" type="submit" id="num5" name="num5" value="5" onclick="return beforesubmit(this)" />
		                <label for="num5" class="hintlabel"><?php echo ('nincs'); ?></label>
                </div>             
	            <div class="linebrake">&nbsp;</div>
            </div>
        	<input class="hidden" type="radio" id="selectcard1" name="selectcard" value="1" />
        	<input class="hidden" type="radio" id="selectcard2" name="selectcard" value="2" />
        	<input class="hidden" type="radio" id="selectcard3" name="selectcard" value="3" />
        	<input class="hidden" type="radio" id="selectcard4" name="selectcard" value="4" />
        	<input class="hidden" type="radio" id="selectcard5" name="selectcard" value="5" />
            <div class="linebrake">&nbsp;</div>
			<input class="action displaynone" type="button" id="canceling" name="canceling" value="Mégsem" onclick="return cancel();" />
			<input class="action" type="button" id="hinting" name="hinting" value="Súgás" onclick="return hint(3);" />
			<input class="action" type="button" id="fireing" name="fireing" value="Lővés" onclick="return fire(5)" />
			<input class="action" type="button" id="droping" name="droping" value="Dobás" onclick="return drop(5);" />
		</form>      
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
	</div>			
</div>
<div class="linebrake">&nbsp;</div>
<div id="logandchat">
  <span id="consolelog">&nbsp;</span>
  <?php krumo($game); ?>
</div>
<div id="players">
  <?php echo $gamerender['players']; ?>
</div>
<div id="discard">
  <?php echo $gamerender['discard']; ?>
</div>
