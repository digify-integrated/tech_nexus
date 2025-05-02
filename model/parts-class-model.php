<?php
/**
* Class PartsClassModel
*
* The PartsClassModel class handles parts class related operations and interactions.
*/
class PartsClassModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updatePartsClass
    # Description: Updates the parts class.
    #
    # Parameters:
    # - $p_parts_class_id (int): The parts class ID.
    # - $p_parts_class_name (string): The parts class name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updatePartsClass($p_parts_class_id, $p_parts_class_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsClass(:p_parts_class_id, :p_parts_class_name, :p_last_log_by)');
        $stmt->bindValue(':p_parts_class_id', $p_parts_class_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_parts_class_name', $p_parts_class_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPartsClass
    # Description: Inserts the parts class.
    #
    # Parameters:
    # - $p_parts_class_name (string): The parts class name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertPartsClass($p_parts_class_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartsClass(:p_parts_class_name, :p_last_log_by, @p_parts_class_id)');
        $stmt->bindValue(':p_parts_class_name', $p_parts_class_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_parts_class_id AS p_parts_class_id");
        $p_parts_class_id = $result->fetch(PDO::FETCH_ASSOC)['p_parts_class_id'];

        return $p_parts_class_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkPartsClassExist
    # Description: Checks if a parts class exists.
    #
    # Parameters:
    # - $p_parts_class_id (int): The parts class ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkPartsClassExist($p_parts_class_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPartsClassExist(:p_parts_class_id)');
        $stmt->bindValue(':p_parts_class_id', $p_parts_class_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePartsClass
    # Description: Deletes the parts class.
    #
    # Parameters:
    # - $p_parts_class_id (int): The parts class ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deletePartsClass($p_parts_class_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePartsClass(:p_parts_class_id)');
        $stmt->bindValue(':p_parts_class_id', $p_parts_class_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPartsClass
    # Description: Retrieves the details of a parts class.
    #
    # Parameters:
    # - $p_parts_class_id (int): The parts class ID.
    #
    # Returns:
    # - An array containing the parts class details.
    #
    # -------------------------------------------------------------
    public function getPartsClass($p_parts_class_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getPartsClass(:p_parts_class_id)');
        $stmt->bindValue(':p_parts_class_id', $p_parts_class_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicatePartsClass
    # Description: Duplicates the parts class.
    #
    # Parameters:
    # - $p_parts_class_id (int): The parts class ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicatePartsClass($p_parts_class_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicatePartsClass(:p_parts_class_id, :p_last_log_by, @p_new_parts_class_id)');
        $stmt->bindValue(':p_parts_class_id', $p_parts_class_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_parts_class_id AS parts_class_id");
        $partsClassID = $result->fetch(PDO::FETCH_ASSOC)['part_class_id'];

        return $partsClassID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generatePartsClassOptions
    # Description: Generates the parts class options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePartsClassOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePartsClassOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsClassID = $row['part_class_id'];
            $partsClassName = $row['part_class_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($partsClassID, ENT_QUOTES) . '">' . htmlspecialchars($partsClassName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generatePartsClassCheckbox
    # Description: Generates the parts class check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePartsClassCheckbox() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePartsClassOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsClassID = $row['part_class_id'];
            $partsClassName = $row['part_class_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input parts-class-filter" type="checkbox" id="parts-class-' . htmlspecialchars($partsClassID, ENT_QUOTES) . '" value="' . htmlspecialchars($partsClassID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="parts-class-' . htmlspecialchars($partsClassID, ENT_QUOTES) . '">' . htmlspecialchars($partsClassName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>