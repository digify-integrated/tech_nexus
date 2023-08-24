<?php
/**
* Class CurrencyModel
*
* The CurrencyModel class handles currency related operations and interactions.
*/
class CurrencyModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateCurrency
    # Description: Updates the currency.
    #
    # Parameters:
    # - $p_currency_id (int): The currency ID.
    # - $p_currency_name (string): The currency name.
    # - $p_symbol (string): The symbol of the currency.
    # - $p_shorthand (string): The shorthand of the currency.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateCurrency($p_currency_id, $p_currency_name, $p_symbol, $p_shorthand, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCurrency(:p_currency_id, :p_currency_name, :p_symbol, :p_shorthand, :p_last_log_by)');
        $stmt->bindValue(':p_currency_id', $p_currency_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_currency_name', $p_currency_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_symbol', $p_symbol, PDO::PARAM_STR);
        $stmt->bindValue(':p_shorthand', $p_shorthand, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertCurrency
    # Description: Inserts the currency.
    #
    # Parameters:
    # - $p_currency_name (string): The currency name.
    # - $p_symbol (string): The symbol of the currency.
    # - $p_shorthand (string): The shorthand of the currency.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertCurrency($p_currency_name, $p_symbol, $p_shorthand, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertCurrency(:p_currency_name, :p_symbol, :p_shorthand, :p_last_log_by, @p_currency_id)');
        $stmt->bindValue(':p_currency_name', $p_currency_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_symbol', $p_symbol, PDO::PARAM_STR);
        $stmt->bindValue(':p_shorthand', $p_shorthand, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_currency_id AS p_currency_id");
        $p_currency_id = $result->fetch(PDO::FETCH_ASSOC)['p_currency_id'];

        return $p_currency_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkCurrencyExist
    # Description: Checks if a currency exists.
    #
    # Parameters:
    # - $p_currency_id (int): The currency ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkCurrencyExist($p_currency_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkCurrencyExist(:p_currency_id)');
        $stmt->bindValue(':p_currency_id', $p_currency_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteCurrency
    # Description: Deletes the currency.
    #
    # Parameters:
    # - $p_currency_id (int): The currency ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteCurrency($p_currency_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCurrency(:p_currency_id)');
        $stmt->bindValue(':p_currency_id', $p_currency_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getCurrency
    # Description: Retrieves the details of a currency.
    #
    # Parameters:
    # - $p_currency_id (int): The currency ID.
    #
    # Returns:
    # - An array containing the currency details.
    #
    # -------------------------------------------------------------
    public function getCurrency($p_currency_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCurrency(:p_currency_id)');
        $stmt->bindValue(':p_currency_id', $p_currency_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateCurrency
    # Description: Duplicates the currency.
    #
    # Parameters:
    # - $p_currency_id (int): The currency ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateCurrency($p_currency_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateCurrency(:p_currency_id, :p_last_log_by, @p_new_currency_id)');
        $stmt->bindValue(':p_currency_id', $p_currency_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_currency_id AS currency_id");
        $systemActionID = $result->fetch(PDO::FETCH_ASSOC)['currency_id'];

        return $systemActionID;
    }
    # -------------------------------------------------------------
}
?>