<?php
// Include the TCPDF library
require_once('assets/libs/TCPDF/tcpdf.php');

// Create a new PDF instance
$pdf = new TCPDF('P', 'mm', array(215.9, 330.2), true, 'UTF-8', false);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set document information
$pdf->SetCreator('CGMI');
$pdf->SetAuthor('CGMI');
$pdf->SetTitle('Sales Proposal');
$pdf->SetSubject('Sales Proposal');

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(15, 15, 15);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(10);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 15);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

$pdf->SetTextColor(255, 0, 0);
// Set some content to display
$html = '<table style="border: 1px solid black; border-collapse: collapse;">
            <tbody>
                <tr style="text-align: center; background-color: red; color: #fff; font-size: 20px;">
                    <td colspan="8" style="border: 1px solid black; border-collapse: collapse;"><b>SALES PROPOSAL</b></td>
                </tr>
                <tr style="font-size: 12px;">
                    <td colspan="4" style="color:#000; border: 1px solid black; border-collapse: collapse;"><br/>&nbsp; NO. 5000000090</td>
                    <td colspan="4" style="color:#000; text-align: right; border: 1px solid black; border-collapse: collapse;">DATE: '. date('d-M-Y') .'</td>
                </tr>
                <tr style="font-size: 10px;">
                    <td colspan="4" style="border: 1px solid black; border-collapse: collapse; margin: 0; padding: 0;">
                        <span style="color:red; font-size:10px; margin: 0; padding: 0;">NAME OF CUSTOMER</span>
                        <p style="font-size:11px; color:#000;">&nbsp; PACHECO, APOLINAR</p>
                    </td>
                    <td colspan="2" style="border: 1px solid black; border-collapse: collapse; margin: 0; padding: 0;">
                        <span style="color:red; font-size:10px; margin: 0; padding: 0;">ADDRESS</span>
                        <p style="font-size:11px; color:#000;">&nbsp; KAALIBANGBANGAN, CITY OF CABANATUAN, NUEVA ECIJA, PHILIPPINES</p>
                    </td>
                    <td colspan="2" style="border: 1px solid black; border-collapse: collapse; margin: 0; padding: 0;">
                        <span style="color:red; font-size:10px; margin: 0; padding: 0;">CONTACT NO.</span>
                        <p style="font-size:11px; color:#000; text-align: justify; text-justify: inter-word;">&nbsp; 09399108659</p>
                    </td>
                </tr>
                <tr style="font-size: 10px;">
                    <td colspan="4" style="border: 1px solid black; border-collapse: collapse; margin: 0; padding: 0;">
                        <span style="color:red; font-size:10px; margin: 0; padding: 0;">CO-BORROWER/CO-MORTGAGOR/CO-MAKER</span>
                        <p style="font-size:12px; color:#000;">&nbsp; CLIENT NAME HERE</p>
                    </td>
                    <td colspan="2" style="border: 1px solid black; border-collapse: collapse; margin: 0; padding: 0;">
                        <span style="color:red; font-size:10px; margin: 0; padding: 0;">ADDRESS</span>
                        <p style="font-size:12px; color:#000;">&nbsp; CLIENT ADDRESS HERE</p>
                    </td>
                    <td colspan="2" style="border: 1px solid black; border-collapse: collapse; margin: 0; padding: 0;">
                        <span style="color:red; font-size:10px; margin: 0; padding: 0;">CONTACT NO.</span>
                        <p style="font-size:12px; color:#000;">&nbsp; 09399108659</p>
                    </td>
                </tr>
            </tbody>
        </table>';





// Write the HTML content to the PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Output the PDF to the browser
$pdf->Output('example.pdf', 'I');
?>