<?php
require_once "auth.php";
require_login();
include "db.php";

$student_name = '';
$matric_number = '';
$subject_name = '';
$subject_code = '';
$test1 = '';
$test2 = '';
$final = '';

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

    $sql = $conn->prepare("INSERT INTO results 
    (student_name, matric_number, subject_name, subject_code,
    test_1, test_2, final_examination, total_marks,final_grade) VALUES
    ('$student_name','$matric_number','$subject_name',
    '$subject_code',$test1,$test2,$final,$total,'$grade')");

    if ($sql->execute()) {
        header("Location: index.php");
        exit;
    }
}
?>


<?php
include "header.php";
include "form.php";
include "footer.php";
?>
