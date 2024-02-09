<?php
/**
* Class ColorModel
*
* The ColorModel class handles color related operations and interactions.
*/
class ColorModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateColor
    # Description: Updates the color.
    #
    # Parameters:
    # - $p_color_id (int): The color ID.
    # - $p_color_name (string): The color name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateColor($p_color_id, $p_color_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateColor(:p_color_id, :p_color_name, :p_last_log_by)');
        $stmt->bindValue(':p_color_id', $p_color_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_color_name', $p_color_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertColor
    # Description: Inserts the color.
    #
    # Parameters:
    # - $p_color_name (string): The color name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertColor($p_color_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertColor(:p_color_name, :p_last_log_by, @p_color_id)');
        $stmt->bindValue(':p_color_name', $p_color_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_color_id AS p_color_id");
        $p_color_id = $result->fetch(PDO::FETCH_ASSOC)['p_color_id'];

        return $p_color_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkColorExist
    # Description: Checks if a color exists.
    #
    # Parameters:
    # - $p_color_id (int): The color ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkColorExist($p_color_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkColorExist(:p_color_id)');
        $stmt->bindValue(':p_color_id', $p_color_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteColor
    # Description: Deletes the color.
    #
    # Parameters:
    # - $p_color_id (int): The color ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteColor($p_color_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteColor(:p_color_id)');
        $stmt->bindValue(':p_color_id', $p_color_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getColor
    # Description: Retrieves the details of a color.
    #
    # Parameters:
    # - $p_color_id (int): The color ID.
    #
    # Returns:
    # - An array containing the color details.
    #
    # -------------------------------------------------------------
    public function getColor($p_color_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getColor(:p_color_id)');
        $stmt->bindValue(':p_color_id', $p_color_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateColor
    # Description: Duplicates the color.
    #
    # Parameters:
    # - $p_color_id (int): The color ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateColor($p_color_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateColor(:p_color_id, :p_last_log_by, @p_new_color_id)');
        $stmt->bindValue(':p_color_id', $p_color_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_color_id AS color_id");
        $systemActionID = $result->fetch(PDO::FETCH_ASSOC)['color_id'];

        return $systemActionID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateColorOptions
    # Description: Generates the color options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateColorOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateColorOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $colorID = $row['color_id'];
            $colorName = $row['color_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($colorID, ENT_QUOTES) . '">' . htmlspecialchars($colorName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateColorCheckBox
    # Description: Generates the color check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateColorCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateColorOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $colorID = $row['color_id'];
            $colorName = $row['color_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input color-filter" type="checkbox" id="color-' . htmlspecialchars($colorID, ENT_QUOTES) . '" value="' . htmlspecialchars($colorID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="color-' . htmlspecialchars($colorID, ENT_QUOTES) . '">' . htmlspecialchars($colorName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>