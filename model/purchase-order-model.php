<?php
/**
* Class PurchaseOrderModel
*
* The PurchaseOrderModel class handles purchase order related operations and interactions.
*/
class PurchaseOrderModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updatePurchaseOrder
    # Description: Updates the purchase order.
    #
    # Parameters:
    # - $p_purchase_order_id (int): The purchase order ID.
    # - $p_purchase_order_name (string): The purchase order name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updatePurchaseOrderCart($p_part_transaction_cart_id, $p_quantity, $p_add_on, $p_discount, $p_discount_type, $p_discount_total, $p_sub_total, $p_total, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePurchaseOrderCart(:p_part_transaction_cart_id, :p_quantity, :p_add_on, :p_discount, :p_discount_type, :p_discount_total, :p_sub_total, :p_total, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_cart_id', $p_part_transaction_cart_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_quantity', $p_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_add_on', $p_add_on, PDO::PARAM_STR);
        $stmt->bindValue(':p_discount', $p_discount, PDO::PARAM_STR);
        $stmt->bindValue(':p_discount_type', $p_discount_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_discount_total', $p_discount_total, PDO::PARAM_STR);
        $stmt->bindValue(':p_sub_total', $p_sub_total, PDO::PARAM_STR);
        $stmt->bindValue(':p_total', $p_total, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePartTransactionSummary($p_purchase_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL UpdatePartTransactionSummary(:p_purchase_order_id)');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePurchaseOrderStatus($p_purchase_order_id, $p_part_transaction_status, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePurchaseOrderStatus(:p_purchase_order_id, :p_part_transaction_status, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_part_transaction_status', $p_part_transaction_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPurchaseOrder
    # Description: Inserts the purchase order.
    #
    # Parameters:
    # - $p_part_transaction_id (string): The purchase order ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertPurchaseOrder($p_reference_number, $p_purchase_order_type, $p_company_id, $p_supplier_id, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPurchaseOrder(:p_reference_number, :p_purchase_order_type, :p_company_id, :p_supplier_id, :p_remarks, :p_last_log_by, @p_purchase_order_id)');
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_purchase_order_type', $p_purchase_order_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_supplier_id', $p_supplier_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_purchase_order_id AS p_purchase_order_id");
        $p_purchase_order_id = $result->fetch(PDO::FETCH_ASSOC)['p_purchase_order_id'];

        return $p_purchase_order_id;
    }

    public function createPurchaseOrderProductExpense($p_product_id, $p_reference_type, $p_reference_number, $p_expense_amount, $p_expense_type, $p_particulars, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createPurchaseOrderProductExpense(:p_product_id, :p_reference_type, :p_reference_number, :p_expense_amount, :p_expense_type, :p_particulars, :p_last_log_by)');
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_type', $p_reference_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_expense_amount', $p_expense_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_expense_type', $p_expense_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_particulars', $p_particulars, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function createPurchaseOrderProductExpenseTemp($p_product_id, $p_reference_type, $p_reference_number, $p_expense_amount, $p_expense_type, $p_particulars, $p_issuance_date, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createPurchaseOrderProductExpenseTemp(:p_product_id, :p_reference_type, :p_reference_number, :p_expense_amount, :p_expense_type, :p_particulars, :p_issuance_date, :p_last_log_by)');
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_type', $p_reference_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_expense_amount', $p_expense_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_expense_type', $p_expense_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_particulars', $p_particulars, PDO::PARAM_STR);
        $stmt->bindValue(':p_issuance_date', $p_issuance_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePurchaseOrder($p_purchase_order_id, $p_purchase_order_type, $p_company_id, $p_supplier_id, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePurchaseOrder(:p_purchase_order_id, :p_purchase_order_type, :p_company_id, :p_supplier_id, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_purchase_order_type', $p_purchase_order_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_supplier_id', $p_supplier_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePurchaseOrderSlipReferenceNumber($p_part_transaction_id, $slip_reference_no, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePurchaseOrderSlipReferenceNumber(:p_part_transaction_id, :slip_reference_no, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':slip_reference_no', $slip_reference_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePurchaseOrderForApproval($p_purchase_order_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE purchase_order SET purchase_order_status = "For Approval", for_approval_date = NOW(), last_log_by = :p_last_log_by WHERE purchase_order_id = :p_purchase_order_id');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePurchaseOrderOnProcess($p_purchase_order_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE purchase_order SET purchase_order_status = "On-Process", onprocess_date  = NOW(), last_log_by = :p_last_log_by WHERE purchase_order_id = :p_purchase_order_id');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePurchaseOrderComplete($p_purchase_order_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE purchase_order SET purchase_order_status = "Completed", completion_date = NOW(), last_log_by = :p_last_log_by WHERE purchase_order_id = :p_purchase_order_id');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function updatePurchaseOrderItemUnitReceive($purchase_order_unit_id, $received_quantity, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE purchase_order_unit SET actual_quantity = actual_quantity + :received_quantity, last_log_by = :p_last_log_by WHERE purchase_order_unit_id = :purchase_order_unit_id');
        $stmt->bindValue(':purchase_order_unit_id', $purchase_order_unit_id, PDO::PARAM_STR);
        $stmt->bindValue(':received_quantity', $received_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function updatePurchaseOrderItemPartReceive($purchase_order_part_id, $received_quantity, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE purchase_order_part SET actual_quantity = actual_quantity + :received_quantity, last_log_by = :p_last_log_by WHERE purchase_order_part_id = :purchase_order_part_id');
        $stmt->bindValue(':purchase_order_part_id', $purchase_order_part_id, PDO::PARAM_STR);
        $stmt->bindValue(':received_quantity', $received_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function updatePurchaseOrderItemSupplyReceive($purchase_order_supply_id, $received_quantity, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE purchase_order_supply SET actual_quantity = actual_quantity + :received_quantity, last_log_by = :p_last_log_by WHERE purchase_order_supply_id = :purchase_order_supply_id');
        $stmt->bindValue(':purchase_order_supply_id', $purchase_order_supply_id, PDO::PARAM_STR);
        $stmt->bindValue(':received_quantity', $received_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function updatePurchaseOrderItemUnitCancel($purchase_order_unit_id, $received_quantity, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE purchase_order_unit SET cancelled_quantity = cancelled_quantity + :received_quantity, last_log_by = :p_last_log_by WHERE purchase_order_unit_id = :purchase_order_unit_id');
        $stmt->bindValue(':purchase_order_unit_id', $purchase_order_unit_id, PDO::PARAM_STR);
        $stmt->bindValue(':received_quantity', $received_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function updatePurchaseOrderItemPartCancel($purchase_order_part_id, $received_quantity, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE purchase_order_part SET cancelled_quantity = cancelled_quantity + :received_quantity, last_log_by = :p_last_log_by WHERE purchase_order_part_id = :purchase_order_part_id');
        $stmt->bindValue(':purchase_order_part_id', $purchase_order_part_id, PDO::PARAM_STR);
        $stmt->bindValue(':received_quantity', $received_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function updatePurchaseOrderItemSupplyCancel($purchase_order_supply_id, $received_quantity, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE purchase_order_supply SET cancelled_quantity = cancelled_quantity + :received_quantity, last_log_by = :p_last_log_by WHERE purchase_order_supply_id = :purchase_order_supply_id');
        $stmt->bindValue(':purchase_order_supply_id', $purchase_order_supply_id, PDO::PARAM_STR);
        $stmt->bindValue(':received_quantity', $received_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePurchaseOrderDraft($p_purchase_order_id, $draft_reason, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE purchase_order SET purchase_order_status = "Draft", last_set_to_draft_reason = :draft_reason, last_set_to_draft_date = NOW(), last_log_by = :p_last_log_by WHERE purchase_order_id = :p_purchase_order_id');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->bindValue(':draft_reason', $draft_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updatePurchaseOrderDraftUnitReceive($p_purchase_order_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE purchase_order_unit SET actual_quantity = 0, cancelled_quantity = 0, last_log_by = :p_last_log_by WHERE purchase_order_id = :p_purchase_order_id');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updatePurchaseOrderDraftPartReceive($p_purchase_order_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE purchase_order_part SET actual_quantity = 0, cancelled_quantity = 0, last_log_by = :p_last_log_by WHERE purchase_order_id = :p_purchase_order_id');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updatePurchaseOrderDraftSupplyReceive($p_purchase_order_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE purchase_order_supply SET actual_quantity = 0, cancelled_quantity = 0, last_log_by = :p_last_log_by WHERE purchase_order_id = :p_purchase_order_id');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePurchaseOrderCancel($p_purchase_order_id, $draft_reason, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE purchase_order SET purchase_order_status = "Cancelled", cancellation_reason = :draft_reason, cancellation_date = NOW(), last_log_by = :p_last_log_by WHERE purchase_order_id = :p_purchase_order_id');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->bindValue(':draft_reason', $draft_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePurchaseOrderApprove($p_purchase_order_id, $draft_reason, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE purchase_order SET purchase_order_status = "Approved", approval_remarks = :draft_reason, approval_date = NOW(), last_log_by = :p_last_log_by WHERE purchase_order_id = :p_purchase_order_id');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->bindValue(':draft_reason', $draft_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertPurchaseOrderItemUnit(
        $purchase_order_id,
        $purchase_request_cart_id,
        $product_category_id,
        $cabin_id,
        $brand_id,
        $model_id,
        $body_type_id,
        $class_id,
        $color_id,
        $make_id,
        $year_model,
        $quantity,
        $unit_id,
        $length,
        $length_unit,
        $price,
        $fx_rate,
        $converted_amount,
        $package_deal,
        $taxes_duties,
        $freight,
        $lto_registration,
        $royalties,
        $conversion,
        $arrastre,
        $wharrfage,
        $insurance,
        $aircon,
        $import_permit,
        $others,
        $total_landed_cost,
        $preorder,
        $remarks,
        $userID
    ) {
        $sql = "
            INSERT INTO purchase_order_unit (
                purchase_order_id, purchase_request_cart_id, product_category_id,
                cabin_id, brand_id, model_id, body_type_id, class_id,
                color_id, make_id, year_model,
                quantity, unit_id, length, length_unit, price,
                fx_rate, converted_amount, package_deal, taxes_duties,
                freight, lto_registration, royalties, conversion,
                arrastre, wharrfage, insurance, aircon,
                import_permit, others, total_landed_cost, preorder,
                remarks, last_log_by
            ) VALUES (
                :purchase_order_id, :purchase_request_cart_id, :product_category_id,
                :cabin_id, :brand_id, :model_id, :body_type_id, :class_id,
                :color_id, :make_id, :year_model,
                :quantity, :unit_id, :length, :length_unit, :price,
                :fx_rate, :converted_amount, :package_deal, :taxes_duties,
                :freight, :lto_registration, :royalties, :conversion,
                :arrastre, :wharrfage, :insurance, :aircon,
                :import_permit, :others, :total_landed_cost, :preorder,
                :remarks, :last_log_by
            )
        ";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([
            'purchase_order_id'        => $purchase_order_id,
            'purchase_request_cart_id' => $purchase_request_cart_id,
            'product_category_id'      => $product_category_id,
            'cabin_id'                 => $cabin_id,
            'brand_id'                 => $brand_id,
            'model_id'                 => $model_id,
            'body_type_id'             => $body_type_id,
            'class_id'                 => $class_id,
            'color_id'                 => $color_id,
            'make_id'                  => $make_id,
            'year_model'               => $year_model,
            'quantity'                 => $quantity,
            'unit_id'                  => $unit_id,
            'length'                   => $length,
            'length_unit'              => $length_unit,
            'price'                    => $price,
            'fx_rate'                  => $fx_rate,
            'converted_amount'         => $converted_amount,
            'package_deal'             => $package_deal,
            'taxes_duties'             => $taxes_duties,
            'freight'                  => $freight,
            'lto_registration'         => $lto_registration,
            'royalties'                => $royalties,
            'conversion'               => $conversion,
            'arrastre'                 => $arrastre,
            'wharrfage'                => $wharrfage,
            'insurance'                => $insurance,
            'aircon'                   => $aircon,
            'import_permit'            => $import_permit,
            'others'                   => $others,
            'total_landed_cost'        => $total_landed_cost,
            'preorder'                 => $preorder,
            'remarks'                  => $remarks,
            'last_log_by'              => $userID
        ]);

    }


    public function createPurchaseOrderUnitEntry($p_purchase_order_id, $p_company_id, $p_reference_no, $p_cost, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createPurchaseOrderUnitEntry(:p_purchase_order_id, :p_company_id, :p_reference_no, :p_cost, :p_last_log_by)');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_reference_no', $p_reference_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePurchaseOrderItemUnit(
        $purchase_order_unit_id,
        $purchase_request_cart_id,
        $product_category_id,
        $cabin_id,
        $brand_id,
        $model_id,
        $body_type_id,
        $class_id,
        $color_id,
        $make_id,
        $year_model,
        $quantity,
        $unit_id,
        $length,
        $length_unit,
        $price,
        $fx_rate,
        $converted_amount,
        $package_deal,
        $taxes_duties,
        $freight,
        $lto_registration,
        $royalties,
        $conversion,
        $arrastre,
        $wharrfage,
        $insurance,
        $aircon,
        $import_permit,
        $others,
        $total_landed_cost,
        $preorder,
        $remarks,
        $userID
    ) {
        $sql = "UPDATE purchase_order_unit SET
                    purchase_request_cart_id = :purchase_request_cart_id,
                    product_category_id     = :product_category_id,
                    cabin_id                = :cabin_id,
                    brand_id                = :brand_id,
                    model_id                = :model_id,
                    body_type_id            = :body_type_id,
                    class_id                = :class_id,
                    color_id                = :color_id,
                    make_id                 = :make_id,
                    year_model              = :year_model,
                    quantity                = :quantity,
                    unit_id                 = :unit_id,
                    length                  = :length,
                    length_unit             = :length_unit,
                    price                   = :price,
                    fx_rate                 = :fx_rate,
                    converted_amount        = :converted_amount,
                    package_deal            = :package_deal,
                    taxes_duties            = :taxes_duties,
                    freight                 = :freight,
                    lto_registration        = :lto_registration,
                    royalties               = :royalties,
                    conversion              = :conversion,
                    arrastre                = :arrastre,
                    wharrfage               = :wharrfage,
                    insurance               = :insurance,
                    aircon                  = :aircon,
                    import_permit           = :import_permit,
                    others                  = :others,
                    total_landed_cost       = :total_landed_cost,
                    preorder                = :preorder,
                    remarks                 = :remarks,
                    last_log_by             = :last_log_by
                WHERE purchase_order_unit_id = :purchase_order_unit_id";

        $stmt = $this->db->getConnection()->prepare($sql);

        $stmt->bindValue(':purchase_order_unit_id', $purchase_order_unit_id, PDO::PARAM_INT);
        $stmt->bindValue(':purchase_request_cart_id', $purchase_request_cart_id, PDO::PARAM_INT);
        $stmt->bindValue(':product_category_id', $product_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':cabin_id', $cabin_id, PDO::PARAM_INT);
        $stmt->bindValue(':brand_id', $brand_id, PDO::PARAM_INT);
        $stmt->bindValue(':model_id', $model_id, PDO::PARAM_INT);
        $stmt->bindValue(':body_type_id', $body_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
        $stmt->bindValue(':color_id', $color_id, PDO::PARAM_INT);
        $stmt->bindValue(':make_id', $make_id, PDO::PARAM_INT);
        $stmt->bindValue(':year_model', $year_model, PDO::PARAM_STR);

        $stmt->bindValue(':quantity', $quantity);
        $stmt->bindValue(':unit_id', $unit_id, PDO::PARAM_INT);
        $stmt->bindValue(':length', $length);
        $stmt->bindValue(':length_unit', $length_unit, PDO::PARAM_INT);
        $stmt->bindValue(':price', $price);

        $stmt->bindValue(':fx_rate', $fx_rate);
        $stmt->bindValue(':converted_amount', $converted_amount);
        $stmt->bindValue(':package_deal', $package_deal);
        $stmt->bindValue(':taxes_duties', $taxes_duties);
        $stmt->bindValue(':freight', $freight);
        $stmt->bindValue(':lto_registration', $lto_registration);
        $stmt->bindValue(':royalties', $royalties);
        $stmt->bindValue(':conversion', $conversion);
        $stmt->bindValue(':arrastre', $arrastre);
        $stmt->bindValue(':wharrfage', $wharrfage);
        $stmt->bindValue(':insurance', $insurance);
        $stmt->bindValue(':aircon', $aircon);
        $stmt->bindValue(':import_permit', $import_permit);
        $stmt->bindValue(':others', $others);

        $stmt->bindValue(':total_landed_cost', $total_landed_cost);
        $stmt->bindValue(':preorder', $preorder, PDO::PARAM_STR);
        $stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $userID, PDO::PARAM_INT);

        $stmt->execute();
    }


    public function createPurchaseOrderEntry($p_part_transaction_id, $p_company_id, $p_reference_number, $p_cost, $p_price, $p_customer_type, $p_is_service, $p_product_status, $p_issuance_for, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createPurchaseOrderEntry(:p_part_transaction_id, :p_company_id, :p_reference_number, :p_cost, :p_price, :p_customer_type, :p_is_service, :p_product_status, :p_issuance_for, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_price', $p_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_customer_type', $p_customer_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_is_service', $p_is_service, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_status', $p_product_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_issuance_for', $p_issuance_for, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function createPurchaseOrderEntryReversed($p_part_transaction_id, $p_company_id, $p_reference_number, $p_cost, $p_price, $p_customer_type, $p_is_service, $p_product_status, $p_issuance_for, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createPurchaseOrderEntryReversed(:p_part_transaction_id, :p_company_id, :p_reference_number, :p_cost, :p_price, :p_customer_type, :p_is_service, :p_product_status, :p_issuance_for, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_price', $p_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_customer_type', $p_customer_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_is_service', $p_is_service, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_status', $p_product_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_issuance_for', $p_issuance_for, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function createPurchaseOrderEntry2($p_part_transaction_id, $p_company_id, $p_reference_number, $p_cost, $p_price, $p_customer_type, $p_is_service, $p_product_status, $p_journal_entry_date, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createPurchaseOrderEntry2(:p_part_transaction_id, :p_company_id, :p_reference_number, :p_cost, :p_price, :p_customer_type, :p_is_service, :p_product_status, :p_journal_entry_date, :p_last_log_by)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_price', $p_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_customer_type', $p_customer_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_is_service', $p_is_service, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_status', $p_product_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_journal_entry_date', $p_journal_entry_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkPurchaseOrderExist
    # Description: Checks if a purchase order exists.
    #
    # Parameters:
    # - $p_purchase_order_id (int): The purchase order ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkPurchaseOrderExist($p_purchase_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPurchaseOrderExist(:p_purchase_order_id)');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function checkPurchaseOrderItemUnitExist($p_purchase_order_unit_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT COUNT(*) AS total
        FROM purchase_order_unit
        WHERE purchase_order_unit_id = :p_purchase_order_unit_id;');
        $stmt->bindValue(':p_purchase_order_unit_id', $p_purchase_order_unit_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getPurchaseOrderCartUnitCount($p_purchase_order_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT COUNT(*) AS total
        FROM purchase_order_unit
        WHERE purchase_order_id = :p_purchase_order_id;');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getPurchaseOrderCartPartCount($p_purchase_order_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT COUNT(*) AS total
        FROM purchase_order_part
        WHERE purchase_order_id = :p_purchase_order_id;');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getPurchaseOrderCartSupplyCount($p_purchase_order_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT COUNT(*) AS total
        FROM purchase_order_supply
        WHERE purchase_order_id = :p_purchase_order_id;');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getPurchaseOrderCartUnit($p_purchase_order_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT *
        FROM purchase_order_unit
        WHERE purchase_order_id = :p_purchase_order_id;');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getPurchaseOrderCartPart($p_purchase_order_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT *
        FROM purchase_order_part
        WHERE purchase_order_id = :p_purchase_order_id;');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getPurchaseOrderCartSupply($p_purchase_order_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT *
        FROM purchase_order_supply
        WHERE purchase_order_id = :p_purchase_order_id;');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function checkPurchaseOrderItemPartExist($p_purchase_order_part_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT COUNT(*) AS total
        FROM purchase_order_part
        WHERE purchase_order_part_id = :p_purchase_order_part_id;');
        $stmt->bindValue(':p_purchase_order_part_id', $p_purchase_order_part_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function checkPurchaseOrderItemSupplyExist($p_purchase_order_supply_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT COUNT(*) AS total
        FROM purchase_order_supply
        WHERE purchase_order_supply_id = :p_purchase_order_supply_id;');
        $stmt->bindValue(':p_purchase_order_supply_id', $p_purchase_order_supply_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_exceeded_part_quantity_count($p_purchase_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL get_exceeded_part_quantity_count(:p_purchase_order_id)');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function check_exceed_part_quantity($p_purchase_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL check_exceed_part_quantity(:p_purchase_order_id)');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function check_linked_job_order($p_purchase_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL check_linked_job_order(:p_purchase_order_id)');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkPurchaseOrderCartExist($p_part_transaction_cart_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPurchaseOrderCartExist(:p_part_transaction_cart_id)');
        $stmt->bindValue(':p_part_transaction_cart_id', $p_part_transaction_cart_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePurchaseOrder
    # Description: Deletes the purchase order.
    #
    # Parameters:
    # - $p_purchase_order_id (int): The purchase order ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deletePurchaseOrder($p_purchase_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePurchaseOrder(:p_purchase_order_id)');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deletePurchaseOrderUnit($purchase_order_unit_id) {
        $stmt = $this->db->getConnection()->prepare('DELETE FROM purchase_order_unit WHERE purchase_order_unit_id = :purchase_order_unit_id');
        $stmt->bindValue(':purchase_order_unit_id', $purchase_order_unit_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deletePurchaseOrderPart($purchase_order_part_id) {
        $stmt = $this->db->getConnection()->prepare('DELETE FROM purchase_order_part WHERE purchase_order_part_id = :purchase_order_part_id');
        $stmt->bindValue(':purchase_order_part_id', $purchase_order_part_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deletePurchaseOrderSupply($purchase_order_supply_id) {
        $stmt = $this->db->getConnection()->prepare('DELETE FROM purchase_order_supply WHERE purchase_order_supply_id = :purchase_order_supply_id');
        $stmt->bindValue(':purchase_order_supply_id', $purchase_order_supply_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deletePurchaseOrderJobOrder($p_purchase_order_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePurchaseOrderJobOrder(:p_purchase_order_job_order_id)');
        $stmt->bindValue(':p_purchase_order_job_order_id', $p_purchase_order_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deletePurchaseOrderAdditionalJobOrder($p_purchase_order_additional_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePurchaseOrderAdditionalJobOrder(:p_purchase_order_additional_job_order_id)');
        $stmt->bindValue(':p_purchase_order_additional_job_order_id', $p_purchase_order_additional_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function deletePurchaseOrderDocument($p_part_transaction_document_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePurchaseOrderDocument(:p_part_transaction_document_id)');
        $stmt->bindValue(':p_part_transaction_document_id', $p_part_transaction_document_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPurchaseOrder
    # Description: Retrieves the details of a purchase order.
    #
    # Parameters:
    # - $p_purchase_order_id (int): The purchase order ID.
    #
    # Returns:
    # - An array containing the purchase order details.
    #
    # -------------------------------------------------------------
    public function getPurchaseOrder($p_purchase_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getPurchaseOrder(:p_purchase_order_id)');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getPurchaseOrderUnit($purchase_order_unit_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM purchase_order_unit WHERE purchase_order_unit_id = :purchase_order_unit_id');
        $stmt->bindValue(':purchase_order_unit_id', $purchase_order_unit_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getPurchaseOrderPart($purchase_order_part_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM purchase_order_part WHERE purchase_order_part_id = :purchase_order_part_id');
        $stmt->bindValue(':purchase_order_part_id', $purchase_order_part_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getPurchaseOrderSupply($purchase_order_supply_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM purchase_order_supply WHERE purchase_order_supply_id = :purchase_order_supply_id');
        $stmt->bindValue(':purchase_order_supply_id', $purchase_order_supply_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getPurchaseOrderItemCount($p_purchase_order_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT COUNT(purchase_order_cart_id) AS total FROM purchase_order_cart WHERE purchase_order_id = :p_purchase_order_id');
        $stmt->bindValue(':p_purchase_order_id', $p_purchase_order_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPurchaseOrderCartTotal($p_part_transaction_id, $p_type) {
        $stmt = $this->db->getConnection()->prepare('CALL getPurchaseOrderCartTotal(:p_part_transaction_id, :p_type)');
        $stmt->bindValue(':p_part_transaction_id', $p_part_transaction_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generatePurchaseOrderOptions
    # Description: Generates the purchase order options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePurchaseOrderOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePurchaseOrderOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsTransactionID = $row['purchase_order_id'];
            $partsTransactionName = $row['purchase_order_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($partsTransactionID, ENT_QUOTES) . '">' . htmlspecialchars($partsTransactionName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    public function generatePartTransactionReleasedOptions($company_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM part_transaction WHERE (part_transaction_status = "Released" OR part_transaction_status = "Checked") AND company_id = :company_id ORDER BY part_transaction_id ASC');
        $stmt->bindValue(':company_id', $company_id, PDO::PARAM_INT);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsTransactionID = $row['part_transaction_id'];
            $partsTransactionName = $row['reference_number'];
            $issuance_no = $row['issuance_no'];

            $htmlOptions .= '<option value="' . htmlspecialchars($partsTransactionID, ENT_QUOTES) . '">Reference No: ' . htmlspecialchars($partsTransactionName, ENT_QUOTES) .' - Issuance No: '. $issuance_no .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generatePurchaseOrderCheckBox
    # Description: Generates the purchase order check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePurchaseOrderCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePurchaseOrderOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsTransactionID = $row['purchase_order_id'];
            $partsTransactionName = $row['purchase_order_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input parts-transaction-filter" type="checkbox" id="parts-transaction-' . htmlspecialchars($partsTransactionID, ENT_QUOTES) . '" value="' . htmlspecialchars($partsTransactionID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="parts-transaction-' . htmlspecialchars($partsTransactionID, ENT_QUOTES) . '">' . htmlspecialchars($partsTransactionName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>