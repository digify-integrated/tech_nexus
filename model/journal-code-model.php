<?php
/**
* Class JournalCodeModel
*
* The JournalCodeModel class handles journal code related operations and interactions.
*/
class JournalCodeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateJournalCode
    # Description: Updates the journal code.
    #
    # Parameters:
    # - $p_journal_code_id (int): The journal code ID.
    # - $p_code (string): The journal code name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateJournalCode($p_journal_code_id, $p_company_id, $p_transaction_type, $p_product_type_id, $p_transaction, $p_item, $p_debit, $p_credit, $p_reference_code, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateJournalCode(:p_journal_code_id, :p_company_id, :p_transaction_type, :p_product_type_id, :p_transaction, :p_item, :p_debit, :p_credit, :p_reference_code, :p_last_log_by)');
        $stmt->bindValue(':p_journal_code_id', $p_journal_code_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction_type', $p_transaction_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_type_id', $p_product_type_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction', $p_transaction, PDO::PARAM_STR);
        $stmt->bindValue(':p_item', $p_item, PDO::PARAM_STR);
        $stmt->bindValue(':p_debit', $p_debit, PDO::PARAM_STR);
        $stmt->bindValue(':p_credit', $p_credit, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_code', $p_reference_code, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertJournalCode
    # Description: Inserts the journal code.
    #
    # Parameters:
    # - $p_chart_of_account_name (string): The journal code name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertJournalCode($p_company_id, $p_transaction_type, $p_product_type_id, $p_transaction, $p_item, $p_debit, $p_credit, $p_reference_code, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertJournalCode(:p_company_id, :p_transaction_type, :p_product_type_id, :p_transaction, :p_item, :p_debit, :p_credit, :p_reference_code, :p_last_log_by, @p_journal_code_id)');
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction_type', $p_transaction_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_type_id', $p_product_type_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction', $p_transaction, PDO::PARAM_STR);
        $stmt->bindValue(':p_item', $p_item, PDO::PARAM_STR);
        $stmt->bindValue(':p_debit', $p_debit, PDO::PARAM_STR);
        $stmt->bindValue(':p_credit', $p_credit, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_code', $p_reference_code, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_journal_code_id AS p_journal_code_id");
        $p_journal_code_id = $result->fetch(PDO::FETCH_ASSOC)['p_journal_code_id'];

        return $p_journal_code_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkJournalCodeExist
    # Description: Checks if a journal code exists.
    #
    # Parameters:
    # - $p_journal_code_id (int): The journal code ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkJournalCodeExist($p_journal_code_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkJournalCodeExist(:p_journal_code_id)');
        $stmt->bindValue(':p_journal_code_id', $p_journal_code_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteJournalCode
    # Description: Deletes the journal code.
    #
    # Parameters:
    # - $p_journal_code_id (int): The journal code ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteJournalCode($p_journal_code_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteJournalCode(:p_journal_code_id)');
        $stmt->bindValue(':p_journal_code_id', $p_journal_code_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getJournalCode
    # Description: Retrieves the details of a journal code.
    #
    # Parameters:
    # - $p_journal_code_id (int): The journal code ID.
    #
    # Returns:
    # - An array containing the journal code details.
    #
    # -------------------------------------------------------------
    public function getJournalCode($p_journal_code_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getJournalCode(:p_journal_code_id)');
        $stmt->bindValue(':p_journal_code_id', $p_journal_code_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateJournalCode
    # Description: Duplicates the journal code.
    #
    # Parameters:
    # - $p_journal_code_id (int): The journal code ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateJournalCode($p_journal_code_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateJournalCode(:p_journal_code_id, :p_last_log_by, @p_new_journal_code_id)');
        $stmt->bindValue(':p_journal_code_id', $p_journal_code_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_journal_code_id AS journal_code_id");
        $chartOfAccountIDID = $result->fetch(PDO::FETCH_ASSOC)['journal_code_id'];

        return $chartOfAccountIDID;
    }
    # -------------------------------------------------------------
}
?>