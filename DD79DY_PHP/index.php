<?php
session_start(); 
// username : admin
// password : admin


function readPollsFromFile() {
    $jsonData = file_get_contents("polls.json");
    return json_decode($jsonData, true);
}
function savePollToFile($poll) {
    $jsonData = json_encode($poll);
    file_put_contents("polls.json", $jsonData);
}
 
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");  
    exit();
}

if (isset($_POST['login'])) {
    header("Location: login.php"); 
    exit();
}
$polls = readPollsFromFile(); 

$ongoingPolls = array();
$completedPolls = array();
foreach ($polls as $poll) {
    if (strtotime($poll['deadline']) > time()) {
        $ongoingPolls[] = $poll;
    } else {
        $completedPolls[] = $poll;
    }
}

usort($ongoingPolls, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});
usort($completedPolls, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});
?>
<!DOCTYPE html>
<html>
<head>
    <title>PollingPage</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<a href="rules.php">Rules</a> 
    <div class="inline-block"> 
    <a href="delete.php" class="create-poll-button">Delete Poll</a>
    <?php
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] && $_SESSION['username'] == 'admin') {
        echo '<a href="polledit.php" class="create-poll-button">Edit Poll</a>';
        }
    ?> 
    <a href="create.php" class="create-poll-button">Create Poll</a>  
    </div>
<div>
    <h4>Welcome <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {echo $_SESSION['username'];} ?></h4>
    <h1>The Pulse of the People</h1>
</div>

    <h3>Welcome to our Poll application.</h3>
    <h3>Here you can view and vote on polls.</h3>
    <?php
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
        echo '<form action="" method="post">';
        echo '<input type="submit" name="logout" value="Logout">';
        echo '</form>';
        }
    ?>
    <?php
    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
        echo '<form action="" method="post">';
        echo '<input type="submit" name="login" value="Login/Register">';
        echo '</form>';
        }
    ?>
    <h2>Ongoing Polls</h2>
    <ul>
    <?php 
    usort($polls, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
    foreach ($polls as $poll): 
        if(strtotime($poll['deadline']) >= time()) { ?>
            <li>Poll #<?php echo $poll['id']; ?> - <?php echo $poll['question']; ?> &nbsp;&nbsp;from [<?php echo $poll['created_at']; ?>]  to [<?php echo $poll['deadline']; ?>]
            <a href="vote.php?poll_id=<?php echo $poll['id']; ?>">Vote</a>
            </li>
    <?php } endforeach; ?>
    </ul>
    <h2>Closed Polls</h2>
    <ul>
    <?php foreach ($polls as $poll): 
        if(strtotime($poll['deadline']) < time()) { ?>
            <li>Poll #<?php echo $poll['id']; ?> - <?php echo $poll['question']; ?> - <?php echo $poll['created_at']; ?> - <?php echo $poll['deadline']; ?>
            <a href="result.php?poll_id=<?php echo $poll['id']; ?>">Results</a>
            </li>
    <?php } endforeach; ?>
    </ul>
    <footer class="footer">
        <p>Developed by Kooder Deepak</p>
    </footer>
</body>
</html>
