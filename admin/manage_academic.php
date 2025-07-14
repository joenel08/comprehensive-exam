<?php
include '../db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM academic_list where id={$_GET['id']}")->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage-academic">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div id="msg" class="form-group"></div>
		<div class="form-group">
			<label for="year" class="control-label">Year</label>
			<input type="text" class="form-control form-control-sm" name="year" id="year" value="<?php echo isset($year) ? $year : '' ?>" placeholder="(2019-2020)" required>
		</div>
		<div class="form-group">
			<label for="semester" class="control-label">Semester</label>
			<select name="semester" class="form-control" id="semester" >
				<option value="1">First Sem</option>
				<option value="2">Second Sem</option>
				<option value="3">Third Sem</option>
				<option value="4">Mid Year</option>
			</select>
		
		</div>
	</form>
</div>
<script>
	$(document).ready(function(){
		$('#manage-academic').submit(function(e){
			e.preventDefault();
			start_load()
			$('#msg').html('')
			$.ajax({
				url:'ajax.php?action=save_academic',
				method:'POST',
				data:$(this).serialize(),
				success:function(resp){
					if(resp == 1){
						alert_toast("Data successfully saved.","success");
						setTimeout(function(){
							location.reload()	
						},1750)
					}else if(resp == 2){
						$('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> academic Code already exist.</div>')
						end_load()
					}
				}
			})
		})
	})

</script>