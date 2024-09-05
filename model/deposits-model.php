<?php
/**
* Class DepositsModel
*
* The DepositsModel class handles deposits related operations and interactions.
*/
class DepositsModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateDeposits
    # Description: Updates the deposits.
    #
    # Parameters:
    # - $p_deposits_id (int): The deposits ID.
    # - $p_product_category_name (string): The deposits name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateDeposits($p_deposits_id, $p_deposit_amount, $p_deposit_date, $p_deposited_to, $p_reference_number, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateDeposits(:p_deposits_id, :p_deposit_amount, :p_deposit_date, :p_deposited_to, :p_reference_number, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_deposits_id', $p_deposits_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_deposit_amount', $p_deposit_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_deposit_date', $p_deposit_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_deposited_to', $p_deposited_to, PDO::PARAM_INT);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
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
    # Function: insertDeposits
    # Description: Inserts the deposits.
    #
    # Parameters:
    # - $p_product_category_name (string): The deposits name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertDeposits($p_company_id, $p_deposit_amount, $p_deposit_date, $p_deposited_to, $p_reference_number, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertDeposits(:p_company_id, :p_deposit_amount, :p_deposit_date, :p_deposited_to, :p_reference_number, :p_remarks, :p_last_log_by, @p_deposits_id)');
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_deposit_amount', $p_deposit_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_deposit_date', $p_deposit_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_deposited_to', $p_deposited_to, PDO::PARAM_INT);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_deposits_id AS p_deposits_id");
        $p_deposits_id = $result->fetch(PDO::FETCH_ASSOC)['p_deposits_id'];

        return $p_deposits_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkDepositsExist
    # Description: Checks if a deposits exists.
    #
    # Parameters:
    # - $p_deposits_id (int): The deposits ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkDepositsExist($p_deposits_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkDepositsExist(:p_deposits_id)');
        $stmt->bindValue(':p_deposits_id', $p_deposits_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteDeposits
    # Description: Deletes the deposits.
    #
    # Parameters:
    # - $p_deposits_id (int): The deposits ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteDeposits($p_deposits_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteDeposits(:p_deposits_id)');
        $stmt->bindValue(':p_deposits_id', $p_deposits_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getDeposits
    # Description: Retrieves the details of a deposits.
    #
    # Parameters:
    # - $p_deposits_id (int): The deposits ID.
    #
    # Returns:
    # - An array containing the deposits details.
    #
    # -------------------------------------------------------------
    public function getDeposits($p_deposits_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getDeposits(:p_deposits_id)');
        $stmt->bindValue(':p_deposits_id', $p_deposits_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>