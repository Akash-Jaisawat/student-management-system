<?php
include 'header.php';
include 'config.php';

$firstname = "";
$lastname = "";
$gender = "";
$dob = "";
$address = "";
$city = "";
$pincode = "";
$state = "";
$country_code = "";
$phone = "";
$course = "";
$email = "";
$password = "";
$cpassword = "";
$image = "";
$file = "";
$hobbies = [];
$otherhobbies = "";
$allhobbies = "";
$xboard = "";
$xperc = "";
$xyop = "";
$xiiboard = "";
$xiiperc = "";
$xiiyop = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['submit']) && $_POST['submit']) {
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$gender = $_POST['gender'];
		$dob = $_POST['date'];
		$address = $_POST['address'];
		$city = $_POST['city'];
		$pincode = $_POST['pincode'];
		$state = $_POST['state'];
		$country_code = $_POST['country_code'];
		$phone = $_POST['phone'];
		$course = isset($_POST['course']) ? $_POST['course'] : ''; // Check for course only if it exists
		$email = $_POST['email'];
		$password = $_POST['password'];
		$cpassword = $_POST['cpassword'];
		$xboard = $_POST['xboard'];
		$xperc = $_POST['xperc'];
		$xyop = $_POST['xyop'];
		$xiiboard = $_POST['xiiboard'];
		$xiiperc = $_POST['xiiperc'];
		$xiiyop = $_POST['xiiyop'];
		$required_fields = ['firstname', 'lastname', 'gender', 'date', 'address', 'city', 'pincode', 'state', 'country_code', 'phone', 'email', 'password', 'cpassword', 'course' , 'image']; // Add more fields if needed
		foreach ($required_fields as $field) {
			if (!isset($_POST[$field]) || empty($_POST[$field])) {
				$errors[$field] = "$field is required";
			}
		}
		if ($password !=  $cpassword) {
			$errors['cpassword'] = "Passwords do not match";
		}
		if (!isset($_POST['otherhobbies']) || empty($_POST['otherhobbies'])) {
			$otherhobbies = '';
		} else {
			$otherhobbies = htmlspecialchars(trim(ctype_lower($_POST['otherhobbies'])));
		}
		if (isset($_FILES['files'])) {
			$countfiles = count($_FILES['files']['name']);
			for ($i = 0 ; $i < $countfiles; $i++) {
				$filename = $_FILES['files']['name'][$i];
                $tempname = $_FILES['files']['tmp_name'][$i];
                $folder = 'files/'. $filename;
                if (move_uploaded_file($tempname, $folder)) {
                    echo "file uploaded successfully";
                } else {
                    echo "file not uploaded";
                }
            }
		}
		if (count($errors) == 0) {
			$image = $_FILES['image']['name'];
			$folder = 'images/' . $image;
			if (move_uploaded_file($tempname, $folder)) {
				echo "file uploaded successfully";
			} else {
				echo "file not uploaded";
			}
			$e7 = mysqli_real_escape_string($link, $pincode);
			$e1 = mysqli_real_escape_string($link, $firstname);
			$e2 = mysqli_real_escape_string($link, $lastname);
			$e3 = mysqli_real_escape_string($link, $gender);
			$e4 = mysqli_real_escape_string($link, $dob);
			$e5 = mysqli_real_escape_string($link, $address);
			$e6 = mysqli_real_escape_string($link, $city);
			$e8 = mysqli_real_escape_string($link, $state);
			$escaped_country_code = mysqli_real_escape_string($link, $country_code);
			$sql_get_country_id = "SELECT country_id FROM countries WHERE code = '$escaped_country_code'";
			$result = mysqli_query($link, $sql_get_country_id);
			$row = mysqli_fetch_assoc($result);
			$country_id = $row['country_id'];
			$e9 = mysqli_real_escape_string($link, $country_id);
			$e10 = mysqli_real_escape_string($link, $phone);
			$e11 = mysqli_real_escape_string($link, $course);
			$e12 = mysqli_real_escape_string($link, $email);
			$e13 = mysqli_real_escape_string($link, $password);
			$e14 = mysqli_real_escape_string($link, $image);
			$e15 = mysqli_real_escape_string($link, $file);
			$e16 = mysqli_real_escape_string($link, $allhobbies);
			$e17 = mysqli_real_escape_string($link, $xboard);
			$e18 = mysqli_real_escape_string($link, $xperc);
			$e19 = mysqli_real_escape_string($link, $xyop);
			$e20 = mysqli_real_escape_string($link, $xiiboard);
			$e21 = mysqli_real_escape_string($link, $xiiperc);
			$e22 = mysqli_real_escape_string($link, $xiiyop);
			$sql_insert_data = "INSERT INTO students (firstname, lastname, gender, dob, address, city, pincode, state, country_id, phone, course, email, password, image, file, hobbies, xboard, xperc, xyop, xiiboard, xiiperc, xiiyop) VALUES ('$e1', '$e2', '$e3', '$e4', '$e5', '$e6', '$e7', '$e8', '$e9', '$e10' , '$e11' , '$e12' , '$e13' , '$e14' , '$e15' , '$e16' , '$e17' , '$e18' , '$e19' , '$e20' , '$e21' , '$e22')";
			$result = mysqli_query($link, $sql_insert_data);
			if ($result) {
				echo "success";
				header("Location: login.php");
			} else {
				echo "Error inserting data: " . mysqli_error($link);
			}
		} else {
			print_r($errors);
		}
	}
}
?>

