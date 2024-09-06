<?php
/**
* Class MiscellaneousClientModel
*
* The MiscellaneousClientModel class handles miscellaneous client related operations and interactions.
*/
class MiscellaneousClientModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateMiscellaneousClient
    # Description: Updates the miscellaneous client.
    #
    # Parameters:
    # - $p_miscellaneous_client_id (int): The miscellaneous client ID.
    # - $p_client_name (string): The miscellaneous client name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateMiscellaneousClient($p_miscellaneous_client_id, $p_client_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateMiscellaneousClient(:p_miscellaneous_client_id, :p_client_name, :p_last_log_by)');
        $stmt->bindValue(':p_miscellaneous_client_id', $p_miscellaneous_client_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_client_name', $p_client_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertMiscellaneousClient
    # Description: Inserts the miscellaneous client.
    #
    # Parameters:
    # - $p_client_name (string): The miscellaneous client name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertMiscellaneousClient($p_client_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertMiscellaneousClient(:p_client_name, :p_last_log_by, @p_miscellaneous_client_id)');
        $stmt->bindValue(':p_client_name', $p_client_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_miscellaneous_client_id AS p_miscellaneous_client_id");
        $p_miscellaneous_client_id = $result->fetch(PDO::FETCH_ASSOC)['p_miscellaneous_client_id'];

        return $p_miscellaneous_client_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkMiscellaneousClientExist
    # Description: Checks if a miscellaneous client exists.
    #
    # Parameters:
    # - $p_miscellaneous_client_id (int): The miscellaneous client ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkMiscellaneousClientExist($p_miscellaneous_client_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkMiscellaneousClientExist(:p_miscellaneous_client_id)');
        $stmt->bindValue(':p_miscellaneous_client_id', $p_miscellaneous_client_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMiscellaneousClient
    # Description: Deletes the miscellaneous client.
    #
    # Parameters:
    # - $p_miscellaneous_client_id (int): The miscellaneous client ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteMiscellaneousClient($p_miscellaneous_client_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteMiscellaneousClient(:p_miscellaneous_client_id)');
        $stmt->bindValue(':p_miscellaneous_client_id', $p_miscellaneous_client_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getMiscellaneousClient
    # Description: Retrieves the details of a miscellaneous client.
    #
    # Parameters:
    # - $p_miscellaneous_client_id (int): The miscellaneous client ID.
    #
    # Returns:
    # - An array containing the miscellaneous client details.
    #
    # -------------------------------------------------------------
    public function getMiscellaneousClient($p_miscellaneous_client_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getMiscellaneousClient(:p_miscellaneous_client_id)');
        $stmt->bindValue(':p_miscellaneous_client_id', $p_miscellaneous_client_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateMiscellaneousClient
    # Description: Duplicates the miscellaneous client.
    #
    # Parameters:
    # - $p_miscellaneous_client_id (int): The miscellaneous client ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateMiscellaneousClient($p_miscellaneous_client_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateMiscellaneousClient(:p_miscellaneous_client_id, :p_last_log_by, @p_new_miscellaneous_client_id)');
        $stmt->bindValue(':p_miscellaneous_client_id', $p_miscellaneous_client_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_miscellaneous_client_id AS miscellaneous_client_id");
        $miscellaneousClientID = $result->fetch(PDO::FETCH_ASSOC)['miscellaneous_client_id'];

        return $miscellaneousClientID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateMiscellaneousClientOptions
    # Description: Generates the miscellaneous client options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateMiscellaneousClientOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateMiscellaneousClientOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $miscellaneousClientID = $row['miscellaneous_client_id'];
            $miscellaneousClientName = $row['client_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($miscellaneousClientID, ENT_QUOTES) . '">' . htmlspecialchars($miscellaneousClientName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateMiscellaneousClientCheckBox
    # Description: Generates the miscellaneous client check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateMiscellaneousClientCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateMiscellaneousClientOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $miscellaneousClientID = $row['miscellaneous_client_id'];
            $miscellaneousClientName = $row['client_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input miscellaneous-client-filter" type="checkbox" id="miscellaneous-client-' . htmlspecialchars($miscellaneousClientID, ENT_QUOTES) . '" value="' . htmlspecialchars($miscellaneousClientID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="miscellaneous-client-' . htmlspecialchars($miscellaneousClientID, ENT_QUOTES) . '">' . htmlspecialchars($miscellaneousClientName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>