<?php

$hostname = 'localhost';
$username = 'root';
$password_1 =  '';
$db_name  = 'smhdbf';

$link = mysqli_connect($hostname, $username, $password_1, $db_name);

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    $sql_check_countrytable = "SHOW TABLES LIKE 'countries'";
    $result_check_countrytable = mysqli_query($link, $sql_check_countrytable);
    if (mysqli_num_rows($result_check_countrytable) == 0) {
        $sql_create_countrytable = "CREATE TABLE countries (
        country_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        code VARCHAR(10) NOT NULL
    )";
        $result_create_countrytable = mysqli_query($link, $sql_create_countrytable);
        if ($result_create_countrytable) {
            echo "Table country created successfully";
        } else {
            echo "Error creating country table: " . mysqli_error($link);
        }
        $countries = array(
            array('code' => 'US', 'name' => 'United States'),
            array('code' => 'CA', 'name' => 'Canada'),
            array('code' => 'GB', 'name' => 'United Kingdom'),
            array('code' => 'IN', 'name' => 'India'),
            array('code' => 'AU', 'name' => 'Australia'),
            array('code' => 'NZ', 'name' => 'New Zealand'),
            array('code' => 'DE', 'name' => 'Germany'),
            array('code' => 'FR', 'name' => 'France'),
            array('code' => 'IT', 'name' => 'Italy'),
        );
        
        foreach ($countries as $country) { 
            $sql = "INSERT INTO countries (code, name) VALUES ('". $country['code']. "', '". $country['name']. "')";
            $result = mysqli_query($link, $sql);
            if ($result) {
                echo $country['name']. " added successfully";
            } else {
                echo "Error adding ". $country['name']. ": ". mysqli_error($link);
            }
        }
        
    }
    $sql_check_studentstable = "SHOW TABLES LIKE 'students'";
    $result_check_studentstable = mysqli_query($link, $sql_check_studentstable);
    if (mysqli_num_rows($result_check_studentstable) == 0) {
        $sql_create_studentstable = "CREATE TABLE students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        pincode VARCHAR(6),
        firstname VARCHAR(50) NOT NULL,
        lastname VARCHAR(50) NOT NULL,
        gender VARCHAR(10),
        dob TEXT,
        address TEXT,
        city VARCHAR(50),
        state VARCHAR(50),
        country_id INT ,
        FOREIGN KEY (country_id) REFERENCES countries(country_id),
        phone VARCHAR(20),
        course VARCHAR(100),
        email VARCHAR(100) UNIQUE,
        password VARCHAR(255),
        image VARCHAR(255),
        file VARCHAR(255),
        hobbies TEXT,
        xboard VARCHAR(100),
        xperc FLOAT,
        xyop VARCHAR(10),
        xiiboard VARCHAR(100),
        xiiperc FLOAT,
        xiiyop VARCHAR(10)
    )";
    $sql_update_table = "ALTER TABLE students ADD role VARCHAR(20) DEFAULT 'student'";
    $result_update_table = mysqli_query($link, $sql_update_table);
    
    if (!$result_update_table) {
        echo "Error updating table: ". mysqli_error($link);
    
    } else {
        echo "Table updated successfully";
        
        $result_insert_table = mysqli_query($link, $sql_insert_table);
    }
    
        $result_create_studentstable = mysqli_query($link, $sql_create_studentstable);
        if ($result_create_studentstable) {
            echo "Table students created successfully";
        } else {
            echo "Error creating students table: " . mysqli_error($link);
        }
    }
}
?>

