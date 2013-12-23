<?php
Class GameList {
  public $playerid, $statuses, $columns, $games, $url, $urlparam;
  function __construct($type = null) {
    switch($type) {
      case 'new':
        $this->playerid = null;
        $this->statuses = array(0);
        $this->url = '?page=lobby&action=join&game=';
        $this->columns = array('name', 'playersnum', 'players');
        break;
      case 'current':
        $this->playerid = $_SESSION['user']['id'];
        $this->statuses = array(0, 1);
        $this->url = '?page=main&id=';
        $this->columns = array('name', 'playersnum', 'players', 'status');  
        break;
      default:
        $this->playerid = null;
        $this->statuses = array_keys(Game::$statusNames);
        $this->columns = array('name', 'playersnum', 'players', 'status');
        $this->url = null;
    }
  }

  public function generate() {
    $statuses = implode(',', $this->statuses);
    $columns = array();
    foreach($this->columns as $column) {
      $columns[$column] = $column;
    }
    $this->columns = $columns;
    $q = 'SELECT g.id, g.name, g.playersnum, g.status, u.id AS playerid, u.name AS playername FROM games AS g 
                              JOIN game_player AS gp ON g.id = gp.gameid
                              JOIN users AS u ON gp.playerid = u.id
                              WHERE g.status IN ('.$statuses.')';
    
    if($this->playerid) {
      $playerid = (int)$this->playerid;
      $q .= ' AND g.id IN (SELECT gameid FROM game_player WHERE playerid = '.$playerid.')';
    }
    $query = DB::$db->query($q);
    $query->execute();
    $this->games = array();
    while($game = $query->fetch()) {
      if(!isset($this->games[$game['id']])) {
        $this->games[$game['id']] = $game;
      }
      $this->games[$game['id']]['players'][$game['playerid']] = $game['playername'];
    }
  }
  
  public function render() {
    $this->generate();
    return Template::renderTemplate('gamelist', array('gamelist' => $this));
  }
  
}