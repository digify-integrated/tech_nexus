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
        $fileAs,
        $firstName,
        $middleName,
        $lastName,
        $corporateName,
        $stockNumber,
        $address,
        $cityID,
        $email,
        $phone,
        $genderID,
        $leadStatusID,
        $inquiryTypeID,
        $remarks,
        $inquiry_date,
        $lastLogBy
    ) {

        $stmt = $this->db->getConnection()->prepare('
            INSERT INTO leads (
                file_as,
                first_name,
                middle_name,
                last_name,
                corporate_name,
                stock_number,
                address,
                city_id,
                email,
                phone,
                gender_id,
                lead_status_id,
                inquiry_type_id,
                assigned_to,
                remarks,
                inquiry_date,
                last_log_by
            )
            VALUES (
                :file_as,
                :first_name,
                :middle_name,
                :last_name,
                :corporate_name,
                :stock_number,
                :address,
                :city_id,
                :email,
                :phone,
                :gender_id,
                :lead_status_id,
                :inquiry_type_id,
                :assigned_to,
                :remarks,
                :inquiry_date,
                :last_log_by
            )
        ');

        $stmt->bindValue(':file_as', $fileAs, PDO::PARAM_STR);
        $stmt->bindValue(':first_name', $firstName, PDO::PARAM_STR);
        $stmt->bindValue(':middle_name', $middleName, PDO::PARAM_STR);
        $stmt->bindValue(':last_name', $lastName, PDO::PARAM_STR);
        $stmt->bindValue(':corporate_name', $corporateName, PDO::PARAM_STR);
        $stmt->bindValue(':stock_number', $stockNumber, PDO::PARAM_STR);
        $stmt->bindValue(':address', $address, PDO::PARAM_STR);
        $stmt->bindValue(':city_id', $cityID, PDO::PARAM_INT);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindValue(':gender_id', $genderID, PDO::PARAM_INT);
        $stmt->bindValue(':lead_status_id', $leadStatusID, PDO::PARAM_INT);
        $stmt->bindValue(':inquiry_type_id', $inquiryTypeID, PDO::PARAM_INT);

        // assigned_to = creator during insert
        $stmt->bindValue(':assigned_to', $lastLogBy, PDO::PARAM_INT);

        $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':inquiry_date', $inquiry_date, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

        $stmt->execute();

        return $this->db->getConnection()->lastInsertId();
    }
    
    /* -----------------------------------------------
    | UPDATE LEAD
    |----------------------------------------------- */
    public function updateLead(
        $leadID,
        $fileAs,
        $firstName,
        $middleName,
        $lastName,
        $corporateName,
        $stockNumber,
        $address,
        $cityID,
        $email,
        $phone,
        $genderID,
        $leadStatusID,
        $inquiryTypeID,
        $remarks,
        $inquiry_date,
        $lastLogBy
    ) {

        $stmt = $this->db->getConnection()->prepare('
            UPDATE leads
            SET
                file_as = :file_as,
                first_name = :first_name,
                middle_name = :middle_name,
                last_name = :last_name,
                corporate_name = :corporate_name,
                stock_number = :stock_number,
                address = :address,
                city_id = :city_id,
                email = :email,
                phone = :phone,
                gender_id = :gender_id,
                lead_status_id = :lead_status_id,
                inquiry_type_id = :inquiry_type_id,
                remarks = :remarks,
                inquiry_date = :inquiry_date,
                last_log_by = :last_log_by
            WHERE lead_id = :lead_id
        ');

        $stmt->bindValue(':lead_id', $leadID, PDO::PARAM_INT);
        $stmt->bindValue(':file_as', $fileAs, PDO::PARAM_STR);
        $stmt->bindValue(':first_name', $firstName, PDO::PARAM_STR);
        $stmt->bindValue(':middle_name', $middleName, PDO::PARAM_STR);
        $stmt->bindValue(':last_name', $lastName, PDO::PARAM_STR);
        $stmt->bindValue(':corporate_name', $corporateName, PDO::PARAM_STR);
        $stmt->bindValue(':stock_number', $stockNumber, PDO::PARAM_STR);
        $stmt->bindValue(':address', $address, PDO::PARAM_STR);
        $stmt->bindValue(':city_id', $cityID, PDO::PARAM_INT);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindValue(':gender_id', $genderID, PDO::PARAM_INT);
        $stmt->bindValue(':lead_status_id', $leadStatusID, PDO::PARAM_INT);
        $stmt->bindValue(':inquiry_type_id', $inquiryTypeID, PDO::PARAM_INT);
        $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':inquiry_date', $inquiry_date, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function updateLeadStatus(
        $leadID,
        $leadStatusID,
        $lastLogBy
    ) {

        $stmt = $this->db->getConnection()->prepare('
            UPDATE leads
            SET
                lead_status_id = :lead_status_id,
                last_log_by = :last_log_by
            WHERE lead_id = :lead_id
        ');

        $stmt->bindValue(':lead_id', $leadID, PDO::PARAM_INT);
        $stmt->bindValue(':lead_status_id', $leadStatusID, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function insertLeadNote(
        $leadID,
        $note,
        $createdBy
    ) {

        $stmt = $this->db->getConnection()->prepare('
            INSERT INTO lead_notes (
                lead_id,
                note,
                created_by
            )
            VALUES (
                :lead_id,
                :note,
                :created_by
            )
        ');

        $stmt->bindValue(':lead_id', $leadID, PDO::PARAM_INT);
        $stmt->bindValue(':note', $note, PDO::PARAM_STR);
        $stmt->bindValue(':created_by', $createdBy, PDO::PARAM_INT);

        $stmt->execute();

        return $this->db->getConnection()->lastInsertId();
    }

    public function deleteLeadNote(
        $leadNoteID,
        $deletedBy
    ) {

        $stmt = $this->db->getConnection()->prepare('
            UPDATE lead_notes
            SET
                deleted_by = :deleted_by,
                deleted_at = NOW()
            WHERE lead_note_id = :lead_note_id
        ');

        $stmt->bindValue(':lead_note_id', $leadNoteID, PDO::PARAM_INT);
        $stmt->bindValue(':deleted_by', $deletedBy, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function generateLeadNotes($leadID) {

        $stmt = $this->db->getConnection()->prepare('
            SELECT
                ln.lead_note_id,
                ln.note,
                ln.created_at,
                e.file_as
            FROM lead_notes ln
            LEFT JOIN users e
                ON ln.created_by = e.user_id
            WHERE ln.lead_id = :lead_id
            AND ln.deleted_at IS NULL
            ORDER BY ln.created_at DESC
        ');

        $stmt->bindValue(':lead_id', $leadID, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public function generateAllLeadTable(
        $filter_created_date_start_date,
        $filter_created_date_end_date,
        $filter_inquiry_date_start_date,
        $filter_inquiry_date_end_date,
        $filter_lead_status,
        $filter_inquiry_type
    ) {

        $query = '
            SELECT *
            FROM leads
            WHERE 1=1
        ';

        $params = [];

        /* =========================
            CREATED DATE FILTER
        ========================== */
        if (!empty($filter_created_date_start_date) && !empty($filter_created_date_end_date)) {

            $query .= ' AND DATE(created_at) BETWEEN :created_start AND :created_end';

            $params[':created_start'] = $filter_created_date_start_date;
            $params[':created_end'] = $filter_created_date_end_date;
        }

        /* =========================
            INQUIRY DATE FILTER
        ========================== */
        if (!empty($filter_inquiry_date_start_date) && !empty($filter_inquiry_date_end_date)) {

            $query .= ' AND inquiry_date BETWEEN :inquiry_start AND :inquiry_end';

            $params[':inquiry_start'] = $filter_inquiry_date_start_date;
            $params[':inquiry_end'] = $filter_inquiry_date_end_date;
        }

        /* =========================
            LEAD STATUS FILTER
        ========================== */
        if (!empty($filter_lead_status)) {

            $query .= " AND lead_status_id IN ($filter_lead_status)";
        }

        /* =========================
            INQUIRY TYPE FILTER
        ========================== */
        if (!empty($filter_inquiry_type)) {

            $query .= " AND inquiry_type_id IN ($filter_inquiry_type)";
        }

        /* =========================
            ORDER
        ========================== */
        $query .= ' ORDER BY created_at DESC';

        $stmt = $this->db->getConnection()->prepare($query);

        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function generateLeadTable(
        $filter_created_date_start_date,
        $filter_created_date_end_date,
        $filter_inquiry_date_start_date,
        $filter_inquiry_date_end_date,
        $filter_lead_status,
        $filter_inquiry_type,
        $assigned_to
    ) {

        $query = '
            SELECT *
            FROM leads
            WHERE assigned_to = :assigned_to
        ';

        $params = [];

        /* =========================
            CREATED DATE FILTER
        ========================== */
        if (!empty($filter_created_date_start_date) && !empty($filter_created_date_end_date)) {

            $query .= ' AND DATE(created_at) BETWEEN :created_start AND :created_end';

            $params[':created_start'] = $filter_created_date_start_date;
            $params[':created_end'] = $filter_created_date_end_date;
        }

        /* =========================
            INQUIRY DATE FILTER
        ========================== */
        if (!empty($filter_inquiry_date_start_date) && !empty($filter_inquiry_date_end_date)) {

            $query .= ' AND inquiry_date BETWEEN :inquiry_start AND :inquiry_end';

            $params[':inquiry_start'] = $filter_inquiry_date_start_date;
            $params[':inquiry_end'] = $filter_inquiry_date_end_date;
        }

        /* =========================
            LEAD STATUS FILTER
        ========================== */
        if (!empty($filter_lead_status)) {

            $query .= " AND lead_status_id IN ($filter_lead_status)";
        }

        /* =========================
            INQUIRY TYPE FILTER
        ========================== */
        if (!empty($filter_inquiry_type)) {

            $query .= " AND inquiry_type_id IN ($filter_inquiry_type)";
        }

        /* =========================
            ORDER
        ========================== */
        $query .= ' ORDER BY created_at DESC';

        
        $params['assigned_to'] = $assigned_to;

        $stmt = $this->db->getConnection()->prepare($query);

        $stmt->execute($params);

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