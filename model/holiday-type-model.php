<?php
/**
* Class HolidayTypeModel
*
* The HolidayTypeModel class handles holiday type related operations and interactions.
*/
class HolidayTypeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateHolidayType
    # Description: Updates the holiday type.
    #
    # Parameters:
    # - $p_holiday_type_id (int): The holiday type ID.
    # - $p_holiday_type_name (string): The holiday type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateHolidayType($p_holiday_type_id, $p_holiday_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateHolidayType(:p_holiday_type_id, :p_holiday_type_name, :p_last_log_by)');
        $stmt->bindValue(':p_holiday_type_id', $p_holiday_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_holiday_type_name', $p_holiday_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertHolidayType
    # Description: Inserts the holiday type.
    #
    # Parameters:
    # - $p_holiday_type_name (string): The holiday type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertHolidayType($p_holiday_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertHolidayType(:p_holiday_type_name, :p_last_log_by, @p_holiday_type_id)');
        $stmt->bindValue(':p_holiday_type_name', $p_holiday_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_holiday_type_id AS p_holiday_type_id");
        $p_holiday_type_id = $result->fetch(PDO::FETCH_ASSOC)['p_holiday_type_id'];

        return $p_holiday_type_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkHolidayTypeExist
    # Description: Checks if a holiday type exists.
    #
    # Parameters:
    # - $p_holiday_type_id (int): The holiday type ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkHolidayTypeExist($p_holiday_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkHolidayTypeExist(:p_holiday_type_id)');
        $stmt->bindValue(':p_holiday_type_id', $p_holiday_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteHolidayType
    # Description: Deletes the holiday type.
    #
    # Parameters:
    # - $p_holiday_type_id (int): The holiday type ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteHolidayType($p_holiday_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteHolidayType(:p_holiday_type_id)');
        $stmt->bindValue(':p_holiday_type_id', $p_holiday_type_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getHolidayType
    # Description: Retrieves the details of a holiday type.
    #
    # Parameters:
    # - $p_holiday_type_id (int): The holiday type ID.
    #
    # Returns:
    # - An array containing the holiday type details.
    #
    # -------------------------------------------------------------
    public function getHolidayType($p_holiday_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getHolidayType(:p_holiday_type_id)');
        $stmt->bindValue(':p_holiday_type_id', $p_holiday_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateHolidayType
    # Description: Duplicates the holiday type.
    #
    # Parameters:
    # - $p_holiday_type_id (int): The holiday type ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateHolidayType($p_holiday_type_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateHolidayType(:p_holiday_type_id, :p_last_log_by, @p_new_holiday_type_id)');
        $stmt->bindValue(':p_holiday_type_id', $p_holiday_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_holiday_type_id AS holiday_type_id");
        $holidayTypeID = $result->fetch(PDO::FETCH_ASSOC)['holiday_type_id'];

        return $holidayTypeID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateHolidayTypeOptions
    # Description: Generates the holiday type options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateHolidayTypeOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateHolidayTypeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $holidayTypeID = $row['holiday_type_id'];
            $holidayTypeName = $row['holiday_type_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($holidayTypeID, ENT_QUOTES) . '">' . htmlspecialchars($holidayTypeName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>