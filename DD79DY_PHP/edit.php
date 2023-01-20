<?php
    $question_id = $_GET['id'];
    $data = json_decode(file_get_contents('polls.json'), true);
    $filtered_data = array_filter($data, function($question) use ($question_id) {
        return $question['id'] == $question_id;
    });
    $question_data = reset($filtered_data);
?>

<?php
if(isset($_POST['save'])){
    $question = $_POST['question'];
    $options = $_POST['options'];
    $count = $_POST['count'];
    $deadline = $_POST['deadline'];
    $question_id = $_GET['id'];
    $data = json_decode(file_get_contents('polls.json'), true);

    $filtered_data = array_filter($data, function($question) use ($question_id) {
        return $question['id'] == $question_id;
    });
    $question_data = reset($filtered_data);
    $question_data['question'] = $question;
    $question_data['deadline'] = $deadline;
    $question_data['options'] = array();
    for($i=0; $i<count($options); $i++) {
        $question_data['options'][$i] = array("id"=>$i+1,"text"=>$options[$i], "count"=>$count[$i]);
    }
    $question_data = reset($filtered_data);
    foreach($data as $key => $val){
        if($val['id'] == $question_id){
            $data[$key]['question'] = $question;
            $data[$key]['deadline'] = $deadline;
            $data[$key]['options'] = array();
            for($i=0; $i<count($options); $i++) {
                $data[$key]['options'][$i] = array("id"=>$i+1,"text"=>$options[$i], "count"=>$count[$i]);
            }
        }
    }
    $json_data = json_encode($data,JSON_PRETTY_PRINT);
    file_put_contents('polls.json', $json_data);
    header('location: index.php');
    die();
}
?>



<form action="" method="post">
    <label for="question">Question:</label>
    <input type="text" name="question" value="<?php echo $question_data['question']; ?>"><br>
    <?php 
        foreach($question_data['options'] as $key => $option){
    ?>
    <label for="options">Option:</label>
    <input type="text" name="options[]" value="<?php echo $option['text']; ?>">
    <label for="options">Vote Count:</label>
    <input type="text" name="count[]" value="<?php echo $option['count']; ?>"><br>
    <?php } ?>

    <label for="deadline">Deadline:</label>
    <input type="text" name="deadline" value="<?php echo $question_data['deadline']; ?>"><br>

    <input type="submit" name="save" value="Save">
</form>

<style>
    form {
    width: 40%;
    margin: 0 auto;
    padding: 20px;
}

label {
    font-weight: normal;
    margin-bottom: 10px;
    display: block;
}

input[type="text"] {
    width: 80%;
    padding: 12px 20px;
    margin: 8px 0;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 4px;
}

input[type="submit"] {
    width: 80%;
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #45a049;
}
</style>
