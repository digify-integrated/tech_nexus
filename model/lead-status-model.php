<?php

class LeadStatusModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    /* -----------------------------------------------
    | CHECK LEAD STATUS EXISTENCE
    |----------------------------------------------- */
    public function checkLeadStatusExist($leadStatusID) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT COUNT(*) AS total
            FROM lead_status
            WHERE lead_status_id = :lead_status_id
        ');

        $stmt->bindValue(':lead_status_id', $leadStatusID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | INSERT LEAD STATUS
    |----------------------------------------------- */
    public function insertLeadStatus($leadStatusName, $description, $leadStatusType, $lastLogBy) {
        $stmt = $this->db->getConnection()->prepare('
            INSERT INTO lead_status (
                lead_status_name,
                description,
                lead_status_type,
                last_log_by
            )
            VALUES (
                :lead_status_name,
                :description,
                :lead_status_type,
                :last_log_by
            )
        ');

        $stmt->bindValue(':lead_status_name', $leadStatusName, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':lead_status_type', $leadStatusType, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);
        $stmt->execute();

        return $this->db->getConnection()->lastInsertId();
    }

    /* -----------------------------------------------
    | UPDATE LEAD STATUS
    |----------------------------------------------- */
    public function updateLeadStatus($leadStatusID, $leadStatusName, $description, $leadStatusType, $lastLogBy) {
        $stmt = $this->db->getConnection()->prepare('
            UPDATE lead_status
            SET 
                lead_status_name = :lead_status_name,
                description = :description,
                lead_status_type = :lead_status_type,
                last_log_by = :last_log_by
            WHERE lead_status_id = :lead_status_id
        ');

        $stmt->bindValue(':lead_status_id', $leadStatusID, PDO::PARAM_INT);
        $stmt->bindValue(':lead_status_name', $leadStatusName, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':lead_status_type', $leadStatusType, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

        $stmt->execute();
    }

    /* -----------------------------------------------
    | DELETE LEAD STATUS
    |----------------------------------------------- */
    public function deleteLeadStatus($leadStatusID) {
        $stmt = $this->db->getConnection()->prepare('
            DELETE FROM lead_status
            WHERE lead_status_id = :lead_status_id
        ');

        $stmt->bindValue(':lead_status_id', $leadStatusID, PDO::PARAM_INT);
        $stmt->execute();
    }

    /* -----------------------------------------------
    | GET SINGLE LEAD STATUS
    |----------------------------------------------- */
    public function getLeadStatus($leadStatusID) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT 
                *
            FROM lead_status
            WHERE lead_status_id = :lead_status_id
        ');

        $stmt->bindValue(':lead_status_id', $leadStatusID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | GET ALL LEAD STATUSES (FOR DROPDOWN)
    |----------------------------------------------- */
    public function getLeadStatuses($lead_status_type) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT 
                lead_status_id,
                lead_status_name,
                description
            FROM lead_status
            WHERE lead_status_type = :lead_status_type
            ORDER BY lead_status_name ASC
        ');

        $stmt->bindValue(':lead_status_type', $lead_status_type, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | GENERATE OPTIONS (SELECT DROPDOWN)
    |----------------------------------------------- */
    public function generateLeadStatusOptions($lead_status_type) {
        $options = $this->getLeadStatuses($lead_status_type);

        $html = '';

        foreach ($options as $row) {
            $html .= '
                <option value="' . htmlspecialchars($row['lead_status_id'], ENT_QUOTES) . '">
                    ' . htmlspecialchars($row['lead_status_name'], ENT_QUOTES) . ' - ' . $row['description'] . '
                </option>
            ';
        }

        return $html;
    }

    /* -----------------------------------------------
    | GENERATE CHECKBOX FILTER
    |----------------------------------------------- */
    public function generateLeadStatusCheckBox($lead_status_type) {
        $options = $this->getLeadStatuses($lead_status_type);

        $html = '';

        foreach ($options as $row) {
            $id = htmlspecialchars($row['lead_status_id'], ENT_QUOTES);
            $name = htmlspecialchars($row['lead_status_name'], ENT_QUOTES);

            $html .= '
                <div class="form-check my-2">
                    <input class="form-check-input lead-status-filter" 
                        type="checkbox" 
                        id="lead-status-' . $id . '" 
                        value="' . $id . '" />
                    <label class="form-check-label" for="lead-status-' . $id . '">
                        ' . $name . '
                    </label>
                </div>
            ';
        }

        return $html;
    }
}