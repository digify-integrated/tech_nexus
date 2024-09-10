<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/customer-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/employee-model.php';
require_once '../model/travel-form-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$employeeModel = new EmployeeModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$travelFormModel = new TravelFormModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: travel form table
        # Description:
        # Generates the travel form table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'travel form table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateTravelFormTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $travelFormID = $row['travel_form_id'];
                $checkedBy = $row['checked_by'];
                $checkedDate = $systemModel->checkDate('empty', $row['checked_date'], '', 'm/d/Y', '');
                $recommendedBy = $row['recommended_by'];
                $recommendedDate = $systemModel->checkDate('empty', $row['recommended_date'], '', 'm/d/Y', '');
                $approvalBy = $row['approval_by'];
                $approvalDate = $systemModel->checkDate('empty', $row['approval_date'], '', 'm/d/Y', '');
                $travelFormStatus = $row['travel_form_status'];
                $createdBy = $row['created_by'];

                $checkedByName = !empty($checkedBy) ? ($employeeModel->getPersonalInformation($checkedBy)['file_as'] ?? '--') : '--';

                $recommendedByName = !empty($recommendedBy) ? ($employeeModel->getPersonalInformation($recommendedBy)['file_as'] ?? '--') : '--';

                $approvalByName = !empty($approvalBy) ? ($employeeModel->getPersonalInformation($approvalBy)['file_as'] ?? '--') : '--';

                $createdByDetails = $userModel->getUserByID($createdBy);
                $createdByName = $createdByDetails['file_as'] ?? null;
                
                $statusClasses = [
                    'Draft' => 'info',
                    'For Checking' => 'warning',
                    'Checked' => 'success',
                    'For Recommendation' => 'warning',
                    'Recommended' => 'success',
                    'For Approval' => 'warning',
                    'Approved' => 'success'
                ];
                
                $defaultClass = 'dark';
                
                $class = $statusClasses[$travelFormStatus] ?? $defaultClass;
                
                $badge = '<span class="badge bg-' . $class . '">' . $travelFormStatus . '</span>';

                $travelFormIDEncrypted = $securityModel->encryptData($travelFormID);

                $response[] = [
                    'CREATED_BY' => $createdByName,
                    'CHECKED_BY' => $checkedByName,
                    'CHECKED_DATE' => $checkedDate,
                    'RECOMMENDED_BY' => $recommendedByName,
                    'RECOMMENDED_DATE' => $recommendedDate,
                    'APPROVAL_BY' => $approvalByName,
                    'APPROVAL_DATE' => $approvalDate,
                    'STATUS' => $badge,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="travel-form.php?id='. $travelFormIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: travel form table
        # Description:
        # Generates the travel form table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'travel approval form table':
            $contactID = $_SESSION['contact_id'];
            $sql = $databaseModel->getConnection()->prepare('CALL generateTravelApprovalFormTable(:contactID)');
            $sql->bindValue(':contactID', $contactID, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $travelFormID = $row['travel_form_id'];
                $checkedBy = $row['checked_by'];
                $checkedDate = $systemModel->checkDate('empty', $row['checked_date'], '', 'm/d/Y', '');
                $recommendedBy = $row['recommended_by'];
                $recommendedDate = $systemModel->checkDate('empty', $row['recommended_date'], '', 'm/d/Y', '');
                $approvalBy = $row['approval_by'];
                $approvalDate = $systemModel->checkDate('empty', $row['approval_date'], '', 'm/d/Y', '');
                $travelFormStatus = $row['travel_form_status'];
                $createdBy = $row['created_by'];

                $checkedByName = !empty($checkedBy) ? ($employeeModel->getPersonalInformation($checkedBy)['file_as'] ?? '--') : '--';

                $recommendedByName = !empty($recommendedBy) ? ($employeeModel->getPersonalInformation($recommendedBy)['file_as'] ?? '--') : '--';

                $approvalByName = !empty($approvalBy) ? ($employeeModel->getPersonalInformation($approvalBy)['file_as'] ?? '--') : '--';

                $createdByDetails = $userModel->getUserByID($createdBy);
                $createdByName = $createdByDetails['file_as'] ?? null;

                $statusClasses = [
                    'Draft' => 'info',
                    'For Checking' => 'warning',
                    'Checked' => 'success',
                    'For Recommendation' => 'warning',
                    'Recommended' => 'success',
                    'For Approval' => 'warning',
                    'Approved' => 'success'
                ];
                
                $defaultClass = 'dark';
                
                $class = $statusClasses[$travelFormStatus] ?? $defaultClass;
                
                $badge = '<span class="badge bg-' . $class . '">' . $travelFormStatus . '</span>';

                $travelFormIDEncrypted = $securityModel->encryptData($travelFormID);

                $response[] = [
                    'CREATED_BY' => $createdByName,
                    'CHECKED_BY' => $checkedByName,
                    'CHECKED_DATE' => $checkedDate,
                    'RECOMMENDED_BY' => $recommendedByName,
                    'RECOMMENDED_DATE' => $recommendedDate,
                    'APPROVAL_BY' => $approvalByName,
                    'APPROVAL_DATE' => $approvalDate,
                    'STATUS' => $badge,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="travel-form.php?id='. $travelFormIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: itinerary table
        # Description:
        # Generates the itinerary table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'itinerary table':            
            $travelFormID = $_POST['travel_form_id'];

            $travelFormDetails = $travelFormModel->getTravelForm($travelFormID);
            $travelFormStatus = $travelFormDetails['travel_form_status'];            

            $sql = $databaseModel->getConnection()->prepare('CALL generateItineraryTable(:travelFormID)');
            $sql->bindValue(':travelFormID', $travelFormID, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $itineraryID = $row['itinerary_id'];
                $itineraryDate = $systemModel->checkDate('empty', $row['itinerary_date'], '', 'm/d/Y', '');
                $customerID = $row['customer_id'];
                $itineraryDestination = $row['itinerary_destination'];
                $itineraryPurpose = $row['itinerary_purpose'];
                $expectedTimeOfDeparture = $systemModel->checkDate('empty', $row['expected_time_of_departure'], '', 'h:i a', '');
                $expectedTimeOfArrival = $systemModel->checkDate('empty', $row['expected_time_of_arrival'], '', 'h:i a', '');

                $customerDetails = $customerModel->getPersonalInformation($customerID);
                $customerName = $customerDetails['file_as'] ?? null;

                $action = '';
                if($travelFormStatus == 'Draft'){
                    $action = '<div class="d-flex gap-2">
                        <button type="button" class="btn btn-icon btn-success update-itinerary" data-bs-toggle="offcanvas" data-bs-target="#itinerary-offcanvas" aria-controls="itinerary-offcanvas" data-itinerary-id="'. $itineraryID .'" title="Update Itinerary">
                            <i class="ti ti-edit"></i>
                        </button>
                        <button type="button" class="btn btn-icon btn-danger delete-itinerary" data-itinerary-id="'. $itineraryID .'" title="Delete Itinerary">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>';
                }

                $response[] = [
                    'ITINERARY_DATE' => $itineraryDate,
                    'CUSTOMER_NAME' => $customerName,
                    'ITINERARY_DESTINATION' => $itineraryDestination,
                    'ITINERARY_PURPOSE' => $itineraryPurpose,
                    'EXPECTED_TIME_OF_DEPARTURE' => $expectedTimeOfDeparture,
                    'EXPECTED_TIME_OF_ARRIVAL' => $expectedTimeOfArrival,
                    'ACTION' => $action
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>