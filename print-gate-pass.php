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

    $pdf->SetFont('times', '', 9);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');

    $pdf->Ln(10);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');

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
        

        $travelFormDetails = $travelFormModel->getGatePass($travelFormID);
        $nameOfDriver = $travelFormDetails['name_of_driver'];
        $contactNumber = $travelFormDetails['contact_number'];
        $vehicleType = $travelFormDetails['vehicle_type'];
        $plateNumber = $travelFormDetails['plate_number'];
        $departmentID = $travelFormDetails['department_id'];
        $odometerReading = $travelFormDetails['odometer_reading'];
        $purposeOfEntryExit = $travelFormDetails['purpose_of_entry_exit'];
        $remarks = $travelFormDetails['remarks'];
        $gatePassDepartureDate = $systemModel->checkDate('empty', $travelFormDetails['gate_pass_departure_date'] ?? null, '', 'm/d/Y', '');

        $departmentName = $departmentModel->getDepartment($departmentID)['department_name'] ?? null;

        $pdcData = array();

        $response = '<table border="0.5" width="100%" cellpadding="2" align="left">
                        <tbody>
                        <tr>
                            <td colspan="2" style="text-align: center"><b>VEHICLE GATE PASS</b></td>
                        </tr>
                        <tr>
                            <td>Date & Time: 	 </td>
                            <td>Period Covered of Use: </td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>This gate pass is issued to:</b></td>
                        </tr>
                        <tr>
                            <td>Name of Driver :</td>
                            <td>'. strtoupper($nameOfDriver) .'</td>
                        </tr>
                        <tr>
                            <td>Contact Number :</td>
                            <td>'. strtoupper($contactNumber) .'</td>
                        </tr>
                        <tr>
                            <td>Vehicle Type :</td>
                            <td>'. strtoupper($vehicleType) .'</td>
                        </tr>
                        <tr>
                            <td>Plate No. :</td>
                            <td>'. strtoupper($plateNumber) .'</td>
                        </tr>
                        <tr>
                            <td>Department :</td>
                            <td>'. strtoupper($departmentName) .'</td>
                        </tr>
                        <tr>
                            <td>Purpose of Entry/Exit :</td>
                            <td>'. strtoupper($purposeOfEntryExit) .'</td>
                        </tr>
                        <tr>
                            <td>Odometer Reading :</td>
                            <td>'. strtoupper($odometerReading) .'</td>
                        </tr>
                        <tr>
                            <td>Gate In-charge/Security Officer :</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Date of Arrival :</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Odometer Reading :</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Gate In-charge/Security Officer :</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Remarks :</td>
                            <td>'. strtoupper($remarks) .'</td>
                        </tr>
                        <tr>
                            <td style="border-right: 1px solid white;">Requested By :<br/><br/><br/>'. strtoupper($createdByName) .'<br/>'. strtoupper($createdByJobPositionName) .'</td>
                            <td style="border-left: 1px solid white;">Approved By :<br/><br/><br/>'. strtoupper($approvalByName) .'<br/>'. strtoupper($approvalByJobPositionName) .'</td>
                        </tr>
                        <tr>
                            <td colspan="2">Instructions :</td>
                        </tr>
                        <tr style="font-size:8px;">
                            <td>1.	This gate pass is valid only for the specified date
                            and purpose mentioned above.<br/>
                            2.	Return this pass upon exit.<br/>
                            3.	Any misuse or unauthorized transfer of this pass is strictly prohibited.<br/>
                            4.	The vehicle must adhere to all safety and speed
                            regulations within the premises.
                            </td>
                            <td><i>Note: This gate pass is the property of Christian General Motors, Inc. and must be surrendered to the security personnel upon completion of the intended purpose. Thank you for your cooperation.<br/><br/>

                            Christian General Motors, Inc. 0915-401-1473</i>
                            </td>
                        </tr>
                    </tbody>
            </table>';

        return $response;
    }
?>