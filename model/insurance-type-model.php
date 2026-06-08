<?php

class InsuranceTypeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    /* -----------------------------------------------
    | CHECK LEAD STATUS EXISTENCE
    |----------------------------------------------- */
    public function checkInsuranceTypeExist($insuranceTypeID) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT COUNT(*) AS total
            FROM insurance_type
            WHERE insurance_type_id = :insurance_type_id
        ');

        $stmt->bindValue(':insurance_type_id', $insuranceTypeID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | INSERT LEAD STATUS
    |----------------------------------------------- */
    public function insertInsuranceType($insuranceTypeName, $lastLogBy) {
        $stmt = $this->db->getConnection()->prepare('
            INSERT INTO insurance_type (
                insurance_type_name,
                last_log_by
            )
            VALUES (
                :insurance_type_name,
                :last_log_by
            )
        ');

        $stmt->bindValue(':insurance_type_name', $insuranceTypeName, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);
        $stmt->execute();

        return $this->db->getConnection()->lastInsertId();
    }

    /* -----------------------------------------------
    | UPDATE LEAD STATUS
    |----------------------------------------------- */
    public function updateInsuranceType($insuranceTypeID, $insuranceTypeName, $lastLogBy) {
        $stmt = $this->db->getConnection()->prepare('
            UPDATE insurance_type
            SET 
                insurance_type_name = :insurance_type_name,
                last_log_by = :last_log_by
            WHERE insurance_type_id = :insurance_type_id
        ');

        $stmt->bindValue(':insurance_type_id', $insuranceTypeID, PDO::PARAM_INT);
        $stmt->bindValue(':insurance_type_name', $insuranceTypeName, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

        $stmt->execute();
    }

    /* -----------------------------------------------
    | DELETE LEAD STATUS
    |----------------------------------------------- */
    public function deleteInsuranceType($insuranceTypeID) {
        $stmt = $this->db->getConnection()->prepare('
            DELETE FROM insurance_type
            WHERE insurance_type_id = :insurance_type_id
        ');

        $stmt->bindValue(':insurance_type_id', $insuranceTypeID, PDO::PARAM_INT);
        $stmt->execute();
    }

    /* -----------------------------------------------
    | GET SINGLE LEAD STATUS
    |----------------------------------------------- */
    public function getInsuranceType($insuranceTypeID) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT 
                insurance_type_id,
                insurance_type_name
            FROM insurance_type
            WHERE insurance_type_id = :insurance_type_id
        ');

        $stmt->bindValue(':insurance_type_id', $insuranceTypeID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | GET ALL LEAD STATUSES (FOR DROPDOWN)
    |----------------------------------------------- */
    public function getInsuranceTypees() {
        $stmt = $this->db->getConnection()->prepare('
            SELECT 
                insurance_type_id,
                insurance_type_name
            FROM insurance_type
            ORDER BY insurance_type_name ASC
        ');

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | GENERATE OPTIONS (SELECT DROPDOWN)
    |----------------------------------------------- */
    public function generateInsuranceTypeOptions() {
        $options = $this->getInsuranceTypees();

        $html = '';

        foreach ($options as $row) {
            $html .= '
                <option value="' . htmlspecialchars($row['insurance_type_id'], ENT_QUOTES) . '">
                    ' . htmlspecialchars($row['insurance_type_name'], ENT_QUOTES) . '
                </option>
            ';
        }

        return $html;
    }

    /* -----------------------------------------------
    | GENERATE CHECKBOX FILTER
    |----------------------------------------------- */
    public function generateInsuranceTypeCheckBox() {
        $options = $this->getInsuranceTypees();

        $html = '';

        foreach ($options as $row) {
            $id = htmlspecialchars($row['insurance_type_id'], ENT_QUOTES);
            $name = htmlspecialchars($row['insurance_type_name'], ENT_QUOTES);

            $html .= '
                <div class="form-check my-2">
                    <input class="form-check-input insurance-type-filter" 
                        type="checkbox" 
                        id="insurance-type-' . $id . '" 
                        value="' . $id . '" />
                    <label class="form-check-label" for="insurance-type-' . $id . '">
                        ' . $name . '
                    </label>
                </div>
            ';
        }

        return $html;
    }
}