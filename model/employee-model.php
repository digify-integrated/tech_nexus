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
    # Function: updateEmployeePersonalInformation
    # Description: Updates the employee personal information.
    #
    # Parameters:
    # - $p_employee_id (int): The employee ID.
    # - $p_first_name (string): The first name of the employee.
    # - $p_middle_name (string): The middle name of the employee.
    # - $p_last_name (string): The last name of the employee.
    # - $p_suffix (string): The suffix of the employee.
    # - $p_nickname (string): The nickname of the employee.
    # - $p_bio (string): The bio of the employee.
    # - $p_civil_status_id (int): The civil status of the employee.
    # - $p_gender_id (int): The gender of the employee.
    # - $p_religion_id (int): The religion of the employee.
    # - $p_blood_type_id (int): The blood type of the employee.
    # - $p_birthday (date): The birthday of the employee.
    # - $p_birth_place (string): The birth place of the employee.
    # - $p_height (double): The height of the employee.
    # - $p_weight (double): The weight of the employee.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateEmployeePersonalInformation($p_employee_id, $p_first_name, $p_middle_name, $p_last_name, $p_suffix, $p_nickname, $p_bio, $p_civil_status_id, $p_gender_id, $p_religion_id, $p_blood_type_id, $p_birthday, $p_birth_place, $p_height, $p_weight, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateEmployeePersonalInformation(:p_employee_id, :p_first_name, :p_middle_name, :p_last_name, :p_suffix, :p_nickname, :p_bio, :p_civil_status_id, :p_gender_id, :p_religion_id, :p_blood_type_id, :p_birthday, :p_birth_place, :p_height, :p_weight, :p_last_log_by)');
        $stmt->bindValue(':p_employee_id', $p_employee_id, PDO::PARAM_INT);
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
        $stmt = $this->db->getConnection()->prepare('CALL insertEmployee(:p_last_log_by, @p_employee_id)');
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_employee_id AS p_employee_id");
        $p_employee_id = $result->fetch(PDO::FETCH_ASSOC)['p_employee_id'];

        return $p_employee_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPartialEmployeePersonalInformation
    # Description: Inserts the partial employee personal details.
    #
    # Parameters:
    # - $p_employee_id (int): The employee ID.
    # - $p_first_name (string): The first name of the employee.
    # - $p_middle_name (string): The middle name of the employee.
    # - $p_last_name (string): The last name of the employee.
    # - $p_suffix (string): The suffix of the employee.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertPartialEmployeePersonalInformation($p_employee_id, $p_first_name, $p_middle_name, $p_last_name, $p_suffix, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartialEmployeePersonalInformation(:p_employee_id, :p_first_name, :p_middle_name, :p_last_name, :p_suffix, :p_last_log_by)');
        $stmt->bindValue(':p_employee_id', $p_employee_id, PDO::PARAM_INT);
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
    # Function: insertEmployeePersonalInformation
    # Description: Inserts the employee personal information.
    #
    # Parameters:
    # - $p_employee_id (int): The employee ID.
    # - $p_first_name (string): The first name of the employee.
    # - $p_middle_name (string): The middle name of the employee.
    # - $p_last_name (string): The last name of the employee.
    # - $p_suffix (string): The suffix of the employee.
    # - $p_nickname (string): The nickname of the employee.
    # - $p_bio (string): The bio of the employee.
    # - $p_civil_status_id (int): The civil status of the employee.
    # - $p_gender_id (int): The gender of the employee.
    # - $p_religion_id (int): The religion of the employee.
    # - $p_blood_type_id (int): The blood type of the employee.
    # - $p_birthday (date): The birthday of the employee.
    # - $p_birth_place (string): The birth place of the employee.
    # - $p_height (double): The height of the employee.
    # - $p_weight (double): The weight of the employee.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertEmployeePersonalInformation($p_employee_id, $p_first_name, $p_middle_name, $p_last_name, $p_suffix, $p_nickname, $p_bio, $p_civil_status_id, $p_gender_id, $p_religion_id, $p_blood_type_id, $p_birthday, $p_birth_place, $p_height, $p_weight, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertEmployeePersonalInformation(:p_employee_id, :p_first_name, :p_middle_name, :p_last_name, :p_suffix, :p_nickname, :p_bio, :p_civil_status_id, :p_gender_id, :p_religion_id, :p_blood_type_id, :p_birthday, :p_birth_place, :p_height, :p_weight, :p_last_log_by)');
        $stmt->bindValue(':p_employee_id', $p_employee_id, PDO::PARAM_INT);
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
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkEmployeeExist
    # Description: Checks if a employee exists.
    #
    # Parameters:
    # - $p_employee_id (int): The employee ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkEmployeeExist($p_employee_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkEmployeeExist(:p_employee_id)');
        $stmt->bindValue(':p_employee_id', $p_employee_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkEmployeePersonalInformationExist
    # Description: Checks if a employee personal information exists.
    #
    # Parameters:
    # - $p_employee_id (int): The employee ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkEmployeePersonalInformationExist($p_employee_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkEmployeePersonalInformationExist(:p_employee_id)');
        $stmt->bindValue(':p_employee_id', $p_employee_id, PDO::PARAM_INT);
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
    # - $p_employee_id (int): The employee ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteEmployee($p_employee_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteEmployee(:p_employee_id)');
        $stmt->bindValue(':p_employee_id', $p_employee_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getEmployeePersonalInformation
    # Description: Retrieves the details of a employee.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    #
    # Returns:
    # - An array containing the employee details.
    #
    # -------------------------------------------------------------
    public function getEmployeePersonalInformation($p_contact_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getEmployeePersonalInformation(:p_contact_id)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateEmployee
    # Description: Duplicates the employee.
    #
    # Parameters:
    # - $p_employee_id (int): The employee ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateEmployee($p_employee_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateEmployee(:p_employee_id, :p_last_log_by, @p_new_employee_id)');
        $stmt->bindValue(':p_employee_id', $p_employee_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_employee_id AS employee_id");
        $systemActionID = $result->fetch(PDO::FETCH_ASSOC)['employee_id'];

        return $systemActionID;
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
    public function generateEmployeeOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateEmployeeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $employeeID = $row['employee_id'];
            $employeeName = $row['employee_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($employeeID, ENT_QUOTES) . '">' . htmlspecialchars($employeeName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>