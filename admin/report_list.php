<!-- jsPDF + html2canvas -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

<style>
    #report .nav .nav-item .active{
        background-color: green !important;
        border-radius: 0px !important;
    }
    #report .nav .nav-item a:hover{
        color: green !important;
        background-color: #fff !important;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body" id="report">

                <ul class="nav nav-pills mb-3" id="reportTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="summary-tab" data-toggle="pill" href="#summary"
                            role="tab">Summary of
                            Applicants</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="summaryA-tab" data-toggle="pill" href="#summaryA" role="tab">Summary A
                            (Pass/Fail)</a>
                    </li>
                </ul>

                <div class="tab-content" id="reportTabsContent">
                    <!-- TAB 1: SUMMARY OF APPLICANTS -->
                    <div class="tab-pane fade show active" id="summary" role="tabpanel">
                        <div class="d-flex justify-content-end no-print mb-2">
                            <button class="btn btn-sm btn-primary" onclick="printDiv('summaryTable')">Print</button>
                            <button class="btn btn-sm btn-danger ml-2" onclick="downloadPDF('summaryTable')">Download
                                PDF</button>
                        </div>
                        <div id="summaryTable">
                            <table class="table table-bordered table-sm" id="list">
                                <thead class="">
                                    <tr>
                                        <th>#</th>
                                        <th>School ID</th>
                                        <th>Student Name</th>
                                        <th>Level</th>
                                        <th>Program</th>
                                        <th>Verdict</th>
                                        <th>Notes</th>
                                        <th>Date Approved</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $qry = $conn->query("SELECT da.*, s.*, p.*
                             FROM dean_approval da 
                             LEFT JOIN student_list s ON s.id = da.student_id 
                             LEFT JOIN program p on p.program_id = s.program
                             ORDER BY da.date_of_approval DESC");
                                    while ($row = $qry->fetch_assoc()):
                                        ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= $row['school_id'] ?></td>
                                            <td><?= ucwords($row['firstname'] . ' ' . $row['lastname']) ?></td>
                                            <td><?= $row['level'] ?></td>
                                            <td><?= $row['program_abbrv']. '-'.$row['programFullDesc'] ?></td>
                                            <td><?= $row['verdict'] ?></td>
                                            <td><?= $row['approval_notes'] ?></td>
                                            <td><?= date("F d, Y", strtotime($row['date_of_approval'])) ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 2: SUMMARY A -->
                    <div class="tab-pane fade" id="summaryA" role="tabpanel">
                        <div class="d-flex justify-content-end no-print mb-2">
                            <button class="btn btn-sm btn-primary" onclick="printDiv('summaryAContent')">Print</button>
                            <button class="btn btn-sm btn-danger ml-2" onclick="downloadPDF('summaryAContent')">Download
                                PDF</button>
                        </div>
                        <div id="summaryAContent">
                            <canvas id="summaryAGraph" height="300"></canvas>

                            <hr>
                            <h5 class="mt-4">Detailed Summary Table</h5>
                            <table class="table table-bordered table-sm mt-2">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Level</th>
                                        <th>Program</th>
                                        <th>Passed</th>
                                        <th>Failed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data = [];
                                    $result = $conn->query("SELECT p.*, s.level, s.program, g.grade_status, COUNT(*) as count 
                                FROM student_list s
                                LEFT JOIN student_grade_status g ON g.student_id = s.id
                                  LEFT JOIN program p on p.program_id = s.program
                                GROUP BY s.level, s.program, g.grade_status");
                                    while ($row = $result->fetch_assoc()) {
                                        $data[$row['level']][$row['program_abbrv']][$row['grade_status']] = $row['count'];
                                    }
                                    foreach ($data as $level => $programs):
                                        foreach ($programs as $program => $results):
                                            ?>
                                            <tr>
                                                <td><?= $level ?></td>
                                                <td><?= $program ?></td>
                                                <td><?= $results['Passed'] ?? 0 ?></td>
                                                <td><?= $results['Failed'] ?? 0 ?></td>
                                            </tr>
                                        <?php endforeach; endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
    
    // Chart Data from PHP
    const chartData = {
        labels: [],
        passed: [],
        failed: []
    };

    <?php foreach ($data as $level => $programs): ?>
        <?php foreach ($programs as $program => $results): ?>
            chartData.labels.push("<?= $level ?> - <?= $program ?>");
            chartData.passed.push(<?= $results['Passed'] ?? 0 ?>);
            chartData.failed.push(<?= $results['Failed'] ?? 0 ?>);
        <?php endforeach; ?>
    <?php endforeach; ?>

    // Render Chart
    const ctx = document.getElementById('summaryAGraph').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.labels,
            datasets: [
                {
                    label: 'Passed',
                    backgroundColor: 'green',
                    data: chartData.passed
                },
                {
                    label: 'Failed',
                    backgroundColor: 'red',
                    data: chartData.failed
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: {
                x: { stacked: true },
                y: { stacked: true, beginAtZero: true }
            }
        }
    });

    // Print & Download
    function printDiv(divId) {
        const content = document.getElementById(divId).innerHTML;
        const original = document.body.innerHTML;
        document.body.innerHTML = content;
        window.print();
        document.body.innerHTML = original;
        location.reload();
    }

    function downloadPDF(divId) {
        const el = document.getElementById(divId);
        html2canvas(el).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jspdf.jsPDF('p', 'mm', 'a4');
            const width = pdf.internal.pageSize.getWidth();
            const height = (canvas.height * width) / canvas.width;
            pdf.addImage(imgData, 'PNG', 0, 0, width, height);
            pdf.save('report.pdf');
        });
    }
</script>