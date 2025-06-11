<?php
/**
* Class AssetTypeModel
*
* The AssetTypeModel class handles asset type related operations and interactions.
*/
class AssetTypeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateAssetType
    # Description: Updates the asset type.
    #
    # Parameters:
    # - $p_asset_type_id (int): The asset type ID.
    # - $p_asset_type_name (string): The asset type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateAssetType($p_asset_type_id, $p_asset_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateAssetType(:p_asset_type_id, :p_asset_type_name, :p_last_log_by)');
        $stmt->bindValue(':p_asset_type_id', $p_asset_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_asset_type_name', $p_asset_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertAssetType
    # Description: Inserts the asset type.
    #
    # Parameters:
    # - $p_asset_type_name (string): The asset type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertAssetType($p_asset_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertAssetType(:p_asset_type_name, :p_last_log_by, @p_asset_type_id)');
        $stmt->bindValue(':p_asset_type_name', $p_asset_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_asset_type_id AS p_asset_type_id");
        $p_asset_type_id = $result->fetch(PDO::FETCH_ASSOC)['p_asset_type_id'];

        return $p_asset_type_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkAssetTypeExist
    # Description: Checks if a asset type exists.
    #
    # Parameters:
    # - $p_asset_type_id (int): The asset type ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkAssetTypeExist($p_asset_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkAssetTypeExist(:p_asset_type_id)');
        $stmt->bindValue(':p_asset_type_id', $p_asset_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteAssetType
    # Description: Deletes the asset type.
    #
    # Parameters:
    # - $p_asset_type_id (int): The asset type ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteAssetType($p_asset_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteAssetType(:p_asset_type_id)');
        $stmt->bindValue(':p_asset_type_id', $p_asset_type_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getAssetType
    # Description: Retrieves the details of a asset type.
    #
    # Parameters:
    # - $p_asset_type_id (int): The asset type ID.
    #
    # Returns:
    # - An array containing the asset type details.
    #
    # -------------------------------------------------------------
    public function getAssetType($p_asset_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getAssetType(:p_asset_type_id)');
        $stmt->bindValue(':p_asset_type_id', $p_asset_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateAssetType
    # Description: Duplicates the asset type.
    #
    # Parameters:
    # - $p_asset_type_id (int): The asset type ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateAssetType($p_asset_type_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateAssetType(:p_asset_type_id, :p_last_log_by, @p_new_asset_type_id)');
        $stmt->bindValue(':p_asset_type_id', $p_asset_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_asset_type_id AS asset_type_id");
        $assetTypeID = $result->fetch(PDO::FETCH_ASSOC)['asset_type_id'];

        return $assetTypeID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateAssetTypeOptions
    # Description: Generates the asset type options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateAssetTypeOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateAssetTypeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $assetTypeID = $row['asset_type_id'];
            $assetTypeName = $row['asset_type_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($assetTypeID, ENT_QUOTES) . '">' . htmlspecialchars($assetTypeName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>