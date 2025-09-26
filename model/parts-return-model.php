<?php
/**
* Class PartsReturnModel
*
* The PartsReturnModel class handles parts return related operations and interactions.
*/
class PartsReturnModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updatePartsReturn
    # Description: Updates the parts return.
    #
    # Parameters:
    # - $p_parts_return_id (int): The parts return ID.
    # - $p_reference_number (string): The parts return name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updatePartsReturn($p_parts_return_id, $p_reference_number, $p_supplier_id, $p_rr_no, $p_rr_date, $p_delivery_date, $p_purchase_date, $p_requested_by, $p_product_id, $p_customer_ref_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsReturn(:p_parts_return_id, :p_reference_number, :p_supplier_id, :p_rr_no, :p_rr_date, :p_delivery_date, :p_purchase_date, :p_requested_by, :p_product_id, :p_customer_ref_id, :p_last_log_by)');
        $stmt->bindValue(':p_parts_return_id', $p_parts_return_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_supplier_id', $p_supplier_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_rr_no', $p_rr_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_rr_date', $p_rr_date, type: PDO::PARAM_STR);
        $stmt->bindValue(':p_delivery_date', $p_delivery_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_purchase_date', $p_purchase_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_requested_by', $p_requested_by, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_customer_ref_id', $p_customer_ref_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePartsReturnCart($p_part_return_cart_id, $p_quantity, $p_cost, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsReturnCart(:p_part_return_cart_id, :p_quantity, :p_cost, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_part_return_cart_id', $p_part_return_cart_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_quantity', $p_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePartsReturnStatus($p_parts_return_id, $p_part_return_status, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsReturnStatus(:p_parts_return_id, :p_part_return_status, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_parts_return_id', $p_parts_return_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_part_return_status', $p_part_return_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePartsReturnReleased($p_parts_return_id, $p_part_return_status, $p_invoice_number, $p_invoice_price, $p_invoice_date, $p_delivery_date, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsReturnReleased(:p_parts_return_id, :p_part_return_status, :p_invoice_number, :p_invoice_price, :p_invoice_date, :p_delivery_date, :p_last_log_by)');
        $stmt->bindValue(':p_parts_return_id', $p_parts_return_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_part_return_status', $p_part_return_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_invoice_number', $p_invoice_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_invoice_price', $p_invoice_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_invoice_date', $p_invoice_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_delivery_date', $p_delivery_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function generatePartsIssuanceMonitoring($p_parts_return_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL generatePartsIssuanceMonitoring(:p_parts_return_id, :p_last_log_by)');
        $stmt->bindValue(':p_parts_return_id', $p_parts_return_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePartsReturnPosted($p_parts_return_id, $p_rr_no, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsReturnPosted(:p_parts_return_id, :p_rr_no, :p_last_log_by)');
        $stmt->bindValue(':p_parts_return_id', $p_parts_return_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_rr_no', $p_rr_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePartsReceivedReturnCart($p_part_return_cart_id, $p_part_id, $p_received_quantity, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsReceivedReturnCart(:p_part_return_cart_id, :p_part_id, :p_received_quantity, :p_last_log_by)');
        $stmt->bindValue(':p_part_return_cart_id', $p_part_return_cart_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_part_id', $p_part_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_received_quantity', $p_received_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePartsAverageCostAndSRP($p_part_id, $p_company_id, $p_received_quantity, $p_cost, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsAverageCostAndSRP(:p_part_id, :p_company_id, :p_received_quantity, :p_cost, :p_last_log_by)');
        $stmt->bindValue(':p_part_id', $p_part_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_received_quantity', $p_received_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePartsReceivedCancelReturnCart($p_part_return_cart_id, $p_cancel_quantity, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsReceivedCancelReturnCart(:p_part_return_cart_id, :p_cancel_quantity, :p_last_log_by)');
        $stmt->bindValue(':p_part_return_cart_id', $p_part_return_cart_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_cancel_quantity', $p_cancel_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPartsReturn
    # Description: Inserts the parts return.
    #
    # Parameters:
    # - $p_part_return_id (string): The parts return ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
      public function insertPartsReturn($p_reference_number, $p_supplier_id, $p_rr_no, $p_rr_date, $p_delivery_date, $p_purchase_date, $p_company_id, $p_requested_by, $p_product_id, $p_customer_ref_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartsReturn(:p_reference_number, :p_supplier_id, :p_rr_no, :p_rr_date, :p_delivery_date, :p_purchase_date, :p_company_id, :p_requested_by, :p_product_id, :p_customer_ref_id, :p_last_log_by, @p_part_return_id)');
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_supplier_id', $p_supplier_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_rr_no', $p_rr_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_rr_date', $p_rr_date, type: PDO::PARAM_STR);
        $stmt->bindValue(':p_delivery_date', $p_delivery_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_purchase_date', $p_purchase_date, PDO::PARAM_INT);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_requested_by', $p_requested_by, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_customer_ref_id', $p_customer_ref_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_part_return_id AS p_part_return_id");
        $p_part_return_id = $result->fetch(PDO::FETCH_ASSOC)['p_part_return_id'];

        return $p_part_return_id;
    }

    public function insertPartReturnItem($p_part_return_id, $p_part_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartReturnItem(:p_part_return_id, :p_part_id, :p_last_log_by)');
        $stmt->bindValue(':p_part_return_id', $p_part_return_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_part_id', $p_part_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertPartsReturnDocument($p_part_return_id, $p_document_name, $p_document_file_path, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartsReturnDocument(:p_part_return_id, :p_document_name, :p_document_file_path, :p_last_log_by)');
        $stmt->bindValue(':p_part_return_id', $p_part_return_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_name', $p_document_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_file_path', $p_document_file_path, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function createPartsReturnEntry($p_part_return_id, $p_company_id, $p_reference_number, $p_cost, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createPartsReturnEntry(:p_part_return_id, :p_company_id, :p_reference_number, :p_cost, :p_last_log_by)');
        $stmt->bindValue(':p_part_return_id', $p_part_return_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
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
    # Function: checkPartsReturnExist
    # Description: Checks if a parts return exists.
    #
    # Parameters:
    # - $p_parts_return_id (int): The parts return ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkPartsReturnExist($p_parts_return_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPartsReturnExist(:p_parts_return_id)');
        $stmt->bindValue(':p_parts_return_id', $p_parts_return_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_exceeded_part_quantity_count($p_parts_return_id) {
        $stmt = $this->db->getConnection()->prepare('CALL get_exceeded_part_quantity_count(:p_parts_return_id)');
        $stmt->bindValue(':p_parts_return_id', $p_parts_return_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkPartsReturnCartExist($p_part_return_cart_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPartsReturnCartExist(:p_part_return_cart_id)');
        $stmt->bindValue(':p_part_return_cart_id', $p_part_return_cart_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePartsReturn
    # Description: Deletes the parts return.
    #
    # Parameters:
    # - $p_parts_return_id (int): The parts return ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deletePartsReturn($p_parts_return_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePartsReturn(:p_parts_return_id)');
        $stmt->bindValue(':p_parts_return_id', $p_parts_return_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deletePartsReturnCart($p_part_return_cart_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePartsReturnCart(:p_part_return_cart_id)');
        $stmt->bindValue(':p_part_return_cart_id', $p_part_return_cart_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function cancelPartsReturnCart($p_part_return_cart_id) {
        $stmt = $this->db->getConnection()->prepare('CALL cancelPartsReturnCart(:p_part_return_cart_id)');
        $stmt->bindValue(':p_part_return_cart_id', $p_part_return_cart_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function deletePartsReturnDocument($p_part_return_document_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePartsReturnDocument(:p_part_return_document_id)');
        $stmt->bindValue(':p_part_return_document_id', $p_part_return_document_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPartsReturn
    # Description: Retrieves the details of a parts return.
    #
    # Parameters:
    # - $p_parts_return_id (int): The parts return ID.
    #
    # Returns:
    # - An array containing the parts return details.
    #
    # -------------------------------------------------------------
    public function getPartsReturn($p_parts_return_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getPartsReturn(:p_parts_return_id)');
        $stmt->bindValue(':p_parts_return_id', $p_parts_return_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPartsReturnCart($p_part_return_cart_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getPartsReturnCart(:p_part_return_cart_id)');
        $stmt->bindValue(':p_part_return_cart_id', $p_part_return_cart_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPartsReturnCartByID($p_parts_return_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM part_return_cart WHERE part_return_id = :p_parts_return_id');
        $stmt->bindValue(':p_parts_return_id', $p_parts_return_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPartsReturnCartTotal($p_part_return_id, $p_type) {
        $stmt = $this->db->getConnection()->prepare('CALL getPartsReturnCartTotal(:p_part_return_id, :p_type)');
        $stmt->bindValue(':p_part_return_id', $p_part_return_id, PDO::PARAM_INT);
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
    # Function: generatePartsReturnOptions
    # Description: Generates the parts return options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePartsReturnOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePartsReturnOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsReturnID = $row['parts_return_id'];
            $partsReturnName = $row['parts_return_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($partsReturnID, ENT_QUOTES) . '">' . htmlspecialchars($partsReturnName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generatePartsReturnCheckBox
    # Description: Generates the parts return check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePartsReturnCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePartsReturnOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsReturnID = $row['parts_return_id'];
            $partsReturnName = $row['parts_return_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input parts-return-filter" type="checkbox" id="parts-return-' . htmlspecialchars($partsReturnID, ENT_QUOTES) . '" value="' . htmlspecialchars($partsReturnID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="parts-return-' . htmlspecialchars($partsReturnID, ENT_QUOTES) . '">' . htmlspecialchars($partsReturnName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>