<?php
/**
* Class BackJobMonitoringModel
*
* The BackJobMonitoringModel class handles bank account type related operations and interactions.
*/
class BackJobMonitoringModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateBackJobMonitoring
    # Description: Updates the bank account type.
    #
    # Parameters:
    # - $p_backjob_monitoring_id (int): The bank account type ID.
    # - $p_backjob_monitoring_name (string): The bank account type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateBackJobMonitoring($p_backjob_monitoring_id, $p_type, $p_product_id, $p_sales_proposal_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBackJobMonitoring(:p_backjob_monitoring_id, :p_type, :p_product_id, :p_sales_proposal_id, :p_last_log_by)');
        $stmt->bindValue(':p_backjob_monitoring_id', $p_backjob_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateBackJobMonitoringUnitImage($p_backjob_monitoring_id, $p_unit_image, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBackJobMonitoringUnitImage(:p_backjob_monitoring_id, :p_unit_image, :p_last_log_by)');
        $stmt->bindValue(':p_backjob_monitoring_id', $p_backjob_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_unit_image', $p_unit_image, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateBackJobMonitoringOutgoingChecklist($p_backjob_monitoring_id, $p_unit_image, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBackJobMonitoringOutgoingChecklist(:p_backjob_monitoring_id, :p_unit_image, :p_last_log_by)');
        $stmt->bindValue(':p_backjob_monitoring_id', $p_backjob_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_unit_image', $p_unit_image, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateBackJobMonitoringQualityControlForm($p_backjob_monitoring_id, $p_unit_image, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBackJobMonitoringQualityControlForm(:p_backjob_monitoring_id, :p_unit_image, :p_last_log_by)');
        $stmt->bindValue(':p_backjob_monitoring_id', $p_backjob_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_unit_image', $p_unit_image, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function updateBackJobMonitoringJobOrder($p_backjob_monitoring_id, $p_backjob_monitoring_job_order_id, $p_progress, $p_contractor_id, $p_work_center_id, $p_completion_date, $p_cost, $p_job_order, $p_planned_start_date, $p_planned_finish_date, $p_date_started, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBackJobMonitoringJobOrder(:p_backjob_monitoring_id, :p_backjob_monitoring_job_order_id, :p_progress, :p_contractor_id, :p_work_center_id, :p_completion_date, :p_cost, :p_job_order, :p_planned_start_date, :p_planned_finish_date, :p_date_started, :p_last_log_by)');
        $stmt->bindValue(':p_backjob_monitoring_id', $p_backjob_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_backjob_monitoring_job_order_id', $p_backjob_monitoring_job_order_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_progress', $p_progress, PDO::PARAM_STR);
        $stmt->bindValue(':p_contractor_id', $p_contractor_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_work_center_id', $p_work_center_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_completion_date', $p_completion_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_job_order', $p_job_order, PDO::PARAM_STR);
        $stmt->bindValue(':p_planned_start_date', $p_planned_start_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_planned_finish_date', $p_planned_finish_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_date_started', $p_date_started, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateBackJobMonitoringAdditionalJobOrder($p_backjob_monitoring_id, $p_backjob_monitoring_additional_job_order_id, $p_progress, $p_contractor_id, $p_work_center_id, $p_completion_date, $p_cost, $p_job_order_number, $p_job_order_date, $p_particulars, $p_planned_start_date, $p_planned_finish_date, $p_date_started, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBackJobMonitoringAdditionalJobOrder(:p_backjob_monitoring_id, :p_backjob_monitoring_additional_job_order_id, :p_progress, :p_contractor_id, :p_work_center_id, :p_completion_date, :p_cost, :p_job_order_number, :p_job_order_date, :p_particulars, :p_planned_start_date, :p_planned_finish_date, :p_date_started, :p_last_log_by)');
        $stmt->bindValue(':p_backjob_monitoring_id', $p_backjob_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_backjob_monitoring_additional_job_order_id', $p_backjob_monitoring_additional_job_order_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_progress', $p_progress, PDO::PARAM_STR);
        $stmt->bindValue(':p_contractor_id', $p_contractor_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_work_center_id', $p_work_center_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_completion_date', $p_completion_date, PDO::PARAM_INT);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_job_order_number', $p_job_order_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_job_order_date', $p_job_order_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_particulars', $p_particulars, PDO::PARAM_STR);
        $stmt->bindValue(':p_planned_start_date', $p_planned_start_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_planned_finish_date', $p_planned_finish_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_date_started', $p_date_started, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateBackJobMonitoringAsReleased($p_backjob_monitoring_id, $p_status, $p_release_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBackJobMonitoringAsReleased(:p_backjob_monitoring_id, :p_status, :p_release_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_backjob_monitoring_id', $p_backjob_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_status', $p_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_release_remarks', $p_release_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateBackJobMonitoringAsCancelled($p_backjob_monitoring_id, $p_status, $p_cancellation_reason, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBackJobMonitoringAsCancelled(:p_backjob_monitoring_id, :p_status, :p_cancellation_reason, :p_last_log_by)');
        $stmt->bindValue(':p_backjob_monitoring_id', $p_backjob_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_status', $p_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_cancellation_reason', $p_cancellation_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateBackJobMonitoringAsApproved($p_backjob_monitoring_id, $p_status, $p_approval_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBackJobMonitoringAsApproved(:p_backjob_monitoring_id, :p_status, :p_approval_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_backjob_monitoring_id', $p_backjob_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_status', $p_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_approval_remarks', $p_approval_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateBackJobMonitoringAsDraft($p_backjob_monitoring_id, $p_status, $p_set_to_draft_reason, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBackJobMonitoringAsDraft(:p_backjob_monitoring_id, :p_status, :p_set_to_draft_reason, :p_last_log_by)');
        $stmt->bindValue(':p_backjob_monitoring_id', $p_backjob_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_status', $p_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_set_to_draft_reason', $p_set_to_draft_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateBackJobMonitoringAsForApproval($p_backjob_monitoring_id, $p_status, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBackJobMonitoringAsForApproval(:p_backjob_monitoring_id, :p_status, :p_last_log_by)');
        $stmt->bindValue(':p_backjob_monitoring_id', $p_backjob_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_status', $p_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateBackJobMonitoringAsOnProcess($p_backjob_monitoring_id, $p_status, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBackJobMonitoringAsOnProcess(:p_backjob_monitoring_id, :p_status, :p_last_log_by)');
        $stmt->bindValue(':p_backjob_monitoring_id', $p_backjob_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_status', $p_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateBackJobMonitoringAsReadyForRelease($p_backjob_monitoring_id, $p_status, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBackJobMonitoringAsReadyForRelease(:p_backjob_monitoring_id, :p_status, :p_last_log_by)');
        $stmt->bindValue(':p_backjob_monitoring_id', $p_backjob_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_status', $p_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function updateBackJobMonitoringAsForDR($p_backjob_monitoring_id, $p_status, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBackJobMonitoringAsForDR(:p_backjob_monitoring_id, :p_status, :p_last_log_by)');
        $stmt->bindValue(':p_backjob_monitoring_id', $p_backjob_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_status', $p_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function updateSalesProposalBackjobProgress($p_backjob_monitoring_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalBackjobProgress(:p_backjob_monitoring_id, :p_last_log_by)');
        $stmt->bindValue(':p_backjob_monitoring_id', $p_backjob_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertBackJobMonitoring
    # Description: Inserts the bank account type.
    #
    # Parameters:
    # - $p_backjob_monitoring_name (string): The bank account type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertBackJobMonitoring($p_type, $p_product_id, $p_sales_proposal_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare(
            'CALL insertBackJobMonitoring(:p_type, :p_product_id, :p_sales_proposal_id, :p_last_log_by, @p_backjob_monitoring_id)'
        );
    
        // Remove spaces after parameter names
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
    
        $stmt->execute();
    
        $result = $this->db->getConnection()->query("SELECT @p_backjob_monitoring_id AS p_backjob_monitoring_id");
        $p_backjob_monitoring_id = $result->fetch(PDO::FETCH_ASSOC)['p_backjob_monitoring_id'];
    
        return $p_backjob_monitoring_id;
    }
    
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkBackJobMonitoringExist
    # Description: Checks if a bank account type exists.
    #
    # Parameters:
    # - $p_backjob_monitoring_id (int): The bank account type ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkBackJobMonitoringExist($p_backjob_monitoring_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkBackJobMonitoringExist(:p_backjob_monitoring_id)');
        $stmt->bindValue(':p_backjob_monitoring_id', $p_backjob_monitoring_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBackJobMonitoring
    # Description: Deletes the bank account type.
    #
    # Parameters:
    # - $p_backjob_monitoring_id (int): The bank account type ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteBackJobMonitoring($p_backjob_monitoring_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteBackJobMonitoring(:p_backjob_monitoring_id)');
        $stmt->bindValue(':p_backjob_monitoring_id', $p_backjob_monitoring_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    
    public function deleteBackJobMonitoringJobOrder($p_backjob_monitoring_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteBackJobMonitoringJobOrder(:p_backjob_monitoring_job_order_id)');
        $stmt->bindValue(':p_backjob_monitoring_job_order_id', $p_backjob_monitoring_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteBackJobMonitoringAdditionalJobOrder($p_backjob_monitoring_additional_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteBackJobMonitoringAdditionalJobOrder(:p_backjob_monitoring_additional_job_order_id)');
        $stmt->bindValue(':p_backjob_monitoring_additional_job_order_id', $p_backjob_monitoring_additional_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBackJobMonitoring
    # Description: Retrieves the details of a bank account type.
    #
    # Parameters:
    # - $p_backjob_monitoring_id (int): The bank account type ID.
    #
    # Returns:
    # - An array containing the bank account type details.
    #
    # -------------------------------------------------------------
    public function getBackJobMonitoring($p_backjob_monitoring_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getBackJobMonitoring(:p_backjob_monitoring_id)');
        $stmt->bindValue(':p_backjob_monitoring_id', $p_backjob_monitoring_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBackJobMonitoringJobOrder($p_backjob_monitoring_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getBackJobMonitoringJobOrder(:p_backjob_monitoring_job_order_id)');
        $stmt->bindValue(':p_backjob_monitoring_job_order_id', $p_backjob_monitoring_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBackJobMonitoringAdditionalJobOrder($p_backjob_monitoring_additional_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getBackJobMonitoringAdditionalJobOrder(:p_backjob_monitoring_additional_job_order_id)');
        $stmt->bindValue(':p_backjob_monitoring_additional_job_order_id', $p_backjob_monitoring_additional_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function loadBackJobMonitoringJobOrder($p_backjob_monitoring_id, $p_sales_proposal_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL loadBackJobMonitoringJobOrder(:p_backjob_monitoring_id, :p_sales_proposal_id, :p_last_log_by)');
        $stmt->bindValue(':p_backjob_monitoring_id', $p_backjob_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateBackJobMonitoringOptions
    # Description: Generates the bank account type options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateBackJobMonitoringOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateBackJobMonitoringOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $bankAccountTypeID = $row['backjob_monitoring_id'];
            $sales_proposal_number = $row['sales_proposal_number'];
            $type = $row['type'];
            $stock_number = $row['stock_number'];
            $description = $row['description'];

            if($type == 'Warranty'){
                $htmlOptions .= '<option value="' . htmlspecialchars($bankAccountTypeID, ENT_QUOTES) . '">Warranty - ' . $stock_number .' - '. $description .'</option>';
            }
            else{
                $htmlOptions .= '<option value="' . htmlspecialchars($bankAccountTypeID, ENT_QUOTES) . '">Back Job - ' . $sales_proposal_number .'</option>';
            }

           
        }

        return $htmlOptions;
    }

    public function getBackJobMonitoringJobOrderCount($p_backjob_monitoring_id, $p_type): mixed {
        $stmt = $this->db->getConnection()->prepare('CALL getBackJobMonitoringJobOrderCount(:p_backjob_monitoring_id, :p_type)');
        $stmt->bindValue(':p_backjob_monitoring_id', $p_backjob_monitoring_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>