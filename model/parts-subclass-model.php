<?php
/**
* Class PartsSubclassModel
*
* The PartsSubclassModel class handles parts subclass related operations and interactions.
*/
class PartsSubclassModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updatePartsSubclass
    # Description: Updates the parts subclass.
    #
    # Parameters:
    # - $p_parts_subclass_id (int): The parts subclass ID.
    # - $p_parts_subclass_name (string): The parts subclass name.
    # - $p_parts_class_id (int): The product category ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updatePartsSubclass($p_parts_subclass_id, $p_parts_subclass_name, $p_parts_class_id, $part_subclass_code, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsSubclass(:p_parts_subclass_id, :p_parts_subclass_name, :p_parts_class_id, :p_part_subclass_code, :p_last_log_by)');
        $stmt->bindValue(':p_parts_subclass_id', $p_parts_subclass_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_parts_subclass_name', $p_parts_subclass_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_parts_class_id', $p_parts_class_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_part_subclass_code', $part_subclass_code, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPartsSubclass
    # Description: Inserts the parts subclass.
    #
    # Parameters:
    # - $p_parts_subclass_name (string): The parts subclass name.
    # - $p_parts_subclass_code (string): The parts subclass code.
    # - $p_parts_class_id (int): The product category ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertPartsSubclass($p_parts_subclass_name, $p_parts_class_id, $part_subclass_code, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartsSubclass(:p_parts_subclass_name, :p_parts_class_id, :p_part_subclass_code, :p_last_log_by, @p_parts_subclass_id)');
        $stmt->bindValue(':p_parts_subclass_name', $p_parts_subclass_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_parts_class_id', $p_parts_class_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_part_subclass_code', $part_subclass_code, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_parts_subclass_id AS p_parts_subclass_id");
        $p_parts_subclass_id = $result->fetch(PDO::FETCH_ASSOC)['p_parts_subclass_id'];

        return $p_parts_subclass_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkPartsSubclassExist
    # Description: Checks if a parts subclass exists.
    #
    # Parameters:
    # - $p_parts_subclass_id (int): The parts subclass ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkPartsSubclassExist($p_parts_subclass_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPartsSubclassExist(:p_parts_subclass_id)');
        $stmt->bindValue(':p_parts_subclass_id', $p_parts_subclass_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePartsSubclass
    # Description: Deletes the parts subclass.
    #
    # Parameters:
    # - $p_parts_subclass_id (int): The parts subclass ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deletePartsSubclass($p_parts_subclass_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePartsSubclass(:p_parts_subclass_id)');
        $stmt->bindValue(':p_parts_subclass_id', $p_parts_subclass_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPartsSubclass
    # Description: Retrieves the details of a parts subclass.
    #
    # Parameters:
    # - $p_parts_subclass_id (int): The parts subclass ID.
    #
    # Returns:
    # - An array containing the parts subclass details.
    #
    # -------------------------------------------------------------
    public function getPartsSubclass($p_parts_subclass_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getPartsSubclass(:p_parts_subclass_id)');
        $stmt->bindValue(':p_parts_subclass_id', $p_parts_subclass_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicatePartsSubclass
    # Description: Duplicates the parts subclass.
    #
    # Parameters:
    # - $p_parts_subclass_id (int): The parts subclass ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicatePartsSubclass($p_parts_subclass_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicatePartsSubclass(:p_parts_subclass_id, :p_last_log_by, @p_new_parts_subclass_id)');
        $stmt->bindValue(':p_parts_subclass_id', $p_parts_subclass_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_parts_subclass_id AS parts_subclass_id");
        $partsSubclassID = $result->fetch(PDO::FETCH_ASSOC)['part_subclass_id'];

        return $partsSubclassID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generatePartsSubclassOptions
    # Description: Generates the parts subclass options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePartsSubclassOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePartsSubclassOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsSubclassID = $row['part_subclass_id'];
            $partsSubclassName = $row['part_subclass_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($partsSubclassID, ENT_QUOTES) . '">' . htmlspecialchars($partsSubclassName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    public function generatePartsSubclassOptionsByClass($p_parts_class_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT part_subclass_id, part_subclass_name FROM part_subclass WHERE part_class_id = :p_parts_class_id ORDER BY part_subclass_name');
        $stmt->bindValue(':p_parts_class_id', $p_parts_class_id, PDO::PARAM_INT);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = [];
        foreach ($options as $row) {
            $part_subclass_id = $row['part_subclass_id'];
            $part_subclass_name = $row['part_subclass_name'];

            $htmlOptions[] = '<option value="' . htmlspecialchars($part_subclass_id, ENT_QUOTES) . '">' . htmlspecialchars($part_subclass_name, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }

    public function generateGroupedPartsSubclassOptions() {
        $stmt = $this->db->getConnection()->prepare('SELECT part_class_id, part_class_name FROM part_class ORDER BY part_class_name');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = [];

        foreach ($options as $row) {
            $part_class_id = $row['part_class_id'];
            $partClassName = $row['part_class_name'];

            $htmlOptions[] = '<optgroup label="'. $partClassName .'">';

            $subcategoryOptions = $this->generatePartsSubclassOptionsByClass($part_class_id);

            foreach ($subcategoryOptions as $subcategoryOption) {
                $htmlOptions[] = $subcategoryOption;
            }

            $htmlOptions[] = '</optgroup>';
        }

        return implode('', $htmlOptions);
    }
    # -------------------------------------------------------------


    # -------------------------------------------------------------
    #
    # Function: generatePartsSubclassByCategoryOptions
    # Description: Generates the parts subclass check box.
    #
    # Parameters: None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePartsSubclassByCategoryOptions($p_parts_class_id) {
        $stmt = $this->db->getConnection()->prepare('CALL generatePartsSubclassByCategoryOptions(:p_parts_class_id)');
        $stmt->bindValue(':p_parts_class_id', $p_parts_class_id, PDO::PARAM_INT);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = [];
        foreach ($options as $row) {
            $partsSubclassID = $row['part_subclass_id'];
            $partsSubclassName = $row['part_subclass_name'];

            $htmlOptions[] = '<option value="' . htmlspecialchars($partsSubclassID, ENT_QUOTES) . '">' . htmlspecialchars($partsSubclassName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generatePartsSubclassCheckbox
    # Description: Generates the parts subclass check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePartsSubclassCheckbox() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePartsSubclassOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsSubclassID = $row['part_subclass_id'];
            $partsSubclassName = $row['part_subclass_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input parts-subclass-filter" type="checkbox" id="parts-subclass-' . htmlspecialchars($partsSubclassID, ENT_QUOTES) . '" value="' . htmlspecialchars($partsSubclassID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="parts-subclass-' . htmlspecialchars($partsSubclassID, ENT_QUOTES) . '">' . htmlspecialchars($partsSubclassName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>