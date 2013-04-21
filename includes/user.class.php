<?php
Class User {
  public $id, $name, $username, $password1, $password2, $email;

  function __construct($param = null) {
    if(is_array($param)){
      $this->loadFromArray($param);
    } else if(is_int($param)){
      $this->loadFromDB($param);
    } else if($param == 'current'){
      $this->loadFromDB($_SESSION['user']['id']);
    }

    else {
      $this->id = null;
      $this->name = '';
      $this->password1 = '';
      $this->password2 = '';
      $this->email = '';
    }
  }
  
  public function loadFromArray($array) {
    $this->id = isset($array['id'])?$array['id']:null;
    $this->name = isset($array['name'])?$array['name']:'';
    $this->password1 = isset($array['password1'])?$array['password1']:null;
    $this->password2 = isset($array['password2'])?$array['password2']:null;
    $this->email = isset($array['email'])?$array['email']:'';
  }

  public function validate() {
    //TODO: check if name already exists
    if(!$this->name) {
      $errors['name'] = 'Minek nevezzelek?';
    }
    if(!$this->id && !$this->password1){
      $errors['password1'] = 'Jelszó az kell, hogy legyen mit elfelejteni!';
    }
    if($this->password1 != $this->password2) {
      $errors['password2'] = 'Nem sikerült ugyanazt a jelszót leírni mégegyszer!';
    }
    if($this->password1 && strlen($this->password1) < 5){
      $errors['password1'] = 'A jelszó legyen legalább 5 karakteres!';
    }
    if(!$this->email){
      $errors['email'] = 'Kell, hogy legyen email címed!';
    } else if(!validate_email($this->email)){
      $errors['email'] = 'Elgépelted az email címedet. ';
    }
    if(!isset($errors)) {
      return false;
    }
    return $errors;
  }

  public function loadFromDB($id){
    $id = (int)$id;
    if(!$id) {
      return false;
    }
    $query = DB::$db->prepare("SELECT name, email FROM users WHERE id = :id");
    $query->bindParam(':id', $id);
    $query->execute();
    if($m = $query->fetch()) {
      $this->id = $id;
      $this->name = $m['name'];
      $this->email = $m['email'];
    } else {
      return false;
    }
  }

  public function saveToDB() {
    $password = encrypt_password($this->password1);
    if($this->password1){
      $passwordstr = 'password = :password,';
    } else {
      $passwordstr = '';
    }
    if($this->id) {
      $query = DB::$db->prepare("UPDATE users SET name = :name {$passwordstr}
        email = :email WHERE id = :id");
      $query->bindParam(':id', $this->id);
    } else {
      $query = DB::$db->prepare("INSERT INTO users(name, password, email)
        VALUES (:name, :password, :email)");
    }
    $query->bindParam(':name', $this->name);
    $query->bindParam(':email', $this->email);
    if($this->password1){
      $query->bindParam(':password', $password);
    }
    $query->execute();
  }
}