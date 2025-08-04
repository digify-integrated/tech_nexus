<?php
/**
* Class PartsTransactionModel
*
* The PartsTransactionModel class handles parts transaction related operations and interactions.
*/
class PartsTransactionModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updatePartsTransaction
    # Description: Updates the parts transaction.
    #
    # Parameters:
    # - $p_parts_transaction_id (int): The parts transaction ID.
    # - $p_parts_transaction_name (string): The parts transaction name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updatePartsTransactionCart($p_part_transaction_cart_id, $p_quantity, $p_add_on, $p_discount, $p_discount_type, $p_discount_total, $p_sub_total, $p_total, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsTransactionCart(:p_part_transaction_cart_id, :p_quantity, :p_add_on, :p_discount, :p_discount_type, :p_discount_total, :p_sub_total, :p_total, :p_remarks, :p_last_log_by)');
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

    public function updatePartTransactionSummary($p_parts_transaction_id) {
        $stmt = $this->db->getConnection()->prepare('CALL UpdatePartTransactionSummary(:p_parts_transaction_id)');
        $stmt->bindValue(':p_parts_transaction_id', $p_parts_transaction_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePartsTransactionStatus($p_parts_transaction_id, $p_part_transaction_status, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsTransactionStatus(:p_parts_transaction_id, :p_part_transaction_status, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_parts_transaction_id', $p_parts_transaction_id, PDO::PARAM_STR);
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
    # Function: insertPartsTransaction
    # Description: Inserts the parts transaction.
    #
    # Parameters:
    # - $p_part_transaction_id (string): The parts transaction ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertPartsTransaction($p_part_transaction_id, $customer_type, $customer_id, $company_id, $issuance_date, $issuance_no, $reference_date, $reference_number, $remarks, $request_by, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartsTransaction(:p_part_transaction_id, :customer_type, :customer_id, :company_id, :issuance_date, :issuance_no, :reference_date, :reference_number, :remarks, :request_by, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':customer_id', $customer_id, PDO::PARAM_INT);
        $stmt->bindValue(':company_id', $company_id, PDO::PARAM_INT);
        $stmt->bindValue(':customer_type', $customer_type, PDO::PARAM_STR);
        $stmt->bindValue(':issuance_date', $issuance_date, PDO::PARAM_STR);
        $stmt->bindValue(':issuance_no',  $issuance_no, PDO::PARAM_STR);
        $stmt->bindValue(':reference_date',  $reference_date, PDO::PARAM_STR);
        $stmt->bindValue(':reference_number',  $reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':remarks',  $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':request_by',  $request_by, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function createPartsTransactionProductExpense($p_product_id, $p_reference_type, $p_reference_number, $p_expense_amount, $p_expense_type, $p_particulars, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createPartsTransactionProductExpense(:p_product_id, :p_reference_type, :p_reference_number, :p_expense_amount, :p_expense_type, :p_particulars, :p_last_log_by)');
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_type', $p_reference_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_expense_amount', $p_expense_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_expense_type', $p_expense_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_particulars', $p_particulars, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePartsTransaction($p_part_transaction_id, $customer_type, $customer_id, $company_id, $issuance_date, $issuance_no, $reference_date, $reference_number, $remarks, $discount, $discount_type, $overall_total, $request_by, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsTransaction(:p_part_transaction_id, :customer_type, :customer_id, :company_id, :issuance_date, :issuance_no, :reference_date, :reference_number, :remarks, :discount, :discount_type, :overall_total, :request_by, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':customer_id', $customer_id, PDO::PARAM_INT);
        $stmt->bindValue(':company_id', $company_id, PDO::PARAM_INT);
        $stmt->bindValue(':customer_type', $customer_type, PDO::PARAM_STR);
        $stmt->bindValue(':issuance_date', $issuance_date, PDO::PARAM_STR);
        $stmt->bindValue(':issuance_no',  $issuance_no, PDO::PARAM_STR);
        $stmt->bindValue(':reference_date',  $reference_date, PDO::PARAM_STR);
        $stmt->bindValue(':reference_number',  $reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':remarks',  $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':discount', $discount, PDO::PARAM_STR);
        $stmt->bindValue(':discount_type', $discount_type, PDO::PARAM_STR);
        $stmt->bindValue(':overall_total', $overall_total, PDO::PARAM_STR);
        $stmt->bindValue(':request_by', $request_by, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePartsTransactionSlipReferenceNumber($p_part_transaction_id, $slip_reference_no, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsTransactionSlipReferenceNumber(:p_part_transaction_id, :slip_reference_no, :p_last_log_by)');
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

    public function insertPartsTransactionJobOrder($p_part_transaction_id, $p_job_order_id, $p_type, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartsTransactionJobOrder(:p_part_transaction_id, :p_job_order_id, :p_type, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_job_order_id', $p_job_order_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertPartsTransactionAdditionalJobOrder($p_part_transaction_id, $p_additional_job_order_id, $p_type, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartsTransactionAdditionalJobOrder(:p_part_transaction_id, :p_additional_job_order_id, :p_type, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_additional_job_order_id', $p_additional_job_order_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertPartsTransactionDocument($p_part_transaction_id, $p_document_name, $p_document_file_path, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartsTransactionDocument(:p_part_transaction_id, :p_document_name, :p_document_file_path, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_name', $p_document_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_file_path', $p_document_file_path, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function createPartsTransactionEntry($p_part_transaction_id, $p_company_id, $p_reference_number, $p_cost, $p_price, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createPartsTransactionEntry(:p_part_transaction_id, :p_company_id, :p_reference_number, :p_cost, :p_price, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_price', $p_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkPartsTransactionExist
    # Description: Checks if a parts transaction exists.
    #
    # Parameters:
    # - $p_parts_transaction_id (int): The parts transaction ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkPartsTransactionExist($p_parts_transaction_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPartsTransactionExist(:p_parts_transaction_id)');
        $stmt->bindValue(':p_parts_transaction_id', $p_parts_transaction_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_exceeded_part_quantity_count($p_parts_transaction_id) {
        $stmt = $this->db->getConnection()->prepare('CALL get_exceeded_part_quantity_count(:p_parts_transaction_id)');
        $stmt->bindValue(':p_parts_transaction_id', $p_parts_transaction_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function check_exceed_part_quantity($p_parts_transaction_id) {
        $stmt = $this->db->getConnection()->prepare('CALL check_exceed_part_quantity(:p_parts_transaction_id)');
        $stmt->bindValue(':p_parts_transaction_id', $p_parts_transaction_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkPartsTransactionCartExist($p_part_transaction_cart_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPartsTransactionCartExist(:p_part_transaction_cart_id)');
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
    # Function: deletePartsTransaction
    # Description: Deletes the parts transaction.
    #
    # Parameters:
    # - $p_parts_transaction_id (int): The parts transaction ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deletePartsTransaction($p_parts_transaction_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePartsTransaction(:p_parts_transaction_id)');
        $stmt->bindValue(':p_parts_transaction_id', $p_parts_transaction_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deletePartsTransactionCart($p_part_transaction_cart_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePartsTransactionCart(:p_part_transaction_cart_id)');
        $stmt->bindValue(':p_part_transaction_cart_id', $p_part_transaction_cart_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function deletePartsTransactionDocument($p_part_transaction_document_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePartsTransactionDocument(:p_part_transaction_document_id)');
        $stmt->bindValue(':p_part_transaction_document_id', $p_part_transaction_document_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPartsTransaction
    # Description: Retrieves the details of a parts transaction.
    #
    # Parameters:
    # - $p_parts_transaction_id (int): The parts transaction ID.
    #
    # Returns:
    # - An array containing the parts transaction details.
    #
    # -------------------------------------------------------------
    public function getPartsTransaction($p_parts_transaction_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getPartsTransaction(:p_parts_transaction_id)');
        $stmt->bindValue(':p_parts_transaction_id', $p_parts_transaction_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPartsTransactionCart($p_part_transaction_cart_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getPartsTransactionCart(:p_part_transaction_cart_id)');
        $stmt->bindValue(':p_part_transaction_cart_id', $p_part_transaction_cart_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPartsTransactionCartTotal($p_part_transaction_id, $p_type) {
        $stmt = $this->db->getConnection()->prepare('CALL getPartsTransactionCartTotal(:p_part_transaction_id, :p_type)');
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
    # Function: generatePartsTransactionOptions
    # Description: Generates the parts transaction options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePartsTransactionOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePartsTransactionOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsTransactionID = $row['parts_transaction_id'];
            $partsTransactionName = $row['parts_transaction_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($partsTransactionID, ENT_QUOTES) . '">' . htmlspecialchars($partsTransactionName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generatePartsTransactionCheckBox
    # Description: Generates the parts transaction check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePartsTransactionCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePartsTransactionOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsTransactionID = $row['parts_transaction_id'];
            $partsTransactionName = $row['parts_transaction_name'];

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