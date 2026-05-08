<?php

class CivilStatusModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }
    public function updateCivilStatus($p_civil_status_id, $p_civil_status_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCivilStatus(:p_civil_status_id, :p_civil_status_name, :p_last_log_by)');
        $stmt->bindValue(':p_civil_status_id', $p_civil_status_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_civil_status_name', $p_civil_status_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertCivilStatus($p_civil_status_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertCivilStatus(:p_civil_status_name, :p_last_log_by, @p_civil_status_id)');
        $stmt->bindValue(':p_civil_status_name', $p_civil_status_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_civil_status_id AS p_civil_status_id");
        $p_civil_status_id = $result->fetch(PDO::FETCH_ASSOC)['p_civil_status_id'];

        return $p_civil_status_id;
    }
    
    public function checkCivilStatusExist($p_civil_status_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkCivilStatusExist(:p_civil_status_id)');
        $stmt->bindValue(':p_civil_status_id', $p_civil_status_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteCivilStatus($p_civil_status_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCivilStatus(:p_civil_status_id)');
        $stmt->bindValue(':p_civil_status_id', $p_civil_status_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function getCivilStatus($p_civil_status_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCivilStatus(:p_civil_status_id)');
        $stmt->bindValue(':p_civil_status_id', $p_civil_status_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function duplicateCivilStatus($p_civil_status_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateCivilStatus(:p_civil_status_id, :p_last_log_by, @p_new_civil_status_id)');
        $stmt->bindValue(':p_civil_status_id', $p_civil_status_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_civil_status_id AS civil_status_id");
        $civilStatusID = $result->fetch(PDO::FETCH_ASSOC)['civil_status_id'];

        return $civilStatusID;
    }
    
    public function generateCivilStatusOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateCivilStatusOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $civilStatusID = $row['civil_status_id'];
            $civilStatusName = $row['civil_status_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($civilStatusID, ENT_QUOTES) . '">' . htmlspecialchars($civilStatusName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    
    public function generateCivilStatusCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateCivilStatusOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $civilStatusID = $row['civil_status_id'];
            $civilStatusName = $row['civil_status_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input civil-status-filter" type="checkbox" id="civil-status-' . htmlspecialchars($civilStatusID, ENT_QUOTES) . '" value="' . htmlspecialchars($civilStatusID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="civil-status-' . htmlspecialchars($civilStatusID, ENT_QUOTES) . '">' . htmlspecialchars($civilStatusName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
}