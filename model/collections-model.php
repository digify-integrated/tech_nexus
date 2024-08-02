<?php
/**
* Class CollectionModel
*
* The CollectionModel class handles collection related operations and interactions.
*/
class CollectionsModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateCollections
    # Description: Updates the collection.
    #
    # Parameters:
    # - $p_loan_collection_id (int): The collection ID.
    # - $p_product_category_name (string): The collection name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateCollection($p_loan_collection_id, $p_sales_proposal_id, $p_loan_number, $p_product_id, $p_customer_id, $p_pdc_type, $p_mode_of_payment, $p_or_number, $p_or_date, $p_payment_date, $p_payment_amount, $p_reference_number, $p_payment_details, $p_company_id, $p_deposited_to, $p_remarks, $p_collected_from, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCollection(:p_loan_collection_id, :p_sales_proposal_id, :p_loan_number, :p_product_id, :p_customer_id, :p_pdc_type, :p_mode_of_payment, :p_or_number, :p_or_date, :p_payment_date, :p_payment_amount, :p_reference_number, :p_payment_details, :p_company_id, :p_deposited_to, :p_remarks, :p_collected_from, :p_last_log_by)');
        $stmt->bindValue(':p_loan_collection_id', $p_loan_collection_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_loan_number', $p_loan_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_customer_id', $p_customer_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_pdc_type', $p_pdc_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_mode_of_payment', $p_mode_of_payment, PDO::PARAM_STR);
        $stmt->bindValue(':p_or_number', $p_or_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_or_date', $p_or_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_date', $p_payment_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_amount', $p_payment_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_details', $p_payment_details, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_deposited_to', $p_deposited_to, PDO::PARAM_INT);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_collected_from', $p_collected_from, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateCollectionStatus
    # Description: Updates the collection.
    #
    # Parameters:
    # - $p_loan_collection_id (int): The collection ID.
    # - $p_product_category_name (string): The collection name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateCollectionStatus($p_loan_collection_id, $p_collection_status, $p_reason, $p_remarks, $p_reference_number, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCollectionStatus(:p_loan_collection_id, :p_collection_status, :p_reason, :p_remarks, :p_reference_number, :p_last_log_by)');
        $stmt->bindValue(':p_loan_collection_id', $p_loan_collection_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_collection_status', $p_collection_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_reason', $p_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertCollections
    # Description: Inserts the collection.
    #
    # Parameters:
    # - $p_product_category_name (string): The collection name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertCollection($p_sales_proposal_id, $p_loan_number, $p_product_id, $p_customer_id, $p_pdc_type, $p_mode_of_payment, $p_or_number, $p_or_date, $p_payment_date, $p_payment_amount, $p_reference_number, $p_payment_details, $p_company_id, $p_deposited_to, $p_remarks, $p_collected_from, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertCollection(:p_sales_proposal_id, :p_loan_number, :p_product_id, :p_customer_id, :p_pdc_type, :p_mode_of_payment, :p_or_number, :p_or_date, :p_payment_date, :p_payment_amount, :p_reference_number, :p_payment_details, :p_company_id, :p_deposited_to, :p_remarks, :p_collected_from, :p_last_log_by, @p_loan_collection_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_loan_number', $p_loan_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_customer_id', $p_customer_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_pdc_type', $p_pdc_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_mode_of_payment', $p_mode_of_payment, PDO::PARAM_STR);
        $stmt->bindValue(':p_or_number', $p_or_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_or_date', $p_or_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_date', $p_payment_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_amount', $p_payment_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_details', $p_payment_details, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_deposited_to', $p_deposited_to, PDO::PARAM_INT);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_collected_from', $p_collected_from, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_loan_collection_id AS p_loan_collection_id");
        $p_loan_collection_id = $result->fetch(PDO::FETCH_ASSOC)['p_loan_collection_id'];

        return $p_loan_collection_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkLoanCollectionExist
    # Description: Checks if a collection exists.
    #
    # Parameters:
    # - $p_loan_collection_id (int): The collection ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkLoanCollectionExist($p_loan_collection_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkLoanCollectionExist(:p_loan_collection_id)');
        $stmt->bindValue(':p_loan_collection_id', $p_loan_collection_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteCollections
    # Description: Deletes the collection.
    #
    # Parameters:
    # - $p_loan_collection_id (int): The collection ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteCollections($p_loan_collection_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCollections(:p_loan_collection_id)');
        $stmt->bindValue(':p_loan_collection_id', $p_loan_collection_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getCollections
    # Description: Retrieves the details of a collection.
    #
    # Parameters:
    # - $p_loan_collection_id (int): The collection ID.
    #
    # Returns:
    # - An array containing the collection details.
    #
    # -------------------------------------------------------------
    public function getCollections($p_loan_collection_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCollections(:p_loan_collection_id)');
        $stmt->bindValue(':p_loan_collection_id', $p_loan_collection_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>