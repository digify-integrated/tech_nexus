<?php
/**
* Class TransmittalModel
*
* The TransmittalModel class handles transmittal related operations and interactions.
*/
class TransmittalModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateTransmittal
    # Description: Updates the transmittal.
    #
    # Parameters:
    # - $p_transmittal_id (int): The transmittal ID.
    # - $p_transmittal_description (string): The transmittal description.
    # - $p_receiver_id (int): The receiver id.
    # - $p_receiver_name (string): The receiver name.
    # - $p_receiver_department (int): The receiver department.
    # - $p_receiver_department_name (string): The receiver department name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateTransmittal($p_transmittal_id, $p_transmittal_description, $p_receiver_id, $p_receiver_name, $p_receiver_department, $p_receiver_department_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateTransmittal(:p_transmittal_id, :p_transmittal_description, :p_receiver_id, :p_receiver_name, :p_receiver_department, :p_receiver_department_name, :p_last_log_by)');
        $stmt->bindValue(':p_transmittal_id', $p_transmittal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_transmittal_description', $p_transmittal_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_receiver_id', $p_receiver_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_receiver_name', $p_receiver_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_receiver_department', $p_receiver_department, PDO::PARAM_INT);
        $stmt->bindValue(':p_receiver_department_name', $p_receiver_department_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateReTransmittal
    # Description: Updates the re-transmittal.
    #
    # Parameters:
    # - $p_transmittal_description (string): The transmittal description.
    # - $p_created_by (int): The transmittal description.
    # - $p_transmitter_id (int): The transmitter ID.
    # - $p_transmitter_name (string): The transmitter name.
    # - $p_transmitter_department (int): The transmitter department.
    # - $p_transmitter_department_name (string): The transmitter department name.
    # - $p_receiver_id (int): The receiver ID.
    # - $p_receiver_name (string): The receiver name.
    # - $p_receiver_department (int): The receiver department.
    # - $p_receiver_department_name (string): The receiver department name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function updateReTransmittal($p_transmittal_id, $p_transmittal_description, $p_transmitter_id, $p_transmitter_name, $p_transmitter_department, $p_transmitter_department_name, $p_receiver_id, $p_receiver_name, $p_receiver_department, $p_receiver_department_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateReTransmittal(:p_transmittal_id, :p_transmittal_description, :p_transmitter_id, :p_transmitter_name, :p_transmitter_department, :p_transmitter_department_name, :p_receiver_id, :p_receiver_name, :p_receiver_department, :p_receiver_department_name, :p_last_log_by)');
        $stmt->bindValue(':p_transmittal_id', $p_transmittal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_transmittal_description', $p_transmittal_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_transmitter_id', $p_transmitter_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_transmitter_name', $p_transmitter_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_transmitter_department', $p_transmitter_department, PDO::PARAM_INT);
        $stmt->bindValue(':p_transmitter_department_name', $p_transmitter_department_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_receiver_id', $p_receiver_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_receiver_name', $p_receiver_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_receiver_department', $p_receiver_department, PDO::PARAM_INT);
        $stmt->bindValue(':p_receiver_department_name', $p_receiver_department_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateTransmittalStatus
    # Description: Updates the transmittal.
    #
    # Parameters:
    # - $p_transmittal_id (int): The transmittal ID.
    # - $p_transmittal_status (string): The transmittal status.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateTransmittalStatus($p_transmittal_id, $p_transmittal_status, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateTransmittalStatus(:p_transmittal_id, :p_transmittal_status, :p_last_log_by)');
        $stmt->bindValue(':p_transmittal_id', $p_transmittal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_transmittal_status', $p_transmittal_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertTransmittal
    # Description: Inserts the transmittal.
    #
    # Parameters:
    # - $p_transmittal_description (string): The transmittal description.
    # - $p_created_by (int): The transmittal description.
    # - $p_transmitter_id (int): The transmitter ID.
    # - $p_transmitter_name (string): The transmitter name.
    # - $p_transmitter_department (int): The transmitter department.
    # - $p_transmitter_department_name (string): The transmitter department name.
    # - $p_receiver_id (int): The receiver ID.
    # - $p_receiver_name (string): The receiver name.
    # - $p_receiver_department (int): The receiver department.
    # - $p_receiver_department_name (string): The receiver department name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertTransmittal($p_transmittal_description, $p_created_by, $p_transmitter_id, $p_transmitter_name, $p_transmitter_department, $p_transmitter_department_name, $p_receiver_id, $p_receiver_name, $p_receiver_department, $p_receiver_department_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertTransmittal(:p_transmittal_description, :p_created_by, :p_transmitter_id, :p_transmitter_name, :p_transmitter_department, :p_transmitter_department_name, :p_receiver_id, :p_receiver_name, :p_receiver_department, :p_receiver_department_name, :p_last_log_by, @p_transmittal_id)');
        $stmt->bindValue(':p_transmittal_description', $p_transmittal_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_created_by', $p_created_by, PDO::PARAM_INT);
        $stmt->bindValue(':p_transmitter_id', $p_transmitter_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_transmitter_name', $p_transmitter_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_transmitter_department', $p_transmitter_department, PDO::PARAM_INT);
        $stmt->bindValue(':p_transmitter_department_name', $p_transmitter_department_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_receiver_id', $p_receiver_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_receiver_name', $p_receiver_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_receiver_department', $p_receiver_department, PDO::PARAM_INT);
        $stmt->bindValue(':p_receiver_department_name', $p_receiver_department_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_transmittal_id AS p_transmittal_id");
        $p_transmittal_id = $result->fetch(PDO::FETCH_ASSOC)['p_transmittal_id'];

        return $p_transmittal_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkTransmittalExist
    # Description: Checks if a transmittal exists.
    #
    # Parameters:
    # - $p_transmittal_id (int): The transmittal ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkTransmittalExist($p_transmittal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkTransmittalExist(:p_transmittal_id)');
        $stmt->bindValue(':p_transmittal_id', $p_transmittal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteTransmittal
    # Description: Deletes the transmittal.
    #
    # Parameters:
    # - $p_transmittal_id (int): The transmittal ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteTransmittal($p_transmittal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteTransmittal(:p_transmittal_id)');
        $stmt->bindValue(':p_transmittal_id', $p_transmittal_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getTransmittal
    # Description: Retrieves the details of a transmittal.
    #
    # Parameters:
    # - $p_transmittal_id (int): The transmittal ID.
    #
    # Returns:
    # - An array containing the transmittal details.
    #
    # -------------------------------------------------------------
    public function getTransmittal($p_transmittal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getTransmittal(:p_transmittal_id)');
        $stmt->bindValue(':p_transmittal_id', $p_transmittal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getTransmittalStatus
    # Description: Retrieves the transmittal status badge.
    #
    # Parameters:
    # - $p_transmittal_status (string): The transmittal status.
    #
    # Returns:
    # - An array containing the transmittal details.
    #
    # -------------------------------------------------------------
    public function getTransmittalStatus($p_transmittal_status) {
        $statusClasses = [
            'Draft' => 'light-secondary',
            'Transmitted' => 'light-primary',
            'Re-Transmitted' => 'light-info',
            'Received' => 'light-success',
            'Cancelled' => 'light-warning'
        ];
        
        $defaultClass = 'light-dark';
        
        $class = $statusClasses[$p_transmittal_status] ?? $defaultClass;
        
        return '<span class="badge bg-' . $class . '">' . $p_transmittal_status . '</span>';
    }
    # -------------------------------------------------------------
}
?>