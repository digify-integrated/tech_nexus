<?php
/**
* Class EmployeeTypeModel
*
* The EmployeeTypeModel class handles employee type related operations and interactions.
*/
class EmployeeTypeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateEmployeeType
    # Description: Updates the employee type.
    #
    # Parameters:
    # - $p_employee_type_id (int): The employee type ID.
    # - $p_employee_type_name (string): The employee type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateEmployeeType($p_employee_type_id, $p_employee_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateEmployeeType(:p_employee_type_id, :p_employee_type_name, :p_last_log_by)');
        $stmt->bindValue(':p_employee_type_id', $p_employee_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_employee_type_name', $p_employee_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertEmployeeType
    # Description: Inserts the employee type.
    #
    # Parameters:
    # - $p_employee_type_name (string): The employee type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertEmployeeType($p_employee_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertEmployeeType(:p_employee_type_name, :p_last_log_by, @p_employee_type_id)');
        $stmt->bindValue(':p_employee_type_name', $p_employee_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_employee_type_id AS p_employee_type_id");
        $p_employee_type_id = $result->fetch(PDO::FETCH_ASSOC)['p_employee_type_id'];

        return $p_employee_type_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkEmployeeTypeExist
    # Description: Checks if a employee type exists.
    #
    # Parameters:
    # - $p_employee_type_id (int): The employee type ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkEmployeeTypeExist($p_employee_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkEmployeeTypeExist(:p_employee_type_id)');
        $stmt->bindValue(':p_employee_type_id', $p_employee_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteEmployeeType
    # Description: Deletes the employee type.
    #
    # Parameters:
    # - $p_employee_type_id (int): The employee type ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteEmployeeType($p_employee_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteEmployeeType(:p_employee_type_id)');
        $stmt->bindValue(':p_employee_type_id', $p_employee_type_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getEmployeeType
    # Description: Retrieves the details of a employee type.
    #
    # Parameters:
    # - $p_employee_type_id (int): The employee type ID.
    #
    # Returns:
    # - An array containing the employee type details.
    #
    # -------------------------------------------------------------
    public function getEmployeeType($p_employee_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getEmployeeType(:p_employee_type_id)');
        $stmt->bindValue(':p_employee_type_id', $p_employee_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateEmployeeType
    # Description: Duplicates the employee type.
    #
    # Parameters:
    # - $p_employee_type_id (int): The employee type ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateEmployeeType($p_employee_type_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateEmployeeType(:p_employee_type_id, :p_last_log_by, @p_new_employee_type_id)');
        $stmt->bindValue(':p_employee_type_id', $p_employee_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_employee_type_id AS employee_type_id");
        $employeeTypeID = $result->fetch(PDO::FETCH_ASSOC)['employee_type_id'];

        return $employeeTypeID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateEmployeeTypeOptions
    # Description: Generates the employee type options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateEmployeeTypeOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateEmployeeTypeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $employeeTypeID = $row['employee_type_id'];
            $employeeTypeName = $row['employee_type_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($employeeTypeID, ENT_QUOTES) . '">' . htmlspecialchars($employeeTypeName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateEmployeeTypeCheckBox
    # Description: Generates the employee type check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateEmployeeTypeCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateEmployeeTypeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $employeeTypeID = $row['employee_type_id'];
            $employeeTypeName = $row['employee_type_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input employee-type-filter" type="checkbox" id="employee-type-' . htmlspecialchars($employeeTypeID, ENT_QUOTES) . '" value="' . htmlspecialchars($employeeTypeID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="employee-type-' . htmlspecialchars($employeeTypeID, ENT_QUOTES) . '">' . htmlspecialchars($employeeTypeName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>