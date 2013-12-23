<table>
  <tr>
    <?php if(isset($gamelist->columns['name'])) { ?>
    <th>Játék neve</th>
    <?php } ?>
    <?php if(isset($gamelist->columns['playersnum'])) { ?>
    <th>Játékosok száma</th>
    <?php } ?>
    <?php if(isset($gamelist->columns['players'])) { ?>
    <th>Játékosok</th>
    <?php } ?>
    <?php if(isset($gamelist->columns['status'])) { ?>
    <th>Státusz</th>
    <?php } ?>
  </tr>
<?php foreach($gamelist->games as $game) { ?>
  <tr>
    <?php if(isset($gamelist->columns['name'])) { ?>
    <td>
    <?php if($gamelist->url) { ?>
    <a href="<?php echo $gamelist->url.$game['id']; ?>"><?php echo $game['name']; ?></a>
    <?php } else { ?>
    <?php echo $game['name']; ?>
    <?php } ?>
    </td>
    <?php } ?>
    <?php if(isset($gamelist->columns['playersnum'])) { ?>
    <td><?php echo $game['playersnum']; ?></td>
    <?php } ?>
    <?php if(isset($gamelist->columns['players'])) { ?>
    <td>
      <?php echo implode(', ', $game['players']);  ?><br />
    </td>
    <?php } ?>
    <?php if(isset($gamelist->columns['status'])) { ?>
    <td><?php echo Game::$statusNames[$game['status']]; ?></td>
    <?php } ?>
  </tr>
<?php } ?>
</table>