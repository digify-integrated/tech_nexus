<?php

class LeadModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    /* -----------------------------------------------
    | CHECK IF LEAD EXISTS
    |----------------------------------------------- */
    public function checkLeadExist($leadID) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT COUNT(*) AS total 
            FROM leads 
            WHERE lead_id = :lead_id
        ');

        $stmt->bindValue(':lead_id', $leadID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | INSERT LEAD
    |----------------------------------------------- */
    public function insertLead(
        $leadName,
        $email,
        $phone,
        $leadStatusID,
        $leadStatusName,
        $assignedTo,
        $assignedName,
        $remarks,
        $lastLogBy
    ) {

        $stmt = $this->db->getConnection()->prepare('
            INSERT INTO leads (
                lead_name,
                email,
                phone,
                lead_status_id,
                lead_status_name,
                assigned_to,
                assigned_to_name,
                remarks,
                last_log_by
            )
            VALUES (
                :lead_name,
                :email,
                :phone,
                :lead_status_id,
                :lead_status_name
                :assigned_to,
                :assigned_to_name
                :remarks,
                :last_log_by
            )
        ');

        $stmt->bindValue(':lead_name', $leadName, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindValue(':lead_status_id', $leadStatusID, PDO::PARAM_INT);
        $stmt->bindValue(':lead_status_name', $leadStatusName, PDO::PARAM_INT);
        $stmt->bindValue(':assigned_to', $assignedTo, PDO::PARAM_INT);
        $stmt->bindValue(':assigned_to_name', $assignedName, PDO::PARAM_INT);
        $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

        $stmt->execute();

        return $this->db->getConnection()->lastInsertId();
    }
    
    /* -----------------------------------------------
    | UPDATE LEAD
    |----------------------------------------------- */
    public function updateLead(
        $leadID,
        $leadName,
        $email,
        $phone,
        $leadStatusID,
        $leadStatusName,
        $assignedTo,
        $assignedName,
        $remarks,
        $lastLogBy
    ) {

        $stmt = $this->db->getConnection()->prepare('
            UPDATE leads
            SET 
                lead_name = :lead_name,
                email = :email,
                phone = :phone,
                lead_status_id = :lead_status_id,
                lead_status_name = :lead_status_name,
                assigned_to = :assigned_to,
                assigned_to_name = :assigned_to_name,
                remarks = :remarks,
                last_log_by = :last_log_by
            WHERE lead_id = :lead_id
        ');

        $stmt->bindValue(':lead_id', $leadID, PDO::PARAM_INT);
        $stmt->bindValue(':lead_name', $leadName, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindValue(':lead_status_id', $leadStatusID, PDO::PARAM_INT);
        $stmt->bindValue(':lead_status_name', $leadStatusName, PDO::PARAM_INT);
        $stmt->bindValue(':assigned_to', $assignedTo, PDO::PARAM_INT);
        $stmt->bindValue(':assigned_to_name', $assignedName, PDO::PARAM_INT);
        $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

        $stmt->execute();
    }

    /* -----------------------------------------------
    | DELETE LEAD
    |----------------------------------------------- */
    public function deleteLead($leadID) {
        $stmt = $this->db->getConnection()->prepare('
            DELETE FROM leads 
            WHERE lead_id = :lead_id
        ');

        $stmt->bindValue(':lead_id', $leadID, PDO::PARAM_INT);
        $stmt->execute();
    }

    /* -----------------------------------------------
    | GET LEAD DETAILS
    |----------------------------------------------- */
    public function getLead($leadID) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT 
                *
            FROM leads
            WHERE lead_id = :lead_id
        ');

        $stmt->bindValue(':lead_id', $leadID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function generateLeadTable() {
        $stmt = $this->db->getConnection()->prepare('
            SELECT *
            FROM leads
        ');

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | DUPLICATE LEAD
    |----------------------------------------------- */
    public function duplicateLead($leadID, $lastLogBy) {

        // get original lead
        $stmt = $this->db->getConnection()->prepare('
            SELECT lead_name, lead_status_id
            FROM leads
            WHERE lead_id = :lead_id
        ');

        $stmt->bindValue(':lead_id', $leadID, PDO::PARAM_INT);
        $stmt->execute();

        $lead = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$lead) {
            return null;
        }

        // insert copy
        $insert = $this->db->getConnection()->prepare('
            INSERT INTO leads (
                lead_name,
                lead_status_id,
                last_log_by
            )
            VALUES (
                :lead_name,
                :lead_status_id,
                :last_log_by
            )
        ');

        $insert->bindValue(':lead_name', $lead['lead_name'], PDO::PARAM_STR);
        $insert->bindValue(':lead_status_id', $lead['lead_status_id'], PDO::PARAM_STR);
        $insert->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);
        $insert->execute();

        return $this->db->getConnection()->lastInsertId();
    }

    /* -----------------------------------------------
    | GENERATE OPTIONS (FOR DROPDOWNS)
    |----------------------------------------------- */
    public function generateLeadOptions() {
        $stmt = $this->db->getConnection()->prepare('
            SELECT lead_id, lead_name 
            FROM leads 
            ORDER BY lead_name ASC
        ');

        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';

        foreach ($options as $row) {
            $htmlOptions .= '
                <option value="' . htmlspecialchars($row['lead_id'], ENT_QUOTES) . '">
                    ' . htmlspecialchars($row['lead_name'], ENT_QUOTES) . '
                </option>
            ';
        }

        return $htmlOptions;
    }

    /* -----------------------------------------------
    | GENERATE CHECKBOX FILTER
    |----------------------------------------------- */
    public function generateLeadCheckBox() {
        $stmt = $this->db->getConnection()->prepare('
            SELECT lead_id, lead_name 
            FROM leads 
            ORDER BY lead_name ASC
        ');

        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';

        foreach ($options as $row) {
            $leadID = htmlspecialchars($row['lead_id'], ENT_QUOTES);
            $leadName = htmlspecialchars($row['lead_name'], ENT_QUOTES);

            $htmlOptions .= '
                <div class="form-check my-2">
                    <input class="form-check-input lead-filter" 
                        type="checkbox" 
                        id="lead-' . $leadID . '" 
                        value="' . $leadID . '" />
                    <label class="form-check-label" for="lead-' . $leadID . '">
                        ' . $leadName . '
                    </label>
                </div>
            ';
        }

        return $htmlOptions;
    }
}