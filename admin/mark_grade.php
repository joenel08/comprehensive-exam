<style>
    .gso {
        border: 2px solid #000;
        padding: 5px 10px 5px 10px;
        font-weight: bold;
    }
</style>
<?php
$academic_id = $_SESSION['academic']['id'];
$student_id = $_GET['student_id']; // or use session if it's logged-in student

// Get student info
$student = $conn->query("SELECT * FROM student_list WHERE id = '$student_id'")->fetch_assoc();

// Get program info
$program = $conn->query("SELECT * FROM program WHERE program_id = '{$student['program']}'")->fetch_assoc();

// Get academic year
$academic = $conn->query("SELECT * FROM academic_list WHERE id = '$academic_id'")->fetch_assoc();

// Get exam info
$exam = $conn->query("SELECT * FROM exam_list WHERE academic_id = '$academic_id' AND exam_level = '{$student['level']}'")->fetch_assoc();
?>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <!-- <span class="gso">GSO-2B</span> -->
                <div class="text-center">

                    <p class="font-weight-bold">COMPREHENSIVE EXAMINATION GRADE FORM</p>
                    <p class="text-uppercase">FOR <?php
                    if ($student['level'] == 'masteral') {
                        echo 'MASTERAL';
                    } else {
                        echo 'DOCTORAL';
                    }
                    ?> DEGREE CANDIDATE</p>
                </div>



                <div class="row">
                    <div class="col">

                        <p class="m-0">SCHOOL YEAR: <?= $academic['year'] ?></p>
                        <p class="m-0">STUDENT NO.: <?= $student['school_id'] ?></p>
                        <p class="m-0">NAME:
                            <?= strtoupper($student['lastname'] . ', ' . $student['firstname'] . ' ' . $student['middlename'] . ' ' . $student['extname']) ?>
                        </p>

                    </div>

                    <div class="col">
                        <p class="m-0">COURSE: <?= $program['programFullDesc'] ?> (<?= $program['program_abbrv'] ?>)</p>
                        <p class="m-0">DATE OF EXAM:
                            <?php
                            if (!empty($exam) && !empty($exam['exam_date'])) {
                                echo date('F d, Y', strtotime($exam['exam_date']));
                            } else {
                                echo 'Exam Not Set Yet!';
                            }
                            ?>
                        </p>

                        <p class="m-0">CONTACT NO: <?= $student['mobile_number'] ?></p>

                    </div>
                </div>

                <?php
                $student_id = $_GET['student_id']; // Or use $_SESSION['login_id']
                
                $grades = [];
                $grade_q = $conn->query("SELECT * FROM subject_grades WHERE student_id = '$student_id'");
                while ($gr = $grade_q->fetch_assoc()) {
                    $grades[$gr['af_id']] = $gr; // Store grades by af_id for fast lookup
                }
                // Join each subject in the application form
                $qry = $conn->query("
                    SELECT 
                        af.af_id,
                        af.subject_ids,
                        sl.subject_type,
                        sl.subject_code,
                        sl.subject_desc
                    FROM application_form af
                    LEFT JOIN subject_list sl ON af.subject_ids = sl.sub_id
                    WHERE af.student_id = '$student_id' and af.subject_status = 'approved'
                ");

                $subjects = [
                    'core' => [],
                    'major' => [],
                    'cognate' => [],
                ];

                while ($row = $qry->fetch_assoc()) {
                    $type = strtolower($row['subject_type']);
                    if (isset($subjects[$type])) {
                        $subjects[$type][] = $row;
                    }
                }
                ?>

                <hr>
                <div class="row">
                    <div class="col">
                        <form id="gradeForm">
                            <input type="hidden" name="student_id" value="<?= $student_id ?>">
                            <table class="table table-bordered">
                                <thead>
                                    <!-- Core Subjects -->
                                    <?php if (!empty($subjects['core'])): ?>
                                        <tr>
                                            <td rowspan="<?= count($subjects['core']) + 1 ?>">
                                                <?= count($subjects['core']) ?>
                                                Core Subject(s)
                                            </td>
                                            <td>Subject Code</td>
                                            <td>Subject Title</td>
                                            <td>Grade</td>
                                        </tr>
                                        <?php foreach ($subjects['core'] as $core): ?>
                                            <tr>
                                                <td><input type="text" class="form-control" readonly
                                                        value="<?= $core['subject_code'] ?>"></td>
                                                <td><input type="text" class="form-control" readonly
                                                        value="<?= $core['subject_desc'] ?>"></td>
                                                <td>
                                                    <input type="hidden" name="af_id[]" value="<?= $core['af_id'] ?>">
                                                    <input type="text" name="grade[]" placeholder="Input Grade"
                                                        class="form-control"
                                                        value="<?= isset($grades[$core['af_id']]) ? $grades[$core['af_id']]['grade'] : '' ?>">
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <!-- Major Subjects -->
                                    <?php if (!empty($subjects['major'])): ?>
                                        <tr>
                                            <td rowspan="<?= count($subjects['major']) + 1 ?>">
                                                <?= count($subjects['major']) ?>
                                                Major Subject(s)
                                            </td>

                                        </tr>
                                        <?php foreach ($subjects['major'] as $major): ?>
                                            <tr>
                                                <td><input type="text" class="form-control" readonly
                                                        value="<?= $major['subject_code'] ?>"></td>
                                                <td><input type="text" class="form-control" readonly
                                                        value="<?= $major['subject_desc'] ?>"></td>
                                                <td>
                                                    <input type="hidden" name="af_id[]" value="<?= $major['af_id'] ?>">
                                                    <input type="text" name="grade[]" placeholder="Input Grade"
                                                        class="form-control"
                                                        value="<?= isset($grades[$major['af_id']]) ? $grades[$major['af_id']]['grade'] : '' ?>">
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <!-- Cognate Subjects -->
                                    <?php if (!empty($subjects['cognate'])): ?>
                                        <tr>
                                            <td rowspan="<?= count($subjects['cognate']) + 1 ?>">
                                                <?= count($subjects['cognate']) ?> Cognate Subject(s)
                                            </td>
                                            <?php foreach ($subjects['cognate'] as $cognate): ?>
                                            <tr>
                                                <td><input type="text" class="form-control" readonly
                                                        value="<?= $cognate['subject_code'] ?>"></td>
                                                <td><input type="text" class="form-control" readonly
                                                        value="<?= $cognate['subject_desc'] ?>"></td>
                                                <td>
                                                    <input type="hidden" name="af_id[]" value="<?= $cognate['af_id'] ?>">
                                                    <input type="text" name="grade[]" placeholder="Input Grade"
                                                        class="form-control"
                                                        value="<?= isset($grades[$cognate['af_id']]) ? $grades[$cognate['af_id']]['grade'] : '' ?>">
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tr>

                                    <?php endif; ?>
                                </thead>
                            </table>
                            <hr>
                            <div class="form-group">
                                <label for="">Over-all Remarks</label>
                                <textarea name="remarks" rows="7" id="" class="form-control"></textarea>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-success btn-flat">Save Grades</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#gradeForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: 'ajax.php?action=save_subject_grades',
            type: 'POST',
            data: $(this).serialize(),
            success: function (resp) {
                alert_toast(resp.message, resp.status);
            }

            error: function () {
                alert('Failed to save grades.');
            }
        });
    });
</script>