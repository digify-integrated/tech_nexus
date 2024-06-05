<?php
/**
* Class PropertyModel
*
* The PropertyModel class handles property related operations and interactions.
*/
class PropertyModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateProperty
    # Description: Updates the property.
    #
    # Parameters:
    # - $p_property_id (int): The property ID.
    # - $p_property_name (string): The property name.
    # - $p_address (string): The address of the property.
    # - $p_city_id (int): The city ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateProperty($p_property_id, $p_property_name, $p_company_id, $p_address, $p_city_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateProperty(:p_property_id, :p_property_name, :p_company_id, :p_address, :p_city_id, :p_last_log_by)');
        $stmt->bindValue(':p_property_id', $p_property_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_property_name', $p_property_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_address', $p_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_city_id', $p_city_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updatePropertyLogo
    # Description: Updates the property logo.
    #
    # Parameters:
    # - $p_property_id (int): The property ID.
    # - $p_property_logo (string): The property logo.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updatePropertyLogo($p_property_id, $p_property_logo, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePropertyLogo(:p_property_id, :p_property_logo, :p_last_log_by)');
        $stmt->bindValue(':p_property_id', $p_property_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_property_logo', $p_property_logo, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertProperty
    # Description: Inserts the property.
    #
    # Parameters:
    # - $p_property_name (string): The property name.
    # - $p_address (string): The address of the property.
    # - $p_city_id (int): The city ID.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertProperty($p_property_name, $p_company_id, $p_address, $p_city_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertProperty(:p_property_name, :p_company_id, :p_address, :p_city_id, :p_last_log_by, @p_property_id)');
        $stmt->bindValue(':p_property_name', $p_property_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_city_id', $p_city_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_property_id AS p_property_id");
        $p_property_id = $result->fetch(PDO::FETCH_ASSOC)['p_property_id'];

        return $p_property_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkPropertyExist
    # Description: Checks if a property exists.
    #
    # Parameters:
    # - $p_property_id (int): The property ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkPropertyExist($p_property_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPropertyExist(:p_property_id)');
        $stmt->bindValue(':p_property_id', $p_property_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteProperty
    # Description: Deletes the property.
    #
    # Parameters:
    # - $p_property_id (int): The property ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteProperty($p_property_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteProperty(:p_property_id)');
        $stmt->bindValue(':p_property_id', $p_property_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getProperty
    # Description: Retrieves the details of a property.
    #
    # Parameters:
    # - $p_property_id (int): The property ID.
    #
    # Returns:
    # - An array containing the property details.
    #
    # -------------------------------------------------------------
    public function getProperty($p_property_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getProperty(:p_property_id)');
        $stmt->bindValue(':p_property_id', $p_property_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generatePropertyOptions
    # Description: Generates the property options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePropertyOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePropertyOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $propertyID = $row['property_id'];
            $propertyName = $row['property_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($propertyID, ENT_QUOTES) . '">' . htmlspecialchars($propertyName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>