<div class="row">


    <div class="col-sm">
        <?php
        $student_id = $_SESSION['login_id']; // Replace with your login session variable
        $qry = $conn->query("SELECT * FROM application_form WHERE student_id = '{$_GET['student_id']}'");


        $student = $conn->query("SELECT * FROM student_list where id = '{$_GET['student_id']}'");


        $i = 1;
        ?>

        <div class="card shadow">
            <div class="card-header">

                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        <?php
                        while ($row = $student->fetch_assoc()):
                            echo '<span class="font-weight-bold">' . $row['firstname'] . ' ' . $row['lastname'] . '</span>';
                        endwhile;
                        ?>
                        Subjects Application
                    </h5>
                    <div class="d-flex">
                        <button class="btn btn-flat btn-success mr-2" id="acceptSelected">Approve</button>
                        <button class="btn btn-flat btn-danger mr-2" id="declineSelected">Disapprove</button>
                        <a href="./index.php?page=download_form&student_id=<?php echo $_GET['student_id'] ?>" class="btn btn-flat btn-info">Print</a>
                    </div>

                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover mb-0" id="list">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="checkAll"></th>
                            <th>#</th>
                            <th>Subject Type</th>
                            <th>Subject Code</th>
                            <th>Subject Description</th>
                            <th>Subject Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $qry->fetch_assoc()):
                            $subject_id = (int) $row['subject_ids']; // assuming it's now a single ID
                            $subject_list = $conn->query("SELECT * FROM subject_list WHERE sub_id = $subject_id");
                            while ($srow = $subject_list->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><input type="checkbox" class="checkItem" value="<?= $row['af_id']; ?>"></td>
                                    <td><?= $i++; ?></td>
                                    <td><?= ucfirst($srow['subject_type']); ?></td>
                                    <td><?= $srow['subject_code']; ?></td>
                                    <td><?= $srow['subject_desc']; ?></td>
                                    <td>
                                        <?php
                                        $status = $row['subject_status'];
                                        $badgeClass = match ($status) {
                                            'under review' => 'badge-warning',
                                            'approved' => 'badge-success',
                                            'declined' => 'badge-danger',
                                            default => 'badge-secondary',
                                        };
                                        ?>
                                        <span class="badge <?= $badgeClass; ?>"><?= htmlspecialchars($status); ?></span>
                                    </td>

                                </tr>
                                <?php
                            endwhile;
                        endwhile;
                        ?>
                    </tbody>

                </table>
            </div>
        </div>

    </div>
</div>



<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: "Please select here",
            width: "100%"
        });

        $('#list').dataTable()

    })

</script>
<script>
    $(document).ready(function () {
        // Select/Deselect All
        $('#checkAll').click(function () {
            $('.checkItem').prop('checked', $(this).prop('checked'));
        });

        // Accept selected
        $('#acceptSelected').click(function () {
            updateStatus('approved');
        });

        // Decline selected
        $('#declineSelected').click(function () {
            updateStatus('declined');
        });

        function updateStatus(status) {
            const selected = $('.checkItem:checked').map(function () {
                return $(this).val();
            }).get();

            if (selected.length === 0) {
                alert_toast(" Please select at least one subject.",'warning');
                return;
            }

            $.ajax({
                url: 'ajax.php?action=update_subject_status',
                method: 'POST',
                data: {
                    af_ids: selected,
                    status: status
                },
                success: function (resp) {
                    alert_toast(resp,'success');
                    location.reload();
                }
            });
        }
    });
</script>