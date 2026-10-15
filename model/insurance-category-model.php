<?php

class InsuranceCategoryModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    /* -----------------------------------------------
    | CHECK LEAD STATUS EXISTENCE
    |----------------------------------------------- */
    public function checkInsuranceCategoryExist($insuranceCategoryID) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT COUNT(*) AS total
            FROM insurance_category
            WHERE insurance_category_id = :insurance_category_id
        ');

        $stmt->bindValue(':insurance_category_id', $insuranceCategoryID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | INSERT LEAD STATUS
    |----------------------------------------------- */
    public function insertInsuranceCategory($insuranceCategoryName, $lastLogBy) {
        $stmt = $this->db->getConnection()->prepare('
            INSERT INTO insurance_category (
                insurance_category_name,
                last_log_by
            )
            VALUES (
                :insurance_category_name,
                :last_log_by
            )
        ');

        $stmt->bindValue(':insurance_category_name', $insuranceCategoryName, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);
        $stmt->execute();

        return $this->db->getConnection()->lastInsertId();
    }

    /* -----------------------------------------------
    | UPDATE LEAD STATUS
    |----------------------------------------------- */
    public function updateInsuranceCategory($insuranceCategoryID, $insuranceCategoryName, $lastLogBy) {
        $stmt = $this->db->getConnection()->prepare('
            UPDATE insurance_category
            SET 
                insurance_category_name = :insurance_category_name,
                last_log_by = :last_log_by
            WHERE insurance_category_id = :insurance_category_id
        ');

        $stmt->bindValue(':insurance_category_id', $insuranceCategoryID, PDO::PARAM_INT);
        $stmt->bindValue(':insurance_category_name', $insuranceCategoryName, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

        $stmt->execute();
    }

    /* -----------------------------------------------
    | DELETE LEAD STATUS
    |----------------------------------------------- */
    public function deleteInsuranceCategory($insuranceCategoryID) {
        $stmt = $this->db->getConnection()->prepare('
            DELETE FROM insurance_category
            WHERE insurance_category_id = :insurance_category_id
        ');

        $stmt->bindValue(':insurance_category_id', $insuranceCategoryID, PDO::PARAM_INT);
        $stmt->execute();
    }

    /* -----------------------------------------------
    | GET SINGLE LEAD STATUS
    |----------------------------------------------- */
    public function getInsuranceCategory($insuranceCategoryID) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT 
                insurance_category_id,
                insurance_category_name
            FROM insurance_category
            WHERE insurance_category_id = :insurance_category_id
        ');

        $stmt->bindValue(':insurance_category_id', $insuranceCategoryID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | GET ALL LEAD STATUSES (FOR DROPDOWN)
    |----------------------------------------------- */
    public function getInsuranceCategoryes() {
        $stmt = $this->db->getConnection()->prepare('
            SELECT 
                insurance_category_id,
                insurance_category_name
            FROM insurance_category
            ORDER BY insurance_category_name ASC
        ');

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | GENERATE OPTIONS (SELECT DROPDOWN)
    |----------------------------------------------- */
    public function generateInsuranceCategoryOptions() {
        $options = $this->getInsuranceCategoryes();

        $html = '';

        foreach ($options as $row) {
            $html .= '
                <option value="' . htmlspecialchars($row['insurance_category_id'], ENT_QUOTES) . '">
                    ' . htmlspecialchars($row['insurance_category_name'], ENT_QUOTES) . '
                </option>
            ';
        }

        return $html;
    }

    /* -----------------------------------------------
    | GENERATE CHECKBOX FILTER
    |----------------------------------------------- */
    public function generateInsuranceCategoryCheckBox() {
        $options = $this->getInsuranceCategoryes();

        $html = '';

        foreach ($options as $row) {
            $id = htmlspecialchars($row['insurance_category_id'], ENT_QUOTES);
            $name = htmlspecialchars($row['insurance_category_name'], ENT_QUOTES);

            $html .= '
                <div class="form-check my-2">
                    <input class="form-check-input insurance-category-filter" 
                        type="checkbox" 
                        id="insurance-category-' . $id . '" 
                        value="' . $id . '" />
                    <label class="form-check-label" for="insurance-category-' . $id . '">
                        ' . $name . '
                    </label>
                </div>
            ';
        }

        return $html;
    }
}