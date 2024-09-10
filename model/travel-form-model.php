<?php
/**
* Class TravelFormModel
*
* The TravelFormModel class handles travel form related operations and interactions.
*/
class TravelFormModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateTravelForm
    # Description: Updates the travel form.
    #
    # Parameters:
    # - $p_travel_form_id (int): The travel form ID.
    # - $p_checked_by (string): The travel form name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateTravelForm($p_travel_form_id, $p_checked_by, $p_recommended_by, $p_approval_by, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateTravelForm(:p_travel_form_id, :p_checked_by, :p_recommended_by, :p_approval_by, :p_last_log_by)');
        $stmt->bindValue(':p_travel_form_id', $p_travel_form_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_checked_by', $p_checked_by, PDO::PARAM_STR);
        $stmt->bindValue(':p_recommended_by', $p_recommended_by, PDO::PARAM_STR);
        $stmt->bindValue(':p_approval_by', $p_approval_by, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateTravelAuthorization
    # Description: Updates the travel form.
    #
    # Parameters:
    # - $p_travel_form_id (int): The travel form ID.
    # - $p_checked_by (string): The travel form name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateTravelAuthorization($p_travel_form_id, $p_destination, $p_mode_of_transportation, $p_purpose_of_travel, $p_authorization_departure_date, $p_authorization_return_date, $p_accomodation_details, $p_toll_fee, $p_accomodation, $p_meals, $p_other_expenses, $p_total_estimated_cost, $p_additional_comments, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateTravelAuthorization(:p_travel_form_id, :p_destination, :p_mode_of_transportation, :p_purpose_of_travel, :p_authorization_departure_date, :p_authorization_return_date, :p_accomodation_details, :p_toll_fee, :p_accomodation, :p_meals, :p_other_expenses, :p_total_estimated_cost, :p_additional_comments, :p_last_log_by)');
        $stmt->bindValue(':p_travel_form_id', $p_travel_form_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_destination', $p_destination, PDO::PARAM_STR);
        $stmt->bindValue(':p_mode_of_transportation', $p_mode_of_transportation, PDO::PARAM_STR);
        $stmt->bindValue(':p_purpose_of_travel', $p_purpose_of_travel, PDO::PARAM_STR);
        $stmt->bindValue(':p_authorization_departure_date', $p_authorization_departure_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_authorization_return_date', $p_authorization_return_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_accomodation_details', $p_accomodation_details, PDO::PARAM_STR);
        $stmt->bindValue(':p_toll_fee', $p_toll_fee, PDO::PARAM_STR);
        $stmt->bindValue(':p_accomodation', $p_accomodation, PDO::PARAM_STR);
        $stmt->bindValue(':p_meals', $p_meals, PDO::PARAM_STR);
        $stmt->bindValue(':p_other_expenses', $p_other_expenses, PDO::PARAM_STR);
        $stmt->bindValue(':p_total_estimated_cost', $p_total_estimated_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_additional_comments', $p_additional_comments, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateTravelAuthorization
    # Description: Updates the travel form.
    #
    # Parameters:
    # - $p_travel_form_id (int): The travel form ID.
    # - $p_checked_by (string): The travel form name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateGatePass($p_travel_form_id, $p_name_of_driver, $p_contact_number, $p_vehicle_type, $p_plate_number, $p_purpose_of_entry_exit, $p_department_id, $p_gate_pass_departure_date, $p_odometer_reading, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateGatePass(:p_travel_form_id, :p_name_of_driver, :p_contact_number, :p_vehicle_type, :p_plate_number, :p_purpose_of_entry_exit, :p_department_id, :p_gate_pass_departure_date, :p_odometer_reading, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_travel_form_id', $p_travel_form_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_name_of_driver', $p_name_of_driver, PDO::PARAM_STR);
        $stmt->bindValue(':p_contact_number', $p_contact_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_vehicle_type', $p_vehicle_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_plate_number', $p_plate_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_purpose_of_entry_exit', $p_purpose_of_entry_exit, PDO::PARAM_STR);
        $stmt->bindValue(':p_department_id', $p_department_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_gate_pass_departure_date', $p_gate_pass_departure_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_odometer_reading', $p_odometer_reading, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateItinerary
    # Description: Updates the travel form.
    #
    # Parameters:
    # - $p_travel_form_id (int): The travel form ID.
    # - $p_checked_by (string): The travel form name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateItinerary($p_itinerary_id, $p_travel_form_id, $p_itinerary_date, $p_customer_id, $p_itinerary_destination, $p_itinerary_purpose, $p_expected_time_of_departure, $p_expected_time_of_arrival, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateItinerary(:p_itinerary_id, :p_travel_form_id, :p_itinerary_date, :p_customer_id, :p_itinerary_destination, :p_itinerary_purpose, :p_expected_time_of_departure, :p_expected_time_of_arrival, :p_last_log_by)');
        $stmt->bindValue(':p_itinerary_id', $p_itinerary_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_travel_form_id', $p_travel_form_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_itinerary_date', $p_itinerary_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_customer_id', $p_customer_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_itinerary_destination', $p_itinerary_destination, PDO::PARAM_STR);
        $stmt->bindValue(':p_itinerary_purpose', $p_itinerary_purpose, PDO::PARAM_STR);
        $stmt->bindValue(':p_expected_time_of_departure', $p_expected_time_of_departure, PDO::PARAM_STR);
        $stmt->bindValue(':p_expected_time_of_arrival', $p_expected_time_of_arrival, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateTravelFormStatus
    # Description: Updates the travel form.
    #
    # Parameters:
    # - $p_travel_form_id (int): The travel form ID.
    # - $p_checked_by (string): The travel form name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateTravelFormStatus($p_travel_form_id, $p_travel_form_status, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateTravelFormStatus(:p_travel_form_id, :p_travel_form_status, :p_last_log_by)');
        $stmt->bindValue(':p_travel_form_id', $p_travel_form_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_travel_form_status', $p_travel_form_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertTravelForm
    # Description: Inserts the travel form.
    #
    # Parameters:
    # - $p_travel_form_name (string): The travel form name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertTravelForm($p_checked_by, $p_recommended_by, $p_approval_by, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertTravelForm(:p_checked_by, :p_recommended_by, :p_approval_by, :p_last_log_by, @p_travel_form_id)');
        $stmt->bindValue(':p_checked_by', $p_checked_by, PDO::PARAM_STR);
        $stmt->bindValue(':p_recommended_by', $p_recommended_by, PDO::PARAM_STR);
        $stmt->bindValue(':p_approval_by', $p_approval_by, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_travel_form_id AS p_travel_form_id");
        $p_travel_form_id = $result->fetch(PDO::FETCH_ASSOC)['p_travel_form_id'];

        return $p_travel_form_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertTravelAuthorization
    # Description: Updates the travel form.
    #
    # Parameters:
    # - $p_travel_form_id (int): The travel form ID.
    # - $p_checked_by (string): The travel form name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertTravelAuthorization($p_travel_form_id, $p_destination, $p_mode_of_transportation, $p_purpose_of_travel, $p_authorization_departure_date, $p_authorization_return_date, $p_accomodation_details, $p_toll_fee, $p_accomodation, $p_meals, $p_other_expenses, $p_total_estimated_cost, $p_additional_comments, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertTravelAuthorization(:p_travel_form_id, :p_destination, :p_mode_of_transportation, :p_purpose_of_travel, :p_authorization_departure_date, :p_authorization_return_date, :p_accomodation_details, :p_toll_fee, :p_accomodation, :p_meals, :p_other_expenses, :p_total_estimated_cost, :p_additional_comments, :p_last_log_by)');
        $stmt->bindValue(':p_travel_form_id', $p_travel_form_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_destination', $p_destination, PDO::PARAM_STR);
        $stmt->bindValue(':p_mode_of_transportation', $p_mode_of_transportation, PDO::PARAM_STR);
        $stmt->bindValue(':p_purpose_of_travel', $p_purpose_of_travel, PDO::PARAM_STR);
        $stmt->bindValue(':p_authorization_departure_date', $p_authorization_departure_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_authorization_return_date', $p_authorization_return_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_accomodation_details', $p_accomodation_details, PDO::PARAM_STR);
        $stmt->bindValue(':p_toll_fee', $p_toll_fee, PDO::PARAM_STR);
        $stmt->bindValue(':p_accomodation', $p_accomodation, PDO::PARAM_STR);
        $stmt->bindValue(':p_meals', $p_meals, PDO::PARAM_STR);
        $stmt->bindValue(':p_other_expenses', $p_other_expenses, PDO::PARAM_STR);
        $stmt->bindValue(':p_total_estimated_cost', $p_total_estimated_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_additional_comments', $p_additional_comments, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertTravelAuthorization
    # Description: Updates the travel form.
    #
    # Parameters:
    # - $p_travel_form_id (int): The travel form ID.
    # - $p_checked_by (string): The travel form name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertGatePass($p_travel_form_id, $p_name_of_driver, $p_contact_number, $p_vehicle_type, $p_plate_number, $p_purpose_of_entry_exit, $p_department_id, $p_gate_pass_departure_date, $p_odometer_reading, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertGatePass(:p_travel_form_id, :p_name_of_driver, :p_contact_number, :p_vehicle_type, :p_plate_number, :p_purpose_of_entry_exit, :p_department_id, :p_gate_pass_departure_date, :p_odometer_reading, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_travel_form_id', $p_travel_form_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_name_of_driver', $p_name_of_driver, PDO::PARAM_STR);
        $stmt->bindValue(':p_contact_number', $p_contact_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_vehicle_type', $p_vehicle_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_plate_number', $p_plate_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_purpose_of_entry_exit', $p_purpose_of_entry_exit, PDO::PARAM_STR);
        $stmt->bindValue(':p_department_id', $p_department_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_gate_pass_departure_date', $p_gate_pass_departure_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_odometer_reading', $p_odometer_reading, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertItinerary
    # Description: Updates the travel form.
    #
    # Parameters:
    # - $p_travel_form_id (int): The travel form ID.
    # - $p_checked_by (string): The travel form name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertItinerary($p_travel_form_id, $p_itinerary_date, $p_customer_id, $p_itinerary_destination, $p_itinerary_purpose, $p_expected_time_of_departure, $p_expected_time_of_arrival, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertItinerary(:p_travel_form_id, :p_itinerary_date, :p_customer_id, :p_itinerary_destination, :p_itinerary_purpose, :p_expected_time_of_departure, :p_expected_time_of_arrival, :p_last_log_by)');
        $stmt->bindValue(':p_travel_form_id', $p_travel_form_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_itinerary_date', $p_itinerary_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_customer_id', $p_customer_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_itinerary_destination', $p_itinerary_destination, PDO::PARAM_STR);
        $stmt->bindValue(':p_itinerary_purpose', $p_itinerary_purpose, PDO::PARAM_STR);
        $stmt->bindValue(':p_expected_time_of_departure', $p_expected_time_of_departure, PDO::PARAM_STR);
        $stmt->bindValue(':p_expected_time_of_arrival', $p_expected_time_of_arrival, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkTravelFormExist
    # Description: Checks if a travel form exists.
    #
    # Parameters:
    # - $p_travel_form_id (int): The travel form ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkTravelFormExist($p_travel_form_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkTravelFormExist(:p_travel_form_id)');
        $stmt->bindValue(':p_travel_form_id', $p_travel_form_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkTravelAuthorizationExist
    # Description: Checks if a travel form exists.
    #
    # Parameters:
    # - $p_travel_form_id (int): The travel form ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkTravelAuthorizationExist($p_travel_form_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkTravelAuthorizationExist(:p_travel_form_id)');
        $stmt->bindValue(':p_travel_form_id', $p_travel_form_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkGatePassExist
    # Description: Checks if a travel form exists.
    #
    # Parameters:
    # - $p_travel_form_id (int): The travel form ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkGatePassExist($p_travel_form_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkGatePassExist(:p_travel_form_id)');
        $stmt->bindValue(':p_travel_form_id', $p_travel_form_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkItineraryExist
    # Description: Checks if a travel form exists.
    #
    # Parameters:
    # - $p_travel_form_id (int): The travel form ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkItineraryExist($p_itinerary_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkItineraryExist(:p_itinerary_id)');
        $stmt->bindValue(':p_itinerary_id', $p_itinerary_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteTravelForm
    # Description: Deletes the travel form.
    #
    # Parameters:
    # - $p_travel_form_id (int): The travel form ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteTravelForm($p_travel_form_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteTravelForm(:p_travel_form_id)');
        $stmt->bindValue(':p_travel_form_id', $p_travel_form_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteItinerary
    # Description: Deletes the travel form.
    #
    # Parameters:
    # - $p_travel_form_id (int): The travel form ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteItinerary($p_itinerary_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteItinerary(:p_itinerary_id)');
        $stmt->bindValue(':p_itinerary_id', $p_itinerary_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getTravelForm
    # Description: Retrieves the details of a travel form.
    #
    # Parameters:
    # - $p_travel_form_id (int): The travel form ID.
    #
    # Returns:
    # - An array containing the travel form details.
    #
    # -------------------------------------------------------------
    public function getTravelForm($p_travel_form_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getTravelForm(:p_travel_form_id)');
        $stmt->bindValue(':p_travel_form_id', $p_travel_form_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getTravelAuthorization
    # Description: Retrieves the details of a travel form.
    #
    # Parameters:
    # - $p_travel_form_id (int): The travel form ID.
    #
    # Returns:
    # - An array containing the travel form details.
    #
    # -------------------------------------------------------------
    public function getTravelAuthorization($p_travel_form_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getTravelAuthorization(:p_travel_form_id)');
        $stmt->bindValue(':p_travel_form_id', $p_travel_form_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getGatePass
    # Description: Retrieves the details of a travel form.
    #
    # Parameters:
    # - $p_travel_form_id (int): The travel form ID.
    #
    # Returns:
    # - An array containing the travel form details.
    #
    # -------------------------------------------------------------
    public function getGatePass($p_travel_form_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getGatePass(:p_travel_form_id)');
        $stmt->bindValue(':p_travel_form_id', $p_travel_form_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getItinerary
    # Description: Retrieves the details of a travel form.
    #
    # Parameters:
    # - $p_travel_form_id (int): The travel form ID.
    #
    # Returns:
    # - An array containing the travel form details.
    #
    # -------------------------------------------------------------
    public function getItinerary($p_itinerary_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getItinerary(:p_itinerary_id)');
        $stmt->bindValue(':p_itinerary_id', $p_itinerary_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getItinerary
    # Description: Retrieves the details of a travel form.
    #
    # Parameters:
    # - $p_travel_form_id (int): The travel form ID.
    #
    # Returns:
    # - An array containing the travel form details.
    #
    # -------------------------------------------------------------
    public function getItineraryByTravelForm($p_travel_form_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getItineraryByTravelForm(:p_travel_form_id)');
        $stmt->bindValue(':p_travel_form_id', $p_travel_form_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>