<div class="container">
	<div class="ManagementSystem">
		<h1 class="form-title">Student Management System</h1>
		<form id="sample" method="post" enctype="multipart/form-data" action="index.php">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-right">
					<div class="profile-pic">
						<div class="form-group">
							<label>Upload Image</label>
							<img id='img-upload' src="images/user.png" />
							<div class="input-group">
								<span class="input-group-btn">
									<span class="btn btn-default btn-file">
										Browse… <input name="image" type="file" id="imgInp">
									</span>
								</span>
								<input name="image" type="text" class="form-control" readonly>
							</div>

							<div class="form-group">
								<label>Upload Documents</label>
								<div class="box">
									<input type="file" name="files[]" id="file-1" class="inputfile inputfile-1" data-multiple-caption="{count} files selected" multiple/>
									<label for="file-1"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
											<path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z" />
										</svg> <span>Choose a file&hellip;</span></label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>First Name <span class="color-danger">*</span></label>
								<input type="text" class="form-control" id="first_name" name="firstname" data-rule-firstname="true" value="<?php echo $firstname ?>" />
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>Last Name <span class="color-danger">*</span></label>
								<input type="text" class="form-control" id="last_name" name="lastname" data-rule-lastname="true" value="<?php echo $lastname ?>" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>Date of Birth <span class="color-danger">*</span></label>
								<input placeholder="MM/DD/YYYY" type="text" class="form-control" id="date1" name="date" value="<?php echo $dob ?>" data-rule-requiredmmddyy="true" />
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>Email <span class="color-danger">*</span></label>
								<input type="text" id="email" name="email" class="form-control" value="<?php echo $email ?>" data-rule-email="true" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>Password <span class="color-danger">*</span></label>
								<input type="password" class="form-control" id="password" name="password" value="<?php $password ?>" data-rule-passwd="true" />
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>Confirm Password <span class="color-danger">*</span></label>
								<input type="password" name="cpassword" class="form-control" value="" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>Mobile Number <span class="color-danger">*</span></label>
								<input type="text" id="contact_no" name="phone" value="<?php echo $phone ?>" class="form-control" />
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>Gender <span class="color-danger">*</span></label>
								<div class="gender_controls">
									<label class="radio-inline" for="gender-0">
										<input name="gender" id="gender-0" value="Male" type="radio" checked="checked">
										Male
									</label>
									<label class="radio-inline" for="gender-1">
										<input name="gender" id="gender-1" value="Female" type="radio">
										Female
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label>Address <span class="color-danger">*</span></label>
								<textarea class="form-control" id="address_line1" name="address" value="<?php echo $address ?>"><?php echo $address ?></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>City <span class="color-danger">*</span></label>
								<input type="text" name="city" id="city" class="form-control" value="<?php echo $city ?>" />
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>Zip Code<span class="color-danger">*</span></label>
								<input type="text" name="pincode" id="pincode" class="form-control" value="<?php echo $pincode ?>" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>State <span class="color-danger">*</span></label>
								<input type="text" name="state" id="state" class="form-control" value="<?php echo $state ?>" />
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>Country <span class="color-danger">*</span></label>
								<select name="country_code">
									<?php
									$sql_get_countries = "SELECT code, name FROM countries ORDER BY name ASC";
									$result_get_countries = mysqli_query($link, $sql_get_countries);
									if ($result_get_countries) {
										echo "<option value=''>(please select a country)</option>";
										while ($row = mysqli_fetch_assoc($result_get_countries)) {
											echo "<option value='" . $row['code'] . "' >" . $row['name'] . "</option>";
										}
									} else {
										echo "Error fetching countries: " . mysqli_error($link);
									}

									?>
								</select>

							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label>Hobbies</label>
								<div class="controls">
									<label class="checkbox-inline"><input type="checkbox" name="drawing" value="drawing">Drawing</label>
									<label class="checkbox-inline"><input type="checkbox" name="singing" value="singing">Singing</label>
									<label class="checkbox-inline"><input type="checkbox" name="dancing" value="dancing">Dancing</label>
									<label class="checkbox-inline"><input type="checkbox" name="sketching" value="skecthing">Sketching</label>
									<label class="checkbox-inline">Other :<input type="text" name="otherhobbies" class="form-control" value="<?php echo $otherhobbies ?>"></label>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label>Qualification</label>
								<div class="table-responsive">
									<table>
										<thead>
											<tr>
												<th>Sr. No.</th>
												<th>Examination</th>
												<th>Board</th>
												<th>Percentage</th>
												<th>Year of Passing</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1</td>
												<td>Class X</td>
												<td><input type="text" class="form-control" name="xboard" id="X-board" value="<?php echo $xyop ?>"></td>
												<td><input type="text" class="form-control" name="xperc" id="X-perc" value="<?php echo $xyop ?>"></td>
												<td><input type="text" class="form-control" name="xyop" id="X-yop" value="<?php echo $xyop ?>"></td>
											</tr>
											<tr>
												<td>2</td>
												<td>Class XII</td>
												<td><input type="text" class="form-control" name="xiiboard" id="xiiboard" value="<?php echo $xiiboard ?>"></td>
												<td><input type="text" class="form-control" name="xiiperc" id="XII-perc" value="<?php echo $xiiperc ?>"></td>
												<td><input type="text" class="form-control" name="xiiyop" id="XII-yop" value="<?php echo $xiiyop ?>"></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label>Courses Applied for</label>
								<div class="controls">
									<label class="radio-inline" for="gender-0">
										<input name="course" id="course-0" value="bca" type="radio">
										BCA
									</label>
									<label class="radio-inline" for="gender-1">
										<input name="course" id="course-1" value="bcom" type="radio">
										B.COM
									</label>
									<label class="radio-inline" for="gender-1">
										<input name="course" id="course-2" value="bsc" type="radio">
										B.Sc
									</label>
									<label class="radio-inline" for="gender-1">
										<input name="course" id="course-3" value="ba" type="radio">
										B.A
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="action-button">
								<input name="submit" type="submit" class="btn btn-green submit-form" value="Submit" />
								<input type="reset" class="btn btn-orange" value="Reset" />
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<?php include 'footer.php'; ?>