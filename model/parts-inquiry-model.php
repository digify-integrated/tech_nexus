<?php
/**
* Class PartsInquiryModel
*
* The PartsInquiryModel class handles parts inquiry related operations and interactions.
*/
class PartsInquiryModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updatePartsInquiry
    # Description: Updates the parts inquiry.
    #
    # Parameters:
    # - $p_parts_number (int): The parts inquiry ID.
    # - $p_parts_inquiry_name (string): The parts inquiry name.
    # - $p_parts_inquiry_description (string): The parts inquiry description.
    # - $p_max_file_size (double): The max file size.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updatePartsInquiry($p_parts_inquiry_id, $p_parts_number, $p_parts_description, $p_stock, $p_price, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsInquiry(:p_parts_inquiry_id, :p_parts_number, :p_parts_description, :p_stock, :p_price, :p_last_log_by)');
        $stmt->bindValue(':p_parts_inquiry_id', $p_parts_inquiry_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_parts_number', $p_parts_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_parts_description', $p_parts_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_stock', $p_stock, PDO::PARAM_STR);
        $stmt->bindValue(':p_price', $p_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updatePartsInquiry
    # Description: Updates the parts inquiry.
    #
    # Parameters:
    # - $p_parts_number (int): The parts inquiry ID.
    # - $p_parts_inquiry_name (string): The parts inquiry name.
    # - $p_parts_inquiry_description (string): The parts inquiry description.
    # - $p_max_file_size (double): The max file size.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updatePartsInquiryImport($p_parts_number, $p_parts_description, $p_stock, $p_price, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsInquiryImport(:p_parts_number, :p_parts_description, :p_stock, :p_price, :p_last_log_by)');
        $stmt->bindValue(':p_parts_number', $p_parts_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_parts_description', $p_parts_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_stock', $p_stock, PDO::PARAM_STR);
        $stmt->bindValue(':p_price', $p_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPartsInquiry
    # Description: Inserts the parts inquiry.
    #
    # Parameters:
    # - $p_parts_inquiry_name (string): The parts inquiry name.
    # - $p_parts_inquiry_description (string): The parts inquiry description.
    # - $p_max_file_size (double): The max file size.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertPartsInquiry($p_parts_number, $p_parts_description, $p_stock, $p_price, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartsInquiry(:p_parts_number, :p_parts_description, :p_stock, :p_price, :p_last_log_by, @p_parts_inquiry_id)');
        $stmt->bindValue(':p_parts_number', $p_parts_number, PDO::PARAM_INT);
        $stmt->bindValue(':p_parts_description', $p_parts_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_stock', $p_stock, PDO::PARAM_STR);
        $stmt->bindValue(':p_price', $p_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_parts_inquiry_id AS p_parts_inquiry_id");
        $p_parts_inquiry_id = $result->fetch(PDO::FETCH_ASSOC)['p_parts_inquiry_id'];

        return $p_parts_inquiry_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkPartsInquiryExist
    # Description: Checks if a parts inquiry exists.
    #
    # Parameters:
    # - $p_parts_number (int): The parts inquiry ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkPartsInquiryExist($p_parts_inquiry_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPartsInquiryExist(:p_parts_inquiry_id)');
        $stmt->bindValue(':p_parts_inquiry_id', $p_parts_inquiry_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkPartsNumberExist
    # Description: Checks if a parts inquiry exists.
    #
    # Parameters:
    # - $p_parts_number (int): The parts inquiry ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkPartsNumberExist($p_parts_number) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPartsNumberExist(:p_parts_number)');
        $stmt->bindValue(':p_parts_number', $p_parts_number, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePartsInquiry
    # Description: Deletes the parts inquiry.
    #
    # Parameters:
    # - $p_parts_number (int): The parts inquiry ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deletePartsInquiry($p_parts_inquiry_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePartsInquiry(:p_parts_inquiry_id)');
        $stmt->bindValue(':p_parts_inquiry_id', $p_parts_inquiry_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    
    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPartsInquiry
    # Description: Retrieves the details of a parts inquiry.
    #
    # Parameters:
    # - $p_parts_number (int): The parts inquiry ID.
    #
    # Returns:
    # - An array containing the parts inquiry details.
    #
    # -------------------------------------------------------------
    public function getPartsInquiry($p_parts_inquiry_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getPartsInquiry(:p_parts_inquiry_id)');
        $stmt->bindValue(':p_parts_inquiry_id', $p_parts_inquiry_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

}
?>