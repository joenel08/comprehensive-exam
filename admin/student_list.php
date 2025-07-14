<?php include 'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card">
		<!-- <div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-success btn-flat text-white" href="./index.php?page=new_student"><i
						class="fa fa-plus"></i> Add New Student</a>
			</div>
		</div> -->
		<div class="card-body">
			<table class="table tabe-hover table-bordered table-responsive" id="list">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>School ID</th>
						<th>Level</th>
						<th>Program</th>
						<th>Student Name</th>
						<th>Marital Status</th>
						<th>Email</th>
						<th>Document Status</th>
						<th>Additional Notes</th>
						<th>Verification</th>
						<th>Application Status</th>
						<th>Grade Status</th>
						<!-- <th>Action</th> -->
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("
							SELECT student_list.*, student_list.id AS student_id,
								CONCAT(firstname, ' ', lastname) AS name,
								p.program_abbrv, p.programFullDesc,
								d.tor, d.birth_cert, d.grades, d.marriage_cert, d.notes,
								da.verdict
							FROM student_list 
							LEFT JOIN program p ON p.program_id = student_list.program
							LEFT JOIN student_documents d ON d.student_id = student_list.id
							LEFT JOIN dean_approval da ON da.student_id = student_list.id
							ORDER BY name ASC
						");

					while ($row = $qry->fetch_assoc()):
						$docs = [
							'TOR' => $row['tor'],
							'Birth Certificate' => $row['birth_cert'],
							'Grades' => $row['grades']
						];
						if (strtolower($row['marital_status']) !== 'single') {
							$docs['Marriage Certificate'] = $row['marriage_cert'];
						}
						?>
						<tr>
							<th class="text-center"><?= $i++ ?></th>
							<td><?= htmlspecialchars($row['school_id']) ?></td>
							<td class="text-uppercase"><?= ucwords($row['level']) ?></td>
							<td><?= $row['program_abbrv'] . ' - ' . $row['programFullDesc'] ?></td>
							<td><?= ucwords($row['name']) ?></td>
							<td><?= ucwords($row['marital_status']) ?></td>
							<td><?= htmlspecialchars($row['email']) ?></td>
							<td>
								<?php foreach ($docs as $label => $value): ?>
									<span class="badge badge-<?= $value ? 'success' : 'danger' ?>">
										<?= $label . ': ' . ($value ? 'Submitted' : 'Missing') ?>
									</span><br>
								<?php endforeach; ?>
							</td>
							<td><?= nl2br(htmlspecialchars($row['notes'])) ?></td>
							<td>
								<a class="btn btn-success btn-flat btn-sm verify_documents" href="javascript:void(0)"
									data-id="<?= $row['student_id'] ?>">Verify Documents</a>
							</td>
							<td class="text-center">
								<?php if (!empty($row['verdict'])): ?>
									<span class="badge badge-success"><?= htmlspecialchars($row['verdict']) ?></span>


									<?php

									$afqry = $conn->query("SELECT * FROM application_form 
									where academic_id = '{$_SESSION['academic']['id']}' and student_id = '{$row['student_id']}'
								");

									if ($afqry->num_rows > 0) { ?>
										<a class="text-center btn btn-primary btn-sm btn-flat"
											href="./index.php?page=comprehensive_examination_form&student_id=<?php echo $row['student_id'] ?> ">Comprehensive
											Examination Form</a>
									<?php }

									?>
								<?php else: ?>
									<span class="badge badge-warning">N/A</span>
								<?php endif; ?>
							</td>

							<td>
								<?php
								//	$markqry = $conn->query("SELECT * from student_grade_status where student_id = '{$row['student_id']}'");
							
								//	if ($markqry->num_rows > 0) {
								//		while ($mrow = $markqry->fetch_assoc()) {
								//			echo '<span class="badge badge-warning">' . $mrow['grade_status'] . '</span>'; ?>
								<!-- <button class="btn btn-primary btn-flat btn-sm mark-grade-btn"
											data-id="<?= $row['student_id'] ?>" data-name="<?= htmlspecialchars($row['name']) ?>">
											Update Grade Status
										</button> -->
								<?php //}
									//} else { ?>

								<!-- <a class="btn btn-primary btn-flat btn-sm mark-grade-btn"
										data-id="<?= $row['student_id'] ?>" data-name="<?= htmlspecialchars($row['name']) ?>">
										Mark Grade
								</a> -->
								<?php //}
									?>


								<a href="./index.php?page=mark_grade&student_id=<?= $row['student_id']; ?>"
									class="btn btn-primary">Mark Grade</a>
							</td>


						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Document Verification Modal -->
<div class="modal fade" id="verifyDocumentsModal" tabindex="-1" role="dialog"
	aria-labelledby="verifyDocumentsModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<form id="verify-documents-form">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Verify Submitted Documents</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<input type="hidden" name="student_id" id="student_id">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="1" id="tor" name="tor">
						<label class="form-check-label" for="tor">
							Photocopy of TOR / Original copy (if Non-SPUP student)
						</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="1" id="birth_cert" name="birth_cert">
						<label class="form-check-label" for="birth_cert">
							PSA Authenticated Birth Certificate
						</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="1" id="grades" name="grades">
						<label class="form-check-label" for="grades">
							Grades
						</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="1" id="marriage_cert"
							name="marriage_cert">
						<label class="form-check-label" for="marriage_cert">
							Marriage Certificate (if married)
						</label>
					</div>
					<hr>
					<label for="">Verdict</label>
					<div class="d-flex justify-content-between align-items-center">
						<!-- Radio Buttons -->
						<div class="form-group">
							<label><input type="radio" name="approved" value="Approved"> Approved</label>
						</div>
						<div class="form-group">
							<label><input type="radio" name="approved" value="Disapproved"> Disapproved</label>
						</div>
						<div class="form-group">
							<label><input type="radio" name="approved" value="Pending" checked> Pending</label>
						</div>



					</div>

					<!-- Notes -->
					<label for="notes">Additional Notes:</label>
					<textarea name="notes" class="form-control" id="notes"></textarea>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success btn-flat">Save</button>
					<button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Close</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
	$(document).ready(function () {
		$('.view_student').click(function () {
			uni_modal("<i class='fa fa-id-card'></i> student Details", "<?php echo $_SESSION['login_view_folder'] ?>view_student.php?id=" + $(this).attr('data-id'))
		})
		$('.delete_student').click(function () {
			_conf("Are you sure to delete this student?", "delete_student", [$(this).attr('data-id')])
		})
		$('#list').dataTable()
	})
	function delete_student($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_student',
			method: 'POST',
			data: { id: $id },
			success: function (resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function () {
						location.reload()
					}, 1500)

				}
			}
		})
	}
