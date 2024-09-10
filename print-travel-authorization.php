<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Load TCPDF library
    require('assets/libs/tcpdf2/tcpdf.php');

    // Load required files
    require_once 'config/config.php';
    require_once 'session.php';
    require_once 'model/database-model.php';
    require_once 'model/pdc-management-model.php';
    require_once 'model/system-model.php';
    require_once 'model/user-model.php';
    require_once 'model/customer-model.php';
    require_once 'model/employee-model.php';
    require_once 'model/department-model.php';
    require_once 'model/job-position-model.php';
    require_once 'model/travel-form-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel(new DatabaseModel, new SystemModel);
    $travelFormModel = new TravelFormModel($databaseModel);
    $employeeModel = new EmployeeModel($databaseModel);
    $departmentModel = new DepartmentModel($databaseModel);
    $jobPositionModel = new JobPositionModel($databaseModel);

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: dashboard.php');
          exit;
        }
        
        $travelFormID = $_GET['id'];

        $travelFormDetails = $travelFormModel->getTravelForm($travelFormID);
        $createdBy = $travelFormDetails['created_by'];

        // Created By
        $contactDetails = $userModel->getContactByID($createdBy);
        $createdByContactID = $contactDetails['contact_id'] ?? null;
        $createdByName = !empty($createdByContactID) ? ($employeeModel->getPersonalInformation($createdByContactID)['file_as'] ?? '--') : '--';

        $createdByEmploymentDetails = $employeeModel->getEmploymentInformation($createdByContactID);
        $createdByJobPositionID = $createdByEmploymentDetails['job_position_id'] ?? null;

        $createdByJobPositionName = $jobPositionModel->getJobPosition($createdByJobPositionID)['job_position_name'] ?? null;


        // Checked By
        
        $checkedBy = $travelFormDetails['checked_by'];
        $checkedByName = !empty($checkedBy) ? ($employeeModel->getPersonalInformation($checkedBy)['file_as'] ?? '--') : '--';

        $checkedByEmploymentDetails = $employeeModel->getEmploymentInformation($checkedBy);
        $checkedByJobPositionID = $checkedByEmploymentDetails['job_position_id'] ?? null;

        $checkedByJobPositionName = $jobPositionModel->getJobPosition($checkedByJobPositionID)['job_position_name'] ?? null;

        // Approval By

        $approvalBy = $travelFormDetails['approval_by'];
        $approvalByName = !empty($approvalBy) ? ($employeeModel->getPersonalInformation($approvalBy)['file_as'] ?? '--') : '--';
  
        $approvalByEmploymentDetails = $employeeModel->getEmploymentInformation($approvalBy);
        $approvalByDepartmentID = $approvalByEmploymentDetails['department_id'] ?? null;
        $approvalByJobPositionID = $approvalByEmploymentDetails['job_position_id'] ?? null;
  
        $approvalByJobPositionName = $jobPositionModel->getJobPosition($approvalByJobPositionID)['job_position_name'] ?? null;
    }

    $summaryTable = generatePrint($travelFormID);

    ob_start();

    // Create TCPDF instance
    $pdf = new TCPDF('P', 'mm', array(330.2, 215.9), true, 'UTF-8', false);

   // Disable header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetPageOrientation('P');

    // Set PDF metadata
    $pdf->SetCreator('CGMI');
    $pdf->SetAuthor('CGMI');
    $pdf->SetTitle('PDC Print');
    $pdf->SetSubject('PDC Print');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    $pdf->SetFont('times', '', 10.5);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');

    $pdf->Ln(0);
    $pdf->Cell(90, 8, 'Prepared By:', 0, 0, 'L');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 8, 'Approved By:', 0, 0, 'L');
    $pdf->Ln(10);
    $pdf->Cell(90, 4, strtoupper($createdByName), 'B', 0 , 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 4, strtoupper($approvalByName), 'B', 0, 'C');
    $pdf->Ln(5);
    $pdf->Cell(90, 8, 'Signature over Printed Name/Date', 0, 0, 'L');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 8, 'Signature over Printed Name/Date', 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(90, 8, 'Position: ' . strtoupper($createdByJobPositionName), 0, 0, 'L');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 8, 'Position: ' . strtoupper($approvalByJobPositionName), 0, 0, 'L');

    $pdf->Ln(20);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');

    $pdf->Ln(0);
    $pdf->Cell(90, 8, 'Prepared By:', 0, 0, 'L');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 8, 'Approved By:', 0, 0, 'L');
    $pdf->Ln(10);
    $pdf->Cell(90, 4, strtoupper($createdByName), 'B', 0 , 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 4, strtoupper($approvalByName), 'B', 0, 'C');
    $pdf->Ln(5);
    $pdf->Cell(90, 8, 'Signature over Printed Name/Date', 0, 0, 'L');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 8, 'Signature over Printed Name/Date', 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(90, 8, 'Position: ' . strtoupper($createdByJobPositionName), 0, 0, 'L');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 8, 'Position: ' . strtoupper($approvalByJobPositionName), 0, 0, 'L');


    // Output the PDF to the browser
    $pdf->Output('pdc-print.pdf', 'I');
    ob_end_flush();

    function generatePrint($travelFormID){
        
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/user-model.php';
        require_once 'model/customer-model.php';
        require_once 'model/employee-model.php';
        require_once 'model/department-model.php';
        require_once 'model/job-position-model.php';
        require_once 'model/travel-form-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $travelFormModel = new TravelFormModel($databaseModel);
        $employeeModel = new EmployeeModel($databaseModel);
        $departmentModel = new DepartmentModel($databaseModel);
        $jobPositionModel = new JobPositionModel($databaseModel);
        
        $travelFormDetails = $travelFormModel->getTravelForm($travelFormID);
        $createdBy = $travelFormDetails['created_by'];

        // Created By
        $contactDetails = $userModel->getContactByID($createdBy);
        $createdByContactID = $contactDetails['contact_id'] ?? null;
        $createdByName = !empty($createdByContactID) ? ($employeeModel->getPersonalInformation($createdByContactID)['file_as'] ?? '--') : '--';

        $createdByEmploymentDetails = $employeeModel->getEmploymentInformation($createdByContactID);
        $createdByDepartmentID = $createdByEmploymentDetails['department_id'] ?? null;
        $createdByJobPositionID = $createdByEmploymentDetails['job_position_id'] ?? null;
        $createdByBadgeID = $createdByEmploymentDetails['badge_id'] ?? null;

        $createdByDepartmentName = $departmentModel->getDepartment($createdByDepartmentID)['department_name'] ?? null;
        $createdByJobPositionName = $jobPositionModel->getJobPosition($createdByJobPositionID)['job_position_name'] ?? null;

        $createdByContactInformationDetails = $employeeModel->getEmployeePrimaryContactInformation($createdByContactID);
        $createdByMobile = $createdByContactInformationDetails['mobile'];

        // Checked By
        
        $checkedBy = $travelFormDetails['checked_by'];
        $checkedByName = !empty($checkedBy) ? ($employeeModel->getPersonalInformation($checkedBy)['file_as'] ?? '--') : '--';

        $checkedByEmploymentDetails = $employeeModel->getEmploymentInformation($checkedBy);
        $checkedByDepartmentID = $checkedByEmploymentDetails['department_id'] ?? null;
        $checkedByJobPositionID = $checkedByEmploymentDetails['job_position_id'] ?? null;

        $checkedByDepartmentName = $departmentModel->getDepartment($checkedByDepartmentID)['department_name'] ?? null;
        $checkedByJobPositionName = $jobPositionModel->getJobPosition($checkedByJobPositionID)['job_position_name'] ?? null;
      
        // Approval By

        $approvalBy = $travelFormDetails['approval_by'];
        $approvalByName = !empty($approvalBy) ? ($employeeModel->getPersonalInformation($approvalBy)['file_as'] ?? '--') : '--';

        $approvalByEmploymentDetails = $employeeModel->getEmploymentInformation($approvalBy);
        $approvalByDepartmentID = $approvalByEmploymentDetails['department_id'] ?? null;
        $approvalByJobPositionID = $approvalByEmploymentDetails['job_position_id'] ?? null;

        $approvalByDepartmentName = $departmentModel->getDepartment($approvalByDepartmentID)['department_name'] ?? null;
        $approvalByJobPositionName = $jobPositionModel->getJobPosition($approvalByJobPositionID)['job_position_name'] ?? null;
        
        $travelAuthorizationDetails = $travelFormModel->getTravelAuthorization($travelFormID);
        $destination = $travelAuthorizationDetails['destination'];
        $modeOfTransportation = $travelAuthorizationDetails['mode_of_transportation'];
        $purposeOfTravel = $travelAuthorizationDetails['purpose_of_travel'];
        $authorizationDepartureDate = $systemModel->checkDate('empty', $travelAuthorizationDetails['authorization_departure_date'] ?? null, '', 'M d, Y', '');
        $authorizationReturnDate = $systemModel->checkDate('empty', $travelAuthorizationDetails['authorization_return_date'] ?? null, '', 'M d, Y', '');
        $accomodationDetails = $travelAuthorizationDetails['accomodation_details'];
        $tollFee = $travelAuthorizationDetails['toll_fee'];
        $accomodation = $travelAuthorizationDetails['accomodation'];
        $meals = $travelAuthorizationDetails['meals'];
        $otherExpenses = $travelAuthorizationDetails['other_expenses'];
        $totalEstimatedCost = $tollFee + $accomodation + $meals + $otherExpenses;
        $additionalComments = $travelAuthorizationDetails['additional_comments'];

        $response = '<table border="0.5" width="100%" cellpadding="2" align="left">
                        <tbody>
                        <tr>
                            <td colspan="4" style="text-align: center"><b>Official Business Form - Travel Authorization</b></td>
                        </tr>
                        <tr>
                            <td>Full Name:</td>
                            <td>'. strtoupper($createdByName) .'</td>
                            <td>Employee ID:</td>
                            <td>'. strtoupper($createdByBadgeID) .'</td>
                        </tr>
                        <tr>
                            <td>Position/Title:</td>
                            <td>'. strtoupper($createdByJobPositionName) .'</td>
                            <td>Department:</td>
                            <td>'. strtoupper($createdByDepartmentName) .'</td>
                        </tr>
                        <tr>
                            <td>Destination:</td>
                            <td>'. strtoupper($destination) .'</td>
                            <td>Contact Number:</td>
                            <td>'. $createdByMobile .'</td>
                        </tr>
                        <tr>
                            <td>Purpose of Travel:</td>
                            <td>'. strtoupper($purposeOfTravel) .'</td>
                            <td>Departure Date:</td>
                            <td>'. strtoupper($authorizationDepartureDate) .'</td>
                        </tr>
                        <tr>
                            <td>Mode of Transportation:</td>
                            <td>'. strtoupper($modeOfTransportation) .'</td>
                            <td>Return Date:</td>
                            <td>'. strtoupper($authorizationReturnDate) .'</td>
                        </tr>
                        <tr>
                            <td colspan="2">Accommodation Details (if applicable):</td>
                            <td colspan="2">'. strtoupper($accomodationDetails) .'</td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Estimated Expenses:</b></td>
                            <td colspan="2"><b>Additional Comments/Notes :</b></td>
                        </tr>
                        <tr>
                            <td>Transportation (TOLL FEE)</td>
                            <td>'. number_format($tollFee, 2) .'</td>
                            <td colspan="2" rowspan="5"></td>
                        </tr>
                        <tr>
                            <td>Accommodation:</td>
                            <td>'. number_format($accomodation, 2) .'</td>
                        </tr>
                        <tr>
                            <td>Meals:</td>
                            <td>'. number_format($meals, 2) .'</td>
                        </tr>
                        <tr>
                            <td>Other Expenses (Specify on Notes):</td>
                            <td>'. number_format($otherExpenses, 2) .'</td>
                        </tr>
                        <tr>
                            <td>Total Estimated Cost:</td>
                            <td>'. number_format($totalEstimatedCost, 2) .'</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: center"><b>I hereby declare that the details provided in this form are accurate and that the travel is essential for official business purposes.</b></td>
                        </tr>
                    </tbody>
            </table>';

        return $response;
    }
?>