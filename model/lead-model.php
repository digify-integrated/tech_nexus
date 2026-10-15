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

    public function checkSPAFExist($spaf_id) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT COUNT(*) AS total 
            FROM spaf 
            WHERE spaf_id = :spaf_id
        ');

        $stmt->bindValue(':spaf_id', $spaf_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkQuotationExist($quotation_id) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT COUNT(*) AS total 
            FROM quotations 
            WHERE quotation_id = :quotation_id
        ');

        $stmt->bindValue(':quotation_id', $quotation_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkCIFSupplierExist($cif_id, $supplier_id) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT COUNT(*) AS total 
            FROM cif_supplier 
            WHERE cif_id = :cif_id AND supplier_id = :supplier_id
        ');

        $stmt->bindValue(':cif_id', $cif_id, PDO::PARAM_INT);
        $stmt->bindValue(':supplier_id', $supplier_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkCIFExist($cif_id) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT COUNT(*) AS total 
            FROM cif
            WHERE cif_id = :cif_id
        ');

        $stmt->bindValue(':cif_id', $cif_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkLeadProductInquiryExist($leadID, $product_id) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT COUNT(*) AS total 
            FROM leads_product_inquiry 
            WHERE lead_id = :lead_id AND product_id = :product_id
        ');

        $stmt->bindValue(':lead_id', $leadID, PDO::PARAM_INT);
        $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkDuplicateLead($firstName, $lastName, $leadID = null) {
        $sql = 'SELECT l.lead_id, u.file_as AS assigned_user_name 
                FROM leads l
                LEFT JOIN users u ON l.assigned_to = u.user_id
                WHERE LOWER(l.first_name) = LOWER(:first_name) 
                AND LOWER(l.last_name) = LOWER(:last_name)';

        // If updating, ignore current lead
        if (!empty($leadID)) {
            $sql .= ' AND l.lead_id != :lead_id';
        }

        $sql .= ' LIMIT 1';

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindValue(':first_name', $firstName, PDO::PARAM_STR);
        $stmt->bindValue(':last_name', $lastName, PDO::PARAM_STR);

        if (!empty($leadID)) {
            $stmt->bindValue(':lead_id', $leadID, PDO::PARAM_INT);
        }

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
        $address,
        $cityID,
        $email,
        $phone,
        $social_media_account,
        $genderID,
        $leadStatusID,
        $inquiryTypeID,
        $leadSourceID,
        $leadPriority,
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
                address,
                city_id,
                email,
                phone,
                social_media_account,
                gender_id,
                lead_status_id,
                lead_source_id,
                lead_priority,
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
                :address,
                :city_id,
                :email,
                :phone,
                :social_media_account,
                :gender_id,
                :lead_status_id,
                :lead_source_id,
                :lead_priority,
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
        $stmt->bindValue(':address', $address, PDO::PARAM_STR);
        $stmt->bindValue(':city_id', $cityID, PDO::PARAM_INT);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindValue(':social_media_account', $social_media_account, PDO::PARAM_STR);
        $stmt->bindValue(':gender_id', $genderID, PDO::PARAM_INT);
        $stmt->bindValue(':lead_status_id', $leadStatusID, PDO::PARAM_INT);
        $stmt->bindValue(':inquiry_type_id', $inquiryTypeID, PDO::PARAM_INT);
        $stmt->bindValue(':lead_source_id', $leadSourceID, PDO::PARAM_INT);
        $stmt->bindValue(':lead_priority', $leadPriority, PDO::PARAM_STR);
        // assigned_to = creator during insert
        $stmt->bindValue(':assigned_to', $lastLogBy, PDO::PARAM_INT);

        $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':inquiry_date', $inquiry_date, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

        $stmt->execute();

        return $this->db->getConnection()->lastInsertId();
    }

    public function insertQuotation(
        $lead_id,
        $leads_product_inquiry_id,
        $company_id,
        $price,
        $quantity,
        $discount,
        $inclusions,
        $delivery_address,
        $delivery_instructions,
        $delivery_conditions,
        $remarks,
        $expiration_date,
        $pre_approve,
        $lastLogBy
    ) {

        $stmt = $this->db->getConnection()->prepare('
            INSERT INTO quotations (
                lead_id,
                leads_product_inquiry_id,
                company_id,
                price,
                quantity,
                discount,
                inclusions,
                delivery_address,
                delivery_instructions,
                delivery_conditions,
                remarks,
                expiration_date,
                pre_approve,
                last_log_by
            )
            VALUES (
                :lead_id,
                :leads_product_inquiry_id,
                :company_id,
                :price,
                :quantity,
                :discount,
                :inclusions,
                :delivery_address,
                :delivery_instructions,
                :delivery_conditions,
                :remarks,
                :expiration_date,
                :pre_approve,
                :last_log_by
            )
        ');

        $stmt->bindValue(':lead_id', $lead_id, PDO::PARAM_INT);
        $stmt->bindValue(':leads_product_inquiry_id', $leads_product_inquiry_id, PDO::PARAM_INT);
        $stmt->bindValue(':company_id', $company_id, PDO::PARAM_STR);
        $stmt->bindValue(':price', $price, PDO::PARAM_STR);
        $stmt->bindValue(':quantity', $quantity, PDO::PARAM_STR);
        $stmt->bindValue(':discount', $discount, PDO::PARAM_STR);
        $stmt->bindValue(':inclusions', $inclusions, PDO::PARAM_STR);
        $stmt->bindValue(':delivery_address', $delivery_address, PDO::PARAM_STR);
        $stmt->bindValue(':delivery_instructions', $delivery_instructions, PDO::PARAM_STR);
        $stmt->bindValue(':delivery_conditions', $delivery_conditions, PDO::PARAM_STR);
        $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':expiration_date', $expiration_date, PDO::PARAM_STR);
        $stmt->bindValue(':pre_approve', $pre_approve, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

        $stmt->execute();

        return $this->db->getConnection()->lastInsertId();
    }

    public function insertCIF(
        $lead_id,
        $leads_product_inquiry_id,
        $cif_reference_no,
        $company_id,
        $add_ons,
        $remarks,
        $lastLogBy
    ) {

        $stmt = $this->db->getConnection()->prepare('
            INSERT INTO cif (
                lead_id,
                leads_product_inquiry_id,
                cif_reference_no,
                company_id,
                add_ons,
                remarks,
                last_log_by
            )
            VALUES (
                :lead_id,
                :leads_product_inquiry_id,
                :cif_reference_no,
                :company_id,
                :add_ons,
                :remarks,
                :last_log_by
            )
        ');

        $stmt->bindValue(':lead_id', $lead_id, PDO::PARAM_INT);
        $stmt->bindValue(':leads_product_inquiry_id', $leads_product_inquiry_id, PDO::PARAM_INT);
        $stmt->bindValue(':cif_reference_no', $cif_reference_no, PDO::PARAM_STR);
        $stmt->bindValue(':company_id', $company_id, PDO::PARAM_STR);
        $stmt->bindValue(':add_ons', $add_ons, PDO::PARAM_STR);
        $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

        $stmt->execute();

        return $this->db->getConnection()->lastInsertId();
    }

    public function insertLeadProductInquiry(
        $lead_id,
        $product_id,
        $product_subcategory_id,
        $unit_description,
        $engine_no,
        $chassis_no,
        $color,
        $year_model,
        $transmission_type,
        $length,
        $length_unit,
        $lastLogBy
    ) {

        $stmt = $this->db->getConnection()->prepare('
            INSERT INTO leads_product_inquiry (
                lead_id,
                product_id,
                product_subcategory_id,
                unit_description,
                engine_no,
                chassis_no,
                color,
                year_model,
                transmission_type,
                length,
                length_unit,
                last_log_by
            )
            VALUES (
                :lead_id,
                :product_id,
                :product_subcategory_id,
                :unit_description,
                :engine_no,
                :chassis_no,
                :color,
                :year_model,
                :transmission_type,
                :length,
                :length_unit,
                :last_log_by
            )
        ');

        $stmt->bindValue(':lead_id', $lead_id, PDO::PARAM_INT);
        $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindValue(':product_subcategory_id', $product_subcategory_id, PDO::PARAM_INT);
        $stmt->bindValue(':unit_description', $unit_description, PDO::PARAM_STR);
        $stmt->bindValue(':engine_no', $engine_no, PDO::PARAM_STR);
        $stmt->bindValue(':chassis_no', $chassis_no, PDO::PARAM_STR);
        $stmt->bindValue(':color', $color, PDO::PARAM_STR);
        $stmt->bindValue(':year_model', $year_model, PDO::PARAM_STR);
        $stmt->bindValue(':transmission_type', $transmission_type, PDO::PARAM_STR);
        $stmt->bindValue(':length', $length, PDO::PARAM_STR);
        $stmt->bindValue(':length_unit', $length_unit, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertSPAF(
        $cif_id,
        $lead_id,
        $leads_product_inquiry_id,
        $supplier_id,
        $unit_price,
        $dealer_discount,
        $net_price,
        $remarks,
        $lastLogBy
    ) {
        // Automatically generate a reference number since spaf_reference_no is NOT NULL
        $spaf_reference_no = 'SPAF-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        $stmt = $this->db->getConnection()->prepare('
            INSERT INTO spaf (
                cif_id,
                lead_id,
                leads_product_inquiry_id,
                supplier_id,
                spaf_reference_no,
                unit_price,
                dealer_discount,
                net_price,
                remarks,
                last_log_by
            )
            VALUES (
                :cif_id,
                :lead_id,
                :leads_product_inquiry_id,
                :supplier_id,
                :spaf_reference_no,
                :unit_price,
                :dealer_discount,
                :net_price,
                :remarks,
                :last_log_by
            )
        ');

        $stmt->bindValue(':cif_id', $cif_id, PDO::PARAM_INT);
        $stmt->bindValue(':lead_id', $lead_id, PDO::PARAM_INT);
        $stmt->bindValue(':leads_product_inquiry_id', $leads_product_inquiry_id, PDO::PARAM_INT);
        $stmt->bindValue(':supplier_id', $supplier_id, PDO::PARAM_INT);
        $stmt->bindValue(':spaf_reference_no', $spaf_reference_no, PDO::PARAM_STR);
        $stmt->bindValue(':unit_price', $unit_price, PDO::PARAM_STR);
        $stmt->bindValue(':dealer_discount', $dealer_discount, PDO::PARAM_STR);
        $stmt->bindValue(':net_price', $net_price, PDO::PARAM_STR);
        $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);
        
        $stmt->execute();
        return $this->db->getConnection()->lastInsertId();
    }

    public function updateSPAF(
        $spaf_id,
        $cif_id,
        $lead_id,
        $leads_product_inquiry_id,
        $supplier_id,
        $unit_price,
        $dealer_discount,
        $net_price,
        $remarks,
        $lastLogBy
    ) {
        $stmt = $this->db->getConnection()->prepare('
            UPDATE spaf SET
                cif_id = :cif_id,
                lead_id = :lead_id,
                leads_product_inquiry_id = :leads_product_inquiry_id,
                supplier_id = :supplier_id,
                unit_price = :unit_price,
                dealer_discount = :dealer_discount,
                net_price = :net_price,
                remarks = :remarks,
                last_log_by = :last_log_by
            WHERE spaf_id = :spaf_id
        ');

        $stmt->bindValue(':spaf_id', $spaf_id, PDO::PARAM_INT);
        $stmt->bindValue(':cif_id', $cif_id, PDO::PARAM_INT);
        $stmt->bindValue(':lead_id', $lead_id, PDO::PARAM_INT);
        $stmt->bindValue(':leads_product_inquiry_id', $leads_product_inquiry_id, PDO::PARAM_INT);
        $stmt->bindValue(':supplier_id', $supplier_id, PDO::PARAM_INT);
        $stmt->bindValue(':unit_price', $unit_price, PDO::PARAM_STR);
        $stmt->bindValue(':dealer_discount', $dealer_discount, PDO::PARAM_STR);
        $stmt->bindValue(':net_price', $net_price, PDO::PARAM_STR);
        $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);
        
        $stmt->execute();
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
        $address,
        $cityID,
        $email,
        $phone,
        $social_media_account,
        $genderID,
        $leadStatusID,
        $inquiryTypeID,
        $leadSourceID,
        $leadPriority,
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
                address = :address,
                city_id = :city_id,
                email = :email,
                phone = :phone,
                social_media_account = :social_media_account,
                gender_id = :gender_id,
                lead_status_id = :lead_status_id,
                lead_source_id = :lead_source_id,
                lead_priority = :lead_priority,
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
        $stmt->bindValue(':address', $address, PDO::PARAM_STR);
        $stmt->bindValue(':city_id', $cityID, PDO::PARAM_INT);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindValue(':social_media_account', $social_media_account, PDO::PARAM_STR);
        $stmt->bindValue(':gender_id', $genderID, PDO::PARAM_INT);
        $stmt->bindValue(':lead_status_id', $leadStatusID, PDO::PARAM_INT);
        $stmt->bindValue(':inquiry_type_id', $inquiryTypeID, PDO::PARAM_INT);
        $stmt->bindValue(':lead_source_id', $leadSourceID, PDO::PARAM_INT);
        $stmt->bindValue(':lead_priority', $leadPriority, PDO::PARAM_STR);
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

    public function updateQuotationImage(
        $quotation_id,
        $quotation_image,
        $lastLogBy
    ) {

        $stmt = $this->db->getConnection()->prepare('
            UPDATE quotations 
            SET quotation_image = :quotation_image, 
            last_log_by = :last_log_by 
            WHERE quotation_id = :quotation_id;
        ');

        $stmt->bindValue(':quotation_id', $quotation_id, PDO::PARAM_INT);
        $stmt->bindValue(':quotation_image', $quotation_image, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function updateApprovalConfirmation(
        $quotation_id,
        $approval_confirmation,
        $lastLogBy
    ) {

        $stmt = $this->db->getConnection()->prepare('
            UPDATE quotations 
            SET approval_confirmation = :approval_confirmation, 
            last_log_by = :last_log_by 
            WHERE quotation_id = :quotation_id;
        ');

        $stmt->bindValue(':quotation_id', $quotation_id, PDO::PARAM_INT);
        $stmt->bindValue(':approval_confirmation', $approval_confirmation, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function updateCIFImage(
        $cif_id,
        $cif_image,
        $lastLogBy
    ) {

        $stmt = $this->db->getConnection()->prepare('
            UPDATE cif 
            SET cif_image = :cif_image, 
            last_log_by = :last_log_by 
            WHERE cif_id = :cif_id;
        ');

        $stmt->bindValue(':cif_id', $cif_id, PDO::PARAM_INT);
        $stmt->bindValue(':cif_image', $cif_image, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function updateCIF(
        $cif_id, 
        $company_id, 
        $add_ons, 
        $remarks, 
        $lastLogBy
    ) {

        $stmt = $this->db->getConnection()->prepare('
            UPDATE cif
            SET
                company_id = :company_id,
                add_ons = :add_ons,
                remarks = :remarks, 
            last_log_by = :last_log_by 
            WHERE cif_id = :cif_id
        ');

        $stmt->bindValue(':cif_id', $cif_id, PDO::PARAM_INT);
        $stmt->bindValue(':company_id', $company_id, PDO::PARAM_STR);
        $stmt->bindValue(':add_ons', $add_ons, PDO::PARAM_STR);
        $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

        $stmt->execute();
    }


    public function updateQuotation(
        $quotation_id, 
        $company_id, 
        $price, 
        $quantity, 
        $discount, 
        $inclusions, 
        $delivery_address, 
        $delivery_instructions, 
        $delivery_conditions, 
        $remarks, 
        $expiration_date,
        $pre_approve,
        $lastLogBy
    ) {

        $stmt = $this->db->getConnection()->prepare('
            UPDATE quotations
            SET
                company_id = :company_id,
                inclusions = :inclusions,
                price = :price,
                quantity = :quantity,
                discount = :discount,
                delivery_address = :delivery_address,
                delivery_instructions = :delivery_instructions,
                delivery_conditions = :delivery_conditions,
                remarks = :remarks,
                expiration_date = :expiration_date,
                pre_approve = :pre_approve,
                last_log_by = :last_log_by
            WHERE quotation_id = :quotation_id
        ');

        $stmt->bindValue(':quotation_id', $quotation_id, PDO::PARAM_INT);
        $stmt->bindValue(':company_id', $company_id, PDO::PARAM_STR);
        $stmt->bindValue(':price', $price, PDO::PARAM_STR);
        $stmt->bindValue(':quantity', $quantity, PDO::PARAM_STR);
        $stmt->bindValue(':discount', $discount, PDO::PARAM_STR);
        $stmt->bindValue(':inclusions', $inclusions, PDO::PARAM_STR);
        $stmt->bindValue(':delivery_address', $delivery_address, PDO::PARAM_STR);
        $stmt->bindValue(':delivery_instructions', $delivery_instructions, PDO::PARAM_STR);
        $stmt->bindValue(':delivery_conditions', $delivery_conditions, PDO::PARAM_STR);
        $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':expiration_date', $expiration_date, PDO::PARAM_STR);
        $stmt->bindValue(':pre_approve', $pre_approve, PDO::PARAM_STR);
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

    public function forApprovalQuotation(
        $quotation_id,
        $last_log_by
    ) {

        $stmt = $this->db->getConnection()->prepare('
            UPDATE quotations
            SET
                status = "For Approval",
                for_approval_date = NOW(),
                last_log_by = :last_log_by
            WHERE quotation_id = :quotation_id
        ');

        $stmt->bindValue(':quotation_id', $quotation_id, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function forCheckingQuotation(
        $quotation_id,
        $last_log_by
    ) {

        $stmt = $this->db->getConnection()->prepare('
            UPDATE quotations
            SET
                status = "For Checking",
                for_checking_date = NOW(),
                last_log_by = :last_log_by
            WHERE quotation_id = :quotation_id
        ');

        $stmt->bindValue(':quotation_id', $quotation_id, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function draftQuotation(
        $quotation_id,
        $last_log_by
    ) {

        $stmt = $this->db->getConnection()->prepare('
            UPDATE quotations
            SET
                status = "Draft",
                last_log_by = :last_log_by
            WHERE quotation_id = :quotation_id
        ');

        $stmt->bindValue(':quotation_id', $quotation_id, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function approveQuotation(
        $quotation_id,
        $last_log_by
    ) {

        $stmt = $this->db->getConnection()->prepare('
            UPDATE quotations
            SET
                status = "Approved",
                approval_date = NOW(),
                last_log_by = :last_log_by
            WHERE quotation_id = :quotation_id
        ');

        $stmt->bindValue(':quotation_id', $quotation_id, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function completeCIF(
        $cif_id,
        $last_log_by
    ) {

        $stmt = $this->db->getConnection()->prepare('
            UPDATE cif
            SET
                status = "Completed",
                completed_date = NOW(),
                last_log_by = :last_log_by
            WHERE cif_id = :cif_id
        ');

        $stmt->bindValue(':cif_id', $cif_id, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function onProcessCIF(
        $cif_id,
        $last_log_by
    ) {

        $stmt = $this->db->getConnection()->prepare('
            UPDATE cif
            SET
                status = "On-Process",
                on_process_date = NOW(),
                last_log_by = :last_log_by
            WHERE cif_id = :cif_id
        ');

        $stmt->bindValue(':cif_id', $cif_id, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function deleteCIFSuppliers(
        $cif_id
    ) {
        $stmt = $this->db->getConnection()->prepare('
            DELETE FROM cif_supplier 
            WHERE cif_id = :cif_id
        ');

        $stmt->bindValue(':cif_id', $cif_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertCIFSupplier(
        $cif_id,
        $supplier_id,
        $last_log_by
    ) {
        $stmt = $this->db->getConnection()->prepare('
            INSERT INTO cif_supplier (
                cif_id,
                supplier_id,
                last_log_by
            ) VALUES (
                :cif_id,
                :supplier_id,
                :last_log_by
            )
        ');

        $stmt->bindValue(':cif_id', $cif_id, PDO::PARAM_INT);
        $stmt->bindValue(':supplier_id', $supplier_id, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function getCifSuppliersWithDetails($cif_id) {
        // 1. Get suppliers
        $stmt = $this->db->getConnection()->prepare('
            SELECT cs.cif_supplier_id, cs.supplier_id, cs.supplier_quotation, cs.remarks, cs.quoted_amount, s.supplier_name
            FROM cif_supplier cs
            JOIN supplier s ON cs.supplier_id = s.supplier_id
            WHERE cs.cif_id = :cif_id
        ');
        $stmt->bindValue(':cif_id', $cif_id, PDO::PARAM_INT);
        $stmt->execute();
        $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2. For each supplier, fetch their related SPAFs for this CIF
        foreach ($suppliers as &$sup) {
            $spafStmt = $this->db->getConnection()->prepare('
                SELECT spaf_id, spaf_reference_no, unit_price, net_price, created_at 
                FROM spaf 
                WHERE cif_id = :cif_id AND supplier_id = :supplier_id
            ');
            $spafStmt->bindValue(':cif_id', $cif_id, PDO::PARAM_INT);
            $spafStmt->bindValue(':supplier_id', $sup['supplier_id'], PDO::PARAM_INT);
            $spafStmt->execute();
            $sup['spafs'] = $spafStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $suppliers;
    }

public function getCifSupplierById($cif_supplier_id) {
    $stmt = $this->db->getConnection()->prepare('
        SELECT * FROM cif_supplier WHERE cif_supplier_id = :cif_supplier_id
    ');
    $stmt->bindValue(':cif_supplier_id', $cif_supplier_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function updateCifSupplierQuotation($cif_supplier_id, $supplier_quotation, $quoted_amount, $remarks, $last_log_by) {
    $stmt = $this->db->getConnection()->prepare('
        UPDATE cif_supplier 
        SET supplier_quotation = :supplier_quotation, 
            quoted_amount = :quoted_amount, 
            remarks = :remarks, 
            last_log_by = :last_log_by 
        WHERE cif_supplier_id = :cif_supplier_id
    ');
    $stmt->bindValue(':supplier_quotation', $supplier_quotation, PDO::PARAM_STR);
    $stmt->bindValue(':quoted_amount', $quoted_amount, PDO::PARAM_STR);
    $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
    $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);
    $stmt->bindValue(':cif_supplier_id', $cif_supplier_id, PDO::PARAM_INT);
    $stmt->execute();
}

    public function approveCIF(
        $spaf_id,
        $last_log_by
    ) {

        $stmt = $this->db->getConnection()->prepare('
            UPDATE spaf
            SET
                status = "Approved",
                approval_date = NOW(),
                last_log_by = :last_log_by
            WHERE spaf_id = :spaf_id
        ');

        $stmt->bindValue(':spaf_id', $spaf_id, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function forApprovalCIF(
        $spaf_id,
        $last_log_by
    ) {

        $stmt = $this->db->getConnection()->prepare('
            UPDATE spaf
            SET
                status = "For Approval",
                for_approval_date = NOW(),
                last_log_by = :last_log_by
            WHERE spaf_id = :spaf_id
        ');

        $stmt->bindValue(':spaf_id', $spaf_id, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function cancelQuotation(
        $quotation_id,
        $last_log_by
    ) {

        $stmt = $this->db->getConnection()->prepare('
            UPDATE quotations
            SET
                status = "Cancelled",
                cancellation_date = NOW(),
                last_log_by = :last_log_by
            WHERE quotation_id = :quotation_id
        ');

        $stmt->bindValue(':quotation_id', $quotation_id, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function cancelCIF(
        $cif_id,
        $last_log_by
    ) {

        $stmt = $this->db->getConnection()->prepare('
            UPDATE cif
            SET
                status = "Cancelled",
                cancellation_date = NOW(),
                last_log_by = :last_log_by
            WHERE cif_id = :cif_id
        ');

        $stmt->bindValue(':cif_id', $cif_id, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function cancelSPAF(
        $spaf_id,
        $last_log_by
    ) {

        $stmt = $this->db->getConnection()->prepare('
            UPDATE spaf
            SET
                status = "Cancelled",
                cancellation_date = NOW(),
                last_log_by = :last_log_by
            WHERE spaf_id = :spaf_id
        ');

        $stmt->bindValue(':spaf_id', $spaf_id, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function deleteProductInquiry($leads_product_inquiry_id) {
        $stmt = $this->db->getConnection()->prepare('
            DELETE FROM leads_product_inquiry 
            WHERE leads_product_inquiry_id = :leads_product_inquiry_id
        ');

        $stmt->bindValue(':leads_product_inquiry_id', $leads_product_inquiry_id, PDO::PARAM_INT);
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
    public function deleteQuotation($quotation_id) {
        $stmt = $this->db->getConnection()->prepare('
            DELETE FROM quotations 
            WHERE quotation_id = :quotation_id
        ');

        $stmt->bindValue(':quotation_id', $quotation_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deleteCIF($cif_id) {
        $stmt = $this->db->getConnection()->prepare('
            DELETE FROM cif 
            WHERE cif_id = :cif_id
        ');

        $stmt->bindValue(':cif_id', $cif_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deleteSpaf($spaf_id) {
        $stmt = $this->db->getConnection()->prepare('
            DELETE FROM spaf 
            WHERE spaf_id = :spaf_id
        ');

        $stmt->bindValue(':spaf_id', $spaf_id, PDO::PARAM_INT);
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

    public function getLeadInquiryDetails($leads_product_inquiry_id) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT 
                *
            FROM leads_product_inquiry
            WHERE leads_product_inquiry_id = :leads_product_inquiry_id
        ');

        $stmt->bindValue(':leads_product_inquiry_id', $leads_product_inquiry_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getQuotation($quotation_id) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT 
                *
            FROM quotations
            WHERE quotation_id = :quotation_id
        ');

        $stmt->bindValue(':quotation_id', $quotation_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCIF($cif_id) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT 
                *
            FROM cif
            WHERE cif_id = :cif_id
        ');

        $stmt->bindValue(':cif_id', $cif_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCifSuppliers($cif_id) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT supplier_id 
            FROM cif_supplier 
            WHERE cif_id = :cif_id
        ');

        $stmt->bindValue(':cif_id', $cif_id, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch just a flat array of supplier IDs (e.g., [1, 4, 12])
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getCifSupplierDetails($cif_id, $supplier_id) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT * 
            FROM cif_supplier 
            WHERE cif_id = :cif_id AND supplier_id = :supplier_id
        ');

        $stmt->bindValue(':cif_id', $cif_id, PDO::PARAM_INT);
        $stmt->bindValue(':supplier_id', $supplier_id, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch just a flat array of supplier IDs (e.g., [1, 4, 12])
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSpaf($spaf_id) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT 
                *
            FROM spaf
            WHERE spaf_id = :spaf_id
        ');

        $stmt->bindValue(':spaf_id', $spaf_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProductInquiry($leads_product_inquiry_id) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT 
                *
            FROM leads_product_inquiry
            WHERE leads_product_inquiry_id = :leads_product_inquiry_id
        ');

        $stmt->bindValue(':leads_product_inquiry_id', $leads_product_inquiry_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getLastNote($leadID) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT 
                *
            FROM lead_notes
            WHERE lead_id = :lead_id
            ORDER BY created_at DESC
            LIMIT 1
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
        $filter_inquiry_type,
        $filter_lead_source,
        $filter_lead_priority,
        $filter_assigned_to
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

        if (!empty($filter_assigned_to)) {

            $query .= " AND assigned_to IN ($filter_assigned_to)";
        }

        /* =========================
            LEAD SOURCE FILTER
        ========================== */
        if (!empty($filter_lead_source)) {

            $query .= " AND lead_source_id IN ($filter_lead_source)";
        }

        /* =========================
            LEAD PRIORITY FILTER
        ========================== */
        if (!empty($filter_lead_priority)) {

            $query .= " AND lead_priority IN ($filter_lead_priority)";
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
        $filter_lead_source,
        $filter_lead_priority,
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
            LEAD SOURCE FILTER
        ========================== */
        if (!empty($filter_lead_source)) {

            $query .= " AND lead_source_id IN ($filter_lead_source)";
        }

        /* =========================
            LEAD PRIORITY FILTER
        ========================== */
        if (!empty($filter_lead_priority)) {

            $query .= " AND lead_priority IN ($filter_lead_priority)";
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

    public function generateCIFOptions() {
        $stmt = $this->db->getConnection()->prepare('
            SELECT c.cif_id, c.cif_reference_no, l.file_as 
            FROM cif c
            JOIN leads l ON l.lead_id = c.lead_id 
            WHERE c.status = "Submitted"
            ORDER BY l.file_as ASC
        ');

        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';

        foreach ($options as $row) {
            $htmlOptions .= '
                <option value="' . htmlspecialchars($row['cif_id'], ENT_QUOTES) . '">
                    ' . htmlspecialchars($row['cif_reference_no'], ENT_QUOTES) . ' - ' . htmlspecialchars($row['file_as'], ENT_QUOTES) . '
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

    public function generateSPAFTable(
        $filter_status
    ) {

        $query = '
            SELECT *
            FROM spaf
            WHERE 1=1
        ';

        $params = [];

        if (!empty($filter_status)) {

            $query .= " AND status IN ($filter_status)";
        }

        $query .= ' ORDER BY spaf_id DESC';

        $stmt = $this->db->getConnection()->prepare($query);

        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function generateCIFTable(
        $filter_status
    ) {

        $query = '
            SELECT *
            FROM cif
            WHERE 1=1
        ';

        $params = [];

        if (!empty($filter_status)) {

            $query .= " AND status IN ($filter_status)";
        }

        $query .= ' ORDER BY cif_id DESC';

        $stmt = $this->db->getConnection()->prepare($query);

        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function generateCreatedByCheckBox() {
        $stmt = $this->db->getConnection()->prepare('
            SELECT DISTINCT(assigned_to) assigned_to, users.file_as as file_as
            FROM leads 
            LEFT JOIN users ON users.user_id = leads.assigned_to
            ORDER BY users.file_as ASC
        ');

        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';

        foreach ($options as $row) {
            $assigned_to = htmlspecialchars($row['assigned_to'], ENT_QUOTES);
            $file_as = htmlspecialchars($row['file_as'], ENT_QUOTES);

            $htmlOptions .= '
                <div class="form-check my-2">
                    <input class="form-check-input assigned-to-filter" 
                        type="checkbox" 
                        id="assigned-to-' . $assigned_to . '" 
                        value="' . $assigned_to . '" />
                    <label class="form-check-label" for="assigned-to-' . $assigned_to . '">
                        ' . strtoupper($file_as) . '
                    </label>
                </div>
            ';
        }

        return $htmlOptions;
    }
}