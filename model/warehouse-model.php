<?php
/**
* Class WarehouseModel
*
* The WarehouseModel class handles warehouse related operations and interactions.
*/
class WarehouseModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateWarehouse
    # Description: Updates the warehouse.
    #
    # Parameters:
    # - $p_warehouse_id (int): The warehouse ID.
    # - $p_warehouse_name (string): The warehouse name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateWarehouse($p_warehouse_id, $p_warehouse_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateWarehouse(:p_warehouse_id, :p_warehouse_name, :p_last_log_by)');
        $stmt->bindValue(':p_warehouse_id', $p_warehouse_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_warehouse_name', $p_warehouse_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertWarehouse
    # Description: Inserts the warehouse.
    #
    # Parameters:
    # - $p_warehouse_name (string): The warehouse name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertWarehouse($p_warehouse_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertWarehouse(:p_warehouse_name, :p_last_log_by, @p_warehouse_id)');
        $stmt->bindValue(':p_warehouse_name', $p_warehouse_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_warehouse_id AS p_warehouse_id");
        $p_warehouse_id = $result->fetch(PDO::FETCH_ASSOC)['p_warehouse_id'];

        return $p_warehouse_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkWarehouseExist
    # Description: Checks if a warehouse exists.
    #
    # Parameters:
    # - $p_warehouse_id (int): The warehouse ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkWarehouseExist($p_warehouse_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkWarehouseExist(:p_warehouse_id)');
        $stmt->bindValue(':p_warehouse_id', $p_warehouse_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteWarehouse
    # Description: Deletes the warehouse.
    #
    # Parameters:
    # - $p_warehouse_id (int): The warehouse ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteWarehouse($p_warehouse_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteWarehouse(:p_warehouse_id)');
        $stmt->bindValue(':p_warehouse_id', $p_warehouse_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getWarehouse
    # Description: Retrieves the details of a warehouse.
    #
    # Parameters:
    # - $p_warehouse_id (int): The warehouse ID.
    #
    # Returns:
    # - An array containing the warehouse details.
    #
    # -------------------------------------------------------------
    public function getWarehouse($p_warehouse_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getWarehouse(:p_warehouse_id)');
        $stmt->bindValue(':p_warehouse_id', $p_warehouse_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateWarehouse
    # Description: Duplicates the warehouse.
    #
    # Parameters:
    # - $p_warehouse_id (int): The warehouse ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateWarehouse($p_warehouse_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateWarehouse(:p_warehouse_id, :p_last_log_by, @p_new_warehouse_id)');
        $stmt->bindValue(':p_warehouse_id', $p_warehouse_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_warehouse_id AS warehouse_id");
        $systemActionID = $result->fetch(PDO::FETCH_ASSOC)['warehouse_id'];

        return $systemActionID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateWarehouseOptions
    # Description: Generates the warehouse options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateWarehouseOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateWarehouseOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $warehouseID = $row['warehouse_id'];
            $warehouseName = $row['warehouse_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($warehouseID, ENT_QUOTES) . '">' . htmlspecialchars($warehouseName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateWarehouseCheckBox
    # Description: Generates the warehouse check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateWarehouseCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateWarehouseOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $warehouseID = $row['warehouse_id'];
            $warehouseName = $row['warehouse_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input warehouse-filter" type="checkbox" id="warehouse-' . htmlspecialchars($warehouseID, ENT_QUOTES) . '" value="' . htmlspecialchars($warehouseID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="warehouse-' . htmlspecialchars($warehouseID, ENT_QUOTES) . '">' . htmlspecialchars($warehouseName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>