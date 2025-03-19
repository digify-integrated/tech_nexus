<?php
/**
* Class ContractorModel
*
* The ContractorModel class handles contractor related operations and interactions.
*/
class ContractorModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContractor
    # Description: Updates the contractor.
    #
    # Parameters:
    # - $p_contractor_id (int): The contractor ID.
    # - $p_contractor_name (string): The contractor name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContractor($p_contractor_id, $p_contractor_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContractor(:p_contractor_id, :p_contractor_name, :p_last_log_by)');
        $stmt->bindValue(':p_contractor_id', $p_contractor_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contractor_name', $p_contractor_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertContractor
    # Description: Inserts the contractor.
    #
    # Parameters:
    # - $p_contractor_name (string): The contractor name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertContractor($p_contractor_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertContractor(:p_contractor_name, :p_last_log_by, @p_contractor_id)');
        $stmt->bindValue(':p_contractor_name', $p_contractor_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_contractor_id AS p_contractor_id");
        $p_contractor_id = $result->fetch(PDO::FETCH_ASSOC)['p_contractor_id'];

        return $p_contractor_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkContractorExist
    # Description: Checks if a contractor exists.
    #
    # Parameters:
    # - $p_contractor_id (int): The contractor ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkContractorExist($p_contractor_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkContractorExist(:p_contractor_id)');
        $stmt->bindValue(':p_contractor_id', $p_contractor_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContractor
    # Description: Deletes the contractor.
    #
    # Parameters:
    # - $p_contractor_id (int): The contractor ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteContractor($p_contractor_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteContractor(:p_contractor_id)');
        $stmt->bindValue(':p_contractor_id', $p_contractor_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContractor
    # Description: Retrieves the details of a contractor.
    #
    # Parameters:
    # - $p_contractor_id (int): The contractor ID.
    #
    # Returns:
    # - An array containing the contractor details.
    #
    # -------------------------------------------------------------
    public function getContractor($p_contractor_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getContractor(:p_contractor_id)');
        $stmt->bindValue(':p_contractor_id', $p_contractor_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateContractor
    # Description: Duplicates the contractor.
    #
    # Parameters:
    # - $p_contractor_id (int): The contractor ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateContractor($p_contractor_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateContractor(:p_contractor_id, :p_last_log_by, @p_new_contractor_id)');
        $stmt->bindValue(':p_contractor_id', $p_contractor_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_contractor_id AS contractor_id");
        $contractoriD = $result->fetch(PDO::FETCH_ASSOC)['contractor_id'];

        return $contractoriD;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateContractorOptions
    # Description: Generates the contractor options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateContractorOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateContractorOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $contractorID = $row['contractor_id'];
            $contractorName = $row['contractor_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($contractorID, ENT_QUOTES) . '">' . htmlspecialchars($contractorName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateContractorCheckBox
    # Description: Generates the contractor check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateContractorCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateContractorOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $contractorID = $row['contractor_id'];
            $contractorName = $row['contractor_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input contractor-filter" type="checkbox" id="contractor-' . htmlspecialchars($contractorID, ENT_QUOTES) . '" value="' . htmlspecialchars($contractorID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="contractor-' . htmlspecialchars($contractorID, ENT_QUOTES) . '">' . htmlspecialchars($contractorName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>