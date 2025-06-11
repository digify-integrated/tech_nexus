<?php
/**
* Class BusinessPremisesModel
*
* The BusinessPremisesModel class handles business premises related operations and interactions.
*/
class BusinessPremisesModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateBusinessPremises
    # Description: Updates the business premises.
    #
    # Parameters:
    # - $p_business_premises_id (int): The business premises ID.
    # - $p_business_premises_name (string): The business premises name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateBusinessPremises($p_business_premises_id, $p_business_premises_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBusinessPremises(:p_business_premises_id, :p_business_premises_name, :p_last_log_by)');
        $stmt->bindValue(':p_business_premises_id', $p_business_premises_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_business_premises_name', $p_business_premises_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertBusinessPremises
    # Description: Inserts the business premises.
    #
    # Parameters:
    # - $p_business_premises_name (string): The business premises name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertBusinessPremises($p_business_premises_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertBusinessPremises(:p_business_premises_name, :p_last_log_by, @p_business_premises_id)');
        $stmt->bindValue(':p_business_premises_name', $p_business_premises_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_business_premises_id AS p_business_premises_id");
        $p_business_premises_id = $result->fetch(PDO::FETCH_ASSOC)['p_business_premises_id'];

        return $p_business_premises_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkBusinessPremisesExist
    # Description: Checks if a business premises exists.
    #
    # Parameters:
    # - $p_business_premises_id (int): The business premises ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkBusinessPremisesExist($p_business_premises_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkBusinessPremisesExist(:p_business_premises_id)');
        $stmt->bindValue(':p_business_premises_id', $p_business_premises_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBusinessPremises
    # Description: Deletes the business premises.
    #
    # Parameters:
    # - $p_business_premises_id (int): The business premises ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteBusinessPremises($p_business_premises_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteBusinessPremises(:p_business_premises_id)');
        $stmt->bindValue(':p_business_premises_id', $p_business_premises_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBusinessPremises
    # Description: Retrieves the details of a business premises.
    #
    # Parameters:
    # - $p_business_premises_id (int): The business premises ID.
    #
    # Returns:
    # - An array containing the business premises details.
    #
    # -------------------------------------------------------------
    public function getBusinessPremises($p_business_premises_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getBusinessPremises(:p_business_premises_id)');
        $stmt->bindValue(':p_business_premises_id', $p_business_premises_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateBusinessPremises
    # Description: Duplicates the business premises.
    #
    # Parameters:
    # - $p_business_premises_id (int): The business premises ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateBusinessPremises($p_business_premises_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateBusinessPremises(:p_business_premises_id, :p_last_log_by, @p_new_business_premises_id)');
        $stmt->bindValue(':p_business_premises_id', $p_business_premises_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_business_premises_id AS business_premises_id");
        $businessPremisesID = $result->fetch(PDO::FETCH_ASSOC)['business_premises_id'];

        return $businessPremisesID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateBusinessPremisesOptions
    # Description: Generates the business premises options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateBusinessPremisesOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateBusinessPremisesOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $businessPremisesID = $row['business_premises_id'];
            $businessPremisesName = $row['business_premises_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($businessPremisesID, ENT_QUOTES) . '">' . htmlspecialchars($businessPremisesName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>