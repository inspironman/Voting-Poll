<?php
session_start();

if ( !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}
 
$errors = [];
$poll_id = $_GET['poll_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $options = $_POST['options'] ?? [];
    if (!$options) {
        $errors[] = 'You must select at least one option.';
    } else {
    }
    if (!$errors) {
        $message = 'Your vote has been successfully submitted.';
    }
}
if(file_exists('polls.json')){
    $polls = json_decode(file_get_contents('polls.json'), true);
    $poll = null;
    foreach ($polls as $p) {
        if ($p['id'] == $poll_id) {
            $poll = $p;
            break;
        }
    }
    if (!$poll) {
        echo 'Poll not found.';
        exit;
    }
}else{
    echo 'Polls not found.';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote</title>
    <link rel="stylesheet" type="text/css" href="vote.css">
</head>
<body>
<div>
    <h2>Poll: <?= htmlspecialchars($poll['question']) ?></h2>
    <p>Deadline: <?= htmlspecialchars($poll['deadline']) ?></p>
    <p>Time of creation: <?= htmlspecialchars($poll['created_at']) ?></p>
    <form method="post">
        <ul>
            <?php foreach ($poll['options'] as $opt): ?>
                <li>
                    <input type="<?= $poll['multiple_choice'] ? 'checkbox' : 'radio'?>" id="<?= $opt['id'] ?>" name="options[]" value="<?= $opt['id'] ?>" >
                    <label for="<?= $opt['id'] ?>"><?= htmlspecialchars($opt['text']) ?></label>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php if ($errors): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php if (isset($message)): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>
        <?php if (!isset($message)): ?>
            <input type="submit" value="Vote">
        <?php endif; ?>
    </form>
    <a href="index.php" class="back-button">Back to Main Page</a>
</div>
</body>
</html>
<script>
var multiple_choice = <?= $poll['multiple_choice'] ?>;
if(!multiple_choice){
    var radios = document.getElementsByName('options[]');
    for (var i = 0, length = radios.length; i < length; i++) {
        radios[i].onclick = function() {
            for (var i = 0; i < radios.length; i++) {
                radios[i].checked = false;
            }
            this.checked = true;
        };
    }
}
</script>
<?php

if(isset($_POST['options'])) {
    if(empty($_POST['options'])) {
        $errors[] = "Please select an option.";
    } else {
        if(file_exists('polls.json')){
            $polls = json_decode(file_get_contents('polls.json'), true);
        }else{
            $polls = array();
        }
        for($i=0; $i<count($polls); $i++){
            if($polls[$i]['id'] == $poll['id']){
                for($j=0; $j<count($_POST['options']); $j++){
                    for($k=0; $k<count($polls[$i]['options']); $k++){
                        if($polls[$i]['options'][$k]['id'] == $_POST['options'][$j]){
                            $polls[$i]['options'][$k]['count']++;
                            break;
                        }
                    }
                }
                break;
            }
        }
        file_put_contents('polls.json', json_encode($polls,JSON_PRETTY_PRINT));
        $message = "Your vote has been successfully submitted.";
    }
}
?>
<?php
$poll_id = $_GET['id'] ?? 1;
$json_file = 'polls.json';
$json_data = file_get_contents('polls.json');
$polls = json_decode($json_data, true);
$poll = null;

foreach ($polls as $p) {
    if ($p['id'] == $poll_id) {
        $poll = $p;
        break;
    }
}
?>