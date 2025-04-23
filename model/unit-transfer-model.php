<?php
/**
* Class DisbursementModel
*
* The DisbursementModel class handles disbursement related operations and interactions.
*/
class UnitTransferModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    public function checkUnitTransferExist($p_product_id): mixed {
        $stmt = $this->db->getConnection()->prepare('CALL checkUnitTransferExist(:p_product_id)');
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkUnitTransferOpenExist($p_product_id): mixed {
        $stmt = $this->db->getConnection()->prepare('CALL checkUnitTransferOpenExist(:p_product_id)');
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkUnitReceiveAuthorization($p_transferred_to, $p_user_id): mixed {
        $stmt = $this->db->getConnection()->prepare('CALL checkUnitReceiveAuthorization(:p_transferred_to, :p_user_id)');
        $stmt->bindValue(':p_transferred_to', $p_transferred_to, PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertUnitTransfer($p_product_id, $p_transferred_from, $p_transferred_to, $p_transfer_remarks, $p_last_log_by): mixed {
        $stmt = $this->db->getConnection()->prepare('CALL insertUnitTransfer(:p_product_id, :p_transferred_from, :p_transferred_to, :p_transfer_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_transferred_from', $p_transferred_from, PDO::PARAM_INT);
        $stmt->bindValue(':p_transferred_to', $p_transferred_to, PDO::PARAM_INT);
        $stmt->bindValue(':p_transfer_remarks', $p_transfer_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUnitTransferDetails($p_unit_transfer_id, $p_transferred_to_update, $p_transfer_remarks_update, $p_last_log_by): mixed {
        $stmt = $this->db->getConnection()->prepare('CALL updateUnitTransferDetails(:p_unit_transfer_id, :p_transferred_to_update, :p_transfer_remarks_update, :p_last_log_by)');
        $stmt->bindValue(':p_unit_transfer_id', $p_unit_transfer_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_transferred_to_update', $p_transferred_to_update, PDO::PARAM_INT);
        $stmt->bindValue(':p_transfer_remarks_update', $p_transfer_remarks_update, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cancelUnitTransfer($p_unit_transfer_id, $p_last_log_by): mixed {
        $stmt = $this->db->getConnection()->prepare('CALL cancelUnitTransfer(:p_unit_transfer_id, :p_last_log_by)');
        $stmt->bindValue(':p_unit_transfer_id', $p_unit_transfer_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUnitTransferDetails($p_unit_transfer_id): mixed {
        $stmt = $this->db->getConnection()->prepare('CALL getUnitTransferDetails(:p_unit_transfer_id)');
        $stmt->bindValue(':p_unit_transfer_id', $p_unit_transfer_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUnitReceive($p_transfer_unit_id, $p_receive_remarks, $p_product_id, $p_transferred_to, $p_last_log_by): mixed {
        $stmt = $this->db->getConnection()->prepare('CALL updateUnitReceive(:p_transfer_unit_id, :p_receive_remarks, :p_product_id, :p_transferred_to, :p_last_log_by)');
        $stmt->bindValue(':p_transfer_unit_id', $p_transfer_unit_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_receive_remarks', $p_receive_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_transferred_to', $p_transferred_to, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>