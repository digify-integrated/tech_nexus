<?php
/**
* Class PurchaseRequestModel
*
* The PurchaseRequestModel class handles purchase request related operations and interactions.
*/
class PurchaseRequestModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updatePurchaseRequest
    # Description: Updates the purchase request.
    #
    # Parameters:
    # - $p_purchase_request_id (int): The purchase request ID.
    # - $p_purchase_request_name (string): The purchase request name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updatePurchaseRequestCart($p_part_transaction_cart_id, $p_quantity, $p_add_on, $p_discount, $p_discount_type, $p_discount_total, $p_sub_total, $p_total, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePurchaseRequestCart(:p_part_transaction_cart_id, :p_quantity, :p_add_on, :p_discount, :p_discount_type, :p_discount_total, :p_sub_total, :p_total, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_cart_id', $p_part_transaction_cart_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_quantity', $p_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_add_on', $p_add_on, PDO::PARAM_STR);
        $stmt->bindValue(':p_discount', $p_discount, PDO::PARAM_STR);
        $stmt->bindValue(':p_discount_type', $p_discount_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_discount_total', $p_discount_total, PDO::PARAM_STR);
        $stmt->bindValue(':p_sub_total', $p_sub_total, PDO::PARAM_STR);
        $stmt->bindValue(':p_total', $p_total, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePartTransactionSummary($p_purchase_request_id) {
        $stmt = $this->db->getConnection()->prepare('CALL UpdatePartTransactionSummary(:p_purchase_request_id)');
        $stmt->bindValue(':p_purchase_request_id', $p_purchase_request_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePurchaseRequestStatus($p_purchase_request_id, $p_part_transaction_status, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePurchaseRequestStatus(:p_purchase_request_id, :p_part_transaction_status, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_purchase_request_id', $p_purchase_request_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_part_transaction_status', $p_part_transaction_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPurchaseRequest
    # Description: Inserts the purchase request.
    #
    # Parameters:
    # - $p_part_transaction_id (string): The purchase request ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertPurchaseRequest($p_reference_number, $p_purchase_request_type, $p_company_id, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPurchaseRequest(:p_reference_number, :p_purchase_request_type, :p_company_id, :p_remarks, :p_last_log_by, @p_purchase_request_id)');
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_purchase_request_type', $p_purchase_request_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_purchase_request_id AS p_purchase_request_id");
        $p_purchase_request_id = $result->fetch(PDO::FETCH_ASSOC)['p_purchase_request_id'];

        return $p_purchase_request_id;
    }

    public function createPurchaseRequestProductExpense($p_product_id, $p_reference_type, $p_reference_number, $p_expense_amount, $p_expense_type, $p_particulars, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createPurchaseRequestProductExpense(:p_product_id, :p_reference_type, :p_reference_number, :p_expense_amount, :p_expense_type, :p_particulars, :p_last_log_by)');
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_type', $p_reference_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_expense_amount', $p_expense_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_expense_type', $p_expense_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_particulars', $p_particulars, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function createPurchaseRequestProductExpenseTemp($p_product_id, $p_reference_type, $p_reference_number, $p_expense_amount, $p_expense_type, $p_particulars, $p_issuance_date, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createPurchaseRequestProductExpenseTemp(:p_product_id, :p_reference_type, :p_reference_number, :p_expense_amount, :p_expense_type, :p_particulars, :p_issuance_date, :p_last_log_by)');
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_type', $p_reference_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_expense_amount', $p_expense_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_expense_type', $p_expense_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_particulars', $p_particulars, PDO::PARAM_STR);
        $stmt->bindValue(':p_issuance_date', $p_issuance_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePurchaseRequest($p_purchase_request_id, $p_purchase_request_type, $p_company_id, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePurchaseRequest(:p_purchase_request_id, :p_purchase_request_type, :p_company_id, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_purchase_request_id', $p_purchase_request_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_purchase_request_type', $p_purchase_request_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePurchaseRequestSlipReferenceNumber($p_part_transaction_id, $slip_reference_no, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePurchaseRequestSlipReferenceNumber(:p_part_transaction_id, :slip_reference_no, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':slip_reference_no', $slip_reference_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePurchaseRequestItem($purchase_request_cart_id, $description, $quantity, $unit_id, $short_name, $remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE purchase_request_cart SET description = :description, quantity = :quantity, available_order = :available_order, unit_id = :unit_id, short_name = :short_name, remarks = :remarks, last_log_by = :p_last_log_by WHERE purchase_request_cart_id = :purchase_request_cart_id');
        $stmt->bindValue(':purchase_request_cart_id', $purchase_request_cart_id, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':quantity', $quantity, PDO::PARAM_STR);
        $stmt->bindValue(':available_order', $quantity, PDO::PARAM_STR);
        $stmt->bindValue(':unit_id', $unit_id, PDO::PARAM_STR);
        $stmt->bindValue(':short_name', $short_name, PDO::PARAM_STR);
        $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePurchaseRequestForApproval($p_purchase_request_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE purchase_request SET purchase_request_status = "For Approval", for_approval_date = NOW(), last_log_by = :p_last_log_by WHERE purchase_request_id = :p_purchase_request_id');
        $stmt->bindValue(':p_purchase_request_id', $p_purchase_request_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePurchaseRequestDraft($p_purchase_request_id, $draft_reason, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE purchase_request SET purchase_request_status = "Draft", last_set_to_draft_reason = :draft_reason, last_set_to_draft_date = NOW(), last_log_by = :p_last_log_by WHERE purchase_request_id = :p_purchase_request_id');
        $stmt->bindValue(':p_purchase_request_id', $p_purchase_request_id, PDO::PARAM_STR);
        $stmt->bindValue(':draft_reason', $draft_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePurchaseRequestCancel($p_purchase_request_id, $draft_reason, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE purchase_request SET purchase_request_status = "Cancelled", cancellation_reason = :draft_reason, cancellation_date = NOW(), last_log_by = :p_last_log_by WHERE purchase_request_id = :p_purchase_request_id');
        $stmt->bindValue(':p_purchase_request_id', $p_purchase_request_id, PDO::PARAM_STR);
        $stmt->bindValue(':draft_reason', $draft_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePurchaseRequestApprove($p_purchase_request_id, $draft_reason, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE purchase_request SET purchase_request_status = "Approved", approval_remarks = :draft_reason, approval_date = NOW(), last_log_by = :p_last_log_by WHERE purchase_request_id = :p_purchase_request_id');
        $stmt->bindValue(':p_purchase_request_id', $p_purchase_request_id, PDO::PARAM_STR);
        $stmt->bindValue(':draft_reason', $draft_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertPurchaseRequestItem($purchase_request_id, $description, $quantity, $unit_id, $short_name, $remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('INSERT INTO purchase_request_cart (purchase_request_id, description, quantity, available_order, unit_id, short_name, remarks, last_log_by) VALUES (:purchase_request_id, :description, :quantity, :available_order, :unit_id, :short_name, :remarks, :p_last_log_by)');
        $stmt->bindValue(':purchase_request_id', $purchase_request_id, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':quantity', $quantity, PDO::PARAM_STR);
        $stmt->bindValue(':available_order', $quantity, PDO::PARAM_STR);
        $stmt->bindValue(':unit_id', $unit_id, PDO::PARAM_STR);
        $stmt->bindValue(':short_name', $short_name, PDO::PARAM_STR);
        $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertPurchaseRequestJobOrder($p_part_transaction_id, $p_job_order_id, $p_type, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPurchaseRequestJobOrder(:p_part_transaction_id, :p_job_order_id, :p_type, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_job_order_id', $p_job_order_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertPurchaseRequestAdditionalJobOrder($p_part_transaction_id, $p_additional_job_order_id, $p_type, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPurchaseRequestAdditionalJobOrder(:p_part_transaction_id, :p_additional_job_order_id, :p_type, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_additional_job_order_id', $p_additional_job_order_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertPurchaseRequestDocument($p_part_transaction_id, $p_document_name, $p_document_file_path, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPurchaseRequestDocument(:p_part_transaction_id, :p_document_name, :p_document_file_path, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_name', $p_document_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_file_path', $p_document_file_path, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function createPurchaseRequestEntry($p_part_transaction_id, $p_company_id, $p_reference_number, $p_cost, $p_price, $p_customer_type, $p_is_service, $p_product_status, $p_issuance_for, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createPurchaseRequestEntry(:p_part_transaction_id, :p_company_id, :p_reference_number, :p_cost, :p_price, :p_customer_type, :p_is_service, :p_product_status, :p_issuance_for, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_price', $p_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_customer_type', $p_customer_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_is_service', $p_is_service, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_status', $p_product_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_issuance_for', $p_issuance_for, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function createPurchaseRequestEntryReversed($p_part_transaction_id, $p_company_id, $p_reference_number, $p_cost, $p_price, $p_customer_type, $p_is_service, $p_product_status, $p_issuance_for, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createPurchaseRequestEntryReversed(:p_part_transaction_id, :p_company_id, :p_reference_number, :p_cost, :p_price, :p_customer_type, :p_is_service, :p_product_status, :p_issuance_for, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_price', $p_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_customer_type', $p_customer_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_is_service', $p_is_service, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_status', $p_product_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_issuance_for', $p_issuance_for, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function createPurchaseRequestEntry2($p_part_transaction_id, $p_company_id, $p_reference_number, $p_cost, $p_price, $p_customer_type, $p_is_service, $p_product_status, $p_journal_entry_date, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createPurchaseRequestEntry2(:p_part_transaction_id, :p_company_id, :p_reference_number, :p_cost, :p_price, :p_customer_type, :p_is_service, :p_product_status, :p_journal_entry_date, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_price', $p_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_customer_type', $p_customer_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_is_service', $p_is_service, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_status', $p_product_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_journal_entry_date', $p_journal_entry_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkPurchaseRequestExist
    # Description: Checks if a purchase request exists.
    #
    # Parameters:
    # - $p_purchase_request_id (int): The purchase request ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkPurchaseRequestExist($p_purchase_request_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPurchaseRequestExist(:p_purchase_request_id)');
        $stmt->bindValue(':p_purchase_request_id', $p_purchase_request_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function checkPurchaseRequestItemExist($p_purchase_request_cart_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT COUNT(*) AS total
        FROM purchase_request_cart
        WHERE purchase_request_cart_id = :p_purchase_request_cart_id;');
        $stmt->bindValue(':p_purchase_request_cart_id', $p_purchase_request_cart_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_exceeded_part_quantity_count($p_purchase_request_id) {
        $stmt = $this->db->getConnection()->prepare('CALL get_exceeded_part_quantity_count(:p_purchase_request_id)');
        $stmt->bindValue(':p_purchase_request_id', $p_purchase_request_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function check_exceed_part_quantity($p_purchase_request_id) {
        $stmt = $this->db->getConnection()->prepare('CALL check_exceed_part_quantity(:p_purchase_request_id)');
        $stmt->bindValue(':p_purchase_request_id', $p_purchase_request_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function check_linked_job_order($p_purchase_request_id) {
        $stmt = $this->db->getConnection()->prepare('CALL check_linked_job_order(:p_purchase_request_id)');
        $stmt->bindValue(':p_purchase_request_id', $p_purchase_request_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkPurchaseRequestCartExist($p_part_transaction_cart_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPurchaseRequestCartExist(:p_part_transaction_cart_id)');
        $stmt->bindValue(':p_part_transaction_cart_id', $p_part_transaction_cart_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePurchaseRequest
    # Description: Deletes the purchase request.
    #
    # Parameters:
    # - $p_purchase_request_id (int): The purchase request ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deletePurchaseRequest($p_purchase_request_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePurchaseRequest(:p_purchase_request_id)');
        $stmt->bindValue(':p_purchase_request_id', $p_purchase_request_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deletePurchaseRequestCart($purchase_request_cart_id) {
        $stmt = $this->db->getConnection()->prepare('DELETE FROM purchase_request_cart WHERE purchase_request_cart_id = :purchase_request_cart_id');
        $stmt->bindValue(':purchase_request_cart_id', $purchase_request_cart_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deletePurchaseRequestJobOrder($p_purchase_request_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePurchaseRequestJobOrder(:p_purchase_request_job_order_id)');
        $stmt->bindValue(':p_purchase_request_job_order_id', $p_purchase_request_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deletePurchaseRequestAdditionalJobOrder($p_purchase_request_additional_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePurchaseRequestAdditionalJobOrder(:p_purchase_request_additional_job_order_id)');
        $stmt->bindValue(':p_purchase_request_additional_job_order_id', $p_purchase_request_additional_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function deletePurchaseRequestDocument($p_part_transaction_document_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePurchaseRequestDocument(:p_part_transaction_document_id)');
        $stmt->bindValue(':p_part_transaction_document_id', $p_part_transaction_document_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPurchaseRequest
    # Description: Retrieves the details of a purchase request.
    #
    # Parameters:
    # - $p_purchase_request_id (int): The purchase request ID.
    #
    # Returns:
    # - An array containing the purchase request details.
    #
    # -------------------------------------------------------------
    public function getPurchaseRequest($p_purchase_request_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getPurchaseRequest(:p_purchase_request_id)');
        $stmt->bindValue(':p_purchase_request_id', $p_purchase_request_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getPurchaseRequestCart($purchase_request_cart_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM purchase_request_cart WHERE purchase_request_cart_id = :purchase_request_cart_id');
        $stmt->bindValue(':purchase_request_cart_id', $purchase_request_cart_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getPurchaseRequestItemCount($p_purchase_request_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT COUNT(purchase_request_cart_id) AS total FROM purchase_request_cart WHERE purchase_request_id = :p_purchase_request_id');
        $stmt->bindValue(':p_purchase_request_id', $p_purchase_request_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPurchaseRequestCartTotal($p_part_transaction_id, $p_type) {
        $stmt = $this->db->getConnection()->prepare('CALL getPurchaseRequestCartTotal(:p_part_transaction_id, :p_type)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generatePurchaseRequestOptions
    # Description: Generates the purchase request options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePurchaseRequestOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePurchaseRequestOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsTransactionID = $row['purchase_request_id'];
            $partsTransactionName = $row['purchase_request_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($partsTransactionID, ENT_QUOTES) . '">' . htmlspecialchars($partsTransactionName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }

    public function generatePurchaseRequestCartOptions($type): string {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM purchase_request_cart WHERE purchase_request_id IN (SELECT purchase_request_id FROM purchase_request WHERE purchase_request_status = "Approved" AND purchase_request_type = :type) AND available_order > 0 ORDER BY purchase_request_cart_id ASC');
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $purchase_request_cart_id  = $row['purchase_request_cart_id'];
            $description = $row['description'];
            $available_order = $row['available_order'];

            $htmlOptions .= '<option value="' . htmlspecialchars($purchase_request_cart_id , ENT_QUOTES) . '">' . htmlspecialchars($description, ENT_QUOTES) .' (Available To Order: '. $available_order .')</option>';
        }

        return $htmlOptions;
    }

    public function generatePartTransactionReleasedOptions($company_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM part_transaction WHERE (part_transaction_status = "Released" OR part_transaction_status = "Checked") AND company_id = :company_id ORDER BY part_transaction_id ASC');
        $stmt->bindValue(':company_id', $company_id, PDO::PARAM_INT);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsTransactionID = $row['part_transaction_id'];
            $partsTransactionName = $row['reference_number'];
            $issuance_no = $row['issuance_no'];

            $htmlOptions .= '<option value="' . htmlspecialchars($partsTransactionID, ENT_QUOTES) . '">Reference No: ' . htmlspecialchars($partsTransactionName, ENT_QUOTES) .' - Issuance No: '. $issuance_no .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generatePurchaseRequestCheckBox
    # Description: Generates the purchase request check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePurchaseRequestCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePurchaseRequestOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsTransactionID = $row['purchase_request_id'];
            $partsTransactionName = $row['purchase_request_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input parts-transaction-filter" type="checkbox" id="parts-transaction-' . htmlspecialchars($partsTransactionID, ENT_QUOTES) . '" value="' . htmlspecialchars($partsTransactionID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="parts-transaction-' . htmlspecialchars($partsTransactionID, ENT_QUOTES) . '">' . htmlspecialchars($partsTransactionName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>