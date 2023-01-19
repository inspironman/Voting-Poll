<?php
$poll_id = $_GET['poll_id'];
$poll_data = json_decode(file_get_contents("polls.json"), true);

foreach ($poll_data as $poll) {
    if ($poll['id'] == $poll_id) {
        $question = $poll['question'];
        $options = $poll['options'];
        break;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Poll Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h1, h2 {
            text-align: center;
        }
        ul {
            list-style: none; 
            margin: 0; 
            padding: 0;
        }

        li {
            margin: 10px 0;
            font-size: 18px;
        }
        .vote-count {
            font-weight: bold;
            color: #333;
            margin-left: 10px;
        }
        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50; 
            color: white;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            margin-top: 20px;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <h1>Poll Results</h1>
    <h2><?php echo $question; ?></h2>
    <ul>
    <?php foreach ($options as $option): ?>
        <li><?php echo $option['text']; ?> <span class="vote-count"><?php echo $option['count']; ?> votes</span></li>
    <?php endforeach; ?>
    </ul>
    <a href="index.php" class="back-button">Back to Index</a>
</body>
</html>
