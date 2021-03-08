<?php
require('function.php');
//init everything and create an ally

if (!empty($_POST)) {
  session_start();
  debug('post:' . print_r($_POST, true));
  if (!empty($_POST["start"])) {
    init();
    createAlly();
    debug('-----Game Start-----');
    debug('Session Data at start:' . print_r($_SESSION, true));
  }
  if (!empty($_SESSION['battleFlg']) && $_SESSION['battleFlg'] === 'win' || !empty($_SESSION['battleFlg']) && $_SESSION['battleFlg'] === 'lose') {
    if (!empty($_POST['continue-battle'])) {
      $_SESSION["battleFlg"] = '';
      createAlly();
      debug('Session Data after continue:' . print_r($_SESSION, true));
    }
  } else {
    if (!empty($_POST["rock_x"])) {
      battle(PATTERN::ROCK, $_SESSION['ally']->getHand());
      debug('Session Data after battle:' . print_r($_SESSION, true));
    } elseif (!empty($_POST["paper_x"])) {
      battle(PATTERN::PAPER, $_SESSION['ally']->getHand());
      debug('Session Data after battle:' . print_r($_SESSION, true));
    }
    if (!empty($_POST["scissors_x"])) {
      battle(PATTERN::SCISSORS, $_SESSION['ally']->getHand());
      debug('Session Data after battle:' . print_r($_SESSION, true));
    }
  }
  //when user lifeCount === 0
  if (!empty($_SESSION['user']) && $_SESSION['user']->getLifeCount() === 0) {
    Sentence::clear();
    $bossHp = 333;
    $result = $bossHp - ($_SESSION['user']->getAttackPoints());
    if (!empty($_POST['attack'])) {
      $attack = $_POST['attack'];

      debug('Rest of bosss hp:' . print_r($result, true));

      if ($result <= 0) {
        $_SESSION['result'] = 'win';
        // debug('result(win)session:' . print_r($_SESSION, true));
      } else {
        $_SESSION['result'] = 'lose';
        // debug('result(lose)session:' . print_r($_SESSION, true));
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rock Paper Scissors</title>
  <link rel="stylesheet" href="reset.css">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Stick&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=DotGothic16&family=Roboto&display=swap" rel="stylesheet">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>

<body>
  <main class="wrapper">
    <?php if (!empty($_POST['restart'])) {
      gameHaveFinished();
    }
    ?>
    <?php if (empty($_SESSION)) : ?>

      <div class="start-wrapper">
        <div class="title-container">
          <h1 class="title">Rock Paper Scissors</h1>
        </div>
        <div class="place-container">
          <img src="imgs/objects/place.png">
        </div>
        <div class="description-container">
          <p>You are just a person who dreamed of defeating the devil king but you have no skills. Luckily, you will meet some adventurers who will want to help you if you can win "rock scissors paper". There is only one chance to attack the devil king because he has a strong skill. So collect as many allies as you can! Remember: if you lose 'rock, paper, scissors', your life total goes down by 1.</p>
        </div>
        <form class="btn-container" method="post">
          <input type="submit" class="btn" name="start" value="Play">
        </form>
      </div>
      <!-- when user lifeCount === 0, show the boss screen -->
    <?php elseif ($_SESSION['user']->getLifeCount() === 0 && !$_SESSION['result']) : ?>
      . <div class="game-wrapper">
        <div class="chara-container">
          <div class="chara-name">
            <h3>BOSS: devil king</h3>
            <h3>HP:333</h3>
          </div>
          <img src="imgs/charas/boss.png">
        </div>
        <form class="btn-container" method="post">
          <input type="submit" class="btn" name="attack" value="Attack">
        </form>
        <div class="life-container">
          <p>
            <?php if (!empty($_SESSION['user']->getTotalAllies())) {
              echo 'Your allies:' . $_SESSION['user']->getTotalAllies();
            } ?>
            <?php if (empty($_SESSION['user']->getTotalAllies())) {
              echo 'Your allies:0';
            } ?>
          </p>
        </div>
        <div class="comment-wrapper">
          <?php if ($_SESSION['user']->getTotalAllies() <= 2) {
            Sentence::clear();
            Sentence::allyAndBossSet('Boss', 'What a waste of time. I will finish this with my special attack!');
          } elseif ($_SESSION['user']->getTotalAllies() <= 5) {
            Sentence::clear();
            Sentence::allyAndBossSet('Boss', 'Looks like you have got some friends..');
          } elseif ($_SESSION['user']->getTotalAllies() < 10) {
            Sentence::clear();
            Sentence::allyAndBossSet('Boss', 'Looks like you have got many friends..');
          } elseif ($_SESSION['user']->getTotalAllies() >= 10) {
            Sentence::clear();
            Sentence::allyAndBossSet('Boss', 'You brought over 10 people!? .. Well, no matter how many people come.');
          } elseif ($_SESSION['user']->getTotalAllies() === 0) {
            Sentence::clear();
            Sentence::allyAndBossSet('Boss', 'You came here alone. How
              ridiculous!');
          } ?>
          <div class="speaker"><?php echo $_SESSION['speaker']; ?></div>
          <div class="comment">
            <h3>
              <?php echo $_SESSION['sentence']; ?>
            </h3>
          </div>
        </div>
      </div>
      <!-- When boss's Hp < attackPoints -->
    <?php elseif (!empty($_POST['attack']) && $_SESSION['result'] === 'win') : ?>
      . <div class="game-wrapper">
        <div class="chara-container">
          <img src="imgs/objects/treasure_chest.png" style="margin-top:100px;margin-bottom:100px;width:150px; height: 100px;">
        </div>
        <div class="comment-wrapper">
          <div class="speaker">???</div>
          <div class="comment">
            <h3>
              The demon is dead. Your attack points were <?php echo $_SESSION['user']->getAttackPoints(); ?>. Your party earned 10000G. YAY.
            </h3>
          </div>
        </div>
      </div>
      <form class="btn-container" method="post">
        <input type="submit" class="btn" name="restart" value="Restart?">
      </form>
      <!-- When boss's Hp > attackPoints -->
    <?php elseif (!empty($_POST['attack']) && $_SESSION['result'] === 'lose') : ?>
      . <div class="game-wrapper">
        <div class="chara-container">
          <img src="imgs/charas/sadface.png" style="margin-top:100px;margin-bottom:100px;width:150px; height: 100px;">
        </div>
        <div class="comment-wrapper">
          <div class="speaker">???</div>
          <div class="comment">
            <h3>Your attack points were <?php echo $_SESSION['user']->getAttackPoints(); ?>. The devil king didn't die. He used a special skill and you were all defeated.
            </h3>
          </div>
        </div>
      </div>
      <form class="btn-container" method="post">
        <input type="submit" class="btn" name="restart" value="Try again?">
      </form>
      <!-- battle screen -->
    <?php else : ?>
      <div class="game-wrapper">
        <div class="chara-container">
          <div class="chara-name">
            <h3 style="font-weight:bolder;"><?php echo $_SESSION['ally']->getName(); ?></h3>
            <h4>Attack Point:<?php echo $_SESSION['ally']->getAttack(); ?></h4>
          </div>
          <img src="<?php echo $_SESSION['ally']->getImg(); ?>">
        </div>
        <?php if ($_SESSION['battleFlg'] === 'win' || $_SESSION['battleFlg'] === 'lose') : ?>
          <form class="btn-container" method="post">
            <input type="submit" class="btn" name="continue-battle" value="continue">
          </form>
        <?php else : ?>
          <form class="hands-container" method="post">
            <input type="image" class="hand" name="rock" value="rock" src="imgs/objects/hand_rock.png" style="height:100px; width: 100px;">
            <input type="image" class="hand" name="paper" value="paper" src="imgs/objects/hand_paper.png" style="height:100px; width: 100px;">
            <input type="image" class="hand" name="scissors" value="scissors" src="imgs/objects/hand_scissors.png" style="height:100px; width: 100px;">
          </form>
        <?php endif; ?>
        <div class="life-container">
          <p>Life:</p><?php if ($_SESSION['user']->getLifeCount() === 1) echo '<i class="fas fa-heart fa-2x"></i>'; ?>
          <?php if ($_SESSION['user']->getLifeCount() === 2) echo '<i class="fas fa-heart fa-2x"></i><i class="fas fa-heart fa-2x"></i>'; ?>
          <?php if ($_SESSION['user']->getLifeCount() === 3) echo '<i class="fas fa-heart fa-2x"></i><i class="fas fa-heart fa-2x"></i><i class="fas fa-heart fa-2x"></i>'; ?>
          <p>
            <?php if (!empty($_SESSION['user']->getTotalAllies())) echo 'Allies:' . $_SESSION['user']->getTotalAllies(); ?>
          </p>
        </div>
        <div class="result-wrapper">
          <h4>
            <?php if (!empty($_SESSION['sentenceResult'])) echo $_SESSION['sentenceResult']; ?>
          </h4>
        </div>
        <div class="comment-wrapper">
          <div class="speaker"><?php echo $_SESSION['speaker'];  ?></div>
          <div class="comment">
            <h2>
              <?php echo $_SESSION['sentence']; ?>
            </h2>
          </div>
        </div>
      <?php endif; ?>
  </main>
</body>

</html>
