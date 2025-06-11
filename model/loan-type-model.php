<?php
/**
* Class LoanTypeModel
*
* The LoanTypeModel class handles loan type related operations and interactions.
*/
class LoanTypeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateLoanType
    # Description: Updates the loan type.
    #
    # Parameters:
    # - $p_loan_type_id (int): The loan type ID.
    # - $p_loan_type_name (string): The loan type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateLoanType($p_loan_type_id, $p_loan_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateLoanType(:p_loan_type_id, :p_loan_type_name, :p_last_log_by)');
        $stmt->bindValue(':p_loan_type_id', $p_loan_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_loan_type_name', $p_loan_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertLoanType
    # Description: Inserts the loan type.
    #
    # Parameters:
    # - $p_loan_type_name (string): The loan type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertLoanType($p_loan_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertLoanType(:p_loan_type_name, :p_last_log_by, @p_loan_type_id)');
        $stmt->bindValue(':p_loan_type_name', $p_loan_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_loan_type_id AS p_loan_type_id");
        $p_loan_type_id = $result->fetch(PDO::FETCH_ASSOC)['p_loan_type_id'];

        return $p_loan_type_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkLoanTypeExist
    # Description: Checks if a loan type exists.
    #
    # Parameters:
    # - $p_loan_type_id (int): The loan type ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkLoanTypeExist($p_loan_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkLoanTypeExist(:p_loan_type_id)');
        $stmt->bindValue(':p_loan_type_id', $p_loan_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLoanType
    # Description: Deletes the loan type.
    #
    # Parameters:
    # - $p_loan_type_id (int): The loan type ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteLoanType($p_loan_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteLoanType(:p_loan_type_id)');
        $stmt->bindValue(':p_loan_type_id', $p_loan_type_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLoanType
    # Description: Retrieves the details of a loan type.
    #
    # Parameters:
    # - $p_loan_type_id (int): The loan type ID.
    #
    # Returns:
    # - An array containing the loan type details.
    #
    # -------------------------------------------------------------
    public function getLoanType($p_loan_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getLoanType(:p_loan_type_id)');
        $stmt->bindValue(':p_loan_type_id', $p_loan_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateLoanType
    # Description: Duplicates the loan type.
    #
    # Parameters:
    # - $p_loan_type_id (int): The loan type ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateLoanType($p_loan_type_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateLoanType(:p_loan_type_id, :p_last_log_by, @p_new_loan_type_id)');
        $stmt->bindValue(':p_loan_type_id', $p_loan_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_loan_type_id AS loan_type_id");
        $loanTypeID = $result->fetch(PDO::FETCH_ASSOC)['loan_type_id'];

        return $loanTypeID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateLoanTypeOptions
    # Description: Generates the loan type options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateLoanTypeOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateLoanTypeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $loanTypeID = $row['loan_type_id'];
            $loanTypeName = $row['loan_type_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($loanTypeID, ENT_QUOTES) . '">' . htmlspecialchars($loanTypeName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>