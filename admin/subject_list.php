<?php include('db_connect.php') ?>


<div class="row">
  <div class="col-sm-4">
    <div class="card">
      <div class="card-body">
        <div class="container-fluid">
          <h4 class="text-success font-weight-bold"><?php if ($title == 'Edit Subject') {
            echo $title;
          } else {
            echo 'Add New Subject';
          } ?></h4>

          <form action="" id="manage-subject" class="mt-3">
            <input type="hidden" name="sub_id" value="<?php echo isset($sub_id) ? $sub_id : '' ?>">

            <div class="form-group">
              <label for="" class="control-label">Level</label>

              <select name="subject_level" id="subject_level" class="form-control">
                <option value="doctorate" <?php echo (isset($subject_level) && $subject_level == 'doctorate') ? 'selected' : ''; ?>>
                  Doctorate</option>
                <option value="masteral" <?php echo (isset($subject_level) && $subject_level == 'masteral') ? 'selected' : ''; ?>>Masteral
                </option>
              </select>

            </div>

            <div class="form-group">
              <label for="" class="control-label">Subject Type</label>

              <select name="subject_type" id="subject_type" class="form-control">
                <option value="major" <?php echo (isset($subject_type) && $subject_type == 'major') ? 'selected' : ''; ?>>
                  Major</option>
                <option value="core" <?php echo (isset($subject_type) && $subject_type == 'core') ? 'selected' : ''; ?>>
                  Core</option>
                <option value="cognate" <?php echo (isset($subject_type) && $subject_type == 'cognate') ? 'selected' : ''; ?>>Cognate</option>
              </select>
            </div>
            <div class="form-group">
              <label for="" class="control-label">Subject Code</label>

              <input type="text" name="subject_code" value="<?php echo isset($subject_code) ? $subject_code : '' ?>"
                class="form-control">
            </div>

            <div class="form-group">
              <label for="" class="control-label">Subject Description</label>

              <input type="text" name="subject_desc" value="<?php echo isset($subject_desc) ? $subject_desc : '' ?>"
                class="form-control">
            </div>

            <div class="form-group">

              <label for="" class="control-label">Subject Professor</label>

              <input type="text" name="professor" value="<?php echo isset($professor) ? $professor : '' ?>"
                class="form-control">
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
          <h4 class="text-success font-weight-bold">Subject List</h4>
        </div>
        <div class="card-body" style="align-items: center;">

          <table class="table tabe-hover table-bordered" id="list">

            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Subject Type</th>
                <th class="text-center">Subject Level</th>
                <th class="text-center">Subject</th>
                <th class="text-center">Professor</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              $qry = $conn->query("SELECT * FROM subject_list");
              while ($row = $qry->fetch_assoc()):
                ?>
                <tr>
                  <th class="text-center"><?php echo $i++ ?></th>
                  <td class="text-center text-uppercase">
                    <?php echo $row['subject_type'] ?>
                  </td>

                  <td class="text-center">
                    <?php echo $row['subject_level'] ?>
                  </td>

                  <td class="text-center">
                    <?php echo $row['subject_code'] . ' - ' . $row['subject_desc'] ?>
                  </td>
                  <td class="text-center">
                    <?php echo $row['professor'] ?>
                  </td>
                  <td class="text-center">

                    <div class="btn-group">
                      <a href="./index.php?page=edit_subject&sub_id=<?php echo $row['sub_id'] ?>"
                        class="btn btn-primary text-white btn-sm btn-flat ">
                        <i class="fas fa-edit"></i>
                      </a>

                      <a href="javascript:void(0)" data-id="<?php echo $row['sub_id'] ?>"
                        class="delete_exam btn btn-danger btn-sm btn-flat ">
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
    $("#manage-subject").on('submit', function (e) {
      e.preventDefault();

      var form_data = new FormData(this); // Use 'this' to refer to the form element
      start_load()
      $.ajax({
        url: "ajax.php?action=save_subject",
        type: "POST",
        data: form_data,
        processData: false, // Do not process the data
        contentType: false, // Do not set contentType
        success: function (resp) {
          // console.log(data);
          if (resp == 1) {
            alert_toast("Data successfully saved!", 'success');
            setTimeout(function () {
              location.replace('index.php?page=subject_list');
            }, 750);

          }
        }
      });
    });
  });

</script>
<script>
  $('.delete_exam').click(function () {
    _conf("This exam date will be deleted!", "delete_exam", [$(this).attr('data-id')])
  })

  function delete_exam($id) {
    start_load()
    $.ajax({
      url: 'ajax.php?action=delete_exam',
      method: 'POST',
      data: {
        exam_id: $id
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