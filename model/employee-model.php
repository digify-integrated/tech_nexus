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
    # Function: updateEmployee
    # Description: Updates the employee.
    #
    # Parameters:
    # - $p_employee_id (int): The employee ID.
    # - $p_employee_name (string): The employee name.
    # - $p_employee_identifier_code (string): The employee identifier code.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateEmployee($p_employee_id, $p_employee_name, $p_employee_identifier_code, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateEmployee(:p_employee_id, :p_employee_name, :p_employee_identifier_code, :p_last_log_by)');
        $stmt->bindValue(':p_employee_id', $p_employee_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_employee_name', $p_employee_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_employee_identifier_code', $p_employee_identifier_code, PDO::PARAM_STR);
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
    # - $p_file_as (string): The employee full name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertEmployee($p_file_as, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertEmployee(:p_file_as, :p_last_log_by, @p_employee_id)');
        $stmt->bindValue(':p_file_as', $p_file_as, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_employee_id AS p_employee_id");
        $p_employee_id = $result->fetch(PDO::FETCH_ASSOC)['p_employee_id'];

        return $p_employee_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertEmployeePersonalInformation
    # Description: Inserts the employee.
    #
    # Parameters:
    # - $p_contact_id (int): The contact id.
    # - $p_first_name (string): The employee first name.
    # - $p_middle_name (string): The employee middle name.
    # - $p_last_name (string): The employee last name.
    # - $p_suffix (string): The employee suffix.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertEmployeePersonalInformation($p_contact_id, $p_first_name, $p_middle_name, $p_last_name, $p_suffix, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertEmployeePersonalInformation(:p_contact_id, :p_first_name, :p_middle_name, :p_last_name, :p_suffix, :p_last_log_by)');
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
    # Function: getEmployee
    # Description: Retrieves the details of a employee.
    #
    # Parameters:
    # - $p_employee_id (int): The employee ID.
    #
    # Returns:
    # - An array containing the employee details.
    #
    # -------------------------------------------------------------
    public function getEmployee($p_employee_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getEmployee(:p_employee_id)');
        $stmt->bindValue(':p_employee_id', $p_employee_id, PDO::PARAM_INT);
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