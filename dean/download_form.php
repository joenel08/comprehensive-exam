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
                <span class="gso">GSO-2B</span>
                <div class="text-center">
                    <img src="./assets/img/spup_logo.png" height="70px" alt="">
                    <h4 class="m-0 font-weight-bold">St. Paul University Philippines</h3>
                        <p class="">Tuguegarao City, Cagayan 3500</p>
                        <p class="font-weight-bold">OFFICE OF THE GRADUATE SCHOOL </p>
                        <p class="font-weight-bold">COMPREHENSIVE EXAMINATION FORM <br>FOR COURSES TO BE TAKEN</p>
                        <p class="text-uppercase">FOR <?php
                        if ($student['level'] == 'masteral') {
                            echo 'MASTERAL';
                        } else {
                            echo 'DOCTORAL';
                        }
                        ?> DEGREE CANDIDATE</p>
                </div>

<hr>

                <div class="row">
                    <div class="col">
                        <p class="m-0">NAME:
                            <?= strtoupper($student['lastname'] . ', ' . $student['firstname'] . ' ' . $student['middlename'] . ' ' . $student['extname']) ?>
                        </p>
                        <p class="m-0">COURSE: <?= $program['programFullDesc'] ?> (<?= $program['program_abbrv'] ?>)</p>
                        <p class="m-0">CONTACT NO: <?= $student['mobile_number'] ?></p>
                    </div>

                    <div class="col">
                        <p class="m-0">RECEIPT NO.: <?= $receipt_no ?? '__________' ?></p>
                        <!-- Replace with actual receipt no -->
                        <p class="m-0">STUDENT NO.: <?= $student['school_id'] ?></p>
                        <p class="m-0">DATE OF EXAM: <?= date('F d, Y', strtotime($exam['exam_date'])) ?></p>
                        <p class="m-0">SCHOOL YEAR: <?= $academic['year'] ?></p>
                        <p class="m-0">EXAMINEE NO.: <?= $examinee_no ?? '__________' ?></p>
                        <!-- Replace with actual examinee no -->
                    </div>
                </div>

                <?php
                $student_id = $_GET['student_id']; // Or use $_SESSION['login_id']
                
                // Join each subject in the application form
                $qry = $conn->query("
    SELECT 
        af.af_id,
        af.subject_ids,
        sl.*
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

                        <table class="table table-bordered">
                            <thead>
                                <!-- Core Subjects -->
                                <?php if (!empty($subjects['core'])): ?>
                                    <tr>
                                        <td rowspan="<?= count($subjects['core']) + 1 ?>"><?= count($subjects['core']) ?>
                                            Core Subject(s)</td>
                                        <td>Subject Code</td>
                                        <td>Subject Title</td>
                                        <td>Professor</td>
                                    </tr>
                                    <?php foreach ($subjects['core'] as $core): ?>
                                        <tr>
                                            <td><input type="text" class="form-control" readonly value="<?= $core['subject_code'] ?>"></td>
                                            <td><input type="text" class="form-control" readonly value="<?= $core['subject_desc'] ?>"></td>
                                            <td><input type="text" class="form-control" readonly value="<?= $core['professor'] ?>"></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <!-- Major Subjects -->
                                <?php if (!empty($subjects['major'])): ?>
                                    <tr>
                                        <td rowspan="<?= count($subjects['major']) + 1 ?>"><?= count($subjects['major']) ?>
                                            Major Subject(s)</td>
                                  
                                    </tr>
                                    <?php foreach ($subjects['major'] as $major): ?>
                                        <tr>
                                            <td><input type="text" class="form-control" readonly value="<?= $major['subject_code'] ?>"></td>
                                            <td><input type="text" class="form-control" readonly value="<?= $major['subject_desc'] ?>"></td>
                                            <td><input type="text" class="form-control" readonly value="<?= $major['professor'] ?>"></td>
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
                                            <td><input type="text" class="form-control" readonly value="<?= $cognate['subject_code'] ?>"></td>
                                            <td><input type="text" class="form-control" readonly value="<?= $cognate['subject_desc'] ?>"></td>
                                            <td><input type="text" class="form-control" readonly value="<?= $cognate['professor'] ?>"></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tr>

                                <?php endif; ?>
                            </thead>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.onload = function () {
        window.print();

        window.onafterprint = function () {
            // Redirect whether user prints or cancels
            window.location.href = './index.php?page=comprehensive_examination_form&student_id=<?php echo $_GET['student_id'] ?>'; // Change to your desired page
        };
    };
</script>