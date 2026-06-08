<?php
/**
* Class InsuranceProviderModel
*
* The InsuranceProviderModel class handles insurance provider related operations and interactions.
*/
class InsuranceProviderModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateInsuranceProvider
    # Description: Updates the insurance provider details.
    #
    # Parameters:
    # - $p_provider_id (int): The provider ID.
    # - $p_provider_name (string): The provider name.
    # - $p_address (string): The address of the provider.
    # - $p_city_id (int): The city ID.
    # - $p_tax_id (string): The professional/regulatory license number.
    # - $p_currency_id (int): The currency ID.
    # - $p_phone (string): The phone of the provider.
    # - $p_mobile (string): The mobile of the provider.
    # - $p_telephone (string): The telephone of the provider.
    # - $p_email (string): The email of the provider.
    # - $p_website (string): The website of the provider.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateInsuranceProvider($p_provider_id, $p_provider_name, $p_address, $p_city_id, $p_tax_id, $p_currency_id, $p_mobile, $p_telephone, $p_email, $p_website, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('
            UPDATE insurance_provider 
            SET provider_name = :p_provider_name, 
                address = :p_address, 
                city_id = :p_city_id, 
                tax_id = :p_tax_id, 
                currency_id = :p_currency_id, 
                mobile = :p_mobile, 
                telephone = :p_telephone, 
                email = :p_email, 
                website = :p_website, 
                last_log_by = :p_last_log_by
            WHERE provider_id = :p_provider_id
        ');
        
        $stmt->bindValue(':p_provider_id', $p_provider_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_provider_name', $p_provider_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_address', $p_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_city_id', $p_city_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_tax_id', $p_tax_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_currency_id', $p_currency_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_mobile', $p_mobile, PDO::PARAM_STR);
        $stmt->bindValue(':p_telephone', $p_telephone, PDO::PARAM_STR);
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
        $stmt->bindValue(':p_website', $p_website, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateInsuranceProviderLogo
    # Description: Updates the brand logo image path for the provider.
    #
    # Parameters:
    # - $p_provider_id (int): The provider ID.
    # - $p_provider_logo (string): The file storage path of the logo.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateInsuranceProviderLogo($p_provider_id, $p_provider_logo, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('
            UPDATE insurance_provider 
            SET provider_logo = :p_provider_logo, 
                last_log_by = :p_last_log_by,
            WHERE provider_id = :p_provider_id
        ');
        
        $stmt->bindValue(':p_provider_id', $p_provider_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_provider_logo', $p_provider_logo, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertInsuranceProvider
    # Description: Inserts a new insurance provider profile and returns the new ID.
    #
    # Parameters:
    # - $p_provider_name (string): The provider name.
    # - $p_address (string): The address of the provider.
    # - $p_city_id (int): The city ID.
    # - $p_tax_id (string): The license number.
    # - $p_currency_id (int): The currency ID.
    # - $p_phone (string): The phone of the provider.
    # - $p_mobile (string): The mobile of the provider.
    # - $p_telephone (string): The telephone of the provider.
    # - $p_email (string): The email of the provider.
    # - $p_website (string): The website of the provider.
    # - $p_last_log_by (int): The logging user context.
    #
    # Returns: String (The newly generated record primary key)
    #
    # -------------------------------------------------------------
    public function insertInsuranceProvider($p_provider_name, $p_address, $p_city_id, $p_tax_id, $p_currency_id, $p_mobile, $p_telephone, $p_email, $p_website, $p_last_log_by) {
        $connection = $this->db->getConnection();
        
        $stmt = $connection->prepare('
            INSERT INTO insurance_provider (provider_name, address, city_id, tax_id, currency_id, mobile, telephone, email, website, last_log_by) 
            VALUES (:p_provider_name, :p_address, :p_city_id, :p_tax_id, :p_currency_id, :p_mobile, :p_telephone, :p_email, :p_website, :p_last_log_by)
        ');
        
        $stmt->bindValue(':p_provider_name', $p_provider_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_address', $p_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_city_id', $p_city_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_tax_id', $p_tax_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_currency_id', $p_currency_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_mobile', $p_mobile, PDO::PARAM_STR);
        $stmt->bindValue(':p_telephone', $p_telephone, PDO::PARAM_STR);
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
        $stmt->bindValue(':p_website', $p_website, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        return $connection->lastInsertId();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkInsuranceProviderExist
    # Description: Checks if an insurance provider profile exists by counting matches.
    #
    # Parameters:
    # - $p_provider_id (int): The provider ID.
    #
    # Returns: The result containing a 'total' count key in an associative array.
    #
    # -------------------------------------------------------------
    public function checkInsuranceProviderExist($p_provider_id) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT COUNT(*) AS total 
            FROM insurance_provider 
            WHERE provider_id = :p_provider_id
        ');
        $stmt->bindValue(':p_provider_id', $p_provider_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteInsuranceProvider
    # Description: Deletes an insurance provider profile completely.
    #
    # Parameters:
    # - $p_provider_id (int): The provider ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteInsuranceProvider($p_provider_id) {
        $stmt = $this->db->getConnection()->prepare('
            DELETE FROM insurance_provider 
            WHERE provider_id = :p_provider_id
        ');
        $stmt->bindValue(':p_provider_id', $p_provider_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getInsuranceProvider
    # Description: Retrieves the details of an insurance provider profile.
    #
    # Parameters:
    # - $p_provider_id (int): The provider ID.
    #
    # Returns:
    # - An array containing the insurance provider profile values.
    #
    # -------------------------------------------------------------
    public function getInsuranceProvider($p_provider_id) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT * FROM insurance_provider 
            WHERE provider_id = :p_provider_id
        ');
        $stmt->bindValue(':p_provider_id', $p_provider_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateInsuranceProvider
    # Description: Duplicates a target provider profile config onto a fresh record.
    #
    # Parameters:
    # - $p_provider_id (int): The reference source provider ID.
    # - $p_last_log_by (int): The tracking user tracking identity context.
    #
    # Returns: string (The new record primary key)
    #
    # -------------------------------------------------------------
    public function duplicateInsuranceProvider($p_provider_id, $p_last_log_by) {
        $connection = $this->db->getConnection();
        
        // Read the prototype details first
        $source = $this->getInsuranceProvider($p_provider_id);
        
        if (!$source) {
            return null;
        }

        // Insert copy data explicitly (Appending an indicator mark to names if needed, though clean copy matching the SP target is here)
        $stmt = $connection->prepare('
            INSERT INTO insurance_provider (provider_name, address, city_id, tax_id, currency_id, mobile, telephone, email, website, provider_logo, last_log_by) 
            VALUES (:p_provider_name, :p_address, :p_city_id, :p_tax_id, :p_currency_id, :p_mobile, :p_telephone, :p_email, :p_website, :p_provider_logo, :p_last_log_by)
        ');
        
        $stmt->bindValue(':p_provider_name', $source['provider_name'] . ' (Copy)', PDO::PARAM_STR);
        $stmt->bindValue(':p_address', $source['address'], PDO::PARAM_STR);
        $stmt->bindValue(':p_city_id', $source['city_id'], PDO::PARAM_INT);
        $stmt->bindValue(':p_tax_id', $source['tax_id'], PDO::PARAM_STR);
        $stmt->bindValue(':p_currency_id', $source['currency_id'], PDO::PARAM_INT);
        $stmt->bindValue(':p_mobile', $source['mobile'], PDO::PARAM_STR);
        $stmt->bindValue(':p_telephone', $source['telephone'], PDO::PARAM_STR);
        $stmt->bindValue(':p_email', $source['email'], PDO::PARAM_STR);
        $stmt->bindValue(':p_website', $source['website'], PDO::PARAM_STR);
        $stmt->bindValue(':p_provider_logo', $source['provider_logo'], PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        return $connection->lastInsertId();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate UI element template methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateInsuranceProviderOptions
    # Description: Generates safe HTML option collection components.
    #
    # Parameters: None
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function generateInsuranceProviderOptions() {
        $stmt = $this->db->getConnection()->prepare('
            SELECT provider_id, provider_name 
            FROM insurance_provider 
            ORDER BY provider_name ASC
        ');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $providerID = $row['provider_id'];
            $providerName = $row['provider_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($providerID, ENT_QUOTES) . '">' . htmlspecialchars($providerName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateInsuranceProviderCheckBox
    # Description: Generates standard filter input checkboxes for safe programmatic views.
    #
    # Parameters: None
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function generateInsuranceProviderCheckBox() {
        $stmt = $this->db->getConnection()->prepare('
            SELECT provider_id, provider_name 
            FROM insurance_provider 
            ORDER BY provider_name ASC
        ');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $providerID = $row['provider_id'];
            $providerName = $row['provider_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input provider-filter" type="checkbox" id="provider-' . htmlspecialchars($providerID, ENT_QUOTES) . '" value="' . htmlspecialchars($providerID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="provider-' . htmlspecialchars($providerID, ENT_QUOTES) . '">' . htmlspecialchars($providerName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>