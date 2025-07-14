<?php include 'db_connect.php'; ?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-header">
			<h4 class="card-title text-success font-weight-bold">Student Status Update</h4>
		</div>
		<div class="card-body">
			<table class="table table-hover table-bordered table-responsive" id="list">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>School ID</th>
						<th>Level</th>
						<th>Program</th>
						<th>Name</th>
						<th>Marital Status</th>
						<th>Email</th>
						<th>Document Status</th>

						<th>Payment Status</th>
						<th>Status</th>
						<th>Date of Approval</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("
						SELECT 
							s.id AS student_id,
							s.school_id,
							s.firstname,
							s.lastname,
							s.middlename,
							s.extname,
							s.level,
							s.program,
							s.marital_status,
							s.email,
							
							payment.payment_status,
							payment.payment_notes,
							payment.date_updated,

							program.program_abbrv,
							program.programFullDesc,

							doc.tor,
							doc.birth_cert,
							doc.grades,
							doc.marriage_cert,
							doc.notes,

							da.verdict,
		da.approval_notes,
		da.date_of_approval
						FROM 
							student_list s
						LEFT JOIN paymentstatus payment ON payment.student_id = s.id
						LEFT JOIN student_documents doc ON doc.student_id = s.id
						LEFT JOIN program program ON program.program_id = s.program

						LEFT JOIN dean_approval da ON da.student_id = s.id
						ORDER BY s.lastname ASC
					");


					$i = 1;
					while ($row = $qry->fetch_assoc()):
						$full_name = ucwords("{$row['lastname']}, {$row['firstname']} {$row['middlename']} {$row['extname']}");
						?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo $row['school_id']; ?></td>
							<td><?php echo ucwords($row['level']); ?></td>
							<td><?php echo $row['program_abbrv'] . ' - ' . $row['programFullDesc']; ?></td>
							<td><?php echo $full_name; ?></td>
							<td><?php echo ucwords($row['marital_status']); ?></td>
							<td><?php echo $row['email']; ?></td>
							<td>
								<?php
								$docs = [
									'TOR' => $row['tor'],
									'Birth Certificate' => $row['birth_cert'],
									'Grades' => $row['grades']
								];
								if (strtolower($row['marital_status']) !== 'single') {
									$docs['Marriage Certificate'] = $row['marriage_cert'];
								}
								foreach ($docs as $label => $value): ?>
									<span class="badge badge-<?php echo $value ? 'success' : 'danger' ?>">
										<?php echo $label . ': ' . ($value ? 'Submitted' : 'Missing'); ?>
									</span><br>
								<?php endforeach; ?>

								<small><?php echo $row['notes']; ?></small>
							</td>

							<td>
								<span
									class="badge badge-<?php echo ($row['payment_status'] === 'Verified') ? 'success' : (($row['payment_status'] === 'Pending') ? 'warning' : 'danger'); ?>">
									<?php echo $row['payment_status'] ?? 'N/A'; ?>
								</span><br>
								<small><?php echo $row['payment_notes']; ?></small><br>
								<small><?php echo !empty($row['date_updated']) ? date("M d, Y h:i A", strtotime($row['date_updated'])) : ''; ?></small>
							</td>


							<td>
								<?php echo '<span class="badge badge-success">' . $row['verdict'] . '</span>' ?? '<span class="badge badge-warning">Pending</span>'; ?>
								<br>
								<small><?php echo $row['approval_notes']; ?></small>

								<?php

								$afqry = $conn->query("SELECT * FROM application_form 
									where academic_id = '{$_SESSION['academic']['id']}' and student_id = '{$row['student_id']}'
								");

								if($afqry->num_rows > 0){ ?>
								<a class="text-center btn btn-primary btn-sm btn-flat" href="./index.php?page=comprehensive_examination_form&student_id=<?php echo $row['student_id']?> ">Comprehensive Examination Form</a>
								<?php }

								?>

							</td>
							<td>
								<?php echo $row['date_of_approval'] ?? 'N/A'; ?>
							</td>
							<td>
								<button class="btn btn-success btn-flat verdict-btn"
									data-id="<?php echo $row['student_id']; ?>"
									data-verdict="<?php echo $row['verdict']; ?>"
									data-notes="<?php echo htmlspecialchars($row['approval_notes'], ENT_QUOTES); ?>"
									data-name="<?php echo $full_name; ?>">
									Verdict
								</button>


							</td>
						</tr>
					<?php endwhile; ?>

				</tbody>
			</table>
		</div>
	</div>
</div>
<!-- Dean Verdict Modal -->
<div class="modal fade" id="verdictModal" tabindex="-1" role="dialog" aria-labelledby="verdictModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<form id="verdictForm">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Update Dean Verdict</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
				</div>
				<div class="modal-body">
					<div class="alert alert-warning" role="alert">
						<h4 class="alert-heading">Exam Date Alert!</h4>

						<?php

						$aqry = $conn->query("SELECT * FROM exam_list el
						LEFT JOIN academic_list al ON al.id = el.academic_id
						where al.id = '{$_SESSION['academic']['id']}'
					");
						while ($arow = $aqry->fetch_assoc()): ?>

							<!-- <p> <?php echo $arow['exam_level'] ?></p> -->
							<hr>
							<p class="mb-0">Exam Date for <?php echo $arow['exam_level'] ?> level will be on
								<?php echo $arow['exam_date'] ?>.
							</p>

							<?php
						endwhile;

						?>
					</div>
					<div class="mb-3">
						<h5 class="text-primary">Student: <span id="studentFullName"></span></h5>
					</div>

					<input type="hidden" name="student_id" id="verdictStudentId">
					<div class="form-group">
						<label for="verdictSelect">Verdict</label>
						<select class="form-control" name="verdict" id="verdictSelect" required>
							<option value="">Select Verdict</option>
							<option value="Approved">Approved</option>
							<option value="Partially Approved">Partially Approved</option>
							<option value="Disapproved">Disapproved</option>
						</select>
					</div>
					<div class="form-group">
						<label for="approvalNotes">Notes</label>
						<textarea class="form-control" name="approval_notes" id="approvalNotes" rows="3"
							required></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success btn-flat">Save Verdict</button>
					<button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</form>
	</div>
</div>


<script>
	$(document).ready(function () {
		$('#list').dataTable()
	})
</script>

<script>
	$(document).ready(function () {
		$('.verdict-btn').on('click', function () {
			const studentId = $(this).data('id');
			const verdict = $(this).data('verdict');
			const notes = $(this).data('notes');
			const fullName = $(this).data('name'); // get the student's full name

			$('#verdictStudentId').val(studentId);
			$('#verdictSelect').val(verdict);
			$('#approvalNotes').val(notes);
			$('#studentFullName').text(fullName); // inject into modal
			$('#verdictModal').modal('show');

		});

		$('#verdictForm').on('submit', function (e) {
			e.preventDefault();

			$.ajax({
				url: 'ajax.php?action=update_dean_verdict',
				type: 'POST',
				data: $(this).serialize(),
				success: function (resp) {


					try {
						let res = JSON.parse(resp);
						if (res.status === 'success') {
							alert_toast(res.message, 'success');
							$('#verdictModal').modal('hide');
							setTimeout(() => location.reload(), 1000);
						} else {
							alert_toast(res.message || 'Update failed.', 'error');
						}
					} catch (e) {
						alert_toast('Invalid server response.', 'error');
					}


				},
				error: function () {
					alert('Failed to update verdict.');
				}
			});
		});
	});
</script>