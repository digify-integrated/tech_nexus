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
    public function updateStockTransferAdviceCart($p_part_transaction_cart_id, $p_quantity, $p_add_on, $p_discount, $p_discount_type, $p_discount_total, $p_sub_total, $p_total, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateStockTransferAdviceCart(:p_part_transaction_cart_id, :p_quantity, :p_add_on, :p_discount, :p_discount_type, :p_discount_total, :p_sub_total, :p_total, :p_remarks, :p_last_log_by)');
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

    public function updatePartTransactionSummary($p_stock_transfer_advice_id) {
        $stmt = $this->db->getConnection()->prepare('CALL UpdatePartTransactionSummary(:p_stock_transfer_advice_id)');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateStockTransferAdviceStatus($p_stock_transfer_advice_id, $p_part_transaction_status, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateStockTransferAdviceStatus(:p_stock_transfer_advice_id, :p_part_transaction_status, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_part_transaction_status', $p_part_transaction_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateStockTransferAdvice($p_stock_transfer_advice_id, $p_transferred_from, $p_transferred_to, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateStockTransferAdvice(:p_stock_transfer_advice_id, :p_transferred_from, :p_transferred_to, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_stock_transfer_advice_id', $p_stock_transfer_advice_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_transferred_from', $p_transferred_from, PDO::PARAM_INT);
        $stmt->bindValue(':p_transferred_to', $p_transferred_to, PDO::PARAM_INT);
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
    # - $p_part_transaction_id (string): The stock transfer advice ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertStockTransferAdvice($reference_number, $transferred_from, $transferred_to, $remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertStockTransferAdvice(:reference_number, :transferred_from, :transferred_to, :remarks, :p_last_log_by, @p_stock_transfer_advice_id)');
        $stmt->bindValue(':reference_number', $reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':transferred_from', $transferred_from, PDO::PARAM_INT);
        $stmt->bindValue(':transferred_to', $transferred_to, PDO::PARAM_INT);
        $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_stock_transfer_advice_id AS p_stock_transfer_advice_id");
        $p_stock_transfer_advice_id = $result->fetch(PDO::FETCH_ASSOC)['p_stock_transfer_advice_id'];

        return $p_stock_transfer_advice_id;
    }

    public function createStockTransferAdviceProductExpense($p_product_id, $p_reference_type, $p_reference_number, $p_expense_amount, $p_expense_type, $p_particulars, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createStockTransferAdviceProductExpense(:p_product_id, :p_reference_type, :p_reference_number, :p_expense_amount, :p_expense_type, :p_particulars, :p_last_log_by)');
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_type', $p_reference_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_expense_amount', $p_expense_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_expense_type', $p_expense_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_particulars', $p_particulars, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function createStockTransferAdviceProductExpenseTemp($p_product_id, $p_reference_type, $p_reference_number, $p_expense_amount, $p_expense_type, $p_particulars, $p_issuance_date, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createStockTransferAdviceProductExpenseTemp(:p_product_id, :p_reference_type, :p_reference_number, :p_expense_amount, :p_expense_type, :p_particulars, :p_issuance_date, :p_last_log_by)');
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

    public function updateStockTransferAdviceSlipReferenceNumber($p_part_transaction_id, $slip_reference_no, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateStockTransferAdviceSlipReferenceNumber(:p_part_transaction_id, :slip_reference_no, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':slip_reference_no', $slip_reference_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertPartItem($p_part_transaction_id, $p_part_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartItem(:p_part_transaction_id, :p_part_id, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_part_id', $p_part_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertStockTransferAdviceJobOrder($p_part_transaction_id, $p_job_order_id, $p_type, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertStockTransferAdviceJobOrder(:p_part_transaction_id, :p_job_order_id, :p_type, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_job_order_id', $p_job_order_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertStockTransferAdviceAdditionalJobOrder($p_part_transaction_id, $p_additional_job_order_id, $p_type, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertStockTransferAdviceAdditionalJobOrder(:p_part_transaction_id, :p_additional_job_order_id, :p_type, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_additional_job_order_id', $p_additional_job_order_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertStockTransferAdviceDocument($p_part_transaction_id, $p_document_name, $p_document_file_path, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertStockTransferAdviceDocument(:p_part_transaction_id, :p_document_name, :p_document_file_path, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_name', $p_document_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_file_path', $p_document_file_path, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function createStockTransferAdviceEntry($p_part_transaction_id, $p_company_id, $p_reference_number, $p_cost, $p_price, $p_customer_type, $p_is_service, $p_product_status, $p_issuance_for, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createStockTransferAdviceEntry(:p_part_transaction_id, :p_company_id, :p_reference_number, :p_cost, :p_price, :p_customer_type, :p_is_service, :p_product_status, :p_issuance_for, :p_last_log_by)');
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

    public function createStockTransferAdviceEntryReversed($p_part_transaction_id, $p_company_id, $p_reference_number, $p_cost, $p_price, $p_customer_type, $p_is_service, $p_product_status, $p_issuance_for, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createStockTransferAdviceEntryReversed(:p_part_transaction_id, :p_company_id, :p_reference_number, :p_cost, :p_price, :p_customer_type, :p_is_service, :p_product_status, :p_issuance_for, :p_last_log_by)');
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

    public function createStockTransferAdviceEntry2($p_part_transaction_id, $p_company_id, $p_reference_number, $p_cost, $p_price, $p_customer_type, $p_is_service, $p_product_status, $p_journal_entry_date, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createStockTransferAdviceEntry2(:p_part_transaction_id, :p_company_id, :p_reference_number, :p_cost, :p_price, :p_customer_type, :p_is_service, :p_product_status, :p_journal_entry_date, :p_last_log_by)');
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

    public function checkStockTransferAdviceCartExist($p_part_transaction_cart_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkStockTransferAdviceCartExist(:p_part_transaction_cart_id)');
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
    public function deleteStockTransferAdviceCart($p_part_transaction_cart_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteStockTransferAdviceCart(:p_part_transaction_cart_id)');
        $stmt->bindValue(':p_part_transaction_cart_id', $p_part_transaction_cart_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deleteStockTransferAdviceJobOrder($p_stock_transfer_advice_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteStockTransferAdviceJobOrder(:p_stock_transfer_advice_job_order_id)');
        $stmt->bindValue(':p_stock_transfer_advice_job_order_id', $p_stock_transfer_advice_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deleteStockTransferAdviceAdditionalJobOrder($p_stock_transfer_advice_additional_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteStockTransferAdviceAdditionalJobOrder(:p_stock_transfer_advice_additional_job_order_id)');
        $stmt->bindValue(':p_stock_transfer_advice_additional_job_order_id', $p_stock_transfer_advice_additional_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function deleteStockTransferAdviceDocument($p_part_transaction_document_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteStockTransferAdviceDocument(:p_part_transaction_document_id)');
        $stmt->bindValue(':p_part_transaction_document_id', $p_part_transaction_document_id, PDO::PARAM_INT);
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

    public function getStockTransferAdviceCart($p_part_transaction_cart_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getStockTransferAdviceCart(:p_part_transaction_cart_id)');
        $stmt->bindValue(':p_part_transaction_cart_id', $p_part_transaction_cart_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStockTransferAdviceCartTotal($p_part_transaction_id, $p_type) {
        $stmt = $this->db->getConnection()->prepare('CALL getStockTransferAdviceCartTotal(:p_part_transaction_id, :p_type)');
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
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM part_transaction WHERE (part_transaction_status = "Released" OR part_transaction_status = "Checked") AND company_id = :company_id ORDER BY part_transaction_id ASC');
        $stmt->bindValue(':company_id', $company_id, PDO::PARAM_INT);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $stockTransferAdviceID = $row['part_transaction_id'];
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