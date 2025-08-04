<?php
/**
* Class PartsPurchasedMonitoringModel
*
* The PartsPurchasedMonitoringModel class handles parts purchase monitoring related operations and interactions.
*/
class PartsPurchasedMonitoringModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    } 

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generatePartsPurchasedMonitoringOptions
    # Description: Generates the parts purchase monitoring options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePartsPurchasedMonitoringOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePartsPurchasedMonitoringOptions()');
        $stmt->execute();
        $count = $stmt->rowCount();

        if($count > 0){
            $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            $htmlOptions = '';

            foreach ($options as $row) {
                $languageProficiencyID = $row['parts_purchased_monitoring_id'];
                $languageProficiencyName = $row['parts_purchased_monitoring_name'];
                $description = $row['description'];

                $htmlOptions .= "<option value='". htmlspecialchars($languageProficiencyID, ENT_QUOTES) ."'>". htmlspecialchars($languageProficiencyName, ENT_QUOTES) ." - ". htmlspecialchars($description, ENT_QUOTES) ."</option>";
            }

            return $htmlOptions;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updatePartsPurchasedMonitoring
    # Description: Updates the parts purchase monitoring.
    #
    # Parameters:
    # - $p_parts_purchased_monitoring_id (int): The parts purchase monitoring ID.
    # - $p_parts_purchased_monitoring_name (string): The parts purchase monitoring name.
    # - $p_details (int): The details.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updatePartsPurchasedMonitoring($p_parts_purchased_monitoring_id, $p_parts_purchased_monitoring_name, $p_details, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsPurchasedMonitoring(:p_parts_purchased_monitoring_id, :p_parts_purchased_monitoring_name, :p_details, :p_last_log_by)');
        $stmt->bindValue(':p_parts_purchased_monitoring_id', $p_parts_purchased_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_parts_purchased_monitoring_name', $p_parts_purchased_monitoring_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_details', $p_details, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPartsPurchasedMonitoring
    # Description: Inserts the parts purchase monitoring.
    #
    # Parameters:
    # - $p_parts_purchased_monitoring_name (string): The parts purchase monitoring name.
    # - $p_details (int): The details.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertPartsPurchasedMonitoring($p_parts_purchased_monitoring_name, $p_details, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartsPurchasedMonitoring(:p_parts_purchased_monitoring_name, :p_details, :p_last_log_by, @p_parts_purchased_monitoring_id)');
        $stmt->bindValue(':p_parts_purchased_monitoring_name', $p_parts_purchased_monitoring_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_details', $p_details, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_parts_purchased_monitoring_id AS parts_purchased_monitoring_id");
        $languageProficiencyID = $result->fetch(PDO::FETCH_ASSOC)['parts_purchased_monitoring_id'];

        return $languageProficiencyID;
    }

    public function cancelPartsPurchasedMonitoringStatus($parts_purchased_monitoring_id, $cancellation_reason, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL cancelPartsPurchasedMonitoringStatus(:p_parts_purchased_monitoring_id, :p_cancellation_reason, :p_last_log_by)');
        $stmt->bindValue(':p_parts_purchased_monitoring_id', $parts_purchased_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_cancellation_reason', $cancellation_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function issuePartsPurchasedMonitoringStatus($parts_purchased_monitoring_id, $reference_number, $issued_quantity, $issuance_date, $remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL issuePartsPurchasedMonitoringStatus(:p_parts_purchased_monitoring_id, :p_reference_number, :p_issued_quantity, :p_issuance_date, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_parts_purchased_monitoring_id', $parts_purchased_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_reference_number', $reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_issued_quantity', $issued_quantity, PDO::PARAM_INT);
        $stmt->bindValue(':p_issuance_date', $issuance_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function tagPartsPurchasedMonitoringAsIssued($parts_purchased_monitoring_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL tagPartsPurchasedMonitoringAsIssued(:p_parts_purchased_monitoring_id, :p_last_log_by)');
        $stmt->bindValue(':p_parts_purchased_monitoring_id', $parts_purchased_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkPartsPurchasedMonitoringExist
    # Description: Checks if a parts purchase monitoring exists.
    #
    # Parameters:
    # - $p_parts_purchased_monitoring_id (int): The parts purchase monitoring ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkPartsPurchasedMonitoringExist($p_parts_purchased_monitoring_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPartsPurchasedMonitoringExist(:p_parts_purchased_monitoring_id)');
        $stmt->bindValue(':p_parts_purchased_monitoring_id', $p_parts_purchased_monitoring_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePartsPurchasedMonitoring
    # Description: Deletes the parts purchase monitoring.
    #
    # Parameters:
    # - $p_parts_purchased_monitoring_id (int): The parts purchase monitoring ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deletePartsPurchasedMonitoring($p_parts_purchased_monitoring_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePartsPurchasedMonitoring(:p_parts_purchased_monitoring_id)');
        $stmt->bindValue(':p_parts_purchased_monitoring_id', $p_parts_purchased_monitoring_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPartsPurchasedMonitoring
    # Description: Retrieves the details of a parts purchase monitoring.
    #
    # Parameters:
    # - $p_parts_purchased_monitoring_id (int): The parts purchase monitoring ID.
    #
    # Returns:
    # - An array containing the parts purchase monitoring details.
    #
    # -------------------------------------------------------------
    public function checkPartsPurchasedMonitoringItem($p_parts_purchased_monitoring_item_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPartsPurchasedMonitoringItem(:p_parts_purchased_monitoring_item_id)');
        $stmt->bindValue(':p_parts_purchased_monitoring_item_id', $p_parts_purchased_monitoring_item_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getPartsPurchasedMonitoringProgress($p_parts_purchased_monitoring_id, $type) {
        $stmt = $this->db->getConnection()->prepare('CALL getPartsPurchasedMonitoringProgress(:p_parts_purchased_monitoring_id, :type)');
        $stmt->bindValue(':p_parts_purchased_monitoring_id', $p_parts_purchased_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicatePartsPurchasedMonitoring
    # Description: Duplicates the parts purchase monitoring.
    #
    # Parameters:
    # - $p_parts_purchased_monitoring_id (int): The parts purchase monitoring ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicatePartsPurchasedMonitoring($p_parts_purchased_monitoring_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicatePartsPurchasedMonitoring(:p_parts_purchased_monitoring_id, :p_last_log_by, @p_new_parts_purchased_monitoring_id)');
        $stmt->bindValue(':p_parts_purchased_monitoring_id', $p_parts_purchased_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_parts_purchased_monitoring_id AS parts_purchased_monitoring_id");
        $languageProficiencyID = $result->fetch(PDO::FETCH_ASSOC)['parts_purchased_monitoring_id'];

        return $languageProficiencyID;
    }
    # -------------------------------------------------------------
}
?>