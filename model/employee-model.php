
<?php
/**
* Class EmployeeModel
*
* The EmployeeModel class handles employee related operations and interactions.
*/
class EmployeeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updatePersonalInformation
    # Description: Updates the personal information.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_file_as (string): The file as.
    # - $p_first_name (string): The first name.
    # - $p_middle_name (string): The middle name.
    # - $p_last_name (string): The last name.
    # - $p_suffix (string): The suffix.
    # - $p_nickname (string): The nickname.
    # - $p_bio (string): The bio.
    # - $p_civil_status_id (int): The civil status.
    # - $p_gender_id (int): The gender.
    # - $p_religion_id (int): The religion.
    # - $p_blood_type_id (int): The blood type.
    # - $p_birthday (date): The birthday.
    # - $p_birth_place (string): The birth place.
    # - $p_height (double): The height.
    # - $p_weight (double): The weight.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updatePersonalInformation($p_contact_id, $p_file_as, $p_first_name, $p_middle_name, $p_last_name, $p_suffix, $p_nickname, $p_corporate_name, $p_bio, $p_civil_status_id, $p_gender_id, $p_religion_id, $p_blood_type_id, $p_birthday, $p_birth_place, $p_height, $p_weight, $p_tin, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePersonalInformation(:p_contact_id, :p_file_as, :p_first_name, :p_middle_name, :p_last_name, :p_suffix, :p_nickname, :p_corporate_name, :p_bio, :p_civil_status_id, :p_gender_id, :p_religion_id, :p_blood_type_id, :p_birthday, :p_birth_place, :p_height, :p_weight, :p_tin, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_file_as', $p_file_as, PDO::PARAM_STR);
        $stmt->bindValue(':p_first_name', $p_first_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_middle_name', $p_middle_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_name', $p_last_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_suffix', $p_suffix, PDO::PARAM_STR);
        $stmt->bindValue(':p_nickname', $p_nickname, PDO::PARAM_STR);
        $stmt->bindValue(':p_corporate_name', $p_corporate_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_bio', $p_bio, PDO::PARAM_STR);
        $stmt->bindValue(':p_civil_status_id', $p_civil_status_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_gender_id', $p_gender_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_religion_id', $p_religion_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_blood_type_id', $p_blood_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_birthday', $p_birthday, PDO::PARAM_STR);
        $stmt->bindValue(':p_birth_place', $p_birth_place, PDO::PARAM_STR);
        $stmt->bindValue(':p_height', $p_height, PDO::PARAM_STR);
        $stmt->bindValue(':p_weight', $p_weight, PDO::PARAM_STR);
        $stmt->bindValue(':p_tin', $p_tin, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    # -------------------------------------------------------------
    public function archiveEmployee($p_contact_id, $p_offboard_date, $p_departure_reason_id , $p_detailed_departure_reason, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL archiveEmployee(:p_contact_id, :p_offboard_date, :p_departure_reason_id, :p_detailed_departure_reason, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_offboard_date', $p_offboard_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_departure_reason_id', $p_departure_reason_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_detailed_departure_reason', $p_detailed_departure_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateEmployeeImage
    # Description: Updates the employee image.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_contact_image (string): The image of the contact.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateEmployeeImage($p_contact_id, $p_contact_image, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactImage(:p_contact_id, :p_contact_image, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_image', $p_contact_image, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateEmploymentInformation
    # Description: Updates the employment information.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_badge_id (string): The badge ID.
    # - $p_company_id (int): The company ID.
    # - $p_employee_type_id (int): The employee type ID.
    # - $p_department_id (int): The department ID.
    # - $p_job_position_id (int): The job position ID.
    # - $p_job_level_id (int): The job level ID.
    # - $p_branch_id (int): The branch ID.
    # - $p_manager_id (int): The manager ID.
    # - $p_work_schedule_id (int): The work schedule ID.
    # - $p_kiosk_pin_code (string): The kiosk pin code.
    # - $p_biometrics_id (string): The biometrics ID.
    # - $p_onboard_date (date): The onboard date.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateEmploymentInformation($p_contact_id, $p_badge_id, $p_company_id, $p_employee_type_id, $p_department_id, $p_job_position_id, $p_job_level_id, $p_branch_id, $p_manager_id, $p_leave_approver_id, $p_work_schedule_id, $p_kiosk_pin_code, $p_biometrics_id, $p_onboard_date, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateEmploymentInformation (:p_contact_id, :p_badge_id, :p_company_id, :p_employee_type_id, :p_department_id, :p_job_position_id, :p_job_level_id, :p_branch_id, :p_manager_id, :p_leave_approver_id, :p_work_schedule_id, :p_kiosk_pin_code, :p_biometrics_id, :p_onboard_date, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_badge_id', $p_badge_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_employee_type_id', $p_employee_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_department_id', $p_department_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_job_position_id', $p_job_position_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_job_level_id', $p_job_level_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_branch_id', $p_branch_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_manager_id', $p_manager_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_leave_approver_id', $p_leave_approver_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_work_schedule_id', $p_work_schedule_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_kiosk_pin_code', $p_kiosk_pin_code, PDO::PARAM_STR);
        $stmt->bindValue(':p_biometrics_id', $p_biometrics_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_onboard_date', $p_onboard_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

     # -------------------------------------------------------------
    #
    # Function: updateContactInformation
    # Description: Updates the contact information.
    #
    # Parameters:
    # - $p_contact_information_id (int): The contact information ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_contact_information_type_id (int): The contact information type ID.
    # - $p_mobile (string): The mobile number.
    # - $p_telephone (string): The telephone number.
    # - $p_email (string): The email address.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactInformation($p_contact_information_id, $p_contact_id, $p_contact_information_type_id, $p_mobile, $p_telephone, $p_email, $p_facebook, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactInformation (:p_contact_information_id, :p_contact_id, :p_contact_information_type_id, :p_mobile, :p_telephone, :p_email, :p_facebook, :p_last_log_by)');
        $stmt->bindValue(':p_contact_information_id', $p_contact_information_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_information_type_id', $p_contact_information_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_mobile', $p_mobile, PDO::PARAM_STR);
        $stmt->bindValue(':p_telephone', $p_telephone, PDO::PARAM_STR);
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
        $stmt->bindValue(':p_facebook', $p_facebook, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContactInformationStatus
    # Description: Updates the contact information as primary.
    #
    # Parameters:
    # - $p_contact_information_id (int): The contact information ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactInformationStatus($p_contact_information_id, $p_contact_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactInformationStatus (:p_contact_information_id, :p_contact_id, :p_last_log_by)');
        $stmt->bindValue(':p_contact_information_id', $p_contact_information_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContactAddress
    # Description: Updates the contact address.
    #
    # Parameters:
    # - $p_contact_address_id (int): The contact address ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_address_type_id (int): The address type ID.
    # - $p_address (string): The address.
    # - $p_city_id (int): The city ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactAddress($p_contact_address_id, $p_contact_id, $p_address_type_id, $p_address, $p_city_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactAddress (:p_contact_address_id, :p_contact_id, :p_address_type_id, :p_address, :p_city_id, :p_last_log_by)');
        $stmt->bindValue(':p_contact_address_id', $p_contact_address_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_address_type_id', $p_address_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_address', $p_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_city_id', $p_city_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContactAddressStatus
    # Description: Updates the contact address as primary.
    #
    # Parameters:
    # - $p_contact_address_id (int): The contact address ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactAddressStatus($p_contact_address_id, $p_contact_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactAddressStatus (:p_contact_address_id, :p_contact_id, :p_last_log_by)');
        $stmt->bindValue(':p_contact_address_id', $p_contact_address_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

     # -------------------------------------------------------------
    #
    # Function: getEmployeePrimaryContactInformation
    # Description: Retrieves the details of a contact primary contact information.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    #
    # Returns:
    # - An array containing the bank.
    #
    # -------------------------------------------------------------
    public function getEmployeePrimaryContactInformation($p_contact_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getEmployeePrimaryContactInformation(:p_contact_id)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
    # -------------------------------------------------------------
    public function getEmployeeWorkContactInformation($p_contact_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getEmployeeWorkContactInformation(:p_contact_id)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
    #
    # -------------------------------------------------------------
    public function getEmployeeActiveWorkContactInformation() {
        $stmt = $this->db->getConnection()->prepare('CALL getEmployeeActiveWorkContactInformation()');
        $stmt->execute();
        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContactIdentification
    # Description: Updates the contact identification.
    #
    # Parameters:
    # - $p_contact_identification_id (int): The contact identification ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_id_type_id (int): The ID type ID.
    # - $p_id_number (string): The ID Number.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactIdentification($p_contact_identification_id, $p_contact_id, $p_id_type_id, $p_id_number, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactIdentification (:p_contact_identification_id, :p_contact_id, :p_id_type_id, :p_id_number, :p_last_log_by)');
        $stmt->bindValue(':p_contact_identification_id', $p_contact_identification_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_id_type_id', $p_id_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_id_number', $p_id_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContactIdentificationStatus
    # Description: Updates the contact identification as primary.
    #
    # Parameters:
    # - $p_contact_identification_id (int): The contact identification ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactIdentificationStatus($p_contact_identification_id, $p_contact_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactIdentificationStatus (:p_contact_identification_id, :p_contact_id, :p_last_log_by)');
        $stmt->bindValue(':p_contact_identification_id', $p_contact_identification_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContactEducationalBackground
    # Description: Updates the contact educational background.
    #
    # Parameters:
    # - $p_contact_educational_background_id (int): The educational background ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_educational_stage_id (int): The educational stage id.
    # - $p_institution_name (string): The institution name.
    # - $p_degree_earned (string): The degree earned.
    # - $p_field_of_study (string): The field of study.
    # - $p_start_month (string): The start month.
    # - $p_start_year (string): The start year.
    # - $p_end_month (string): The end month.
    # - $p_end_year (string): The end year.
    # - $p_course_highlights (string): The course highlights.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactEducationalBackground($p_contact_educational_background_id, $p_contact_id, $p_educational_stage_id, $p_institution_name, $p_degree_earned, $p_field_of_study, $p_start_month, $p_start_year, $p_end_month, $p_end_year, $p_course_highlights, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactEducationalBackground (:p_contact_educational_background_id, :p_contact_id, :p_educational_stage_id, :p_institution_name, :p_degree_earned, :p_field_of_study, :p_start_month, :p_start_year, :p_end_month, :p_end_year, :p_course_highlights, :p_last_log_by)');
        $stmt->bindValue(':p_contact_educational_background_id', $p_contact_educational_background_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_educational_stage_id', $p_educational_stage_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_institution_name', $p_institution_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_degree_earned', $p_degree_earned, PDO::PARAM_STR);
        $stmt->bindValue(':p_field_of_study', $p_field_of_study, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_month', $p_start_month, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_year', $p_start_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_end_month', $p_end_month, PDO::PARAM_STR);
        $stmt->bindValue(':p_end_year', $p_end_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_course_highlights', $p_course_highlights, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContactFamilyBackground
    # Description: Updates the contact family background.
    #
    # Parameters:
    # - $p_contact_family_background_id (int): The family background ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_family_name (string): The family name.
    # - $p_relation_id (int): The relation ID.
    # - $p_birthday (date): The birthday.
    # - $p_mobile (string): The mobile.
    # - $p_telephone (string): The telephone.
    # - $p_email (string): The email.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactFamilyBackground($p_contact_family_background_id, $p_contact_id, $p_family_name, $p_relation_id, $p_birthday, $p_mobile, $p_telephone, $p_email, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactFamilyBackground (:p_contact_family_background_id, :p_contact_id, :p_family_name, :p_relation_id, :p_birthday, :p_mobile, :p_telephone, :p_email, :p_last_log_by)');
        $stmt->bindValue(':p_contact_family_background_id', $p_contact_family_background_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_family_name', $p_family_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_relation_id', $p_relation_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_birthday', $p_birthday, PDO::PARAM_STR);
        $stmt->bindValue(':p_mobile', $p_mobile, PDO::PARAM_STR);
        $stmt->bindValue(':p_telephone', $p_telephone, PDO::PARAM_STR);
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContactEmergencyContact
    # Description: Updates the contact emergency contact.
    #
    # Parameters:
    # - $p_contact_emergency_contact_id (int): The emergency contact ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_emergency_contact_name (string): The emergency contact name.
    # - $p_relation_id (int): The relation ID.
    # - $p_mobile (string): The mobile.
    # - $p_telephone (string): The telephone.
    # - $p_email (string): The email.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactEmergencyContact($p_contact_emergency_contact_id, $p_contact_id, $p_emergency_contact_name, $p_relation_id, $p_mobile, $p_telephone, $p_email, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactEmergencyContact (:p_contact_emergency_contact_id, :p_contact_id, :p_emergency_contact_name, :p_relation_id, :p_mobile, :p_telephone, :p_email, :p_last_log_by)');
        $stmt->bindValue(':p_contact_emergency_contact_id', $p_contact_emergency_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_emergency_contact_name', $p_emergency_contact_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_relation_id', $p_relation_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_mobile', $p_mobile, PDO::PARAM_STR);
        $stmt->bindValue(':p_telephone', $p_telephone, PDO::PARAM_STR);
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContactTraining
    # Description: Updates the contact training.
    #
    # Parameters:
    # - $p_contact_training_id (int): The training ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_training_name (string): The training name.
    # - $p_training_date (date): The training date.
    # - $p_training_location (string): The training location.
    # - $p_training_provider (string): The training provider.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactTraining($p_contact_training_id, $p_contact_id, $p_training_name, $p_training_date, $p_training_location, $p_training_provider, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactTraining (:p_contact_training_id, :p_contact_id, :p_training_name, :p_training_date, :p_training_location, :p_training_provider, :p_last_log_by)');
        $stmt->bindValue(':p_contact_training_id', $p_contact_training_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_training_name', $p_training_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_training_date', $p_training_date, PDO::PARAM_INT);
        $stmt->bindValue(':p_training_location', $p_training_location, PDO::PARAM_STR);
        $stmt->bindValue(':p_training_provider', $p_training_provider, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContactSkills
    # Description: Updates the contact skills.
    #
    # Parameters:
    # - $p_contact_skills_id (int): The skills ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_skill_name (string): The skill name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactSkills($p_contact_skills_id, $p_contact_id, $p_skill_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactSkills (:p_contact_skills_id, :p_contact_id, :p_skill_name, :p_last_log_by)');
        $stmt->bindValue(':p_contact_skills_id', $p_contact_skills_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_skill_name', $p_skill_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContactTalents
    # Description: Updates the contact talents.
    #
    # Parameters:
    # - $p_contact_talents_id (int): The talents ID.
    # - $p_talent_name (string): The talent name.
    # - $p_contact_id (int): The contact ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactTalents($p_contact_talents_id, $p_contact_id, $p_talent_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactTalents (:p_contact_talents_id, :p_contact_id, :p_talent_name, :p_last_log_by)');
        $stmt->bindValue(':p_contact_talents_id', $p_contact_talents_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_talent_name', $p_talent_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContactHobby
    # Description: Updates the contact hobby.
    #
    # Parameters:
    # - $p_contact_hobby_id (int): The hobby ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_hobby_name (string): The hobby name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactHobby($p_contact_hobby_id, $p_contact_id, $p_hobby_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactHobby (:p_contact_hobby_id, :p_contact_id, :p_hobby_name, :p_last_log_by)');
        $stmt->bindValue(':p_contact_hobby_id', $p_contact_hobby_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_hobby_name', $p_hobby_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContactEmploymentHistory
    # Description: Updates the contact employment history.
    #
    # Parameters:
    # - $p_contact_employment_history_id (int): The employment history ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_company (string): The company name.
    # - $p_address (string): The company address.
    # - $p_last_position_held (string): The last position held.
    # - $p_start_month (string): The start month.
    # - $p_start_year (string): The start year.
    # - $p_end_month (string): The end month.
    # - $p_end_year (string): The end year.
    # - $p_basic_function (date): The basic function.
    # - $p_starting_salary (double): The starting salary.
    # - $p_final_salary (double): The final salary.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactEmploymentHistory($p_contact_employment_history_id, $p_contact_id, $p_company, $p_address, $p_last_position_held, $p_start_month, $p_start_year, $p_end_month, $p_end_year, $p_basic_function, $p_starting_salary, $p_final_salary, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactEmploymentHistory (:p_contact_employment_history_id, :p_contact_id, :p_company, :p_address, :p_last_position_held, :p_start_month, :p_start_year, :p_end_month, :p_end_year, :p_basic_function, :p_starting_salary, :p_final_salary, :p_last_log_by)');
        $stmt->bindValue(':p_contact_employment_history_id', $p_contact_employment_history_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_company', $p_company, PDO::PARAM_STR);
        $stmt->bindValue(':p_address', $p_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_position_held', $p_last_position_held, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_month', $p_start_month, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_year', $p_start_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_end_month', $p_end_month, PDO::PARAM_STR);
        $stmt->bindValue(':p_end_year', $p_end_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_basic_function', $p_basic_function, PDO::PARAM_STR);
        $stmt->bindValue(':p_starting_salary', $p_starting_salary, PDO::PARAM_STR);
        $stmt->bindValue(':p_final_salary', $p_final_salary, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContactLicense
    # Description: Updates the contact license.
    #
    # Parameters:
    # - $p_contact_license_id (int): The license ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_license_name (string): The license name.
    # - $issuing_organization (string): The issuing organization.
    # - $p_start_month (string): The start month.
    # - $p_start_year (string): The start year.
    # - $p_end_month (string): The end month.
    # - $p_end_year (string): The end year.
    # - $p_description (string): The description.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactLicense($p_contact_license_id, $p_contact_id, $p_license_name, $issuing_organization, $p_start_month, $p_start_year, $p_end_month, $p_end_year, $p_description, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactLicense (:p_contact_license_id, :p_contact_id, :p_license_name, :issuing_organization, :p_start_month, :p_start_year, :p_end_month, :p_end_year, :p_description, :p_last_log_by)');
        $stmt->bindValue(':p_contact_license_id', $p_contact_license_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_license_name', $p_license_name, PDO::PARAM_STR);
        $stmt->bindValue(':issuing_organization', $issuing_organization, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_month', $p_start_month, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_year', $p_start_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_end_month', $p_end_month, PDO::PARAM_STR);
        $stmt->bindValue(':p_end_year', $p_end_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_description', $p_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContactLanguage
    # Description: Updates the contact language.
    #
    # Parameters:
    # - $p_contact_language_id (int): The language ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_language_id (int): The language ID.
    # - $p_language_proficiency_id (int): The language proficiency ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactLanguage($p_contact_language_id, $p_contact_id, $p_language_id, $p_language_proficiency_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactLanguage (:p_contact_language_id, :p_contact_id, :p_language_id, :p_language_proficiency_id, :p_last_log_by)');
        $stmt->bindValue(':p_contact_language_id', $p_contact_language_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_language_id', $p_language_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_language_proficiency_id', $p_language_proficiency_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContactBank
    # Description: Updates the contact bank.
    #
    # Parameters:
    # - $p_contact_bank_id (int): The bank ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_bank_id (int): The bank ID.
    # - $bank_account_type_id (int): The bank account type ID.
    # - $p_account_number (string): The account number.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactBank($p_contact_bank_id, $p_contact_id, $p_bank_id, $bank_account_type_id, $p_account_number, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactBank (:p_contact_bank_id, :p_contact_id, :p_bank_id, :bank_account_type_id, :p_account_number, :p_last_log_by)');
        $stmt->bindValue(':p_contact_bank_id', $p_contact_bank_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_bank_id', $p_bank_id, PDO::PARAM_INT);
        $stmt->bindValue(':bank_account_type_id', $bank_account_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_account_number', $p_account_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateRegularAttendanceExit
    # Description: Updates the regular attendance exit.
    #
    # Parameters:
    # - $p_attendance_id (int): The attendance ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_check_out_image (int): The check out image.
    # - $p_check_out (datetime): The date and time of check out.
    # - $p_check_out_location (string): The check out location.
    # - $p_check_out_by (string): The check out by.
    # - $p_check_out_notes (string): The check out note.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateRegularAttendanceExit($p_attendance_id, $p_contact_id, $p_check_out_image, $p_check_out, $p_check_out_location, $p_check_out_by, $p_check_out_notes, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateRegularAttendanceExit (:p_attendance_id, :p_contact_id, :p_check_out_image, :p_check_out, :p_check_out_location, :p_check_out_by, :p_check_out_notes, :p_last_log_by)');
        $stmt->bindValue(':p_attendance_id', $p_attendance_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_check_out_image', $p_check_out_image, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_out', $p_check_out, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_out_location', $p_check_out_location, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_out_by', $p_check_out_by, PDO::PARAM_INT);
        $stmt->bindValue(':p_check_out_notes', $p_check_out_notes, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateManualAttendanceEntry
    # Description: Updates the manual attendance exit.
    #
    # Parameters:
    # - $p_attendance_id (int): The attendance ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_check_in (datetime): The date and time of check in.
    # - $p_check_in_notes (string): The check in notes.
    # - $p_check_in_by (int): The check in by.
    # - $p_check_out (datetime): The date and time of check out.
    # - $p_check_out_notes (string): The check out notes.
    # - $p_check_out_by (int): The check out by.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateManualAttendanceEntry($p_attendance_id, $p_contact_id, $p_check_in, $p_check_in_notes, $p_check_in_by, $p_check_out, $p_check_out_notes, $p_check_out_by, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateManualAttendanceEntry (:p_attendance_id, :p_contact_id, :p_check_in, :p_check_in_notes, :p_check_in_by, :p_check_out, :p_check_out_notes, :p_check_out_by, :p_last_log_by)');
        $stmt->bindValue(':p_attendance_id', $p_attendance_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_check_in', $p_check_in, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_in_notes', $p_check_in_notes, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_in_by', $p_check_in_by, PDO::PARAM_INT);
        $stmt->bindValue(':p_check_out', $p_check_out, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_out_notes', $p_check_out_notes, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_out_by', $p_check_out_by, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateArrangedBiometricsAttendanceRecord
    # Description: Updates the arranged biometrics attendance record.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_check_out (datetime): The date and time of check out.
    # - $p_check_out_by (int): The check out by.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateArrangedBiometricsAttendanceRecord($p_contact_id, $p_check_out, $p_check_out_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateArrangedBiometricsAttendanceRecord (:p_contact_id, :p_check_out, :p_check_out_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_check_out', $p_check_out, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_out_by', $p_check_out_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContactIdentificationImage
    # Description: Updates the customer status.
    #
    # Parameters:
    # - $p_contact_identification_id (int): The contact ID.
    # - $p_id_image (string): The contact status.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactIdentificationImage($p_contact_identification_id, $p_id_image, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactIdentificationImage (:p_contact_identification_id, :p_id_image, :p_last_log_by)');
        $stmt->bindValue(':p_contact_identification_id', $p_contact_identification_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_id_image', $p_id_image, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContactDocumentFile
    # Description: Updates the customer status.
    #
    # Parameters:
    # - $p_contact_identification_id (int): The contact ID.
    # - $p_id_image (string): The contact status.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactDocumentFile($p_contact_document_id, $p_document_file, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactDocumentFile (:p_contact_document_id, :p_document_file, :p_last_log_by)');
        $stmt->bindValue(':p_contact_document_id', $p_contact_document_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_document_file', $p_document_file, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContactDocument
    # Description: Updates the customer status.
    #
    # Parameters:
    # - $p_contact_identification_id (int): The contact ID.
    # - $p_document_file (string): The contact status.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactDocument($p_contact_identification_id, $p_document_file, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactDocument (:p_contact_identification_id, :p_document_file, :p_last_log_by)');
        $stmt->bindValue(':p_contact_identification_id', $p_contact_identification_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_document_file', $p_document_file, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertEmployee
    # Description: Inserts the employee.
    #
    # Parameters:
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertEmployee($p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertEmployee(:p_last_log_by, @p_contact_id)');
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_contact_id AS p_contact_id");
        $p_contact_id = $result->fetch(PDO::FETCH_ASSOC)['p_contact_id'];

        return $p_contact_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPartialPersonalInformation
    # Description: Inserts the partial personal details.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_file_as (string): The file as.
    # - $p_first_name (string): The first name.
    # - $p_middle_name (string): The middle name.
    # - $p_last_name (string): The last name.
    # - $p_suffix (string): The suffix.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertPartialPersonalInformation($p_contact_id, $p_file_as, $p_first_name, $p_middle_name, $p_last_name, $p_suffix, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartialPersonalInformation(:p_contact_id, :p_file_as, :p_first_name, :p_middle_name, :p_last_name, :p_suffix, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_file_as', $p_file_as, PDO::PARAM_STR);
        $stmt->bindValue(':p_first_name', $p_first_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_middle_name', $p_middle_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_name', $p_last_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_suffix', $p_suffix, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPartialEmploymentInformation
    # Description: Inserts the partial employment details.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_company_id (int): The company ID.
    # - $p_department_id (int): The department ID.
    # - $p_job_position_id (int): The job position ID.
    # - $p_branch_id (int): The branch ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertPartialEmploymentInformation($p_contact_id, $p_company_id, $p_department_id, $p_job_position_id, $p_branch_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartialEmploymentInformation(:p_contact_id, :p_company_id, :p_department_id, :p_job_position_id, :p_branch_id, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_department_id', $p_department_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_job_position_id', $p_job_position_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_branch_id', $p_branch_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPersonalInformation
    # Description: Inserts the personal information.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_file_as (string): The file as.
    # - $p_first_name (string): The first name.
    # - $p_middle_name (string): The middle name.
    # - $p_last_name (string): The last name.
    # - $p_suffix (string): The suffix.
    # - $p_nickname (string): The nickname.
    # - $p_bio (string): The bio.
    # - $p_civil_status_id (int): The civil status.
    # - $p_gender_id (int): The gender.
    # - $p_religion_id (int): The religion.
    # - $p_blood_type_id (int): The blood type.
    # - $p_birthday (date): The birthday.
    # - $p_birth_place (string): The birth place.
    # - $p_height (double): The height.
    # - $p_weight (double): The weight.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertPersonalInformation($p_contact_id, $p_file_as, $p_first_name, $p_middle_name, $p_last_name, $p_suffix, $p_nickname, $p_corporate_name, $p_bio, $p_civil_status_id, $p_gender_id, $p_religion_id, $p_blood_type_id, $p_birthday, $p_birth_place, $p_height, $p_weight, $p_tin, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPersonalInformation(:p_contact_id, :p_file_as, :p_first_name, :p_middle_name, :p_last_name, :p_suffix, :p_nickname, :p_corporate_name, :p_bio, :p_civil_status_id, :p_gender_id, :p_religion_id, :p_blood_type_id, :p_birthday, :p_birth_place, :p_height, :p_weight, :p_tin, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_file_as', $p_file_as, PDO::PARAM_STR);
        $stmt->bindValue(':p_first_name', $p_first_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_middle_name', $p_middle_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_name', $p_last_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_suffix', $p_suffix, PDO::PARAM_STR);
        $stmt->bindValue(':p_nickname', $p_nickname, PDO::PARAM_STR);
        $stmt->bindValue(':p_corporate_name', $p_corporate_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_bio', $p_bio, PDO::PARAM_STR);
        $stmt->bindValue(':p_civil_status_id', $p_civil_status_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_gender_id', $p_gender_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_religion_id', $p_religion_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_blood_type_id', $p_blood_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_birthday', $p_birthday, PDO::PARAM_STR);
        $stmt->bindValue(':p_birth_place', $p_birth_place, PDO::PARAM_STR);
        $stmt->bindValue(':p_height', $p_height, PDO::PARAM_STR);
        $stmt->bindValue(':p_weight', $p_weight, PDO::PARAM_STR);
        $stmt->bindValue(':p_tin', $p_tin, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertEmploymentInformation
    # Description: Inserts the employee employment information.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_badge_id (string): The badge ID.
    # - $p_company_id (int): The company ID.
    # - $p_employee_type_id (int): The employee type ID.
    # - $p_department_id (int): The department ID.
    # - $p_job_position_id (int): The job position ID.
    # - $p_job_level_id (int): The job level ID.
    # - $p_branch_id (int): The branch ID.
    # - $p_manager_id (int): The manager ID.
    # - $p_work_schedule_id (int): The work schedule ID.
    # - $p_kiosk_pin_code (string): The kiosk pin code.
    # - $p_biometrics_id (string): The biometrics ID.
    # - $p_onboard_date (date): The onboard date.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertEmploymentInformation($p_contact_id, $p_badge_id, $p_company_id, $p_employee_type_id, $p_department_id, $p_job_position_id, $p_job_level_id, $p_branch_id, $p_manager_id, $p_leave_approver_id, $p_work_schedule_id, $p_kiosk_pin_code, $p_biometrics_id, $p_onboard_date, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertEmploymentInformation(:p_contact_id, :p_badge_id, :p_company_id, :p_employee_type_id, :p_department_id, :p_job_position_id, :p_job_level_id, :p_branch_id, :p_manager_id, :p_leave_approver_id, :p_work_schedule_id, :p_kiosk_pin_code, :p_biometrics_id, :p_onboard_date, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_badge_id', $p_badge_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_employee_type_id', $p_employee_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_department_id', $p_department_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_job_position_id', $p_job_position_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_job_level_id', $p_job_level_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_branch_id', $p_branch_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_manager_id', $p_manager_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_leave_approver_id', $p_leave_approver_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_work_schedule_id', $p_work_schedule_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_kiosk_pin_code', $p_kiosk_pin_code, PDO::PARAM_STR);
        $stmt->bindValue(':p_biometrics_id', $p_biometrics_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_onboard_date', $p_onboard_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertContactInformation
    # Description: Inserts the contact information.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_contact_information_type_id (int): The contact information type ID.
    # - $p_mobile (string): The mobile number.
    # - $p_telephone (string): The telephone number.
    # - $p_email (string): The email address.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertContactInformation($p_contact_id, $p_contact_information_type_id, $p_mobile, $p_telephone, $p_email, $p_facebook, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertContactInformation (:p_contact_id, :p_contact_information_type_id, :p_mobile, :p_telephone, :p_email, :p_facebook, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_information_type_id', $p_contact_information_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_mobile', $p_mobile, PDO::PARAM_STR);
        $stmt->bindValue(':p_telephone', $p_telephone, PDO::PARAM_STR);
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
        $stmt->bindValue(':p_facebook', $p_facebook, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertContactAddress
    # Description: Inserts the contact address.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_address_type_id (int): The address type ID.
    # - $p_address (string): The address.
    # - $p_city_id (int): The city ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertContactAddress($p_contact_id, $p_address_type_id, $p_address, $p_city_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertContactAddress (:p_contact_id, :p_address_type_id, :p_address, :p_city_id, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_address_type_id', $p_address_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_address', $p_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_city_id', $p_city_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertContactIdentification
    # Description: Inserts the contact identification.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_id_type_id (int): The ID type ID.
    # - $p_id_number (string): The ID Number.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertContactIdentification($p_contact_id, $p_id_type_id, $p_id_number, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertContactIdentification(:p_contact_id, :p_id_type_id, :p_id_number, :p_last_log_by, @p_contact_identification_id)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_id_type_id', $p_id_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_id_number', $p_id_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_contact_identification_id AS p_contact_identification_id");
        $p_contact_identification_id = $result->fetch(PDO::FETCH_ASSOC)['p_contact_identification_id'];

        return $p_contact_identification_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertContactDocument
    # Description: Inserts the contact identification.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_document_type (string): The ID Number.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertContactDocument($p_contact_id, $p_document_name, $p_document_type, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertContactDocument(:p_contact_id, :p_document_name, :p_document_type, :p_last_log_by, @p_contact_document_id)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_document_name', $p_document_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_type', $p_document_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_contact_document_id AS p_contact_document_id");
        $p_contact_document_id = $result->fetch(PDO::FETCH_ASSOC)['p_contact_document_id'];

        return $p_contact_document_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertContactEducationalBackground
    # Description: Inserts the contact educational background.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_educational_stage_id (int): The educational stage id.
    # - $p_institution_name (string): The institution name.
    # - $p_degree_earned (string): The degree earned.
    # - $p_field_of_study (string): The field of study.
    # - $p_start_month (string): The start month.
    # - $p_start_year (string): The start year.
    # - $p_end_month (string): The end month.
    # - $p_end_year (string): The end year.
    # - $p_course_highlights (string): The course highlights.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertContactEducationalBackground($p_contact_id, $p_educational_stage_id, $p_institution_name, $p_degree_earned, $p_field_of_study, $p_start_month, $p_start_year, $p_end_month, $p_end_year, $p_course_highlights, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertContactEducationalBackground (:p_contact_id, :p_educational_stage_id, :p_institution_name, :p_degree_earned, :p_field_of_study, :p_start_month, :p_start_year, :p_end_month, :p_end_year, :p_course_highlights, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_educational_stage_id', $p_educational_stage_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_institution_name', $p_institution_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_degree_earned', $p_degree_earned, PDO::PARAM_STR);
        $stmt->bindValue(':p_field_of_study', $p_field_of_study, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_month', $p_start_month, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_year', $p_start_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_end_month', $p_end_month, PDO::PARAM_STR);
        $stmt->bindValue(':p_end_year', $p_end_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_course_highlights', $p_course_highlights, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertContactFamilyBackground
    # Description: Inserts the contact family background.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_family_name (string): The family name.
    # - $p_relation_id (int): The institution name.
    # - $p_birthday (date): The birthday.
    # - $p_mobile (string): The mobile.
    # - $p_telephone (string): The telephone.
    # - $p_email (string): The email.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertContactFamilyBackground($p_contact_id, $p_family_name, $p_relation_id, $p_birthday, $p_mobile, $p_telephone, $p_email, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertContactFamilyBackground (:p_contact_id, :p_family_name, :p_relation_id, :p_birthday, :p_mobile, :p_telephone, :p_email, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_family_name', $p_family_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_relation_id', $p_relation_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_birthday', $p_birthday, PDO::PARAM_STR);
        $stmt->bindValue(':p_mobile', $p_mobile, PDO::PARAM_STR);
        $stmt->bindValue(':p_telephone', $p_telephone, PDO::PARAM_STR);
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertContactEmergencyContact
    # Description: Inserts the contact emergency contact.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_emergency_contact_name (string): The emergency contact name.
    # - $p_relation_id (int): The relation ID.
    # - $p_mobile (string): The mobile.
    # - $p_telephone (string): The telephone.
    # - $p_email (string): The email.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertContactEmergencyContact($p_contact_id, $p_emergency_contact_name, $p_relation_id, $p_mobile, $p_telephone, $p_email, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertContactEmergencyContact (:p_contact_id, :p_emergency_contact_name, :p_relation_id, :p_mobile, :p_telephone, :p_email, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_emergency_contact_name', $p_emergency_contact_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_relation_id', $p_relation_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_mobile', $p_mobile, PDO::PARAM_STR);
        $stmt->bindValue(':p_telephone', $p_telephone, PDO::PARAM_STR);
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertContactTraining
    # Description: Inserts the contact training.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_training_name (string): The training name.
    # - $p_training_date (date): The training date.
    # - $p_training_location (string): The training location.
    # - $p_training_provider (string): The training provider.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertContactTraining($p_contact_id, $p_training_name, $p_training_date, $p_training_location, $p_training_provider, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertContactTraining (:p_contact_id, :p_training_name, :p_training_date, :p_training_location, :p_training_provider, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_training_name', $p_training_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_training_date', $p_training_date, PDO::PARAM_INT);
        $stmt->bindValue(':p_training_location', $p_training_location, PDO::PARAM_STR);
        $stmt->bindValue(':p_training_provider', $p_training_provider, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertContactSkills
    # Description: Inserts the contact skills.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_skill_name (string): The skill name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertContactSkills($p_contact_id, $p_skill_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertContactSkills (:p_contact_id, :p_skill_name, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_skill_name', $p_skill_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertContactTalents
    # Description: Inserts the contact talents.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_talent_name (string): The talent name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertContactTalents($p_contact_id, $p_talent_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertContactTalents (:p_contact_id, :p_talent_name, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_talent_name', $p_talent_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertContactHobby
    # Description: Inserts the contact hobby.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_hobby_name (string): The hobby name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertContactHobby($p_contact_id, $p_hobby_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertContactHobby (:p_contact_id, :p_hobby_name, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_hobby_name', $p_hobby_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: insertContactEmploymentHistory
    # Description: Inserts the contact employment history.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_company (string): The company name.
    # - $p_address (string): The company address.
    # - $p_last_position_held (string): The last position held.
    # - $p_start_month (string): The start month.
    # - $p_start_year (string): The start year.
    # - $p_end_month (string): The end month.
    # - $p_end_year (string): The end year.
    # - $p_basic_function (date): The basic function.
    # - $p_starting_salary (double): The starting salary.
    # - $p_final_salary (double): The final salary.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertContactEmploymentHistory($p_contact_id, $p_company, $p_address, $p_last_position_held, $p_start_month, $p_start_year, $p_end_month, $p_end_year, $p_basic_function, $p_starting_salary, $p_final_salary, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertContactEmploymentHistory (:p_contact_id, :p_company, :p_address, :p_last_position_held, :p_start_month, :p_start_year, :p_end_month, :p_end_year, :p_basic_function, :p_starting_salary, :p_final_salary, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_company', $p_company, PDO::PARAM_STR);
        $stmt->bindValue(':p_address', $p_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_position_held', $p_last_position_held, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_month', $p_start_month, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_year', $p_start_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_end_month', $p_end_month, PDO::PARAM_STR);
        $stmt->bindValue(':p_end_year', $p_end_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_basic_function', $p_basic_function, PDO::PARAM_STR);
        $stmt->bindValue(':p_starting_salary', $p_starting_salary, PDO::PARAM_STR);
        $stmt->bindValue(':p_final_salary', $p_final_salary, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertContactLicense
    # Description: Inserts the contact license.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_license_name (string): The license name.
    # - $p_start_month (string): The start month.
    # - $p_start_year (string): The start year.
    # - $p_end_month (string): The end month.
    # - $p_end_year (string): The end year.
    # - $p_expiry_date (date): The expiry date.
    # - $p_description (string): The description.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertContactLicense($p_contact_id, $p_license_name, $issuing_organization, $p_start_month, $p_start_year, $p_end_month, $p_end_year, $p_description, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertContactLicense (:p_contact_id, :p_license_name, :issuing_organization, :p_start_month, :p_start_year, :p_end_month, :p_end_year
        , :p_description, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_license_name', $p_license_name, PDO::PARAM_STR);
        $stmt->bindValue(':issuing_organization', $issuing_organization, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_month', $p_start_month, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_year', $p_start_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_end_month', $p_end_month, PDO::PARAM_STR);
        $stmt->bindValue(':p_end_year', $p_end_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_description', $p_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: insertContactLanguage
    # Description: Inserts the contact language.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_language_id (int): The language ID.
    # - $p_language_proficiency_id (int): The language proficiency ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertContactLanguage($p_contact_id, $p_language_id, $p_language_proficiency_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertContactLanguage (:p_contact_id, :p_language_id, :p_language_proficiency_id, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_language_id', $p_language_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_language_proficiency_id', $p_language_proficiency_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertContactBank
    # Description: Inserts the contact bank.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_bank_id (int): The bank ID.
    # - $bank_account_type_id (int): The bank account type ID.
    # - $p_account_number (string): The account number.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertContactBank($p_contact_id, $p_bank_id, $bank_account_type_id, $p_account_number, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertContactBank (:p_contact_id, :p_bank_id, :bank_account_type_id, :p_account_number, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_bank_id', $p_bank_id, PDO::PARAM_INT);
        $stmt->bindValue(':bank_account_type_id', $bank_account_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_account_number', $p_account_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertRegularAttendanceEntry
    # Description: Inserts the regular attendance exit.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_check_in_image (int): The check in image.
    # - $p_check_in (datetime): The date and time of check in.
    # - $p_check_in_location (string): The check in location.
    # - $p_check_in_by (string): The check in by.
    # - $p_check_out_notes (string): The check in note.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertRegularAttendanceEntry($p_contact_id, $p_check_in_image, $p_check_in, $p_check_in_location, $p_check_in_by, $p_check_in_notes, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertRegularAttendanceEntry (:p_contact_id, :p_check_in_image, :p_check_in, :p_check_in_location, :p_check_in_by, :p_check_in_notes, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_check_in_image', $p_check_in_image, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_in', $p_check_in, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_in_location', $p_check_in_location, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_in_by', $p_check_in_by, PDO::PARAM_INT);
        $stmt->bindValue(':p_check_in_notes', $p_check_in_notes, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertManualAttendanceEntry
    # Description: Inserts the manual attendance exit.
    #
    # Parameters:
    # - $p_attendance_id (int): The attendance ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_check_in (datetime): The date and time of check in.
    # - $p_check_in_notes (string): The check in notes.
    # - $p_check_in_by (int): The check in by.
    # - $p_check_out (datetime): The date and time of check out.
    # - $p_check_out_notes (string): The check out notes.
    # - $p_check_out_by (int): The check out by.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertManualAttendanceEntry($p_contact_id, $p_check_in, $p_check_in_notes, $p_check_in_by, $p_check_out, $p_check_out_notes, $p_check_out_by, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertManualAttendanceEntry (:p_contact_id, :p_check_in, :p_check_in_notes, :p_check_in_by, :p_check_out, :p_check_out_notes, :p_check_out_by, :p_last_log_by, @p_attendance_id)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_check_in', $p_check_in, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_in_notes', $p_check_in_notes, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_in_by', $p_check_in_by, PDO::PARAM_INT);
        $stmt->bindValue(':p_check_out', $p_check_out, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_out_notes', $p_check_out_notes, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_out_by', $p_check_out_by, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_attendance_id AS p_attendance_id");
        $p_attendance_id = $result->fetch(PDO::FETCH_ASSOC)['p_attendance_id'];

        return $p_attendance_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertBiometricsAttendanceRecord
    # Description: Inserts the biometrics attendance record for arrangement.
    #
    # Parameters:
    # - $p_biometrics_id (int): The biometrics ID.
    # - $p_company_id (int): The company ID.
    # - $p_attendance_date (datetime): The attendance date.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertBiometricsAttendanceRecord($p_biometrics_id, $p_company_id, $p_attendance_date) {
        $stmt = $this->db->getConnection()->prepare('CALL insertBiometricsAttendanceRecord (:p_biometrics_id, :p_company_id, :p_attendance_date)');
        $stmt->bindValue(':p_biometrics_id', $p_biometrics_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_attendance_date', $p_attendance_date, PDO::PARAM_STR);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertArrangedBiometricsAttendanceRecord
    # Description: Inserts the arranged biometrics attendance record.
    #
    # Parameters:
    # - $p_contact_id (int): The biometrics ID.
    # - $p_check_in (datetime): The date and time of check in.
    # - $p_check_in_by (int): The check in by.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertArrangedBiometricsAttendanceRecord($p_contact_id, $p_check_in, $p_check_in_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertArrangedBiometricsAttendanceRecord (:p_contact_id, :p_check_in, :p_check_in_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_check_in', $p_check_in, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_in_by', $p_check_in_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertImportedAttendanceEntry
    # Description: Inserts the arranged imported attendance record.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_check_in (datetime): The date and time of check in.
    # - $p_check_in_location (string): The check in location.
    # - $p_check_in_by (int): The check in by.
    # - $p_check_in_mode (string): The check in mode.
    # - $p_check_in_notes (string): The check in notes.
    # - $p_check_out (datetime): The date and time of check out.
    # - $p_check_out_location (string): The check out location.
    # - $p_check_out_by (int): The check out by.
    # - $p_check_out_mode (string): The check out mode.
    # - $p_check_out_notes (string): The check out notes.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertImportedAttendanceEntry($p_contact_id, $p_check_in, $p_check_in_location, $p_check_in_by, $p_check_in_mode, $p_check_in_notes, $p_check_out, $p_check_out_location, $p_check_out_by, $p_check_out_mode, $p_check_out_notes, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertImportedAttendanceEntry (:p_contact_id, :p_check_in, :p_check_in_location, :p_check_in_by, :p_check_in_mode, :p_check_in_notes, :p_check_out, :p_check_out_location, :p_check_out_by, :p_check_out_mode, :p_check_out_notes, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_check_in', $p_check_in, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_in_location', $p_check_in_location, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_in_by', $p_check_in_by, PDO::PARAM_INT);
        $stmt->bindValue(':p_check_in_mode', $p_check_in_mode, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_in_notes', $p_check_in_notes, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_out', $p_check_out, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_out_location', $p_check_out_location, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_out_by', $p_check_out_by, PDO::PARAM_INT);
        $stmt->bindValue(':p_check_out_mode', $p_check_out_mode, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_out_notes', $p_check_out_notes, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertRegularImportedAttendanceEntry
    # Description: Inserts the regular imported attendance record.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_check_in (datetime): The date and time of check in.
    # - $p_check_in_location (string): The check in location.
    # - $p_check_in_by (int): The check in by.
    # - $p_check_in_mode (string): The check in mode.
    # - $p_check_in_notes (string): The check in notes.
    # - $p_check_out (datetime): The date and time of check out.
    # - $p_check_out_location (string): The check out location.
    # - $p_check_out_by (int): The check out by.
    # - $p_check_out_mode (string): The check out mode.
    # - $p_check_out_notes (string): The check out notes.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertRegularImportedAttendanceEntry($p_contact_id, $p_check_in, $p_check_in_location, $p_check_in_by, $p_check_in_mode, $p_check_in_notes, $p_check_out, $p_check_out_location, $p_check_out_by, $p_check_out_mode, $p_check_out_notes) {
        $stmt = $this->db->getConnection()->prepare('CALL insertRegularImportedAttendanceEntry (:p_contact_id, :p_check_in, :p_check_in_location, :p_check_in_by, :p_check_in_mode, :p_check_in_notes, :p_check_out, :p_check_out_location, :p_check_out_by, :p_check_out_mode, :p_check_out_notes)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_check_in', $p_check_in, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_in_location', $p_check_in_location, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_in_by', $p_check_in_by, PDO::PARAM_INT);
        $stmt->bindValue(':p_check_in_mode', $p_check_in_mode, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_in_notes', $p_check_in_notes, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_out', $p_check_out, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_out_location', $p_check_out_location, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_out_by', $p_check_out_by, PDO::PARAM_INT);
        $stmt->bindValue(':p_check_out_mode', $p_check_out_mode, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_out_notes', $p_check_out_notes, PDO::PARAM_STR);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkEmployeeExist
    # Description: Checks if a employee exists.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkEmployeeExist($p_contact_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkEmployeeExist(:p_contact_id)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkPersonalInformationExist
    # Description: Checks if a personal information exists.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkPersonalInformationExist($p_contact_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPersonalInformationExist(:p_contact_id)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkEmploymentInformationExist
    # Description: Checks if a employment information exists.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkEmploymentInformationExist($p_contact_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkEmploymentInformationExist(:p_contact_id)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkContactInformationExist
    # Description: Checks if a contact information exists.
    #
    # Parameters:
    # - $p_contact_information_id (int): The contact information ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkContactInformationExist($p_contact_information_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkContactInformationExist(:p_contact_information_id)');
        $stmt->bindValue(':p_contact_information_id', $p_contact_information_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkContactAddressExist
    # Description: Checks if a contact address exists.
    #
    # Parameters:
    # - $p_contact_address_id (int): The contact address ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkContactAddressExist($p_contact_address_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkContactAddressExist(:p_contact_address_id)');
        $stmt->bindValue(':p_contact_address_id', $p_contact_address_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkContactIdentificationExist
    # Description: Checks if a contact identification exists.
    #
    # Parameters:
    # - $p_contact_identification_id (int): The contact identification ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkContactIdentificationExist($p_contact_identification_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkContactIdentificationExist(:p_contact_identification_id)');
        $stmt->bindValue(':p_contact_identification_id', $p_contact_identification_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkContactEducationalBackgroundExist
    # Description: Checks if a contact educational background exists.
    #
    # Parameters:
    # - $p_contact_educational_background_id (int): The contact educational background ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkContactEducationalBackgroundExist($p_contact_educational_background_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkContactEducationalBackgroundExist(:p_contact_educational_background_id)');
        $stmt->bindValue(':p_contact_educational_background_id', $p_contact_educational_background_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkContactFamilyBackgroundExist
    # Description: Checks if a contact family background exists.
    #
    # Parameters:
    # - $p_contact_family_background_id (int): The contact family background ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkContactFamilyBackgroundExist($p_contact_family_background_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkContactFamilyBackgroundExist(:p_contact_family_background_id)');
        $stmt->bindValue(':p_contact_family_background_id', $p_contact_family_background_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkContactEmergencyContactExist
    # Description: Checks if a contact emergency contact exists.
    #
    # Parameters:
    # - $p_contact_emergency_contact_id (int): The contact emergency contact ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkContactEmergencyContactExist($p_contact_emergency_contact_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkContactEmergencyContactExist(:p_contact_emergency_contact_id)');
        $stmt->bindValue(':p_contact_emergency_contact_id', $p_contact_emergency_contact_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkContactTrainingExist
    # Description: Checks if a contact training exists.
    #
    # Parameters:
    # - $p_contact_training_id (int): The contact training ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkContactTrainingExist($p_contact_training_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkContactTrainingExist(:p_contact_training_id)');
        $stmt->bindValue(':p_contact_training_id', $p_contact_training_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkContactSkillsExist
    # Description: Checks if a contact skills exists.
    #
    # Parameters:
    # - $p_contact_skills_id (int): The contact skills ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkContactSkillsExist($p_contact_skills_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkContactSkillsExist(:p_contact_skills_id)');
        $stmt->bindValue(':p_contact_skills_id', $p_contact_skills_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkContactTalentsExist
    # Description: Checks if a contact talents exists.
    #
    # Parameters:
    # - $p_contact_talents_id (int): The contact talents ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkContactTalentsExist($p_contact_talents_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkContactTalentsExist(:p_contact_talents_id)');
        $stmt->bindValue(':p_contact_talents_id', $p_contact_talents_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkContactHobbyExist
    # Description: Checks if a contact hobby exists.
    #
    # Parameters:
    # - $p_contact_hobby_id (int): The contact hobby ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkContactHobbyExist($p_contact_hobby_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkContactHobbyExist(:p_contact_hobby_id)');
        $stmt->bindValue(':p_contact_hobby_id', $p_contact_hobby_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkContactEmploymentHistoryExist
    # Description: Checks if a contact employment history exists.
    #
    # Parameters:
    # - $p_contact_employment_history_id (int): The contact employment history ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkContactEmploymentHistoryExist($p_contact_employment_history_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkContactEmploymentHistoryExist(:p_contact_employment_history_id)');
        $stmt->bindValue(':p_contact_employment_history_id', $p_contact_employment_history_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkContactLicenseExist
    # Description: Checks if a contact license exists.
    #
    # Parameters:
    # - $p_contact_license_id (int): The contact license ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkContactLicenseExist($p_contact_license_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkContactLicenseExist(:p_contact_license_id)');
        $stmt->bindValue(':p_contact_license_id', $p_contact_license_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkContactLanguageExist
    # Description: Checks if a contact language exists.
    #
    # Parameters:
    # - $p_contact_language_id (int): The contact language ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkContactLanguageExist($p_contact_language_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkContactLanguageExist(:p_contact_language_id)');
        $stmt->bindValue(':p_contact_language_id', $p_contact_language_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkContactBankExist
    # Description: Checks if a contact bank exists.
    #
    # Parameters:
    # - $p_contact_bank_id (int): The contact bank ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkContactBankExist($p_contact_bank_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkContactBankExist(:p_contact_bank_id)');
        $stmt->bindValue(':p_contact_bank_id', $p_contact_bank_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkAttendanceExist
    # Description: Checks if a attendance exists.
    #
    # Parameters:
    # - $p_attendance_id (int): The attendance ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkAttendanceExist($p_attendance_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkAttendanceExist(:p_attendance_id)');
        $stmt->bindValue(':p_attendance_id', $p_attendance_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkContactDocumentExist
    # Description: Checks if a attendance exists.
    #
    # Parameters:
    # - $p_attendance_id (int): The attendance ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkContactDocumentExist($p_contact_document_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkContactDocumentExist(:p_contact_document_id)');
        $stmt->bindValue(':p_contact_document_id', $p_contact_document_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkBiometricsAttendanceRecordExist
    # Description: Checks if the imported biometrics attendance record exists on the temp_attendance_import table for arranging of attendance records.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_attendance_date (datetime): The attendance date.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkBiometricsAttendanceRecordExist($p_contact_id, $p_attendance_date) {
        $stmt = $this->db->getConnection()->prepare('CALL checkBiometricsAttendanceRecordExist(:p_contact_id, :p_attendance_date)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_attendance_date', $p_attendance_date, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkArrangedBiometricsAttendanceRecordExist
    # Description: Checks if the there are attendance record that does not have check out.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_attendance_date (datetime): The attendance date.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkArrangedBiometricsAttendanceRecordExist($p_contact_id, $p_attendance_date) {
        $stmt = $this->db->getConnection()->prepare('CALL checkArrangedBiometricsAttendanceRecordExist(:p_contact_id, :p_attendance_date)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_attendance_date', $p_attendance_date, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check methods
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: checkAttendanceConflict
    # Description: Checks if a attendance conflicts with other attendace record.
    #
    # Parameters:
    # - $p_attendance_id (int): The attendance ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_check_in (datetime): The check in.
    # - $p_check_out (datetime): The check out.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkAttendanceConflict($p_attendance_id, $p_contact_id, $p_check_in, $p_check_out) {
        $stmt = $this->db->getConnection()->prepare('CALL checkAttendanceConflict(:p_attendance_id, :p_contact_id, :p_check_in, :p_check_out)');
        $stmt->bindValue(':p_attendance_id', $p_attendance_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_check_in', $p_check_in, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_out', $p_check_out, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteEmployee
    # Description: Deletes the employee.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteEmployee($p_contact_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteEmployee(:p_contact_id)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactInformation
    # Description: Deletes the contact information.
    #
    # Parameters:
    # - $p_contact_information_id (int): The contact information ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteContactInformation($p_contact_information_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteContactInformation(:p_contact_information_id)');
        $stmt->bindValue(':p_contact_information_id', $p_contact_information_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactAddress
    # Description: Deletes the contact address.
    #
    # Parameters:
    # - $p_contact_address_id (int): The contact address ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteContactAddress($p_contact_address_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteContactAddress(:p_contact_address_id)');
        $stmt->bindValue(':p_contact_address_id', $p_contact_address_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactIdentification
    # Description: Deletes the contact identification.
    #
    # Parameters:
    # - $p_contact_identification_id (int): The contact identification ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteContactIdentification($p_contact_identification_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteContactIdentification(:p_contact_identification_id)');
        $stmt->bindValue(':p_contact_identification_id', $p_contact_identification_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactDocument
    # Description: Deletes the contact identification.
    #
    # Parameters:
    # - $p_contact_document_idf (int): The contact identification ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteContactDocument($p_contact_document_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteContactDocument(:p_contact_document_id)');
        $stmt->bindValue(':p_contact_document_id', $p_contact_document_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactEducationalBackground
    # Description: Deletes the contact educational background.
    #
    # Parameters:
    # - $p_contact_educational_background_id (int): The contact educational background ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteContactEducationalBackground($p_contact_educational_background_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteContactEducationalBackground(:p_contact_educational_background_id)');
        $stmt->bindValue(':p_contact_educational_background_id', $p_contact_educational_background_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactFamilyBackground
    # Description: Deletes the contact family background.
    #
    # Parameters:
    # - $p_contact_family_background_id (int): The contact family background ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteContactFamilyBackground($p_contact_family_background_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteContactFamilyBackground(:p_contact_family_background_id)');
        $stmt->bindValue(':p_contact_family_background_id', $p_contact_family_background_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactEmergencyContact
    # Description: Deletes the contact emergency contact.
    #
    # Parameters:
    # - $p_contact_emergency_contact_id (int): The contact emergency contact ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteContactEmergencyContact($p_contact_emergency_contact_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteContactEmergencyContact(:p_contact_emergency_contact_id)');
        $stmt->bindValue(':p_contact_emergency_contact_id', $p_contact_emergency_contact_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactTraining
    # Description: Deletes the contact training.
    #
    # Parameters:
    # - $p_contact_training_id (int): The contact training ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteContactTraining($p_contact_training_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteContactTraining(:p_contact_training_id)');
        $stmt->bindValue(':p_contact_training_id', $p_contact_training_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactSkills
    # Description: Deletes the contact skills.
    #
    # Parameters:
    # - $p_contact_skills_id (int): The contact skills ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteContactSkills($p_contact_skills_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteContactSkills(:p_contact_skills_id)');
        $stmt->bindValue(':p_contact_skills_id', $p_contact_skills_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactTalents
    # Description: Deletes the contact talents.
    #
    # Parameters:
    # - $p_contact_talents_id (int): The contact talents ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteContactTalents($p_contact_talents_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteContactTalents(:p_contact_talents_id)');
        $stmt->bindValue(':p_contact_talents_id', $p_contact_talents_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactHobby
    # Description: Deletes the contact hobby.
    #
    # Parameters:
    # - $p_contact_hobby_id (int): The contact hobby ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteContactHobby($p_contact_hobby_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteContactHobby(:p_contact_hobby_id)');
        $stmt->bindValue(':p_contact_hobby_id', $p_contact_hobby_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactEmploymentHistory
    # Description: Deletes the contact employment history.
    #
    # Parameters:
    # - $p_contact_employment_history_id (int): The contact employment history ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteContactEmploymentHistory($p_contact_employment_history_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteContactEmploymentHistory(:p_contact_employment_history_id)');
        $stmt->bindValue(':p_contact_employment_history_id', $p_contact_employment_history_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactLicense
    # Description: Deletes the contact license.
    #
    # Parameters:
    # - $p_contact_license_id (int): The contact license ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteContactLicense($p_contact_license_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteContactLicense(:p_contact_license_id)');
        $stmt->bindValue(':p_contact_license_id', $p_contact_license_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactLanguage
    # Description: Deletes the contact language.
    #
    # Parameters:
    # - $p_contact_language_id (int): The contact language ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteContactLanguage($p_contact_language_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteContactLanguage(:p_contact_language_id)');
        $stmt->bindValue(':p_contact_language_id', $p_contact_language_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactBank
    # Description: Deletes the contact bank.
    #
    # Parameters:
    # - $p_contact_bank_id (int): The contact bank ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteContactBank($p_contact_bank_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteContactBank(:p_contact_bank_id)');
        $stmt->bindValue(':p_contact_bank_id', $p_contact_bank_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteAttendance
    # Description: Deletes the attendance record.
    #
    # Parameters:
    # - $p_attendance_id (int): The attendance ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteAttendance($p_attendance_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteAttendance(:p_attendance_id)');
        $stmt->bindValue(':p_attendance_id', $p_attendance_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBiometricsAttendanceRecord
    # Description: Deletes the biometrics import attendance record.
    #
    # Parameters: None
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteBiometricsAttendanceRecord() {
        $stmt = $this->db->getConnection()->prepare('CALL deleteBiometricsAttendanceRecord()');
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getEmployee
    # Description: Retrieves the details of an employee.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    #
    # Returns:
    # - An array containing the employee information.
    #
    # -------------------------------------------------------------
    public function getEmployee($p_contact_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getEmployee(:p_contact_id)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPersonalInformation
    # Description: Retrieves the details of a personal information.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    #
    # Returns:
    # - An array containing the personal information.
    #
    # -------------------------------------------------------------
    public function getPersonalInformation($p_contact_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getPersonalInformation(:p_contact_id)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getEmploymentInformation
    # Description: Retrieves the details of a employment information.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    #
    # Returns:
    # - An array containing the employment information.
    #
    # -------------------------------------------------------------
    public function getEmploymentInformation($p_contact_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getEmploymentInformation(:p_contact_id)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactInformation
    # Description: Retrieves the details of a contact information.
    #
    # Parameters:
    # - $p_contact_information_id (int): The contact information ID.
    #
    # Returns:
    # - An array containing the contact information.
    #
    # -------------------------------------------------------------
    public function getContactInformation($p_contact_information_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getContactInformation(:p_contact_information_id)');
        $stmt->bindValue(':p_contact_information_id', $p_contact_information_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactAddress
    # Description: Retrieves the details of a contact address.
    #
    # Parameters:
    # - $p_contact_address_id (int): The contact address ID.
    #
    # Returns:
    # - An array containing the contact address.
    #
    # -------------------------------------------------------------
    public function getContactAddress($p_contact_address_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getContactAddress(:p_contact_address_id)');
        $stmt->bindValue(':p_contact_address_id', $p_contact_address_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactIdentification
    # Description: Retrieves the details of a contact identification.
    #
    # Parameters:
    # - $p_contact_identification_id (int): The contact identification ID.
    #
    # Returns:
    # - An array containing the contact identification.
    #
    # -------------------------------------------------------------
    public function getContactIdentification($p_contact_identification_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getContactIdentification(:p_contact_identification_id)');
        $stmt->bindValue(':p_contact_identification_id', $p_contact_identification_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactEducationalBackground
    # Description: Retrieves the details of a contact educational background.
    #
    # Parameters:
    # - $p_contact_educational_background_id (int): The educational background ID.
    #
    # Returns:
    # - An array containing the educational background.
    #
    # -------------------------------------------------------------
    public function getContactEducationalBackground($p_contact_educational_background_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getContactEducationalBackground(:p_contact_educational_background_id)');
        $stmt->bindValue(':p_contact_educational_background_id', $p_contact_educational_background_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactFamilyBackground
    # Description: Retrieves the details of a contact family background.
    #
    # Parameters:
    # - $p_contact_family_background_id (int): The family background ID.
    #
    # Returns:
    # - An array containing the family background.
    #
    # -------------------------------------------------------------
    public function getContactFamilyBackground($p_contact_family_background_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getContactFamilyBackground(:p_contact_family_background_id)');
        $stmt->bindValue(':p_contact_family_background_id', $p_contact_family_background_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactEmergencyContact
    # Description: Retrieves the details of a contact emergency contact.
    #
    # Parameters:
    # - $p_contact_emergency_contact_id (int): The emergency contact ID.
    #
    # Returns:
    # - An array containing the emergency contact.
    #
    # -------------------------------------------------------------
    public function getContactEmergencyContact($p_contact_emergency_contact_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getContactEmergencyContact(:p_contact_emergency_contact_id)');
        $stmt->bindValue(':p_contact_emergency_contact_id', $p_contact_emergency_contact_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactTraining
    # Description: Retrieves the details of a contact training.
    #
    # Parameters:
    # - $p_contact_training_id (int): The training ID.
    #
    # Returns:
    # - An array containing the training.
    #
    # -------------------------------------------------------------
    public function getContactTraining($p_contact_training_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getContactTraining(:p_contact_training_id)');
        $stmt->bindValue(':p_contact_training_id', $p_contact_training_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactSkills
    # Description: Retrieves the details of a contact skills.
    #
    # Parameters:
    # - $p_contact_skills_id (int): The skills ID.
    #
    # Returns:
    # - An array containing the skills.
    #
    # -------------------------------------------------------------
    public function getContactSkills($p_contact_skills_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getContactSkills(:p_contact_skills_id)');
        $stmt->bindValue(':p_contact_skills_id', $p_contact_skills_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactTalents
    # Description: Retrieves the details of a contact talents.
    #
    # Parameters:
    # - $p_contact_talents_id (int): The talents ID.
    #
    # Returns:
    # - An array containing the talents.
    #
    # -------------------------------------------------------------
    public function getContactTalents($p_contact_talents_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getContactTalents(:p_contact_talents_id)');
        $stmt->bindValue(':p_contact_talents_id', $p_contact_talents_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactHobby
    # Description: Retrieves the details of a contact hobby.
    #
    # Parameters:
    # - $p_contact_hobby_id (int): The hobby ID.
    #
    # Returns:
    # - An array containing the hobby.
    #
    # -------------------------------------------------------------
    public function getContactHobby($p_contact_hobby_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getContactHobby(:p_contact_hobby_id)');
        $stmt->bindValue(':p_contact_hobby_id', $p_contact_hobby_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactEmploymentHistory
    # Description: Retrieves the details of a contact employment history.
    #
    # Parameters:
    # - $p_contact_employment_history_id (int): The employment history ID.
    #
    # Returns:
    # - An array containing the employment history.
    #
    # -------------------------------------------------------------
    public function getContactEmploymentHistory($p_contact_employment_history_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getContactEmploymentHistory(:p_contact_employment_history_id)');
        $stmt->bindValue(':p_contact_employment_history_id', $p_contact_employment_history_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactLicense
    # Description: Retrieves the details of a contact license.
    #
    # Parameters:
    # - $p_contact_license_id (int): The license ID.
    #
    # Returns:
    # - An array containing the license.
    #
    # -------------------------------------------------------------
    public function getContactLicense($p_contact_license_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getContactLicense(:p_contact_license_id)');
        $stmt->bindValue(':p_contact_license_id', $p_contact_license_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactLanguage
    # Description: Retrieves the details of a contact language.
    #
    # Parameters:
    # - $p_contact_language_id (int): The language ID.
    #
    # Returns:
    # - An array containing the license.
    #
    # -------------------------------------------------------------
    public function getContactLanguage($p_contact_language_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getContactLanguage(:p_contact_language_id)');
        $stmt->bindValue(':p_contact_language_id', $p_contact_language_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactBank
    # Description: Retrieves the details of a contact bank.
    #
    # Parameters:
    # - $p_contact_bank_id (int): The bank ID.
    #
    # Returns:
    # - An array containing the bank.
    #
    # -------------------------------------------------------------
    public function getContactBank($p_contact_bank_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getContactBank(:p_contact_bank_id)');
        $stmt->bindValue(':p_contact_bank_id', $p_contact_bank_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getAttendance
    # Description: Retrieves the details of the attendance record.
    #
    # Parameters:
    # - $p_attendance_id (int): The attendance ID.
    #
    # Returns:
    # - An array containing the bank.
    #
    # -------------------------------------------------------------
    public function getAttendance($p_attendance_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getAttendance(:p_attendance_id)');
        $stmt->bindValue(':p_attendance_id', $p_attendance_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getAttendanceRecordWithoutCheckOut
    # Description: Retrieves the details of the attendance record of the employee without check-out based on the current date.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    #
    # Returns:
    # - An array containing the bank.
    #
    # -------------------------------------------------------------
    public function getAttendanceRecordWithoutCheckOut($p_contact_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getAttendanceRecordWithoutCheckOut(:p_contact_id)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLatestAttendanceRecord
    # Description: Retrieves the details of the latest attendance record of the employee with check-in and check-out based on current date.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    #
    # Returns:
    # - An array containing the bank.
    #
    # -------------------------------------------------------------
    public function getLatestAttendanceRecord($p_contact_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getLatestAttendanceRecord(:p_contact_id)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getAttendanceRecordCount
    # Description: Retrieves the number of attendance record of the employee based on the current date.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    #
    # Returns:
    # - An array containing the bank.
    #
    # -------------------------------------------------------------
    public function getAttendanceRecordCount($p_contact_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getAttendanceRecordCount(:p_contact_id)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getEmploymentInformationByBiometricsID
    # Description: Retrieves the details of employment information based on the biometrics ID.
    #
    # Parameters:
    # - $p_biometrics_id (int): The biometrics ID.
    #
    # Returns:
    # - An array containing the bank.
    #
    # -------------------------------------------------------------
    public function getEmploymentInformationByBiometricsID($p_biometrics_id, $p_company_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getEmploymentInformationByBiometricsID(:p_biometrics_id, :p_company_id)');
        $stmt->bindValue(':p_biometrics_id', $p_biometrics_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getArrangedImportedAttendanceRecord
    # Description: Retrieves the details of arranged imported attendance record.
    #
    # Parameters:
    # - $p_attendance_id (int): The attendace ID.
    #
    # Returns:
    # - An array containing the bank.
    #
    # -------------------------------------------------------------
    public function getArrangedImportedAttendanceRecord($p_attendance_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getArrangedImportedAttendanceRecord(:p_attendance_id)');
        $stmt->bindValue(':p_attendance_id', $p_attendance_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: generateEmployeeOptions
    # Description: Generates the employee options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateEmployeeOptions($p_generate_type, $p_reference_id = null) {
        $stmt = $this->db->getConnection()->prepare('CALL generateEmployeeOptions(:p_generate_type, :p_reference_id)');
        $stmt->bindValue(':p_generate_type', $p_generate_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_id', $p_reference_id, PDO::PARAM_STR);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->closeCursor();

        $htmlOptions = '';
        foreach ($options as $row) {
            $contactID = $row['contact_id'];
            $fileAs = $row['file_as'];

            $htmlOptions .= '<option value="' . htmlspecialchars($contactID, ENT_QUOTES) . '">' . htmlspecialchars($fileAs, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Grant methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: grantPortalAccess
    # Description: Grants the portal access.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_last_log_by (int): The ID of the user who last modified the contact.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function grantPortalAccess($p_contact_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL grantPortalAccess(:p_contact_id, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Revoke methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: revokePortalAccess
    # Description: Revokes the portal access.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_last_log_by (int): The ID of the user who last modified the contact.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function revokePortalAccess($p_contact_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL revokePortalAccess(:p_contact_id, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
}
?>