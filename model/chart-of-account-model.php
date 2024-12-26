<?php
/**
* Class ChartOfAccountModel
*
* The ChartOfAccountModel class handles chart of account related operations and interactions.
*/
class ChartOfAccountModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateChartOfAccount
    # Description: Updates the chart of account.
    #
    # Parameters:
    # - $p_chart_of_account_id (int): The chart of account ID.
    # - $p_code (string): The chart of account name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateChartOfAccount($p_chart_of_account_id, $p_code, $p_name, $p_account_type, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateChartOfAccount(:p_chart_of_account_id, :p_code, :p_name, :p_account_type, :p_last_log_by)');
        $stmt->bindValue(':p_chart_of_account_id', $p_chart_of_account_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_code', $p_code, PDO::PARAM_STR);
        $stmt->bindValue(':p_name', $p_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_account_type', $p_account_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertChartOfAccount
    # Description: Inserts the chart of account.
    #
    # Parameters:
    # - $p_chart_of_account_name (string): The chart of account name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertChartOfAccount($p_code, $p_name, $p_account_type, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertChartOfAccount(:p_code, :p_name, :p_account_type, :p_last_log_by, @p_chart_of_account_id)');
        $stmt->bindValue(':p_code', $p_code, PDO::PARAM_STR);
        $stmt->bindValue(':p_name', $p_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_account_type', $p_account_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_chart_of_account_id AS p_chart_of_account_id");
        $p_chart_of_account_id = $result->fetch(PDO::FETCH_ASSOC)['p_chart_of_account_id'];

        return $p_chart_of_account_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkChartOfAccountExist
    # Description: Checks if a chart of account exists.
    #
    # Parameters:
    # - $p_chart_of_account_id (int): The chart of account ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkChartOfAccountExist($p_chart_of_account_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkChartOfAccountExist(:p_chart_of_account_id)');
        $stmt->bindValue(':p_chart_of_account_id', $p_chart_of_account_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteChartOfAccount
    # Description: Deletes the chart of account.
    #
    # Parameters:
    # - $p_chart_of_account_id (int): The chart of account ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteChartOfAccount($p_chart_of_account_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteChartOfAccount(:p_chart_of_account_id)');
        $stmt->bindValue(':p_chart_of_account_id', $p_chart_of_account_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getChartOfAccount
    # Description: Retrieves the details of a chart of account.
    #
    # Parameters:
    # - $p_chart_of_account_id (int): The chart of account ID.
    #
    # Returns:
    # - An array containing the chart of account details.
    #
    # -------------------------------------------------------------
    public function getChartOfAccount($p_chart_of_account_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getChartOfAccount(:p_chart_of_account_id)');
        $stmt->bindValue(':p_chart_of_account_id', $p_chart_of_account_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateChartOfAccount
    # Description: Duplicates the chart of account.
    #
    # Parameters:
    # - $p_chart_of_account_id (int): The chart of account ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateChartOfAccount($p_chart_of_account_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateChartOfAccount(:p_chart_of_account_id, :p_last_log_by, @p_new_chart_of_account_id)');
        $stmt->bindValue(':p_chart_of_account_id', $p_chart_of_account_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_chart_of_account_id AS chart_of_account_id");
        $chartOfAccountIDID = $result->fetch(PDO::FETCH_ASSOC)['chart_of_account_id'];

        return $chartOfAccountIDID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateChartOfAccountOptions
    # Description: Generates the chart of account options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateChartOfAccountOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateChartOfAccountOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $chartOfAccountID = $row['chart_of_account_id'];
            $name = $row['name'];
            $code = $row['code'];

            $htmlOptions .= '<option value="' . htmlspecialchars($chartOfAccountID, ENT_QUOTES) . '">' . $code . ' ' . $name .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
    public function generateChartOfAccountDisbursementOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateChartOfAccountDisbursementOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $chartOfAccountID = $row['chart_of_account_id'];
            $name = $row['name'];
            $code = $row['code'];

            $htmlOptions .= '<option value="' . htmlspecialchars($chartOfAccountID, ENT_QUOTES) . '">' . $name .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>