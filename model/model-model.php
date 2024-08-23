<?php
/**
* Class ModelModel
*
* The ModelModel class handles model related operations and interactions.
*/
class ModelModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateModel
    # Description: Updates the model.
    #
    # Parameters:
    # - $p_model_id (int): The model ID.
    # - $p_model_name (string): The model name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateModel($p_model_id, $p_model_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateModel(:p_model_id, :p_model_name, :p_last_log_by)');
        $stmt->bindValue(':p_model_id', $p_model_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_model_name', $p_model_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertModel
    # Description: Inserts the model.
    #
    # Parameters:
    # - $p_model_name (string): The model name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertModel($p_model_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertModel(:p_model_name, :p_last_log_by, @p_model_id)');
        $stmt->bindValue(':p_model_name', $p_model_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_model_id AS p_model_id");
        $p_model_id = $result->fetch(PDO::FETCH_ASSOC)['p_model_id'];

        return $p_model_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkModelExist
    # Description: Checks if a model exists.
    #
    # Parameters:
    # - $p_model_id (int): The model ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkModelExist($p_model_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkModelExist(:p_model_id)');
        $stmt->bindValue(':p_model_id', $p_model_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteModel
    # Description: Deletes the model.
    #
    # Parameters:
    # - $p_model_id (int): The model ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteModel($p_model_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteModel(:p_model_id)');
        $stmt->bindValue(':p_model_id', $p_model_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getModel
    # Description: Retrieves the details of a model.
    #
    # Parameters:
    # - $p_model_id (int): The model ID.
    #
    # Returns:
    # - An array containing the model details.
    #
    # -------------------------------------------------------------
    public function getModel($p_model_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getModel(:p_model_id)');
        $stmt->bindValue(':p_model_id', $p_model_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateModel
    # Description: Duplicates the model.
    #
    # Parameters:
    # - $p_model_id (int): The model ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateModel($p_model_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateModel(:p_model_id, :p_last_log_by, @p_new_model_id)');
        $stmt->bindValue(':p_model_id', $p_model_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_model_id AS model_id");
        $modeliD = $result->fetch(PDO::FETCH_ASSOC)['model_id'];

        return $modeliD;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateModelOptions
    # Description: Generates the model options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateModelOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateModelOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $modelID = $row['model_id'];
            $modelName = $row['model_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($modelID, ENT_QUOTES) . '">' . htmlspecialchars($modelName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateModelCheckBox
    # Description: Generates the model check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateModelCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateModelOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $modelID = $row['model_id'];
            $modelName = $row['model_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input model-filter" type="checkbox" id="model-' . htmlspecialchars($modelID, ENT_QUOTES) . '" value="' . htmlspecialchars($modelID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="model-' . htmlspecialchars($modelID, ENT_QUOTES) . '">' . htmlspecialchars($modelName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>