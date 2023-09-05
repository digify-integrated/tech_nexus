<?php
/**
* Class ZoomAPIModel
*
* The ZoomAPIModel class handles zoom API related operations and interactions.
*/
class ZoomAPIModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateZoomAPI
    # Description: Updates the zoom API.
    #
    # Parameters:
    # - $p_zoom_api_id (int): The zoom API ID.
    # - $p_zoom_api_name (string): The zoom API name.
    # - $p_zoom_api_description (string): The zoom API description.
    # - $p_api_key (string): The Zoom API key.
    # - $p_api_secret (string): The Zoom API secret.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateZoomAPI($p_zoom_api_id, $p_zoom_api_name, $p_zoom_api_description, $p_api_key, $p_api_secret, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateZoomAPI(:p_zoom_api_id, :p_zoom_api_name, :p_zoom_api_description, :p_api_key, :p_api_secret, :p_last_log_by)');
        $stmt->bindValue(':p_zoom_api_id', $p_zoom_api_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_zoom_api_name', $p_zoom_api_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_zoom_api_description', $p_zoom_api_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_api_key', $p_api_key, PDO::PARAM_STR);
        $stmt->bindValue(':p_api_secret', $p_api_secret, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertZoomAPI
    # Description: Inserts the zoom API.
    #
    # Parameters:
    # - $p_zoom_api_name (string): The zoom API name.
    # - $p_zoom_api_description (string): The zoom API description.
    # - $p_api_key (string): The Zoom API key.
    # - $p_api_secret (string): The Zoom API secret.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertZoomAPI($p_zoom_api_name, $p_zoom_api_description, $p_api_key, $p_api_secret, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertZoomAPI(:p_zoom_api_name, :p_zoom_api_description, :p_api_key, :p_api_secret, :p_last_log_by, @p_zoom_api_id)');
        $stmt->bindValue(':p_zoom_api_name', $p_zoom_api_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_zoom_api_description', $p_zoom_api_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_api_key', $p_api_key, PDO::PARAM_STR);
        $stmt->bindValue(':p_api_secret', $p_api_secret, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_zoom_api_id AS p_zoom_api_id");
        $p_zoom_api_id = $result->fetch(PDO::FETCH_ASSOC)['p_zoom_api_id'];

        return $p_zoom_api_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkZoomAPIExist
    # Description: Checks if a zoom API exists.
    #
    # Parameters:
    # - $p_zoom_api_id (int): The zoom API ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkZoomAPIExist($p_zoom_api_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkZoomAPIExist(:p_zoom_api_id)');
        $stmt->bindValue(':p_zoom_api_id', $p_zoom_api_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteZoomAPI
    # Description: Deletes the zoom API.
    #
    # Parameters:
    # - $p_zoom_api_id (int): The zoom API ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteZoomAPI($p_zoom_api_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteZoomAPI(:p_zoom_api_id)');
        $stmt->bindValue(':p_zoom_api_id', $p_zoom_api_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getZoomAPI
    # Description: Retrieves the details of a zoom API.
    #
    # Parameters:
    # - $p_zoom_api_id (int): The zoom API ID.
    #
    # Returns:
    # - An array containing the zoom API details.
    #
    # -------------------------------------------------------------
    public function getZoomAPI($p_zoom_api_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getZoomAPI(:p_zoom_api_id)');
        $stmt->bindValue(':p_zoom_api_id', $p_zoom_api_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateZoomAPI
    # Description: Duplicates the zoom API.
    #
    # Parameters:
    # - $p_zoom_api_id (int): The zoom API ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateZoomAPI($p_zoom_api_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateZoomAPI(:p_zoom_api_id, :p_last_log_by, @p_new_zoom_api_id)');
        $stmt->bindValue(':p_zoom_api_id', $p_zoom_api_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_zoom_api_id AS zoom_api_id");
        $systemActionID = $result->fetch(PDO::FETCH_ASSOC)['zoom_api_id'];

        return $systemActionID;
    }
    # -------------------------------------------------------------
}
?>