<?php
/**
* Class SupplierModel
*
* The SupplierModel class handles supplier related operations and interactions.
*/
class SupplierModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSupplier
    # Description: Updates the supplier.
    #
    # Parameters:
    # - $p_supplier_id (int): The supplier ID.
    # - $p_supplier_name (string): The supplier name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSupplier($p_supplier_id, $p_supplier_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSupplier(:p_supplier_id, :p_supplier_name, :p_last_log_by)');
        $stmt->bindValue(':p_supplier_id', $p_supplier_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_supplier_name', $p_supplier_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertSupplier
    # Description: Inserts the supplier.
    #
    # Parameters:
    # - $p_supplier_name (string): The supplier name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertSupplier($p_supplier_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertSupplier(:p_supplier_name, :p_last_log_by, @p_supplier_id)');
        $stmt->bindValue(':p_supplier_name', $p_supplier_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_supplier_id AS p_supplier_id");
        $p_supplier_id = $result->fetch(PDO::FETCH_ASSOC)['p_supplier_id'];

        return $p_supplier_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkSupplierExist
    # Description: Checks if a supplier exists.
    #
    # Parameters:
    # - $p_supplier_id (int): The supplier ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkSupplierExist($p_supplier_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkSupplierExist(:p_supplier_id)');
        $stmt->bindValue(':p_supplier_id', $p_supplier_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSupplier
    # Description: Deletes the supplier.
    #
    # Parameters:
    # - $p_supplier_id (int): The supplier ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteSupplier($p_supplier_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteSupplier(:p_supplier_id)');
        $stmt->bindValue(':p_supplier_id', $p_supplier_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSupplier
    # Description: Retrieves the details of a supplier.
    #
    # Parameters:
    # - $p_supplier_id (int): The supplier ID.
    #
    # Returns:
    # - An array containing the supplier details.
    #
    # -------------------------------------------------------------
    public function getSupplier($p_supplier_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getSupplier(:p_supplier_id)');
        $stmt->bindValue(':p_supplier_id', $p_supplier_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateSupplier
    # Description: Duplicates the supplier.
    #
    # Parameters:
    # - $p_supplier_id (int): The supplier ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateSupplier($p_supplier_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateSupplier(:p_supplier_id, :p_last_log_by, @p_new_supplier_id)');
        $stmt->bindValue(':p_supplier_id', $p_supplier_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_supplier_id AS supplier_id");
        $supplieriD = $result->fetch(PDO::FETCH_ASSOC)['supplier_id'];

        return $supplieriD;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateSupplierOptions
    # Description: Generates the supplier options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateSupplierOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateSupplierOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $supplierID = $row['supplier_id'];
            $supplierName = $row['supplier_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($supplierID, ENT_QUOTES) . '">' . htmlspecialchars($supplierName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateSupplierCheckBox
    # Description: Generates the supplier check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateSupplierCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateSupplierOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $supplierID = $row['supplier_id'];
            $supplierName = $row['supplier_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input supplier-filter" type="checkbox" id="supplier-' . htmlspecialchars($supplierID, ENT_QUOTES) . '" value="' . htmlspecialchars($supplierID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="supplier-' . htmlspecialchars($supplierID, ENT_QUOTES) . '">' . htmlspecialchars($supplierName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>