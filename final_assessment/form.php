<style>
    label {
        display: inline-block;
        width: 200px;
    }
</style>

<form method="post">
    <label for="">Student Name: </label>
    <input type="text" name="student_name" value="<?php echo $student_name; ?>"><br><br>
    <label for="">Matric Number</label>
    <input type="text" name="matric_number" value="<?php echo $matric_number; ?>"><br><br>
    <label for="">Subject Name</label>
    <input type="text" name="subject_name" value="<?php echo $subject_name; ?>"><br><br>
    <label for="">Subject Code</label>
    <input type="text" name="subject_code" value="<?php echo $subject_code; ?>"><br><br>
    <label for="">Test 1 (20%)</label>
    <input type="text" name="test1" value="<?php echo $test1; ?>"> out of 20<br><br>
    <label for="">Test 2 (30%)</label>
    <input type="text" name="test2" value="<?php echo $test2; ?>"> out of 30<br><br>
    <label for="">Final Examination (50%)</label>
    <input type="text" name="final" value="<?php echo $final; ?>"> out of 50<br><br>
    <input type="submit" name="submit" value="Submit">
</form>