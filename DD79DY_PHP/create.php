<?php
session_start();
$error = "";

if(!isset($_SESSION['username']) || $_SESSION['username'] != "admin") {
    header("Location: redirect.php");
} 

if(isset($_POST['create'])) {
    if(empty($_POST['question']) || empty($_POST['options']) || empty($_POST['deadline'])) {
        $error = "All fields are required";
    } else {
        if(file_exists('polls.json')){
            $polls = json_decode(file_get_contents('polls.json'), true);
        }else{
            $polls = array();
        }
        $options = explode("\n", $_POST['options']);
        $options = array_map('trim', $options);
        $options_array = array();
        for($i=0; $i<count($options); $i++){
            $options_array[] = array("id"=>$i+1,"text"=>$options[$i],"count"=>0);
        }
        $id = uniqid();
        $polls[] = [
            'id' => $id,
            'question' => $_POST['question'],
            'options' => $options_array,
            'multiple_choice' => (isset($_POST['multiple_choice'])) ? true : false,
            'deadline' => $_POST['deadline'],
            'created_at' => date("Y-m-d")
        ];
        file_put_contents('polls.json', json_encode($polls,JSON_PRETTY_PRINT));
        $success = "Poll created successfully";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Poll</title>
    <link rel="stylesheet" type="text/css" href="create.css">
</head>
<body>
<h2>Hello Admin</h2>
<form method="post">
    <div>
        <label for="question">Question:</label>
        <textarea name="question" id="question"></textarea>
    </div>
    <div>
        <label for="options">Options:</label>
        <textarea name="options" id="options"></textarea>
        <p>Enter one option per line</p>
    </div>
    <div>
        <input type="checkbox" name="multiple_choice" id="multiple_choice">
        <label for="multiple_choice">Allow multiple choices</label>
    </div>
    <div>
        <label for="deadline">Deadline:</label>
        <input type="date" name="deadline" id="deadline">
    </div>
    <div>
        <input type="submit" name="create" value="Create Poll">
    </div>
    <?php if(isset($error)): ?>
    <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <?php if(isset($success)): ?>
    <div class="success"><?= $success ?></div>
    <?php endif; ?>
</form>

<a href="index.php" class="back-button">Back to Main Page</a>
</body>
</html>
