<table>
    <thead>
        <tr>
            <th>Question</th>
            <th>Options</th>
            <th>Deadline</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            $data = json_decode(file_get_contents('polls.json'), true);
            foreach($data as $question){
        ?>
        <tr>
            <td><?php echo $question['question']; ?></td>
            <td>
                <?php 
                    foreach($question['options'] as $option){
                        echo $option['text']."<br>";
                    }
                ?>
            </td>
            <td><?php echo $question['deadline']; ?></td>
            <td><?php echo $question['created_at']; ?></td>
            <td><a href="edit.php?id=<?php echo $question['id']; ?>">Edit</a></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

th {
    background-color: #f2f2f2;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}
</style>
