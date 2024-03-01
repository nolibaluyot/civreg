<?php
include('includes/header.php'); 
include('includes/navbar.php');
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success"><i class="bi bi-check-circle-fill"></i> Approved Civil Requests</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="approvedDataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Registration Date</th>
                            <th>Registrar Name</th>
                            <th>Type of Request</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                         require('db.php');
                         
                        $query = "SELECT * FROM approved_requests";
                        $query_run = mysqli_query($conn, $query);

                        if ($query) {
                            $row_number = 1;
                            while ($row = mysqli_fetch_assoc($query_run)) {
                                ?>
                                <tr>
                                    <td><?php echo $row_number; ?></td>
                                    <td><?php echo $row['registration_date']; ?></td>
                                    <td><?php echo $row['registrar_name']; ?></td>
                                    <td><?php echo $row['type_request']; ?></td>
                                    <td><?php echo $row['status']; ?></td>
                                </tr>
                                <?php
                                $row_number++;
                            }
                        } else {
                            echo "No Approved Requests Found";
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
