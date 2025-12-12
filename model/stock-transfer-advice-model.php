<?php
/**
* Class StockTransferAdviceModel
*
* The StockTransferAdviceModel class handles stock transfer advice related operations and interactions.
*/
class StockTransferAdviceModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateStockTransferAdvice
    # Description: Updates the stock transfer advice.
    #
    # Parameters:
    # - $p_stock_transfer_advice_id (int): The stock transfer advice ID.
    # - $p_stock_transfer_advice_name (string): The stock transfer advice name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateStockTransferAdviceCart($p_stock_transfer_advice_id, $p_quantity, $p_price, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateStockTransferAdviceCart(:p_stock_transfer_advice_id, :p_quantity, :p_price, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_quantity', $p_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_price', $p_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePartTransactionSummary($p_stock_transfer_advice_id) {
        $stmt = $this->db->getConnection()->prepare('CALL UpdatePartTransactionSummary(:p_stock_transfer_advice_id)');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateStockTransferAdviceStatus($p_stock_transfer_advice_id, $p_stock_transfer_advice_status, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateStockTransferAdviceStatus(:p_stock_transfer_advice_id, :p_stock_transfer_advice_status, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_stock_transfer_advice_status', $p_stock_transfer_advice_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateStockTransferAdvice($p_stock_transfer_advice_id, $p_transferred_from, $p_transferred_to, $p_company_id, $p_customer_id, $p_sta_type, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateStockTransferAdvice(:p_stock_transfer_advice_id, :p_transferred_from, :p_transferred_to, :p_company_id, :p_customer_id, :p_sta_type, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_transferred_from', $p_transferred_from, PDO::PARAM_INT);
        $stmt->bindValue(':p_transferred_to', $p_transferred_to, PDO::PARAM_INT);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_customer_id', $p_customer_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_sta_type', $p_sta_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updateStockTransferAdviceOnProcess($p_stock_transfer_advice_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE stock_transfer_advice
        SET 
            on_process_date = NOW(),
            sta_status = "On-Process",
            approval_by = :p_approval_by,
            last_log_by = :p_last_log_by
        WHERE stock_transfer_advice_id = :p_stock_transfer_advice_id;');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_approval_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updateStockTransferAdviceForApproval($p_stock_transfer_advice_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE stock_transfer_advice
        SET 
            for_approval_date = NOW(),
            sta_status = "For Approval",
            last_log_by = :p_last_log_by
        WHERE stock_transfer_advice_id = :p_stock_transfer_advice_id;');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updateStockTransferAdviceComplete($p_stock_transfer_advice_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE stock_transfer_advice
        SET 
            completion_date = NOW(),
            sta_status = "Completed",
            last_log_by = :p_last_log_by
        WHERE stock_transfer_advice_id = :p_stock_transfer_advice_id;');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updateStockTransferAdvicePosted($p_stock_transfer_advice_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE stock_transfer_advice
        SET 
            posted_date = NOW(),
            sta_status = "Posted",
            last_log_by = :p_last_log_by
        WHERE stock_transfer_advice_id = :p_stock_transfer_advice_id;');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updateStockTransferAdviceStatusDraft($p_stock_transfer_advice_id, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE stock_transfer_advice
        SET 
            set_to_draft_date = NOW(),
            sta_status = "Draft",
            set_to_draft_reason = :p_remarks,
            last_log_by = :p_last_log_by
        WHERE stock_transfer_advice_id = :p_stock_transfer_advice_id;');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updateStockTransferAdviceStatusCancel($p_stock_transfer_advice_id, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE stock_transfer_advice
        SET 
            cancellation_date = NOW(),
            sta_status = "Cancelled",
            cancellation_reason = :p_remarks,
            last_log_by = :p_last_log_by
        WHERE stock_transfer_advice_id = :p_stock_transfer_advice_id;');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_STR);
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
    # Function: insertStockTransferAdvice
    # Description: Inserts the stock transfer advice.
    #
    # Parameters:
    # - $p_stock_transfer_advice_id (string): The stock transfer advice ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertStockTransferAdvice($reference_number, $transferred_from, $transferred_to, $p_company_id, $p_customer_id, $p_sta_type, $remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertStockTransferAdvice(:reference_number, :transferred_from, :transferred_to, :p_company_id, :p_customer_id, :p_sta_type, :remarks, :p_last_log_by, @p_stock_transfer_advice_id)');
        $stmt->bindValue(':reference_number', $reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':transferred_from', $transferred_from, PDO::PARAM_INT);
        $stmt->bindValue(':transferred_to', $transferred_to, PDO::PARAM_INT);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_customer_id', $p_customer_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_sta_type', $p_sta_type, PDO::PARAM_STR);
        $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_stock_transfer_advice_id AS p_stock_transfer_advice_id");
        $p_stock_transfer_advice_id = $result->fetch(PDO::FETCH_ASSOC)['p_stock_transfer_advice_id'];

        return $p_stock_transfer_advice_id;
    }

    public function createStockTransferAdviceProductExpense($p_product_id, $p_reference_type, $p_reference_number, $p_expense_amount, $p_expense_type, $p_particulars, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('INSERT INTO product_expense (
            product_id,
            reference_type,
            reference_number,
            expense_amount,
            expense_type,
            particulars,
            issuance_date,
            last_log_by
        ) 
        VALUES (
            :p_product_id,
            :p_reference_type,
            :p_reference_number,
            :p_expense_amount,
            :p_expense_type,
            :p_particulars,
            NOW(),
            :p_last_log_by
        );');
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_type', $p_reference_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_expense_amount', $p_expense_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_expense_type', $p_expense_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_particulars', $p_particulars, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertStockTransferAdviceReplacement($stock_transfer_advice_cart_id, $stock_transfer_advice_id, $part_replace, $part_id, $replacement_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('INSERT INTO stock_transfer_advice_replacement (
            stock_transfer_advice_cart_id,
            stock_transfer_advice_id,
            part_replace,
            part_id,
            remarks,
            last_log_by
        ) 
        VALUES (
            :stock_transfer_advice_cart_id,
            :stock_transfer_advice_id,
            :part_replace,
            :part_id,
            :replacement_remarks,
            :p_last_log_by
        );');
        $stmt->bindValue(':stock_transfer_advice_cart_id', $stock_transfer_advice_cart_id, PDO::PARAM_STR);
        $stmt->bindValue(':stock_transfer_advice_id', $stock_transfer_advice_id, PDO::PARAM_STR);
        $stmt->bindValue(':part_replace', $part_replace, PDO::PARAM_STR);
        $stmt->bindValue(':part_id', $part_id, PDO::PARAM_STR);
        $stmt->bindValue(':replacement_remarks', $replacement_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateStockTransferAdviceSlipReferenceNumber($p_stock_transfer_advice_id, $slip_reference_no, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateStockTransferAdviceSlipReferenceNumber(:p_stock_transfer_advice_id, :slip_reference_no, :p_last_log_by)');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_STR);
        $stmt->bindValue(':slip_reference_no', $slip_reference_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertStockTransferAdvicePartItem($p_stock_transfer_advice_id, $p_part_id, $part_from, $part_price, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('INSERT INTO stock_transfer_advice_cart (
        stock_transfer_advice_id,
        part_id,
        price,
        quantity,
        part_from,
        last_log_by
    ) VALUES (
        :p_stock_transfer_advice_id,
        :p_part_id,
        :part_price,
        1,
        :part_from,
        :p_last_log_by
    )');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_part_id', $p_part_id, PDO::PARAM_INT);
        $stmt->bindValue(':part_from', $part_from, PDO::PARAM_STR);
        $stmt->bindValue(':part_price', $part_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertStockTransferAdviceJobOrder($p_stock_transfer_advice_id, $p_job_order_id, $p_type, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('INSERT INTO stock_transfer_advice_job_order (
            stock_transfer_advice_id,
            job_order_id,
            type,
            last_log_by
        ) VALUES (
            :p_stock_transfer_advice_id,
            :p_job_order_id,
            :p_type,
            :p_last_log_by
        );');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_job_order_id', $p_job_order_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertStockTransferAdviceAdditionalJobOrder($p_stock_transfer_advice_id, $p_additional_job_order_id, $p_type, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare(' INSERT INTO stock_transfer_advice_additional_job_order (
            stock_transfer_advice_id,
            additional_job_order_id,
            type,
            last_log_by
        ) VALUES (
            :p_stock_transfer_advice_id,
            :p_additional_job_order_id,
            :p_type,
            :p_last_log_by
        );');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_additional_job_order_id', $p_additional_job_order_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertStockTransferAdviceDocument($p_stock_transfer_advice_id, $p_document_name, $p_document_file_path, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertStockTransferAdviceDocument(:p_stock_transfer_advice_id, :p_document_name, :p_document_file_path, :p_last_log_by)');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_name', $p_document_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_file_path', $p_document_file_path, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

     public function createStockTransferAdviceEntry($p_part_incoming_id, $p_company_id, $p_reference_number, $p_transferred_to, $p_cost, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createStockTransferAdviceEntry(:p_part_incoming_id, :p_company_id, :p_reference_number, :p_transferred_to, :p_cost, :p_last_log_by)');
        $stmt->bindValue(':p_part_incoming_id', $p_part_incoming_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_transferred_to', $p_transferred_to, PDO::PARAM_INT);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkStockTransferAdviceExist
    # Description: Checks if a stock transfer advice exists.
    #
    # Parameters:
    # - $p_stock_transfer_advice_id (int): The stock transfer advice ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkStockTransferAdviceExist($p_stock_transfer_advice_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkStockTransferAdviceExist(:p_stock_transfer_advice_id)');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_exceeded_part_quantity_count($p_stock_transfer_advice_id) {
        $stmt = $this->db->getConnection()->prepare('CALL get_exceeded_part_quantity_count(:p_stock_transfer_advice_id)');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function check_exceed_part_quantity($p_stock_transfer_advice_id) {
        $stmt = $this->db->getConnection()->prepare('CALL check_exceed_part_quantity(:p_stock_transfer_advice_id)');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function check_linked_job_order($p_stock_transfer_advice_id) {
        $stmt = $this->db->getConnection()->prepare('CALL check_linked_job_order(:p_stock_transfer_advice_id)');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkStockTransferAdviceCartExist($p_stock_transfer_advice_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkStockTransferAdviceCartExist(:p_stock_transfer_advice_id)');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function checkStockTransferAdviceReplacementExist($stock_transfer_advice_id, $stock_transfer_advice_cart_id, $part_replace, $part_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT COUNT(*) AS total
    FROM stock_transfer_advice_replacement
    WHERE stock_transfer_advice_id = :stock_transfer_advice_id AND stock_transfer_advice_cart_id = :stock_transfer_advice_cart_id AND part_replace = :part_replace AND part_id = :part_id;');
        $stmt->bindValue(':stock_transfer_advice_id', $stock_transfer_advice_id, PDO::PARAM_INT);
        $stmt->bindValue(':stock_transfer_advice_cart_id', $stock_transfer_advice_cart_id, PDO::PARAM_INT);
        $stmt->bindValue(':part_replace', $part_replace, PDO::PARAM_STR);
        $stmt->bindValue(':part_id', $part_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteStockTransferAdvice
    # Description: Deletes the stock transfer advice.
    #
    # Parameters:
    # - $p_stock_transfer_advice_id (int): The stock transfer advice ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteStockTransferAdvice($p_stock_transfer_advice_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteStockTransferAdvice(:p_stock_transfer_advice_id)');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deleteStockTransferAdviceCart($p_stock_transfer_advice_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteStockTransferAdviceCart(:p_stock_transfer_advice_id)');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deleteStockTransferAdviceJobOrder($p_stock_transfer_advice_job_order_id) {
        $stmt = $this->db->getConnection()->prepare(' DELETE FROM stock_transfer_advice_job_order WHERE stock_transfer_advice_job_order_id = :p_stock_transfer_advice_job_order_id;');
        $stmt->bindValue(':p_stock_transfer_advice_job_order_id', $p_stock_transfer_advice_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deleteStockTransferAdviceAdditionalJobOrder($p_stock_transfer_advice_additional_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('DELETE FROM stock_transfer_advice_additional_job_order WHERE stock_transfer_advice_additional_job_order_id = :p_stock_transfer_advice_additional_job_order_id');
        $stmt->bindValue(':p_stock_transfer_advice_additional_job_order_id', $p_stock_transfer_advice_additional_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deletePartReplacement($stock_transfer_advice_replacement_id) {
        $stmt = $this->db->getConnection()->prepare('DELETE FROM stock_transfer_advice_replacement WHERE stock_transfer_advice_replacement_id = :stock_transfer_advice_replacement_id');
        $stmt->bindValue(':stock_transfer_advice_replacement_id', $stock_transfer_advice_replacement_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getStockTransferAdvice
    # Description: Retrieves the details of a stock transfer advice.
    #
    # Parameters:
    # - $p_stock_transfer_advice_id (int): The stock transfer advice ID.
    #
    # Returns:
    # - An array containing the stock transfer advice details.
    #
    # -------------------------------------------------------------
    public function getStockTransferAdvice($p_stock_transfer_advice_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getStockTransferAdvice(:p_stock_transfer_advice_id)');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStockTransferAdviceCart($p_stock_transfer_advice_cart_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getStockTransferAdviceCart(:p_stock_transfer_advice_cart_id)');
        $stmt->bindValue(':p_stock_transfer_advice_cart_id', $p_stock_transfer_advice_cart_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStockTransferAdviceCartByMain($p_stock_transfer_advice_id, $p_part_from) {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM stock_transfer_advice_cart WHERE stock_transfer_advice_id = :p_stock_transfer_advice_id AND part_from = :p_part_from');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_part_from', $p_part_from, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStockTransferAdviceCartTotal($p_stock_transfer_advice_id, $p_part_from) {
        $stmt = $this->db->getConnection()->prepare('SELECT SUM(price) AS total FROM stock_transfer_advice_cart
        WHERE stock_transfer_advice_id = :p_stock_transfer_advice_id AND part_from = :p_part_from');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_part_from', $p_part_from, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStockTransferAdviceItemPrice($p_part_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT cost 
        from part_incoming_cart 
        LEFT JOIN part_incoming ON part_incoming.part_incoming_id = part_incoming_cart.part_incoming_id
        WHERE part_id = :p_part_id AND part_incoming_status IN ("Completed", "Posted") ORDER BY completion_date DESC LIMIT 1;');
        $stmt->bindValue(':p_part_id', $p_part_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAssignedPartCount($p_stock_transfer_advice_id, $p_part_from) {

        $stmt = $this->db->getConnection()->prepare('SELECT COUNT(stock_transfer_advice_cart_id) AS total FROM stock_transfer_advice_cart
            WHERE stock_transfer_advice_id = :p_stock_transfer_advice_id AND part_from = :p_part_from');

        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_part_from', $p_part_from, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAssignedPartPrice($p_stock_transfer_advice_id, $p_part_from) {
        $stmt = $this->db->getConnection()->prepare('SELECT COUNT(stock_transfer_advice_cart_id) AS total FROM stock_transfer_advice_cart
            WHERE stock_transfer_advice_id = :p_stock_transfer_advice_id AND part_from = :p_part_from AND price = 0');

        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_part_from', $p_part_from, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getJobOrderCount($p_stock_transfer_advice_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT COUNT(stock_transfer_advice_job_order_id) AS total FROM stock_transfer_advice_job_order
            WHERE stock_transfer_advice_id = :p_stock_transfer_advice_id');

        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAdditionalJobOrderCount($p_stock_transfer_advice_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT COUNT(stock_transfer_advice_additional_job_order_id) AS total FROM stock_transfer_advice_additional_job_order
            WHERE stock_transfer_advice_id = :p_stock_transfer_advice_id');

        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateStockTransferAdviceOptions
    # Description: Generates the stock transfer advice options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateStockTransferAdviceOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateStockTransferAdviceOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $stockTransferAdviceID = $row['stock_transfer_advice_id'];
            $stockTransferAdviceName = $row['stock_transfer_advice_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($stockTransferAdviceID, ENT_QUOTES) . '">' . htmlspecialchars($stockTransferAdviceName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    public function generatePartTransactionReleasedOptions($company_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM stock_transfer_advice WHERE (stock_transfer_advice_status = "Released" OR stock_transfer_advice_status = "Checked") AND company_id = :company_id ORDER BY stock_transfer_advice_id ASC');
        $stmt->bindValue(':company_id', $company_id, PDO::PARAM_INT);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $stockTransferAdviceID = $row['stock_transfer_advice_id'];
            $stockTransferAdviceName = $row['reference_number'];
            $issuance_no = $row['issuance_no'];

            $htmlOptions .= '<option value="' . htmlspecialchars($stockTransferAdviceID, ENT_QUOTES) . '">Reference No: ' . htmlspecialchars($stockTransferAdviceName, ENT_QUOTES) .' - Issuance No: '. $issuance_no .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateStockTransferAdviceCheckBox
    # Description: Generates the stock transfer advice check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateStockTransferAdviceCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateStockTransferAdviceOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $stockTransferAdviceID = $row['stock_transfer_advice_id'];
            $stockTransferAdviceName = $row['stock_transfer_advice_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input stock-transfer-advice-filter" type="checkbox" id="stock-transfer-advice-' . htmlspecialchars($stockTransferAdviceID, ENT_QUOTES) . '" value="' . htmlspecialchars($stockTransferAdviceID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="stock-transfer-advice-' . htmlspecialchars($stockTransferAdviceID, ENT_QUOTES) . '">' . htmlspecialchars($stockTransferAdviceName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>