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
    #$summaryTable2 = generatePrint2($productID);
    #$summaryTable3 = generatePrint3($productID);

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
    $pdf->SetTitle('Receiving Report Print');
    $pdf->SetSubject('Receiving Report Print');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->SetFont('times', '', 14);
    $pdf->AddPage();
    $pdf->Image('./assets/images/fuso_logo.jpg', 15, 10, 30, 0, '', '', '', false, 300, '', false, false, 0, false, false, false);
    $pdf->MultiCell(0, 0, '<b>FUSO TARLAC</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Image('./assets/images/cgmi_logo_dark.jpg', 170, 10, 25, 0, '', '', '', false, 300, '', false, false, 0, false, false, false);
    $pdf->Ln(4);
    $pdf->SetFont('times', '', 10);
    $pdf->MultiCell(0, 0, '<b>CHRISTIAN GENERAL MOTORS, INC</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(2);
    $pdf->MultiCell(0, 0, '<b>Authorized Dealer of SOJITZ FUSO PHILIPPINES CORPORATION</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(2);
    $pdf->MultiCell(0, 0, '<b>SAN JOSE, TARLAC CITY</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(2);
    $pdf->MultiCell(0, 0, '<b>Tel. No. (+63) 919 062 6591</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(4);
    $pdf->MultiCell(0, 0, '<b>INCOMING CHECKLIST INSPECTION REPORT</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(4);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(45, 8, 'UNIT RECEIVED BY:', 0, 0, 'L');
    $pdf->Cell(60, 8, '_____________________________________', 0, 0, 'L');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(40, 8, 'DATE RECEIVED:', 0, 0, 'L');
    $pdf->Cell(10, 8, '_____________', 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(45, 8, 'UNIT DELIVERED BY:', 0, 0, 'L');
    $pdf->Cell(60, 8, '_____________________________________', 0, 0, 'L');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(40, 8, 'STOCK NO.:', 0, 0, 'L');
    $pdf->Cell(10, 8, '_____________', 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(45, 8, 'UNIT DESCRIPTION:', 0, 0, 'L');
    $pdf->Cell(60, 8, '_____________________________________', 0, 0, 'L');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(40, 8, 'CONDUCTION NO.:', 0, 0, 'L');
    $pdf->Cell(10, 8, '_____________', 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(25, 8, 'ENGINE NO.:', 0, 0, 'L');
    $pdf->Cell(25, 8, '_________________', 0, 0, 'L');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(30, 8, 'CHASSIS NO.:', 0, 0, 'L');
    $pdf->Cell(25, 8, '_________________', 0, 0, 'L');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(30, 8, 'KM READING:', 0, 0, 'L');
    $pdf->Cell(25, 8, '_____________', 0, 0, 'L');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Ln(8);
    $pdf->Cell(38, 8, 'NUMBER OF KEYS:', 0, 0, 'L');
    $pdf->Cell(30, 8, '__________________', 0, 0, 'L');
    $pdf->Ln(8);

    $pdf->Ln(2);
    $pdf->SetFont('times', '', 8.2);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');
    /*$pdf->Ln(-3);
    $pdf->SetFont('times', '', 7);
    $pdf->MultiCell(0, 0, '<b>ACCESSORIES:</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(2);
    $pdf->writeHTML($summaryTable2, true, false, true, false, '');
    $pdf->SetFont('times', '', 7);
    $pdf->Ln(-3);
    $pdf->MultiCell(0, 0, '<b>PARTS:</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(2);
    $pdf->writeHTML($summaryTable3, true, false, true, false, '');*/

    

    // Output the PDF to the browser
    $pdf->Output('receiving-report.pdf', 'I');
    ob_end_flush();

    function generatePrint($productID){
        
      

        $response = '<table border="0.5" width="100%" cellpadding="2" align="left">
                        <tbody>
                        <tr style="background-color:black;color:white;">
                            <td style="text-align:center"><b>VEHICLE-EXTERIOR</b></td>
                            <td style="text-align:center"><b>FUNCTIONAL (YES OR NO)</b></td>
                            <td style="text-align:center" colspan="2"><b>VEHICLE ACCESSORIES</b></td>
                        </tr>
                        <tr>
                            <td style="text-align:center"><b>ITEM</b></td>
                            <td style="text-align:center"><b>STATUS</b></td>
                            <td style="text-align:center"><b>ITEM</b></td>
                            <td style="text-align:center"><b>STATUS</b></td>
                        </tr>
                        <tr>
                            <td>WINDSHIELD (CRACKED)</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td>SINGLE AC</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                        </tr>
                        <tr>
                            <td>WINDSHIELD WIPERS (FUNCTIONAL)</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td>DUAL AC</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                        </tr>
                        <tr>
                            <td>TAIL LIGHT/BRAKE LIGHT</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td>STOP BUZZER</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                        </tr>
                        <tr>
                            <td>FOG LIGHT</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td>AFCS</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                        </tr>
                        <tr>
                            <td>TIRES IN GOOD CONDITION</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td>DROP HANDLE</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                        </tr>
                        <tr>
                            <td>OIL LEAKS UNDER VEHICLE</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td>WIFI ROUTER</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                        </tr>
                        <tr>
                            <td>NO FUEL LEAKS (ENGINE)</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td>GPS</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                        </tr>
                        <tr>
                            <td>EXHAUST SYSTEM IN GOOD CONDITION</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td>TV</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                        </tr>
                        <tr>
                            <td>BUMPER SIDE MIRROR</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td>CCTV</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                        </tr>
                        <tr>
                            <td>TURN SIGNAL LIGHTS</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td>CCTV MONITOR</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                        </tr>
                        <tr style="background-color:black;color:white;">
                            <td style="text-align:center"><b>VEHICLE-INTERIOR</b></td>
                            <td style="text-align:center"><b>FUNCTIONAL (YES OR NO)</b></td>
                            <td style="text-align:center;background-color:white;color:white;"></td>
                            <td style="text-align:center;background-color:white;color:white;"></td>
                        </tr>
                        <tr>
                            <td style="text-align:center"><b>ITEM</b></td>
                            <td style="text-align:center"><b>STATUS</b></td>
                            <td style="text-align:center"><b></b></td>
                            <td style="text-align:center"><b></b></td>
                        </tr>
                        <tr>
                            <td>STEERING WHEEL</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td></td>
                            <td style="text-align:center"></td>
                        </tr>
                        <tr>
                            <td>HEADLIGHT SWITCH</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td></td>
                            <td style="text-align:center"></td>
                        </tr>
                        <tr>
                            <td>RADIO FUNCTIONAL</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td></td>
                            <td style="text-align:center"></td>
                        </tr>
                        <tr>
                            <td>PANEL GAUGE FUNCTIONAL</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td></td>
                            <td style="text-align:center"></td>
                        </tr>
                        <tr>
                            <td>AIRCON FUNCTIONAL</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td></td>
                            <td style="text-align:center"></td>
                        </tr>
                        <tr>
                            <td>NO WARNING LIGHTS ON ( DASH PANEL)</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td></td>
                            <td style="text-align:center"></td>
                        </tr>
                        <tr>
                            <td>WINDSHIELD WIPER FLUID (FUNCTIONAL)</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td></td>
                            <td style="text-align:center"></td>
                        </tr>
                        <tr>
                            <td>SEAT BELTS LH/RH (FUNCTIONAL)</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td></td>
                            <td style="text-align:center"></td>
                        </tr>
                        <tr>
                            <td>INTERIORS LIGHTS</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td></td>
                            <td style="text-align:center"></td>
                        </tr>
                        <tr>
                            <td>SUN VISOR LH/RH</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td></td>
                            <td style="text-align:center"></td>
                        </tr>
                        <tr>
                            <td>REAR VIEW MIRRORS</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td></td>
                            <td style="text-align:center"></td>
                        </tr>
                        <tr>
                            <td>POWER WINDOW SWITCH (FUNCTIONAL)</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td></td>
                            <td style="text-align:center"></td>
                        </tr>
                        <tr>
                            <td>WINDOW LH/RH (FUNCTIONAL)</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td></td>
                            <td style="text-align:center"></td>
                        </tr>
                        <tr>
                            <td>POWER LOCK</td>
                            <td style="text-align:center">YES  ______ NO_______</td>
                            <td></td>
                            <td style="text-align:center"></td>
                        </tr>
                        <tr>
                            <td>OTHERS</td>
                            <td style="text-align:center">SPECIFY ______________________</td>
                        </tr>
                        <tr>
                            <td style="background-color:black;color:white;text-align:center" colspan="2"><b>NOTABLE OBSERVATION ON VEHICLE:</b></td>
                            <td style="text-align:center" colspan="2"></td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td style="text-align:left" colspan="2"><br/><br/><br/>
                                INSPECTED BY: ________________________<br/><br/>
                                DATE INSPECTED: ________________________<br/><br/>
                                TIME: ________________________<br/>
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