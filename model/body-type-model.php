<?php
/**
* Class BodyTypeModel
*
* The BodyTypeModel class handles body type related operations and interactions.
*/
class BodyTypeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateBodyType
    # Description: Updates the body type.
    #
    # Parameters:
    # - $p_body_type_id (int): The body type ID.
    # - $p_body_type_name (string): The body type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateBodyType($p_body_type_id, $p_body_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBodyType(:p_body_type_id, :p_body_type_name, :p_last_log_by)');
        $stmt->bindValue(':p_body_type_id', $p_body_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_body_type_name', $p_body_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertBodyType
    # Description: Inserts the body type.
    #
    # Parameters:
    # - $p_body_type_name (string): The body type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertBodyType($p_body_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertBodyType(:p_body_type_name, :p_last_log_by, @p_body_type_id)');
        $stmt->bindValue(':p_body_type_name', $p_body_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_body_type_id AS p_body_type_id");
        $p_body_type_id = $result->fetch(PDO::FETCH_ASSOC)['p_body_type_id'];

        return $p_body_type_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkBodyTypeExist
    # Description: Checks if a body type exists.
    #
    # Parameters:
    # - $p_body_type_id (int): The body type ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkBodyTypeExist($p_body_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkBodyTypeExist(:p_body_type_id)');
        $stmt->bindValue(':p_body_type_id', $p_body_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBodyType
    # Description: Deletes the body type.
    #
    # Parameters:
    # - $p_body_type_id (int): The body type ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteBodyType($p_body_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteBodyType(:p_body_type_id)');
        $stmt->bindValue(':p_body_type_id', $p_body_type_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBodyType
    # Description: Retrieves the details of a body type.
    #
    # Parameters:
    # - $p_body_type_id (int): The body type ID.
    #
    # Returns:
    # - An array containing the body type details.
    #
    # -------------------------------------------------------------
    public function getBodyType($p_body_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getBodyType(:p_body_type_id)');
        $stmt->bindValue(':p_body_type_id', $p_body_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateBodyType
    # Description: Duplicates the body type.
    #
    # Parameters:
    # - $p_body_type_id (int): The body type ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateBodyType($p_body_type_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateBodyType(:p_body_type_id, :p_last_log_by, @p_new_body_type_id)');
        $stmt->bindValue(':p_body_type_id', $p_body_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_body_type_id AS body_type_id");
        $bodyTypeiD = $result->fetch(PDO::FETCH_ASSOC)['body_type_id'];

        return $bodyTypeiD;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateBodyTypeOptions
    # Description: Generates the body type options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateBodyTypeOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateBodyTypeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $bodyTypeID = $row['body_type_id'];
            $bodyTypeName = $row['body_type_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($bodyTypeID, ENT_QUOTES) . '">' . htmlspecialchars($bodyTypeName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateBodyTypeCheckBox
    # Description: Generates the body type check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateBodyTypeCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateBodyTypeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $bodyTypeID = $row['body_type_id'];
            $bodyTypeName = $row['body_type_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input body-type-filter" type="checkbox" id="body-type-' . htmlspecialchars($bodyTypeID, ENT_QUOTES) . '" value="' . htmlspecialchars($bodyTypeID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="body-type-' . htmlspecialchars($bodyTypeID, ENT_QUOTES) . '">' . htmlspecialchars($bodyTypeName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>