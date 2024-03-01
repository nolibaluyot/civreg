<?php
include('includes/header.php'); 
include('includes/navbar.php');
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-people-fill"></i> Registered Users</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Registration Date</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require('db.php');

                        // Check if the connection is successful
                        if (!$conn) {
                            die("Connection failed: " . mysqli_connect_error());
                        }

                        $query = "SELECT * FROM users ORDER BY u_ln"; // Sort by last name
                        $query_run = mysqli_query($conn, $query);

                        // Check if the query was executed successfully
                        if (!$query_run) {
                            die("Query failed: " . mysqli_error($con));
                        }

                        $row_number = 1;

                        if (mysqli_num_rows($query_run) > 0) {
                            while ($row = mysqli_fetch_assoc($query_run)) {
                                ?>
                                <tr>
                                    <td><?php echo $row_number; ?></td>
                                    <td><?php echo $row['create_datetime']; ?></td>
                                    <td><?php echo $row['u_ln']; ?></td>
                                    <td><?php echo $row['u_fn']; ?></td>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['contact_no']; ?></td>
                                    <td>
                                        <?php echo $row['house_no'] . ', ' . $row['street_brgy'] . ', ' . $row['city_municipality'] . ', ' . $row['province']; ?>
                                    </td>
                                </tr>
                                <?php
                                $row_number++;
                            }
                        } else {
                            echo "No Record Found";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; MCRO 2023</span>
                    </div>
                </div>
            </footer>

<?php
include('includes/script.php');
include('includes/footer.php');
?>
