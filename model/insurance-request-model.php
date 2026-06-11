<?php

class InsuranceRequestModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    /* -----------------------------------------------
    | CHECK LEAD STATUS EXISTENCE
    |----------------------------------------------- */
    public function checkInsuranceRequestExist($insuranceRequestID) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT COUNT(*) AS total
            FROM insurance_request
            WHERE insurance_request_id = :insurance_request_id
        ');

        $stmt->bindValue(':insurance_request_id', $insuranceRequestID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | INSERT LEAD STATUS
    |----------------------------------------------- */
    public function insertInsuranceRequest(
    $requestType,
    $inceptionDate,
    $insuranceProviderId,
    $insuranceTypeId,
    $customerType,
    $customerId,
    $salesProposalId,
    $yearModel,
    $color,
    $make,
    $plateNumber,
    $chassisNumber,
    $engineNumber,
    $mvFileNumber,
    $lastLogBy
) {
    // Generate a default request number block or temporary draft code if your system requires it
    $requestNumber = 'REQ-' . time(); 

    $stmt = $this->db->getConnection()->prepare('
        INSERT INTO insurance_request (
            request_number,
            request_type,
            inception_date,
            insurance_provider,
            insurance_type_id,
            customer_type,
            customer_id,
            sales_proposal_id,
            year_model,
            color,
            make,
            plate_number,
            chassis_number,
            engine_number,
            mv_file_number,
            last_log_by
        )
        VALUES (
            :request_number,
            :request_type,
            :inception_date,
            :insurance_provider,
            :insurance_type_id,
            :customer_type,
            :customer_id,
            :sales_proposal_id,
            :year_model,
            :color,
            :make,
            :plate_number,
            :chassis_number,
            :engine_number,
            :mv_file_number,
            :last_log_by
        )
    ');

    $stmt->bindValue(':request_number', $requestNumber, PDO::PARAM_STR);
    $stmt->bindValue(':request_type', $requestType, PDO::PARAM_STR);
    $stmt->bindValue(':inception_date', $inceptionDate, PDO::PARAM_STR);
    $stmt->bindValue(':insurance_provider', $insuranceProviderId, PDO::PARAM_INT);
    $stmt->bindValue(':insurance_type_id', $insuranceTypeId, PDO::PARAM_INT);
    $stmt->bindValue(':customer_type', $customerType, PDO::PARAM_STR);
    $stmt->bindValue(':customer_id', !empty($customerId) ? $customerId : null, PDO::PARAM_INT);
    $stmt->bindValue(':sales_proposal_id', !empty($salesProposalId) ? $salesProposalId : null, PDO::PARAM_INT);
    
    // Vehicle parameters binding
    $stmt->bindValue(':year_model', !empty($yearModel) ? $yearModel : null, PDO::PARAM_STR);
    $stmt->bindValue(':color', !empty($color) ? $color : null, PDO::PARAM_STR);
    $stmt->bindValue(':make', !empty($make) ? $make : null, PDO::PARAM_STR);
    $stmt->bindValue(':plate_number', !empty($plateNumber) ? $plateNumber : null, PDO::PARAM_STR);
    $stmt->bindValue(':chassis_number', !empty($chassisNumber) ? $chassisNumber : null, PDO::PARAM_STR);
    $stmt->bindValue(':engine_number', !empty($engineNumber) ? $engineNumber : null, PDO::PARAM_STR);
    $stmt->bindValue(':mv_file_number', !empty($mvFileNumber) ? $mvFileNumber : null, PDO::PARAM_STR);
    
    $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

    $stmt->execute();

    return $this->db->getConnection()->lastInsertId();
}

    /* -----------------------------------------------
    | UPDATE LEAD STATUS
    |----------------------------------------------- */
    public function updateInsuranceRequest(
        $insuranceRequestID,
        $requestType,
        $inceptionDate,
        $insuranceProviderId,
        $insuranceTypeId,
        $customerType,
        $customerId,
        $salesProposalId,
        $yearModel,
        $color,
        $make,
        $plateNumber,
        $chassisNumber,
        $engineNumber,
        $mvFileNumber,
        $lastLogBy
    ) {
        $stmt = $this->db->getConnection()->prepare('
            UPDATE insurance_request
            SET
                request_type = :request_type,
                inception_date = :inception_date,
                insurance_provider = :insurance_provider_id,
                insurance_type_id = :insurance_type_id,
                customer_type = :customer_type,
                customer_id = :customer_id,
                sales_proposal_id = :sales_proposal_id,
                year_model = :year_model,
                color = :color,
                make = :make,
                plate_number = :plate_number,
                chassis_number = :chassis_number,
                engine_number = :engine_number,
                mv_file_number = :mv_file_number,
                last_log_by = :last_log_by
            WHERE insurance_request_id = :insurance_request_id
        ');

        $stmt->bindValue(':insurance_request_id', $insuranceRequestID, PDO::PARAM_INT);
        $stmt->bindValue(':request_type', $requestType, PDO::PARAM_STR);
        $stmt->bindValue(':inception_date', $inceptionDate, PDO::PARAM_STR);
        $stmt->bindValue(':insurance_provider_id', $insuranceProviderId, PDO::PARAM_INT);
        $stmt->bindValue(':insurance_type_id', $insuranceTypeId, PDO::PARAM_INT);
        $stmt->bindValue(':customer_type', $customerType, PDO::PARAM_STR);
        $stmt->bindValue(':customer_id', !empty($customerId) ? $customerId : null, PDO::PARAM_INT);
        $stmt->bindValue(':sales_proposal_id', !empty($salesProposalId) ? $salesProposalId : null, PDO::PARAM_INT);
        
        // Vehicle parameters binding
        $stmt->bindValue(':year_model', !empty($yearModel) ? $yearModel : null, PDO::PARAM_STR);
        $stmt->bindValue(':color', !empty($color) ? $color : null, PDO::PARAM_STR);
        $stmt->bindValue(':make', !empty($make) ? $make : null, PDO::PARAM_STR);
        $stmt->bindValue(':plate_number', !empty($plateNumber) ? $plateNumber : null, PDO::PARAM_STR);
        $stmt->bindValue(':chassis_number', !empty($chassisNumber) ? $chassisNumber : null, PDO::PARAM_STR);
        $stmt->bindValue(':engine_number', !empty($engineNumber) ? $engineNumber : null, PDO::PARAM_STR);
        $stmt->bindValue(':mv_file_number', !empty($mvFileNumber) ? $mvFileNumber : null, PDO::PARAM_STR);
        
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function updateInsuranceRequestComputation($insuranceRequestID, array $data) {
        $stmt = $this->db->getConnection()->prepare('
            UPDATE insurance_request
            SET
                insurance_category  = :insurance_category,
                ctpl_coverage       = :ctpl_coverage,
                od_theft_coverage   = :od_theft_coverage,
                aon_coverage        = :aon_coverage,
                tpbi_coverage       = :tpbi_coverage,
                tppd_coverage       = :tppd_coverage,
                par_coverage        = :par_coverage,
                
                ctpl_premium        = :ctpl_premium,
                od_theft_premium    = :od_theft_premium,
                aon_premium         = :aon_premium,
                tpbi_premium        = :tpbi_premium,
                tppd_premium        = :tppd_premium,
                par_premium         = :par_premium,
                
                total_premium       = :total_premium,
                vat_premium_tax     = :vat_premium_tax,
                docstamp            = :docstamp,
                local_tax           = :local_tax,
                gross               = :gross,
                net_premium         = :net_premium,
                
                premium_comission   = :premium_comission,
                aon_comission       = :aon_comission,
                tpbi_comission      = :tpbi_comission,
                tppd_comission      = :tppd_comission,
                par_comission       = :par_comission,
                comission_subtotal  = :comission_subtotal,
                commission_discount = :commission_discount,
                net_commission      = :net_commission,
                last_log_by         = :last_log_by
            WHERE insurance_request_id = :insurance_request_id
        ');

        // Bind the WHERE filter target
        $stmt->bindValue(':insurance_request_id', (int)$insuranceRequestID, PDO::PARAM_INT);

        // Iteratively bind all data attributes parsed out by our controller block
        foreach ($data as $key => $value) {
            if ($key === 'insurance_category') {
                $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
            } elseif ($key === 'last_log_by') {
                $stmt->bindValue(':' . $key, $value, PDO::PARAM_INT);
            } else {
                // All coverage calculations, premiums, taxes, and net scales map as numeric doubles
                $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR); 
            }
        }

        return $stmt->execute();
    }

    public function updateInsuranceRequestStatus($insuranceRequestID, $status, $userID) {
        // 1. Map statuses to their respective database timestamp columns
        $timestampFields = [
            'Draft'          => null, // Uses default updated_at trigger
            'For Submission' => 'for_submission_date',
            'Submitted'      => 'submitted_date',
            'Received'       => 'received_date'
        ];

        // 2. Build the dynamic SQL query
        $timestampColumn = $timestampFields[$status] ?? null;
        
        $sql = 'UPDATE insurance_request 
                SET status = :status, 
                    last_log_by = :last_log_by';
        
        // If the status has a specific timestamp column, update it to the current time
        if ($timestampColumn) {
            $sql .= ", {$timestampColumn} = NOW()";
        }

        $sql .= ' WHERE insurance_request_id = :insurance_request_id';

        // 3. Prepare and bind values safely
        $stmt = $this->db->getConnection()->prepare($sql);
        
        $stmt->bindValue(':insurance_request_id', (int)$insuranceRequestID, PDO::PARAM_INT);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', (int)$userID, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /* -----------------------------------------------
    | DELETE LEAD STATUS
    |----------------------------------------------- */
    public function deleteInsuranceRequest($insuranceRequestID) {
        $stmt = $this->db->getConnection()->prepare('
            DELETE FROM insurance_request
            WHERE insurance_request_id = :insurance_request_id
        ');

        $stmt->bindValue(':insurance_request_id', $insuranceRequestID, PDO::PARAM_INT);
        $stmt->execute();
    }

    /* -----------------------------------------------
    | GET SINGLE LEAD STATUS
    |----------------------------------------------- */
    public function getInsuranceRequest($insuranceRequestID) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT *
            FROM insurance_request
            WHERE insurance_request_id = :insurance_request_id
        ');

        $stmt->bindValue(':insurance_request_id', $insuranceRequestID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | GET ALL LEAD STATUSES (FOR DROPDOWN)
    |----------------------------------------------- */
    public function getInsuranceRequestes() {
        $stmt = $this->db->getConnection()->prepare('
            SELECT 
                insurance_request_id,
                insurance_request_name
            FROM insurance_request
            ORDER BY insurance_request_name ASC
        ');

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | GENERATE OPTIONS (SELECT DROPDOWN)
    |----------------------------------------------- */
    public function generateInsuranceRequestOptions() {
        $options = $this->getInsuranceRequestes();

        $html = '';

        foreach ($options as $row) {
            $html .= '
                <option value="' . htmlspecialchars($row['insurance_request_id'], ENT_QUOTES) . '">
                    ' . htmlspecialchars($row['insurance_request_name'], ENT_QUOTES) . '
                </option>
            ';
        }

        return $html;
    }

    /* -----------------------------------------------
    | GENERATE CHECKBOX FILTER
    |----------------------------------------------- */
    public function generateInsuranceRequestCheckBox() {
        $options = $this->getInsuranceRequestes();

        $html = '';

        foreach ($options as $row) {
            $id = htmlspecialchars($row['insurance_request_id'], ENT_QUOTES);
            $name = htmlspecialchars($row['insurance_request_name'], ENT_QUOTES);

            $html .= '
                <div class="form-check my-2">
                    <input class="form-check-input insurance-request-filter" 
                        request="checkbox" 
                        id="insurance-request-' . $id . '" 
                        value="' . $id . '" />
                    <label class="form-check-label" for="insurance-request-' . $id . '">
                        ' . $name . '
                    </label>
                </div>
            ';
        }

        return $html;
    }
}