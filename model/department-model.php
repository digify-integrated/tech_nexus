<?php
/**
* Class DepartmentModel
*
* The DepartmentModel class handles department related operations and interactions.
*/
class DepartmentModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateDepartment
    # Description: Updates the department.
    #
    # Parameters:
    # - $p_department_id (int): The department ID.
    # - $p_department_name (string): The department name.
    # - $p_parent_department (int): The parent department of the department.
    # - $p_manager (int): The manager of the department.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateDepartment($p_department_id, $p_department_name, $p_parent_department, $p_manager, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateDepartment(:p_department_id, :p_department_name, :p_parent_department, :p_manager, :p_last_log_by)');
        $stmt->bindValue(':p_department_id', $p_department_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_department_name', $p_department_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_parent_department', $p_parent_department, PDO::PARAM_INT);
        $stmt->bindValue(':p_manager', $p_manager, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertDepartment
    # Description: Inserts the department.
    #
    # Parameters:
    # - $p_department_name (string): The department name.
    # - $p_parent_department (int): The parent department of the department.
    # - $p_manager (int): The manager of the department.
    # - $p_last_log_by (int): The last logged user.
    #
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertDepartment($p_department_name, $p_parent_department, $p_manager, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertDepartment(:p_department_name, :p_parent_department, :p_manager, :p_last_log_by, @p_department_id)');
        $stmt->bindValue(':p_department_name', $p_department_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_parent_department', $p_parent_department, PDO::PARAM_INT);
        $stmt->bindValue(':p_manager', $p_manager, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_department_id AS p_department_id");
        $p_department_id = $result->fetch(PDO::FETCH_ASSOC)['p_department_id'];

        return $p_department_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkDepartmentExist
    # Description: Checks if a department exists.
    #
    # Parameters:
    # - $p_department_id (int): The department ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkDepartmentExist($p_department_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkDepartmentExist(:p_department_id)');
        $stmt->bindValue(':p_department_id', $p_department_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteDepartment
    # Description: Deletes the department.
    #
    # Parameters:
    # - $p_department_id (int): The department ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteDepartment($p_department_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteDepartment(:p_department_id)');
        $stmt->bindValue(':p_department_id', $p_department_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getDepartment
    # Description: Retrieves the details of a department.
    #
    # Parameters:
    # - $p_department_id (int): The department ID.
    #
    # Returns:
    # - An array containing the department details.
    #
    # -------------------------------------------------------------
    public function getDepartment($p_department_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getDepartment(:p_department_id)');
        $stmt->bindValue(':p_department_id', $p_department_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateDepartment
    # Description: Duplicates the department.
    #
    # Parameters:
    # - $p_department_id (int): The department ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateDepartment($p_department_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateDepartment(:p_department_id, :p_last_log_by, @p_new_department_id)');
        $stmt->bindValue(':p_department_id', $p_department_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_department_id AS department_id");
        $departmentID = $result->fetch(PDO::FETCH_ASSOC)['department_id'];

        return $departmentID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateDepartmentOptions
    # Description: Generates the department options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateDepartmentOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateDepartmentOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $departmentID = $row['department_id'];
            $departmentName = $row['department_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($departmentID, ENT_QUOTES) . '">' . htmlspecialchars($departmentName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateDepartmentCheckBox
    # Description: Generates the department check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateDepartmentCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateDepartmentOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $departmentID = $row['department_id'];
            $departmentName = $row['department_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input department-filter" type="checkbox" id="department-' . htmlspecialchars($departmentID, ENT_QUOTES) . '" value="' . htmlspecialchars($departmentID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="department-' . htmlspecialchars($departmentID, ENT_QUOTES) . '">' . htmlspecialchars($departmentName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>