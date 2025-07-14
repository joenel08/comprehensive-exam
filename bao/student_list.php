<?php include 'db_connect.php'; ?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-header">
			<h4 class="card-title text-success font-weight-bold">Payment Status Requests</h4>
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
						<th>Email</th>
						<th>Payment Status</th>
						<th>Additional Notes</th>
						<th>Date Updated</th>
						<th>Action</th>
						<!-- You may add actions if needed -->
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
							s.email,
							p.*,
							pr.*
						FROM 
							paymentstatus p
						JOIN 
							student_list s ON s.id = p.student_id
						LEFT JOIN 
							program pr ON pr.program_id = s.program
						ORDER BY 
							s.lastname ASC
					");

					while ($row = $qry->fetch_assoc()):
						$full_name = ucwords("{$row['lastname']}, {$row['firstname']} {$row['middlename']} {$row['extname']}");
						?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo $row['school_id']; ?></td>
							<td><?php echo ucwords($row['level']); ?></td>
							<td><?php echo $row['program_abbrv'] ?? 'N/A'; ?></td>
							<td><?php echo $full_name; ?></b></td>
							<td><?php echo $row['email']; ?></td>
							<td>
								<span
									class="badge badge-<?php echo ($row['payment_status'] === 'Verified') ? 'success' : (($row['payment_status'] === 'Pending') ? 'warning' : 'danger'); ?>">
									<?php echo $row['payment_status']; ?>
								</span>
							</td>
							<td><?php echo $row['payment_notes']; ?></td>
							<td><?php echo date("M d, Y h:i A", strtotime($row['date_updated'])); ?></td>
							<td>
								<button class="btn btn-primary btn-sm mark-payment-status"
									data-id="<?php echo $row['student_id']; ?>"
									data-status="<?php echo $row['payment_status']; ?>"
									data-notes="<?php echo htmlspecialchars($row['payment_notes'] ?? '', ENT_QUOTES); ?>">
									Update Payment Status
								</button>
							</td>

						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Payment Status Modal -->
<div class="modal fade" id="paymentStatusModal" tabindex="-1" role="dialog" aria-labelledby="paymentStatusModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<form id="payment-status-form">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Update Payment Status</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<input type="hidden" name="student_id" id="modal_student_id">

					<div class="form-group">
						<label>Status:</label><br>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="payment_status" id="statusPaid"
								value="Paid">
							<label class="form-check-label" for="statusPaid">Paid</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="payment_status" id="statusUnpaid"
								value="Unpaid">
							<label class="form-check-label" for="statusUnpaid">Unpaid</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="payment_status" id="statusPending"
								value="Pending">
							<label class="form-check-label" for="statusPending">Pending</label>
						</div>
					</div>

					<div class="form-group">
						<label for="payment_notes">Description/Notes:</label>
						<textarea name="payment_notes" id="payment_notes" class="form-control" rows="3"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success btn-flat">Save</button>
					<button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
	$(document).ready(function () {
		// When Mark Payment Status button is clicked
		$('.mark-payment-status').click(function () {
			const studentId = $(this).data('id');
			const status = $(this).data('status');
			const notes = $(this).data('notes');

			$('#modal_student_id').val(studentId);
			$('#payment_notes').val(notes);

			// Reset radio buttons
			$('input[name="payment_status"]').prop('checked', false);
			if (status) {
				$(`input[name="payment_status"][value="${status}"]`).prop('checked', true);
			}

			$('#paymentStatusModal').modal('show');
		});

		// Submit form via AJAX
		$('#payment-status-form').submit(function (e) {
			e.preventDefault();
			start_load()
			$.ajax({
				url: 'ajax.php?action=update_payment_status',
				method: 'POST',
				data: $(this).serialize(),
				success: function (resp) {
					try {
						let res = JSON.parse(resp);
						if (res.status === 'success') {
							alert_toast(res.message, 'success');
							$('#paymentStatusModal').modal('hide');
							setTimeout(() => location.reload(), 1000);
						} else {
							alert_toast(res.message || 'Update failed.', 'error');
						}
					} catch (e) {
						alert_toast('Invalid server response.', 'error');
					}
				}
			});
		});
	});
</script>