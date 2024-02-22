<?php
/**
* Class ApprovingOfficerModel
*
* The ApprovingOfficerModel class handles approving officer related operations and interactions.
*/
class ApprovingOfficerModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertApprovingOfficer
    # Description: Inserts the approving officer.
    #
    # Parameters:
    # - $p_contact_id (int): The contact ID.
    # - $p_approving_officer_type (string): The approving officer type.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertApprovingOfficer($p_contact_id, $p_approving_officer_type, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertApprovingOfficer(:p_contact_id, :p_approving_officer_type, :p_last_log_by, @p_approving_officer_id)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_approving_officer_type', $p_approving_officer_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_approving_officer_id AS p_approving_officer_id");
        $p_approving_officer_id = $result->fetch(PDO::FETCH_ASSOC)['p_approving_officer_id'];

        return $p_approving_officer_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkApprovingOfficerExist
    # Description: Checks if a approving officer exists.
    #
    # Parameters:
    # - $p_approving_officer_id (int): The approving officer ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkApprovingOfficerExist($p_approving_officer_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkApprovingOfficerExist(:p_approving_officer_id)');
        $stmt->bindValue(':p_approving_officer_id', $p_approving_officer_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteApprovingOfficer
    # Description: Deletes the approving officer.
    #
    # Parameters:
    # - $p_approving_officer_id (int): The approving officer ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteApprovingOfficer($p_approving_officer_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteApprovingOfficer(:p_approving_officer_id)');
        $stmt->bindValue(':p_approving_officer_id', $p_approving_officer_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getApprovingOfficer
    # Description: Retrieves the details of a approving officer.
    #
    # Parameters:
    # - $p_approving_officer_id (int): The approving officer ID.
    #
    # Returns:
    # - An array containing the approving officer details.
    #
    # -------------------------------------------------------------
    public function getApprovingOfficer($p_approving_officer_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getApprovingOfficer(:p_approving_officer_id)');
        $stmt->bindValue(':p_approving_officer_id', $p_approving_officer_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateApprovingOfficerOptions
    # Description: Generates the approving officer options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateApprovingOfficerOptions($p_approving_officer_type) {
        $stmt = $this->db->getConnection()->prepare('CALL generateApprovingOfficerOptions(:p_approving_officer_type)');
        $stmt->bindValue(':p_approving_officer_type', $p_approving_officer_type, PDO::PARAM_STR);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $contactID = $row['contact_id'];
            $fileAs = $row['file_as'];

            $htmlOptions .= '<option value="' . htmlspecialchars($contactID, ENT_QUOTES) . '">' . htmlspecialchars($fileAs, ENT_QUOTES) . '</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>