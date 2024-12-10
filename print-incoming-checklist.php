<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Load TCPDF library
    require('assets/libs/tcpdf2/tcpdf.php');

    // Load required files
    require_once 'config/config.php';
    require_once 'session.php';
    require_once 'model/database-model.php';
    require_once 'model/system-model.php';
    require_once 'model/user-model.php';
    require_once 'model/product-model.php';
    require_once 'model/product-category-model.php';
    require_once 'model/product-subcategory-model.php';
    require_once 'model/company-model.php';
    require_once 'model/body-type-model.php';
    require_once 'model/warehouse-model.php';
    require_once 'model/unit-model.php';
    require_once 'model/color-model.php';
    require_once 'model/brand-model.php';
    require_once 'model/cabin-model.php';
    require_once 'model/make-model.php';
    require_once 'model/model-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel(new DatabaseModel, new SystemModel);
    $productModel = new ProductModel($databaseModel);
    $productCategoryModel = new ProductCategoryModel($databaseModel);
    $productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
    $companyModel = new CompanyModel($databaseModel);
    $bodyTypeModel = new BodyTypeModel($databaseModel);
    $warehouseModel = new WarehouseModel($databaseModel);
    $unitModel = new UnitModel($databaseModel);
    $colorModel = new ColorModel($databaseModel);
    $brandModel = new BrandModel($databaseModel);
    $cabinModel = new CabinModel($databaseModel);
    $makeModel = new MakeModel($databaseModel);
    $modelModel = new ModelModel($databaseModel);

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: dashboard.php');
          exit;
        }
        
        $productID = $_GET['id'];

        $productDetails = $productModel->getProduct($productID);
        $rrNumber = $productDetails['rr_no'];
    }

    $summaryTable = generatePrint($productID);
    $summaryTable2 = generatePrint2($productID);
    $summaryTable3 = generatePrint3($productID);

    ob_start();

    // Create TCPDF instance
    $pdf = new TCPDF('P', 'mm', array(330.2, 215.9), true, 'UTF-8', false);

   // Disable header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetPageOrientation('P');

    // Set PDF metadata
    $pdf->SetCreator('CGMI');
    $pdf->SetAuthor('CGMI');
    $pdf->SetTitle('Incoming Checklist Print');
    $pdf->SetSubject('Incoming Checklist Print');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 10, 15);
    $pdf->SetHeaderMargin(0);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 10);

    // Add a page
    $pdf->SetFont('times', '', 9);
    $pdf->AddPage();
    $pdf->MultiCell(0, 0, '<b>INCOMING CHECKLIST</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(2);

    $pdf->Ln(2);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');
    $pdf->Ln(-3);
    $pdf->SetFont('times', '', 7);
    $pdf->MultiCell(0, 0, '<b>ACCESSORIES:</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(2);
    $pdf->writeHTML($summaryTable2, true, false, true, false, '');
    $pdf->SetFont('times', '', 7);
    $pdf->Ln(-3);
    $pdf->MultiCell(0, 0, '<b>PARTS:</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(2);
    $pdf->writeHTML($summaryTable3, true, false, true, false, '');

    $pdf->SetFont('times', '', 8);
    

    // Output the PDF to the browser
    $pdf->Output('receiving-report.pdf', 'I');
    ob_end_flush();

    function generatePrint($productID){
        
        require_once 'model/database-model.php';
        require_once 'model/product-model.php';
        require_once 'model/product-category-model.php';
        require_once 'model/product-subcategory-model.php';
        require_once 'model/company-model.php';
        require_once 'model/body-type-model.php';
        require_once 'model/warehouse-model.php';
        require_once 'model/unit-model.php';
        require_once 'model/color-model.php';
        require_once 'model/brand-model.php';
        require_once 'model/cabin-model.php';
        require_once 'model/make-model.php';
        require_once 'model/supplier-model.php';
        require_once 'model/model-model.php';
        require_once 'model/class-model.php';
    
        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $productModel = new ProductModel($databaseModel);
        $productCategoryModel = new ProductCategoryModel($databaseModel);
        $productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
        $companyModel = new CompanyModel($databaseModel);
        $bodyTypeModel = new BodyTypeModel($databaseModel);
        $warehouseModel = new WarehouseModel($databaseModel);
        $unitModel = new UnitModel($databaseModel);
        $colorModel = new ColorModel($databaseModel);
        $brandModel = new BrandModel($databaseModel);
        $cabinModel = new CabinModel($databaseModel);
        $makeModel = new MakeModel($databaseModel);
        $modelModel = new ModelModel($databaseModel);
        $supplierModel = new SupplierModel($databaseModel);
        $classModel = new ClassModel($databaseModel);

        $productDetails = $productModel->getProduct($productID);
        $stock_number = $productDetails['stock_number'];
        $supplier_id = $productDetails['supplier_id'];
        $ref_no = $productDetails['ref_no'];
        $class_id = $productDetails['class_id'];
        $color_id = $productDetails['color_id'];
        $engine_number = $productDetails['engine_number'];
        $chassis_number = $productDetails['chassis_number'];
        $plate_number = $productDetails['plate_number'];
        $description = $productDetails['description'];

        $supplierName = $supplierModel->getSupplier($supplier_id)['supplier_name'] ?? null;
        $className = $classModel->getClass($class_id)['class_name'] ?? null;
        $colorName = $colorModel->getColor($color_id)['color_name'] ?? null;

        $rrDate = $systemModel->checkDate('summary', $productDetails['rr_date'], '', 'm/d/Y', '');

        $response = '<table border="0.5" width="100%" cellpadding="2" align="left">
                        <tbody>
                        <tr>
                            <td><small>STOCK NO.</small><br/>
                                '. $stock_number .'
                            </td>
                            <td><small>SUPPLIER</small><br/>
                                '. $supplierName .'
                            </td>
                            <td><small>DATE RECEIVED</small><br/>
                                '. $rrDate .'
                            </td>
                            <td><small>STOCK CLASS</small><br/>
                                '. $className .'
                            </td>
                        </tr>
                        <tr>
                            <td><small>ENGINE NO.</small><br/>
                                '. $engine_number .'
                            </td>
                            <td><small>CHASSIS</small><br/>
                                '. $chassis_number .'
                            </td>
                            <td><small>PLATE NO.</small><br/>
                                '. $plate_number .'
                            </td>
                            <td><small>COLOR</small><br/>
                                '. $colorName .'
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"><small>STOCK DESCRIPTION</small><br/>
                                '. $description .'
                            </td>
                        </tr>
                    </tbody>
            </table>';

        return $response;
    }


    function generatePrint2($productID){

        $response = '<table width="100%" cellpadding="2" align="left">
                        <tbody>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">1 KEPES</td>
                            <td width="5%">______</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">8 STAINLESS SIDE FENDER</td>
                            <td width="5%">______</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">15 SPOILER</td>
                            <td width="5%">______</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">2 WINDBREAKER</td>
                            <td width="5%">______</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">9 STAINLESS FENDER TEXT</td>
                            <td width="5%">______</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">16 RAIN VISOR</td>
                            <td width="5%">______</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">3 STAINLESS RADIATOR GRILL</td>
                            <td width="5%">______</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">10 STAINLESS LOWER DOOR PANEL</td>
                            <td width="5%">______</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">17 TOP LOAD CARRIER</td>
                            <td width="5%">______</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">4 STAINLESS SIDE MIRROR COVER</td>
                            <td width="5%">______</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">11 WIPER WINGS</td>
                            <td width="5%">______</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">18 LADDER</td>
                            <td width="5%">______</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">5 STAINLESS FRONT PANEL</td>
                            <td width="5%">______</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">12 HUB CAP</td>
                            <td width="5%">______</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">19 STEREO</td>
                            <td width="5%">______</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">6 STAINLESS BUMPER</td>
                            <td width="5%">______</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">13 SIDE GUARD</td>
                            <td width="5%">______</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">20 OTHER (PLS SPECIFY)</td>
                            <td width="5%">______</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">7 STAINLESS CORNER VANE L/R</td>
                            <td width="5%">______</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">14 SIDE LITE</td>
                            <td width="5%">______</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">______________</td>
                            <td width="5%">______</td>
                        </tr>
                    </tbody>
                </table>';

        return $response;
    }

    function generatePrint3($productID){

        $response = '<table width="100%" cellpadding="2" align="left">
                        <tbody>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">1 STEEL FLOORING</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">49 BUMPER BRACKET</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">97 MUFFLER</td>
                            <td width="16%" border="0.5" rowspan="28"><span style="color:red"><b>JOB ORDER TO BE DONE</b></span></td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">2 PANEL GAUGE ASSY</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">50 GRILL</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">98 MUFFLER EXT</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">3 SPEEDOMETER</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">51 HEADLIGHT APRON</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">99 MUDGUARD W/ FRAME FRONT</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">4 TEPERATURE</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">52 HYDROVAC</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">100 MUDGUARD W/ FRAME PLATE</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">5 FUEL GAUGE</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">53 AIR TANK</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">101 TILT SPRING</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">6 GLOVE COMPARTMENT</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">54 AIR CLEANER</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">102 FRONT SPRING</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">7 RADIO</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">55 AIR CLEANER HOSE</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">103 REAR SPRING</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">8 RADIO KNOBS</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">56 AIR CLEANER HOSE W/ EXT</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">104 FRONT AXLE</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">9 CIGARETTE LIGHTER</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">57 ANTENNA</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">105 REAR AXLE</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">10 FUSE COVER</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">58 HAND BRAKE</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">106 BRAKE HOSE FRONT</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">11 BRAKE FLUID COVER</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">59 ACCELERATOR CABLE</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">107 BRAKE HOSE REAR</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">12 AIR-CON VAPOR</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">60 RPM CABLE</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">108 STUD VOLT FRONT</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">13 AIR-CON BLOWER</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">61 SPEEDOMETER CABLE</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">109 STUD VOLT REAR</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">14 AIR-CON COMPRESSOR</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">62 SELECTOR CABLE</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">110 ENGINE MOUNTING SUPPORT</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">15 AIR-CON CONDENSER</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">63 SHIFTER LEVER</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">111 ENGINE SUPPORT</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">16 ASH TRAY</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">64 STOPPING RUBBER PAD L/R</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">112 TRANSMISSION SUPPORT</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">17 ASSIST HANDLE</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">65 ENGINE SIDE COVER</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">113 SPARE TIRE CARRIER</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">18 DRIVER SEAT</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">66 CAB LOCK</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">114 ENGINE STOP MOTOR/CABLE</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">19 MIDDLE SEAT</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">67 CAB SPRING</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">115 POWER STEERING</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">20 PASSENGER SEAT</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">68 CAB STAND</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">116 P/ STEERING W/ CAP</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">21 STEERING WHEEL</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">69 CAB STAND SUPPORT</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">117 INTAKE MANIFOLD</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">22 STEERING COVER</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">70 WATER RESERVOIR</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">118 FUEL FILTER</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">23 IGNITION SWITCH W/ KEY</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">71 RADIATOR</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">119 FAN BLADE</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">24 GEAR BOX</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">72 RADIATOR HOSE UPPER</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">120 ALTERNATOR</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">25 HAND BRAKE</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">73 RADIATOR HOSE LOWER</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">121 STARTER</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">26 SHIFTER LEVER</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">74 RADIATOR CAP</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">122 HAND BRAKE DRUM</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">27 SUN VISOR</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">75 DRAG LINK</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">123 OIL STICK</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">28 REAR VIEW MIRROR</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">76 DRAG LINK NUT</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">124 OIL CAP</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">29 DUMP CABLE</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">77 LINKAGE</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">125 1 SET RIM</td>
                            <td width="16%" border="0.5" rowspan="4"><b>JOB ORDER APPROVED BY:</b></td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">30 SIDE MIRROR</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">78 RING GAUGE PLASTIC</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">126 BODY</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">31 BUMPER MIRROR</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">79 TOOL BOX</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">127 BACK BOARD</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">32 SIDE MIRROR BRACKET</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">80 BATTERY</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">128 RIGHT SIDE</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">33 WINDSHIELD</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">81 PROP W/ YOKE</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">129 LEFT SIDE GATE</td>
                            <td width="16%" border="0.5" rowspan="4"><b>CHECKED BY:</b></td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">34 REAR GLASS</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">82 PROP W/ CB</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">130 TAIL GATE</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">35 WEATHER STRIP</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">83 CENTER BEARING</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">131 CENTER POST</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">36 WIPER W/ BLADE</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">84 U-VOLT FRONT</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">132 REAR POST</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">37 WINDSHIELD WASHER</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">85 U-VOLT REAR</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">133 DUMP HOIST</td>
                            <td width="16%" border="0.5" rowspan="4"><b>DATE:</b></td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">38 LEFT DOOR</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">86 U-VOLT PLATE</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">134 BACK HORN</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">39 RIGHT DOOR</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">87 SPRING PAD RUBBER</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">135 RING GATER</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">40 OKI / L GLASS</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">88 SPRING PLATE</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">136 JACK AND TIRE WRENCH</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">41 QUARTER GLASS</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">89 SHOCK BRACKET</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">137 OTHERS (PLS SPECIFY)</td>
                            <td width="16%" border="0.5" rowspan="4"><b>NOTED BY:</b></td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">42 HEAD GLASS</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">90 SCHOCK FRONT</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">_________________________________</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">43 HEAD LIGHT FRAME</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">91 SCHOCK REAR</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">_________________________________</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">44 SIGNAL LIGHT</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">92 FUEL TANK</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">_________________________________</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">45 PARK LIGHT</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">93 FUEL TANK BRACKET</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">_________________________________</td>
                            <td width="16%" border="0.5" rowspan="4"><b>DATE:</b></td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">46 TAIL LIGHT</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">94 OIL</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">_________________________________</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">47 BUMPER</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">95 EXHAUST PIPE EXT</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">_________________________________</td>
                        </tr>
                        <tr>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">48 BUMPER LIGHT</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">96 EXHAUST MANIFOLD GASKET</td>
                            <td width="3%" border="0.5"></td>
                            <td width="25%">_________________________________</td>
                        </tr>
                    </tbody>
                </table>';

        return $response;
    }
?>