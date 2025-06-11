<?php
/**
* Class CMAPReportTypeModel
*
* The CMAPReportTypeModel class handles cmap report type related operations and interactions.
*/
class CMAPReportTypeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateCMAPReportType
    # Description: Updates the cmap report type.
    #
    # Parameters:
    # - $p_cmap_report_type_id (int): The cmap report type ID.
    # - $p_cmap_report_type_name (string): The cmap report type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateCMAPReportType($p_cmap_report_type_id, $p_cmap_report_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCMAPReportType(:p_cmap_report_type_id, :p_cmap_report_type_name, :p_last_log_by)');
        $stmt->bindValue(':p_cmap_report_type_id', $p_cmap_report_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_cmap_report_type_name', $p_cmap_report_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertCMAPReportType
    # Description: Inserts the cmap report type.
    #
    # Parameters:
    # - $p_cmap_report_type_name (string): The cmap report type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertCMAPReportType($p_cmap_report_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertCMAPReportType(:p_cmap_report_type_name, :p_last_log_by, @p_cmap_report_type_id)');
        $stmt->bindValue(':p_cmap_report_type_name', $p_cmap_report_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_cmap_report_type_id AS p_cmap_report_type_id");
        $p_cmap_report_type_id = $result->fetch(PDO::FETCH_ASSOC)['p_cmap_report_type_id'];

        return $p_cmap_report_type_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkCMAPReportTypeExist
    # Description: Checks if a cmap report type exists.
    #
    # Parameters:
    # - $p_cmap_report_type_id (int): The cmap report type ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkCMAPReportTypeExist($p_cmap_report_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkCMAPReportTypeExist(:p_cmap_report_type_id)');
        $stmt->bindValue(':p_cmap_report_type_id', $p_cmap_report_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteCMAPReportType
    # Description: Deletes the cmap report type.
    #
    # Parameters:
    # - $p_cmap_report_type_id (int): The cmap report type ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteCMAPReportType($p_cmap_report_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCMAPReportType(:p_cmap_report_type_id)');
        $stmt->bindValue(':p_cmap_report_type_id', $p_cmap_report_type_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getCMAPReportType
    # Description: Retrieves the details of a cmap report type.
    #
    # Parameters:
    # - $p_cmap_report_type_id (int): The cmap report type ID.
    #
    # Returns:
    # - An array containing the cmap report type details.
    #
    # -------------------------------------------------------------
    public function getCMAPReportType($p_cmap_report_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCMAPReportType(:p_cmap_report_type_id)');
        $stmt->bindValue(':p_cmap_report_type_id', $p_cmap_report_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateCMAPReportType
    # Description: Duplicates the cmap report type.
    #
    # Parameters:
    # - $p_cmap_report_type_id (int): The cmap report type ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateCMAPReportType($p_cmap_report_type_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateCMAPReportType(:p_cmap_report_type_id, :p_last_log_by, @p_new_cmap_report_type_id)');
        $stmt->bindValue(':p_cmap_report_type_id', $p_cmap_report_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_cmap_report_type_id AS cmap_report_type_id");
        $cmapReportTypeID = $result->fetch(PDO::FETCH_ASSOC)['cmap_report_type_id'];

        return $cmapReportTypeID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateCMAPReportTypeOptions
    # Description: Generates the cmap report type options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateCMAPReportTypeOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateCMAPReportTypeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $cmapReportTypeID = $row['cmap_report_type_id'];
            $cmapReportTypeName = $row['cmap_report_type_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($cmapReportTypeID, ENT_QUOTES) . '">' . htmlspecialchars($cmapReportTypeName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>