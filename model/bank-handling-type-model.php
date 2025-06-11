<?php
/**
* Class BankHandlingTypeModel
*
* The BankHandlingTypeModel class handles bank handling type related operations and interactions.
*/
class BankHandlingTypeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateBankHandlingType
    # Description: Updates the bank handling type.
    #
    # Parameters:
    # - $p_bank_handling_type_id (int): The bank handling type ID.
    # - $p_bank_handling_type_name (string): The bank handling type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateBankHandlingType($p_bank_handling_type_id, $p_bank_handling_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBankHandlingType(:p_bank_handling_type_id, :p_bank_handling_type_name, :p_last_log_by)');
        $stmt->bindValue(':p_bank_handling_type_id', $p_bank_handling_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_bank_handling_type_name', $p_bank_handling_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertBankHandlingType
    # Description: Inserts the bank handling type.
    #
    # Parameters:
    # - $p_bank_handling_type_name (string): The bank handling type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertBankHandlingType($p_bank_handling_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertBankHandlingType(:p_bank_handling_type_name, :p_last_log_by, @p_bank_handling_type_id)');
        $stmt->bindValue(':p_bank_handling_type_name', $p_bank_handling_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_bank_handling_type_id AS p_bank_handling_type_id");
        $p_bank_handling_type_id = $result->fetch(PDO::FETCH_ASSOC)['p_bank_handling_type_id'];

        return $p_bank_handling_type_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkBankHandlingTypeExist
    # Description: Checks if a bank handling type exists.
    #
    # Parameters:
    # - $p_bank_handling_type_id (int): The bank handling type ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkBankHandlingTypeExist($p_bank_handling_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkBankHandlingTypeExist(:p_bank_handling_type_id)');
        $stmt->bindValue(':p_bank_handling_type_id', $p_bank_handling_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBankHandlingType
    # Description: Deletes the bank handling type.
    #
    # Parameters:
    # - $p_bank_handling_type_id (int): The bank handling type ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteBankHandlingType($p_bank_handling_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteBankHandlingType(:p_bank_handling_type_id)');
        $stmt->bindValue(':p_bank_handling_type_id', $p_bank_handling_type_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBankHandlingType
    # Description: Retrieves the details of a bank handling type.
    #
    # Parameters:
    # - $p_bank_handling_type_id (int): The bank handling type ID.
    #
    # Returns:
    # - An array containing the bank handling type details.
    #
    # -------------------------------------------------------------
    public function getBankHandlingType($p_bank_handling_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getBankHandlingType(:p_bank_handling_type_id)');
        $stmt->bindValue(':p_bank_handling_type_id', $p_bank_handling_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateBankHandlingType
    # Description: Duplicates the bank handling type.
    #
    # Parameters:
    # - $p_bank_handling_type_id (int): The bank handling type ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateBankHandlingType($p_bank_handling_type_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateBankHandlingType(:p_bank_handling_type_id, :p_last_log_by, @p_new_bank_handling_type_id)');
        $stmt->bindValue(':p_bank_handling_type_id', $p_bank_handling_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_bank_handling_type_id AS bank_handling_type_id");
        $bankHandlingTypeID = $result->fetch(PDO::FETCH_ASSOC)['bank_handling_type_id'];

        return $bankHandlingTypeID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateBankHandlingTypeOptions
    # Description: Generates the bank handling type options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateBankHandlingTypeOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateBankHandlingTypeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $bankHandlingTypeID = $row['bank_handling_type_id'];
            $bankHandlingTypeName = $row['bank_handling_type_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($bankHandlingTypeID, ENT_QUOTES) . '">' . htmlspecialchars($bankHandlingTypeName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>