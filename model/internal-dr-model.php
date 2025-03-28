<?php
/**
* Class InternalDRModel
*
* The InternalDRModel class handles internal DR related operations and interactions.
*/
class InternalDRModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateInternalDR
    # Description: Updates the internal DR.
    #
    # Parameters:
    # - $p_internal_dr_id (int): The internal DR ID.
    # - $p_release_to (string): The internal DR name.
    # - $p_release_mobile (string): The internal DR description.
    # - $p_api_key (string): The Internal DR key.
    # - $p_api_secret (string): The Internal DR secret.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateInternalDR($p_internal_dr_id, $p_release_to, $p_release_mobile, $p_release_address, $p_dr_number, $p_dr_type, $p_stock_number, $p_product_description, $p_engine_number, $p_chassis_number, $p_plate_number, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateInternalDR(:p_internal_dr_id, :p_release_to, :p_release_mobile, :p_release_address, :p_dr_number, :p_dr_type, :p_stock_number, :p_product_description, :p_engine_number, :p_chassis_number, :p_plate_number, :p_last_log_by)');
        $stmt->bindValue(':p_internal_dr_id', $p_internal_dr_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_release_to', $p_release_to, PDO::PARAM_STR);
        $stmt->bindValue(':p_release_mobile', $p_release_mobile, PDO::PARAM_STR);
        $stmt->bindValue(':p_release_address', $p_release_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_dr_number', $p_dr_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_dr_type', $p_dr_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_stock_number', $p_stock_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_description', $p_product_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_engine_number', $p_engine_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_chassis_number', $p_chassis_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_plate_number', $p_plate_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateInternalDRAsReleased
    # Description: Updates the internal DR.
    #
    # Parameters:
    # - $p_internal_dr_id (int): The internal DR ID.
    # - $p_release_to (string): The internal DR name.
    # - $p_release_mobile (string): The internal DR description.
    # - $p_api_key (string): The Internal DR key.
    # - $p_api_secret (string): The Internal DR secret.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateInternalDRAsReleased($p_internal_dr_id, $p_dr_status, $p_release_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateInternalDRAsReleased(:p_internal_dr_id, :p_dr_status, :p_release_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_internal_dr_id', $p_internal_dr_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_dr_status', $p_dr_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_release_remarks', $p_release_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateInternalDRAsCancelled
    # Description: Updates the internal DR.
    #
    # Parameters:
    # - $p_internal_dr_id (int): The internal DR ID.
    # - $p_release_to (string): The internal DR name.
    # - $p_release_mobile (string): The internal DR description.
    # - $p_api_key (string): The Internal DR key.
    # - $p_api_secret (string): The Internal DR secret.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateInternalDRAsCancelled($p_internal_dr_id, $p_dr_status, $p_cancellation_reason, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateInternalDRAsCancelled(:p_internal_dr_id, :p_dr_status, :p_cancellation_reason, :p_last_log_by)');
        $stmt->bindValue(':p_internal_dr_id', $p_internal_dr_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_dr_status', $p_dr_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_cancellation_reason', $p_cancellation_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateInternalDRAsOnProcess($p_internal_dr_id, $p_dr_status, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateInternalDRAsOnProcess(:p_internal_dr_id, :p_dr_status, :p_last_log_by)');
        $stmt->bindValue(':p_internal_dr_id', $p_internal_dr_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_dr_status', $p_dr_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateInternalDRAsReadyForRelease($p_internal_dr_id, $p_dr_status, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateInternalDRAsReadyForRelease(:p_internal_dr_id, :p_dr_status, :p_last_log_by)');
        $stmt->bindValue(':p_internal_dr_id', $p_internal_dr_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_dr_status', $p_dr_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function updateInternalDRAsForDR($p_internal_dr_id, $p_dr_status, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateInternalDRAsForDR(:p_internal_dr_id, :p_dr_status, :p_last_log_by)');
        $stmt->bindValue(':p_internal_dr_id', $p_internal_dr_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_dr_status', $p_dr_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function updateSalesProposalBackjobProgress($p_internal_dr_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalBackjobProgress(:p_internal_dr_id, :p_last_log_by)');
        $stmt->bindValue(':p_internal_dr_id', $p_internal_dr_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

     # -------------------------------------------------------------
    #
    # Function: updateInternalDRUnitImage
    # Description: Updates the sales proposal client confirmation.
    #
    # Parameters:
    # - $p_internal_dr_accessories_id (int): The sales proposal accessories ID.
    # - $p_client_confirmation (string): The sales proposal client confirmation image.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateInternalDRUnitImage($p_internal_dr_id, $p_unit_image, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateInternalDRUnitImage(:p_internal_dr_id, :p_unit_image, :p_last_log_by)');
        $stmt->bindValue(':p_internal_dr_id', $p_internal_dr_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_unit_image', $p_unit_image, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateInternalDROutgoingChecklist($p_internal_dr_id, $p_unit_image, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateInternalDROutgoingChecklist(:p_internal_dr_id, :p_unit_image, :p_last_log_by)');
        $stmt->bindValue(':p_internal_dr_id', $p_internal_dr_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_unit_image', $p_unit_image, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateInternalDRQualityControlForm($p_internal_dr_id, $p_unit_image, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateInternalDRQualityControlForm(:p_internal_dr_id, :p_unit_image, :p_last_log_by)');
        $stmt->bindValue(':p_internal_dr_id', $p_internal_dr_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_unit_image', $p_unit_image, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function updateInternalDRJobOrder($p_internal_dr_job_order_id, $p_progress, $p_contractor_id, $p_work_center_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateInternalDRJobOrder(:p_internal_dr_job_order_id, :p_progress, :p_contractor_id, :p_work_center_id, :p_last_log_by)');
        $stmt->bindValue(':p_internal_dr_job_order_id', $p_internal_dr_job_order_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_progress', $p_progress, PDO::PARAM_STR);
        $stmt->bindValue(':p_contractor_id', $p_contractor_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_work_center_id', $p_work_center_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateInternalDRAdditionalJobOrder($p_internal_dr_additional_job_order_id, $p_progress, $p_contractor_id, $p_work_center_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateInternalDRAdditionalJobOrder(:p_internal_dr_additional_job_order_id, :p_progress, :p_contractor_id, :p_work_center_id, :p_last_log_by)');
        $stmt->bindValue(':p_internal_dr_additional_job_order_id', $p_internal_dr_additional_job_order_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_progress', $p_progress, PDO::PARAM_STR);
        $stmt->bindValue(':p_contractor_id', $p_contractor_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_work_center_id', $p_work_center_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertInternalDR
    # Description: Inserts the internal DR.
    #
    # Parameters:
    # - $p_internal_dr_name (string): The internal DR name.
    # - $p_internal_dr_description (string): The internal DR description.
    # - $p_api_key (string): The Internal DR key.
    # - $p_api_secret (string): The Internal DR secret.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertInternalDR($p_release_to, $p_release_mobile, $p_release_address, $p_dr_number, $p_dr_type, $p_stock_number, $p_product_description, $p_engine_number, $p_chassis_number, $p_plate_number, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertInternalDR(:p_release_to, :p_release_mobile, :p_release_address, :p_dr_number, :p_dr_type, :p_stock_number, :p_product_description, :p_engine_number, :p_chassis_number, :p_plate_number, :p_last_log_by, @p_internal_dr_id)');
        $stmt->bindValue(':p_release_to', $p_release_to, PDO::PARAM_STR);
        $stmt->bindValue(':p_release_mobile', $p_release_mobile, PDO::PARAM_STR);
        $stmt->bindValue(':p_release_address', $p_release_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_dr_number', $p_dr_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_dr_type', $p_dr_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_stock_number', $p_stock_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_description', $p_product_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_engine_number', $p_engine_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_chassis_number', $p_chassis_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_plate_number', $p_plate_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_internal_dr_id AS p_internal_dr_id");
        $p_internal_dr_id = $result->fetch(PDO::FETCH_ASSOC)['p_internal_dr_id'];

        return $p_internal_dr_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkInternalDRExist
    # Description: Checks if a internal DR exists.
    #
    # Parameters:
    # - $p_internal_dr_id (int): The internal DR ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkInternalDRExist($p_internal_dr_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkInternalDRExist(:p_internal_dr_id)');
        $stmt->bindValue(':p_internal_dr_id', $p_internal_dr_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteInternalDR
    # Description: Deletes the internal DR.
    #
    # Parameters:
    # - $p_internal_dr_id (int): The internal DR ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteInternalDR($p_internal_dr_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteInternalDR(:p_internal_dr_id)');
        $stmt->bindValue(':p_internal_dr_id', $p_internal_dr_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteInternalDRJobOrder($p_internal_dr_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteInternalDRJobOrder(:p_internal_dr_job_order_id)');
        $stmt->bindValue(':p_internal_dr_job_order_id', $p_internal_dr_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteInternalDRAdditionalJobOrder($p_internal_dr_additional_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteInternalDRAdditionalJobOrder(:p_internal_dr_additional_job_order_id)');
        $stmt->bindValue(':p_internal_dr_additional_job_order_id', $p_internal_dr_additional_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getInternalDR
    # Description: Retrieves the details of a internal DR.
    #
    # Parameters:
    # - $p_internal_dr_id (int): The internal DR ID.
    #
    # Returns:
    # - An array containing the internal DR details.
    #
    # -------------------------------------------------------------
    public function getInternalDR($p_internal_dr_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getInternalDR(:p_internal_dr_id)');
        $stmt->bindValue(':p_internal_dr_id', $p_internal_dr_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getInternalDRJobOrder($p_internal_dr_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getInternalDRJobOrder(:p_internal_dr_job_order_id)');
        $stmt->bindValue(':p_internal_dr_job_order_id', $p_internal_dr_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getInternalDRAdditionalJobOrder($p_internal_dr_additional_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getInternalDRAdditionalJobOrder(:p_internal_dr_additional_job_order_id)');
        $stmt->bindValue(':p_internal_dr_additional_job_order_id', $p_internal_dr_additional_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function loadInternalDRJobOrder($p_internal_dr_id, $p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL loadInternalDRJobOrder(:p_internal_dr_id, :p_sales_proposal_id)');
        $stmt->bindValue(':p_internal_dr_id', $p_internal_dr_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getInternalDRJobOrderCount($p_internal_dr_id, $p_type): mixed {
        $stmt = $this->db->getConnection()->prepare('CALL getInternalDRJobOrderCount(:p_internal_dr_id, :p_type)');
        $stmt->bindValue(':p_internal_dr_id', $p_internal_dr_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>