</script>

<script>
	$('.verify_documents').click(function () {
		var studentId = $(this).data('id');
		$('#student_id').val(studentId);

		// Fetch existing data
		$.ajax({
			url: 'ajax.php?action=get_student_documents',
			method: 'POST',
			data: { student_id: studentId },
			dataType: 'json',
			success: function (resp) {
				// Reset all checkboxes and radio
				$('#tor').prop('checked', false);
				$('#birth_cert').prop('checked', false);
				$('#grades').prop('checked', false);
				$('#marriage_cert').prop('checked', false);
				$('input[name="approved"]').prop('checked', false);
				$('#notes').val('');

				if (resp && resp.success && resp.data) {
					const data = resp.data;

					$('#tor').prop('checked', data.tor == 1);
					$('#birth_cert').prop('checked', data.birth_cert == 1);
					$('#grades').prop('checked', data.grades == 1);
					$('#marriage_cert').prop('checked', data.marriage_cert == 1);
					$('input[name="approved"][value="' + data.verdict + '"]').prop('checked', true);
					$('#notes').val(data.notes);
				}
				$('#verifyDocumentsModal').modal('show');
			}
		});
	});

</script>
<script>
	$(document).ready(function () {
		$('.verify_documents').click(function () {
			var studentId = $(this).data('id');
			$('#student_id').val(studentId);
			$('#verifyDocumentsModal').modal('show');
		});

		$('#verify-documents-form').submit(function (e) {
			e.preventDefault();
			start_load()
			$.ajax({
				url: 'ajax.php?action=save_documents_status',
				method: 'POST',
				data: $(this).serialize(),
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
						$('#verifyDocumentsModal').modal('hide');

						setTimeout(function () {
							location.reload();
						}, 1500);
					} else {
						alert_toast(response.message + ' - ' + (response.error || ''), 'error');
					}
				}

			});
		});
	});
</script>

<script>
	$(document).ready(function () {
		// Open modal and populate student info
		$('.mark-grade-btn').click(function () {
			var studentId = $(this).data('id');
			var studentName = $(this).data('name');

			$('#studentId').val(studentId);
			$('#studentName').text(studentName);
			$('#markGradeModal').modal('show');
		});

		// Handle form submit via AJAX
		$('#markGradeForm').submit(function (e) {
			e.preventDefault();

			$.ajax({
				url: 'ajax.php?action=save_grade_status',
				method: 'POST',
				data: $(this).serialize(),
				success: function (response) {

					if (response == 1) {
						// alert(response);
						alert_toast("Successfully marked!", 'success');
						$('#markGradeModal').modal('hide');
						setTimeout(function () {
							location.reload();
						}, 1500);
					}

				},
				error: function () {
					alert("Something went wrong. Try again.");
				}
			});
		});
	});
</script>