
  <style>
    .form-title {
      text-align: center;
      font-weight: bold;
      margin-bottom: 20px;
    }
    .section-title {
      font-weight: bold;
      margin-top: 30px;
      text-decoration: underline;
    }
    .signature-line {
      border-top: 1px solid black;
      width: 100%;
      margin-top: 30px;
    }
  </style>

  <div class="text-center mb-3">
    <h5>St. Paul University Philippines</h5>
    <p>Tuguegarao City, Cagayan 3500</p>
    <h6 class="form-title">OFFICE OF THE GRADUATE SCHOOL<br>APPLICATION FOR COMPREHENSIVE EXAMINATION</h6>
  </div>

  <form>
    <!-- Section A -->
    <h6 class="section-title">A. To be filled-out by the GRADUATE STUDENT APPLICANT</h6>
    <div class="row mb-2">
      <div class="col-md-4">
        <label>Last Name</label>
        <input type="text" class="form-control">
      </div>
      <div class="col-md-4">
        <label>First Name</label>
        <input type="text" class="form-control">
      </div>
      <div class="col-md-4">
        <label>Middle Name</label>
        <input type="text" class="form-control">
      </div>
    </div>
    <div class="mb-2">
      <label>Program</label>
      <input type="text" class="form-control">
    </div>
    <div class="mb-2">
      <label>Major in</label>
      <input type="text" class="form-control">
    </div>
    <div class="mb-2">
      <label>Contact Address</label>
      <input type="text" class="form-control">
    </div>
    <div class="row mb-2">
      <div class="col-md-6">
        <label>Contact Numbers</label>
        <input type="text" class="form-control">
      </div>
      <div class="col-md-6">
        <label>Email Address</label>
        <input type="email" class="form-control">
      </div>
    </div>
    <div class="row mb-4">
      <div class="col-md-6">
        <label>Student's Signature Over Printed Name</label>
        <div class="signature-line"></div>
      </div>
      <div class="col-md-6">
        <label>Date</label>
        <div class="signature-line"></div>
      </div>
    </div>

    <!-- Section B -->
    <h6 class="section-title">B. To be filled-out by the REGISTRAR</h6>
    <p>This is to attest to the fact that on the basis of our records on file,</p>
    <div class="mb-2">
      <label>MR./MS.</label>
      <input type="text" class="form-control">
    </div>
    <p class="mb-1">a bonafide graduate student taking (PROGRAM):</p>
    <input type="text" class="form-control mb-2">
    <label>Major in</label>
    <input type="text" class="form-control mb-2">
    <p>Has satisfactorily completed all academic courses and submitted the following:</p>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="tor">
      <label class="form-check-label" for="tor">
        Transcript of Records
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="bcert">
      <label class="form-check-label" for="bcert">
        NSO Authenticated Birth Certificate
      </label>
    </div>
    <div class="row mt-3">
      <div class="col-md-6">
        <label>Registrar's Signature Over Printed Name</label>
        <div class="signature-line"></div>
      </div>
      <div class="col-md-6">
        <label>Date</label>
        <div class="signature-line"></div>
      </div>
    </div>

    <!-- Section C -->
    <h6 class="section-title">C. To be filled-out by the BUSINESS AFFAIRS OFFICE (BAO)</h6>
    <p>This is to certify that on the basis of our records on file, MR./MS.</p>
    <input type="text" class="form-control mb-2">
    <p>Has been cleared of all financial obligations which include tuition and other fees.</p>
    <div class="row">
      <div class="col-md-6">
        <label>VP-Finance Signature Over Printed Name</label>
        <div class="signature-line"></div>
      </div>
      <div class="col-md-6">
        <label>Date</label>
        <div class="signature-line"></div>
      </div>
    </div>

    <!-- Section D -->
    <h6 class="section-title">D. To be filled-out by the GRADUATE SCHOOL DEAN</h6>
    <p>On the basis of our records and certifications, the application of MR./MS.</p>
    <input type="text" class="form-control mb-2">
    <label>for Comprehensive Examination for the PROGRAM</label>
    <input type="text" class="form-control mb-2">
    <label>Major in</label>
    <input type="text" class="form-control mb-2">
    <label>Scheduled on</label>
    <input type="text" class="form-control mb-3">

    <div class="form-check">
      <input class="form-check-input" type="radio" name="status" id="approved">
      <label class="form-check-label" for="approved">APPROVED</label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="status" id="partiallyApproved">
      <label class="form-check-label" for="partiallyApproved">PARTIALLY APPROVED</label>
      <input type="text" class="form-control mt-1" placeholder="Condition/s">
    </div>
    <div class="form-check mb-3">
      <input class="form-check-input" type="radio" name="status" id="disapproved">
      <label class="form-check-label" for="disapproved">DISAPPROVED</label>
      <input type="text" class="form-control mt-1" placeholder="Reason/s">
    </div>

    <div class="row">
      <div class="col-md-6">
        <label>Dean's Signature Over Printed Name</label>
        <div class="signature-line"></div>
      </div>
      <div class="col-md-6">
        <label>Date</label>
        <div class="signature-line"></div>
      </div>
    </div>
  </form>

