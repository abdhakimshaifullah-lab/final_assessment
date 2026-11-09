<?php
require_once "auth.php";
require_login();
include "db.php";

$id = $_GET['id'];

$sql = $conn->prepare("SELECT * FROM results WHERE id = $id");
$sql->execute();
$row = $sql->get_result()->fetch_assoc();
$student_name = $row['student_name'];
$matric_number = $row['matric_number'];
$subject_name = $row['subject_name'];
$subject_code = $row['subject_code'];
$test1 = $row['test_1'];
$test2 = $row['test_2'];
$final = $row['final_examination']; 

if (isset($_POST['submit'])) {
    $student_name = $_POST['student_name'];
    $matric_number = $_POST['matric_number'];
    $subject_name = $_POST['subject_name'];
    $subject_code = $_POST['subject_code'];
    $test1 = $_POST['test1'];
    $test2 = $_POST['test2'];
    $final = $_POST['final'];

    $total = $test1 + $test2 + $final;

    if ($total >= 80)
        $grade = 'A';
    else if ($total >= 65)
        $grade = 'B';
    else if ($total >= 50)
        $grade = 'C';
    else if ($total >= 40)
        $grade = 'D';
    else
        $grade = 'E';

    $sql = $conn->prepare("UPDATE results SET 
    student_name='$student_name', matric_number='$matric_number', subject_name='$subject_name',
    subject_code='$subject_code', test_1=$test1, test_2=$test2, final_examination=$final,
    total_marks=$total, final_grade='$grade' WHERE id=$id");

    if ($sql->execute()) {
        header("Location: index.php");
        exit;
    }
}
?>

<?php include "header.php"; ?>
<h2>Edit Student Result</h2>

<?php
include "form.php";
include "footer.php";
?>