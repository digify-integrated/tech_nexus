<?php
/**
* Class CIReportModel
*
* The CIReportModel class handles ci report related operations and interactions.
*/
class CIReportModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateCIReport
    # Description: Updates the ci report.
    #
    # Parameters:
    # - $p_ci_report_id (int): The ci report ID.
    # - $p_current_level (string): The current level.
    # - $p_rank (string): The rank.
    # - $p_functional_level (string): The functional level.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateCIReport($p_ci_report_id, $p_appraiser, $p_investigator, $p_narrative_summary, $p_purpose_of_loan, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCIReport(:p_ci_report_id, :p_appraiser, :p_investigator, :p_narrative_summary, :p_purpose_of_loan, :p_last_log_by)');
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_appraiser', $p_appraiser, PDO::PARAM_INT);
        $stmt->bindValue(':p_investigator', $p_investigator, PDO::PARAM_INT);
        $stmt->bindValue(':p_narrative_summary', $p_narrative_summary, PDO::PARAM_STR);
        $stmt->bindValue(':p_purpose_of_loan', $p_purpose_of_loan, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updateCIReportRecommendation($p_ci_report_id, $p_ci_character, $p_ci_capacity, $p_ci_capital, $p_ci_collateral, $p_ci_condition, $p_acceptability, $p_loanability, $p_cmap_result, $p_crif_result, $p_adverse, $p_times_accomodated, $p_cgmi_client_since, $p_recommendation, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCIReportRecommendation(:p_ci_report_id, :p_ci_character, :p_ci_capacity, :p_ci_capital, :p_ci_collateral, :p_ci_condition, :p_acceptability, :p_loanability, :p_cmap_result, :p_crif_result, :p_adverse, :p_times_accomodated, :p_cgmi_client_since, :p_recommendation, :p_last_log_by)');
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_ci_character', $p_ci_character, PDO::PARAM_STR);
        $stmt->bindValue(':p_ci_capacity', $p_ci_capacity, PDO::PARAM_STR);
        $stmt->bindValue(':p_ci_capital', $p_ci_capital, PDO::PARAM_STR);
        $stmt->bindValue(':p_ci_collateral', $p_ci_collateral, PDO::PARAM_STR);
        $stmt->bindValue(':p_ci_condition', $p_ci_condition, PDO::PARAM_STR);
        $stmt->bindValue(':p_acceptability', $p_acceptability, PDO::PARAM_STR);
        $stmt->bindValue(':p_loanability', $p_loanability, PDO::PARAM_STR);
        $stmt->bindValue(':p_cmap_result', $p_cmap_result, PDO::PARAM_STR);
        $stmt->bindValue(':p_crif_result', $p_crif_result, PDO::PARAM_STR);
        $stmt->bindValue(':p_adverse', $p_adverse, PDO::PARAM_STR);
        $stmt->bindValue(':p_times_accomodated', $p_times_accomodated, PDO::PARAM_STR);
        $stmt->bindValue(':p_cgmi_client_since', $p_cgmi_client_since, PDO::PARAM_STR);
        $stmt->bindValue(':p_recommendation', $p_recommendation, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updateCIReportStatus($p_ci_report_id, $p_ci_status, $p_reason, $p_sales_proposal_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCIReportStatus(:p_ci_report_id, :p_ci_status, :p_reason, :p_sales_proposal_id, :p_last_log_by)');
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_ci_status', $p_ci_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_reason', $p_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkCIReportExist
    # Description: Checks if a ci report exists.
    #
    # Parameters:
    # - $p_ci_report_id (int): The ci report ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkCIReportExist($p_ci_report_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkCIReportExist(:p_ci_report_id)');
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    # -------------------------------------------------------------
    
    public function checkCIReportResidenceExist($p_ci_report_residence_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkCIReportResidenceExist(:p_ci_report_residence_id)');
        $stmt->bindValue(':p_ci_report_residence_id', $p_ci_report_residence_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCIReportResidence($p_ci_report_residence_id, $p_ci_report_id, $p_address, $p_city_id, $p_prev_address, $p_prev_city_id, $p_length_stay_year, $p_length_stay_month, $p_residence_type_id, $p_rented_from, $p_rent_amount, $p_estimated_value, $p_structure_type_id, $p_residence_age, $p_building_make_id, $p_lot_area, $p_floor_area, $p_furnishing_appliance, $p_neighborhood_type_id, $p_income_level_id, $p_accessible_to, $p_nearest_corner, $p_informant, $p_informant_address, $p_personal_expense, $p_utilities_expense, $p_other_expense, $p_total_expense, $p_vehicle_owned, $p_real_estate_owned, $p_tct_no, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCIReportResidence(:p_ci_report_residence_id, :p_ci_report_id, :p_address, :p_city_id, :p_prev_address, :p_prev_city_id, :p_length_stay_year, :p_length_stay_month, :p_residence_type_id, :p_rented_from, :p_rent_amount, :p_estimated_value, :p_structure_type_id, :p_residence_age, :p_building_make_id, :p_lot_area, :p_floor_area, :p_furnishing_appliance, :p_neighborhood_type_id, :p_income_level_id, :p_accessible_to, :p_nearest_corner, :p_informant, :p_informant_address, :p_personal_expense, :p_utilities_expense, :p_other_expense, :p_total_expense, :p_vehicle_owned, :p_real_estate_owned, :p_tct_no, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_ci_report_residence_id', $p_ci_report_residence_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_address', $p_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_city_id', $p_city_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_prev_address', $p_prev_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_prev_city_id', $p_prev_city_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_length_stay_year', $p_length_stay_year, PDO::PARAM_INT);
        $stmt->bindValue(':p_length_stay_month', $p_length_stay_month, PDO::PARAM_INT);
        $stmt->bindValue(':p_residence_type_id', $p_residence_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_rented_from', $p_rented_from, PDO::PARAM_STR);
        $stmt->bindValue(':p_rent_amount', $p_rent_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_estimated_value', $p_estimated_value, PDO::PARAM_STR);
        $stmt->bindValue(':p_structure_type_id', $p_structure_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_residence_age', $p_residence_age, PDO::PARAM_INT);
        $stmt->bindValue(':p_building_make_id', $p_building_make_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_lot_area', $p_lot_area, PDO::PARAM_STR);
        $stmt->bindValue(':p_floor_area', $p_floor_area, PDO::PARAM_STR);
        $stmt->bindValue(':p_furnishing_appliance', $p_furnishing_appliance, PDO::PARAM_STR);
        $stmt->bindValue(':p_neighborhood_type_id', $p_neighborhood_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_income_level_id', $p_income_level_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_accessible_to', $p_accessible_to, PDO::PARAM_STR);
        $stmt->bindValue(':p_nearest_corner', $p_nearest_corner, PDO::PARAM_STR);
        $stmt->bindValue(':p_informant', $p_informant, PDO::PARAM_STR);
        $stmt->bindValue(':p_informant_address', $p_informant_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_personal_expense', $p_personal_expense, PDO::PARAM_STR);
        $stmt->bindValue(':p_utilities_expense', $p_utilities_expense, PDO::PARAM_STR);
        $stmt->bindValue(':p_other_expense', $p_other_expense, PDO::PARAM_STR);
        $stmt->bindValue(':p_total_expense', $p_total_expense, PDO::PARAM_STR);
        $stmt->bindValue(':p_vehicle_owned', $p_vehicle_owned, PDO::PARAM_STR);
        $stmt->bindValue(':p_real_estate_owned', $p_real_estate_owned, PDO::PARAM_STR);
        $stmt->bindValue(':p_tct_no', $p_tct_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertCIReportResidence($p_ci_report_id, $p_address, $p_city_id, $p_prev_address, $p_prev_city_id, $p_length_stay_year, $p_length_stay_month, $p_residence_type_id, $p_rented_from, $p_rent_amount, $p_estimated_value, $p_structure_type_id, $p_residence_age, $p_building_make_id, $p_lot_area, $p_floor_area, $p_furnishing_appliance, $p_neighborhood_type_id, $p_income_level_id, $p_accessible_to, $p_nearest_corner, $p_informant, $p_informant_address, $p_personal_expense, $p_utilities_expense, $p_other_expense, $p_total_expense, $p_vehicle_owned, $p_real_estate_owned, $p_tct_no, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertCIReportResidence(:p_ci_report_id, :p_address, :p_city_id, :p_prev_address, :p_prev_city_id, :p_length_stay_year, :p_length_stay_month, :p_residence_type_id, :p_rented_from, :p_rent_amount, :p_estimated_value, :p_structure_type_id, :p_residence_age, :p_building_make_id, :p_lot_area, :p_floor_area, :p_furnishing_appliance, :p_neighborhood_type_id, :p_income_level_id, :p_accessible_to, :p_nearest_corner, :p_informant, :p_informant_address, :p_personal_expense, :p_utilities_expense, :p_other_expense, :p_total_expense, :p_vehicle_owned, :p_real_estate_owned, :p_tct_no, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_address', $p_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_city_id', $p_city_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_prev_address', $p_prev_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_prev_city_id', $p_prev_city_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_length_stay_year', $p_length_stay_year, PDO::PARAM_INT);
        $stmt->bindValue(':p_length_stay_month', $p_length_stay_month, PDO::PARAM_INT);
        $stmt->bindValue(':p_residence_type_id', $p_residence_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_rented_from', $p_rented_from, PDO::PARAM_STR);
        $stmt->bindValue(':p_rent_amount', $p_rent_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_estimated_value', $p_estimated_value, PDO::PARAM_STR);
        $stmt->bindValue(':p_structure_type_id', $p_structure_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_residence_age', $p_residence_age, PDO::PARAM_INT);
        $stmt->bindValue(':p_building_make_id', $p_building_make_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_lot_area', $p_lot_area, PDO::PARAM_STR);
        $stmt->bindValue(':p_floor_area', $p_floor_area, PDO::PARAM_STR);
        $stmt->bindValue(':p_furnishing_appliance', $p_furnishing_appliance, PDO::PARAM_STR);
        $stmt->bindValue(':p_neighborhood_type_id', $p_neighborhood_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_income_level_id', $p_income_level_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_accessible_to', $p_accessible_to, PDO::PARAM_STR);
        $stmt->bindValue(':p_nearest_corner', $p_nearest_corner, PDO::PARAM_STR);
        $stmt->bindValue(':p_informant', $p_informant, PDO::PARAM_STR);
        $stmt->bindValue(':p_informant_address', $p_informant_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_personal_expense', $p_personal_expense, PDO::PARAM_STR);
        $stmt->bindValue(':p_utilities_expense', $p_utilities_expense, PDO::PARAM_STR);
        $stmt->bindValue(':p_other_expense', $p_other_expense, PDO::PARAM_STR);
        $stmt->bindValue(':p_total_expense', $p_total_expense, PDO::PARAM_STR);
        $stmt->bindValue(':p_vehicle_owned', $p_vehicle_owned, PDO::PARAM_STR);
        $stmt->bindValue(':p_real_estate_owned', $p_real_estate_owned, PDO::PARAM_STR);
        $stmt->bindValue(':p_tct_no', $p_tct_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteCIReportResidence($ci_report_residence_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCIReportResidence(:ci_report_residence_id)');
        $stmt->bindValue(':ci_report_residence_id', $ci_report_residence_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getCIReportResidence($p_ci_report_residence_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCIReportResidence(:p_ci_report_residence_id)');
        $stmt->bindValue(':p_ci_report_residence_id', $p_ci_report_residence_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCIReportResidenceExpenseTotal($p_ci_report_id, $p_type) {
        $stmt = $this->db->getConnection()->prepare('CALL getCIReportResidenceExpenseTotal(:p_ci_report_id, :p_type)');
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    # -------------------------------------------------------------
    
    public function checkCIReportDependentsExist($p_ci_report_dependents_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkCIReportDependentsExist(:p_ci_report_dependents_id)');
        $stmt->bindValue(':p_ci_report_dependents_id', $p_ci_report_dependents_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCIReportDependents($p_ci_report_dependents_id, $p_ci_report_id, $p_name, $p_age, $p_school, $p_employment, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCIReportDependents(:p_ci_report_dependents_id, :p_ci_report_id, :p_name, :p_age, :p_school, :p_employment, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_ci_report_dependents_id', $p_ci_report_dependents_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_name', $p_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_age', $p_age, PDO::PARAM_INT);
        $stmt->bindValue(':p_school', $p_school, PDO::PARAM_STR);
        $stmt->bindValue(':p_employment', $p_employment, PDO::PARAM_INT);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertCIReportDependents($p_ci_report_id, $p_name, $p_age, $p_school, $p_employment, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertCIReportDependents(:p_ci_report_id, :p_name, :p_age, :p_school, :p_employment, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_name', $p_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_age', $p_age, PDO::PARAM_INT);
        $stmt->bindValue(':p_school', $p_school, PDO::PARAM_STR);
        $stmt->bindValue(':p_employment', $p_employment, PDO::PARAM_INT);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteCIReportDependents($ci_report_dependents_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCIReportDependents(:ci_report_dependents_id)');
        $stmt->bindValue(':ci_report_dependents_id', $ci_report_dependents_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getCIReportDependents($p_ci_report_dependents_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCIReportDependents(:p_ci_report_dependents_id)');
        $stmt->bindValue(':p_ci_report_dependents_id', $p_ci_report_dependents_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    # -------------------------------------------------------------
    
    public function checkCIReportBusinessExist($p_ci_report_business_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkCIReportBusinessExist(:p_ci_report_business_id)');
        $stmt->bindValue(':p_ci_report_business_id', $p_ci_report_business_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertCIReportBusiness(
        $p_ci_report_id, $p_business_name, $p_description, $p_contact_address, $p_city_id,
        $p_length_stay_year, $p_length_stay_month, $p_registered_with, $p_organization, $p_date_organized,
        $p_no_employee, $p_customer, $p_major_bank_id, $p_contact_person, $p_business_location_type_id,
        $p_building_make_id, $p_business_premises_id, $p_landlord, $p_rental_amount, $p_machineries,
        $p_branches, $p_fixtures, $p_facility_condition, $p_vehicle, $p_trade_reference, $p_gross_monthly_sale,
        $p_monthly_income, $p_inventory, $p_receivable, $p_fixed_asset, $p_liabilities, $p_capital,
        $p_remarks, $p_last_log_by
    ) {
        $stmt = $this->db->getConnection()->prepare('CALL insertCIReportBusiness(
            :p_ci_report_id, :p_business_name, :p_description, :p_contact_address, :p_city_id,
            :p_length_stay_year, :p_length_stay_month, :p_registered_with, :p_organization, :p_date_organized,
            :p_no_employee, :p_customer, :p_major_bank_id, :p_contact_person, :p_business_location_type_id,
            :p_building_make_id, :p_business_premises_id, :p_landlord, :p_rental_amount, :p_machineries,
            :p_branches, :p_fixtures, :p_facility_condition, :p_vehicle, :p_trade_reference, :p_gross_monthly_sale,
            :p_monthly_income, :p_inventory, :p_receivable, :p_fixed_asset, :p_liabilities, :p_capital,
            :p_remarks, :p_last_log_by
        )');

        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_business_name', $p_business_name);
        $stmt->bindValue(':p_description', $p_description);
        $stmt->bindValue(':p_contact_address', $p_contact_address);
        $stmt->bindValue(':p_city_id', $p_city_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_length_stay_year', $p_length_stay_year, PDO::PARAM_INT);
        $stmt->bindValue(':p_length_stay_month', $p_length_stay_month, PDO::PARAM_INT);
        $stmt->bindValue(':p_registered_with', $p_registered_with);
        $stmt->bindValue(':p_organization', $p_organization);
        $stmt->bindValue(':p_date_organized', $p_date_organized);
        $stmt->bindValue(':p_no_employee', $p_no_employee, PDO::PARAM_INT);
        $stmt->bindValue(':p_customer', $p_customer);
        $stmt->bindValue(':p_major_bank_id', $p_major_bank_id);
        $stmt->bindValue(':p_contact_person', $p_contact_person);
        $stmt->bindValue(':p_business_location_type_id', $p_business_location_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_building_make_id', $p_building_make_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_business_premises_id', $p_business_premises_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_landlord', $p_landlord);
        $stmt->bindValue(':p_rental_amount', $p_rental_amount);
        $stmt->bindValue(':p_machineries', $p_machineries);
        $stmt->bindValue(':p_branches', $p_branches);
        $stmt->bindValue(':p_fixtures', $p_fixtures);
        $stmt->bindValue(':p_facility_condition', $p_facility_condition);
        $stmt->bindValue(':p_vehicle', $p_vehicle);
        $stmt->bindValue(':p_trade_reference', $p_trade_reference);
        $stmt->bindValue(':p_gross_monthly_sale', $p_gross_monthly_sale);
        $stmt->bindValue(':p_monthly_income', $p_monthly_income);
        $stmt->bindValue(':p_inventory', $p_inventory);
        $stmt->bindValue(':p_receivable', $p_receivable);
        $stmt->bindValue(':p_fixed_asset', $p_fixed_asset);
        $stmt->bindValue(':p_liabilities', $p_liabilities);
        $stmt->bindValue(':p_capital', $p_capital);
        $stmt->bindValue(':p_remarks', $p_remarks);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCIReportBusiness(
        $p_ci_report_business_id, $p_ci_report_id, $p_business_name, $p_description, $p_contact_address, $p_city_id,
        $p_length_stay_year, $p_length_stay_month, $p_registered_with, $p_organization, $p_date_organized,
        $p_no_employee, $p_customer, $p_major_bank_id, $p_contact_person, $p_business_location_type_id,
        $p_building_make_id, $p_business_premises_id, $p_landlord, $p_rental_amount, $p_machineries,
        $p_branches, $p_fixtures, $p_facility_condition, $p_vehicle, $p_trade_reference, $p_gross_monthly_sale,
        $p_monthly_income, $p_inventory, $p_receivable, $p_fixed_asset, $p_liabilities, $p_capital,
        $p_remarks, $p_last_log_by
        ) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCIReportBusiness(
            :p_ci_report_business_id, :p_ci_report_id, :p_business_name, :p_description, :p_contact_address, :p_city_id,
            :p_length_stay_year, :p_length_stay_month, :p_registered_with, :p_organization, :p_date_organized,
            :p_no_employee, :p_customer, :p_major_bank_id, :p_contact_person, :p_business_location_type_id,
            :p_building_make_id, :p_business_premises_id, :p_landlord, :p_rental_amount, :p_machineries,
            :p_branches, :p_fixtures, :p_facility_condition, :p_vehicle, :p_trade_reference, :p_gross_monthly_sale,
            :p_monthly_income, :p_inventory, :p_receivable, :p_fixed_asset, :p_liabilities, :p_capital,
            :p_remarks, :p_last_log_by
        )');

        $stmt->bindValue(':p_ci_report_business_id', $p_ci_report_business_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_business_name', $p_business_name);
        $stmt->bindValue(':p_description', $p_description);
        $stmt->bindValue(':p_contact_address', $p_contact_address);
        $stmt->bindValue(':p_city_id', $p_city_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_length_stay_year', $p_length_stay_year, PDO::PARAM_INT);
        $stmt->bindValue(':p_length_stay_month', $p_length_stay_month, PDO::PARAM_INT);
        $stmt->bindValue(':p_registered_with', $p_registered_with);
        $stmt->bindValue(':p_organization', $p_organization);
        $stmt->bindValue(':p_date_organized', $p_date_organized);
        $stmt->bindValue(':p_no_employee', $p_no_employee, PDO::PARAM_INT);
        $stmt->bindValue(':p_customer', $p_customer);
        $stmt->bindValue(':p_major_bank_id', $p_major_bank_id);
        $stmt->bindValue(':p_contact_person', $p_contact_person);
        $stmt->bindValue(':p_business_location_type_id', $p_business_location_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_building_make_id', $p_building_make_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_business_premises_id', $p_business_premises_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_landlord', $p_landlord);
        $stmt->bindValue(':p_rental_amount', $p_rental_amount);
        $stmt->bindValue(':p_machineries', $p_machineries);
        $stmt->bindValue(':p_branches', $p_branches);
        $stmt->bindValue(':p_fixtures', $p_fixtures);
        $stmt->bindValue(':p_facility_condition', $p_facility_condition);
        $stmt->bindValue(':p_vehicle', $p_vehicle);
        $stmt->bindValue(':p_trade_reference', $p_trade_reference);
        $stmt->bindValue(':p_gross_monthly_sale', $p_gross_monthly_sale);
        $stmt->bindValue(':p_monthly_income', $p_monthly_income);
        $stmt->bindValue(':p_inventory', $p_inventory);
        $stmt->bindValue(':p_receivable', $p_receivable);
        $stmt->bindValue(':p_fixed_asset', $p_fixed_asset);
        $stmt->bindValue(':p_liabilities', $p_liabilities);
        $stmt->bindValue(':p_capital', $p_capital);
        $stmt->bindValue(':p_remarks', $p_remarks);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function openCustomerCIReport($p_customer_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL openCustomerCIReport(:p_customer_id, :p_last_log_by)');
        $stmt->bindValue(':p_customer_id', $p_customer_id, type: PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteCIReportBusiness($ci_report_business_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCIReportBusiness(:ci_report_business_id)');
        $stmt->bindValue(':ci_report_business_id', $ci_report_business_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getCIReportBusiness($p_ci_report_business_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCIReportBusiness(:p_ci_report_business_id)');
        $stmt->bindValue(':p_ci_report_business_id', $p_ci_report_business_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCIReportBusinessExpenseTotal($p_ci_report_id, $p_type) {
        $stmt = $this->db->getConnection()->prepare('CALL getCIReportBusinessExpenseTotal(:p_ci_report_id, :p_type)');
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    # -------------------------------------------------------------
    
    public function checkCIReportEmploymentExist($p_ci_report_employment_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkCIReportEmploymentExist(:p_ci_report_employment_id)');
        $stmt->bindValue(':p_ci_report_employment_id', $p_ci_report_employment_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertCIReportEmployment(
        $ci_report_id, $employment_name, $description, $contact_address, $city_id,
        $length_stay_year, $length_stay_month, $pres_length_stay_year, $pres_length_stay_month,
        $informant, $informant_address, $department, $rank, $position, $status,
        $net_salary, $commission, $allowance, $other_income, $grand_total,
        $remarks, $last_log_by
    ) {
        $stmt = $this->db->getConnection()->prepare('CALL insertCIReportEmployment(
            :ci_report_id, :employment_name, :description, :contact_address, :city_id,
            :length_stay_year, :length_stay_month, :pres_length_stay_year, :pres_length_stay_month,
            :informant, :informant_address, :department, :rank, :position, :status,
            :net_salary, :commission, :allowance, :other_income, :grand_total,
            :remarks, :last_log_by
        )');

        $stmt->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':employment_name', $employment_name);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':contact_address', $contact_address);
        $stmt->bindValue(':city_id', $city_id, PDO::PARAM_INT);
        $stmt->bindValue(':length_stay_year', $length_stay_year, PDO::PARAM_INT);
        $stmt->bindValue(':length_stay_month', $length_stay_month, PDO::PARAM_INT);
        $stmt->bindValue(':pres_length_stay_year', $pres_length_stay_year, PDO::PARAM_INT);
        $stmt->bindValue(':pres_length_stay_month', $pres_length_stay_month, PDO::PARAM_INT);
        $stmt->bindValue(':informant', $informant);
        $stmt->bindValue(':informant_address', $informant_address);
        $stmt->bindValue(':department', $department);
        $stmt->bindValue(':rank', $rank);
        $stmt->bindValue(':position', $position);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':net_salary', $net_salary);
        $stmt->bindValue(':commission', $commission);
        $stmt->bindValue(':allowance', $allowance);
        $stmt->bindValue(':other_income', $other_income);
        $stmt->bindValue(':grand_total', $grand_total);
        $stmt->bindValue(':remarks', $remarks);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCIReportEmployment(
        $ci_report_employment_id, $ci_report_id, $employment_name, $description, $contact_address, $city_id,
        $length_stay_year, $length_stay_month, $pres_length_stay_year, $pres_length_stay_month,
        $informant, $informant_address, $department, $rank, $position, $status,
        $net_salary, $commission, $allowance, $other_income, $grand_total,
        $remarks, $last_log_by
    ) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCIReportEmployment(
            :ci_report_employment_id, :ci_report_id, :employment_name, :description, :contact_address, :city_id,
            :length_stay_year, :length_stay_month, :pres_length_stay_year, :pres_length_stay_month,
            :informant, :informant_address, :department, :rank, :position, :status,
            :net_salary, :commission, :allowance, :other_income, :grand_total,
            :remarks, :last_log_by
        )');

        $stmt->bindValue(':ci_report_employment_id', $ci_report_employment_id, PDO::PARAM_INT);
        $stmt->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':employment_name', $employment_name);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':contact_address', $contact_address);
        $stmt->bindValue(':city_id', $city_id, PDO::PARAM_INT);
        $stmt->bindValue(':length_stay_year', $length_stay_year, PDO::PARAM_INT);
        $stmt->bindValue(':length_stay_month', $length_stay_month, PDO::PARAM_INT);
        $stmt->bindValue(':pres_length_stay_year', $pres_length_stay_year, PDO::PARAM_INT);
        $stmt->bindValue(':pres_length_stay_month', $pres_length_stay_month, PDO::PARAM_INT);
        $stmt->bindValue(':informant', $informant);
        $stmt->bindValue(':informant_address', $informant_address);
        $stmt->bindValue(':department', $department);
        $stmt->bindValue(':rank', $rank);
        $stmt->bindValue(':position', $position);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':net_salary', $net_salary);
        $stmt->bindValue(':commission', $commission);
        $stmt->bindValue(':allowance', $allowance);
        $stmt->bindValue(':other_income', $other_income);
        $stmt->bindValue(':grand_total', $grand_total);
        $stmt->bindValue(':remarks', $remarks);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteCIReportEmployment($ci_report_employment_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCIReportEmployment(:ci_report_employment_id)');
        $stmt->bindValue(':ci_report_employment_id', $ci_report_employment_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getCIReportEmployment($p_ci_report_employment_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCIReportEmployment(:p_ci_report_employment_id)');
        $stmt->bindValue(':p_ci_report_employment_id', $p_ci_report_employment_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCIReportEmploymentExpenseTotal($p_ci_report_id, $p_type) {
        $stmt = $this->db->getConnection()->prepare('CALL getCIReportEmploymentExpenseTotal(:p_ci_report_id, :p_type)');
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
    
    public function checkCIReportBankExist($p_ci_report_bank_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkCIReportBankExist(:p_ci_report_bank_id)');
        $stmt->bindValue(':p_ci_report_bank_id', $p_ci_report_bank_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertCIReportBank(
        $ci_report_id, $bank_id, $account_name, $account_number, $bank_account_type_id,
        $currency_id, $bank_handling_type_id, $date_open, $adb, $informant,
        $remarks, $last_log_by
    ) {
        $stmt = $this->db->getConnection()->prepare('CALL insertCIReportBank(
            :ci_report_id, :bank_id, :account_name, :account_number, :bank_account_type_id,
            :currency_id, :bank_handling_type_id, :date_open, :adb, :informant,
            :remarks, :last_log_by
        )');

        $stmt->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':bank_id', $bank_id);
        $stmt->bindValue(':account_name', $account_name);
        $stmt->bindValue(':account_number', $account_number);
        $stmt->bindValue(':bank_account_type_id', $bank_account_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':currency_id', $currency_id, PDO::PARAM_INT);
        $stmt->bindValue(':bank_handling_type_id', $bank_handling_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':date_open', $date_open);
        $stmt->bindValue(':adb', $adb);
        $stmt->bindValue(':informant', $informant);
        $stmt->bindValue(':remarks', $remarks);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCIReportBank(
        $ci_report_bank_id, $ci_report_id, $bank_id, $account_name, $account_number,
        $bank_account_type_id, $currency_id, $bank_handling_type_id, $date_open, $adb,
        $informant, $remarks, $last_log_by
    ) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCIReportBank(
            :ci_report_bank_id, :ci_report_id, :bank_id, :account_name, :account_number,
            :bank_account_type_id, :currency_id, :bank_handling_type_id, :date_open, :adb,
            :informant, :remarks, :last_log_by
        )');

        $stmt->bindValue(':ci_report_bank_id', $ci_report_bank_id, PDO::PARAM_INT);
        $stmt->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':bank_id', $bank_id);
        $stmt->bindValue(':account_name', $account_name);
        $stmt->bindValue(':account_number', $account_number);
        $stmt->bindValue(':bank_account_type_id', $bank_account_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':currency_id', $currency_id, PDO::PARAM_INT);
        $stmt->bindValue(':bank_handling_type_id', $bank_handling_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':date_open', $date_open);
        $stmt->bindValue(':adb', $adb);
        $stmt->bindValue(':informant', $informant);
        $stmt->bindValue(':remarks', $remarks);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteCIReportBank($ci_report_bank_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCIReportBank(:ci_report_bank_id)');
        $stmt->bindValue(':ci_report_bank_id', $ci_report_bank_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getBankDepositAverage($ci_report_bank_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getBankDepositAverage(:ci_report_bank_id)');
        $stmt->bindValue(':ci_report_bank_id', $ci_report_bank_id, PDO::PARAM_INT);
        $stmt->execute();
         return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCIReportBank($p_ci_report_bank_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCIReportBank(:p_ci_report_bank_id)');
        $stmt->bindValue(':p_ci_report_bank_id', $p_ci_report_bank_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    # -------------------------------------------------------------
    
    public function checkCIReportBankDepositsExist($p_ci_report_bank_deposits_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkCIReportBankDepositsExist(:p_ci_report_bank_deposits_id)');
        $stmt->bindValue(':p_ci_report_bank_deposits_id', $p_ci_report_bank_deposits_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertCIReportBankDeposits($ci_report_bank_id, $ci_report_id, $deposit_month, $amount, $remarks, $last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertCIReportBankDeposits(:ci_report_bank_id, :ci_report_id, :deposit_month, :amount, :remarks, :last_log_by)');
        $stmt->bindValue(':ci_report_bank_id', $ci_report_bank_id, PDO::PARAM_INT);
        $stmt->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':deposit_month', $deposit_month, PDO::PARAM_STR);
        $stmt->bindValue(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindValue(':remarks', $remarks);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCIReportBankDeposits($ci_report_bank_deposits_id, $ci_report_bank_id, $ci_report_id, $deposit_month, $amount, $remarks, $last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCIReportBankDeposits(:ci_report_bank_deposits_id, :ci_report_bank_id, :ci_report_id, :deposit_month, :amount, :remarks, :last_log_by)');

        $stmt->bindValue(':ci_report_bank_deposits_id', $ci_report_bank_deposits_id, PDO::PARAM_INT);
        $stmt->bindValue(':ci_report_bank_id', $ci_report_bank_id, PDO::PARAM_INT);
        $stmt->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':deposit_month', $deposit_month, PDO::PARAM_STR);
        $stmt->bindValue(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindValue(':remarks', $remarks);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteCIReportBankDeposits($ci_report_bank_deposits_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCIReportBankDeposits(:ci_report_bank_deposits_id)');
        $stmt->bindValue(':ci_report_bank_deposits_id', $ci_report_bank_deposits_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getCIReportBankDeposits($ci_report_bank_deposits_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCIReportBankDeposits(:ci_report_bank_deposits_id)');
        $stmt->bindValue(':ci_report_bank_deposits_id', $ci_report_bank_deposits_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    # -------------------------------------------------------------
    
    public function checkCIReportAppraisalSourceExist($p_ci_report_bank_deposits_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkCIReportAppraisalSourceExist(:p_ci_report_bank_deposits_id)');
        $stmt->bindValue(':p_ci_report_bank_deposits_id', $p_ci_report_bank_deposits_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertCIReportAppraisalSource($ci_report_bank_id, $ci_report_id, $source, $amount, $remarks, $last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertCIReportAppraisalSource(:ci_report_bank_id, :ci_report_id, :source, :amount, :remarks, :last_log_by)');
        $stmt->bindValue(':ci_report_bank_id', $ci_report_bank_id, PDO::PARAM_INT);
        $stmt->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':source', $source, PDO::PARAM_STR);
        $stmt->bindValue(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindValue(':remarks', $remarks);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCIReportAppraisalSource($ci_report_bank_deposits_id, $ci_report_bank_id, $ci_report_id, $source, $amount, $remarks, $last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCIReportAppraisalSource(:ci_report_bank_deposits_id, :ci_report_bank_id, :ci_report_id, :source, :amount, :remarks, :last_log_by)');

        $stmt->bindValue(':ci_report_bank_deposits_id', $ci_report_bank_deposits_id, PDO::PARAM_INT);
        $stmt->bindValue(':ci_report_bank_id', $ci_report_bank_id, PDO::PARAM_INT);
        $stmt->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':source', $source, PDO::PARAM_STR);
        $stmt->bindValue(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindValue(':remarks', $remarks);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteCIReportAppraisalSource($ci_report_bank_deposits_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCIReportAppraisalSource(:ci_report_bank_deposits_id)');
        $stmt->bindValue(':ci_report_bank_deposits_id', $ci_report_bank_deposits_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getCIReportAppraisalSource($ci_report_bank_deposits_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCIReportAppraisalSource(:ci_report_bank_deposits_id)');
        $stmt->bindValue(':ci_report_bank_deposits_id', $ci_report_bank_deposits_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    # -------------------------------------------------------------
    
    public function checkCIReportLoanExist($p_ci_report_loan_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkCIReportLoanExist(:p_ci_report_loan_id)');
        $stmt->bindValue(':p_ci_report_loan_id', $p_ci_report_loan_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertCIReportLoan(
        $ci_report_id, $company, $loan_source, $informant, $account_name, $loan_type_id,
        $availed_date, $maturity_date, $term, $pn_amount, $outstanding_balance,
        $repayment, $handling, $remarks, $last_log_by
    ) {
        $stmt = $this->db->getConnection()->prepare('CALL insertCIReportLoan(
            :ci_report_id, :company, :loan_source, :informant, :account_name, :loan_type_id,
            :availed_date, :maturity_date, :term, :pn_amount, :outstanding_balance,
            :repayment, :handling, :remarks, :last_log_by
        )');

        $stmt->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':company', $company);
        $stmt->bindValue(':loan_source', $loan_source);
        $stmt->bindValue(':informant', $informant);
        $stmt->bindValue(':account_name', $account_name);
        $stmt->bindValue(':loan_type_id', $loan_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':availed_date', $availed_date);
        $stmt->bindValue(':maturity_date', $maturity_date);
        $stmt->bindValue(':term', $term, PDO::PARAM_INT);
        $stmt->bindValue(':pn_amount', $pn_amount);
        $stmt->bindValue(':outstanding_balance', $outstanding_balance);
        $stmt->bindValue(':repayment', $repayment);
        $stmt->bindValue(':handling', $handling);
        $stmt->bindValue(':remarks', $remarks);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function updateCIReportLoan(
        $ci_report_loan_id, $ci_report_id, $company, $loan_source, $informant, $account_name,
        $loan_type_id, $availed_date, $maturity_date, $term, $pn_amount,
        $outstanding_balance, $repayment, $handling, $remarks, $last_log_by
    ) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCIReportLoan(
            :ci_report_loan_id, :ci_report_id, :company, :loan_source, :informant, :account_name,
            :loan_type_id, :availed_date, :maturity_date, :term, :pn_amount,
            :outstanding_balance, :repayment, :handling, :remarks, :last_log_by
        )');

        $stmt->bindValue(':ci_report_loan_id', $ci_report_loan_id, PDO::PARAM_INT);
        $stmt->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':company', $company);
        $stmt->bindValue(':loan_source', $loan_source);
        $stmt->bindValue(':informant', $informant);
        $stmt->bindValue(':account_name', $account_name);
        $stmt->bindValue(':loan_type_id', $loan_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':availed_date', $availed_date);
        $stmt->bindValue(':maturity_date', $maturity_date);
        $stmt->bindValue(':term', $term, PDO::PARAM_INT);
        $stmt->bindValue(':pn_amount', $pn_amount);
        $stmt->bindValue(':outstanding_balance', $outstanding_balance);
        $stmt->bindValue(':repayment', $repayment);
        $stmt->bindValue(':handling', $handling);
        $stmt->bindValue(':remarks', $remarks);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteCIReportLoan($ci_report_loan_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCIReportLoan(:ci_report_loan_id)');
        $stmt->bindValue(':ci_report_loan_id', $ci_report_loan_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getCIReportLoan($p_ci_report_loan_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCIReportLoan(:p_ci_report_loan_id)');
        $stmt->bindValue(':p_ci_report_loan_id', $p_ci_report_loan_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCIReportLoanTotal($p_ci_report_id, $p_type) {
        $stmt = $this->db->getConnection()->prepare('CALL getCIReportLoanTotal(:p_ci_report_id, :p_type)');
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    # -------------------------------------------------------------
    
    public function checkCIReportCMAPExist($p_ci_report_cmap_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkCIReportCMAPExist(:p_ci_report_cmap_id)');
        $stmt->bindValue(':p_ci_report_cmap_id', $p_ci_report_cmap_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertCIReportCMAP(
        $ci_report_id, $cmap_report_type_id, $defendants, $plaintiff, $nature_of_case,
        $trial_court, $sala_no, $case_no, $reported_date, $remarks, $last_log_by
    ) {
        $stmt = $this->db->getConnection()->prepare('CALL insertCIReportCMAP(
            :ci_report_id, :cmap_report_type_id, :defendants, :plaintiff, :nature_of_case,
            :trial_court, :sala_no, :case_no, :reported_date, :remarks, :last_log_by
        )');

        $stmt->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':cmap_report_type_id', $cmap_report_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':defendants', $defendants);
        $stmt->bindValue(':plaintiff', $plaintiff);
        $stmt->bindValue(':nature_of_case', $nature_of_case);
        $stmt->bindValue(':trial_court', $trial_court);
        $stmt->bindValue(':sala_no', $sala_no);
        $stmt->bindValue(':case_no', $case_no);
        $stmt->bindValue(':reported_date', $reported_date);
        $stmt->bindValue(':remarks', $remarks);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCIReportCMAP(
        $ci_report_cmap_id, $ci_report_id, $cmap_report_type_id, $defendants, $plaintiff,
        $nature_of_case, $trial_court, $sala_no, $case_no, $reported_date,
        $remarks, $last_log_by
    ) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCIReportCMAP(
            :ci_report_cmap_id, :ci_report_id, :cmap_report_type_id, :defendants, :plaintiff,
            :nature_of_case, :trial_court, :sala_no, :case_no, :reported_date,
            :remarks, :last_log_by
        )');

        $stmt->bindValue(':ci_report_cmap_id', $ci_report_cmap_id, PDO::PARAM_INT);
        $stmt->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':cmap_report_type_id', $cmap_report_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':defendants', $defendants);
        $stmt->bindValue(':plaintiff', $plaintiff);
        $stmt->bindValue(':nature_of_case', $nature_of_case);
        $stmt->bindValue(':trial_court', $trial_court);
        $stmt->bindValue(':sala_no', $sala_no);
        $stmt->bindValue(':case_no', $case_no);
        $stmt->bindValue(':reported_date', $reported_date);
        $stmt->bindValue(':remarks', $remarks);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteCIReportCMAP($ci_report_cmap_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCIReportCMAP(:ci_report_cmap_id)');
        $stmt->bindValue(':ci_report_cmap_id', $ci_report_cmap_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getCIReportCMAP($p_ci_report_cmap_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCIReportCMAP(:p_ci_report_cmap_id)');
        $stmt->bindValue(':p_ci_report_cmap_id', $p_ci_report_cmap_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    # -------------------------------------------------------------

    public function checkCIReportCollateralExist($p_ci_report_collateral_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkCIReportCollateralExist(:p_ci_report_collateral_id)');
        $stmt->bindValue(':p_ci_report_collateral_id', $p_ci_report_collateral_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertCIReportCollateral(
        $ci_report_id, $appraisal_date, $brand_id, $description, $color_id,
        $year_model, $plate_no, $motor_no, $serial_no, $mvr_file_no,
        $cr_no, $or_no, $registered_owner, $appraised_value, $loannable_value,
        $remarks, $last_log_by
    ) {
        $stmt = $this->db->getConnection()->prepare('CALL insertCIReportCollateral(
            :ci_report_id, :appraisal_date, :brand_id, :description, :color_id,
            :year_model, :plate_no, :motor_no, :serial_no, :mvr_file_no,
            :cr_no, :or_no, :registered_owner, :appraised_value, :loannable_value,
            :remarks, :last_log_by
        )');

        $stmt->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':appraisal_date', $appraisal_date);
        $stmt->bindValue(':brand_id', $brand_id, PDO::PARAM_INT);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':color_id', $color_id, PDO::PARAM_STR);
        $stmt->bindValue(':year_model', $year_model);
        $stmt->bindValue(':plate_no', $plate_no);
        $stmt->bindValue(':motor_no', $motor_no);
        $stmt->bindValue(':serial_no', $serial_no);
        $stmt->bindValue(':mvr_file_no', $mvr_file_no);
        $stmt->bindValue(':cr_no', $cr_no);
        $stmt->bindValue(':or_no', $or_no);
        $stmt->bindValue(':registered_owner', $registered_owner);
        $stmt->bindValue(':appraised_value', $appraised_value);
        $stmt->bindValue(':loannable_value', $loannable_value);
        $stmt->bindValue(':remarks', $remarks);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function updateCIReportCollateral(
        $ci_report_collateral_id, $ci_report_id, $appraisal_date, $brand_id, $description, $color_id,
        $year_model, $plate_no, $motor_no, $serial_no, $mvr_file_no,
        $cr_no, $or_no, $registered_owner, $appraised_value, $loannable_value,
        $remarks, $last_log_by
    ) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCIReportCollateral(
            :ci_report_collateral_id, :ci_report_id, :appraisal_date, :brand_id, :description, :color_id,
            :year_model, :plate_no, :motor_no, :serial_no, :mvr_file_no,
            :cr_no, :or_no, :registered_owner, :appraised_value, :loannable_value,
            :remarks, :last_log_by
        )');

        $stmt->bindValue(':ci_report_collateral_id', $ci_report_collateral_id, PDO::PARAM_INT);
        $stmt->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':appraisal_date', $appraisal_date);
        $stmt->bindValue(':brand_id', $brand_id, PDO::PARAM_INT);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':color_id', $color_id, PDO::PARAM_STR);
        $stmt->bindValue(':year_model', $year_model);
        $stmt->bindValue(':plate_no', $plate_no);
        $stmt->bindValue(':motor_no', $motor_no);
        $stmt->bindValue(':serial_no', $serial_no);
        $stmt->bindValue(':mvr_file_no', $mvr_file_no);
        $stmt->bindValue(':cr_no', $cr_no);
        $stmt->bindValue(':or_no', $or_no);
        $stmt->bindValue(':registered_owner', $registered_owner);
        $stmt->bindValue(':appraised_value', $appraised_value);
        $stmt->bindValue(':loannable_value', $loannable_value);
        $stmt->bindValue(':remarks', $remarks);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function deleteCIReportCollateral($ci_report_collateral_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCIReportCollateral(:ci_report_collateral_id)');
        $stmt->bindValue(':ci_report_collateral_id', $ci_report_collateral_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getCIReportCollateral($p_ci_report_collateral_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCIReportCollateral(:p_ci_report_collateral_id)');
        $stmt->bindValue(':p_ci_report_collateral_id', $p_ci_report_collateral_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    # -------------------------------------------------------------

    public function checkCIReportAssetExist($p_ci_report_asset_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkCIReportAssetExist(:p_ci_report_asset_id)');
        $stmt->bindValue(':p_ci_report_asset_id', $p_ci_report_asset_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertCIReportAsset(
        $ci_report_id, $asset_type_id, $description, $value,
        $remarks, $last_log_by
    ) {
        $stmt = $this->db->getConnection()->prepare('CALL insertCIReportAsset(
            :ci_report_id, :asset_type_id, :description, :value,
            :remarks, :last_log_by
        )');

        $stmt->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':asset_type_id', $asset_type_id);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':value', $value);
        $stmt->bindValue(':remarks', $remarks);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function updateCIReportAsset(
        $ci_report_asset_id, $ci_report_id, $asset_type_id, $description, $value,
        $remarks, $last_log_by
    ) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCIReportAsset(
            :ci_report_asset_id, :ci_report_id, :asset_type_id, :description, :value,
            :remarks, :last_log_by
        )');

        $stmt->bindValue(':ci_report_asset_id', $ci_report_asset_id, PDO::PARAM_INT);
        $stmt->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':asset_type_id', $asset_type_id);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':value', $value);
        $stmt->bindValue(':remarks', $remarks);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function deleteCIReportAsset($ci_report_asset_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCIReportAsset(:ci_report_asset_id)');
        $stmt->bindValue(':ci_report_asset_id', $ci_report_asset_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getCIReportAsset($p_ci_report_asset_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCIReportAsset(:p_ci_report_asset_id)');
        $stmt->bindValue(':p_ci_report_asset_id', $p_ci_report_asset_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCIReportAssetsTotal($p_ci_report_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCIReportAssetsTotal(:p_ci_report_id)');
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCIReportHighestExposure($p_ci_report_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM ci_report_loan WHERE ci_report_id = :p_ci_report_id ORDER BY pn_amount DESC LIMIT 1');
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    # -------------------------------------------------------------

    # -------------------------------------------------------------

    public function checkCIReportTradeReferenceExist($p_ci_report_trade_reference_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkCIReportTradeReferenceExist(:p_ci_report_trade_reference_id)');
        $stmt->bindValue(':p_ci_report_trade_reference_id', $p_ci_report_trade_reference_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertCIReportTradeReference(
        $ci_report_id, $ci_report_business_id, $supplier, $contact_person, $years_of_transaction,
        $remarks, $last_log_by
    ) {
        $stmt = $this->db->getConnection()->prepare('CALL insertCIReportTradeReference(
            :ci_report_id, :ci_report_business_id, :supplier, :contact_person, :years_of_transaction,
            :remarks, :last_log_by
        )');

        $stmt->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':ci_report_business_id', $ci_report_business_id);
        $stmt->bindValue(':supplier', $supplier);
        $stmt->bindValue(':contact_person', $contact_person);
        $stmt->bindValue(':years_of_transaction', $years_of_transaction);
        $stmt->bindValue(':remarks', $remarks);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function updateCIReportTradeReference(
        $ci_report_trade_reference_id, $ci_report_id, $supplier, $contact_person, $years_of_transaction,
        $remarks, $last_log_by
    ) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCIReportTradeReference(
            :ci_report_trade_reference_id, :ci_report_id, :supplier, :contact_person, :years_of_transaction,
            :remarks, :last_log_by
        )');

        $stmt->bindValue(':ci_report_trade_reference_id', $ci_report_trade_reference_id, PDO::PARAM_INT);
        $stmt->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':supplier', $supplier);
        $stmt->bindValue(':contact_person', $contact_person);
        $stmt->bindValue(':years_of_transaction', $years_of_transaction);
        $stmt->bindValue(':remarks', $remarks);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function deleteCIReportTradeReference($ci_report_trade_reference_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCIReportTradeReference(:ci_report_trade_reference_id)');
        $stmt->bindValue(':ci_report_trade_reference_id', $ci_report_trade_reference_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getCIReportTradeReference($p_ci_report_trade_reference_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCIReportTradeReference(:p_ci_report_trade_reference_id)');
        $stmt->bindValue(':p_ci_report_trade_reference_id', $p_ci_report_trade_reference_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    # -------------------------------------------------------------

    
    public function insertCIReportFile(
        $ci_report_id, $ci_files_file_name, $filePath, $ci_file_type_id,
        $remarks, $last_log_by
    ) {
        $stmt = $this->db->getConnection()->prepare('CALL insertCIReportFile(
            :ci_report_id, :ci_files_file_name, :filePath, :ci_file_type_id,
            :remarks, :last_log_by
        )');

        $stmt->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $stmt->bindValue(':ci_files_file_name', $ci_files_file_name);
        $stmt->bindValue(':filePath', $filePath);
        $stmt->bindValue(':ci_file_type_id', $ci_file_type_id);
        $stmt->bindValue(':remarks', $remarks);
        $stmt->bindValue(':last_log_by', $last_log_by, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteCIReport
    # Description: Deletes the ci report.
    #
    # Parameters:
    # - $p_ci_report_id (int): The ci report ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteCIReport($p_ci_report_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCIReport(:p_ci_report_id)');
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteCIReportFile($p_ci_report_files_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCIReportFile(:p_ci_report_files_id)');
        $stmt->bindValue(':p_ci_report_files_id', $p_ci_report_files_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getCIReport
    # Description: Retrieves the details of a ci report.
    #
    # Parameters:
    # - $p_ci_report_id (int): The ci report ID.
    #
    # Returns:
    # - An array containing the ci report details.
    #
    # -------------------------------------------------------------
    public function getCIReport($p_ci_report_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCIReport(:p_ci_report_id)');
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCIReportLoanPNAmount($p_ci_report_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT sum(pn_amount) as total FROM ci_report_loan WHERE ci_report_id = :p_ci_report_id AND (loan_source != "CGMI" OR loan_source IS NULL)');
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCIReportBusinessMonthlyIncome($p_ci_report_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT sum(monthly_income) as total FROM ci_report_business WHERE ci_report_id = :p_ci_report_id');
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getPrimaryResidence($p_ci_report_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM ci_report_residence WHERE ci_report_id = :p_ci_report_id ORDER BY created_date ASC LIMIT 1');
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function generateCIReportBusinessSummary($p_ci_report_id) {
         $table = '<table class="table table-borderless text-sm ">
                <tbody>
                <tr>
                    <td style="text-align: center !important;"><u>Business Name</u></td>
                    <td style="text-align: center !important;"><u>Amount</u></td>
                </tr>';

        $stmt = $this->db->getConnection()->prepare('CALL generateCIReportBusinessSummary(:p_ci_report_id)');
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();


        foreach ($options as $row) {
            $business_name = $row['business_name'];
            $monthly_income = number_format($row['monthly_income'] ?? 0, 2);

            $table .= '<tr>
                            <td style="text-align: center !important;" class="text-wrap">'. $business_name .'</td>
                            <td style="text-align: center !important;" class="text-wrap">'. $monthly_income .' PHP</td>
                        </tr>';
        }

        $table .= '</tbody></table>';

        return $table;
    }

    public function generateCIReportEmploymentSummary($p_ci_report_id) {
         $table = '<table class="table table-borderless text-sm ">
                <tbody>
                <tr>
                    <td style="text-align: center !important;"><u>Employment Name</u></td>
                    <td style="text-align: center !important;"><u>Amount</u></td>
                </tr>';

        $stmt = $this->db->getConnection()->prepare('CALL generateCIReportEmploymentSummary(:p_ci_report_id)');
        $stmt->bindValue(':p_ci_report_id', $p_ci_report_id, PDO::PARAM_INT);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();


        foreach ($options as $row) {
            $employment_name = $row['employment_name'];
            $grand_total = number_format($row['grand_total'] ?? 0, 2);

            $table .= '<tr>
                            <td style="text-align: center !important;" class="text-wrap">'. $employment_name .'</td>
                            <td style="text-align: center !important;" class="text-wrap">'. $grand_total .' PHP</td>
                        </tr>';
        }

        $table .= '</tbody></table>';

        return $table;
    }
    # -------------------------------------------------------------
 
   public static function rate(
    int $nper,
    float $pmt,
    float $pv,
    float $fv = 0.0,
    int $type = 0,
    float $guess = 0.1,
    int $maxIter = 128,
    float $tol = 1.0e-8
): float {
    $rate = $guess;
    $x0 = 0.0;
    $x1 = $rate;

    $calcF = function($r) use ($nper, $pmt, $pv, $fv, $type, $tol) {
        if (abs($r) < $tol) {
            return $pv + $pmt * $nper + $fv;
        } else {
            $pow = pow(1 + $r, $nper);
            return $pv * $pow + $pmt * (1 + $r * $type) * ($pow - 1) / $r + $fv;
        }
    };

    $y0 = $calcF($x0);
    $y1 = $calcF($x1);

    for ($i = 0; $i < $maxIter; $i++) {
        if (abs($y1 - $y0) < $tol) {
            break; // avoid division by zero
        }

        // Secant method update (Excel uses this)
        $rate = $x1 - $y1 * ($x1 - $x0) / ($y1 - $y0);

        $x0 = $x1;
        $y0 = $y1;

        $x1 = $rate;
        $y1 = $calcF($x1);

        if (abs($y1) < $tol) {
            return $rate;
        }
    }

    throw new RuntimeException('RATE did not converge');
}







}
?>