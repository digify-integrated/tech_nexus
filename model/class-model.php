<?php
/**
* Class ClassModel
*
* The ClassModel class handles class related operations and interactions.
*/
class ClassModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateClass
    # Description: Updates the class.
    #
    # Parameters:
    # - $p_class_id (int): The class ID.
    # - $p_class_name (string): The class name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateClass($p_class_id, $p_class_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateClass(:p_class_id, :p_class_name, :p_last_log_by)');
        $stmt->bindValue(':p_class_id', $p_class_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_class_name', $p_class_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertClass
    # Description: Inserts the class.
    #
    # Parameters:
    # - $p_class_name (string): The class name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertClass($p_class_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertClass(:p_class_name, :p_last_log_by, @p_class_id)');
        $stmt->bindValue(':p_class_name', $p_class_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_class_id AS p_class_id");
        $p_class_id = $result->fetch(PDO::FETCH_ASSOC)['p_class_id'];

        return $p_class_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkClassExist
    # Description: Checks if a class exists.
    #
    # Parameters:
    # - $p_class_id (int): The class ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkClassExist($p_class_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkClassExist(:p_class_id)');
        $stmt->bindValue(':p_class_id', $p_class_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteClass
    # Description: Deletes the class.
    #
    # Parameters:
    # - $p_class_id (int): The class ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteClass($p_class_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteClass(:p_class_id)');
        $stmt->bindValue(':p_class_id', $p_class_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getClass
    # Description: Retrieves the details of a class.
    #
    # Parameters:
    # - $p_class_id (int): The class ID.
    #
    # Returns:
    # - An array containing the class details.
    #
    # -------------------------------------------------------------
    public function getClass($p_class_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getClass(:p_class_id)');
        $stmt->bindValue(':p_class_id', $p_class_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateClass
    # Description: Duplicates the class.
    #
    # Parameters:
    # - $p_class_id (int): The class ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateClass($p_class_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateClass(:p_class_id, :p_last_log_by, @p_new_class_id)');
        $stmt->bindValue(':p_class_id', $p_class_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_class_id AS class_id");
        $classiD = $result->fetch(PDO::FETCH_ASSOC)['class_id'];

        return $classiD;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateClassOptions
    # Description: Generates the class options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateClassOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateClassOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $classID = $row['class_id'];
            $className = $row['class_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($classID, ENT_QUOTES) . '">' . htmlspecialchars($className, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateClassCheckBox
    # Description: Generates the class check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateClassCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateClassOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $classID = $row['class_id'];
            $className = $row['class_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input class-filter" type="checkbox" id="class-' . htmlspecialchars($classID, ENT_QUOTES) . '" value="' . htmlspecialchars($classID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="class-' . htmlspecialchars($classID, ENT_QUOTES) . '">' . htmlspecialchars($className, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>