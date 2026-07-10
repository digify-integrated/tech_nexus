<?php
/**
* Class PMSCatalogModel
*
* The PMSCatalogModel class handles pms catalog related operations and interactions.
*/
class PMSCatalogModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updatePMSCatalog
    # Description: Updates the pms catalog.
    #
    # Parameters:
    # - $p_pms_catalog_id (int): The pms catalog ID.
    # - $p_pms_catalog_name (string): The pms catalog name.
    # - $p_pms_catalog_description (string): The pms catalog description.
    # - $company_id (double): The max file size.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updatePMSCatalog(
        $p_pms_catalog_id,
        $p_pms_catalog_name,
        $p_pms_catalog_description,
        $company_id,
        $p_last_log_by
    ) {
        $sql = "
            UPDATE pms_catalog
            SET
                pms_catalog_name = :pms_catalog_name,
                description = :description,
                company_id = :company_id,
                last_log_by = :last_log_by
            WHERE pms_catalog_id = :pms_catalog_id
        ";

        $stmt = $this->db->getConnection()->prepare($sql);

        $stmt->bindValue(':pms_catalog_id', $p_pms_catalog_id, PDO::PARAM_INT);
        $stmt->bindValue(':pms_catalog_name', $p_pms_catalog_name, PDO::PARAM_STR);
        $stmt->bindValue(':description', $p_pms_catalog_description, PDO::PARAM_STR);
        $stmt->bindValue(':company_id', $company_id, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $p_last_log_by, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updatePMSMatrix(
        $p_pms_matrix_id,
        $p_odometer,
        $p_months_interval,
        $p_consumables,
        $p_consumables_quantity,
        $p_consumables_total,
        $p_labor_cost,
        $p_labor_quantity,
        $p_labor_total,
        $p_last_log_by
    ) {
        $sql = "
            UPDATE pms_matrix
            SET
                odometer = :odometer,
                months_interval = :months_interval,
                consumables = :consumables,
                consumables_quantity = :consumables_quantity,
                consumables_total = :consumables_total,
                labor_cost = :labor_cost,
                labor_quantity = :labor_quantity,
                labor_total = :labor_total,
                last_log_by = :last_log_by
            WHERE pms_matrix_id = :pms_matrix_id
        ";

        $stmt = $this->db->getConnection()->prepare($sql);

        $stmt->bindValue(':pms_matrix_id', $p_pms_matrix_id, PDO::PARAM_INT);
        $stmt->bindValue(':odometer', $p_odometer, PDO::PARAM_STR);
        $stmt->bindValue(':months_interval', $p_months_interval, PDO::PARAM_STR);
        $stmt->bindValue(':consumables', $p_consumables, PDO::PARAM_STR);
        $stmt->bindValue(':consumables_quantity', $p_consumables_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':consumables_total', $p_consumables_total, PDO::PARAM_STR);
        $stmt->bindValue(':labor_cost', $p_labor_cost, PDO::PARAM_STR);
        $stmt->bindValue(':labor_quantity', $p_labor_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':labor_total', $p_labor_total, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $p_last_log_by, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updatePMSMatrixPart(
        $p_pms_matrix_part_id,
        $p_part_id,
        $p_required_quantity,
        $p_unit_id,
        $p_last_log_by
    ) {
        $sql = "
            UPDATE pms_matrix_part
            SET
                part_id = :part_id,
                required_quantity = :required_quantity,
                unit_id = :unit_id,
                last_log_by = :last_log_by
            WHERE pms_matrix_part_id = :pms_matrix_part_id
        ";

        $stmt = $this->db->getConnection()->prepare($sql);

        $stmt->bindValue(':pms_matrix_part_id', $p_pms_matrix_part_id, PDO::PARAM_INT);
        $stmt->bindValue(':part_id', $p_part_id, PDO::PARAM_INT);
        $stmt->bindValue(':required_quantity', $p_required_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':unit_id', $p_unit_id, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $p_last_log_by, PDO::PARAM_INT);

        return $stmt->execute();
        
    }

    public function updatePMSCatalogStatus(
        $p_pms_catalog_id,
        $p_status,
        $p_last_log_by
    ) {
        $sql = "
            UPDATE pms_catalog
            SET
                status = :status,
                last_log_by = :last_log_by
            WHERE pms_catalog_id = :pms_catalog_id
        ";

        $stmt = $this->db->getConnection()->prepare($sql);

        $stmt->bindValue(':pms_catalog_id', $p_pms_catalog_id, PDO::PARAM_INT);
        $stmt->bindValue(':status', $p_status, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $p_last_log_by, PDO::PARAM_INT);

        return $stmt->execute();
        
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPMSCatalog
    # Description: Inserts the pms catalog.
    #
    # Parameters:
    # - $p_pms_catalog_name (string): The pms catalog name.
    # - $p_pms_catalog_description (string): The pms catalog description.
    # - $company_id (double): The max file size.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertPMSCatalog(
        $p_pms_catalog_name,
        $p_pms_catalog_description,
        $company_id,
        $p_last_log_by
    ) {
        $sql = "
            INSERT INTO pms_catalog (
                pms_catalog_name,
                description,
                company_id,
                last_log_by
            )
            VALUES (
                :pms_catalog_name,
                :description,
                :company_id,
                :last_log_by
            )
        ";

        $stmt = $this->db->getConnection()->prepare($sql);

        $stmt->bindValue(':pms_catalog_name', $p_pms_catalog_name, PDO::PARAM_STR);
        $stmt->bindValue(':description', $p_pms_catalog_description, PDO::PARAM_STR);
        $stmt->bindValue(':company_id', $company_id, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $p_last_log_by, PDO::PARAM_INT);

        $stmt->execute();

        return $this->db->getConnection()->lastInsertId();
    }

    public function insertPMSMatrix(
        $pms_catalog_id,
        $p_odometer,
        $p_months_interval,
        $p_consumables,
        $p_consumables_quantity,
        $p_consumables_total,
        $p_labor_cost,
        $p_labor_quantity,
        $p_labor_total,
        $p_last_log_by
    ) {
        $sql = "
            INSERT INTO pms_matrix (
                pms_catalog_id,
                odometer,
                months_interval,
                consumables,
                consumables_quantity,
                consumables_total,
                labor_cost,
                labor_quantity,
                labor_total,
                last_log_by
            )
            VALUES (
                :pms_catalog_id,
                :odometer,
                :months_interval,
                :consumables,
                :consumables_quantity,
                :consumables_total,
                :labor_cost,
                :labor_quantity,
                :labor_total,
                :last_log_by
            )
        ";

        $stmt = $this->db->getConnection()->prepare($sql);

        $stmt->bindValue(':pms_catalog_id', $pms_catalog_id, PDO::PARAM_INT);
        $stmt->bindValue(':odometer', $p_odometer, PDO::PARAM_STR);
        $stmt->bindValue(':months_interval', $p_months_interval, PDO::PARAM_STR);
        $stmt->bindValue(':consumables', $p_consumables, PDO::PARAM_STR);
        $stmt->bindValue(':consumables_quantity', $p_consumables_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':consumables_total', $p_consumables_total, PDO::PARAM_STR);
        $stmt->bindValue(':labor_cost', $p_labor_cost, PDO::PARAM_STR);
        $stmt->bindValue(':labor_quantity', $p_labor_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':labor_total', $p_labor_total, PDO::PARAM_STR);
        $stmt->bindValue(':last_log_by', $p_last_log_by, PDO::PARAM_INT);

        $stmt->execute();
    }
    public function insertPMSMatrixPart(
        $pms_matrix_id,
        $p_part_id,
        $p_required_quantity,
        $p_unit_id,
        $p_last_log_by
    ) {
        $sql = "
            INSERT INTO pms_matrix_part (
                pms_matrix_id,
                part_id,
                required_quantity,
                unit_id,
                last_log_by
            )
            VALUES (
                :pms_matrix_id,
                :part_id,
                :required_quantity,
                :unit_id,
                :last_log_by
            )
        ";

        $stmt = $this->db->getConnection()->prepare($sql);

        $stmt->bindValue(':pms_matrix_id', $pms_matrix_id, PDO::PARAM_INT);
        $stmt->bindValue(':part_id', $p_part_id, PDO::PARAM_INT);
        $stmt->bindValue(':required_quantity', $p_required_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':unit_id', $p_unit_id, PDO::PARAM_INT);
        $stmt->bindValue(':last_log_by', $p_last_log_by, PDO::PARAM_INT);

        $stmt->execute();

        return $this->db->getConnection()->lastInsertId();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPMSCatalogFileExtension
    # Description: Inserts the pms catalog file extension.
    #
    # Parameters:
    # - $p_pms_catalog_id (int): The pms catalog ID.
    # - $p_file_extension_id (int): The file extension ID.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertPMSCategory($p_pms_catalog_id, $product_subcategory_id, $last_log_by) {
        $stmt = $this->db->getConnection()->prepare('INSERT INTO pms_matrix_category (pms_catalog_id, product_subcategory_id, last_log_by) VALUES (:p_pms_catalog_id, :p_product_subcategory_id, :p_last_log_by)');
        $stmt->bindValue(':p_pms_catalog_id', $p_pms_catalog_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_subcategory_id', $product_subcategory_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertPMSMatrixAlternatePart($pms_matrix_id, $pms_matrix_part_id, $part_id, $last_log_by) {
        $stmt = $this->db->getConnection()->prepare('INSERT INTO pms_matrix_alternate_part (pms_matrix_id, pms_matrix_part_id, part_id, last_log_by) VALUES (:p_pms_matrix_id, :p_pms_matrix_part_id, :p_part_id, :p_last_log_by)');
        $stmt->bindValue(':p_pms_matrix_id', $pms_matrix_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_pms_matrix_part_id', $pms_matrix_part_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_part_id', $part_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkPMSCatalogExist
    # Description: Checks if a pms catalog exists.
    #
    # Parameters:
    # - $p_pms_catalog_id (int): The pms catalog ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkPMSCatalogExist($p_pms_catalog_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT COUNT(*) AS total
        FROM pms_catalog
        WHERE pms_catalog_id = :p_pms_catalog_id;');
        $stmt->bindValue(':p_pms_catalog_id', $p_pms_catalog_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function checkPMSMatrixExist($p_pms_matrix_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT COUNT(*) AS total
        FROM pms_matrix
        WHERE pms_matrix_id = :p_pms_matrix_id;');
        $stmt->bindValue(':p_pms_matrix_id', $p_pms_matrix_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function checkPMSMatrixPartExist($p_pms_matrix_part_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT COUNT(*) AS total
        FROM pms_matrix_part
        WHERE pms_matrix_part_id = :p_pms_matrix_part_id;');
        $stmt->bindValue(':p_pms_matrix_part_id', $p_pms_matrix_part_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePMSCatalog
    # Description: Deletes the pms catalog.
    #
    # Parameters:
    # - $p_pms_catalog_id (int): The pms catalog ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deletePMSCatalog($p_pms_catalog_id) {
        $stmt = $this->db->getConnection()->prepare('DELETE FROM pms_catalog WHERE pms_catalog_id = :p_pms_catalog_id');
        $stmt->bindValue(':p_pms_catalog_id', $p_pms_catalog_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    public function deletePMSMatrix($p_pms_matrix_id) {
        $stmt = $this->db->getConnection()->prepare('DELETE FROM pms_matrix WHERE pms_matrix_id = :p_pms_matrix_id');
        $stmt->bindValue(':p_pms_matrix_id', $p_pms_matrix_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deletePMSMatrixPart($p_pms_matrix_part_id) {
        $stmt = $this->db->getConnection()->prepare('DELETE FROM pms_matrix_part WHERE pms_matrix_part_id = :p_pms_matrix_part_id');
        $stmt->bindValue(':p_pms_matrix_part_id', $p_pms_matrix_part_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deletePMSCategory($p_pms_catalog_id) {
        $stmt = $this->db->getConnection()->prepare('DELETE FROM pms_matrix_category WHERE pms_catalog_id = :p_pms_catalog_id');
        $stmt->bindValue(':p_pms_catalog_id', $p_pms_catalog_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deletePMSMatrixAlternateParts($p_pms_matrix_part_id) {
        $stmt = $this->db->getConnection()->prepare('DELETE FROM pms_matrix_alternate_part WHERE pms_matrix_part_id = :p_pms_matrix_part_id');
        $stmt->bindValue(':p_pms_matrix_part_id', $p_pms_matrix_part_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPMSCatalog
    # Description: Retrieves the details of a pms catalog.
    #
    # Parameters:
    # - $p_pms_catalog_id (int): The pms catalog ID.
    #
    # Returns:
    # - An array containing the pms catalog details.
    #
    # -------------------------------------------------------------
    public function getPMSCatalog($p_pms_catalog_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM pms_catalog WHERE pms_catalog_id = :p_pms_catalog_id');
        $stmt->bindValue(':p_pms_catalog_id', $p_pms_catalog_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getPMSMatrix($p_pms_matrix_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM pms_matrix WHERE pms_matrix_id = :p_pms_matrix_id');
        $stmt->bindValue(':p_pms_matrix_id', $p_pms_matrix_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPMSMatrices($p_pms_catalog_id) {
        $stmt = $this->db->getConnection()->prepare("
            SELECT
                *
            FROM pms_matrix
            WHERE pms_catalog_id = :p_pms_catalog_id
            ORDER BY CAST(odometer AS UNSIGNED) ASC
        ");
        $stmt->bindValue(':p_pms_catalog_id', $p_pms_catalog_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPMSMatrixPart($p_pms_matrix_part_id) {
        $stmt = $this->db->getConnection()->prepare("
            SELECT *
            FROM pms_matrix_part
            WHERE pms_matrix_part_id = :p_pms_matrix_part_id
        ");
        $stmt->bindValue(':p_pms_matrix_part_id', $p_pms_matrix_part_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPMSMatrixParts($pms_matrix_id)
    {
        $stmt = $this->db->getConnection()->prepare("
            SELECT
                pmp.pms_matrix_part_id,
                pmp.pms_matrix_id,
                pmp.part_id,
                p.description AS part_name,
                pmp.required_quantity,
                pmp.unit_id,
                u.unit_name,
                pmp.last_log_by,
                pmp.created_at,
                pmp.updated_at
            FROM pms_matrix_part pmp
            INNER JOIN part p
                ON p.part_id = pmp.part_id
            LEFT JOIN unit u
                ON u.unit_id = pmp.unit_id
            WHERE pmp.pms_matrix_id = :pms_matrix_id
            ORDER BY p.description ASC
        ");

        $stmt->bindValue(':pms_matrix_id', $pms_matrix_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getPMSCatalogSubcategories($p_pms_catalog_id) {

        $stmt = $this->db->getConnection()->prepare("
            SELECT product_subcategory_id
            FROM pms_matrix_category
            WHERE pms_catalog_id = :p_pms_catalog_id
        ");

        $stmt->bindValue(':p_pms_catalog_id', $p_pms_catalog_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getPMSMatrixPartAternate($pms_matrix_part_id) {

        $stmt = $this->db->getConnection()->prepare("
            SELECT part_id
            FROM pms_matrix_alternate_part
            WHERE pms_matrix_part_id = :pms_matrix_part_id
        ");

        $stmt->bindValue(':pms_matrix_part_id', $pms_matrix_part_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getPMSMatrixAlternateParts($pms_matrix_part_id)
    {
        $stmt = $this->db->getConnection()->prepare("
            SELECT
                a.pms_matrix_alternate_part_id,
                a.part_id,
                p.description AS part_name
            FROM pms_matrix_alternate_part a
            INNER JOIN part p
                ON p.part_id = a.part_id
            WHERE a.pms_matrix_part_id = :pms_matrix_part_id
            ORDER BY p.description ASC
        ");

        $stmt->bindValue(':pms_matrix_part_id', $pms_matrix_part_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function generateGeneratePartByCompanyOptions($company_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT part_id, description FROM part WHERE company_id IN (?) ORDER BY description');
        $stmt->execute([$company_id]);
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsID = $row['part_id'];
            $description = $row['description'];

            $htmlOptions .= '<option value="' . htmlspecialchars($partsID, ENT_QUOTES) . '">' .  htmlspecialchars($description, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>