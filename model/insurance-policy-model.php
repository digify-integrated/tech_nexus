<?php

class InsurancePolicyModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    /* -----------------------------------------------
    | CHECK LEAD STATUS EXISTENCE
    |----------------------------------------------- */
    public function checkInsurancePolicyExist($insurancePolicyID) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT COUNT(*) AS total
            FROM insurance_policy
            WHERE insurance_policy_id = :insurance_policy_id
        ');

        $stmt->bindValue(':insurance_policy_id', $insurancePolicyID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertInsurancePolicy(
        $insurance_policy_id,
        $policy_number,
        $premium_amount,
        $coverage_amount,
        $inception_date,
        $expiry_date,
        $remarks,
        $lastLogBy
    ) {
        $stmt = $this->db->getConnection()->prepare('
            INSERT INTO insurance_policy (
                insurance_policy_id,
                policy_number,
                inception_date,
                expiry_date,
                premium_amount,
                coverage_amount,
                remarks,
                last_log_by
            )
            VALUES (
                :insurance_policy_id,
                :policy_number,
                :inception_date,
                :expiry_date,
                :premium_amount,
                :coverage_amount,
                :remarks,
                :last_log_by
            )
        ');

        $stmt->bindValue(':insurance_policy_id', $insurance_policy_id);
        $stmt->bindValue(':policy_number', $policy_number);
        $stmt->bindValue(':inception_date', $inception_date);
        $stmt->bindValue(':expiry_date', $expiry_date);
        $stmt->bindValue(':premium_amount', $premium_amount);
        $stmt->bindValue(':coverage_amount', $coverage_amount);
        $stmt->bindValue(':remarks', $remarks);
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

        $stmt->execute();
    }

    /* -----------------------------------------------
    | UPDATE LEAD STATUS
    |----------------------------------------------- */
    public function updateInsurancePolicy(
        $insurancePolicyID,
        $policyType,
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
            UPDATE insurance_policy
            SET
                policy_type = :policy_type,
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
            WHERE insurance_policy_id = :insurance_policy_id
        ');

        $stmt->bindValue(':insurance_policy_id', $insurancePolicyID, PDO::PARAM_INT);
        $stmt->bindValue(':policy_type', $policyType, PDO::PARAM_STR);
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

    public function updateInsurancePolicyComputation($insurancePolicyID, array $data) {
        $stmt = $this->db->getConnection()->prepare('
            UPDATE insurance_policy
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
            WHERE insurance_policy_id = :insurance_policy_id
        ');

        // Bind the WHERE filter target
        $stmt->bindValue(':insurance_policy_id', (int)$insurancePolicyID, PDO::PARAM_INT);

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

    public function updateInsurancePolicyStatus($insurancePolicyID, $cancellationReason, $userID) {
        
        $sql = 'UPDATE insurance_policy 
                SET status = "Cancelled", 
                cancellation_date = NOW(),
                cancellation_reason = :cancellation_reason,
                    last_log_by = :last_log_by';
        
        
        $sql .= ' WHERE insurance_policy_id = :insurance_policy_id';

        // 3. Prepare and bind values safely
        $stmt = $this->db->getConnection()->prepare($sql);
        
        $stmt->bindValue(':insurance_policy_id', (int)$insurancePolicyID, PDO::PARAM_INT);
        $stmt->bindValue(':cancellation_reason', $cancellationReason);
        $stmt->bindValue(':last_log_by', (int)$userID, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /* -----------------------------------------------
    | DELETE LEAD STATUS
    |----------------------------------------------- */
    public function deleteInsurancePolicy($insurancePolicyID) {
        $stmt = $this->db->getConnection()->prepare('
            DELETE FROM insurance_policy
            WHERE insurance_policy_id = :insurance_policy_id
        ');

        $stmt->bindValue(':insurance_policy_id', $insurancePolicyID, PDO::PARAM_INT);
        $stmt->execute();
    }

    /* -----------------------------------------------
    | GET SINGLE LEAD STATUS
    |----------------------------------------------- */
    public function getInsurancePolicy($insurancePolicyID) {
        $stmt = $this->db->getConnection()->prepare('
            SELECT *
            FROM insurance_policy
            WHERE insurance_policy_id = :insurance_policy_id
        ');

        $stmt->bindValue(':insurance_policy_id', $insurancePolicyID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | GET ALL LEAD STATUSES (FOR DROPDOWN)
    |----------------------------------------------- */
    public function getInsurancePolicyes() {
        $stmt = $this->db->getConnection()->prepare('
            SELECT 
                *
            FROM insurance_policy
            WHERE status = "Active"
            ORDER BY expiry_date DESC
        ');

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* -----------------------------------------------
    | GENERATE OPTIONS (SELECT DROPDOWN)
    |----------------------------------------------- */
    public function generateInsurancePolicyOptions() {
        $options = $this->getInsurancePolicyes();

        $html = '';

        foreach ($options as $row) {
            $html .= '
                <option value="' . htmlspecialchars($row['insurance_policy_id'], ENT_QUOTES) . '">
                    ' . $row['policy_number'] . ' ('. date('M d, Y', strtotime($row['expiry_date'])) .')
                </option>
            ';
        }

        return $html;
    }

    /* -----------------------------------------------
    | GENERATE CHECKBOX FILTER
    |----------------------------------------------- */
    public function generateInsurancePolicyCheckBox() {
        $options = $this->getInsurancePolicyes();

        $html = '';

        foreach ($options as $row) {
            $id = htmlspecialchars($row['insurance_policy_id'], ENT_QUOTES);
            $name = htmlspecialchars($row['policy_number'], ENT_QUOTES);
            $expiry = $row['expiry_date'];

            $html .= '
                <div class="form-check my-2">
                    <input class="form-check-input insurance-policy-filter" 
                        policy="checkbox" 
                        id="insurance-policy-' . $id . '" 
                        value="' . $id . '" />
                    <label class="form-check-label" for="insurance-policy-' . $id . '">
                        ' . $name . '
                    </label>
                </div>
            ';
        }

        return $html;
    }
}