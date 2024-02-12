<?php
/**
* Class InterfaceSettingModel
*
* The InterfaceSettingModel class handles interface setting related operations and interactions.
*/
class InterfaceSettingModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateInterfaceSetting
    # Description: Updates the interface setting.
    #
    # Parameters:
    # - $p_interface_setting_id (int): The interface setting ID.
    # - $p_interface_setting_name (string): The interface setting name.
    # - $p_interface_setting_description (string): The interface setting description.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateInterfaceSetting($p_interface_setting_id, $p_interface_setting_name, $p_interface_setting_description, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateInterfaceSetting(:p_interface_setting_id, :p_interface_setting_name, :p_interface_setting_description, :p_last_log_by)');
        $stmt->bindValue(':p_interface_setting_id', $p_interface_setting_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_interface_setting_name', $p_interface_setting_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_interface_setting_description', $p_interface_setting_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateInterfaceSettingValue
    # Description: Updates the interface setting value.
    #
    # Parameters:
    # - $p_interface_setting_id (int): The interface setting ID.
    # - $p_value (string): The interface setting value.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateInterfaceSettingValue($p_interface_setting_id, $p_value, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateInterfaceSettingValue(:p_interface_setting_id, :p_value, :p_last_log_by)');
        $stmt->bindValue(':p_interface_setting_id', $p_interface_setting_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_value', $p_value, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertInterfaceSetting
    # Description: Inserts the interface setting.
    #
    # Parameters:
    # - $p_interface_setting_name (string): The interface setting name.
    # - $p_interface_setting_description (string): The interface setting description.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertInterfaceSetting($p_interface_setting_name, $p_interface_setting_description, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertInterfaceSetting(:p_interface_setting_name, :p_interface_setting_description, :p_last_log_by, @p_interface_setting_id)');
        $stmt->bindValue(':p_interface_setting_name', $p_interface_setting_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_interface_setting_description', $p_interface_setting_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_interface_setting_id AS p_interface_setting_id");
        $p_interface_setting_id = $result->fetch(PDO::FETCH_ASSOC)['p_interface_setting_id'];

        return $p_interface_setting_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkInterfaceSettingExist
    # Description: Checks if a interface setting exists.
    #
    # Parameters:
    # - $p_interface_setting_id (int): The interface setting ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkInterfaceSettingExist($p_interface_setting_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkInterfaceSettingExist(:p_interface_setting_id)');
        $stmt->bindValue(':p_interface_setting_id', $p_interface_setting_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteInterfaceSetting
    # Description: Deletes the interface setting.
    #
    # Parameters:
    # - $p_interface_setting_id (int): The interface setting ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteInterfaceSetting($p_interface_setting_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteInterfaceSetting(:p_interface_setting_id)');
        $stmt->bindValue(':p_interface_setting_id', $p_interface_setting_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getInterfaceSetting
    # Description: Retrieves the details of a interface setting.
    #
    # Parameters:
    # - $p_interface_setting_id (int): The interface setting ID.
    #
    # Returns:
    # - An array containing the interface setting details.
    #
    # -------------------------------------------------------------
    public function getInterfaceSetting($p_interface_setting_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getInterfaceSetting(:p_interface_setting_id)');
        $stmt->bindValue(':p_interface_setting_id', $p_interface_setting_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getInterfaceSettingFileExtension
    # Description: Retrieves the details of a interface setting file extension.
    #
    # Parameters:
    # - $p_interface_setting_id (int): The interface setting ID.
    #
    # Returns:
    # - An array containing the interface setting file extension details.
    #
    # -------------------------------------------------------------
    public function getInterfaceSettingFileExtension($p_interface_setting_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getInterfaceSettingFileExtension(:p_interface_setting_id)');
        $stmt->bindValue(':p_interface_setting_id', $p_interface_setting_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateInterfaceSetting
    # Description: Duplicates the interface setting.
    #
    # Parameters:
    # - $p_interface_setting_id (int): The interface setting ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateInterfaceSetting($p_interface_setting_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateInterfaceSetting(:p_interface_setting_id, :p_last_log_by, @p_new_interface_setting_id)');
        $stmt->bindValue(':p_interface_setting_id', $p_interface_setting_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_interface_setting_id AS interface_setting_id");
        $interfaceSettingID = $result->fetch(PDO::FETCH_ASSOC)['interface_setting_id'];

        return $interfaceSettingID;
    }
    # -------------------------------------------------------------
}
?>