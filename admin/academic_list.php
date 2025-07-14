<?php include 'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<div class="d-flex">
				
				<div class="card-tools ml-auto">
					<a class="btn  btn-sm btn-success text-white btn-flat new_academic" href="javascript:void(0)"><i
							class="fa fa-plus"></i> Add New</a>

				</div>
			</div>
			<hr>
			<table class="table table-hover" id="list">
				<!-- <colgroup>
					<col width="5%">
					<col width="25%">
					<col width="25%">
					<col width="15%">
					<col width="15%">
					<col width="15%">
				</colgroup> -->
				<thead>
					<tr class="text-center">
						<th class="text-center">#</th>
						<th>Year</th>
						<th>Semester</th>
						<th>System Default</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT * FROM academic_list order by id desc ");
					while ($row = $qry->fetch_assoc()):
						?>
						<tr class="text-center">
							<th class="text-center"><?php echo $i++ ?></th>
							<td><b><?php echo $row['year'] ?></b></td>
							<td><b><?php if ($row['semester'] = 1 ){
								echo 'First';
								} else if ($row['semester'] = 2 ){
									echo 'Second';
								}else if ($row['semester'] = 3 ){
									echo 'Third';
								}else if ($row['semester'] = 4 ){
									echo 'Mid-year';
								}?> Semester</b></td>

						
							<td class="text-center">
								<?php if ($row['is_default'] == 0): ?>
									<button type="button"
										class="btn btn-secondary bg-gradient-secondary col-sm-4 btn-flat btn-sm px-1 py-0 make_default"
										data-id="<?php echo $row['id'] ?>">No</button>
								<?php else: ?>
									<button type="button"
										class="btn btn-primary bg-gradient-primary col-sm-4 btn-flat btn-sm px-1 py-0">Yes</button>
								<?php endif; ?>
							</td>
							

							<td class="text-center">
								<div class="btn-group">
									<a href="javascript:void(0)" data-id='<?php echo $row['id'] ?>'
										class="btn btn-primary btn-sm text-white btn-flat manage_academic">
										<i class="fas fa-edit"></i>
									</a>
									<button type="button" class="btn btn-danger btn-sm  btn-flat delete_academic"
										data-id="<?php echo $row['id'] ?>">
										<i class="fas fa-trash"></i>
									</button>
								</div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		$('.new_academic').click(function () {
			uni_modal("New academic", "<?php echo $_SESSION['login_view_folder'] ?>manage_academic.php")
		})
		$('.manage_academic').click(function () {
			uni_modal("Manage academic", "<?php echo $_SESSION['login_view_folder'] ?>manage_academic.php?id=" + $(this).attr('data-id'))
		})
		$('.delete_academic').click(function () {
			_conf("Are you sure to delete this academic?", "delete_academic", [$(this).attr('data-id')])
		})
		$('.make_default').click(function () {
			_conf("Are you sure to make this academic year as the system default?", "make_default", [$(this).attr('data-id')])
		})
		$('#list').dataTable()
	})
	function delete_academic($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_academic',
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
	function make_default($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=make_default',
			method: 'POST',
			data: { id: $id },
			success: function (resp) {
				if (resp == 1) {
					alert_toast("Dafaut Academic Year Updated", 'success')
					setTimeout(function () {
						location.reload()
					}, 1500)
				}
			}
		})
	}
</script>