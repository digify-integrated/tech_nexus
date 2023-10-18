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
    # Description: Updates the contact identfication.
    #
    # Parameters:
    # - $p_contact_identfication_id (int): The contact identfication ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_id_type_id (int): The ID type ID.
    # - $p_id_number (string): The ID Number.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactIdentification($p_contact_identfication_id, $p_contact_id, $p_id_type_id, $p_id_number, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactIdentification (:p_contact_identfication_id, :p_contact_id, :p_id_type_id, :p_id_number, :p_last_log_by)');
        $stmt->bindValue(':p_contact_identfication_id', $p_contact_identfication_id, PDO::PARAM_INT);
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
    # Description: Updates the contact identfication as primary.
    #
    # Parameters:
    # - $p_contact_identfication_id (int): The contact identfication ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactIdentificationStatus($p_contact_identfication_id, $p_contact_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactIdentificationStatus (:p_contact_identfication_id, :p_contact_id, :p_last_log_by)');
        $stmt->bindValue(':p_contact_identfication_id', $p_contact_identfication_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
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
    # Description: Inserts the contact identfication.
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
    # Description: Checks if a contact identfication exists.
    #
    # Parameters:
    # - $p_contact_identfication_id (int): The contact identfication ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkContactIdentificationExist($p_contact_identfication_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkContactIdentificationExist(:p_contact_identfication_id)');
        $stmt->bindValue(':p_contact_identfication_id', $p_contact_identfication_id, PDO::PARAM_INT);
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
    # Description: Deletes the contact identfication.
    #
    # Parameters:
    # - $p_contact_identfication_id (int): The contact identfication ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteContactIdentification($p_contact_identfication_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteContactIdentification(:p_contact_identfication_id)');
        $stmt->bindValue(':p_contact_identfication_id', $p_contact_identfication_id, PDO::PARAM_INT);
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
    # Description: Retrieves the details of a contact identfication.
    #
    # Parameters:
    # - $p_contact_identfication_id (int): The contact identfication ID.
    #
    # Returns:
    # - An array containing the contact identfication.
    #
    # -------------------------------------------------------------
    public function getContactIdentification($p_contact_identfication_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getContactIdentification(:p_contact_identfication_id)');
        $stmt->bindValue(':p_contact_identfication_id', $p_contact_identfication_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>