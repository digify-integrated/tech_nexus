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
        $createdByDepartmentID = $createdByEmploymentDetails['department_id'] ?? null;
        $createdByJobPositionID = $createdByEmploymentDetails['job_position_id'] ?? null;

        $createdByDepartmentName = $departmentModel->getDepartment($createdByDepartmentID)['department_name'] ?? null;
        $createdByJobPositionName = $jobPositionModel->getJobPosition($createdByJobPositionID)['job_position_name'] ?? null;

        // Checked By
        
        $checkedBy = $travelFormDetails['checked_by'];
        $checkedByName = !empty($checkedBy) ? ($employeeModel->getPersonalInformation($checkedBy)['file_as'] ?? '--') : '--';

        $checkedByEmploymentDetails = $employeeModel->getEmploymentInformation($checkedBy);
        $checkedByDepartmentID = $checkedByEmploymentDetails['department_id'] ?? null;
        $checkedByJobPositionID = $checkedByEmploymentDetails['job_position_id'] ?? null;
        
        $checkedByJobPositionName = $jobPositionModel->getJobPosition($checkedByJobPositionID)['job_position_name'] ?? null;

         // Recommended By
        
         $recommendedBy = $travelFormDetails['recommended_by'];
         $recommendedByName = !empty($recommendedBy) ? ($employeeModel->getPersonalInformation($recommendedBy)['file_as'] ?? '--') : '--';

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
    $pdf = new TCPDF('L', 'mm', array(215.9, 330.2), true, 'UTF-8', false);

   // Disable header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetPageOrientation('L');

    // Set PDF metadata
    $pdf->SetCreator('CGMI');
    $pdf->SetAuthor('CGMI');
    $pdf->SetTitle('PDC Print');
    $pdf->SetSubject('PDC Print');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    $pdf->SetFont('times', '', 10.5);
    $pdf->Cell(20, 8, 'Name:', 0, 0, 'L');
    $pdf->Cell(10, 8, '', 0, 0, 'L');
    $pdf->Cell(100, 8, strtoupper($createdByName), 'B', 0, 'L');
    $pdf->Cell(40, 4, ' ', 0, 0 , 'L');
    $pdf->Cell(20, 8, 'Date:', 0, 0, 'L');
    $pdf->Cell(10, 8, '', 0, 0, 'L');
    $pdf->Cell(100, 8, date('F d, Y'), 'B', 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(20, 8, 'Position:', 0, 0, 'L');
    $pdf->Cell(10, 8, '', 0, 0, 'L');
    $pdf->Cell(100, 8, strtoupper($createdByJobPositionName), 'B', 0, 'L');
    $pdf->Cell(40, 4, ' ', 0, 0 , 'L');
    $pdf->Cell(20, 8, 'Department:', 0, 0, 'L');
    $pdf->Cell(10, 8, '', 0, 0, 'L');
    $pdf->Cell(100, 8, strtoupper($createdByDepartmentName), 'B', 0, 'L');
    $pdf->Ln(15);

    
    $pdf->SetFont('times', '', 10.5);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');
    $pdf->Ln(0);
    $pdf->Cell(60, 8, 'Prepared By:', 0, 0, 'L');
    $pdf->Cell(20, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(60, 8, 'Checked By:', 0, 0, 'L');
    $pdf->Cell(20, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(60, 8, 'Recommended By:', 0, 0, 'L');
    $pdf->Cell(20, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(60, 8, 'Approved By:', 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(60, 8, strtoupper($createdByName), 0, 0, 'L');
    $pdf->Cell(20, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(60, 8, strtoupper($checkedByName), 0, 0, 'L');
    $pdf->Cell(20, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(60, 8, strtoupper($recommendedByName), 0, 0, 'L');
    $pdf->Cell(20, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(60, 8, strtoupper($approvalByName), 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(60, 8, 'Printed name of Vehicle User', 0, 0, 'L');
    $pdf->Cell(20, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(60, 8, 'Department Supervisor', 0, 0, 'L');
    $pdf->Cell(20, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(60, 8, 'Department Head', 0, 0, 'L');
    $pdf->Cell(20, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(60, 8, 'Operations & Finance Head', 0, 0, 'L');


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
        require_once 'model/customer-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $travelFormModel = new TravelFormModel($databaseModel);
        $employeeModel = new EmployeeModel($databaseModel);
        $departmentModel = new DepartmentModel($databaseModel);
        $jobPositionModel = new JobPositionModel($databaseModel);
        $customerModel = new CustomerModel($databaseModel);
        
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
        $createdByMobile = $createdByContactInformationDetails['mobile'] ?? null;

        // Checked By
        
        $checkedBy = $travelFormDetails['checked_by'];
        $checkedByName = !empty($checkedBy) ? ($employeeModel->getPersonalInformation($checkedBy)['file_as'] ?? '--') : '--';

        $checkedByEmploymentDetails = $employeeModel->getEmploymentInformation($checkedBy);
        $checkedByDepartmentID = $checkedByEmploymentDetails['department_id'] ?? null;
        $checkedByJobPositionID = $checkedByEmploymentDetails['job_position_id'] ?? null;

        $checkedByDepartmentName = $departmentModel->getDepartment($checkedByDepartmentID)['department_name'] ?? null;
        $checkedByJobPositionName = $jobPositionModel->getJobPosition($checkedByJobPositionID)['job_position_name'] ?? null;
      
        // Recommended By
        
        $recommendedBy = $travelFormDetails['recommended_by'];
        $recommendedByName = !empty($recommendedBy) ? ($employeeModel->getPersonalInformation($recommendedBy)['file_as'] ?? '--') : '--';

        // Approval By

        $approvalBy = $travelFormDetails['approval_by'];
        $approvalByName = !empty($approvalBy) ? ($employeeModel->getPersonalInformation($approvalBy)['file_as'] ?? '--') : '--';

        $approvalByEmploymentDetails = $employeeModel->getEmploymentInformation($approvalBy);
        $approvalByDepartmentID = $approvalByEmploymentDetails['department_id'] ?? null;
        $approvalByJobPositionID = $approvalByEmploymentDetails['job_position_id'] ?? null;

        $approvalByDepartmentName = $departmentModel->getDepartment($approvalByDepartmentID)['department_name'] ?? null;
        $approvalByJobPositionName = $jobPositionModel->getJobPosition($approvalByJobPositionID)['job_position_name'] ?? null;
        

        $itineraryDetails = $travelFormModel->getItineraryByTravelForm($travelFormID);

        $itineraryRow = '';
        foreach ($itineraryDetails as $row) {
            $itineraryDate = $systemModel->checkDate('empty', $row['itinerary_date'] ?? null, '', 'M d, Y', '');
            $customerID = $row['customer_id'] ?? null;
            $itineraryDestination = $row['itinerary_destination'] ?? null;
            $itineraryPurpose = $row['itinerary_purpose'] ?? null;
            $expectedTimeOfDeparture = $systemModel->checkDate('empty', $row['expected_time_of_departure'] ?? null, '', 'h:i a', '');
            $expectedTimeOfArrival = $systemModel->checkDate('empty', $row['expected_time_of_arrival'] ?? null, '', 'h:i a', '');

            $customerDetails = $customerModel->getPersonalInformation($customerID);
            $customerName = strtoupper($customerDetails['file_as'] ?? null);

            $itineraryRow .= '
                        <tr>
                            <td>'. $itineraryDate .'</td>
                            <td>'. $customerName .'</td>
                            <td>'. $itineraryDestination .'</td>
                            <td>'. $itineraryPurpose .'</td>
                            <td>'. $expectedTimeOfDeparture .'</td>
                            <td>'. $expectedTimeOfArrival .'</td>
                        </tr>
            ';

            
        }

        $travelAuthorizationDetails = $travelFormModel->getTravelAuthorization($travelFormID);
            $destination = $travelAuthorizationDetails['destination'] ?? null;
            $modeOfTransportation = $travelAuthorizationDetails['mode_of_transportation'] ?? null;
            $purposeOfTravel = $travelAuthorizationDetails['purpose_of_travel'] ?? null;
            $authorizationDepartureDate = $systemModel->checkDate('empty', $travelAuthorizationDetails['authorization_departure_date'] ?? null, '', 'M d, Y', '');
            $authorizationReturnDate = $systemModel->checkDate('empty', $travelAuthorizationDetails['authorization_return_date'] ?? null, '', 'M d, Y', '');
            $accomodationDetails = $travelAuthorizationDetails['accomodation_details'] ?? null;
            $tollFee = $travelAuthorizationDetails['toll_fee'] ?? null;
            $accomodation = $travelAuthorizationDetails['accomodation'] ?? null;
            $meals = $travelAuthorizationDetails['meals'] ?? null;
            $otherExpenses = $travelAuthorizationDetails['other_expenses'] ?? null;
            $totalEstimatedCost = $tollFee + $accomodation + $meals + $otherExpenses;
            $additionalComments = $travelAuthorizationDetails['additional_comments'] ?? null;

            $travelFormDetails = $travelFormModel->getGatePass($travelFormID);
            $nameOfDriver = $travelFormDetails['name_of_driver'] ?? null;
            $contactNumber = $travelFormDetails['contact_number'] ?? null;
            $vehicleType = $travelFormDetails['vehicle_type'] ?? null;
            $plateNumber = $travelFormDetails['plate_number'] ?? null;
            $departmentID = $travelFormDetails['department_id'] ?? null;
            $odometerReading = $travelFormDetails['odometer_reading'] ?? null;
            $purposeOfEntryExit = $travelFormDetails['purpose_of_entry_exit'] ?? null;
            $remarks = $travelFormDetails['remarks'] ?? null;
            $gatePassDepartureDate = $systemModel->checkDate('empty', $travelFormDetails['gate_pass_departure_date'] ?? null, '', 'm/d/Y', '');

            $departmentName = $departmentModel->getDepartment($departmentID)['department_name'] ?? null;

            $itineraryRow .= '
                            <tr>
                                <td colspan="3" style="text-align: center"><b>Travel Authorization</b></td>
                                <td colspan="3" style="text-align: center"><b>Vehicle Gatepass</b></td>
                            </tr>
                            <tr>
                                <td colspan="2">Mode of Transportation:</td>
                                <td>'. strtoupper($modeOfTransportation) .'</td>
                                <td colspan="2">Name of Driver :</td>
                                <td>'. strtoupper($nameOfDriver) .'</td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>Estimated Expenses:</b></td>
                                <td></td>
                                <td colspan="2">Return Date:</td>
                                <td>'. strtoupper($authorizationReturnDate) .'</td>
                            </tr>
                            <tr>
                                <td colspan="2">Transportation (TOLL FEE)</td>
                                <td>'. number_format($tollFee, 2) .'</td>
                                <td colspan="2">Contact Number :</td>
                                <td>'. strtoupper($contactNumber) .'</td>
                            </tr>
                            <tr>
                                <td colspan="2">Accommodation:</td>
                                <td>'. number_format($accomodation, 2) .'</td>
                                <td colspan="2">Vehicle Type :</td>
                                <td>'. strtoupper($vehicleType) .'</td>
                            </tr>
                            <tr>
                                <td colspan="2">Meals:</td>
                                <td>'. number_format($meals, 2) .'</td>
                                <td colspan="2">Plate No. :</td>
                                <td>'. strtoupper($plateNumber) .'</td>
                            </tr>
                            <tr>
                                <td colspan="2">Other Expenses (Specify on Notes):</td>
                                <td>'. number_format($otherExpenses, 2) .'</td>
                                <td colspan="2">Department :</td>
                                <td>'. strtoupper($departmentName) .'</td>
                            </tr>
                            <tr>
                                <td colspan="2">Total Estimated Cost:</td>
                                <td>'. number_format($totalEstimatedCost, 2) .'</td>
                                <td colspan="2">Purpose of Entry/Exit :</td>
                                <td>'. strtoupper($purposeOfEntryExit) .'</td>
                            </tr>
                            <tr>
                                <td colspan="2">Accommodation Details (if applicable):</td>
                                <td>'. strtoupper($accomodationDetails) .'</td>
                                <td colspan="2">Odometer Reading :</td>
                                <td>'. strtoupper($odometerReading) .'</td>
                            </tr>
                            <tr>
                                <td colspan="3" rowspan="5"></td>
                                <td colspan="2">Gate In-charge/Security Officer :</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2">Date of Arrival :</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2">Odometer Reading:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2">Gate In-charge/Security Officer:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2">Remarks:</td>
                                <td>'. strtoupper($remarks) .'</td>
                            </tr>
                            ';

        $response = '<table border="0.5" width="100%" cellpadding="2" align="left">
                        <tbody>
                        <tr>
                            <td colspan="6" style="text-align: center"><b>ITINERARY AND TRAVEL ORDER</b></td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td>Name of Client</td>
                            <td>Destination</td>
                            <td>Purpose</td>
                            <td>Expected Time of Departure (ETD)</td>
                            <td>Expected Time of Arrival (ETA)</td>
                        </tr>
                        '. $itineraryRow .'
                    </tbody>
            </table>';

        return $response;
    }
?>