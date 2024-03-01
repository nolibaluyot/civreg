<?php
include('includes/header.php'); 
include('includes/navbar.php'); 
?>


<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Botolan Civil Registry Online Portal Admin Dashboard</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
        class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
  </div>

  <!-- Content Row -->
  <div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Registered User</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">

                <?php
                    require('db.php');

                       $query = "SELECT id_user FROM users ORDER BY id_user";
                       $query_run = mysqli_query($conn, $query);
                       $row = mysqli_num_rows($query_run);

                       echo '<h4> '.$row.'</h4>'
                ?>

              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-solid fa-users fa-2x text-gray-400"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Pending Request</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                   <?php
                    require('db.php');

                       $query = "SELECT type_request FROM reqtracking_tbl ORDER BY type_request";
                       $query_run = mysqli_query($conn, $query);
                       $row = mysqli_num_rows($query_run);

                       echo '<h4> '.$row.'</h4>'
                ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="fa fa-clock fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Approve Request</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                 <?php
                    require('db.php');

                       $query = "SELECT type_request FROM approved_requests ORDER BY type_request";
                       $query_run = mysqli_query($conn, $query);
                       $row = mysqli_num_rows($query_run);

                       echo '<h4> '.$row.'</h4>'
                ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-check fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Reject Request</div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                     <?php
                    require('db.php');

                       $query = "SELECT type_request FROM rejected_requests ORDER BY type_request";
                       $query_run = mysqli_query($conn, $query);
                       $row = mysqli_num_rows($query_run);

                       echo '<h4> '.$row.'</h4>'
                ?>
                  </div>
                </div>
                <div class="col">
                  <div class="progress progress-sm mr-2">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 50%" aria-valuenow="50"
                      aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fa fa-ban fa-2x text-gray-300"></i>
            </div>
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

    <!-- Pending Requests Card Example -->

  <!-- Content Row -->


  <?php
include('includes/script.php');
include('includes/footer.php');
?>