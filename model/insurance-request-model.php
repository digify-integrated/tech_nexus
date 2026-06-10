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
        $lastLogBy
    ) {
        $stmt = $this->db->getConnection()->prepare('
            INSERT INTO insurance_request (
                request_type,
                inception_date,
                insurance_provider,
                insurance_type_id,
                customer_type,
                customer_id,
                sales_proposal_id,
                last_log_by
            )
            VALUES (
                :request_type,
                :inception_date,
                :insurance_provider,
                :insurance_type_id,
                :customer_type,
                :customer_id,
                :sales_proposal_id,
                :last_log_by
            )
        ');

        $stmt->bindValue(':request_type', $requestType, PDO::PARAM_STR);
        $stmt->bindValue(':inception_date', $inceptionDate, PDO::PARAM_STR);
        $stmt->bindValue(':insurance_provider', $insuranceProviderId, PDO::PARAM_INT);
        $stmt->bindValue(':insurance_type_id', $insuranceTypeId, PDO::PARAM_INT);
        $stmt->bindValue(':customer_type', $customerType, PDO::PARAM_STR);
        $stmt->bindValue(':customer_id', !empty($customerId) ? $customerId : null, PDO::PARAM_INT);
        $stmt->bindValue(':sales_proposal_id', !empty($salesProposalId) ? $salesProposalId : null, PDO::PARAM_INT);
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
        $stmt->bindValue(':last_log_by', $lastLogBy, PDO::PARAM_INT);

        $stmt->execute();
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