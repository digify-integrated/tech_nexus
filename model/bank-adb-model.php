<?php
/**
* Class BankADBModel
*
* The BankADBModel class handles bank adb related operations and interactions.
*/
class BankADBModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateBankADB
    # Description: Updates the bank adb.
    #
    # Parameters:
    # - $p_bank_adb_id (int): The bank adb ID.
    # - $p_bank_adb_name (string): The bank adb name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateBankADB($p_bank_adb_id, $p_bank_adb_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBankADB(:p_bank_adb_id, :p_bank_adb_name, :p_last_log_by)');
        $stmt->bindValue(':p_bank_adb_id', $p_bank_adb_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_bank_adb_name', $p_bank_adb_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertBankADB
    # Description: Inserts the bank adb.
    #
    # Parameters:
    # - $p_bank_adb_name (string): The bank adb name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertBankADB($p_bank_adb_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertBankADB(:p_bank_adb_name, :p_last_log_by, @p_bank_adb_id)');
        $stmt->bindValue(':p_bank_adb_name', $p_bank_adb_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_bank_adb_id AS p_bank_adb_id");
        $p_bank_adb_id = $result->fetch(PDO::FETCH_ASSOC)['p_bank_adb_id'];

        return $p_bank_adb_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkBankADBExist
    # Description: Checks if a bank adb exists.
    #
    # Parameters:
    # - $p_bank_adb_id (int): The bank adb ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkBankADBExist($p_bank_adb_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkBankADBExist(:p_bank_adb_id)');
        $stmt->bindValue(':p_bank_adb_id', $p_bank_adb_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBankADB
    # Description: Deletes the bank adb.
    #
    # Parameters:
    # - $p_bank_adb_id (int): The bank adb ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteBankADB($p_bank_adb_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteBankADB(:p_bank_adb_id)');
        $stmt->bindValue(':p_bank_adb_id', $p_bank_adb_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBankADB
    # Description: Retrieves the details of a bank adb.
    #
    # Parameters:
    # - $p_bank_adb_id (int): The bank adb ID.
    #
    # Returns:
    # - An array containing the bank adb details.
    #
    # -------------------------------------------------------------
    public function getBankADB($p_bank_adb_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getBankADB(:p_bank_adb_id)');
        $stmt->bindValue(':p_bank_adb_id', $p_bank_adb_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateBankADB
    # Description: Duplicates the bank adb.
    #
    # Parameters:
    # - $p_bank_adb_id (int): The bank adb ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateBankADB($p_bank_adb_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateBankADB(:p_bank_adb_id, :p_last_log_by, @p_new_bank_adb_id)');
        $stmt->bindValue(':p_bank_adb_id', $p_bank_adb_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_bank_adb_id AS bank_adb_id");
        $bankADBID = $result->fetch(PDO::FETCH_ASSOC)['bank_adb_id'];

        return $bankADBID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateBankADBOptions
    # Description: Generates the bank adb options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateBankADBOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateBankADBOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $bankADBID = $row['bank_adb_id'];
            $bankADBName = $row['bank_adb_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($bankADBID, ENT_QUOTES) . '">' . htmlspecialchars($bankADBName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateBankADBCheckBox
    # Description: Generates the bank adb check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateBankADBCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateBankADBOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $bankADBID = $row['bank_adb_id'];
            $bankADBName = $row['bank_adb_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input bank-adb-filter" type="checkbox" id="bank-adb-' . htmlspecialchars($bankADBID, ENT_QUOTES) . '" value="' . htmlspecialchars($bankADBID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="bank-adb-' . htmlspecialchars($bankADBID, ENT_QUOTES) . '">' . htmlspecialchars($bankADBName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>