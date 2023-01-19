<?php
session_start();
$error = "";

if(!isset($_SESSION['username']) || $_SESSION['username'] != "admin") {
    header("Location: redirect.php");
} 

$json = file_get_contents('polls.json');
$data = json_decode($json, true);
if(isset($_POST['id']) && !empty($_POST['id'])){
    $id = $_POST['id'];
} else {
    $error = "Click on Delete to delete the Poll";
}
if(!empty($id)){
    foreach ($data as $key => $poll) {
        if ($poll['id'] == $id) {
            unset($data[$key]);
            break;
        }
    }
    $data = array_values($data); // re-index the array
    $json = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents('polls.json', $json);
    $success = "Data deleted successfully.";
} else {
    $error = "Click on Delete to delete the Poll";
}
   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Poll</title>
    <link rel="stylesheet" type="text/css" href="delete.css">
</head>
<body>
    
<div>
<table>
    <tr>
        <th>ID</th>
        <th>Question</th>
        <th>Action</th>
    </tr>
    <?php 
        $json = file_get_contents('polls.json');
        $data = json_decode($json, true);
        foreach($data as $poll):
    ?>
    <tr>
        <td><?= $poll['id'] ?></td>
        <td><?= $poll['question'] ?></td>
        <td>
            <form action="delete.php" method="post">
                <input type="hidden" name="id" value="<?= $poll['id'] ?>">
                <input type="submit" value="Delete">
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</div>
<div>
    <?php if(isset($_GET['error'])): ?>
        <div class="error"><?= $_GET['error'] ?></div>
    <?php endif; ?>
    <?php if(isset($_GET['success'])): ?>
        <div class="success"><?= $_GET['success'] ?></div>
    <?php endif; ?>
    <a href="index.php" class="back-button">Back to Main Page</a>
</div>
</body>
</html>