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
    public function updatePersonalInformation($p_contact_id, $p_first_name, $p_middle_name, $p_last_name, $p_suffix, $p_nickname, $p_bio, $p_civil_status_id, $p_gender_id, $p_religion_id, $p_blood_type_id, $p_birthday, $p_birth_place, $p_height, $p_weight, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePersonalInformation(:p_contact_id, :p_first_name, :p_middle_name, :p_last_name, :p_suffix, :p_nickname, :p_bio, :p_civil_status_id, :p_gender_id, :p_religion_id, :p_blood_type_id, :p_birthday, :p_birth_place, :p_height, :p_weight, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_first_name', $p_first_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_middle_name', $p_middle_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_name', $p_last_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_suffix', $p_suffix, PDO::PARAM_STR);
        $stmt->bindValue(':p_nickname', $p_nickname, PDO::PARAM_STR);
        $stmt->bindValue(':p_bio', $p_bio, PDO::PARAM_STR);
        $stmt->bindValue(':p_civil_status_id', $p_civil_status_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_gender_id', $p_gender_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_religion_id', $p_religion_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_blood_type_id', $p_blood_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_birthday', $p_birthday, PDO::PARAM_STR);
        $stmt->bindValue(':p_birth_place', $p_birth_place, PDO::PARAM_STR);
        $stmt->bindValue(':p_height', $p_height, PDO::PARAM_STR);
        $stmt->bindValue(':p_weight', $p_weight, PDO::PARAM_STR);
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
    # - $p_permanency_date (date): The permanency date.
    # - $p_onboard_date (date): The onboard date.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateEmploymentInformation($p_contact_id, $p_badge_id, $p_company_id, $p_employee_type_id, $p_department_id, $p_job_position_id, $p_job_level_id, $p_branch_id, $p_permanency_date, $p_onboard_date, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateEmploymentInformation (:p_contact_id, :p_badge_id, :p_company_id, :p_employee_type_id, :p_department_id, :p_job_position_id, :p_job_level_id, :p_branch_id, :p_permanency_date, :p_onboard_date, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_badge_id', $p_badge_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_employee_type_id', $p_employee_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_department_id', $p_department_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_job_position_id', $p_job_position_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_job_level_id', $p_job_level_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_branch_id', $p_branch_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_permanency_date', $p_permanency_date, PDO::PARAM_STR);
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
    public function updateContactInformation($p_contact_information_id, $p_contact_id, $p_contact_information_type_id, $p_mobile, $p_telephone, $p_email, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactInformation (:p_contact_information_id, :p_contact_id, :p_contact_information_type_id, :p_mobile, :p_telephone, :p_email, :p_last_log_by)');
        $stmt->bindValue(':p_contact_information_id', $p_contact_information_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_information_type_id', $p_contact_information_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_mobile', $p_mobile, PDO::PARAM_STR);
        $stmt->bindValue(':p_telephone', $p_telephone, PDO::PARAM_STR);
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
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
    # - $start_date (date): The start date.
    # - $end_date (date): The end date.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactEducationalBackground($p_contact_educational_background_id, $p_contact_id, $p_educational_stage_id, $p_institution_name, $p_degree_earned, $p_field_of_study, $start_date, $end_date, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactEducationalBackground (:p_contact_educational_background_id, :p_contact_id, :p_educational_stage_id, :p_institution_name, :p_degree_earned, :p_field_of_study, :start_date, :end_date, :p_last_log_by)');
        $stmt->bindValue(':p_contact_educational_background_id', $p_contact_educational_background_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_educational_stage_id', $p_educational_stage_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_institution_name', $p_institution_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_degree_earned', $p_degree_earned, PDO::PARAM_STR);
        $stmt->bindValue(':p_field_of_study', $p_field_of_study, PDO::PARAM_STR);
        $stmt->bindValue(':start_date', $start_date, PDO::PARAM_STR);
        $stmt->bindValue(':end_date', $end_date, PDO::PARAM_STR);
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
    # - $p_first_name (string): The first name.
    # - $p_middle_name (string): The middle name.
    # - $p_last_name (string): The last name.
    # - $p_suffix (string): The suffix.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertPartialPersonalInformation($p_contact_id, $p_first_name, $p_middle_name, $p_last_name, $p_suffix, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartialPersonalInformation(:p_contact_id, :p_first_name, :p_middle_name, :p_last_name, :p_suffix, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
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
    public function insertPersonalInformation($p_contact_id, $p_first_name, $p_middle_name, $p_last_name, $p_suffix, $p_nickname, $p_bio, $p_civil_status_id, $p_gender_id, $p_religion_id, $p_blood_type_id, $p_birthday, $p_birth_place, $p_height, $p_weight, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPersonalInformation(:p_contact_id, :p_first_name, :p_middle_name, :p_last_name, :p_suffix, :p_nickname, :p_bio, :p_civil_status_id, :p_gender_id, :p_religion_id, :p_blood_type_id, :p_birthday, :p_birth_place, :p_height, :p_weight, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_first_name', $p_first_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_middle_name', $p_middle_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_name', $p_last_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_suffix', $p_suffix, PDO::PARAM_STR);
        $stmt->bindValue(':p_nickname', $p_nickname, PDO::PARAM_STR);
        $stmt->bindValue(':p_bio', $p_bio, PDO::PARAM_STR);
        $stmt->bindValue(':p_civil_status_id', $p_civil_status_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_gender_id', $p_gender_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_religion_id', $p_religion_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_blood_type_id', $p_blood_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_birthday', $p_birthday, PDO::PARAM_STR);
        $stmt->bindValue(':p_birth_place', $p_birth_place, PDO::PARAM_STR);
        $stmt->bindValue(':p_height', $p_height, PDO::PARAM_STR);
        $stmt->bindValue(':p_weight', $p_weight, PDO::PARAM_STR);
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
    # - $p_permanency_date (date): The permanency date.
    # - $p_onboard_date (date): The onboard date.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertEmploymentInformation($p_contact_id, $p_badge_id, $p_company_id, $p_employee_type_id, $p_department_id, $p_job_position_id, $p_job_level_id, $p_branch_id, $p_permanency_date, $p_onboard_date, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertEmploymentInformation(:p_contact_id, :p_badge_id, :p_company_id, :p_employee_type_id, :p_department_id, :p_job_position_id, :p_job_level_id, :p_branch_id, :p_permanency_date, :p_onboard_date, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_badge_id', $p_badge_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_employee_type_id', $p_employee_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_department_id', $p_department_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_job_position_id', $p_job_position_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_job_level_id', $p_job_level_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_branch_id', $p_branch_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_permanency_date', $p_permanency_date, PDO::PARAM_STR);
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
    public function insertContactInformation($p_contact_id, $p_contact_information_type_id, $p_mobile, $p_telephone, $p_email, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertContactInformation (:p_contact_id, :p_contact_information_type_id, :p_mobile, :p_telephone, :p_email, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_information_type_id', $p_contact_information_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_mobile', $p_mobile, PDO::PARAM_STR);
        $stmt->bindValue(':p_telephone', $p_telephone, PDO::PARAM_STR);
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
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
        $stmt = $this->db->getConnection()->prepare('CALL insertContactIdentification (:p_contact_id, :p_id_type_id, :p_id_number, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_id_type_id', $p_id_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_id_number', $p_id_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
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
    # - $start_date (date): The start date.
    # - $end_date (date): The end date.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertContactEducationalBackground($p_contact_id, $p_educational_stage_id, $p_institution_name, $p_degree_earned, $p_field_of_study, $start_date, $end_date, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertContactEducationalBackground (:p_contact_id, :p_educational_stage_id, :p_institution_name, :p_degree_earned, :p_field_of_study, :start_date, :end_date, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_educational_stage_id', $p_educational_stage_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_institution_name', $p_institution_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_degree_earned', $p_degree_earned, PDO::PARAM_STR);
        $stmt->bindValue(':p_field_of_study', $p_field_of_study, PDO::PARAM_STR);
        $stmt->bindValue(':start_date', $start_date, PDO::PARAM_STR);
        $stmt->bindValue(':end_date', $end_date, PDO::PARAM_STR);
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
    #   Get methods
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
}
?>