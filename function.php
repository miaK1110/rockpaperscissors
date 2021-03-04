<?php
//==============================================
//ini_set setting
//==============================================
//report all errors
error_reporting(E_ALL);
//take a log
ini_set('log_errors', 'On');
//set a logfile
ini_set('error_log', 'php.log');

//==============================================
//debug
//==============================================
//set 'false' when you're not developing
$debug_flg = false;
function debug($str)
{
  global $debug_flg;
  if (!empty($debug_flg)) {
    error_log($str);
  }
}
//===============================================
//init variable
//===============================================

$allies = array();

//===============================================
//const
//===============================================
class Type
{
  const BARBARIAN = 1;
  const BARD = 2;
  const DRUID = 3;
  const FIGHTER = 4;
  const RANGER = 5;
  const ROGUE = 6;
  const WARLOCK = 7;
  const WIZARD = 8;
}

class pattern
{
  const ROCK = 1;
  const PAPER = 2;
  const SCISSORS = 3;
}
//===============================================
//class
//===============================================
class Allies
{
  private $name;
  private $img;
  private $attack;

  public function __construct($name, $type, $img, $attack, $hand)
  {
    $this->name = $name;
    $this->type = $type;
    $this->img = $img;
    $this->attack = $attack;
    $this->hand = $hand;
  }
  public function setAttack($num)
  {
    $this->attack = (int)filter_var($num, FILTER_VALIDATE_FLOAT);
  }
  public function getName()
  {
    return $this->name;
  }
  public function getType()
  {
    return $this->type;
  }
  public function getImg()
  {
    return $this->img;
  }
  public function getAttack()
  {
    return $this->attack;
  }
  public function getAttackPoints()
  {
    return $this->getAttack();
  }
  public function getHand()
  {
    return mt_rand(1, 3);
  }
  public function createSentence($str1, $str2)
  {
    $_SESSION['speaker'] = $str1;
    $_SESSION['sentence'] .= $str2 . '<BR/>';
  }
  public function allyGreeting()
  {
    switch ($this->type) {
      case Type::BARBARIAN:
        $this->createSentence($this->getName(), 'Greetings.');
        break;
      case Type::BARD:
        $this->createSentence($this->getName(), 'Hello hello.');
        break;
      case Type::DRUID:
        $this->createSentence($this->getName(), 'Do you need my help huh?');
        break;
      case Type::FIGHTER:
        $this->createSentence($this->getName(), 'You good?');
        break;
      case Type::RANGER:
        $this->createSentence($this->getName(), 'Hi there.');
        break;
      case Type::ROGUE:
        $this->createSentence($this->getName(), 'Hello, how are you?');
        break;
      case Type::WARLOCK:
        $this->createSentence($this->getName(), 'Nice to meet you.');
        break;
      case Type::WIZARD:
        $this->createSentence($this->getName(), 'Hello.');
        break;
    }
  }
  //when user lose
  public function allyWin()
  {
    switch ($this->type) {
      case Type::BARBARIAN:
        $this->createSentence($this->getName(), 'Farewell.');
        break;
      case Type::BARD:
        $this->createSentence($this->getName(), 'Bye-bye now.');
        break;
      case Type::DRUID:
        $this->createSentence($this->getName(), 'We will meet again.');
        break;
      case Type::FIGHTER:
        $this->createSentence($this->getName(), 'Sorry, I must go.');
        break;
      case Type::RANGER:
        $this->createSentence($this->getName(), 'Goodbye.');
        break;
      case Type::ROGUE:
        $this->createSentence($this->getName(), 'You should leave.');
        break;
      case Type::WARLOCK:
        $this->createSentence($this->getName(), 'Have a good day.');
        break;
      case Type::WIZARD:
        $this->createSentence($this->getName(), 'See you.');
        break;
    }
  }
  //when user win
  public function allyLose()
  {
    switch ($this->type) {
      case Type::BARBARIAN:
        $this->createSentence($this->getName(), 'I will crush him.');
        break;
      case Type::BARD:
        $this->createSentence($this->getName(), 'Yes! I can sing a song for you.');
        break;
      case Type::DRUID:
        $this->createSentence($this->getName(), 'I am with you.');
        break;
      case Type::FIGHTER:
        $this->createSentence($this->getName(), 'I will protect you.');
        break;
      case Type::RANGER:
        $this->createSentence($this->getName(), 'I will watch your back.');
        break;
      case Type::ROGUE:
        $this->createSentence($this->getName(), 'I am on the right path.');
        break;
      case Type::WARLOCK:
        $this->createSentence($this->getName(), 'Okay, I will follow you.');
        break;
      case Type::WIZARD:
        $this->createSentence($this->getName(), 'We must defeat the devil king!');
        break;
    }
  }
}

