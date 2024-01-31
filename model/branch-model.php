<?php
/**
* Class BranchModel
*
* The BranchModel class handles branch related operations and interactions.
*/
class BranchModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateBranch
    # Description: Updates the branch.
    #
    # Parameters:
    # - $p_branch_id (int): The branch ID.
    # - $p_branch_name (string): The branch name.
    # - $p_address (string): The address of the branch.
    # - $p_city_id (int): The city ID.
    # - $p_company_id (int): The company ID.
    # - $p_phone (string): The phone of the branch.
    # - $p_mobile (string): The mobile of the branch.
    # - $p_telephone (string): The telephone of the branch.
    # - $p_email (string): The email of the branch.
    # - $p_website (string): The website of the branch.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateBranch($p_branch_id, $p_branch_name, $p_address, $p_city_id, $p_company_id, $p_phone, $p_mobile, $p_telephone, $p_email, $p_website, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBranch(:p_branch_id, :p_branch_name, :p_address, :p_city_id, :p_company_id, :p_phone, :p_mobile, :p_telephone, :p_email, :p_website, :p_last_log_by)');
        $stmt->bindValue(':p_branch_id', $p_branch_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_branch_name', $p_branch_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_address', $p_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_city_id', $p_city_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
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
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertBranch
    # Description: Inserts the branch.
    #
    # Parameters:
    # - $p_branch_name (string): The branch name.
    # - $p_address (string): The address of the branch.
    # - $p_city_id (int): The city ID.
    # - $p_company_id (int): The company ID.
    # - $p_phone (string): The phone of the branch.
    # - $p_mobile (string): The mobile of the branch.
    # - $p_telephone (string): The telephone of the branch.
    # - $p_email (string): The email of the branch.
    # - $p_website (string): The website of the branch.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertBranch($p_branch_name, $p_address, $p_city_id, $p_company_id, $p_phone, $p_mobile, $p_telephone, $p_email, $p_website, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertBranch(:p_branch_name, :p_address, :p_city_id, :p_company_id, :p_phone, :p_mobile, :p_telephone, :p_email, :p_website, :p_last_log_by, @p_branch_id)');
        $stmt->bindValue(':p_branch_name', $p_branch_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_address', $p_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_city_id', $p_city_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_phone', $p_phone, PDO::PARAM_STR);
        $stmt->bindValue(':p_mobile', $p_mobile, PDO::PARAM_STR);
        $stmt->bindValue(':p_telephone', $p_telephone, PDO::PARAM_STR);
        $stmt->bindValue(':p_email', $p_email, PDO::PARAM_STR);
        $stmt->bindValue(':p_website', $p_website, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_branch_id AS p_branch_id");
        $p_branch_id = $result->fetch(PDO::FETCH_ASSOC)['p_branch_id'];

        return $p_branch_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkBranchExist
    # Description: Checks if a branch exists.
    #
    # Parameters:
    # - $p_branch_id (int): The branch ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkBranchExist($p_branch_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkBranchExist(:p_branch_id)');
        $stmt->bindValue(':p_branch_id', $p_branch_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBranch
    # Description: Deletes the branch.
    #
    # Parameters:
    # - $p_branch_id (int): The branch ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteBranch($p_branch_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteBranch(:p_branch_id)');
        $stmt->bindValue(':p_branch_id', $p_branch_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBranch
    # Description: Retrieves the details of a branch.
    #
    # Parameters:
    # - $p_branch_id (int): The branch ID.
    #
    # Returns:
    # - An array containing the branch details.
    #
    # -------------------------------------------------------------
    public function getBranch($p_branch_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getBranch(:p_branch_id)');
        $stmt->bindValue(':p_branch_id', $p_branch_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateBranch
    # Description: Duplicates the branch.
    #
    # Parameters:
    # - $p_branch_id (int): The branch ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateBranch($p_branch_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateBranch(:p_branch_id, :p_last_log_by, @p_new_branch_id)');
        $stmt->bindValue(':p_branch_id', $p_branch_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_branch_id AS branch_id");
        $systemActionID = $result->fetch(PDO::FETCH_ASSOC)['branch_id'];

        return $systemActionID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateBranchOptions
    # Description: Generates the branch options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateBranchOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateBranchOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $branchID = $row['branch_id'];
            $branchName = $row['branch_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($branchID, ENT_QUOTES) . '">' . htmlspecialchars($branchName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateBranchCheckBox
    # Description: Generates the branch check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateBranchCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateBranchOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $branchID = $row['branch_id'];
            $branchName = $row['branch_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input branch-filter" type="checkbox" id="branch-' . htmlspecialchars($branchID, ENT_QUOTES) . '" value="' . htmlspecialchars($branchID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="branch-' . htmlspecialchars($branchID, ENT_QUOTES) . '">' . htmlspecialchars($branchName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>