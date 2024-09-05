<?php
/**
* Class ApplicationSourceModel
*
* The ApplicationSourceModel class handles application source related operations and interactions.
*/
class ApplicationSourceModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateApplicationSource
    # Description: Updates the application source.
    #
    # Parameters:
    # - $p_application_source_id (int): The application source ID.
    # - $p_application_source_name (string): The application source name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateApplicationSource($p_application_source_id, $p_application_source_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateApplicationSource(:p_application_source_id, :p_application_source_name, :p_last_log_by)');
        $stmt->bindValue(':p_application_source_id', $p_application_source_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_application_source_name', $p_application_source_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertApplicationSource
    # Description: Inserts the application source.
    #
    # Parameters:
    # - $p_application_source_name (string): The application source name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertApplicationSource($p_application_source_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertApplicationSource(:p_application_source_name, :p_last_log_by, @p_application_source_id)');
        $stmt->bindValue(':p_application_source_name', $p_application_source_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_application_source_id AS p_application_source_id");
        $p_application_source_id = $result->fetch(PDO::FETCH_ASSOC)['p_application_source_id'];

        return $p_application_source_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkApplicationSourceExist
    # Description: Checks if a application source exists.
    #
    # Parameters:
    # - $p_application_source_id (int): The application source ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkApplicationSourceExist($p_application_source_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkApplicationSourceExist(:p_application_source_id)');
        $stmt->bindValue(':p_application_source_id', $p_application_source_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteApplicationSource
    # Description: Deletes the application source.
    #
    # Parameters:
    # - $p_application_source_id (int): The application source ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteApplicationSource($p_application_source_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteApplicationSource(:p_application_source_id)');
        $stmt->bindValue(':p_application_source_id', $p_application_source_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getApplicationSource
    # Description: Retrieves the details of a application source.
    #
    # Parameters:
    # - $p_application_source_id (int): The application source ID.
    #
    # Returns:
    # - An array containing the application source details.
    #
    # -------------------------------------------------------------
    public function getApplicationSource($p_application_source_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getApplicationSource(:p_application_source_id)');
        $stmt->bindValue(':p_application_source_id', $p_application_source_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateApplicationSource
    # Description: Duplicates the application source.
    #
    # Parameters:
    # - $p_application_source_id (int): The application source ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateApplicationSource($p_application_source_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateApplicationSource(:p_application_source_id, :p_last_log_by, @p_new_application_source_id)');
        $stmt->bindValue(':p_application_source_id', $p_application_source_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_application_source_id AS application_source_id");
        $applicationSourceID = $result->fetch(PDO::FETCH_ASSOC)['application_source_id'];

        return $applicationSourceID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateApplicationSourceOptions
    # Description: Generates the application source options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateApplicationSourceOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateApplicationSourceOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $applicationSourceID = $row['application_source_id'];
            $applicationSourceName = $row['application_source_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($applicationSourceID, ENT_QUOTES) . '">' . htmlspecialchars($applicationSourceName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateApplicationSourceCheckBox
    # Description: Generates the application source check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateApplicationSourceCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateApplicationSourceOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $applicationSourceID = $row['application_source_id'];
            $applicationSourceName = $row['application_source_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input application-source-filter" type="checkbox" id="application-source-' . htmlspecialchars($applicationSourceID, ENT_QUOTES) . '" value="' . htmlspecialchars($applicationSourceID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="application-source-' . htmlspecialchars($applicationSourceID, ENT_QUOTES) . '">' . htmlspecialchars($applicationSourceName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>