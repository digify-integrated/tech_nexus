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
    public function updatePartsReturn($parts_return_id, $supplier_id, $purchase_date, $return_type, $ref_invoice_number, $ref_po_number, $ref_po_date, $prev_total_billing, $adjusted_total_billing, $remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsReturn(:parts_return_id, :supplier_id, :purchase_date, :return_type, :ref_invoice_number, :ref_po_number, :ref_po_date, :prev_total_billing, :adjusted_total_billing, :remarks, :p_last_log_by)');
        $stmt->bindValue(':parts_return_id', $parts_return_id, PDO::PARAM_INT);
        $stmt->bindValue(':supplier_id', $supplier_id, PDO::PARAM_INT);
        $stmt->bindValue(':purchase_date', $purchase_date, PDO::PARAM_STR);
        $stmt->bindValue(':return_type', $return_type, PDO::PARAM_STR);
        $stmt->bindValue(':ref_invoice_number', $ref_invoice_number, PDO::PARAM_STR);
        $stmt->bindValue(':ref_po_number', $ref_po_number, PDO::PARAM_STR);
        $stmt->bindValue(':ref_po_date', $ref_po_date, PDO::PARAM_STR);
        $stmt->bindValue(':prev_total_billing', $prev_total_billing, PDO::PARAM_STR);
        $stmt->bindValue(':adjusted_total_billing', $adjusted_total_billing, PDO::PARAM_STR);
        $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
     public function createPartsReturnStockEntry($part_id, $p_company_id, $p_reference_number, $p_cost, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createPartsReturnStockEntry(:part_id, :p_company_id, :p_reference_number, :p_cost, :p_last_log_by)');
        $stmt->bindValue(':part_id', $part_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updatePartsReturnValue($parts_return_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsReturnValue(:parts_return_id, :p_last_log_by)');
        $stmt->bindValue(':parts_return_id', $parts_return_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updatePartsStockReturnValue($parts_return_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsStockReturnValue(:parts_return_id, :p_last_log_by)');
        $stmt->bindValue(':parts_return_id', $parts_return_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updatePartsReturnCart($part_return_cart_id, $return_quantity, $remarks, $last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE part_return_cart SET return_quantity = :return_quantity, remarks = :remarks, last_log_by = :last_log_by WHERE part_return_cart_id = :part_return_cart_id');
        $stmt->bindValue(':part_return_cart_id', $part_return_cart_id, PDO::PARAM_INT);
        $stmt->bindValue(':return_quantity', $return_quantity, PDO::PARAM_INT);
        $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updatePartsReturnReferenceNumber($part_return_id, $reference_number, $last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE part_return SET reference_number = :reference_number, last_log_by = :last_log_by WHERE part_return_id = :part_return_id');
        $stmt->bindValue(':part_return_id', $part_return_id, PDO::PARAM_INT);
        $stmt->bindValue(':reference_number', $reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updatePartsReturnForApproval($parts_return_id, $last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE part_return
        SET 
            for_approval = NOW(),
            part_return_status = "For Validation",
            last_log_by = :last_log_by
        WHERE part_return_id = :parts_return_id');
        $stmt->bindValue(':parts_return_id', $parts_return_id, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updatePartsReturnReleased($parts_return_id, $last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE part_return
        SET 
            released_date = NOW(),
            part_return_status = "Released",
            last_log_by = :last_log_by
        WHERE part_return_id = :parts_return_id');
        $stmt->bindValue(':parts_return_id', $parts_return_id, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updatePartsReturnDraft($parts_return_id, $draft_reason, $last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE part_return
        SET 
            set_to_draft_date = NOW(),
            set_to_draft_reason = :draft_reason,
            part_return_status = "Draft",
            last_log_by = :last_log_by
        WHERE part_return_id = :parts_return_id');
        $stmt->bindValue(':parts_return_id', $parts_return_id, PDO::PARAM_INT);
        $stmt->bindValue(':draft_reason', $draft_reason, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePartsReturnApprove($parts_return_id, $approval_remarks, $last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE part_return
            SET 
                approval_date = NOW(),
                approval_by = :approval_by,
                part_return_status = \'Validated\',
                approval_remarks = :approval_remarks,
                last_log_by = :last_log_by
            WHERE part_return_id = :parts_return_id');
        $stmt->bindValue(':parts_return_id', $parts_return_id, PDO::PARAM_INT);
        $stmt->bindValue(':approval_remarks', $approval_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':approval_by', $last_log_by, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);
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
      public function insertPartsReturn($supplier_id, $company_id, $purchase_date, $return_type, $ref_invoice_number, $ref_po_number, $ref_po_date, $prev_total_billing, $adjusted_total_billing, $remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartsReturn(:supplier_id, :company_id, :purchase_date, :return_type, :ref_invoice_number, :ref_po_number, :ref_po_date, :prev_total_billing, :adjusted_total_billing, :remarks, :p_last_log_by, @p_part_return_id)'); 
        $stmt->bindValue(':supplier_id', $supplier_id, PDO::PARAM_INT);
        $stmt->bindValue(':company_id', $company_id, PDO::PARAM_INT);
        $stmt->bindValue(':purchase_date', $purchase_date, PDO::PARAM_STR);
        $stmt->bindValue(':return_type', $return_type, PDO::PARAM_STR);
        $stmt->bindValue(':ref_invoice_number', $ref_invoice_number, PDO::PARAM_STR);
        $stmt->bindValue(':ref_po_number', $ref_po_number, PDO::PARAM_STR);
        $stmt->bindValue(':ref_po_date', $ref_po_date, PDO::PARAM_STR);
        $stmt->bindValue(':prev_total_billing', $prev_total_billing, PDO::PARAM_STR);
        $stmt->bindValue(':adjusted_total_billing', $adjusted_total_billing, PDO::PARAM_STR);
        $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_part_return_id AS p_part_return_id");
        $p_part_return_id = $result->fetch(PDO::FETCH_ASSOC)['p_part_return_id'];

        return $p_part_return_id;
    }
    public function insertPartReturnItem($parts_return_id, $part_transaction_id, $part_transaction_cart_id, $part_id, $last_log_by) {
        $stmt = $this->db->getConnection()->prepare('INSERT INTO part_return_cart (
            part_return_id,
            part_transaction_id,
            part_transaction_cart_id,
            part_id,
            return_quantity,
            last_log_by
        ) VALUES (
            :part_return_id,
            :part_transaction_id,
            :part_transaction_cart_id,
            :part_id,
            1,
            :last_log_by
        );'); 
        $stmt->bindValue(':part_return_id', $parts_return_id, PDO::PARAM_INT);
        $stmt->bindValue(':part_transaction_id', $part_transaction_id, PDO::PARAM_INT);
        $stmt->bindValue(':part_id', $part_id, PDO::PARAM_INT);
        $stmt->bindValue(':part_transaction_cart_id', $part_transaction_cart_id, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function insertPartReturnItemStock($parts_return_id, $part_id, $last_log_by) {
        $stmt = $this->db->getConnection()->prepare('INSERT INTO part_return_cart (
            part_return_id,
            part_id,
            return_quantity,
            last_log_by
        ) VALUES (
            :part_return_id,
            :part_id,
            1,
            :last_log_by
        );'); 
        $stmt->bindValue(':part_return_id', $parts_return_id, PDO::PARAM_INT);
        $stmt->bindValue(':part_id', $part_id, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getPartsReturn($p_parts_return_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM part_return WHERE part_return_id = :p_parts_return_id');
        $stmt->bindValue(':p_parts_return_id', $p_parts_return_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPartsReturnCart($part_return_cart_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM part_return_cart WHERE part_return_cart_id = :part_return_cart_id');
        $stmt->bindValue(':part_return_cart_id', $part_return_cart_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPartsReturnCart2($part_return_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM part_return_cart WHERE part_return_id = :part_return_id');
        $stmt->bindValue(':part_return_id', $part_return_id, PDO::PARAM_INT);
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

    public function getPartsReturnCartStockTotal($p_part_return_id, $p_type) {
        $stmt = $this->db->getConnection()->prepare('CALL getPartsReturnCartStockTotal(:p_part_return_id, :p_type)');
        $stmt->bindValue(':p_part_return_id', $p_part_return_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function check_exceed_part_quantity($p_part_return_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT 
        COUNT(*) AS total
    FROM 
        part_return_cart ptc
        INNER JOIN part_transaction_cart p 
            ON ptc.part_transaction_cart_id = p.part_transaction_cart_id
    WHERE 
        ptc.part_return_id = :p_part_return_id
        AND ptc.return_quantity > p.return_quantity');
        $stmt->bindValue(':p_part_return_id', $p_part_return_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deletePartsReturnCart($part_return_cart_id) {
        $stmt = $this->db->getConnection()->prepare('DELETE FROM part_return_cart WHERE part_return_cart_id = :part_return_cart_id');
        $stmt->bindValue(':part_return_cart_id', $part_return_cart_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
    public function checkPartsReturnExist($p_part_return_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT COUNT(*) AS total FROM part_return WHERE part_return_id = :p_part_return_id');
        $stmt->bindValue(':p_part_return_id', $p_part_return_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkPartsReturnCartExist($p_part_return_cart_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT COUNT(*) AS total FROM part_return_cart WHERE part_return_cart_id = :p_part_return_cart_id');
        $stmt->bindValue(':p_part_return_cart_id', $p_part_return_cart_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>