<?php

class LeadSourceModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    /* -----------------------------------------------
    | CHECK LEAD STATUS EXISTENCE
    |----------------------------------------------- */
    public function checkLeadSourceExist($leadSourceID) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT COUNT(*) AS total
            FROM lead_source
            WHERE lead_source_id = :lead_source_id
        ');

        $stmt->bindValue(':lead_source_id', $leadSourceID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | INSERT LEAD STATUS
    |----------------------------------------------- */
    public function insertLeadSource($leadSourceName, $lastLogBy) {
        $stmt = $this->db->getConnection()->prepare('
            INSERT INTO lead_source (
                lead_source_name,
                last_log_by
            )
            VALUES (
                :lead_source_name,
                :last_log_by
            )
        ');

        $stmt->bindValue(':lead_source_name', $leadSourceName, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);
        $stmt->execute();

        return $this->db->getConnection()->lastInsertId();
    }

    /* -----------------------------------------------
    | UPDATE LEAD STATUS
    |----------------------------------------------- */
    public function updateLeadSource($leadSourceID, $leadSourceName, $lastLogBy) {
        $stmt = $this->db->getConnection()->prepare('
            UPDATE lead_source
            SET 
                lead_source_name = :lead_source_name,
                last_log_by = :last_log_by
            WHERE lead_source_id = :lead_source_id
        ');

        $stmt->bindValue(':lead_source_id', $leadSourceID, PDO::PARAM_INT);
        $stmt->bindValue(':lead_source_name', $leadSourceName, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

        $stmt->execute();
    }

    /* -----------------------------------------------
    | DELETE LEAD STATUS
    |----------------------------------------------- */
    public function deleteLeadSource($leadSourceID) {
        $stmt = $this->db->getConnection()->prepare('
            DELETE FROM lead_source
            WHERE lead_source_id = :lead_source_id
        ');

        $stmt->bindValue(':lead_source_id', $leadSourceID, PDO::PARAM_INT);
        $stmt->execute();
    }

    /* -----------------------------------------------
    | GET SINGLE LEAD STATUS
    |----------------------------------------------- */
    public function getLeadSource($leadSourceID) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT 
                *
            FROM lead_source
            WHERE lead_source_id = :lead_source_id
        ');

        $stmt->bindValue(':lead_source_id', $leadSourceID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | GET ALL LEAD STATUSES (FOR DROPDOWN)
    |----------------------------------------------- */
    public function getLeadSources() {
        $stmt = $this->db->getConnection()->prepare('
            SELECT 
                lead_source_id,
                lead_source_name
            FROM lead_source
            ORDER BY lead_source_name ASC
        ');

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | GENERATE OPTIONS (SELECT DROPDOWN)
    |----------------------------------------------- */
    public function generateLeadSourceOptions() {
        $options = $this->getLeadSources();

        $html = '';

        foreach ($options as $row) {
            $html .= '
                <option value="' . htmlspecialchars($row['lead_source_id'], ENT_QUOTES) . '">
                    ' . htmlspecialchars($row['lead_source_name'], ENT_QUOTES) .'
                </option>
            ';
        }

        return $html;
    }

    /* -----------------------------------------------
    | GENERATE CHECKBOX FILTER
    |----------------------------------------------- */
    public function generateLeadSourceCheckBox() {
        $options = $this->getLeadSources();

        $html = '';

        foreach ($options as $row) {
            $id = htmlspecialchars($row['lead_source_id'], ENT_QUOTES);
            $name = htmlspecialchars($row['lead_source_name'], ENT_QUOTES);

            $html .= '
                <div class="form-check my-2">
                    <input class="form-check-input lead-source-filter" 
                        type="checkbox" 
                        id="lead-source-' . $id . '" 
                        value="' . $id . '" />
                    <label class="form-check-label" for="lead-source-' . $id . '">
                        ' . $name . '
                    </label>
                </div>
            ';
        }

        return $html;
    }
}