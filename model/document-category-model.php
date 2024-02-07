<?php
/**
* Class DocumentCategoryModel
*
* The DocumentCategoryModel class handles document category related operations and interactions.
*/
class DocumentCategoryModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateDocumentCategory
    # Description: Updates the document category.
    #
    # Parameters:
    # - $p_document_category_id (int): The document category ID.
    # - $p_document_category_name (string): The document category name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateDocumentCategory($p_document_category_id, $p_document_category_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateDocumentCategory(:p_document_category_id, :p_document_category_name, :p_last_log_by)');
        $stmt->bindValue(':p_document_category_id', $p_document_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_document_category_name', $p_document_category_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertDocumentCategory
    # Description: Inserts the document category.
    #
    # Parameters:
    # - $p_document_category_name (string): The document category name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertDocumentCategory($p_document_category_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertDocumentCategory(:p_document_category_name, :p_last_log_by, @p_document_category_id)');
        $stmt->bindValue(':p_document_category_name', $p_document_category_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_document_category_id AS p_document_category_id");
        $p_document_category_id = $result->fetch(PDO::FETCH_ASSOC)['p_document_category_id'];

        return $p_document_category_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkDocumentCategoryExist
    # Description: Checks if a document category exists.
    #
    # Parameters:
    # - $p_document_category_id (int): The document category ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkDocumentCategoryExist($p_document_category_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkDocumentCategoryExist(:p_document_category_id)');
        $stmt->bindValue(':p_document_category_id', $p_document_category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteDocumentCategory
    # Description: Deletes the document category.
    #
    # Parameters:
    # - $p_document_category_id (int): The document category ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteDocumentCategory($p_document_category_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteDocumentCategory(:p_document_category_id)');
        $stmt->bindValue(':p_document_category_id', $p_document_category_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getDocumentCategory
    # Description: Retrieves the details of a document category.
    #
    # Parameters:
    # - $p_document_category_id (int): The document category ID.
    #
    # Returns:
    # - An array containing the document category details.
    #
    # -------------------------------------------------------------
    public function getDocumentCategory($p_document_category_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getDocumentCategory(:p_document_category_id)');
        $stmt->bindValue(':p_document_category_id', $p_document_category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateDocumentCategory
    # Description: Duplicates the document category.
    #
    # Parameters:
    # - $p_document_category_id (int): The document category ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateDocumentCategory($p_document_category_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateDocumentCategory(:p_document_category_id, :p_last_log_by, @p_new_document_category_id)');
        $stmt->bindValue(':p_document_category_id', $p_document_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_document_category_id AS document_category_id");
        $systemActionID = $result->fetch(PDO::FETCH_ASSOC)['document_category_id'];

        return $systemActionID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateDocumentCategoryOptions
    # Description: Generates the document category options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateDocumentCategoryOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateDocumentCategoryOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $documentCategoryID = $row['document_category_id'];
            $documentCategoryName = $row['document_category_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($documentCategoryID, ENT_QUOTES) . '">' . htmlspecialchars($documentCategoryName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateDocumentCategoryCheckBox
    # Description: Generates the document category check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateDocumentCategoryCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateDocumentCategoryOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $documentCategoryID = $row['document_category_id'];
            $documentCategoryName = $row['document_category_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input document-category-filter" type="checkbox" id="document-category-' . htmlspecialchars($documentCategoryID, ENT_QUOTES) . '" value="' . htmlspecialchars($documentCategoryID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="document-category-' . htmlspecialchars($documentCategoryID, ENT_QUOTES) . '">' . htmlspecialchars($documentCategoryName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>