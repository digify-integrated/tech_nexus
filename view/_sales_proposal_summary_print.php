<div class="print-summary">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-border-style mw-100">
                <div class="table-responsive">
                    <table class="table table-bordered text-uppercase text-wrap">
                        <tbody>
                            <tr>
                                <td colspan="8" class="text-center bg-danger pb-0">
                                    <h3 class="font-size-15 fw-bold text-light">SALES PROPOSAL</h3>
                                </td>
                            </tr>
                            <tr>
                                <tr>
                                    <td colspan="3"><b>No. <span id="summary-sales-proposal-number"></span></b></td>
                                    <td colspan="2" class="text-center"><b>Status: <?php echo $salesProposalStatus; ?></b></td>
                                    <td colspan="3" class="text-end"><b>Date: <?php echo date('d-M-Y'); ?> </b></td>
                                </tr>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-wrap"><small style="color:#c60206"><b>NAME OF CUSTOMER</b></small><br/>
                                    <?php echo strtoupper($customerName);?>
                                </td>
                                <td colspan="3" class="text-wrap"><small style="color:#c60206"><b>ADDRESS</b></small><br/>
                                    <?php echo strtoupper($customerAddress);?>
                                </td>
                                <td class="text-wrap"><small style="color:#c60206"><b>CONTACT NO.</b></small><br/>
                                    <?php echo $customerMobile;?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-wrap"><small style="color:#c60206"><b>ADDITIONAL MAKER</b></small><br/><span id="summary-additional-maker-name"></span></td>
                                <td colspan="3" class="text-wrap"><small style="color:#c60206"><b>ADDRESS</b></small><br/><span id="summary-additional-maker-address"></span></td>
                                <td class="text-wrap"><small style="color:#c60206"><b>CONTACT NO.</b></small><br/><span id="summary-additional-maker-mobile"></span></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-wrap"><small style="color:#c60206"><b>CO-BORROWER/CO-MORTGAGOR/CO-MAKER</b></small><br/><span id="summary-comaker-name"></span></td>
                                <td colspan="3" class="text-wrap"><small style="color:#c60206"><b>ADDRESS</b></small><br/><span id="summary-comaker-address"></span></td>
                                <td class="text-wrap"><small style="color:#c60206"><b>CONTACT NO.</b></small><br/><span id="summary-comaker-mobile"></span></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-wrap"><small style="color:#c60206"><b>ADDITIONAL CO-BORROWER/CO-MORTGAGOR/CO-MAKER</b></small><br/><span id="summary-additional-comaker-name"></span></td>
                                <td colspan="3" class="text-wrap"><small style="color:#c60206"><b>ADDRESS</b></small><br/><span id="summary-additional-comaker-address"></span></td>
                                <td class="text-wrap"><small style="color:#c60206"><b>CONTACT NO.</b></small><br/><span id="summary-additional-comaker-mobile"></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206"><b>REFERRED BY</b></small><br/><span id="summary-referred-by"></span> - <span id="summary-commission"></span></td>
                                <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206"><b>ESTIMATED DATE OF RELEASE</b></small><br/><span id="summary-release-date"></span></td>
                                <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>PRODUCT TYPE</b></small><br/><span id="summary-product-type"></span></td>
                                <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>TRANSACTION TYPE</b></small><br/><span id="summary-transaction-type"></span></td>
                                <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>TERM</b></small><br/><span id="summary-term"></span></td>
                                <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>NO. OF PAYMENTS</b></small><br/><span id="summary-no-payments"></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-wrap"><small style="color:#c60206"><b>STOCK NO.</b></small><br/><span id="summary-stock-no"></span></td>
                                <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>ENGINE NO.</b></small><br/><span id="summary-engine-no"></span></td>
                                <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>CHASSIS NO.</b></small><br/><span id="summary-chassis-no"></span></td>
                                <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>PLATE NO.</b></small><br/><span id="summary-plate-no"></span></td>
                                <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>FOR REGISTRATION?</b></small><br/><span id="summary-for-registration"></span></td>
                                <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>WITH CR?</b></small><br/><span id="summary-with-cr"></span></td>
                                <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>FOR TRANSFER?</b></small><br/><span id="summary-for-transfer"></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>FOR CHANGE COLOR?</b></small><br/><span id="summary-for-change-color"></span></td>
                                <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>NEW COLOR</b></small><br/><span id="summary-new-color"></span></td>
                                <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>FOR CHANGE BODY?</b></small><br/><span id="summary-for-change-body"></span></td>
                                <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>NEW BODY</b></small><br/><span id="summary-new-body"></span></td>
                                <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>FOR CHANGE ENGINE?</b></small><br/><span id="summary-for-change-engine"></span></td>
                                <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>NEW ENGINE</b></small><br/><span id="summary-new-engine"></span></td>
                            </tr>
                            <tr>
                                <td colspan="8" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>FINAL NAME ON OR/CR</b></small><br/><span id="summary-final-name-on-orcr"></span></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>DIESEL FUEL QUANTITY</b></small><br/><span id="summary-diesel-fuel-quantity"></span></td>

                                <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>REGULAR FUEL QUANTITY</b></small><br/><span id="summary-regular-fuel-quantity"></span></td>

                                <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>PREMIUM FUEL QUANTITY</b></small><br/><span id="summary-premium-fuel-quantity"></span></td>

                                <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>TOTAL JOB ORDER PROGRESS</b></small><br/><span id="summary-total-job-order-progress"></span></td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding-bottom:0 !important;" class="text-wrap"><small><b><span style="color:#c60206; margin-right: 20px;">JOB ORDER</span> TOTAL COST  <span id="summary-job-order-total"></span></b></small><br/><br/>
                                    <div class="row pb-0 mb-0">
                                        <div class="col-lg-12">
                                            <div class="table-responsive" id="summary-job-order-table"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206"><b>PRICING COMPUTATION:</b></small><br/>
                                    <div class="row pb-0 mb-0">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-borderless text-sm ">
                                                    <tbody>
                                                        <tr>
                                                            <td>DELIVERY PRICE</td>
                                                            <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-deliver-price"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>ADD: RECONDITIONING COST</td>
                                                            <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-reconditioning-cost"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>SUB-TOTAL</td>
                                                            <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-sub-total"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>LESS: DOWNPAYMENT (<span id="downpayment-percent">0</span>%)</td>
                                                            <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-downpayment"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>OUTSTANDING BALANCE</b></td>
                                                            <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-outstanding-balance"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row pb-0 mt-5">
                                        <div class="col-lg-12" id="summary-total-additional-job-order">
                                            
                                        </div>
                                    </div>
                                </td>
                                <td colspan="4" style="padding-bottom:0 !important;" class="text-wrap"><small style="color:#c60206"><b>OTHER CHARGES:</b></small><br/>
                                    <div class="row pb-0 mb-0">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-borderless text-sm ">
                                                    <tbody>
                                                        <tr>
                                                            <td>INSURANCE COVERAGE</td>
                                                            <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-insurance-coverage"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>INSURANCE PREMIUM</td>
                                                            <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-insurance-premium"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>HANDLING FEE</td>
                                                            <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-handing-fee"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>TRANSFER FEE</td>
                                                            <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-transfer-fee"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>REGISTRATION FEE</td>
                                                            <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-registration-fee"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>DOC. STAMP TAX</td>
                                                            <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-doc-stamp-tax"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>TRANSACTION FEE</td>
                                                            <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-transaction-fee"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>TOTAL</b></td>
                                                            <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-other-charges-total"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style="vertical-align: top !important;" class="text-wrap">
                                    <small><b>FOR REFERRAL TO FINANCING, PLEASE COMPUTE MO AMORTIZATION:</b></small><br/><br/>
                                    <small style="color:#c60206"><b>AMORTIZATION NET</b></small><br/><span class="text-sm" id="summary-repayment-amount"></span><br/><br/>
                                    <small style="color:#c60206"><b>INTEREST RATE</b></small><br/><span class="text-sm" id="summary-interest-rate"></span>
                                </td>
                                <td colspan="4" style="padding-bottom:0 !important; vertical-align: top !important;" class="text-wrap">
                                    <small style="color:#c60206"><b>AMOUNT OF DEPOSIT:</b></small><br/><br/>
                                    <div class="row pb-0 mb-0">
                                        <div class="col-lg-12">
                                            <div class="table-responsive" id="summary-amount-of-deposit-table"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style="padding-bottom:0 !important;" class="text-wrap">
                                    <div class="row pb-0 mb-0">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-borderless text-sm ">
                                                    <tbody>
                                                        <tr>
                                                            <td></td>
                                                            <td><u>2ND YEAR</u></td>
                                                            <td><u>3RD YEAR</u></td>
                                                            <td><u>4TH YEAR</u></td>
                                                        </tr>
                                                        <tr>
                                                            <td>REGISTRATION</td>
                                                            <td id="summary-registration-second-year"></td>
                                                            <td id="summary-registration-third-year"></td>
                                                            <td id="summary-registration-fourth-year"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>INS. COVERAGE</td>
                                                            <td id="summary-insurance-coverage-second-year"></td>
                                                            <td id="summary-insurance-coverage-third-year"></td>
                                                            <td id="summary-insurance-coverage-fourth-year"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>INS. PREMIUM</td>
                                                            <td id="summary-insurance-premium-second-year"></td>
                                                            <td id="summary-insurance-premium-third-year"></td>
                                                            <td id="summary-insurance-premium-fourth-year"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td colspan="4" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206"><b>REMARKS</b></small><br/><br/><span id="summary-remarks" class="text-sm"></span></td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding-bottom:0 !important;" class="text-wrap">
                                    <small style="color:#c60206"><b>REQUIREMENTS:</b></small><br/><br/>
                                    <div class="row">
                                        <div class="col-6">
                                            <ul>
                                                <li><small>PICTURE WITH SIGNATURE AT THE BACK</small></li>
                                                <li><small>POST-DATED CHECKS</small></li>
                                                <li><small>VALID ID (PHOTOCOPY)</small></li>
                                            </ul>
                                        </div>
                                        <div class="col-6">
                                            <ul>
                                                <li><small>BARANGAY CERTIFICATE</small></li>
                                                <li><small>BANK STATEMENT FOR THREE (3) MONTHS</small></li>
                                                <li><small>BUSINESS LICENSE/CERTIFICATE OF EMPLOYMENT</small></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <small style="color:#c60206"><b>ADDITIONAL REQUIREMENTS (IN CASE OF NON-INDIVIDUAL ACCOUNT):</b></small>
                                            <ul>
                                                <li><small>DTI REGISTRATION (FOR SINGLE PROPRIETORSHIP)</small></li>
                                                <li><small>SEC REGISTRATION (FOR CORPORATION) INCLUDING SECRETARY'S CERTIFICATE FOR AUTHORIZED SIGNATORY/IES</small></li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding-bottom:0 !important;" class="text-wrap">
                                    <small style="color:#c60206"><b>REMINDERS:</b></small><br/>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <ol class="text-sm">
                                                <li><small>PRICES ARE SUBJECT TO CHANGE WITHOUT PRIOR NOTICE. FINANCING IS STILL SUBJECT FOR APPROVAL BY OUR ACCREDITED FINANCING COMPANY</small></li>
                                                <li><small>DOWNPAYMENT IS STRICTLY PAYABLE ON OR BEFORE RELEASE OF UNIT IN CASH OR MANAGER'S CHECK</small></li>
                                                <li><small>POST-DATED CHECKS SHOULD BE GIVEN ON OR BEFORE RELEASE OF UNIT OTHERWISE UNIT WILL NOT BE RELEASED</small></li>
                                                <li><small>ADDITIONAL POST-DATED CHECKS SHALL BE ISSUED FOR INSURANCE AND REGISTRATION RENEWAL</small></li>
                                                <li><small>THAT THE BUYER SHALL TAKE AND ASSUME ALL CIVIL AND/OR CRIMINAL LIABILITIES IN THE USE OF SAID VEHICLE EITHER FOR PRIVATE OR BUSINESS USE</small></li>
                                                <li><small>THAT THE BUYER HAS FULL KNOWLEDGE OF THE CONDITION OF THE VEHICLE UPON PURCHASE THEREOF</small></li>
                                                <li><small>THAT THE UNIT SUBJECT OF SALE IS NOT COVERED WITH WARRANTY AND CONVEYED ON AS-IS BASIS ONLY</small></li>
                                                <li><small>THAT THE SELLER IS NOT LIABLE FOR WHATEVER UNSEEN DEFECTS THAT MAY BE DISCOVERED AFTER RELEASE OF UNIT</small></li>
                                            </ol>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small><b>PREPARED BY:</b></small><br/><br/><br/><small><?php
                                                            if(!empty($createdDate)){
                                                                echo 'CREATED THRU SYSTEM<br/>' . $createdDate;
                                                            }
                                                            ?></small><br/><span id="summary-created-by" class="text-sm"></span>
                                </td>
                                <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small><b>INITIAL APPROVAL BY:</b></small><br/><br/><br/><small><?php
                                                            if(!empty($initialApprovalDate)){
                                                                echo 'APPROVED THRU SYSTEM<br/>' . $initialApprovalDate;
                                                            }
                                                            ?></small><br/><span id="summary-initial-approval-by" class="text-sm"></span>
                                </td>
                                <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small><b>FINAL APPROVAL BY:</b></small><br/><br/><br/><small><?php
                                                            if(!empty($approvalDate)){
                                                                 echo 'APPROVED THRU SYSTEM<br/>' . $approvalDate;
                                                            }
                                                            ?></small><br/><span id="summary-final-approval-by" class="text-sm"></span>
                                </td>
                                <td colspan="2" style="vertical-align: top !important;"><small><b>WITH MY CONFORMITY:</b><br/><br/><br/><br/><br/>CUSTOMER'S PRINTED NAME OVER SIGNATURE</small></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row pb-3">
        <div class="col-lg-12">
            <small style="color:#c60206;"><b>ADDITIONAL JOB ORDER:</b></small>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="table-border-style">
                <div class="table-responsive" id="summary-additional-job-order-table"></div>
            </div>
        </div>
    </div>
</div>