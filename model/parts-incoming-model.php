<?php
/**
* Class PartsIncomingModel
*
* The PartsIncomingModel class handles parts incoming related operations and interactions.
*/
class PartsIncomingModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updatePartsIncoming
    # Description: Updates the parts incoming.
    #
    # Parameters:
    # - $p_parts_incoming_id (int): The parts incoming ID.
    # - $p_reference_number (string): The parts incoming name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updatePartsIncoming($p_parts_incoming_id, $p_reference_number, $p_supplier_id, $p_rr_no, $p_rr_date, $p_delivery_date, $p_purchase_date, $p_requested_by, $p_product_id, $p_customer_ref_id, $p_remarks, $p_incoming_for, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsIncoming(:p_parts_incoming_id, :p_reference_number, :p_supplier_id, :p_rr_no, :p_rr_date, :p_delivery_date, :p_purchase_date, :p_requested_by, :p_product_id, :p_customer_ref_id, :p_remarks, :p_incoming_for, :p_last_log_by)');
        $stmt->bindValue(':p_parts_incoming_id', $p_parts_incoming_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_supplier_id', $p_supplier_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_rr_no', $p_rr_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_rr_date', $p_rr_date, type: PDO::PARAM_STR);
        $stmt->bindValue(':p_delivery_date', $p_delivery_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_purchase_date', $p_purchase_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_requested_by', $p_requested_by, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_customer_ref_id', $p_customer_ref_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_incoming_for', $p_incoming_for, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePartsIncomingCart($p_part_incoming_cart_id, $p_quantity, $p_cost, $p_remarks, $part_for, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsIncomingCart(:p_part_incoming_cart_id, :p_quantity, :p_cost, :p_remarks, :part_for, :p_last_log_by)');
        $stmt->bindValue(':p_part_incoming_cart_id', $p_part_incoming_cart_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_quantity', $p_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':part_for', $part_for, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePartsIncomingStatus($p_parts_incoming_id, $p_part_incoming_status, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsIncomingStatus(:p_parts_incoming_id, :p_part_incoming_status, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_parts_incoming_id', $p_parts_incoming_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_part_incoming_status', $p_part_incoming_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePartsIncomingReleased($p_parts_incoming_id, $p_part_incoming_status, $p_invoice_number, $p_invoice_price, $p_invoice_date, $p_delivery_date, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsIncomingReleased(:p_parts_incoming_id, :p_part_incoming_status, :p_invoice_number, :p_invoice_price, :p_invoice_date, :p_delivery_date, :p_last_log_by)');
        $stmt->bindValue(':p_parts_incoming_id', $p_parts_incoming_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_part_incoming_status', $p_part_incoming_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_invoice_number', $p_invoice_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_invoice_price', $p_invoice_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_invoice_date', $p_invoice_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_delivery_date', $p_delivery_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function generatePartsIssuanceMonitoring($p_parts_incoming_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL generatePartsIssuanceMonitoring(:p_parts_incoming_id, :p_last_log_by)');
        $stmt->bindValue(':p_parts_incoming_id', $p_parts_incoming_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePartsIncomingPosted($p_parts_incoming_id, $p_rr_no, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsIncomingPosted(:p_parts_incoming_id, :p_rr_no, :p_last_log_by)');
        $stmt->bindValue(':p_parts_incoming_id', $p_parts_incoming_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_rr_no', $p_rr_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePartsReceivedIncomingCart($p_part_incoming_cart_id, $p_part_id, $p_received_quantity, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsReceivedIncomingCart(:p_part_incoming_cart_id, :p_part_id, :p_received_quantity, :p_last_log_by)');
        $stmt->bindValue(':p_part_incoming_cart_id', $p_part_incoming_cart_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_part_id', $p_part_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_received_quantity', $p_received_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePartsAverageCostAndSRP($p_part_id, $p_company_id, $p_received_quantity, $p_cost, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsAverageCostAndSRP(:p_part_id, :p_company_id, :p_received_quantity, :p_cost, :p_last_log_by)');
        $stmt->bindValue(':p_part_id', $p_part_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_received_quantity', $p_received_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    }

    public function updatePartsReceivedCancelIncomingCart($p_part_incoming_cart_id, $p_cancel_quantity, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsReceivedCancelIncomingCart(:p_part_incoming_cart_id, :p_cancel_quantity, :p_last_log_by)');
        $stmt->bindValue(':p_part_incoming_cart_id', $p_part_incoming_cart_id, PDO::PARAM_STR);
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
    # Function: insertPartsIncoming
    # Description: Inserts the parts incoming.
    #
    # Parameters:
    # - $p_part_incoming_id (string): The parts incoming ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
      public function insertPartsIncoming($p_reference_number, $p_supplier_id, $p_rr_no, $p_rr_date, $p_delivery_date, $p_purchase_date, $p_company_id, $p_requested_by, $p_product_id, $p_customer_ref_id, $p_remarks, $p_incoming_for, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartsIncoming(:p_reference_number, :p_supplier_id, :p_rr_no, :p_rr_date, :p_delivery_date, :p_purchase_date, :p_company_id, :p_requested_by, :p_product_id, :p_customer_ref_id, :p_remarks, :p_incoming_for, :p_last_log_by, @p_part_incoming_id)');
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
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_incoming_for', $p_incoming_for, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_part_incoming_id AS p_part_incoming_id");
        $p_part_incoming_id = $result->fetch(PDO::FETCH_ASSOC)['p_part_incoming_id'];

        return $p_part_incoming_id;
    }

    public function insertPartIncomingItem($p_part_incoming_id, $p_part_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartIncomingItem(:p_part_incoming_id, :p_part_id, :p_last_log_by)');
        $stmt->bindValue(':p_part_incoming_id', $p_part_incoming_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_part_id', $p_part_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertPartsIncomingDocument($p_part_incoming_id, $p_document_name, $p_document_file_path, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartsIncomingDocument(:p_part_incoming_id, :p_document_name, :p_document_file_path, :p_last_log_by)');
        $stmt->bindValue(':p_part_incoming_id', $p_part_incoming_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_name', $p_document_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_file_path', $p_document_file_path, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function createPartsIncomingEntry($p_part_incoming_id, $p_company_id, $p_reference_number, $p_cost, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createPartsIncomingEntry(:p_part_incoming_id, :p_company_id, :p_reference_number, :p_cost, :p_last_log_by)');
        $stmt->bindValue(':p_part_incoming_id', $p_part_incoming_id, PDO::PARAM_STR);
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
    # Function: checkPartsIncomingExist
    # Description: Checks if a parts incoming exists.
    #
    # Parameters:
    # - $p_parts_incoming_id (int): The parts incoming ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkPartsIncomingExist($p_parts_incoming_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPartsIncomingExist(:p_parts_incoming_id)');
        $stmt->bindValue(':p_parts_incoming_id', $p_parts_incoming_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_exceeded_part_quantity_count($p_parts_incoming_id) {
        $stmt = $this->db->getConnection()->prepare('CALL get_exceeded_part_quantity_count(:p_parts_incoming_id)');
        $stmt->bindValue(':p_parts_incoming_id', $p_parts_incoming_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkPartsIncomingCartExist($p_part_incoming_cart_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPartsIncomingCartExist(:p_part_incoming_cart_id)');
        $stmt->bindValue(':p_part_incoming_cart_id', $p_part_incoming_cart_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePartsIncoming
    # Description: Deletes the parts incoming.
    #
    # Parameters:
    # - $p_parts_incoming_id (int): The parts incoming ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deletePartsIncoming($p_parts_incoming_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePartsIncoming(:p_parts_incoming_id)');
        $stmt->bindValue(':p_parts_incoming_id', $p_parts_incoming_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deletePartsIncomingCart($p_part_incoming_cart_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePartsIncomingCart(:p_part_incoming_cart_id)');
        $stmt->bindValue(':p_part_incoming_cart_id', $p_part_incoming_cart_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function cancelPartsIncomingCart($p_part_incoming_cart_id) {
        $stmt = $this->db->getConnection()->prepare('CALL cancelPartsIncomingCart(:p_part_incoming_cart_id)');
        $stmt->bindValue(':p_part_incoming_cart_id', $p_part_incoming_cart_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function deletePartsIncomingDocument($p_part_incoming_document_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePartsIncomingDocument(:p_part_incoming_document_id)');
        $stmt->bindValue(':p_part_incoming_document_id', $p_part_incoming_document_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPartsIncoming
    # Description: Retrieves the details of a parts incoming.
    #
    # Parameters:
    # - $p_parts_incoming_id (int): The parts incoming ID.
    #
    # Returns:
    # - An array containing the parts incoming details.
    #
    # -------------------------------------------------------------
    public function getPartsIncoming($p_parts_incoming_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getPartsIncoming(:p_parts_incoming_id)');
        $stmt->bindValue(':p_parts_incoming_id', $p_parts_incoming_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPartsIncomingCart($p_part_incoming_cart_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getPartsIncomingCart(:p_part_incoming_cart_id)');
        $stmt->bindValue(':p_part_incoming_cart_id', $p_part_incoming_cart_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPartsIncomingCartByID($p_parts_incoming_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM part_incoming_cart WHERE part_incoming_id = :p_parts_incoming_id');
        $stmt->bindValue(':p_parts_incoming_id', $p_parts_incoming_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPartsIncomingCartTotal($p_part_incoming_id, $p_type) {
        $stmt = $this->db->getConnection()->prepare('CALL getPartsIncomingCartTotal(:p_part_incoming_id, :p_type)');
        $stmt->bindValue(':p_part_incoming_id', $p_part_incoming_id, PDO::PARAM_INT);
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
    # Function: generatePartsIncomingOptions
    # Description: Generates the parts incoming options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePartsIncomingOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePartsIncomingOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsIncomingID = $row['parts_incoming_id'];
            $partsIncomingName = $row['parts_incoming_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($partsIncomingID, ENT_QUOTES) . '">' . htmlspecialchars($partsIncomingName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generatePartsIncomingCheckBox
    # Description: Generates the parts incoming check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePartsIncomingCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePartsIncomingOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsIncomingID = $row['parts_incoming_id'];
            $partsIncomingName = $row['parts_incoming_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input parts-incoming-filter" type="checkbox" id="parts-incoming-' . htmlspecialchars($partsIncomingID, ENT_QUOTES) . '" value="' . htmlspecialchars($partsIncomingID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="parts-incoming-' . htmlspecialchars($partsIncomingID, ENT_QUOTES) . '">' . htmlspecialchars($partsIncomingName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>