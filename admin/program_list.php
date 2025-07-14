<?php include('db_connect.php') ?>


<div class="row">
  <div class="col-sm-4">
    <div class="card">
      <div class="card-body">
        <div class="container-fluid">
          <h4 class="text-success font-weight-bold"><?php if ($title == 'Edit Program') {
            echo $title;
          } else {
            echo 'Add New Program';
          } ?></h4>

          <form action="" id="manage-program" class="mt-3">
            <input type="hidden" name="program_id" value="<?php echo isset($program_id) ? $program_id : '' ?>">

            <div class="form-group">
              <label for="" class="control-label">Level</label>

              <select name="level" id="level" class="form-control">
                <option value="doctorate" <?php echo (isset($level) && $level == 'doctorate') ? 'selected' : ''; ?>>
                  Doctorate</option>
                <option value="masteral" <?php echo (isset($level) && $level == 'masteral') ? 'selected' : ''; ?>>Masteral
                </option>
              </select>

            </div>

            <div class="form-group">
              <label for="" class="control-label">Program Abbreviation</label>

              <input type="text " name="program_abbrv" value="<?php echo isset($program_abbrv) ? $program_abbrv : '' ?>"
                class="form-control">
            </div>


            <div class="form-group">
              <label for="" class="control-label">Program Full Description</label>

              <input type="text " name="programFullDesc"
                value="<?php echo isset($programFullDesc) ? $programFullDesc : '' ?>" class="form-control">
            </div>

            <div class="d-flex justify-content-end w-100">
              <button class="btn btn-sm btn-success text-white btn-flat mx-1" type="submit">Save</button>
              <a href="./index.php?page=program_list" class="btn btn-flat btn-secondary mx-1 btn-sm">Cancel</a>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>

  <div class="col-sm-8">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <h4 class="text-success font-weight-bold">Program List</h4>
        </div>
        <div class="card-body" style="align-items: center;">

          <table class="table tabe-hover table-bordered" id="list">
          
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Program</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              $qry = $conn->query("SELECT * FROM program
             ");
              while ($row = $qry->fetch_assoc()):
                ?>
                <tr>
                  <th class="text-center"><?php echo $i++ ?></th>
                
                  <td class="text-center">
                    <?php echo $row['program_abbrv'] . ' - ' . $row['programFullDesc'] ?>
                  </td>

                  <td class="text-center">

                    <div class="btn-group">
                      <a href="./index.php?page=edit_program&program_id=<?php echo $row['program_id'] ?>"
                        class="btn btn-primary text-white btn-sm btn-flat ">
                        <i class="fas fa-edit"></i>
                      </a>

                      <a href="javascript:void(0)" data-id="<?php echo $row['program_id'] ?>"
                        class="delete_dep btn btn-danger btn-sm btn-flat ">
                        <i class="fas fa-trash"></i>
                      </a>
                    </div>

                  </td>

                </tr>
              <?php endwhile;
              ?>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>


<script>
  $(document).ready(function () {
    $('#list').dataTable()
  })
</script>
<script>
  $(document).ready(function () {
    $("#manage-program").on('submit', function (e) {
      e.preventDefault();

      var form_data = new FormData(this); // Use 'this' to refer to the form element
      start_load()
      $.ajax({
        url: "ajax.php?action=save_program",
        type: "POST",
        data: form_data,
        processData: false, // Do not process the data
        contentType: false, // Do not set contentType
        success: function (data) {
          console.log(data);
          alert_toast("Data successfully saved!", 'success');
          setTimeout(function () {
            location.replace('index.php?page=program_list');
          }, 1500);
        }
      });
    });
  });

</script>
<script>
  $('.delete_dep').click(function () {
    _conf("This program will be deleted!", "delete_dep", [$(this).attr('data-id')])
  })

  function delete_dep($dep_id) {
    start_load()
    $.ajax({
      url: 'ajax.php?action=delete_program',
      method: 'POST',
      data: {
        program_id: $dep_id
      },
      success: function (resp) {
        if (resp == 1) {
          alert_toast("Program successfully deleted", 'success')
          setTimeout(function () {
            location.reload()
          }, 1500)

        }
      }
    })
  }
</script>