<?php
/**
* Class JobPositionModel
*
* The JobPositionModel class handles job position related operations and interactions.
*/
class JobPositionModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateJobPosition
    # Description: Updates the job position.
    #
    # Parameters:
    # - $p_job_position_id (int): The job position ID.
    # - $p_job_position_name (string): The job position name.
    # - $p_job_position_description (string): The job position description.
    # - $p_department_id (int): The department linked to the job position.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateJobPosition($p_job_position_id, $p_job_position_name, $p_job_position_description, $p_department_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateJobPosition(:p_job_position_id, :p_job_position_name, :p_job_position_description, :p_department_id, :p_last_log_by)');
        $stmt->bindValue(':p_job_position_id', $p_job_position_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_job_position_name', $p_job_position_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_job_position_description', $p_job_position_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_department_id', $p_department_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateJobPositionRecruitmentStatus
    # Description: Updates the job position recruitment status.
    #
    # Parameters:
    # - $p_job_position_id (int): The job position ID.
    # - $p_recruitment_status (int): The recruitment status.
    # - $p_expected_new_employees (int): The expected new employees.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateJobPositionRecruitmentStatus($p_job_position_id, $p_recruitment_status, $p_expected_new_employees, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateJobPositionRecruitmentStatus(:p_job_position_id, :p_recruitment_status, :p_expected_new_employees, :p_last_log_by)');
        $stmt->bindValue(':p_job_position_id', $p_job_position_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_recruitment_status', $p_recruitment_status, PDO::PARAM_INT);
        $stmt->bindValue(':p_expected_new_employees', $p_expected_new_employees, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateJobPositionResponsibility
    # Description: Updates the job position responsibility.
    #
    # Parameters:
    # - $p_job_position_responsibility_id (int): The job position responsibility ID.
    # - $p_responsibility (string): The job position responsibility.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateJobPositionResponsibility($p_job_position_responsibility_id, $p_responsibility, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateJobPositionResponsibility(:p_job_position_responsibility_id, :p_responsibility, :p_last_log_by)');
        $stmt->bindValue(':p_job_position_responsibility_id', $p_job_position_responsibility_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_responsibility', $p_responsibility, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateJobPositionRequirement
    # Description: Updates the job position requirement.
    #
    # Parameters:
    # - $p_job_position_requirement_id (int): The job position requirement ID.
    # - $p_requirement (string): The job position requirement.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateJobPositionRequirement($p_job_position_requirement_id, $p_requirement, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateJobPositionRequirement(:p_job_position_requirement_id, :p_requirement, :p_last_log_by)');
        $stmt->bindValue(':p_job_position_requirement_id', $p_job_position_requirement_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_requirement', $p_requirement, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateJobPositionQualification
    # Description: Updates the job position qualification.
    #
    # Parameters:
    # - $p_job_position_qualification_id (int): The job position qualification ID.
    # - $p_qualification (string): The job position qualification.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateJobPositionQualification($p_job_position_qualification_id, $p_qualification, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateJobPositionQualification(:p_job_position_qualification_id, :p_qualification, :p_last_log_by)');
        $stmt->bindValue(':p_job_position_qualification_id', $p_job_position_qualification_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_qualification', $p_qualification, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertJobPosition
    # Description: Inserts the job position.
    #
    # Parameters:
    # - $p_job_position_name (string): The job position name.
    # - $p_job_position_description (string): The job position description.
    # - $p_department_id (int): The department linked to the job position.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertJobPosition($p_job_position_name, $p_job_position_description, $p_department_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertJobPosition(:p_job_position_name, :p_job_position_description, :p_department_id, :p_last_log_by, @p_job_position_id)');
        $stmt->bindValue(':p_job_position_name', $p_job_position_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_job_position_description', $p_job_position_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_department_id', $p_department_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_job_position_id AS p_job_position_id");
        $p_job_position_id = $result->fetch(PDO::FETCH_ASSOC)['p_job_position_id'];

        return $p_job_position_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertJobPositionResponsibility
    # Description: Inserts the job position responsibility.
    #
    # Parameters:
    # - $p_job_position_id (int): The job position ID.
    # - $p_responsibility (string): The job position responsibility.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertJobPositionResponsibility($p_job_position_id, $p_responsibility, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertJobPositionResponsibility(:p_job_position_id, :p_responsibility, :p_last_log_by)');
        $stmt->bindValue(':p_job_position_id', $p_job_position_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_responsibility', $p_responsibility, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertJobPositionRequirement
    # Description: Inserts the job position requirement.
    #
    # Parameters:
    # - $p_job_position_id (int): The job position ID.
    # - $p_requirement (string): The job position requirement.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertJobPositionRequirement($p_job_position_id, $p_requirement, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertJobPositionRequirement(:p_job_position_id, :p_requirement, :p_last_log_by)');
        $stmt->bindValue(':p_job_position_id', $p_job_position_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_requirement', $p_requirement, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertJobPositionQualification
    # Description: Inserts the job position qualification.
    #
    # Parameters:
    # - $p_job_position_id (int): The job position ID.
    # - $p_qualification (string): The job position qualification.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertJobPositionQualification($p_job_position_id, $p_qualification, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertJobPositionQualification(:p_job_position_id, :p_qualification, :p_last_log_by)');
        $stmt->bindValue(':p_job_position_id', $p_job_position_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_qualification', $p_qualification, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkJobPositionExist
    # Description: Checks if a job position exists.
    #
    # Parameters:
    # - $p_job_position_id (int): The job position ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkJobPositionExist($p_job_position_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkJobPositionExist(:p_job_position_id)');
        $stmt->bindValue(':p_job_position_id', $p_job_position_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkJobPositionResponsibilityExist
    # Description: Checks if a job position responsibility exists.
    #
    # Parameters:
    # - $p_job_position_responsibility_id (int): The job position responsibility ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkJobPositionResponsibilityExist($p_job_position_responsibility_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkJobPositionResponsibilityExist(:p_job_position_responsibility_id)');
        $stmt->bindValue(':p_job_position_responsibility_id', $p_job_position_responsibility_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkJobPositionRequirementExist
    # Description: Checks if a job position requirement exists.
    #
    # Parameters:
    # - $p_job_position_requirement_id (int): The job position requirement ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkJobPositionRequirementExist($p_job_position_requirement_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkJobPositionRequirementExist(:p_job_position_requirement_id)');
        $stmt->bindValue(':p_job_position_requirement_id', $p_job_position_requirement_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkJobPositionQualificationExist
    # Description: Checks if a job position qualification exists.
    #
    # Parameters:
    # - $p_job_position_qualification_id (int): The job position qualification ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkJobPositionQualificationExist($p_job_position_qualification_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkJobPositionQualificationExist(:p_job_position_qualification_id)');
        $stmt->bindValue(':p_job_position_qualification_id', $p_job_position_qualification_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteJobPosition
    # Description: Deletes the job position.
    #
    # Parameters:
    # - $p_job_position_id (int): The job position ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteJobPosition($p_job_position_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteJobPosition(:p_job_position_id)');
        $stmt->bindValue(':p_job_position_id', $p_job_position_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteJobPositionResponsibility
    # Description: Deletes the job position responsibility.
    #
    # Parameters:
    # - $p_job_position_responsibility_id (int): The job position responsibility ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteJobPositionResponsibility($p_job_position_responsibility_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteJobPositionResponsibility(:p_job_position_responsibility_id)');
        $stmt->bindValue(':p_job_position_responsibility_id', $p_job_position_responsibility_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteJobPositionRequirement
    # Description: Deletes the job position requirement.
    #
    # Parameters:
    # - $p_job_position_requirement_id (int): The job position requirement ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteJobPositionRequirement($p_job_position_requirement_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteJobPositionRequirement(:p_job_position_requirement_id)');
        $stmt->bindValue(':p_job_position_requirement_id', $p_job_position_requirement_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteJobPositionQualification
    # Description: Deletes the job position qualification.
    #
    # Parameters:
    # - $p_job_position_qualification_id (int): The job position qualification ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteJobPositionQualification($p_job_position_qualification_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteJobPositionQualification(:p_job_position_qualification_id)');
        $stmt->bindValue(':p_job_position_qualification_id', $p_job_position_qualification_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getJobPosition
    # Description: Retrieves the details of a job position.
    #
    # Parameters:
    # - $p_job_position_id (int): The job position ID.
    #
    # Returns:
    # - An array containing the job position details.
    #
    # -------------------------------------------------------------
    public function getJobPosition($p_job_position_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getJobPosition(:p_job_position_id)');
        $stmt->bindValue(':p_job_position_id', $p_job_position_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getJobPositionResponsibility
    # Description: Retrieves the details of a job position responsibility.
    #
    # Parameters:
    # - $p_job_position_responsibility_id (int): The job position responsibility ID.
    #
    # Returns:
    # - An array containing the job position details.
    #
    # -------------------------------------------------------------
    public function getJobPositionResponsibility($p_job_position_responsibility_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getJobPositionResponsibility(:p_job_position_responsibility_id)');
        $stmt->bindValue(':p_job_position_responsibility_id', $p_job_position_responsibility_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLinkedJobPositionResponsibility
    # Description: Retrieves the details of a job position responsibility linked to job position.
    #
    # Parameters:
    # - $p_job_position_id (int): The job position responsibility ID.
    #
    # Returns:
    # - An array containing the job position details.
    #
    # -------------------------------------------------------------
    public function getLinkedJobPositionResponsibility($p_job_position_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getLinkedJobPositionResponsibility(:p_job_position_id)');
        $stmt->bindValue(':p_job_position_id', $p_job_position_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getJobPositionRequirement
    # Description: Retrieves the details of a job position requirement.
    #
    # Parameters:
    # - $p_job_position_requirement_id (int): The job position requirement ID.
    #
    # Returns:
    # - An array containing the job position details.
    #
    # -------------------------------------------------------------
    public function getJobPositionRequirement($p_job_position_requirement_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getJobPositionRequirement(:p_job_position_requirement_id)');
        $stmt->bindValue(':p_job_position_requirement_id', $p_job_position_requirement_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLinkedJobPositionRequirement
    # Description: Retrieves the details of a job position requirement linked to job position.
    #
    # Parameters:
    # - $p_job_position_id (int): The job position requirement ID.
    #
    # Returns:
    # - An array containing the job position details.
    #
    # -------------------------------------------------------------
    public function getLinkedJobPositionRequirement($p_job_position_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getLinkedJobPositionRequirement(:p_job_position_id)');
        $stmt->bindValue(':p_job_position_id', $p_job_position_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getJobPositionQualification
    # Description: Retrieves the details of a job position qualification.
    #
    # Parameters:
    # - $p_job_position_qualification_id (int): The job position qualification ID.
    #
    # Returns:
    # - An array containing the job position details.
    #
    # -------------------------------------------------------------
    public function getJobPositionQualification($p_job_position_qualification_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getJobPositionQualification(:p_job_position_qualification_id)');
        $stmt->bindValue(':p_job_position_qualification_id', $p_job_position_qualification_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLinkedJobPositionQualification
    # Description: Retrieves the details of a job position qualification linked to job position.
    #
    # Parameters:
    # - $p_job_position_id (int): The job position qualification ID.
    #
    # Returns:
    # - An array containing the job position details.
    #
    # -------------------------------------------------------------
    public function getLinkedJobPositionQualification($p_job_position_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getLinkedJobPositionQualification(:p_job_position_id)');
        $stmt->bindValue(':p_job_position_id', $p_job_position_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateJobPosition
    # Description: Duplicates the job position.
    #
    # Parameters:
    # - $p_job_position_id (int): The job position ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateJobPosition($p_job_position_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateJobPosition(:p_job_position_id, :p_last_log_by, @p_new_job_position_id)');
        $stmt->bindValue(':p_job_position_id', $p_job_position_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_job_position_id AS job_position_id");
        $systemActionID = $result->fetch(PDO::FETCH_ASSOC)['job_position_id'];

        return $systemActionID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateJobPositionOptions
    # Description: Generates the job position options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateJobPositionOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateJobPositionOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $jobPositionID = $row['job_position_id'];
            $jobPositionName = $row['job_position_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($jobPositionID, ENT_QUOTES) . '">' . htmlspecialchars($jobPositionName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>