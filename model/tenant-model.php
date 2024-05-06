<?php
/**
* Class TenantModel
*
* The TenantModel class handles tenant related operations and interactions.
*/
class TenantModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateTenant
    # Description: Updates the tenant.
    #
    # Parameters:
    # - $p_tenant_id (int): The tenant ID.
    # - $p_tenant_name (string): The tenant name.
    # - $p_address (string): The address of the tenant.
    # - $p_city_id (int): The city ID.
    # - $p_phone (string): The phone of the tenant.
    # - $p_mobile (string): The mobile of the tenant.
    # - $p_telephone (string): The telephone of the tenant.
    # - $p_email (string): The email of the tenant.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateTenant($p_tenant_id, $p_contact_person, $p_tenant_name, $p_address, $p_city_id, $p_phone, $p_mobile, $p_telephone, $p_email, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateTenant(:p_tenant_id, :p_contact_person, :p_tenant_name, :p_address, :p_city_id, :p_phone, :p_mobile, :p_telephone, :p_email, :p_last_log_by)');
        $stmt->bindValue(':p_tenant_id', $p_tenant_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_tenant_name', $p_tenant_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_contact_person', $p_contact_person, PDO::PARAM_STR);
        $stmt->bindValue(':p_address', $p_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_city_id', $p_city_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_phone', $p_phone, PDO::PARAM_STR);
        $stmt->bindValue(':p_mobile', $p_mobile, PDO::PARAM_STR);
        $stmt->bindValue(':p_telephone', $p_telephone, PDO::PARAM_STR);
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateTenantLogo
    # Description: Updates the tenant logo.
    #
    # Parameters:
    # - $p_tenant_id (int): The tenant ID.
    # - $p_tenant_logo (string): The tenant logo.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateTenantLogo($p_tenant_id, $p_tenant_logo, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateTenantLogo(:p_tenant_id, :p_tenant_logo, :p_last_log_by)');
        $stmt->bindValue(':p_tenant_id', $p_tenant_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_tenant_logo', $p_tenant_logo, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertTenant
    # Description: Inserts the tenant.
    #
    # Parameters:
    # - $p_tenant_name (string): The tenant name.
    # - $p_address (string): The address of the tenant.
    # - $p_city_id (int): The city ID.
    # - $p_phone (string): The phone of the tenant.
    # - $p_mobile (string): The mobile of the tenant.
    # - $p_telephone (string): The telephone of the tenant.
    # - $p_email (string): The email of the tenant.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertTenant($p_tenant_name, $p_contact_person, $p_address, $p_city_id, $p_phone, $p_mobile, $p_telephone, $p_email, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertTenant(:p_tenant_name, :p_contact_person, :p_address, :p_city_id, :p_phone, :p_mobile, :p_telephone, :p_email, :p_last_log_by, @p_tenant_id)');
        $stmt->bindValue(':p_tenant_name', $p_tenant_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_contact_person', $p_contact_person, PDO::PARAM_STR);
        $stmt->bindValue(':p_address', $p_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_city_id', $p_city_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_phone', $p_phone, PDO::PARAM_STR);
        $stmt->bindValue(':p_mobile', $p_mobile, PDO::PARAM_STR);
        $stmt->bindValue(':p_telephone', $p_telephone, PDO::PARAM_STR);
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_tenant_id AS p_tenant_id");
        $p_tenant_id = $result->fetch(PDO::FETCH_ASSOC)['p_tenant_id'];

        return $p_tenant_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkTenantExist
    # Description: Checks if a tenant exists.
    #
    # Parameters:
    # - $p_tenant_id (int): The tenant ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkTenantExist($p_tenant_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkTenantExist(:p_tenant_id)');
        $stmt->bindValue(':p_tenant_id', $p_tenant_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteTenant
    # Description: Deletes the tenant.
    #
    # Parameters:
    # - $p_tenant_id (int): The tenant ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteTenant($p_tenant_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteTenant(:p_tenant_id)');
        $stmt->bindValue(':p_tenant_id', $p_tenant_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getTenant
    # Description: Retrieves the details of a tenant.
    #
    # Parameters:
    # - $p_tenant_id (int): The tenant ID.
    #
    # Returns:
    # - An array containing the tenant details.
    #
    # -------------------------------------------------------------
    public function getTenant($p_tenant_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getTenant(:p_tenant_id)');
        $stmt->bindValue(':p_tenant_id', $p_tenant_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateTenantOptions
    # Description: Generates the tenant options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateTenantOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateTenantOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $tenantID = $row['tenant_id'];
            $tenantName = $row['tenant_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($tenantID, ENT_QUOTES) . '">' . htmlspecialchars($tenantName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>