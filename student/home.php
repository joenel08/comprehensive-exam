<?php include('db_connect.php'); ?>

<?php
$academic_id = $_SESSION['academic']['id'];
$document_status = '';

if ($_SESSION['login_table'] == 'student_list') {
  $student_id = $_SESSION['login_id'];

  // Default values if no row is found
  $tor = 0;
  $birth_cert = 0;
  $grades = 0;

  $qry = $conn->query("SELECT * FROM student_documents WHERE student_id = '$student_id'");
  if ($qry && $qry->num_rows > 0) {
    $row = $qry->fetch_assoc();
    $tor = $row['tor'];
    $birth_cert = $row['birth_cert'];
    $grades = $row['grades'];
    $notes = $row['notes'];
    $marriage_cert = $row['marriage_cert'];
    // Prepare display
    $document_status = "<h5>Documents Submission Status</h5>";
    $document_status .= "<ul>";
    $document_status .= "<li>TOR: <span class='" . ($tor ? "text-success" : "text-danger") . "'>" . ($tor ? "Submitted ✅" : "Not Submitted ❌") . "</span></li>";
    $document_status .= "<li>Birth Certificate: <span class='" . ($birth_cert ? "text-success" : "text-danger") . "'>" . ($birth_cert ? "Submitted ✅" : "Not Submitted ❌") . "</span></li>";
    $document_status .= "<li>Grades: <span class='" . ($grades ? "text-success" : "text-danger") . "'>" . ($grades ? "Submitted ✅" : "Not Submitted ❌") . "</span></li>";

    if ($_SESSION['login_marital_status'] != 'Single') {
      $document_status .= "<li>Marriage Certificate: <span class='" . ($marriage_cert ? "text-success" : "text-danger") . "'>" . ($marriage_cert ? "Submitted ✅" : "Not Submitted ❌") . "</span></li>";
    }

    $document_status .= "</ul>";

    $document_status .= "<p>Additional Notes: <span class='badge badge-warning'>" . $notes . "</span></p>";

  } else {
    $document_status = "<h5>Documents Submission Status</h5>";
    $document_status .= "<h5>Please go to registrar's office to submit the following:</h5>";
    $document_status .= "<ul>";
    $document_status .= "<li>Photocopy of TOR / Original Copy if not SPUP</li>";
    $document_status .= "<li>Authenticated Birth Certificate</li>";
    $document_status .= "<li>Grades</li>";
    $document_status .= "</ul>";

  }




  // Payment check in business_ao table
  $payment_qry = $conn->query("SELECT * FROM paymentstatus WHERE student_id = '$student_id'");

  if ($payment_qry && $payment_qry->num_rows > 0) {
    $payment = $payment_qry->fetch_assoc();

    if (isset($payment['payment_status']) && strtolower($payment['payment_status']) == 'paid') {
      $payment_status = "<h5>Payment Status</h5><p><span class='text-success'>You have no pending payments ✅</span></p>";
    } else if (isset($payment['payment_status']) && strtolower($payment['payment_status']) == 'unpaid') {
      $payment_status = "<h5>Payment Status</h5><p><span class='text-danger'>You have a Pending Payment ❌</span></p><p>Additional Notes: " . $payment['payment_notes'] . "</p><p class='text-muted'>Please go to the BAO to pay your balance. Thank you!</span>";
    } else {
      $payment_status = " <h5>Payment Status</h5><p><span class='text-danger'>You already request to checked your balance. Wait for the status of your request. Thank you!</span></p> ";
    }
  } else {
    $payment_status = "
    <h5>Payment Status</h5><p><span class='text-danger'>No request record found ❌</span></p>
      <a class='btn btn-flat btn-success request_check' href='javascript:void(0)'
data-id='" . $student_id . "'>Request Balance Checking</a>";
  }
}
?>


<div class="row">
  <div class="col">
    <div class="card">
      <div class="card-body">
        <h1>Welcome <?php echo ucwords($_SESSION['login_firstname']) . ' ' . $_SESSION['login_lastname'] ?>!</h1>
        <p class="m-0 p-0">This system is designed to modernize and improve the efficiency in taking comprehensive
          examination.</p>
        <br>

      </div>
    </div>
  </div>
</div>
<?php
// Initialize to avoid undefined variable warnings
$verdict = '';
$approval_notes = '';
$date_of_approval = '';

$verdict_qry = $conn->query("SELECT * FROM dean_approval WHERE student_id = '$student_id'");
if ($verdict_qry && $verdict_qry->num_rows > 0) {
  $vrow = $verdict_qry->fetch_assoc();
  $verdict = $vrow['verdict'];
  $approval_notes = $vrow['approval_notes'];
  $date_of_approval = date("F j, Y g:i a", strtotime($vrow['date_of_approval']));


}

?>


<div class="alert alert-success alert-dismissible fade show" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  <h4 class="alert-heading font-weight-bold">Dean's Approval</h4>
  <p class="m-0">Final Verdict:
    <span class="font-weight-bold"><?php echo !empty($verdict) ? $verdict : 'Pending'; ?></span>
  </p>
  <p class="m-0">Additional Notes:
    <span class="font-weight-bold"><?php echo !empty($approval_notes) ? $approval_notes : 'N/A'; ?></span>
  </p>
  <p class="m-0">Date of Approval:
    <span
      class="font-weight-bold"><?php echo !empty($date_of_approval) ? $date_of_approval : 'Not yet approved'; ?></span>
  </p>

  <hr>
  <p class="mb-0">For any concerns or queries, please go to the Dean's Office for guidance. You may apply for the
    comprehensive exam now. Click the link below!</p>
  <a href="./index.php?page=comprehensive_examination_form" class="">Comprehensive Examination Form</a>
</div>


<div class="row">
  <div class="col">
    <div class="card">
      <div class="card-body">

        <?php echo $document_status; ?>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col">
    <div class="card">
      <div class="card-body">

        <?php echo $payment_status; ?>
      </div>
    </div>
  </div>
</div>


<a href="./index.php?page=download_form&student_id=<?php echo $_SESSION['login_id'] ?>" class="btn btn-success btn-flat">Download Form</a>
<script>
  $(document).ready(function () {

    $('.request_check').click(function () {
      _conf("Are you sure you want to request for balance checking?", "request_check", [$(this).attr('data-id')])
    })

  })
  function request_check($id) {
    start_load()
    $.ajax({
      url: 'ajax.php?action=request_check',
      method: 'POST',
      data: { id: $id },
      success: function (resp) {
        let response = {};
        try {
          response = JSON.parse(resp);
        } catch (e) {
          alert_toast("Invalid server response.", 'error');
          return;
        }

        if (response.status === 'success') {
          alert_toast(response.message, 'success');

          setTimeout(function () {
            location.reload();
          }, 1500);
        } else {
          alert_toast(response.message + ' - ' + (response.error || ''), 'error');
        }
      }

    })
  }
</script>