class User
{
  private const LIFE = 3;
  private $totalAllies;
  private $lifeCount;
  private $attackPoints;

  public function __construct()
  {
    $this->lifeCount = $this::LIFE;
    $this->totalAllies = 0;
    $this->attackPoints = 0;
  }

  public function addToTotalAllies()
  {
    $this->totalAllies++;
  }
  public function minusToTotalAllies()
  {
    $this->totalAllies--;
  }
  public function addToAttackPoints($allyAttackPoints)
  {
    $this->attackPoints += $allyAttackPoints;
  }
  public function minusToAttackPoints($allyAttackPoints)
  {
    $this->attackPoints -= $allyAttackPoints;
  }
  public function minusLifeCount()
  {
    $this->lifeCount--;
  }
  public function getLifeCount()
  {
    return $this->lifeCount;
  }
  public function getTotalAllies()
  {
    return $this->totalAllies;
  }
  public function getAttackPoints()
  {
    return $this->attackPoints;
  }
}

interface SentenceInterface
{
  public static function allyAndBossSet($str1, $str2);
  public static function resultSet($str);
  public static function clear();
}

class Sentence implements SentenceInterface
{
  public static function allyAndBossSet($str1, $str2)
  {
    $_SESSION['speaker'] = $str1;
    $_SESSION['sentence'] .= $str2 . '<br>';
  }
  public static function resultSet($str)
  {
    $_SESSION['sentenceResult'] .= $str . '<br>';
  }
  public static function clear()
  {
    //make it nothing in there
    $_SESSION['speaker'] = '';
    $_SESSION['sentence'] = '';
    $_SESSION['sentenceResult'] = '';
  }
}
//===============================================
//function
//===============================================
function createAlly()
{
  global $allies;
  $array = array_keys($allies);
  $arrayMax = max($array);
  $ally =  $allies[mt_rand(0, $arrayMax)];
  $_SESSION['ally'] =  $ally;
  $_SESSION['sentence'] = '';
  $_SESSION['sentenceResult'] = '';
  $_SESSION['ally']->allyGreeting();
}
function init()
{
  $_SESSION['ally'] = '';
  $_SESSION['user'] = new User();
  $_SESSION['speaker'] = '';
  $_SESSION['sentence'] = '';
  $_SESSION['sentenceResult'] = '';
  $_SESSION['battleFlg'] = false;
  $_SESSION['result'] = '';
}

