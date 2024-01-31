<?php
/**
* Class CompanyModel
*
* The CompanyModel class handles company related operations and interactions.
*/
class CompanyModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateCompany
    # Description: Updates the company.
    #
    # Parameters:
    # - $p_company_id (int): The company ID.
    # - $p_company_name (string): The company name.
    # - $p_address (string): The address of the company.
    # - $p_city_id (int): The city ID.
    # - $p_tax_id (string): The tax ID.
    # - $p_currency_id (int): The currency ID.
    # - $p_phone (string): The phone of the company.
    # - $p_mobile (string): The mobile of the company.
    # - $p_telephone (string): The telephone of the company.
    # - $p_email (string): The email of the company.
    # - $p_website (string): The website of the company.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateCompany($p_company_id, $p_company_name, $p_address, $p_city_id, $p_tax_id, $p_currency_id, $p_phone, $p_mobile, $p_telephone, $p_email, $p_website, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCompany(:p_company_id, :p_company_name, :p_address, :p_city_id, :p_tax_id, :p_currency_id, :p_phone, :p_mobile, :p_telephone, :p_email, :p_website, :p_last_log_by)');
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_company_name', $p_company_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_address', $p_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_city_id', $p_city_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_tax_id', $p_tax_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_currency_id', $p_currency_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_phone', $p_phone, PDO::PARAM_STR);
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
    # Function: updateCompanyLogo
    # Description: Updates the company logo.
    #
    # Parameters:
    # - $p_company_id (int): The company ID.
    # - $p_company_logo (string): The company logo.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateCompanyLogo($p_company_id, $p_company_logo, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCompanyLogo(:p_company_id, :p_company_logo, :p_last_log_by)');
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_company_logo', $p_company_logo, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertCompany
    # Description: Inserts the company.
    #
    # Parameters:
    # - $p_company_name (string): The company name.
    # - $p_address (string): The address of the company.
    # - $p_city_id (int): The city ID.
    # - $p_tax_id (string): The tax ID.
    # - $p_currency_id (int): The currency ID.
    # - $p_phone (string): The phone of the company.
    # - $p_mobile (string): The mobile of the company.
    # - $p_telephone (string): The telephone of the company.
    # - $p_email (string): The email of the company.
    # - $p_website (string): The website of the company.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertCompany($p_company_name, $p_address, $p_city_id, $p_tax_id, $p_currency_id, $p_phone, $p_mobile, $p_telephone, $p_email, $p_website, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertCompany(:p_company_name, :p_address, :p_city_id, :p_tax_id, :p_currency_id, :p_phone, :p_mobile, :p_telephone, :p_email, :p_website, :p_last_log_by, @p_company_id)');
        $stmt->bindValue(':p_company_name', $p_company_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_address', $p_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_city_id', $p_city_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_tax_id', $p_tax_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_currency_id', $p_currency_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_phone', $p_phone, PDO::PARAM_STR);
        $stmt->bindValue(':p_mobile', $p_mobile, PDO::PARAM_STR);
        $stmt->bindValue(':p_telephone', $p_telephone, PDO::PARAM_STR);
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
        $stmt->bindValue(':p_website', $p_website, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_company_id AS p_company_id");
        $p_company_id = $result->fetch(PDO::FETCH_ASSOC)['p_company_id'];

        return $p_company_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkCompanyExist
    # Description: Checks if a company exists.
    #
    # Parameters:
    # - $p_company_id (int): The company ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkCompanyExist($p_company_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkCompanyExist(:p_company_id)');
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteCompany
    # Description: Deletes the company.
    #
    # Parameters:
    # - $p_company_id (int): The company ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteCompany($p_company_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCompany(:p_company_id)');
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getCompany
    # Description: Retrieves the details of a company.
    #
    # Parameters:
    # - $p_company_id (int): The company ID.
    #
    # Returns:
    # - An array containing the company details.
    #
    # -------------------------------------------------------------
    public function getCompany($p_company_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCompany(:p_company_id)');
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateCompany
    # Description: Duplicates the company.
    #
    # Parameters:
    # - $p_company_id (int): The company ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateCompany($p_company_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateCompany(:p_company_id, :p_last_log_by, @p_new_company_id)');
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_company_id AS company_id");
        $systemActionID = $result->fetch(PDO::FETCH_ASSOC)['company_id'];

        return $systemActionID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateCompanyOptions
    # Description: Generates the company options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateCompanyOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateCompanyOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $companyID = $row['company_id'];
            $companyName = $row['company_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($companyID, ENT_QUOTES) . '">' . htmlspecialchars($companyName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateCompanyOptions
    # Description: Generates the company options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateCompanyCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateCompanyOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $companyID = $row['company_id'];
            $companyName = $row['company_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input company-filter" type="checkbox" id="company-' . htmlspecialchars($companyID, ENT_QUOTES) . '" value="' . htmlspecialchars($companyID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="company-' . htmlspecialchars($companyID, ENT_QUOTES) . '">' . htmlspecialchars($companyName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>