<?php
/**
* Class JobLevelModel
*
* The JobLevelModel class handles job level related operations and interactions.
*/
class JobLevelModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateJobLevel
    # Description: Updates the job level.
    #
    # Parameters:
    # - $p_job_level_id (int): The job level ID.
    # - $p_current_level (string): The current level.
    # - $p_rank (string): The rank.
    # - $p_functional_level (string): The functional level.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateJobLevel($p_job_level_id, $p_current_level, $p_rank, $p_functional_level, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateJobLevel(:p_job_level_id, :p_current_level, :p_rank, :p_functional_level, :p_last_log_by)');
        $stmt->bindValue(':p_job_level_id', $p_job_level_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_current_level', $p_current_level, PDO::PARAM_STR);
        $stmt->bindValue(':p_rank', $p_rank, PDO::PARAM_STR);
        $stmt->bindValue(':p_functional_level', $p_functional_level, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertJobLevel
    # Description: Inserts the job level.
    #
    # Parameters:
    # - $p_current_level (string): The current level.
    # - $p_rank (string): The rank.
    # - $p_functional_level (string): The functional level.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertJobLevel($p_current_level, $p_rank, $p_functional_level, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertJobLevel(:p_current_level, :p_rank, :p_functional_level, :p_last_log_by, @p_job_level_id)');
        $stmt->bindValue(':p_current_level', $p_current_level, PDO::PARAM_STR);
        $stmt->bindValue(':p_rank', $p_rank, PDO::PARAM_STR);
        $stmt->bindValue(':p_functional_level', $p_functional_level, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_job_level_id AS p_job_level_id");
        $p_job_level_id = $result->fetch(PDO::FETCH_ASSOC)['p_job_level_id'];

        return $p_job_level_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkJobLevelExist
    # Description: Checks if a job level exists.
    #
    # Parameters:
    # - $p_job_level_id (int): The job level ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkJobLevelExist($p_job_level_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkJobLevelExist(:p_job_level_id)');
        $stmt->bindValue(':p_job_level_id', $p_job_level_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteJobLevel
    # Description: Deletes the job level.
    #
    # Parameters:
    # - $p_job_level_id (int): The job level ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteJobLevel($p_job_level_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteJobLevel(:p_job_level_id)');
        $stmt->bindValue(':p_job_level_id', $p_job_level_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getJobLevel
    # Description: Retrieves the details of a job level.
    #
    # Parameters:
    # - $p_job_level_id (int): The job level ID.
    #
    # Returns:
    # - An array containing the job level details.
    #
    # -------------------------------------------------------------
    public function getJobLevel($p_job_level_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getJobLevel(:p_job_level_id)');
        $stmt->bindValue(':p_job_level_id', $p_job_level_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateJobLevel
    # Description: Duplicates the job level.
    #
    # Parameters:
    # - $p_job_level_id (int): The job level ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateJobLevel($p_job_level_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateJobLevel(:p_job_level_id, :p_last_log_by, @p_new_job_level_id)');
        $stmt->bindValue(':p_job_level_id', $p_job_level_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_job_level_id AS job_level_id");
        $jobLevelID = $result->fetch(PDO::FETCH_ASSOC)['job_level_id'];

        return $jobLevelID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateJobLevelOptions
    # Description: Generates the job level options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateJobLevelOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateJobLevelOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $jobLevelID = $row['job_level_id'];
            $currentLevel = $row['current_level'];
            $rank = $row['rank'];

            $htmlOptions .= '<option value="' . htmlspecialchars($jobLevelID, ENT_QUOTES) . '">' . htmlspecialchars($currentLevel, ENT_QUOTES) .' - '. $rank .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateJobLevelCheckBox
    # Description: Generates the job level check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateJobLevelCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateJobLevelOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $jobLevelID = $row['job_level_id'];
            $currentLevel = $row['current_level'];
            $rank = $row['rank'];

            $htmlOptions .= '<div class="form-check my-2">
                <input class="form-check-input job-level-filter" type="checkbox" id="job-level-' . htmlspecialchars($jobLevelID, ENT_QUOTES) . '" value="' . htmlspecialchars($jobLevelID, ENT_QUOTES) . '" />
                <label class="form-check-label" for="job-level-' . htmlspecialchars($jobLevelID, ENT_QUOTES) . '">' . htmlspecialchars($currentLevel, ENT_QUOTES) .' - '. $rank .'</label>
            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>