function gameHaveFinished()
{
  $_SESSION = array();
  session_unset();
  debug("Session reset: " . print_r($_SESSION, true));
}
function battle($user, $ally)
{
  $user = (int)$user;
  $ally = (int)$ally;
  if ($user !== $ally) {
    if (($user === pattern::ROCK && $ally === pattern::SCISSORS)) {
      debug('----user won----');
      $_SESSION['user']->addToTotalAllies();
      $_SESSION["user"]->addToAttackPoints($_SESSION['ally']->getAttackPoints());
      Sentence::clear();
      Sentence::resultSet('You chose Rock and ' . $_SESSION['ally']->getName() . ' chose Scissors.<BR><BR>You won.<BR><BR>Press continue to next.');
      debug('You chose Rock and ' . $_SESSION['ally']->getName() . ' chose Scissors. You won. Press continue to next.');
      $_SESSION['battleFlg'] = 'win';
      Sentence::allyAndBossSet($_SESSION['ally']->getName(), $_SESSION['ally']->allyLose());
    } elseif (($user === pattern::PAPER && $ally === pattern::ROCK)) {
      debug('----user won----');
      $_SESSION['user']->addToTotalAllies();
      $_SESSION["user"]->addToAttackPoints($_SESSION['ally']->getAttackPoints());
      Sentence::clear();
      Sentence::resultSet('You chose Paper and ' . $_SESSION['ally']->getName() . 'chose Rock.<BR><BR>You won.<BR><BR>Press continue to next.');
      debug('You chose Paper and ' . $_SESSION['ally']->getName() . 'chose Rock. You won.');
      $_SESSION['battleFlg'] = 'win';
      Sentence::AllyAndBossSet($_SESSION['ally']->getName(), $_SESSION['ally']->allyLose());
    } elseif (($user === pattern::SCISSORS && $ally === pattern::PAPER)) {
      debug('----user won----');
      $_SESSION['user']->addToTotalAllies();
      $_SESSION["user"]->addToAttackPoints($_SESSION['ally']->getAttackPoints());
      Sentence::clear();
      Sentence::resultSet('You chose Scissors and ' . $_SESSION['ally']->getName() . ' chose Paper.<BR><BR>You won.<BR><BR>Press continue to next.');
      debug('You chose Scissors and ' . $_SESSION['ally']->getName() . ' chose Paper. You won.');
      $_SESSION['battleFlg'] = 'win';
      Sentence::allyAndBossSet($_SESSION['ally']->getName(), $_SESSION['ally']->allyLose());
    } elseif (($user === pattern::ROCK && $ally === pattern::PAPER)) {
      debug('----user lose----');
      $_SESSION['user']->minusLifeCount();
      Sentence::clear();
      Sentence::resultSet('You chose Rock and ' . $_SESSION['ally']->getName() . ' chose Paper.<BR><BR>You lost.<BR><BR>Press continue to next.');
      debug('You chose Rock and ' . $_SESSION['ally']->getName() . ' chose Paper. You lost.');
      $_SESSION['battleFlg'] = 'lose';
      Sentence::allyAndBossSet($_SESSION['ally']->getName(), $_SESSION['ally']->allyWin());
    } elseif (($user === pattern::PAPER && $ally === pattern::SCISSORS)) {
      debug('----user lose----');
      $_SESSION['user']->minusLifeCount();
      Sentence::clear();
      Sentence::resultSet('You chose Paper and ' . $_SESSION['ally']->getName() . ' chose SCISSORS.<BR><BR>You lost.<BR><BR>Press continue to next.');
      debug('You chose Paper and ' . $_SESSION['ally']->getName() . ' chose SCISSORS. You lost.');
      $_SESSION['battleFlg'] = 'lose';
      Sentence::allyAndBossSet($_SESSION['ally']->getName(), $_SESSION['ally']->allyWin());
    } elseif (($user === pattern::SCISSORS && $ally === pattern::ROCK)) {
      debug('----user lose----');
      $_SESSION['user']->minusLifeCount();
      Sentence::clear();
      Sentence::resultSet('You chose Scissors and ' . $_SESSION['ally']->getName() . ' chose ROCK.<BR><BR>You lost.<BR><BR>Press continue to next.');
      $_SESSION['battleFlg'] = 'lose';
      Sentence::allyAndBossSet($_SESSION['ally']->getName(), $_SESSION['ally']->allyWin());
    }
  } else {
    debug('----tie----');
    Sentence::clear();
    Sentence::allyAndBossSet($_SESSION['ally']->getName(), 'Tie! Try again! Rock paper scissors..');
    debug('You Tied. Choose your hand again.');
  }
}
//===============================================
//instance
//===============================================
$allies[] = new Allies('barbarian-dwarf', Type::BARBARIAN, 'imgs/charas/barbarian.png', mt_rand(50, 120), mt_rand(1, 3));
$allies[] = new Allies('bard-human', Type::BARD, 'imgs/charas/bard.png', mt_rand(50, 100),  mt_rand(1, 3));
$allies[] = new Allies('druid-human', Type::DRUID, 'imgs/charas/druid.png', mt_rand(50, 120), mt_rand(1, 3));
$allies[] = new Allies('fighter-tiefling', Type::FIGHTER, 'imgs/charas/fighter.png', mt_rand(50, 120), mt_rand(1, 3));
$allies[] = new Allies('ranger-elf', Type::RANGER, 'imgs/charas/ranger.png', mt_rand(50, 120), mt_rand(1, 3));
$allies[] = new Allies('rogue-high-elf', Type::DRUID, 'imgs/charas/rogue.png', mt_rand(50, 100), mt_rand(1, 3));
$allies[] = new Allies('warlock-human', Type::WARLOCK, 'imgs/charas/warlock.png', mt_rand(50, 120), mt_rand(1, 3));
$allies[] = new Allies('wizard-human', Type::WIZARD, 'imgs/charas/wizard.png', mt_rand(50, 120), mt_rand(1, 3));
