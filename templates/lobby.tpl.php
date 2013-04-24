<a href="?page=newgame" >Új játék létrehozása</a>
<table>
  <tr>
    <th>Játék neve</th>
    <th>Játékosok száma</th>
    <th>Játékosok</th>
  </tr>
<?php foreach($games as $game) { ?>
  <tr>
    <td><?php echo $game->name; ?></td>
    <td><?php echo $game->playersnum; ?></td>
    <td>
      <?php foreach($game->players as $player) { ?>
        <?php echo Player::getUserName($player->id); ?><br />
      <?php } ?>
    </td>
    <td><a href="?page=lobby&action=join&game=<?php echo $game->id; ?>">Csatlakozás</a></td>
  </tr>
<?php } ?>
</table>