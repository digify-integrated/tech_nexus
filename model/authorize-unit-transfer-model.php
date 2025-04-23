<?php
/**
* Class AuthorizeUnitTransferModel
*
* The AuthorizeUnitTransferModel class handles authorize unit transfer related operations and interactions.
*/
class AuthorizeUnitTransferModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateAuthorizeUnitTransfer
    # Description: Updates the authorize unit transfer.
    #
    # Parameters:
    # - $p_authorize_unit_transfer_id (int): The authorize unit transfer ID.
    # - $p_warehouse_id (string): The authorize unit transfer name.
    # - $p_user_id (string): The authorize unit transfer name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateAuthorizeUnitTransfer($p_authorize_unit_transfer_id, $p_warehouse_id, $p_user_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateAuthorizeUnitTransfer(:p_authorize_unit_transfer_id, :p_warehouse_id, :p_user_id, :p_last_log_by)');
        $stmt->bindValue(':p_authorize_unit_transfer_id', $p_authorize_unit_transfer_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_warehouse_id', $p_warehouse_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertAuthorizeUnitTransfer
    # Description: Inserts the authorize unit transfer.
    #
    # Parameters:
    # - $p_warehouse_id (string): The authorize unit transfer name.
    # - $p_user_id (string): The authorize unit transfer name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertAuthorizeUnitTransfer($p_warehouse_id, $p_user_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertAuthorizeUnitTransfer(:p_warehouse_id, :p_user_id, :p_last_log_by, @p_authorize_unit_transfer_id)');
        $stmt->bindValue(':p_warehouse_id', $p_warehouse_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_user_id', $p_user_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_authorize_unit_transfer_id AS p_authorize_unit_transfer_id");
        $p_authorize_unit_transfer_id = $result->fetch(PDO::FETCH_ASSOC)['p_authorize_unit_transfer_id'];

        return $p_authorize_unit_transfer_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkAuthorizeUnitTransferExist
    # Description: Checks if a authorize unit transfer exists.
    #
    # Parameters:
    # - $p_authorize_unit_transfer_id (int): The authorize unit transfer ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkAuthorizeUnitTransferExist($p_authorize_unit_transfer_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkAuthorizeUnitTransferExist(:p_authorize_unit_transfer_id)');
        $stmt->bindValue(':p_authorize_unit_transfer_id', $p_authorize_unit_transfer_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteAuthorizeUnitTransfer
    # Description: Deletes the authorize unit transfer.
    #
    # Parameters:
    # - $p_authorize_unit_transfer_id (int): The authorize unit transfer ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteAuthorizeUnitTransfer($p_authorize_unit_transfer_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteAuthorizeUnitTransfer(:p_authorize_unit_transfer_id)');
        $stmt->bindValue(':p_authorize_unit_transfer_id', $p_authorize_unit_transfer_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getAuthorizeUnitTransfer
    # Description: Retrieves the details of a authorize unit transfer.
    #
    # Parameters:
    # - $p_authorize_unit_transfer_id (int): The authorize unit transfer ID.
    #
    # Returns:
    # - An array containing the authorize unit transfer details.
    #
    # -------------------------------------------------------------
    public function getAuthorizeUnitTransfer($p_authorize_unit_transfer_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getAuthorizeUnitTransfer(:p_authorize_unit_transfer_id)');
        $stmt->bindValue(':p_authorize_unit_transfer_id', $p_authorize_unit_transfer_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

}
?>