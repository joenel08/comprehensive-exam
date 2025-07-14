<div class="row">
    <div class="col-sm">
        <div class="card">
            <div class="card-header">
                    <h5 class="mb-0">Comprehensive Examination Form Application</h5>
                
            </div>
            <div class="card-body">
                <form id="subjectForm">
                    <input type="hidden" name="student_id" value="<?= $_SESSION['login_id']; ?>">

                    <div class="form-group">
                        <label>Core Subjects <small class="badge badge-warning" id="coreLabel"></small></label>
                        <select class="form-control select2" name="core_subjects[]" id="coreSubjects" multiple required>
                            <?php
                            $core = $conn->query("SELECT * FROM subject_list WHERE subject_type = 'core' and subject_level = '{$_SESSION['login_level']}'");
                            while ($row = $core->fetch_assoc()):
                                ?>
                                <option value="<?= $row['sub_id']; ?>"><?= $row['subject_code']; ?> -
                                    <?= $row['subject_desc']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Major Subjects <small class="badge badge-warning" id="majorLabel"></small></label>
                        <select class="form-control select2" name="major_subjects[]" id="majorSubjects" multiple
                            required>
                            <?php
                            $major = $conn->query("SELECT * FROM subject_list WHERE subject_type = 'major' and subject_level = '{$_SESSION['login_level']}'");
                            while ($row = $major->fetch_assoc()):
                                ?>
                                <option value="<?= $row['sub_id']; ?>"><?= $row['subject_code']; ?> -
                                    <?= $row['subject_desc']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Cognate Subjects <small class="badge badge-warning" id="cognateLabel"></small></label>
                        <select class="form-control select2" name="cognate_subjects[]" id="cognateSubjects" multiple
                            required>
                            <?php
                            $cognate = $conn->query("SELECT * FROM subject_list WHERE subject_type = 'cognate' and subject_level = '{$_SESSION['login_level']}'");
                            while ($row = $cognate->fetch_assoc()):
                                ?>
                                <option value="<?= $row['sub_id']; ?>"><?= $row['subject_code']; ?> -
                                    <?= $row['subject_desc']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-success  btn-flat ">Submit Application</button>

                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="col-sm-7">
        <?php
        $student_id = $_SESSION['login_id'];
        $qry = $conn->query("SELECT * FROM application_form WHERE student_id = '$student_id'");
        $i = 1;
        ?>

        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Your Subject Application for Examination</h5>
                    <a href="./index.php?page=download_form&student_id=<?php echo $_SESSION['login_id'] ?>"
                        class="btn btn-flat btn-success">Print</a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Subject Type</th>
                            <th>Subject Code</th>
                            <th>Subject Description</th>
                            <th>Professor</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $student_id = $_SESSION['login_id'];

                        $qry = $conn->query("
            SELECT 
                af.af_id,
                af.subject_ids,
                af.subject_status,
                sl.*
            FROM application_form af
            INNER JOIN subject_list sl ON sl.sub_id = af.subject_ids
            WHERE af.student_id = '$student_id'
            ORDER BY sl.subject_type ASC
        ");

                        if ($qry && $qry->num_rows > 0):
                            while ($row = $qry->fetch_assoc()):
                                $status = strtolower($row['subject_status']);
                                $badgeClass = 'badge-warning';
                                if ($status === 'approved')
                                    $badgeClass = 'badge-success';
                                elseif ($status === 'disapproved')
                                    $badgeClass = 'badge-danger';
                                ?>
                                <tr>
                                    <td><?= ucfirst($row['subject_type']); ?></td>
                                    <td><?= $row['subject_code']; ?></td>
                                    <td><?= $row['subject_desc']; ?></td>
                                    <td><?= $row['professor']; ?></td>

                                    <td>
                                        <span class="badge <?= $badgeClass; ?>">
                                            <?= ucwords($row['subject_status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($status !== 'approved'): ?>
                                            <button class="btn btn-danger btn-sm delete-subject" data-af_id="<?= $row['af_id']; ?>">
                                                Delete
                                            </button>
                                        <?php else: ?>
                                            <span class="text-muted">Locked</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile;
                        else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No subjects applied yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>

    </div>
</div>

<script>
    const level = "<?php echo strtolower($_SESSION['login_level']); ?>";
    const limits = {
        masteral: { core: 2, major: 4, cognate: 1 },
        doctorate: { core: 3, major: 6, cognate: 1 }
    };

    function enforceLimit(selectId, limit) {
        $(`#${selectId}`).on('change', function () {
            if ($(this).val().length > limit) {
                alert(`You can only select ${limit}`);
                const trimmed = $(this).val().slice(0, limit);
                $(this).val(trimmed);
            }
        });
    }

    function updateLabels() {
        $('#coreLabel').text(`Choose ${limits[level].core}`);
        $('#majorLabel').text(`Choose ${limits[level].major}`);
        $('#cognateLabel').text(`Choose ${limits[level].cognate}`);
    }

    $(document).ready(function () {
        if (!limits[level]) return;

        updateLabels();
        enforceLimit('coreSubjects', limits[level].core);
        enforceLimit('majorSubjects', limits[level].major);
        enforceLimit('cognateSubjects', limits[level].cognate);

        $('#subjectForm').on('submit', function (e) {
            e.preventDefault();

            const data = {
                student_id: $("input[name='student_id']").val(),
                core_subjects: $('#coreSubjects').val() || [],
                major_subjects: $('#majorSubjects').val() || [],
                cognate_subjects: $('#cognateSubjects').val() || []
            };

            $.ajax({
                url: 'ajax.php?action=save_application',
                method: 'POST',
                data: data,
                success: function (resp) {
                    alert_toast(resp, 'info');
                    $('#subjectForm')[0].reset();
                    $('#coreSubjects, #majorSubjects, #cognateSubjects').val(null);
                    setTimeout(() => location.reload(), 1500);
                }
            });
        });
    });
</script>