<?php
include 'header2.php';
include 'config.php';

if (!isset($_SESSION['id']) || empty($_SESSION['role'])) {
	header("Location: login.php");
	exit("please login first");
}

$loggedInUser = $_SESSION['id'];
$loggedInAsadmin = $_SESSION['role'];
?>
<div class="container">
	<div class="ManagementSystem">
		<h1 class="form-title">Student Detail</h1>
		<div class="option-buttons">
			<div class="row">
				<form method="post" action="import_export_csv.php" enctype="multipart/form-data">
					<div class="col-lg-8 col-md-8 col-sm-8">
						<label class="btn btn-orange"><i class="fa fa-plus-circle"></i> Add Student <input type="file" name="add-file" id="add-file" class="inputfile" /></label>
						<label class="btn btn-green">
							<i class="fa fa-plus-circle"></i> Import CSV
							<input type="file" name="import-file" id="import-file" class="inputfile" />
							<input type="submit" name="import" value="Import" />
						</label> <label class="btn btn-orange"><i class="fa fa-plus-circle"></i> Export CSV <input type="submit" name="export-file" id="export-file" class="inputfile" /></label>
					</div>
				</form>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<form method="GET">
						<div class="input-group">
							<input name="searchInput" type="text" class="form-control" placeholder="Search for...">
							<span class="input-group-btn">
								<input name="searchButton" value="search" class="btn btn-search btn-green" type="submit" />
							</span>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="table-responsive">
			<table class="user-detail">
				<thead>
					<tr>
						<th><input type="checkbox" name="all-selected" value="" /></th>
						<th>Student Id</th>
						<th>Student</th>
						<th>Course</th>
						<th>Email id</th>
						<th>Qualification</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$records_per_page = 5;
					$sql = "SELECT * FROM students";
					$total_records = mysqli_num_rows(mysqli_query($link, $sql));
					$total_pages = ceil($total_records / $records_per_page);
					$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
					$start_from = ($current_page - 1) * $records_per_page;
					$sql_with_limit = "SELECT * FROM students where not email = 'admin' LIMIT $start_from, $records_per_page ";
					$pagination_result = mysqli_query($link, $sql_with_limit);
					$searchTerm = isset($_GET['searchButton']) ? $_GET['searchInput'] : "";

					if (!empty($searchTerm)) {
						$sql_search = "SELECT * FROM students WHERE  firstname LIKE '%$searchTerm%' OR email LIKE '%$searchTerm%'";
						$search_result = mysqli_query($link, $sql_search);
						if (!$search_result) {
							echo "Error fetching data: " . mysqli_error($link);
						} else {
							while ($searchedrow = mysqli_fetch_assoc($search_result)) {
								displayStudentRow($searchedrow, $loggedInUser, $loggedInAsadmin);
							}
						}
					} else {
						if (!$pagination_result) {
							echo "Error fetching data: " . mysqli_error($link);
						} else {
							while ($row = mysqli_fetch_assoc($pagination_result)) {
								displayStudentRow($row, $loggedInUser, $loggedInAsadmin);
							}
						}
					}
					?>
				</tbody>
			</table>
		</div>
		<div class="pager-navigation">
			<ul class="pagination">
				<?php
				// Pagination links logic
				$prev_page = $current_page - 1;
				$next_page = $current_page + 1;

				if ($current_page > 1) {
					echo "<li><a href=\"?page=$prev_page\">&laquo;</a></li>";
				}

				for ($i = 1; $i <= $total_pages; $i++) {
					$active_class = ($i == $current_page) ? "active" : "";
					echo "<li class=\"$active_class\"><a href=\"?page=$i\">$i</a></li>";
				}

				if ($current_page < $total_pages) {
					echo "<li><a href=\"?page=$next_page\">&raquo;</a></li>";
				}
				?>
			</ul>
		</div>
	</div>
</div>
<?php include 'footer.php'; ?>

<?php
function displayStudentRow($row, $loggedInUser, $loggedInAsadmin)
{
	$name = $row['firstname'];
	$email = $row['email'];
	$image = $row['image'];
	$id = $row['id'];
	$address = $row['address'];
	$course = $row['course'];
	$gender = $row['gender'];
	$dob = $row['dob'];
	$file = $row['file'];
	echo "<tr>
        <td><input type=\"checkbox\" name=\"selected1\" value=\"\"/></td>
        <td>150$id</td>
        <td>
            <div class=\"image\">
                <img src= \"images/$image\" download class=\"img-responsive\"/> 
            </div>
            <h4 class=\"user-name\">$name</h4>
            <h5 class=\"user-gender\">$gender</h5>
            <h5 class=\"user-dob\">$dob</h5>
            <div class=\"user-address\">
                <p>$address</p>
            </div>
        </td>
        <td>$course</td>
        <td>$email</td>
        <td>XII passout</td>";

	if ($loggedInAsadmin === 'admin' || $loggedInUser === $id) {
		echo "<td><a href=\"files/$file\" download class=\"btn btn-green\"><i class=\"fa fa-download\"></i> Document</a>
        <div class=\"user-actions\">
            <a href=\"\" class=\"btn btn-orange\" title=\"View\"><i class=\"fa fa-eye\"></i> </a>
            <a href=\"\" class=\"btn btn-orange\" title=\"Delete\"><i class=\"fa fa-trash\"></i> </a>
            <a href=\"\" class=\"btn btn-orange\" title=\"Edit\"><i class=\"fa fa-pencil\"></i> </a>
        </div>
    </td>
    </tr>";
	} else {
		echo "<td><div class=\"user-actions\">
            <a href=\"\" class=\"btn btn-orange\" title=\"View\"><i class=\"fa fa-eye\"></i> </a>
            <a href=\"\" class=\"btn btn-orange\" title=\"Delete\"><i class=\"fa fa-trash\"></i> </a>
            <a href=\"\" class=\"btn btn-orange\" title=\"Edit\"><i class=\"fa fa-pencil\"></i> </a>
        </div>
    </td>
    </tr>";
	}
}
?>