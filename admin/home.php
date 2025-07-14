<?php include('db_connect.php'); ?>

<div class="row">
  <div class="col">
    <div class="card">
      <div class="card-body">
        <h1>Welcome Admin!</h1>
        <p class="m-0 p-0">This system is designed to modernize and improve the efficiency in taking comprehensive examination.
        </p>
        <br>
        <a href="./index.php?page=exam_list" class="btn btn-flat btn-success">Continue</a>
      </div>
    </div>
  </div>
</div>


<div class="row">
  <div class="col-12 col-sm-4">
    <div class="info-box">
      <span class="info-box-icon bg-warning elevation-1"><i class="fa fa-users"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Total Student</span>
        <span class="info-box-number">
        <?php echo $conn->query("SELECT * from student_list")->num_rows; ?>

        </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-4">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-success elevation-1"><i class="fa fa-check"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Total Passed</span>
        <span class="info-box-number">678</span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->

  <!-- fix for small devices only -->
  <div class="clearfix hidden-md-up"></div>

  <div class="col-12 col-sm-4">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-danger elevation-1"><i class="fa fa-times"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Total Failed</span>
        <span class="info-box-number">760</span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>

</div>
