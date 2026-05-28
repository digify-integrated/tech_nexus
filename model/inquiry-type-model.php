<?php

class InquiryTypeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    /* -----------------------------------------------
    | CHECK LEAD STATUS EXISTENCE
    |----------------------------------------------- */
    public function checkInquiryTypeExist($inquiryTypeID) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT COUNT(*) AS total
            FROM inquiry_type
            WHERE inquiry_type_id = :inquiry_type_id
        ');

        $stmt->bindValue(':inquiry_type_id', $inquiryTypeID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | INSERT LEAD STATUS
    |----------------------------------------------- */
    public function insertInquiryType($inquiryTypeName, $lastLogBy) {
        $stmt = $this->db->getConnection()->prepare('
            INSERT INTO inquiry_type (
                inquiry_type_name,
                last_log_by
            )
            VALUES (
                :inquiry_type_name,
                :last_log_by
            )
        ');

        $stmt->bindValue(':inquiry_type_name', $inquiryTypeName, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);
        $stmt->execute();

        return $this->db->getConnection()->lastInsertId();
    }

    /* -----------------------------------------------
    | UPDATE LEAD STATUS
    |----------------------------------------------- */
    public function updateInquiryType($inquiryTypeID, $inquiryTypeName, $lastLogBy) {
        $stmt = $this->db->getConnection()->prepare('
            UPDATE inquiry_type
            SET 
                inquiry_type_name = :inquiry_type_name,
                last_log_by = :last_log_by
            WHERE inquiry_type_id = :inquiry_type_id
        ');

        $stmt->bindValue(':inquiry_type_id', $inquiryTypeID, PDO::PARAM_INT);
        $stmt->bindValue(':inquiry_type_name', $inquiryTypeName, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

        $stmt->execute();
    }

    /* -----------------------------------------------
    | DELETE LEAD STATUS
    |----------------------------------------------- */
    public function deleteInquiryType($inquiryTypeID) {
        $stmt = $this->db->getConnection()->prepare('
            DELETE FROM inquiry_type
            WHERE inquiry_type_id = :inquiry_type_id
        ');

        $stmt->bindValue(':inquiry_type_id', $inquiryTypeID, PDO::PARAM_INT);
        $stmt->execute();
    }

    /* -----------------------------------------------
    | GET SINGLE LEAD STATUS
    |----------------------------------------------- */
    public function getInquiryType($inquiryTypeID) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT 
                inquiry_type_id,
                inquiry_type_name
            FROM inquiry_type
            WHERE inquiry_type_id = :inquiry_type_id
        ');

        $stmt->bindValue(':inquiry_type_id', $inquiryTypeID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | GET ALL LEAD STATUSES (FOR DROPDOWN)
    |----------------------------------------------- */
    public function getInquiryTypees() {
        $stmt = $this->db->getConnection()->prepare('
            SELECT 
                inquiry_type_id,
                inquiry_type_name
            FROM inquiry_type
            ORDER BY inquiry_type_name ASC
        ');

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | GENERATE OPTIONS (SELECT DROPDOWN)
    |----------------------------------------------- */
    public function generateInquiryTypeOptions() {
        $options = $this->getInquiryTypees();

        $html = '';

        foreach ($options as $row) {
            $html .= '
                <option value="' . htmlspecialchars($row['inquiry_type_id'], ENT_QUOTES) . '">
                    ' . htmlspecialchars($row['inquiry_type_name'], ENT_QUOTES) . '
                </option>
            ';
        }

        return $html;
    }

    /* -----------------------------------------------
    | GENERATE CHECKBOX FILTER
    |----------------------------------------------- */
    public function generateInquiryTypeCheckBox() {
        $options = $this->getInquiryTypees();

        $html = '';

        foreach ($options as $row) {
            $id = htmlspecialchars($row['inquiry_type_id'], ENT_QUOTES);
            $name = htmlspecialchars($row['inquiry_type_name'], ENT_QUOTES);

            $html .= '
                <div class="form-check my-2">
                    <input class="form-check-input inquiry-type-filter" 
                        type="checkbox" 
                        id="inquiry-type-' . $id . '" 
                        value="' . $id . '" />
                    <label class="form-check-label" for="inquiry-type-' . $id . '">
                        ' . $name . '
                    </label>
                </div>
            ';
        }

        return $html;
    }
}