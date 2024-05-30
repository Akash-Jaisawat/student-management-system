<?php
include "config.php";

function escape($link, $data) {
    return mysqli_real_escape_string($link, $data);
}

// Export CSV
if (isset($_POST['export-file'])) {
    $sql = "SELECT * FROM students WHERE email != 'admin'";
    $result = mysqli_query($link, $sql);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=students.csv');
    $output = fopen("php://output", "w");
    fputcsv($output, array('id', 'pincode', 'firstname', 'lastname', 'gender', 'dob', 'address', 'city', 'state', 'country_id', 'phone', 'course', 'email', 'password', 'image', 'file', 'hobbies', 'xboard', 'xperc', 'xyop', 'xiiboard', 'xiiperc', 'xiiyop', 'role'));
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}

// Import CSV
if (isset($_POST['import'])) {
    $filename = $_FILES['import-file']['tmp_name'];
    if ($_FILES["import-file"]["size"] > 0) {
        $file = fopen($filename, "r");
        fgetcsv($file); // Skip header row

        $success_count = 0;
        $fail_count = 0;
        $errors = [];

        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
            $student_id = escape($link, $getData[0]);

            // Check if the student already exists
            $checkQuery = "SELECT * FROM students WHERE id = '$student_id'";
            $checkResult = mysqli_query($link, $checkQuery);

            if (mysqli_num_rows($checkResult) > 0) {
                // Update existing student
                $sqlUpdate = "UPDATE students SET 
                    pincode = '".escape($link, $getData[1])."', 
                    firstname = '".escape($link, $getData[2])."', 
                    lastname = '".escape($link, $getData[3])."', 
                    gender = '".escape($link, $getData[4])."', 
                    dob = '".escape($link, $getData[5])."', 
                    address = '".escape($link, $getData[6])."', 
                    city = '".escape($link, $getData[7])."', 
                    state = '".escape($link, $getData[8])."', 
                    country_id = '".escape($link, $getData[9])."', 
                    phone = '".escape($link, $getData[10])."', 
                    course = '".escape($link, $getData[11])."', 
                    email = '".escape($link, $getData[12])."', 
                    password = '".escape($link, $getData[13])."', 
                    image = '".escape($link, $getData[14])."', 
                    file = '".escape($link, $getData[15])."', 
                    hobbies = '".escape($link, $getData[16])."', 
                    xboard = '".escape($link, $getData[17])."', 
                    xperc = '".escape($link, $getData[18])."', 
                    xyop = '".escape($link, $getData[19])."', 
                    xiiboard = '".escape($link, $getData[20])."', 
                    xiiperc = '".escape($link, $getData[21])."', 
                    xiiyop = '".escape($link, $getData[22])."', 
                    role = '".escape($link, $getData[23])."'
                    WHERE id = '$student_id'";
                $result = mysqli_query($link, $sqlUpdate);
            } else {
                // Insert new student
                $sqlInsert = "INSERT INTO students (pincode, firstname, lastname, gender, dob, address, city, state, country_id, phone, course, email, password, image, file, hobbies, xboard, xperc, xyop, xiiboard, xiiperc, xiiyop, role)
                              VALUES (
                                '".escape($link, $getData[1])."', 
                                '".escape($link, $getData[2])."', 
                                '".escape($link, $getData[3])."', 
                                '".escape($link, $getData[4])."', 
                                '".escape($link, $getData[5])."', 
                                '".escape($link, $getData[6])."', 
                                '".escape($link, $getData[7])."', 
                                '".escape($link, $getData[8])."', 
                                '".escape($link, $getData[9])."', 
                                '".escape($link, $getData[10])."', 
                                '".escape($link, $getData[11])."', 
                                '".escape($link, $getData[12])."', 
                                '".escape($link, $getData[13])."', 
                                '".escape($link, $getData[14])."', 
                                '".escape($link, $getData[15])."', 
                                '".escape($link, $getData[16])."', 
                                '".escape($link, $getData[17])."', 
                                '".escape($link, $getData[18])."', 
                                '".escape($link, $getData[19])."', 
                                '".escape($link, $getData[20])."', 
                                '".escape($link, $getData[21])."', 
                                '".escape($link, $getData[22])."', 
                                '".escape($link, $getData[23])."'
                              )";
                $result = mysqli_query($link, $sqlInsert);
            }

            if ($result) {
                $success_count++;
            } else {
                $fail_count++;
                $errors[] = mysqli_error($link);
            }
        }
        fclose($file);

        // Redirect to summary page
        header("Location: save-data.php?success=$success_count&fail=$fail_count");
        exit();
    } else {
        echo "Invalid File: Please Upload a CSV File.";
    }
}
?>
