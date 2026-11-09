<?php
require_once 'auth.php';
require_login();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Assessment - Student Grading</title>
</head>

<body>
    <?php
    include 'db.php';

    $search = $_GET['search'] ??"";

    $like = "%" . $search . "%";


    //$sql = $conn->prepare("SELECT * FROM results");
     $sql = $conn->prepare("SELECT * FROM results WHERE
     student_name LIKE ? OR 
     matric_number LIKE ? OR
     subject_name LIKE ? OR
     subject_code LIKE ?
     ORDER BY student_name");

     $sql->bind_param("ssss", $like, $like, $like, $like);

    $sql->execute();

    $result = $sql->get_result();

    include 'header.php';
    ?>



    <h2>Student Records</h2>

    <a href="add.php">+ Add Result</a>
    <br><br>
    <form method="get">
    <input type="text" name="search" placeholder="Search by name or matric number"
    value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="submit" value="Search">
    <a href="index.php">Show All</a>
    </form>

    <br><br>


    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>No.</th>
            <th>Student Name</th>
            <th>Matric Number</th>
            <th>Subject</th>
            <th>Test 1 (20%)</th>
            <th>Test 2 (30%)</th>
            <th>Final (50%)</th>
            <th>Total</th>
            <th>Grade</th>
            <th>Actions </th>
        </tr>

        <?php
        $count = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $count++ . "</td>";
            echo "<td>" . $row['student_name'] . "</td>";
            echo "<td>" . $row['matric_number'] . "</td>";
            echo "<td>" . $row['subject_name'] . "<br> (" . $row['subject_code'] . ")" . "</td>";
            echo "<td>" . $row['test_1'] . "</td>";
            echo "<td>" . $row['test_2'] . "</td>";
            echo "<td>" . $row['final_examination'] . "</td>";
            echo "<td>" . $row['total_marks'] . "</td>";
            echo "<td>" . $row['final_grade'] . "</td>";
            echo "<td>";
            echo "<button><a href= 'edit.php?id=" . $row['id'] . "'>Edit</a></button>";
            echo "<form method='post' action='delete.php' onsubmit='return confirm(\"Are you sure you want to delete this record?\");'>";
            echo "<input type='hidden' name='Delete' value='" . $row['id'] . "'>";
            echo "<input type='submit' value='Delete'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }

        ?>



    </table>

    <?php

    if ($result->num_rows == 0) {
        echo "<p>No records found.</p>";
    }
    include 'footer.php';
    ?>
</body>