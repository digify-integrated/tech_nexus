(function ($) {
  'use strict';

  $(function () {
    // -----------------------------
    // Cache frequently-used elements
    // -----------------------------
    const $doc = $(document);
    const $salesProposalStatus = $('#sales_proposal_status');
    const salesProposalStatus = $salesProposalStatus.val();

    // If this is always required, do it once
   

    // ---------------------------------------
    // Helpers (keep small + reusable)
    // ---------------------------------------
    const exists = (selector) => $(selector).length > 0;

    const initIfExists = (selector, initFn) => {
      if (exists(selector)) initFn(selector);
    };

    const initIfExistsNoArg = (selector, initFn) => {
      if (exists(selector)) initFn();
    };

    const setReadonlyIfExists = (selector, readonly) => {
      const $el = $(selector);
      if ($el.length) $el.prop('readonly', !!readonly);
    };

    const withTempDisable = (selector, ms = 500) => {
      const $btn = $(selector);
      if (!$btn.length) return;
      $btn.prop('disabled', true);
      setTimeout(() => $btn.prop('disabled', false), ms);
    };

    // ---------------------------------------
    // Guard displayDetails to reduce races
    // ---------------------------------------
    const displayDetailsGuard = (() => {
      const inFlight = new Map(); // key -> timeout id
      return (key, delay = 0) => {
        // de-dupe bursts (especially from change events)
        if (inFlight.has(key)) clearTimeout(inFlight.get(key));
        const t = setTimeout(() => {
          inFlight.delete(key);
          displayDetails(key);
        }, delay);
        inFlight.set(key, t);
      };
    })();

    // ---------------------------------------
    // 1) Table initializers (map-driven)
    // ---------------------------------------
    const tableInitializers = [
      ['#sales-proposal-table', (sel) => salesProposalTable(sel)],
      ['#all-sales-proposal-table', (sel) => allSalesProposalTable(sel)],
      ['#sales-proposal-all-approval-condition-table', (sel) => salesProposalAllApprovalConditionTable(sel)],
      ['#sales-proposal-job-order-table', (sel) => salesProposalJobOrderTable(sel)],
      ['#sales-proposal-ci-report-table', (sel) => salesProposalCIReportTable(sel)],
      ['#sales-proposal-deposit-amount-table', (sel) => salesProposalDepositAmountTable(sel)],
      ['#sales-proposal-additional-job-order-table', (sel) => salesProposalAdditionalJobOrderTable(sel)],
      ['#sales-proposal-for-ci-table', (sel) => salesProposalForCITable(sel)],
      ['#sales-proposal-approval-condition-table', (sel) => salesProposalApprovalConditionTable(sel)],
      ['#sales-proposal-for-ci-evaluation-table', (sel) => salesProposalForCIEvaluationTable(sel)],
      ['#schedule-of-payments-table', (sel) => scheduleOfPaymentsTable(sel)],
      ['#installment-sales-approval-table', (sel) => installmentSalesApprovalTable(sel)],
      ['#sales-proposal-for-bank-financing-table', (sel) => salesProposalForBankFinancingTable(sel)],
      ['#sales-proposal-change-request-table', (sel) => salesProposalChangeRequestTable(sel)],
      ['#approved-sales-proposal-table', (sel) => approvedSalesProposalTable(sel)],
      ['#incoming-sales-proposal-table', (sel) => incomingSalesProposalTable(sel)],
      ['#sales-proposal-for-dr-table', (sel) => salesProposalForDRTable(sel)],
      ['#sales-proposal-released-table', (sel) => salesProposalReleasedTable(sel, true)],
      ['#sales-proposal-pdc-manual-input-table', (sel) => salesProposalPDCManualInputTable(sel)],
      ['#sales-proposal-parts-issued-table', (sel) => issuedPartsTable(sel)],
      ['#sales-proposal-draft-file-table', (sel) => setToDraftTable(sel)],
    ];

    tableInitializers.forEach(([selector, fn]) => initIfExists(selector, fn));

    // Summary tables (no selector arg)
    initIfExistsNoArg('#summary-job-order-table', salesProposalSummaryJobOrderTable);
    initIfExistsNoArg('#summary-amount-of-deposit-table', salesProposalSummaryDepositTable);
    initIfExistsNoArg('#summary-additional-job-order-table', salesProposalSummaryAdditionalJobOrderTable);
    initIfExistsNoArg('#disclosure-schedule', salesProposalDisclosureScheduleTable);

    // ---------------------------------------
    // 2) Form initializers by status
    // ---------------------------------------
    const draftForms = [
      ['#sales-proposal-form', salesProposalForm],
      ['#sales-proposal-unit-details-form', salesProposalUnitForm],
      ['#sales-proposal-fuel-details-form', salesProposalFuelForm],
      ['#sales-proposal-refinancing-details-form', salesProposalRefinancingForm],
      ['#sales-proposal-job-order-form', salesProposalJobOrderForm],
      ['#sales-proposal-pricing-computation-form', salesProposalPricingComputationForm],
    ];

    const otherChargesFormsAllowed = ['Draft', 'For DR'];
    const otherChargesForms = [
      ['#sales-proposal-other-charges-form', salesProposalOtherChargesForm],
      ['#sales-proposal-deposit-amount-form', salesProposalDepositAmountForm],
      ['#sales-proposal-renewal-amount-form', salesProposalRenewalAmountForm],
    ];

    const initOrDisable = (selector, initFn, enabled) => {
      if (!exists(selector)) return;
      if (enabled) initFn();
      else disableFormAndSelect2(selector.replace('#', ''));
    };

    // Draft-only forms
    const isDraft = salesProposalStatus === 'Draft';
    draftForms.forEach(([selector, fn]) => initOrDisable(selector, fn, isDraft));

    // Draft or For DR forms
    const allowOtherCharges = otherChargesFormsAllowed.includes(salesProposalStatus);
    otherChargesForms.forEach(([selector, fn]) => initOrDisable(selector, fn, allowOtherCharges));

    // Other product details form (special rule)
    if (exists('#sales-proposal-other-product-details-form')) {
      const enableOtherProductDetails = salesProposalStatus === 'For DR';
      if (enableOtherProductDetails) salesProposalOtherProductDetailsForm();
      else disableFormAndSelect2('sales-proposal-other-product-details-form');

      // Always display details, but guarded to reduce races
      displayDetailsGuard('get sales proposal other product details', 50);
    }

    // Always-run form initializers (if present)
    initIfExistsNoArg('#sales-proposal-condition-form', salesProposalConditionForm);
    initIfExistsNoArg('#sales-proposal-additional-job-order-form', salesProposalAdditionalJobOrderForm);
    initIfExistsNoArg('#sales-proposal-client-confirmation-form', salesProposalClientConfirmationForm);
    initIfExistsNoArg('#sales-proposal-no-deposit-form', salesProposalNoDepositForm);
    initIfExistsNoArg('#sales-proposal-comaker-confirmation-form', salesProposalComakerConfirmationForm);
    initIfExistsNoArg('#sales-proposal-engine-stencil-form', salesProposalEngineStencilForm);
    initIfExistsNoArg('#sales-proposal-credit-advice-form', salesProposalCreditAdviceForm);
    initIfExistsNoArg('#sales-proposal-final-approval-form', salesProposalFinalApprovalForm);
    initIfExistsNoArg('#sales-proposal-initial-approval-form', salesProposalInitalApprovalForm);
    initIfExistsNoArg('#sales-proposal-reject-form', salesProposalRejectForm);
    initIfExistsNoArg('#approve-installment-sales-form', installmentSalesApprovalForm);
    initIfExistsNoArg('#sales-proposal-cancel-form', salesProposalCancelForm);
    initIfExistsNoArg('#sales-proposal-ci-recommendation-form', salesProposalCIRecommendationForm);
    initIfExistsNoArg('#sales-proposal-set-to-draft-form', salesProposalSetToDraftForm);
    initIfExistsNoArg('#sales-proposal-other-document-form', salesProposalOtherDocumentForm);
    initIfExistsNoArg('#sales-proposal-quality-control-form', salesProposalQualityControlForm);
    initIfExistsNoArg('#sales-proposal-outgoing-checklist-form', salesProposalOutgoingChecklistForm);
    initIfExistsNoArg('#sales-proposal-unit-image-form', salesProposalUnitImageForm);
    initIfExistsNoArg('#sales-proposal-job-order-confirmation-form', salesProposalAdditionalJobOrderConfirmationImageForm);
    initIfExistsNoArg('#sales-proposal-pdc-manual-input-form', salesProposalPDCManualInputForm);
    initIfExistsNoArg('#sales-proposal-tag-as-released-form', salesProposalReleaseForm);

    // Insurance request display
    if (exists('#v-insurance-request')) {
      displayDetailsGuard('get sales proposal insurance request details', 50);
    }

    // ---------------------------------------
    // 3) Consolidate change handlers (map-driven)
    // ---------------------------------------
    const recalcHandlers = {
      '#start_date': () => calculateFirstDueDate(),
      '#payment_frequency': () => { calculateFirstDueDate(); calculatePaymentNumber(); },
      '#number_of_payments': () => calculateFirstDueDate(),
      '#term_length': () => { calculatePaymentNumber(); calculatePricingComputation(); }, // merged
      '#interest_rate': () => { calculatePricingComputation(); calculateTotalOtherCharges(); },
      '#cost_of_accessories': () => { calculatePricingComputation(); calculateTotalOtherCharges(); },
      '#reconditioning_cost': () => { calculatePricingComputation(); calculateTotalOtherCharges(); },
      '#downpayment': () => { calculatePricingComputation(); calculateTotalOtherCharges(); },

      '#diesel_fuel_quantity': () => { calculateFuelTotal(); calculateTotalOtherCharges(); },
      '#diesel_price_per_liter': () => { calculateFuelTotal(); calculateTotalOtherCharges(); },
      '#regular_fuel_quantity': () => { calculateFuelTotal(); calculateTotalOtherCharges(); },
      '#regular_price_per_liter': () => { calculateFuelTotal(); calculateTotalOtherCharges(); },
      '#premium_fuel_quantity': () => { calculateFuelTotal(); calculateTotalOtherCharges(); },
      '#premium_price_per_liter': () => { calculateFuelTotal(); calculateTotalOtherCharges(); },

      '#delivery_price': () => { calculateTotalDeliveryPrice(); calculateRenewalAmount(); calculateTotalOtherCharges(); },
      '#add_on_charge': () => { calculateTotalDeliveryPrice(); calculateTotalOtherCharges(); },
      '#nominal_discount': () => { calculateTotalDeliveryPrice(); calculateTotalOtherCharges(); },

      '#insurance_coverage': () => calculateTotalOtherCharges(),
      '#insurance_premium': () => calculateTotalOtherCharges(),
      '#insurance_premium_discount': () => calculateTotalOtherCharges(),
      '#handling_fee': () => calculateTotalOtherCharges(),
      '#handling_fee_discount': () => calculateTotalOtherCharges(),
      '#transfer_fee': () => calculateTotalOtherCharges(),
      '#transfer_fee_discount': () => calculateTotalOtherCharges(),
      '#registration_fee': () => calculateTotalOtherCharges(),
      '#doc_stamp_tax': () => calculateTotalOtherCharges(),
      '#doc_stamp_tax_discount': () => calculateTotalOtherCharges(),
      '#transaction_fee': () => calculateTotalOtherCharges(),
      '#transaction_fee_discount': () => calculateTotalOtherCharges(),

      '#compute_second_year': () => calculateRenewalAmount(),
      '#compute_third_year': () => calculateRenewalAmount(),
      '#compute_fourth_year': () => calculateRenewalAmount(),
    };

    // Bind recalculation handlers once
    Object.entries(recalcHandlers).forEach(([selector, handler]) => {
      $doc.on('change', selector, handler);
    });

    // DisplayDetails handlers (guarded)
    $doc.on('change', '#comaker_id', () => displayDetailsGuard('get comaker details', 50));
    $doc.on('change', '#additional_maker_id', () => displayDetailsGuard('get additional maker details', 50));
    $doc.on('change', '#comaker_id2', () => displayDetailsGuard('get comaker2 details', 50));

    $doc.on('change', '#product_id', function () {
      if ($(this).val()) displayDetailsGuard('get product details', 50);
      else {
        $('#delivery_price, #old_color, #old_body, #old_engine').val('');
      }
    });

    // term_type -> payment_frequency options (kept as-is, just cleaned)
    $doc.on('change', '#term_type', function () {
      const termType = $(this).val();
      const $pf = $('#payment_frequency');
      $pf.empty().append(new Option('--', '', false, false));

      if (termType === 'Days') {
        $pf.append(new Option('Lumpsum', 'Lumpsum', false, false));
      } else if (termType === 'Months') {
        ['Lumpsum', 'Monthly', 'Quarterly', 'Semi-Annual'].forEach(v => {
          $pf.append(new Option(v, v, false, false));
        });
      }

      // Summary term update (merged logic)
      const termLength = $('#term_length').val();
      $('#summary-term').text(`${termLength} ${termType}`);
    });

    // for_change_engine
    $doc.on('change', '#for_change_engine', function () {
      const yes = $(this).val() === 'Yes';
      $('#new_engine').prop('readonly', !yes);
      if (!yes) $('#new_engine').val('');
    });

    // ---------------------------------------
    // 4) product_type handler (Revised)
    // ---------------------------------------
    $doc.on('change', '#product_type', function () {
      const productType = $(this).val();

      // Tab 4 label - Ensured 'Brand New' maps to the correct label
      const tab4Label = ({
        'Brand New': 'Brand New Details',
        'Refinancing': 'Refinancing Details',
        'Restructure': 'Restructure Details',
      })[productType];
      
      if (tab4Label) $('#sales-proposal-tab-4').text(tab4Label);

      const $tab2 = $('#sales-proposal-tab-2');
      const $tab3 = $('#sales-proposal-tab-3');
      const $tab4 = $('#sales-proposal-tab-4');

      if ($tab2.length && $tab3.length && $tab4.length) {
        $tab2.add($tab3).add($tab4).addClass('d-none');

        const isUnitLike = ['Unit', 'Rental', 'Consignment'].includes(productType);
        const isFuel = productType === 'Fuel';
        const isRefiLike = ['Refinancing', 'Restructure', 'Brand New'].includes(productType);

        if (isUnitLike) {
          $tab2.removeClass('d-none');
          resetModalForm('sales-proposal-unit-details-form');
          displayDetailsGuard('get sales proposal unit details', 50);
        } else if (isFuel) {
          $tab3.removeClass('d-none');
          displayDetailsGuard('get sales proposal fuel details', 50);
        } else if (isRefiLike) {
          $tab4.removeClass('d-none');
          // IMPORTANT: Ensure 'Brand New' uses the same form initialization as Refinancing
          resetModalForm('sales-proposal-refinancing-details-form');
          displayDetailsGuard('get sales proposal refinancing details', 50);
        }
      }

      const lockDelivery = ['Unit', 'Rental', 'Consignment', 'Fuel'].includes(productType);
      setReadonlyIfExists('#delivery_price', lockDelivery);

      // 'Brand New' should likely follow Refinancing rules for insurance premium
      const unlockInsurance = ['Refinancing', 'Restructure', 'Brand New'].includes(productType);
      setReadonlyIfExists('#insurance_premium', !unlockInsurance);

      $('#summary-product-type').text(productType);
    });

    // ---------------------------------------
    // 5) Summary field bindings (map-driven)
    // ---------------------------------------
    const summaryBindings = [
      ['#commission_amount', '#summary-commission', (v) => encryptCommission(v)],
      ['#referred_by', '#summary-referred-by', (v) => v],
      ['#transaction_type', '#summary-transaction-type', (v) => v],
      ['#term_length', '#summary-term', (v) => `${v} ${$('#term_type').val()}`],
      ['#number_of_payments', '#summary-no-payments', (v) => v],
      ['#remarks', '#summary-remarks', (v) => v],
      ['#new_color', '#summary-new-color', (v) => v],
      ['#new_body', '#summary-new-body', (v) => v],
      ['#new_engine', '#summary-new-engine', (v) => v],
      ['#diesel_fuel_quantity', '#summary-diesel-fuel-quantity', (v) => `${v} lt`],
      ['#regular_fuel_quantity', '#summary-regular-fuel-quantity', (v) => `${v} lt`],
      ['#premium_fuel_quantity', '#summary-premium-fuel-quantity', (v) => `${v} lt`],
    ];

    summaryBindings.forEach(([sourceSel, targetSel, formatter]) => {
      $doc.on('change', sourceSel, function () {
        $(targetSel).text(formatter($(this).val()));
      });
    });

    // transaction_type special rule (kept)
    $doc.on('change', '#transaction_type', function () {
      if ($(this).val() === 'Bank Financing') {
        $('#term_length').val('45').trigger('change');
        checkOptionExist('#payment_frequency', 'Lumpsum', '');
        checkOptionExist('#term_type', 'Days', '');
      }
    });

    // ---------------------------------------
    // 6) Step traversal buttons (DRY)
    // ---------------------------------------
    $doc.on('click', '#next-step', async function (e) {
      e.preventDefault();
      e.stopPropagation();
      withTempDisable(this);
      await traverseTabs('next');
      return false;
    });

    $doc.on('click', '#previous-step', async function (e) {
      e.preventDefault();
      e.stopPropagation();
      withTempDisable(this);
      await traverseTabs('previous');
      return false;
    });

    $doc.on('click', '#first-step', async function (e) {
      e.preventDefault();
      e.stopPropagation();
      withTempDisable(this);
      await traverseTabs('first');
      return false;
    });

    $doc.on('click', '#last-step, #last-step2', async function (e) {
      e.preventDefault();
      e.stopPropagation();
      withTempDisable(this);
      await traverseTabs('last');
      return false;
    });

    if (salesProposalStatus !== 'Draft' && exists('#last-step')) {
      $('#last-step')[0].click();
    }

    // ---------------------------------------
    // 7) Startup detail loads (guarded)
    // ---------------------------------------
    if (exists('#other-charges-rows')) {
      salesProposalSummaryPDCManualInputTable();
    }

    if (exists('#sales-proposal-id')) {
      [
        'get sales proposal basic details',
        'get sales proposal pricing computation details',
        'get sales proposal confirmation details',
        'get sales proposal renewal amount details',
        'get sales proposal other charges details',
      ].forEach((k) => displayDetailsGuard(k, 10));
    }

    // ---------------------------------------
    // 8) Filter + Print (kept)
    // ---------------------------------------
    $doc.on('click', '#apply-filter', function () {
      initIfExists('#sales-proposal-table', (sel) => salesProposalTable(sel));
      initIfExists('#all-sales-proposal-table', (sel) => allSalesProposalTable(sel));
      initIfExists('#sales-proposal-released-table', (sel) => salesProposalReleasedTable(sel, true));
      initIfExists('#schedule-of-payments-table', (sel) => scheduleOfPaymentsTable(sel));
    });
    
    $doc.on('click', '#reset-filter', function () {
      document
        .querySelectorAll('#filter-canvas input[type="checkbox"]')
        .forEach(cb => cb.checked = false);

      // 2. Reset all text inputs (including datepickers)
      document
        .querySelectorAll('#filter-canvas input[type="text"]')
        .forEach(input => input.value = '');

      // 3. Optional: reset select dropdowns (if any exist later)
      document
        .querySelectorAll('#filter-canvas select')
        .forEach(select => select.selectedIndex = 0);
        
      initIfExists('#all-sales-proposal-table', (sel) => allSalesProposalTable(sel)); 
    });

    $('#print').on('click', function () {
      const outstandingBalance = Number($('#outstanding_balance').val() || 0);
      if (outstandingBalance > 0) {
        $('#pricing-computation-block, #amortization-block, #remarks-block').removeClass('dontprint');
        window.print();
      }
    });

    $('#print2').on('click', function () {
      const outstandingBalance = Number($('#outstanding_balance').val() || 0);
      if (outstandingBalance > 0) {
        $('#pricing-computation-block, #amortization-block, #remarks-block').addClass('dontprint');
        window.print();
      }
    });

    $(document).on('click','#add-sales-proposal-condition',function() {
            resetModalForm("sales-proposal-condition-form");
        });

        $(document).on('click','.update-sales-proposal-condition',function() {
            const sales_proposal_condition_id = $(this).data('sales-proposal-condition-id');

            sessionStorage.setItem('sales_proposal_condition_id', sales_proposal_condition_id);

            displayDetails('get sales proposal condition details');
        });

         $(document).on('click','.delete-sales-proposal-condition',function() {
            const sales_proposal_condition_id = $(this).data('sales-proposal-condition-id');
            const transaction = 'delete sales proposal condition';

            Swal.fire({
                title: 'Confirm Condition Deletion',
                text: 'Are you sure you want to delete this condition?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_condition_id : sales_proposal_condition_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Condition Success', 'The condition has been deleted successfully.', 'success');
                                reloadDatatable('#sales-proposal-approval-condition-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Delete Job Order Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','.complete-sales-proposal-condition',function() {
            const sales_proposal_condition_id = $(this).data('sales-proposal-condition-id');
            const transaction = 'complete sales proposal condition';

            Swal.fire({
                title: 'Confirm Condition Completion',
                text: 'Are you sure you want to complete this condition?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Complete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_condition_id : sales_proposal_condition_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Complete Condition Success', 'The condition has been completed successfully.', 'success');
                                reloadDatatable('#sales-proposal-approval-condition-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Complete Condition Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','.waive-sales-proposal-condition',function() {
            const sales_proposal_condition_id = $(this).data('sales-proposal-condition-id');
            const transaction = 'waive sales proposal condition';

            Swal.fire({
                title: 'Confirm Condition Waiver',
                text: 'Are you sure you want to waive this condition?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Waive',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-warning mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_condition_id : sales_proposal_condition_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Waive Condition Success', 'The condition has been waived successfully.', 'success');
                                reloadDatatable('#sales-proposal-approval-condition-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Waive Condition Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#add-sales-proposal-job-order',function() {
            resetModalForm("sales-proposal-job-order-form");
            $('#job_order_type').val('add');

        });

        $(document).on('click','.update-sales-proposal-job-order',function() {
            const sales_proposal_job_order_id = $(this).data('sales-proposal-job-order-id');
    
            sessionStorage.setItem('sales_proposal_job_order_id', sales_proposal_job_order_id);
            
            displayDetails('get sales proposal job order details');
            
            $('#job_order_type').val('update');
        });

        $(document).on('click','.delete-sales-proposal-job-order',function() {
            const sales_proposal_job_order_id = $(this).data('sales-proposal-job-order-id');
            const transaction = 'delete sales proposal job order';
    
            Swal.fire({
                title: 'Confirm Job Order Deletion',
                text: 'Are you sure you want to delete this job order?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_job_order_id : sales_proposal_job_order_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Job Order Success', 'The job order has been deleted successfully.', 'success');
                                reloadDatatable('#sales-proposal-job-order-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Delete Job Order Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#add-sales-proposal-deposit-amount',function() {
            resetModalForm("sales-proposal-deposit-amount-form");
        });

        $(document).on('click','.update-sales-proposal-deposit-amount',function() {
            const sales_proposal_deposit_amount_id = $(this).data('sales-proposal-deposit-amount-id');
    
            sessionStorage.setItem('sales_proposal_deposit_amount_id', sales_proposal_deposit_amount_id);
            
            displayDetails('get sales proposal deposit amount details');
        });

        $(document).on('click','.delete-sales-proposal-deposit-amount',function() {
            const sales_proposal_deposit_amount_id = $(this).data('sales-proposal-deposit-amount-id');
            const transaction = 'delete sales proposal deposit amount';
    
            Swal.fire({
                title: 'Confirm Deposit Amount Deletion',
                text: 'Are you sure you want to delete this deposit amount?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_deposit_amount_id : sales_proposal_deposit_amount_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Amount of Deposit Success', 'The amount of deposit has been deleted successfully.', 'success');
                                reloadDatatable('#sales-proposal-deposit-amount-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Delete Amount of Deposit Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#add-sales-proposal-additional-job-order',function() {
            resetModalForm("sales-proposal-additional-job-order-form");
        });

        $(document).on('click','.update-sales-proposal-additional-job-order',function() {
            const sales_proposal_additional_job_order_id = $(this).data('sales-proposal-additional-job-order-id');
    
            sessionStorage.setItem('sales_proposal_additional_job_order_id', sales_proposal_additional_job_order_id);
            
            displayDetails('get sales proposal additional job order details');
        });

        $(document).on('click','.delete-sales-proposal-additional-job-order',function() {
            const sales_proposal_additional_job_order_id = $(this).data('sales-proposal-additional-job-order-id');
            const transaction = 'delete sales proposal additional job order';
    
            Swal.fire({
                title: 'Confirm Additional Job Order Deletion',
                text: 'Are you sure you want to delete this additional job order?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_additional_job_order_id : sales_proposal_additional_job_order_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Additional Job Order Success', 'The additional job order has been deleted successfully.', 'success');
                                reloadDatatable('#sales-proposal-additional-job-order-table');
                                displayDetails('get sales proposal additional job order total details');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Delete Additional Job Order Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#tag-for-initial-approval',function() {
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'tag for initial approval';
    
            Swal.fire({
                title: 'Confirm Tagging of Sales Proposal For Initial Approval',
                text: 'Are you sure you want to tag this sales proposal for initial approval?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'For Initial Approval',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-warning mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_id : sales_proposal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Sales Proposal For Initial Approval Success', 'The sales proposal has been tagged for initial approval successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.emptyStencil) {
                                    showNotification('For Initial Approval Error', 'Please upload the new engine stencil first.', 'danger');
                                } 
                                else if (response.withApplication) {
                                    showNotification('For Initial Approval Error', 'The product selected is attached to another sales proposal.', 'danger');
                                } 
                                else if (response.comakerRelation) {
                                    showNotification('For Initial Approval Error', 'Please update the comaker relation first.', 'danger');
                                } 
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag Sales Proposal For Initial Approval Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#tag-for-review',function() {
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'tag for review';
    
            Swal.fire({
                title: 'Confirm Tagging of Sales Proposal For Review',
                text: 'Are you sure you want to tag this sales proposal for review?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'For Review',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-warning mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_id : sales_proposal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Sales Proposal For Review Success', 'The sales proposal has been tagged for review successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.emptyStencil) {
                                    showNotification('For Review Error', 'Please upload the new engine stencil first.', 'danger');
                                } 
                                else if (response.zeroBalance) {
                                    showNotification('For Review Error', 'The outstanding balance cannot be zero.', 'danger');
                                } 
                                else if (response.condition) {
                                    showNotification('For Review Error', 'Please complete the required conditions.', 'danger');
                                } 
                                else if (response.clientConfirmation) {
                                    showNotification('For Review Error', 'Please upload the client confirmation first.', 'danger');
                                } 
                                else if (response.incorrectCompany) {
                                    showNotification('For Review Error', 'For bellow 1,000 liters the company should be CGMI and above 1,000 the company should be Ne Fuel.', 'danger');
                                } 
                                else if (response.comakerConfirmation) {
                                    showNotification('For Review Error', 'Please upload the comaker confirmation first.', 'danger');
                                } 
                                else if (response.comakerRelation) {
                                    showNotification('For Review Error', 'Please update the comaker relation first.', 'danger');
                                } 
                                else if (response.depositZero) {
                                    showNotification('For Review Error', 'Please add a deposit amount or if there is no deposit amount upload the no deposit approval image.', 'danger');
                                } 
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag Sales Proposal For Initial Approval Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#for-ci-sales-proposal',function() {
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'tag for ci';
    
            Swal.fire({
                title: 'Confirm Tagging of Sales Proposal For Credit Investigation',
                text: 'Are you sure you want to tag this sales proposal for credit investigation?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'For CI',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_id : sales_proposal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Sales Proposal For Credit Investigation Success', 'The sales proposal has been tagged for credit investigation successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag Sales Proposal For Credit Investigation Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#complete-ci',function() {
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'complete ci';
    
            Swal.fire({
                title: 'Confirm Tagging of Credit Investigation As Complete',
                text: 'Are you sure you want to tag the credit investigation as complete?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'Complete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_id : sales_proposal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Credit Investigation As Complete Success', 'The credit investigation has been tagged as complete successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag Credit Investigation As Complete Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#sales-proposal-change-request',function() {
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'tag change request as complete';
    
            Swal.fire({
                title: 'Confirm Tagging of Change Request As Complete',
                text: 'Are you sure you want to tag this change request as complete?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Complete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-warning mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_id : sales_proposal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Change Request As Complete Success', 'The change request has been tagged as complete successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag Change Request As Complete Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#on-process-sales-proposal',function() {
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'tag on process';
    
            Swal.fire({
                title: 'Confirm Tagging of Sales Proposal On-Process',
                text: 'Are you sure you want to tag this sales proposal on-process?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'On Process',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_id : sales_proposal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Sales Proposal On-Process Success', 'The sales proposal has been tagged on-process successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag Sales Proposal On-Process Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#ready-for-release-sales-proposal',function() {
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'tag ready for release';
    
            Swal.fire({
                title: 'Confirm Tagging of Sales Proposal Ready For Release',
                text: 'Are you sure you want to tag this sales proposal ready for release?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'Ready For Release',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_id : sales_proposal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Sales Proposal Ready For Release Success', 'The sales proposal has been tagged ready for release successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag Sales Proposal Ready For Release Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#for-dr-sales-proposal',function() {
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'tag for DR';
    
            Swal.fire({
                title: 'Confirm Tagging of Sales Proposal For DR',
                text: 'Are you sure you want to tag this sales proposal for DR?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'For DR',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_id : sales_proposal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Sales Proposal For DR Success', 'The sales proposal has been tagged for DR successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }                            
                                else if (response.fuelQuantity) {
                                    showNotification('Transaction Error', 'Insufficient fuel quantity.', 'danger');
                                } 
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag Sales Proposal For DR Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','.delete-sales-proposal-manual-pdc-input',function() {
            const sales_proposal_manual_pdc_input_id = $(this).data('sales-proposal-manual-pdc-input-id');
            const transaction = 'delete sales proposal pdc manual input';
    
            Swal.fire({
                title: 'Confirm PDC Manual Input Deletion',
                text: 'Are you sure you want to delete this pdc manual input?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_manual_pdc_input_id : sales_proposal_manual_pdc_input_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete PDC Manual Input Success', 'The PDC manula input has been deleted successfully.', 'success');
                                reloadDatatable('#sales-proposal-pdc-manual-input-table');
                                salesProposalSummaryPDCManualInputTable();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Delete PDC Manual Input Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

  });
})(jQuery);

// -----------------------------
// Shared Ajax error builder
// -----------------------------
function buildXhrErrorMessage(xhr, status, error) {
  let msg = `XHR status: ${status}, Error: ${error}`;
  if (xhr && xhr.responseText) msg += `, Response: ${xhr.responseText}`;
  return msg;
}

function dtAjaxError(xhr, status, error) {
  showErrorDialog(buildXhrErrorMessage(xhr, status, error));
}

// -----------------------------
// Filter helpers
// -----------------------------
function getText(selector) {
  const el = document.querySelector(selector);
  return el ? (el.textContent || '').trim() : '';
}

function getSalesProposalId() {
  return getText('#sales-proposal-id');
}

function getVal(selector) {
  const el = document.querySelector(selector);
  return el ? el.value : '';
}

function getCheckedValues(selector) {
  return Array.from(document.querySelectorAll(selector))
    .filter((el) => el.checked)
    .map((el) => el.value);
}

// -----------------------------
// DataTable factory
// -----------------------------

const getReleaseDateFilters = () => ({
  filter_release_date_start_date: $('#filter_release_date_start_date').val(),
  filter_release_date_end_date: $('#filter_release_date_end_date').val(),
});


function createServerDataTable(datatableName, options) {
  const {
    url,
    type,
    data = {},
    columns,
    columnDefs,
    order = [],
    showAll = false,
    buttons = false,
    lengthMenu = null,
    dom = null,
    buttonsConfig = null,
    extra = {},
  } = options;

  const computedLengthMenu =
    lengthMenu ||
    (showAll
      ? [[-1], ['All']]
      : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']]);

  const settings = {
    ajax: {
      url,
      method: 'POST',
      dataType: 'json',
      data: { type, ...data },
      dataSrc: '',
      error: dtAjaxError,
    },
    columns,
    columnDefs,
    order,
    lengthMenu: computedLengthMenu,
    language: {
      emptyTable: 'No data found',
      searchPlaceholder: 'Search...',
      search: '',
      loadingRecords: 'Just a moment while we fetch your data...',
    },
    ...extra,
  };

  if (buttons) {
    settings.dom =
      dom ||
      "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-5'i><'col-sm-7'p>>";

    settings.buttons = buttonsConfig || ['csv', 'excel', 'pdf'];
  }

  destroyDatatable(datatableName);
  return $(datatableName).DataTable(settings); // use DataTable() not dataTable()
}

function salesProposalTable(datatable_name, buttons = false, show_all = false) {
  return createServerDataTable(datatable_name, {
    url: 'view/_sales_proposal_generation.php',
    type: 'sales proposal table',
    data: {
      customer_id: getVal('#customer_id'),
      sales_proposal_status_filter: getVal('.sales-proposal-status-filter:checked'),
    },
    order: [[2, 'desc']],
    columns: [
      { data: 'CHECK_BOX' },
      { data: 'SALES_PROPOSAL_NUMBER' },
      { data: 'CREATED_DATE' },
      { data: 'CUSTOMER' },
      { data: 'PRODUCT_TYPE' },
      { data: 'PRODUCT' },
      { data: 'STATUS' },
      { data: 'ACTION' },
    ],
    columnDefs: [
      { width: '1%', orderable: false, targets: 0 },
      { width: 'auto', targets: 1 },
      { width: 'auto', type: 'date', targets: 2 },
      { width: 'auto', targets: 3 },
      { width: 'auto', targets: 4 },
      { width: 'auto', targets: 5 },
      { width: 'auto', targets: 6 },
      { width: '10%', orderable: false, targets: 7 },
    ],
    buttons,
    showAll: show_all,
  });
}

function allSalesProposalTable(datatable_name, buttons = false, show_all = false) {
  const sale_proposal_status_filter = getCheckedValues('.sales-proposal-status-filter');
  const product_type_filter = getCheckedValues('.product-type-filter');
  const company_filter = getCheckedValues('.company-filter');
  const user_filter = getCheckedValues('.user-filter');

  return createServerDataTable(datatable_name, {
    url: 'view/_sales_proposal_generation.php',
    type: 'all sales proposal table',
    data: {
      filter_sale_proposal_status: sale_proposal_status_filter.join(','),
      filter_product_type: product_type_filter.join(','),
      filter_company: company_filter.join(','),
      filter_user: user_filter.join(','),
      filter_created_date_start_date: getVal('#filter_created_date_start_date'),
      filter_created_date_end_date: getVal('#filter_created_date_end_date'),
      filter_released_date_start_date: getVal('#filter_released_date_start_date'),
      filter_released_date_end_date: getVal('#filter_released_date_end_date'),
    },
    order: [[6, 'desc']],
    columns: [
      { data: 'CHECK_BOX' },
      { data: 'CUSTOMER' },
      { data: 'PRODUCT' },
      { data: 'STATUS' },
      { data: 'SALES_PROPOSAL_NUMBER' },
      { data: 'PRODUCT_TYPE' },
      { data: 'CREATED_DATE' },
      { data: 'ACTION' },
    ],
    columnDefs: [
      { width: '1%', orderable: false, targets: 0 },
      { width: '14%', targets: 1 },
      { width: '15%', targets: 2 },
      { width: '10%', targets: 3 },
      { width: '15%', targets: 4 },
      { width: '10%', targets: 5 },
      { width: '15%', type: 'date', targets: 6 },
      { width: '10%', orderable: false, targets: 7 },
    ],
    buttons,
    showAll: show_all,
    extra: {
      searching: true,
      // DataTables does NOT support your `search: { placeholder, position }` object.
      // Placeholder should be handled via initComplete (below).
      initComplete: function () {
        const api = this.api();
        $(api.table().container())
          .find('div.dataTables_filter input')
          .attr('placeholder', 'Search...');
      },
    },
  });
}

function salesProposalApprovalConditionTable(datatable_name, buttons = false, show_all = false) {
  return createServerDataTable(datatable_name, {
    url: 'view/_sales_proposal_generation.php',
    type: 'sales proposal approval condition table',
    data: { sales_proposal_id: getSalesProposalId() },
    order: [[0, 'asc']],
    columns: [
      { data: 'CONDITION' },
      { data: 'CONDITION_TYPE' },
      { data: 'STATUS' },
      { data: 'ACTION' },
    ],
    columnDefs: [
      { width: '50%', targets: 0 },
      { width: '20%', targets: 1 },
      { width: '15%', targets: 2 },
      { width: '15%', orderable: false, targets: 3 },
    ],
    buttons,
    showAll: show_all,
  });
}

function salesProposalJobOrderTable(datatable_name, buttons = false, show_all = false) {
  return createServerDataTable(datatable_name, {
    url: 'view/_sales_proposal_generation.php',
    type: 'sales proposal job order table',
    data: { sales_proposal_id: getSalesProposalId() },
    order: [[0, 'asc']],
    columns: [
      { data: 'JOB_ORDER' },
      { data: 'COST' },
      { data: 'PROGRESS' },
      { data: 'APPROVAL_DOCUMENT' },
      { data: 'ACTION' },
    ],
    columnDefs: [
      { width: '42%', targets: 0 },
      { width: '14%', targets: 1 },
      { width: '14%', targets: 2 },
      { width: '14%', targets: 3 },
      { width: '16%', orderable: false, targets: 4 },
    ],
    buttons,
    showAll: show_all,
  });
}

function salesProposalCIReportTable(datatable_name, buttons = false, show_all = false) {
  return createServerDataTable(datatable_name, {
    url: 'view/_ci_report_generation.php',
    type: 'sales proposal ci report table',
    data: { sales_proposal_id: getSalesProposalId() },
    order: [[0, 'asc']],
    columns: [
      { data: 'CUSTOMER' },
      { data: 'APPRAISER' },
      { data: 'INVESTIGATOR' },
      { data: 'STATUS' },
      { data: 'DATE_STARTED' },
      { data: 'RELEASED_DATE' },
      { data: 'ACTION' },
    ],
    columnDefs: [
      { width: 'auto', targets: 0 },
      { width: 'auto', targets: 1 },
      { width: 'auto', targets: 2 },
      { width: 'auto', targets: 3 },
      { width: 'auto', type: 'date', targets: 4 },
      { width: 'auto', type: 'date', targets: 5 },
      { width: '10%', orderable: false, targets: 6 },
    ],
    buttons,
    showAll: show_all,
  });
}

function salesProposalDepositAmountTable(datatable_name, buttons = false, show_all = false){
  return createServerDataTable(datatable_name, {
    url: 'view/_sales_proposal_generation.php',
    type: 'sales proposal deposit amount table',
    data: { sales_proposal_id: getSalesProposalId() },
    order: [[0, 'asc']],
    columns: [
      { 'data' : 'DEPOSIT_DATE' },
      { 'data' : 'REFERENCE_NUMBER' },
      { 'data' : 'DEPOSIT_AMOUNT' },
      { 'data' : 'ACTION' }
    ],
    columnDefs: [
      { 'width': '25%', 'type': 'date', 'aTargets': 0 },
      { 'width': '30%', 'aTargets': 1 },
      { 'width': '30%', 'aTargets': 2 },
      { 'width': '15%','bSortable': false, 'aTargets': 3 }
    ],
    buttons,
    showAll: show_all,
  });
}

function salesProposalPDCManualInputTable(datatable_name, buttons = false, show_all = false) {
  return createServerDataTable(datatable_name, {
    url: 'view/_sales_proposal_generation.php',
    type: 'sales proposal pdc manual input table',
    data: { sales_proposal_id: getSalesProposalId() },
    order: [[0, 'asc']],
    columns: [
      { data: 'ACCOUNT_NUMBER' },
      { data: 'BANK_BRANCH' },
      { data: 'CHECK_DATE' },
      { data: 'CHECK_NUMBER' },
      { data: 'PAYMENT_FOR' },
      { data: 'GROSS_AMOUNT' },
      { data: 'ACTION' },
    ],
    columnDefs: [
      { width: '14.16%', targets: 0 },
      { width: '14.16%', targets: 1 },
      { width: '14.16%', type: 'date', targets: 2 },
      { width: '14.16%', targets: 3 },
      { width: '14.16%', targets: 4 },
      { width: '14.16%', targets: 5 },
      { width: '15%', orderable: false, targets: 6 },
    ],
    buttons,
    showAll: show_all,
  });
}

function setToDraftTable(datatable_name, buttons = false, show_all = false) {
  return createServerDataTable(datatable_name, {
    url: 'view/_sales_proposal_generation.php',
    type: 'sales proposal set to draft table',
    data: { sales_proposal_id: getSalesProposalId() },
    order: [[0, 'asc']],
    columns: [
      { data: 'REASON' },
      { data: 'FILE' },
      { data: 'UPLOAD_DATE' }
    ],
    columnDefs: [
      { width: '14.16%', targets: 0 },
      { width: '14.16%', targets: 1 },
      { width: '14.16%', type: 'date', targets: 2 },
    ],
    buttons,
    showAll: show_all,
  });
}

function issuedPartsTable(datatable_name, buttons = false, show_all = false) {
  return createServerDataTable(datatable_name, {
    url: 'view/_parts_transaction_generation.php',
    type: 'part item table 4',
    data: { sales_proposal_id: getSalesProposalId() },
    order: [[0, 'asc']],
    columns: [
      { data: 'PART_TRANSACTION_NO' },
      { data: 'PART' },
      { data: 'QUANTITY' },
      { data: 'REQUESTED_BY' },
      { data: 'RELEASED_DATE' },
      { data: 'STATUS' },
      { data: 'TOTAL_PRICE' }
    ],
    columnDefs: [
      { width: 'auto', targets: 0 },
      { width: 'auto', targets: 1 },
      { width: 'auto', targets: 2 },
      { width: 'auto', targets: 3 },
      { width: 'auto', type: 'date', targets: 4 },
      { width: 'auto', targets: 5 },
      { width: 'auto', targets: 6 },
    ],
    buttons,
    showAll: show_all,
  });
}

function fetchSummaryTableHtml({ type, targetId }) {
  const sales_proposal_id = getSalesProposalId();
  const el = document.getElementById(targetId);
  if (!el) return;

  $.ajax({
    type: 'POST',
    url: 'view/_sales_proposal_generation.php',
    dataType: 'json',
    data: { type, sales_proposal_id },
    success: function (result) {
      const tableHtml = result && result[0] && result[0].table ? result[0].table : '';
      el.innerHTML = tableHtml;
    },
    error: dtAjaxError,
  });
}

function salesProposalSummaryDepositTable() {
  fetchSummaryTableHtml({
    type: 'summary deposit amount table',
    targetId: 'summary-amount-of-deposit-table',
  });
}

function salesProposalDisclosureScheduleTable() {
  fetchSummaryTableHtml({
    type: 'summary disclosure schedule table',
    targetId: 'disclosure-schedule',
  });
}

function salesProposalSummaryAdditionalJobOrderTable() {
  fetchSummaryTableHtml({
    type: 'summary additional job order table',
    targetId: 'summary-additional-job-order-table',
  });
}

function salesProposalSummaryJobOrderTable() {
  fetchSummaryTableHtml({
    type: 'summary job order table',
    targetId: 'summary-job-order-table',
  });
}

function salesProposalSummaryPDCManualInputTable() {
  fetchSummaryTableHtml({
    type: 'summary pdc manual input table',
    targetId: 'other-charges-rows',
  });
}

function approvedSalesProposalTable(datatable_name, buttons = false, show_all = false) {
  return createServerDataTable(datatable_name, {
    url: 'view/_sales_proposal_generation.php',
    type: 'approved sales proposal table',
    order: [[4, 'desc']],
    columns: [
      { data: 'SALES_PROPOSAL_NUMBER' },
      { data: 'CUSTOMER' },
      { data: 'PRODUCT_TYPE' },
      { data: 'PRODUCT' },
      { data: 'PROCEED_DATE' },
      { data: 'PROGRESS' },
      { data: 'STATUS' },
      { data: 'ACTION' },
    ],
    columnDefs: [
      { width: '15%', targets: 0 },
      { width: '15%', targets: 1 },
      { width: '15%', targets: 2 },
      { width: '25%', targets: 3 },
      { width: '15%', type: 'date', targets: 4 },
      { width: '10%', targets: 5 },
      { width: '10%', targets: 6 },
      { width: '10%', orderable: false, targets: 7 },
    ],
    buttons,
    showAll: show_all,
  });
}

function incomingSalesProposalTable(datatable_name, buttons = false, show_all = false) {
  return createServerDataTable(datatable_name, {
    url: 'view/_sales_proposal_generation.php',
    type: 'incoming sales proposal table',
    order: [[4, 'desc']],
    columns: [
      { data: 'SALES_PROPOSAL_NUMBER' },
      { data: 'CUSTOMER' },
      { data: 'PRODUCT_TYPE' },
      { data: 'PRODUCT' },
      { data: 'PROCEED_DATE' },
      { data: 'STATUS' },
      { data: 'ACTION' },
    ],
    columnDefs: [
      { width: '15%', targets: 0 },
      { width: '15%', targets: 1 },
      { width: '15%', targets: 2 },
      { width: '25%', targets: 3 },
      { width: '25%', type: 'date', targets: 4 },
      { width: '10%', targets: 5 },
      { width: '10%', orderable: false, targets: 6 },
    ],
    buttons,
    showAll: show_all,
  });
}

function salesProposalForDRTable(datatable_name, buttons = false, show_all = false) {
  return createServerDataTable(datatable_name, {
    url: 'view/_sales_proposal_generation.php',
    type: 'sales proposal for dr table',
    order: [[4, 'desc']],
    columns: [
      { data: 'SALES_PROPOSAL_NUMBER' },
      { data: 'CUSTOMER' },
      { data: 'PRODUCT_TYPE' },
      { data: 'PRODUCT' },
      { data: 'FOR_DR_DATE' },
      { data: 'STATUS' },
      { data: 'ACTION' },
    ],
    columnDefs: [
      { width: '15%', targets: 0 },
      { width: '15%', targets: 1 },
      { width: '15%', targets: 2 },
      { width: '25%', targets: 3 },
      { width: '25%', type: 'date', targets: 4 },
      { width: '10%', targets: 5 },
      { width: '10%', orderable: false, targets: 6 },
    ],
    buttons,
    showAll: show_all,
  });
}

function salesProposalReleasedTable(datatable_name, buttons = false, show_all = false) {
  return createServerDataTable(datatable_name, {
    url: 'view/_sales_proposal_generation.php',
    type: 'sales proposal released table',
    data: getReleaseDateFilters(),
    order: [[1, 'desc']], // kept as-is from your original
    columns: [
      { data: 'SALES_PROPOSAL_NUMBER' },
      { data: 'CUSTOMER' },
      { data: 'PRODUCT_TYPE' },
      { data: 'PRODUCT' },
      { data: 'RELEASED_DATE' },
      { data: 'STATUS' },
      { data: 'ACTION' },
    ],
    columnDefs: [
      { width: '15%', targets: 0 },
      { width: '15%', targets: 1 },
      { width: '15%', targets: 2 },
      { width: '25%', targets: 3 },
      { width: '25%', type: 'date', targets: 4 },
      { width: '10%', targets: 5 },
      { width: '10%', orderable: false, targets: 6 },
    ],
    buttons,
    showAll: show_all,
    searching: true,
  });
}

function salesProposalForBankFinancingTable(datatable_name, buttons = false, show_all = false) {
  return createServerDataTable(datatable_name, {
    url: 'view/_sales_proposal_generation.php',
    type: 'sales proposal for bank financing table',
    order: [[4, 'desc']],
    columns: [
      { data: 'SALES_PROPOSAL_NUMBER' },
      { data: 'CUSTOMER' },
      { data: 'PRODUCT_TYPE' },
      { data: 'PRODUCT' },
      { data: 'FOR_CI_DATE' },
      { data: 'STATUS' },
      { data: 'ACTION' },
    ],
    columnDefs: [
      { width: '15%', targets: 0 },
      { width: '15%', targets: 1 },
      { width: '15%', targets: 2 },
      { width: '25%', targets: 3 },
      { width: '25%', type: 'date', targets: 4 },
      { width: '10%', targets: 5 },
      { width: '10%', orderable: false, targets: 6 },
    ],
    buttons,
    showAll: show_all,
  });
}

function installmentSalesApprovalTable(datatable_name, buttons = false, show_all = false) {
  return createServerDataTable(datatable_name, {
    url: 'view/_sales_proposal_generation.php',
    type: 'installment sales approval table',
    order: [[4, 'desc']],
    columns: [
      { data: 'SALES_PROPOSAL_NUMBER' },
      { data: 'CUSTOMER' },
      { data: 'PRODUCT_TYPE' },
      { data: 'PRODUCT' },
      { data: 'FOR_CI_DATE' },
      { data: 'STATUS' },
      { data: 'ACTION' },
    ],
    columnDefs: [
      { width: '15%', targets: 0 },
      { width: '15%', targets: 1 },
      { width: '15%', targets: 2 },
      { width: '25%', targets: 3 },
      { width: '25%', type: 'date', targets: 4 },
      { width: '10%', targets: 5 },
      { width: '10%', orderable: false, targets: 6 },
    ],
    buttons,
    showAll: show_all,
  });
}

function salesProposalForCIEvaluationTable(datatable_name, buttons = false, show_all = false) {
  return createServerDataTable(datatable_name, {
    url: 'view/_sales_proposal_generation.php',
    type: 'sales proposal for ci evaluation table',
    order: [[4, 'desc']],
    columns: [
      { data: 'SALES_PROPOSAL_NUMBER' },
      { data: 'CUSTOMER' },
      { data: 'PRODUCT_TYPE' },
      { data: 'PRODUCT' },
      { data: 'FOR_CI_DATE' },
      { data: 'STATUS' },
      { data: 'ACTION' },
    ],
    columnDefs: [
      { width: '15%', targets: 0 },
      { width: '15%', targets: 1 },
      { width: '15%', targets: 2 },
      { width: '25%', targets: 3 },
      { width: '25%', type: 'date', targets: 4 },
      { width: '10%', targets: 5 },
      { width: '10%', orderable: false, targets: 6 },
    ],
    buttons,
    showAll: show_all,
  });
}

function salesProposalForCITable(datatable_name, buttons = false, show_all = false) {
  return createServerDataTable(datatable_name, {
    url: 'view/_sales_proposal_generation.php',
    type: 'sales proposal for ci table',
    order: [[4, 'desc']],
    columns: [
      { data: 'SALES_PROPOSAL_NUMBER' },
      { data: 'CUSTOMER' },
      { data: 'PRODUCT_TYPE' },
      { data: 'PRODUCT' },
      { data: 'FOR_CI_DATE' },
      { data: 'STATUS' },
      { data: 'ACTION' },
    ],
    columnDefs: [
      { width: '15%', targets: 0 },
      { width: '15%', targets: 1 },
      { width: '15%', targets: 2 },
      { width: '25%', targets: 3 },
      { width: '25%', type: 'date', targets: 4 },
      { width: '10%', targets: 5 },
      { width: '10%', orderable: false, targets: 6 },
    ],
    buttons,
    showAll: show_all,
  });
}

function salesProposalAllApprovalConditionTable(datatable_name, buttons = false, show_all = false) {
  return createServerDataTable(datatable_name, {
    url: 'view/_sales_proposal_generation.php',
    type: 'sales proposal all approval condition table',
    order: [[4, 'desc']],
    columns: [
      { data: 'SALES_PROPOSAL_NUMBER' },
      { data: 'CUSTOMER' },
      { data: 'BEFORE_FINAL_APPROVAL' },
      { data: 'BEFORE_RELEASE' },
      { data: 'AFTER_RELEASE' },
      { data: 'TOTAL' },
      { data: 'ACTION' },
    ],
    columnDefs: [
      { width: '15%', targets: 0 },
      { width: '15%', targets: 1 },
      { width: '15%', targets: 2 },
      { width: '25%', targets: 3 },
      { width: '25%', targets: 4 },
      { width: '10%', targets: 5 },
      { width: '10%', orderable: false, targets: 6 },
    ],
    buttons,
    showAll: show_all,
  });
}

function salesProposalAdditionalJobOrderTable(datatable_name, buttons = false, show_all = false) {
  return createServerDataTable(datatable_name, {
    url: 'view/_sales_proposal_generation.php',
    type: 'sales proposal additional job order table',
    data: { sales_proposal_id: getSalesProposalId() },
    order: [[0, 'asc']],
    columns: [
      { data: 'JOB_ORDER_NUMBER' },
      { data: 'JOB_ORDER_DATE' },
      { data: 'PARTICULARS' },
      { data: 'COST' },
      { data: 'PROGRESS' },
      { data: 'ACTION' },
    ],
    columnDefs: [
      { width: '25%', targets: 0 },
      { width: '15%', type: 'date', targets: 1 },
      { width: '15%', targets: 2 },
      { width: '15%', targets: 3 },
      { width: '15%', targets: 4 },
      { width: '15%', orderable: false, targets: 5 },
    ],
    buttons,
    showAll: show_all,
  });
}


/* ============================================================
   Shared utilities (drop this ONCE, then reuse everywhere)
   ============================================================ */

const SALES_CONTROLLER = 'controller/sales-proposal-controller.php';

const ui = {
  notifySuccess: (title, msg) => (typeof showNotification === 'function'
    ? showNotification(title, msg, 'success')
    : setNotification(title, msg, 'success')),
  notifyError: (title, msg) => (typeof showNotification === 'function'
    ? showNotification(title, msg, 'danger')
    : setNotification(title, msg, 'danger')),
  inactiveLogout: (msg) => {
    (typeof setNotification === 'function')
      ? setNotification('User Inactive', msg, 'danger')
      : ui.notifyError('User Inactive', msg);
    window.location = 'logout.php?logout';
  },
  errorDialog: (msg) => (typeof showErrorDialog === 'function' ? showErrorDialog(msg) : alert(msg)),
};

const domText = (selector) => ($(selector).text() || '').trim();

const buildErrorMessage = (err, responseText = '') => {
  const base = err?.message ? err.message : String(err || 'Unknown error');
  return responseText ? `${base}\n\nResponse:\n${responseText}` : base;
};

const serializeForm = (form) => new URLSearchParams($(form).serialize());

const postJson = async (url, body, { signal } = {}) => {
  const res = await fetch(url, {
    method: 'POST',
    body,
    signal,
    headers: {
      // When body is URLSearchParams, fetch will set proper Content-Type in modern browsers;
      // If you want to force it uncomment below:
      // 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
    },
  });

  // Try JSON first; if it fails, bubble up full text for debugging.
  const text = await res.text();
  try {
    return JSON.parse(text);
  } catch {
    throw new Error(buildErrorMessage('Invalid JSON response', text));
  }
};


const postFormDataJson = async (url, formData, { signal } = {}) => {
  const res = await fetch(url, {
    method: 'POST',
    body: formData,
    signal,
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
    },
  });

  const text = await res.text();

  try {
    return JSON.parse(text);
  } catch {
    throw new Error(buildErrorMessage('Invalid JSON response', text));
  }
};

// Abort in-flight requests per “key” (prevents overlap/race conditions)
const requestGate = (() => {
  const controllers = new Map();
  return {
    abort(key) {
      const c = controllers.get(key);
      if (c) c.abort();
      controllers.delete(key);
    },
    async run(key, fn) {
      this.abort(key);
      const controller = new AbortController();
      controllers.set(key, controller);
      try {
        const result = await fn(controller.signal);
        return result;
      } finally {
        // keep latest controller if still same; otherwise ignore
        if (controllers.get(key) === controller) controllers.delete(key);
      }
    },
  };
})();

// jQuery Validate shared behavior
const validateUi = {
  errorPlacement(error, element) {
    if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
      error.insertAfter(element.next('.select2-container'));
      return;
    }
    if (element.hasClass('form-check-input')) {
      error.insertAfter(element.closest('.form-check-inline'));
      return;
    }
    if (element.parent('.input-group').length) {
      error.insertAfter(element.parent());
      return;
    }
    error.insertAfter(element);
  },
  highlight(element) {
    const $el = $(element);
    if ($el.hasClass('select2-hidden-accessible')) {
      $el.next().find('.select2-selection__rendered').addClass('is-invalid');
      return;
    }
    $el.addClass('is-invalid');
  },
  unhighlight(element) {
    const $el = $(element);
    if ($el.hasClass('select2-hidden-accessible')) {
      $el.next().find('.select2-selection__rendered').removeClass('is-invalid');
      return;
    }
    $el.removeClass('is-invalid');
  },
};

const handleControllerResponse = (response, { onSuccess } = {}) => {
  if (response?.success) return onSuccess?.(response);

  if (response?.isInactive) return ui.inactiveLogout(response?.message || 'Your session is inactive.');

  // Custom flags from server (keep your behavior)
  if (response?.negativeAmount) {
    return ui.notifyError('Transaction Error', 'Kindly check the other charges amount. The subtotal cannot be negative.');
  }

  ui.notifyError('Transaction Error', response?.message || 'Something went wrong.');
};

const handleNetworkError = (err) => {
  // Abort is not an error worth surfacing
  if (err?.name === 'AbortError') return;
  ui.errorDialog(buildErrorMessage(err));
};

// Generic validated form initializer
const initValidatedForm = ({
  formSelector,
  rules = {},
  messages = {},
  submit,
  errorPlacement = validateUi.errorPlacement,
  highlight = validateUi.highlight,
  unhighlight = validateUi.unhighlight,
}) => {
  const $form = $(formSelector);
  if (!$form.length) return;

  // Store the submit callback for wizard traversal (prevents reload)
  $form.data('wizardSubmit', submit);

  // Prevent native submit always (safety net)
  $form.off('submit.wizardSafety').on('submit.wizardSafety', function (e) {
    e.preventDefault();
    return false;
  });

  $form.validate({
    // ✅ validate hidden select2 fields, ignore other hidden elements
    ignore: ':hidden:not(.select2-hidden-accessible)',
    rules,
    messages,
    errorPlacement,
    highlight,
    unhighlight,

    // jQuery Validate will intercept submit; we call your async submit here
    submitHandler: (form, event) => {
      event?.preventDefault?.();
      return submit(form);
    },
  });
};


/* ============================================================
   Forms (revised)
   ============================================================ */

function salesProposalForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-form',
    rules: {
      renewal_tag: { required: true },
      product_type: { required: true },
      application_source_id: { required: true },
      transaction_type: { required: true },
      company_id: { required: true },
      financing_institution: {
        required: {
          depends: () => $("select[name='transaction_type']").val() === 'Bank Financing',
        },
      },
      comaker_id: {
        required: {
          depends: () => $("select[name='transaction_type']").val() === 'Installment Sales',
        },
      },
      release_date: { required: true },
      start_date: { required: true },
      term_length: { required: true },
      term_type: { required: true },
      payment_frequency: { required: true },
      initial_approving_officer: { required: true },
      final_approving_officer: { required: true },
    },
    messages: {
      renewal_tag: { required: 'Please choose the renewal tag' },
      product_type: { required: 'Please choose the product type' },
      application_source_id: { required: 'Please choose the application source' },
      company_id: { required: 'Please choose the company' },
      transaction_type: { required: 'Please choose the transaction type' },
      financing_institution: { required: 'Please enter the financing institution' },
      comaker_id: { required: 'Please choose the co-maker' },
      release_date: { required: 'Please choose the release date' },
      start_date: { required: 'Please choose the start date' },
      term_length: { required: 'Please enter the term length' },
      term_type: { required: 'Please choose the term type' },
      payment_frequency: { required: 'Please choose the payment frequency' },
      initial_approving_officer: { required: 'Please choose the initial approving officer' },
      final_approving_officer: { required: 'Please choose the final approving officer' },
    },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const customer_id = domText('#customer-id');
      const transaction = 'save sales proposal';

      try {
        const body = serializeForm(form);
        body.append('transaction', transaction);
        body.append('sales_proposal_id', sales_proposal_id);
        body.append('customer_id', customer_id);

        const response = await postJson(SALES_CONTROLLER, body);

        handleControllerResponse(response, {
          onSuccess: (r) => {
            if (r.insertRecord) {
              ui.notifySuccess('Insert Sales Proposal Success', 'The sales proposal has been inserted successfully.');
              window.location = `sales-proposal.php?customer=${r.customerID}&id=${r.salesProposalID}`;
            }
          },
        });
      } catch (err) {
        handleNetworkError(err);
      }

      return false;
    },
  });
}

function salesProposalUnitForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-unit-details-form',
    rules: {
      product_id: { required: true },
      new_color: { required: true },
      new_body: { required: true },
      for_change_engine: { required: true },
      new_engine: {
        required: {
          depends: () => $("select[name='for_change_engine']").val() === 'Yes',
        },
      },
      final_orcr_name: {
        required: {
          depends: () => String($('#product_category').val()) === '1',
        },
      },
    },
    messages: {
      product_id: { required: 'Please choose the stock' },
      new_color: { required: 'Please enter the new color' },
      new_body: { required: 'Please enter the new body' },
      new_engine: { required: 'Please enter the new engine' },
      final_orcr_name: { required: 'Please enter the final name on or/cr' },
    },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const customer_id = domText('#customer-id');
      const transaction = 'save sales proposal unit';

      try {
        const body = serializeForm(form);
        body.append('transaction', transaction);
        body.append('sales_proposal_id', sales_proposal_id);
        body.append('customer_id', customer_id);

        const response = await postJson(SALES_CONTROLLER, body);
        handleControllerResponse(response);
      } catch (err) {
        handleNetworkError(err);
      }

      return false;
    },
  });
}

function salesProposalFuelForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-fuel-details-form',
    rules: {
      diesel_fuel_quantity: { required: true },
      diesel_price_per_liter: { required: true },
      regular_fuel_quantity: { required: true },
      regular_price_per_liter: { required: true },
      premium_fuel_quantity: { required: true },
      premium_price_per_liter: { required: true },
    },
    messages: {
      diesel_fuel_quantity: { required: 'Please enter the fuel quantity' },
      diesel_price_per_liter: { required: 'Please enter the price per liter' },
      regular_fuel_quantity: { required: 'Please enter the fuel quantity' },
      regular_price_per_liter: { required: 'Please enter the price per liter' },
      premium_fuel_quantity: { required: 'Please enter the fuel quantity' },
      premium_price_per_liter: { required: 'Please enter the price per liter' },
    },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const customer_id = domText('#customer-id');
      const transaction = 'save sales proposal fuel';

      try {
        const body = serializeForm(form);
        body.append('transaction', transaction);
        body.append('sales_proposal_id', sales_proposal_id);
        body.append('customer_id', customer_id);

        const response = await postJson(SALES_CONTROLLER, body);
        handleControllerResponse(response);
      } catch (err) {
        handleNetworkError(err);
      }

      return false;
    },
  });
}

function salesProposalRefinancingForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-refinancing-details-form',
    rules: {
      ref_engine_no: { required: true },
      ref_chassis_no: { required: true },
      ref_plate_no: { required: true },
    },
    messages: {
      ref_engine_no: { required: 'Please enter the engine number' },
      ref_chassis_no: { required: 'Please enter the chassis number' },
      ref_plate_no: { required: 'Please enter the plate number' },
    },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const customer_id = domText('#customer-id');
      const transaction = 'save sales proposal refinancing';

      try {
        const body = serializeForm(form);
        body.append('transaction', transaction);
        body.append('sales_proposal_id', sales_proposal_id);
        body.append('customer_id', customer_id);

        const response = await postJson(SALES_CONTROLLER, body);
        handleControllerResponse(response);
      } catch (err) {
        handleNetworkError(err);
      }

      return false;
    },
  });
}

/* ---------- Offcanvas / FormData forms (Job Order, Condition, Uploads) ---------- */

function salesProposalJobOrderForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-job-order-form',
    rules: {
      job_order: { required: true },
      job_order_cost: { required: true },
      approval_document: {
        required: () => $('#job_order_type').val() === 'add',
      },
    },
    messages: {
      job_order: { required: 'Please enter the job order' },
      job_order_cost: { required: 'Please enter the amount charge' },
      approval_document: { required: 'Please choose the approval document' },
    },
    // Uses offcanvas-select2 too
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'save sales proposal job order';

      const formData = new FormData(form);
      formData.append('sales_proposal_id', sales_proposal_id);
      formData.append('transaction', transaction);

      try {
        disableFormSubmitButton?.('submit-sales-proposal-job-order');

        const response = await postFormDataJson(SALES_CONTROLLER, formData);

        handleControllerResponse(response, {
          onSuccess: (r) => {
            const title = r.insertRecord ? 'Insert Job Order Success' : 'Update Job Order Success';
            const desc = r.insertRecord
              ? 'The job order has been inserted successfully.'
              : 'The job order has been updated successfully.';
            ui.notifySuccess(title, desc);
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-sales-proposal-job-order', 'Submit');
        $('#sales-proposal-job-order-offcanvas').offcanvas('hide');
        reloadDatatable?.('#sales-proposal-job-order-table');
        resetModalForm?.('sales-proposal-job-order-form');
        salesProposalSummaryJobOrderTable?.();
      }

      return false;
    },
  });
}

function salesProposalConditionForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-condition-form',
    rules: {
      condition_type: { required: true },
      approval_condition: { required: true },
    },
    messages: {
      condition_type: { required: 'Please choose the condition type' },
      approval_condition: { required: 'Please enter the condition' },
    },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'save sales proposal condition';

      const formData = new FormData(form);
      formData.append('sales_proposal_id', sales_proposal_id);
      formData.append('transaction', transaction);

      try {
        disableFormSubmitButton?.('submit-sales-proposal-condition');

        const response = await postFormDataJson(SALES_CONTROLLER, formData);

        handleControllerResponse(response, {
          onSuccess: (r) => {
            const title = r.insertRecord ? 'Insert Condition Success' : 'Update Condition Success';
            const desc = r.insertRecord
              ? 'The condition has been inserted successfully.'
              : 'The condition has been updated successfully.';
            ui.notifySuccess(title, desc);
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-sales-proposal-condition', 'Submit');
        $('#sales-proposal-condition-offcanvas').offcanvas('hide');
        reloadDatatable?.('#sales-proposal-approval-condition-table');
        resetModalForm?.('sales-proposal-condition-form');
      }

      return false;
    },
  });
}

/* ---------- Pricing / Other Charges ---------- */

function salesProposalPricingComputationForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-pricing-computation-form',
    rules: {
      delivery_price: { required: true },
      add_on_charge: { required: true },
      nominal_discount: { required: true },
      interest_rate: { required: true },
      cost_of_accessories: { required: true },
      reconditioning_cost: { required: true },
      downpayment: { required: true },
    },
    messages: {
      delivery_price: { required: 'Please enter the delivery price' },
      add_on_charge: { required: 'Please enter the add-on' },
      nominal_discount: { required: 'Please enter the nominal discount' },
      interest_rate: { required: 'Please enter the interest rate' },
      cost_of_accessories: { required: 'Please enter the cost of accessories' },
      reconditioning_cost: { required: 'Please enter the reconditioning cost' },
      downpayment: { required: 'Please enter the downpayment' },
    },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const customer_id = domText('#customer-id');
      const transaction = 'save sales proposal pricing computation';

      try {
        const body = serializeForm(form);
        body.append('transaction', transaction);
        body.append('sales_proposal_id', sales_proposal_id);
        body.append('customer_id', customer_id);

        const response = await postJson(SALES_CONTROLLER, body);
        handleControllerResponse(response);
      } catch (err) {
        handleNetworkError(err);
      }

      return false;
    },
  });
}

function salesProposalOtherChargesForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-other-charges-form',
    rules: {
      insurance_coverage: { required: true },
      insurance_premium: { required: true },
      handling_fee: { required: true },
      transfer_fee: { required: true },
      registration_fee: { required: true },
      doc_stamp_tax: { required: true },
      transaction_fee: { required: true },
    },
    messages: {
      insurance_coverage: { required: 'Please enter the insurance coverage' },
      insurance_premium: { required: 'Please enter the insurance premium' },
      handling_fee: { required: 'Please enter the handling fee' },
      transfer_fee: { required: 'Please enter the transfer fee' },
      registration_fee: { required: 'Please enter the registration fee' },
      doc_stamp_tax: { required: 'Please enter the doc stamp tax' },
      transaction_fee: { required: 'Please enter the transaction fee' },
    },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const customer_id = domText('#customer-id');
      const transaction = 'save sales proposal other charges';

      try {
        const body = serializeForm(form);
        body.append('transaction', transaction);
        body.append('sales_proposal_id', sales_proposal_id);
        body.append('customer_id', customer_id);

        const response = await postJson(SALES_CONTROLLER, body);
        handleControllerResponse(response);
      } catch (err) {
        handleNetworkError(err);
      }

      return false;
    },
  });
}

/* ---------- Renewal Amount ---------- */

function salesProposalRenewalAmountForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-renewal-amount-form',
    // No rules/messages in your original (kept)
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'save sales proposal renewal amount';

      try {
        const body = serializeForm(form);
        body.append('transaction', transaction);
        body.append('sales_proposal_id', sales_proposal_id);

        const response = await postJson(SALES_CONTROLLER, body);
        handleControllerResponse(response);
      } catch (err) {
        handleNetworkError(err);
      }

      return false;
    },
  });
}

/* ---------- Deposit Amount ---------- */

function salesProposalDepositAmountForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-deposit-amount-form',
    rules: {
      deposit_date: { required: true },
      reference_number: { required: true },
      deposit_amount: { required: true },
    },
    messages: {
      deposit_date: { required: 'Please choose the deposit date' },
      reference_number: { required: 'Please enter the reference number' },
      deposit_amount: { required: 'Please enter the deposit amount' },
    },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'save sales proposal deposit amount';

      try {
        disableFormSubmitButton?.('submit-sales-proposal-deposit-amount');

        const body = serializeForm(form);
        body.append('transaction', transaction);
        body.append('sales_proposal_id', sales_proposal_id);

        const response = await postJson(SALES_CONTROLLER, body);

        handleControllerResponse(response, {
          onSuccess: (r) => {
            const title = r.insertRecord ? 'Insert Amount of Deposit Success' : 'Update Amount of Deposit Success';
            const desc = r.insertRecord
              ? 'The amount of deposit has been inserted successfully.'
              : 'The amount of deposit has been updated successfully.';
            ui.notifySuccess(title, desc);
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-sales-proposal-deposit-amount', 'Submit');
        $('#sales-proposal-deposit-amount-offcanvas').offcanvas('hide');
        reloadDatatable?.('#sales-proposal-deposit-amount-table');
        resetModalForm?.('sales-proposal-deposit-amount-form');
      }

      return false;
    },
  });
}

/* ---------- Additional Job Order ---------- */

function salesProposalAdditionalJobOrderForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-additional-job-order-form',
    rules: {
      job_order_number: { required: true },
      job_order_date: { required: true },
      particulars: { required: true },
      additional_job_order_cost: { required: true },
    },
    messages: {
      job_order_number: { required: 'Please enter the job order' },
      job_order_date: { required: 'Please choose the job order date' },
      particulars: { required: 'Please enter the particulars' },
      additional_job_order_cost: { required: 'Please enter the amount charge' },
    },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'save sales proposal additional job order';

      try {
        disableFormSubmitButton?.('submit-sales-proposal-additional-job-order');

        const body = serializeForm(form);
        body.append('transaction', transaction);
        body.append('sales_proposal_id', sales_proposal_id);

        const response = await postJson(SALES_CONTROLLER, body);

        handleControllerResponse(response, {
          onSuccess: (r) => {
            const title = r.insertRecord ? 'Insert Additional Job Order Success' : 'Update Additional Job Order Success';
            const desc = r.insertRecord
              ? 'The additional job order has been inserted successfully.'
              : 'The additional job order has been updated successfully.';
            ui.notifySuccess(title, desc);
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-sales-proposal-additional-job-order', 'Submit');
        $('#sales-proposal-additional-job-order-offcanvas').offcanvas('hide');
        reloadDatatable?.('#sales-proposal-additional-job-order-table');
        resetModalForm?.('sales-proposal-additional-job-order-form');
      }

      return false;
    },
  });
}

/* ---------- Upload forms (Client/Comaker/Credit Advice/Engine Stencil/etc.) ---------- */

const initUploadForm = ({
  formSelector,
  submitButtonId,
  transaction,
  successTitle,
  successMessage,
  onSuccess,
}) => {
  initValidatedForm({
    formSelector,
    rules: { ...(formSelector && { [`${$(formSelector).find('input[type="file"]').attr('name')}`]: { required: true } }) },
    // Keep messages explicit like your original (less “magic”)
    messages: {},
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');

      const formData = new FormData(form);
      formData.append('sales_proposal_id', sales_proposal_id);
      formData.append('transaction', transaction);

      try {
        disableFormSubmitButton?.(submitButtonId);

        const response = await postFormDataJson(SALES_CONTROLLER, formData);

        handleControllerResponse(response, {
          onSuccess: () => {
            ui.notifySuccess(successTitle, successMessage);
            onSuccess?.(response);
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.(submitButtonId, 'Submit');
      }

      return false;
    },
  });
};

function salesProposalNoDepositForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-no-deposit-form',
    rules: { client_confirmation_image: { required: true } },
    messages: { client_confirmation_image: { required: 'Please choose the no deposit approval image' } },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'save sales proposal no deposit';

      const fd = new FormData(form);
      fd.append('sales_proposal_id', sales_proposal_id);
      fd.append('transaction', transaction);

      try {
        disableFormSubmitButton?.('submit-sales-proposal-no-deposit');
        const response = await postFormDataJson(SALES_CONTROLLER, fd);

        handleControllerResponse(response, {
          onSuccess: () => {
            ui.notifySuccess('No Deposit Approval Upload Success', 'The approval has been uploaded successfully');
            window.location.reload();
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-sales-proposal-no-deposit', 'Submit');
      }

      return false;
    },
  });
}

function salesProposalClientConfirmationForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-client-confirmation-form',
    rules: { client_confirmation_image: { required: true } },
    messages: { client_confirmation_image: { required: 'Please choose the client confirmation image' } },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'save sales proposal client confirmation';

      const fd = new FormData(form);
      fd.append('sales_proposal_id', sales_proposal_id);
      fd.append('transaction', transaction);

      try {
        disableFormSubmitButton?.('submit-sales-proposal-client-confirmation');
        const response = await postFormDataJson(SALES_CONTROLLER, fd);

        handleControllerResponse(response, {
          onSuccess: () => {
            ui.notifySuccess('Client Confirmation Upload Success', 'The client confirmation has been uploaded successfully');
            window.location.reload();
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-sales-proposal-client-confirmation', 'Submit');
      }

      return false;
    },
  });
}

function salesProposalComakerConfirmationForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-comaker-confirmation-form',
    rules: { comaker_confirmation_image: { required: true } },
    messages: { comaker_confirmation_image: { required: 'Please choose the comaker confirmation image' } },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'save sales proposal comaker confirmation';

      const fd = new FormData(form);
      fd.append('sales_proposal_id', sales_proposal_id);
      fd.append('transaction', transaction);

      try {
        disableFormSubmitButton?.('submit-sales-proposal-comaker-confirmation');
        const response = await postFormDataJson(SALES_CONTROLLER, fd);

        handleControllerResponse(response, {
          onSuccess: () => {
            ui.notifySuccess('Comaker Confirmation Upload Success', 'The comaker confirmation has been uploaded successfully');
            window.location.reload();
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-sales-proposal-comaker-confirmation', 'Submit');
      }

      return false;
    },
  });
}

function salesProposalCreditAdviceForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-credit-advice-form',
    rules: { credit_advice_image: { required: true } },
    messages: { credit_advice_image: { required: 'Please choose the credit advice image' } },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'save sales proposal credit advice';

      const fd = new FormData(form);
      fd.append('sales_proposal_id', sales_proposal_id);
      fd.append('transaction', transaction);

      try {
        disableFormSubmitButton?.('submit-sales-proposal-credit-advice');
        const response = await postFormDataJson(SALES_CONTROLLER, fd);

        handleControllerResponse(response, {
          onSuccess: () => {
            ui.notifySuccess('Credit Advice Upload Success', 'The credit advice has been uploaded successfully');
            displayDetails('get sales proposal confirmation details');
            $('#sales-proposal-credit-advice-offcanvas').offcanvas('hide');
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-sales-proposal-credit-advice', 'Submit');
      }

      return false;
    },
  });
}

function salesProposalEngineStencilForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-engine-stencil-form',
    rules: { new_engine_stencil_image: { required: true } },
    messages: { new_engine_stencil_image: { required: 'Please choose the new engine stencil image' } },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'save sales proposal new engine stencil';

      const fd = new FormData(form);
      fd.append('sales_proposal_id', sales_proposal_id);
      fd.append('transaction', transaction);

      try {
        disableFormSubmitButton?.('engine-stencil');
        const response = await postFormDataJson(SALES_CONTROLLER, fd);

        handleControllerResponse(response, {
          onSuccess: () => {
            ui.notifySuccess('New Engine Stencil Upload Success', 'The new engine stencil has been uploaded successfully');
            displayDetails('get sales proposal confirmation details');
            $('#sales-proposal-new-engine-stencil-offcanvas').offcanvas('hide');
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('engine-stencil', 'Submit');
      }

      return false;
    },
  });
}

/* ---------- Approvals / Reject / Cancel / CI Recommendation / Set to Draft ---------- */

function salesProposalInitalApprovalForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-initial-approval-form',
    rules: { initial_approval_remarks: { required: true } },
    messages: { initial_approval_remarks: { required: 'Please enter the approval remarks' } },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'sales proposal initial approval';

      try {
        disableFormSubmitButton?.('submit-sales-proposal-initial-approval');

        const body = serializeForm(form);
        body.append('transaction', transaction);
        body.append('sales_proposal_id', sales_proposal_id);

        const response = await postJson(SALES_CONTROLLER, body);

        if (response?.success) {
          ui.notifySuccess('For Initial Approval Success', 'The sales proposal has been tagged for final approval successfully.');
          window.location.reload();
          return;
        }

        if (response?.isInactive) return ui.inactiveLogout(response.message);

        if (response?.withApplication) {
          ui.notifyError('For Initial Approval Error', 'The product selected already linked to another sales proposal.');
          return;
        }

        ui.notifyError('Transaction Error', response?.message || 'Something went wrong.');
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-sales-proposal-initial-approval', 'Submit');
        $('#sales-proposal-initial-approval-offcanvas').offcanvas('hide');
      }

      return false;
    },
  });
}

function salesProposalFinalApprovalForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-final-approval-form',
    rules: { final_approval_remarks: { required: true } },
    messages: { final_approval_remarks: { required: 'Please enter the approval remarks' } },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'sales proposal final approval';

      try {
        disableFormSubmitButton?.('submit-sales-proposal-final-approval');

        const body = serializeForm(form);
        body.append('transaction', transaction);
        body.append('sales_proposal_id', sales_proposal_id);

        const response = await postJson(SALES_CONTROLLER, body);

        handleControllerResponse(response, {
          onSuccess: () => {
            ui.notifySuccess('Proceed Sales Proposal Success', 'The sales proposal has been set to proceed successfully.');
            window.location.reload();
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-sales-proposal-final-approval', 'Submit');
        $('#sales-proposal-final-approval-offcanvas').offcanvas('hide');
      }

      return false;
    },
  });
}

function salesProposalRejectForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-reject-form',
    rules: { rejection_reason: { required: true } },
    messages: { rejection_reason: { required: 'Please enter the rejection reason' } },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'sales proposal reject';

      try {
        disableFormSubmitButton?.('submit-sales-proposal-reject');

        const body = serializeForm(form);
        body.append('transaction', transaction);
        body.append('sales_proposal_id', sales_proposal_id);

        const response = await postJson(SALES_CONTROLLER, body);

        handleControllerResponse(response, {
          onSuccess: () => {
            ui.notifySuccess('Reject Sales Proposal Success', 'The sales proposal has been rejected successfully.');
            window.location.reload();
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-sales-proposal-reject', 'Submit');
        $('#sales-proposal-reject-offcanvas').offcanvas('hide');
      }

      return false;
    },
  });
}

function installmentSalesApprovalForm() {
  initValidatedForm({
    formSelector: '#approve-installment-sales-form',
    rules: {
      installment_sales_approval_remarks: { required: true },
      term_length_2: { required: true },
      add_on_charge_2: { required: true },
      nominal_discount_2: { required: true },
      interest_rate_2: { required: true },
      downpayment_2: { required: true },
    },
    messages: {
      installment_sales_approval_remarks: { required: 'Please enter the approval remarks' },
      term_length_2: { required: 'Please enter the term length' },
      add_on_charge_2: { required: 'Please enter the add-on charge' },
      nominal_discount_2: { required: 'Please enter the nominal discount' },
      interest_rate_2: { required: 'Please enter the interest rate' },
      downpayment_2: { required: 'Please enter the downpayment' },
    },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'sales installment approval';

      try {
        disableFormSubmitButton?.('submit-sales-proposal-reject');

        const body = serializeForm(form);
        body.append('transaction', transaction);
        body.append('sales_proposal_id', sales_proposal_id);

        const response = await postJson(SALES_CONTROLLER, body);

        handleControllerResponse(response, {
          onSuccess: () => {
            ui.notifySuccess('Approve Sales Installment Success', 'The sales installment has been approved successfully.');
            displayDetails('get sales proposal basic details');
            displayDetails('get sales proposal pricing computation details');
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        // Keep your behavior, but ensure sequence is predictable:
        $('#sales-proposal-pricing-computation-form').trigger('submit');
        enableFormSubmitButton?.('submit-sales-proposal-reject', 'Submit');
        $('#sales-proposal-reject-offcanvas').offcanvas('hide');
        window.location.reload();
      }

      return false;
    },
  });
}

function salesProposalCancelForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-cancel-form',
    rules: { cancellation_reason: { required: true } },
    messages: { cancellation_reason: { required: 'Please enter the cancellation reason' } },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'sales proposal cancel';

      try {
        disableFormSubmitButton?.('submit-sales-proposal-cancel');

        const body = serializeForm(form);
        body.append('transaction', transaction);
        body.append('sales_proposal_id', sales_proposal_id);

        const response = await postJson(SALES_CONTROLLER, body);

        handleControllerResponse(response, {
          onSuccess: () => {
            ui.notifySuccess('Cancel Sales Proposal Success', 'The sales proposal has been cancelled successfully.');
            window.location.reload();
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-sales-proposal-cancel', 'Submit');
        $('#sales-proposal-cancel-offcanvas').offcanvas('hide');
      }

      return false;
    },
  });
}

function salesProposalCIRecommendationForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-ci-recommendation-form',
    rules: { ci_recommendation: { required: true } },
    messages: { ci_recommendation: { required: 'Please enter the CI recommendation' } },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'sales proposal ci recommendation';

      try {
        disableFormSubmitButton?.('submit-sales-proposal-ci-recommendation');

        const body = serializeForm(form);
        body.append('transaction', transaction);
        body.append('sales_proposal_id', sales_proposal_id);

        const response = await postJson(SALES_CONTROLLER, body);

        handleControllerResponse(response, {
          onSuccess: () => ui.notifySuccess('CI Recommendation Success', 'The CI recommendation has been submitted successfully.'),
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-sales-proposal-ci-recommendation', 'Submit');
        $('#sales-proposal-ci-recommendation-offcanvas').offcanvas('hide');
      }

      return false;
    },
  });
}

function salesProposalSetToDraftForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-set-to-draft-form',
    rules: {
      set_to_draft_reason: { required: true },
      set_to_draft_file: {
        required: {
          depends: () => {
            const status = $('#sales_proposal_status').val();
            return status !== 'Draft' &&
              status !== 'Cancelled' &&
              status !== 'Rejected' &&
              status !== 'Released' &&
              status !== 'For Review' &&
              status !== 'For Initial Approval';
          },
        },
      },
    },
    messages: {
      set_to_draft_reason: { required: 'Please enter the set to draft reason' },
      set_to_draft_file: { required: 'Please choose the set to draft file' },
    },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'sales proposal set to draft';

      const fd = new FormData(form);
      fd.append('sales_proposal_id', sales_proposal_id);
      fd.append('transaction', transaction);

      try {
        disableFormSubmitButton?.('submit-sales-proposal-set-to-draft');
        const response = await postFormDataJson(SALES_CONTROLLER, fd);

        handleControllerResponse(response, {
          onSuccess: () => {
            ui.notifySuccess('Set Sales Proposal To Draft Success', 'The sales proposal has been set to draft successfully.');
            window.location.reload();
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-sales-proposal-set-to-draft', 'Submit');
        $('#sales-proposal-set-to-draft-offcanvas').offcanvas('hide');
      }

      return false;
    },
  });
}

function salesProposalOtherDocumentForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-other-document-form',
    rules: {
      other_document_file: { required: true },
    },
    messages: {
      other_document_file: { required: 'Please choose the other document' },
    },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'sales proposal other document';

      const fd = new FormData(form);
      fd.append('sales_proposal_id', sales_proposal_id);
      fd.append('transaction', transaction);

      try {
        disableFormSubmitButton?.('submit-sales-proposal-set-to-draft');

        const response = await postFormDataJson(SALES_CONTROLLER, fd);

        handleControllerResponse(response, {
          onSuccess: () => {
            ui.notifySuccess('Save Other Document', 'The other document has been saved successfully.');
            window.location.reload();
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-sales-proposal-set-to-draft', 'Submit');
        $('#sales-proposal-set-to-draft-offcanvas').offcanvas('hide');
      }

      return false;
    },
  });
}

function salesProposalQualityControlForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-quality-control-form',
    rules: {
      quality_control_image: { required: true },
    },
    messages: {
      quality_control_image: { required: 'Please choose the quality control form image' },
    },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'save sales proposal quality control form';

      const fd = new FormData(form);
      fd.append('sales_proposal_id', sales_proposal_id);
      fd.append('transaction', transaction);

      try {
        disableFormSubmitButton?.('submit-sales-proposal-quality-control');

        const response = await postFormDataJson(SALES_CONTROLLER, fd);

        handleControllerResponse(response, {
          onSuccess: () => {
            ui.notifySuccess('Quality Control Form Upload Success', 'The quality control form has been uploaded successfully.');
            window.location.reload();
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-sales-proposal-quality-control', 'Submit');
      }

      return false;
    },
  });
}

function salesProposalOutgoingChecklistForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-outgoing-checklist-form',
    rules: {
      outgoing_checklist_image: { required: true },
    },
    messages: {
      outgoing_checklist_image: { required: 'Please choose the outgoing checklist image' },
    },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'save sales proposal outgoing checklist';

      const fd = new FormData(form);
      fd.append('sales_proposal_id', sales_proposal_id);
      fd.append('transaction', transaction);

      try {
        disableFormSubmitButton?.('submit-sales-proposal-outgoing-checklist');

        const response = await postFormDataJson(SALES_CONTROLLER, fd);

        handleControllerResponse(response, {
          onSuccess: () => {
            ui.notifySuccess('Outgoing Checklist Upload Success', 'The outgoing checklist has been uploaded successfully.');
            window.location.reload();
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-sales-proposal-outgoing-checklist', 'Submit');
      }

      return false;
    },
  });
}

function salesProposalUnitImageForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-unit-image-form',
    rules: {
      unit_image_image: { required: true },
    },
    messages: {
      unit_image_image: { required: 'Please choose the unit image' },
    },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'save sales proposal unit image';

      const fd = new FormData(form);
      fd.append('sales_proposal_id', sales_proposal_id);
      fd.append('transaction', transaction);

      try {
        disableFormSubmitButton?.('submit-sales-proposal-unit-image');

        const response = await postFormDataJson(SALES_CONTROLLER, fd);

        handleControllerResponse(response, {
          onSuccess: () => {
            ui.notifySuccess('Unit Image Upload Success', 'The unit image has been uploaded successfully.');
            window.location.reload();
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-sales-proposal-unit-image', 'Submit');
      }

      return false;
    },
  });
}

function salesProposalAdditionalJobOrderConfirmationImageForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-job-order-confirmation-form',
    rules: {
      additional_job_order_confirmation_image: { required: true },
    },
    messages: {
      additional_job_order_confirmation_image: { required: 'Please choose the additional job order confimation image' },
    },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'save sales proposal additional job order confirmation';

      const fd = new FormData(form);
      fd.append('sales_proposal_id', sales_proposal_id);
      fd.append('transaction', transaction);

      try {
        disableFormSubmitButton?.('submit-sales-proposal-additional-job-order-confirmation');

        const response = await postFormDataJson(SALES_CONTROLLER, fd);

        handleControllerResponse(response, {
          onSuccess: () => {
            ui.notifySuccess(
              'Additional Job Order Confirmation Upload Success',
              'The additional job order confirmation has been uploaded successfully.'
            );
            window.location.reload();
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-sales-proposal-additional-job-order-confirmation', 'Submit');
      }

      return false;
    },
  });
}

function salesProposalPDCManualInputForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-pdc-manual-input-form',
    rules: {
      pdc_payment_frequency: { required: true },
      payment_for: { required: true },
      no_of_payments: { required: true },
      first_check_number: { required: true },
      first_check_date: { required: true },
      amount_due: { required: true },
    },
    messages: {
      pdc_payment_frequency: { required: 'Please choose the payment frequency' },
      payment_for: { required: 'Please choose the payment for' },
      no_of_payments: { required: 'Please enter the number of payments' },
      first_check_number: { required: 'Please enter the first check number' },
      first_check_date: { required: 'Please choose the first check date' },
      amount_due: { required: 'Please enter the gross amount' },
    },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'save sales proposal pdc manual input';

      try {
        disableFormSubmitButton?.('submit-sales-proposal-pdc-manual-input');

        const payload = new URLSearchParams($(form).serialize());
        payload.set('transaction', transaction);
        payload.set('sales_proposal_id', sales_proposal_id);

        const response = await postJson(SALES_CONTROLLER, payload);

        handleControllerResponse(response, {
          onSuccess: () => {
            reloadDatatable?.('#sales-proposal-pdc-manual-input-table');
          },
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-sales-proposal-pdc-manual-input', 'Submit');
        $('#sales-proposal-pdc-manual-input-offcanvas').offcanvas('hide');
        salesProposalSummaryPDCManualInputTable?.();
      }

      return false;
    },
  });
}

function salesProposalOtherProductDetailsForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-other-product-details-form',
    rules: {
      dr_number: { required: true },
      actual_start_date: { required: true },
      product_description: { required: true },
    },
    messages: {
      dr_number: { required: 'Please enter the DR number' },
      actual_start_date: { required: 'Please choose the actual start date' },
      product_description: { required: 'Please enter the product description' },
    },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'save sales proposal other product details';

      try {
        const payload = new URLSearchParams($(form).serialize());
        payload.set('transaction', transaction);
        payload.set('sales_proposal_id', sales_proposal_id);

        const response = await postJson(SALES_CONTROLLER, payload);

        handleControllerResponse(response, {
          // original had no success UI; keep it quiet
          onSuccess: () => {},
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        displayDetails?.('get sales proposal other product details');
        salesProposalDisclosureScheduleTable?.();
      }

      return false;
    },
  });
}

function salesProposalReleaseForm() {
  initValidatedForm({
    formSelector: '#sales-proposal-tag-as-released-form',
    rules: {
      release_remarks: { required: true },
    },
    messages: {
      release_remarks: { required: 'Please eneter the release remarks' },
    },
    submit: async (form) => {
      const sales_proposal_id = domText('#sales-proposal-id');
      const transaction = 'tag for release';

      try {
        disableFormSubmitButton?.('submit-other-product-details-data');

        const payload = new URLSearchParams($(form).serialize());
        payload.set('transaction', transaction);
        payload.set('sales_proposal_id', sales_proposal_id);

        const response = await postJson(SALES_CONTROLLER, payload);

        handleControllerResponse(response, {
          onSuccess: () => window.location.reload(),
        });
      } catch (err) {
        handleNetworkError(err);
      } finally {
        enableFormSubmitButton?.('submit-other-product-details-data', 'Submit');
        displayDetails?.('get sales proposal other product details');
      }

      return false;
    },
  });
}


/* ============================================================
   displayDetails (revised to prevent overlapping/race conditions)
   - Uses requestGate.run(key, ...) so repeated calls do not collide
   ============================================================ */

async function displayDetails(transaction) {
  const sales_proposal_id = domText('#sales-proposal-id');

  // Key by transaction so rapid calls cancel previous in-flight for same transaction
  const key = `display:${transaction}`;

  // Allow different endpoints per case
  const runTo = async (url, payload) => requestGate.run(key, async (signal) => {
    const body = new URLSearchParams(payload);
    return postJson(url, body, { signal });
  });

  const fail = (title, response, fallbackMsg) => {
    if (response?.isInactive) return ui.inactiveLogout(response.message);
    ui.notifyError(title, response?.message || fallbackMsg);
  };

  try {
    switch (transaction) {

      case 'get sales proposal basic details': {
        const sales_proposal_id = domText('#sales-proposal-id');

        const response = await runTo(SALES_CONTROLLER, { sales_proposal_id, transaction });

        if (!response?.success) {
          return fail('Get Sales Proposal Details Error', response, 'Unable to load basic details.');
        }

        $('#sales_proposal_number').text(response.salesProposalNumber);
        $('#summary-sales-proposal-number').text(response.salesProposalNumber);

        if (!['Unit', 'Rental', 'Consignment', 'Refinancing', 'Brand New', 'Restructure'].includes(response.productType)) {
          $('#summary-stock-no').text(response.salesProposalNumber);
        }

        $('#financing_institution').val(response.financingInstitution);
        $('#referred_by').val(response.referredBy);
        $('#commission_amount').val(response.commissionAmount);
        $('#release_date').val(response.releaseDate);
        $('#start_date').val(response.startDate);
        $('#term_length').val(response.termLength);
        $('#number_of_payments').val(response.numberOfPayments);
        $('#first_due_date').val(response.firstDueDate);
        $('#remarks').val(response.remarks);
        $('#dr_number').val(response.drNumber);
        $('#release_to').val(response.releaseTo);
        $('#actual_start_date').val(response.actualStartDate);

        if ($('#term_length_2').length) $('#term_length_2').val(response.termLength);

        if ($('#draft-file').length) document.getElementById('draft-file').src = response.setToDraftFile;
        if ($('#other-document-file').length) document.getElementById('other-document-file').src = response.otherDocumentFile;

        $('#summary-commission').text(encryptCommission(response.commissionAmount));

        checkOptionExist('#renewal_tag', response.renewalTag, '');
        checkOptionExist('#application_source_id', response.applicationSourceID, '');
        checkOptionExist('#product_type', response.productType, '');
        checkOptionExist('#transaction_type', response.transactionType, '');
        checkOptionExist('#comaker_id', response.comakerID, '');
        checkOptionExist('#additional_maker_id', response.additionalMakerID, '');
        checkOptionExist('#comaker_id2', response.comakerID2, '');
        checkOptionExist('#term_type', response.termType, '');
        checkOptionExist('#payment_frequency', response.paymentFrequency, '');
        checkOptionExist('#initial_approving_officer', response.initialApprovingOfficer, '');
        checkOptionExist('#final_approving_officer', response.finalApprovingOfficer, '');
        checkOptionExist('#company_id', response.companyID, '');

        $('#summary-referred-by').text(response.referredBy);
        $('#summary-release-date').text(response.releaseDate);
        $('#summary-product-type').text(response.productType);
        $('#summary-transaction-type').text(response.transactionType);
        $('#summary-term').text(`${response.termLength} ${response.termType}`);
        $('#insurance_term').text(`${response.termLength} ${response.termType}`);
        $('#insurance_maturity').text(response.maturityDate);
        $('#summary-no-payments').text(response.numberOfPayments);
        $('#summary-remarks').text(response.remarks);
        $('#summary-initial-approval-by').text(response.initialApprovingOfficerName);
        $('#summary-final-approval-by').text(response.finalApprovingOfficerName);
        $('#summary-created-by').text(response.createdByName);
        $('#created-date-summary').val(response.createdDate);

        $('#initial_approval_remarks_label').text(response.initialApprovalRemarks);
        $('#initial_approval_remarks').val(response.initialApprovalRemarks);
        $('#final_approval_remarks_label').text(response.finalApprovalRemarks);
        $('#final_approval_remarks').val(response.finalApprovalRemarks);
        $('#installment_sales_approval_remarks_label').text(response.installmentSalesApprovalRemarks);
        $('#ci_recommendation_label').html(String(response.ci_recommendation || '').replace(/\n/g, '<br>'));
        $('#rejection_reason_label').text(response.rejectionReason);
        $('#cancellation_reason_label').text(response.cancellationReason);
        $('#set_to_draft_reason_label').text(response.setToDraftReason);
        $('#release_remarks_label').text(response.releaseRemarks);

        $('#summary-total-job-order-progress').text(response.jobOrderProgress);
        document.getElementById('summary-total-additional-job-order').innerHTML = response.totalAdditionalJobOrder;

        // complete() logic → explicit follow-ups
        const productType = $('#product_type').val();

        if (['Unit', 'Rental', 'Consignment'].includes(productType)) {
          displayDetails('get sales proposal unit details');
        } else if (productType === 'Fuel') {
          displayDetails('get sales proposal fuel details');
        } else {
          displayDetails('get sales proposal refinancing details');
        }

        calculateTotalOtherCharges();
        break;
      }

      case 'get sales proposal unit details': {
        const sales_proposal_id = domText('#sales-proposal-id');

        const response = await runTo(SALES_CONTROLLER, { sales_proposal_id, transaction });

        if (!response?.success) {
          return fail('Get Sales Proposal Details Error', response, 'Unable to load unit details.');
        }

        $('#old_color').val(response.oldColor);
        $('#new_color').val(response.newColor);
        $('#old_body').val(response.oldBody);
        $('#new_body').val(response.newBody);
        $('#old_engine').val(response.oldEngine);
        $('#new_engine').val(response.newEngine);
        $('#final_orcr_name').val(response.finalOrcrName);
        $('#summary-final-name-on-orcr').text(response.finalOrcrName);

        checkOptionExist('#product_id', response.productID, '');
        checkOptionExist('#for_registration', response.forRegistration, '');
        checkOptionExist('#with_cr', response.withCR, '');
        checkOptionExist('#for_transfer', response.forTransfer, '');
        checkOptionExist('#for_change_color', response.forChangeColor, '');
        checkOptionExist('#for_change_body', response.forChangeBody, '');
        checkOptionExist('#for_change_engine', response.forChangeEngine, '');

        $('#summary-new-color').text(response.newColor);
        $('#summary-new-body').text(response.newBody);
        $('#summary-new-engine').text(response.newEngine);

        // complete() logic
        displayDetails('get sales proposal pricing computation details');
        break;
      }

      case 'get sales proposal fuel details': {
        const sales_proposal_id = domText('#sales-proposal-id');

        const response = await runTo(SALES_CONTROLLER, { sales_proposal_id, transaction });

        if (!response?.success) {
          return fail('Get Sales Proposal Details Error', response, 'Unable to load fuel details.');
        }

        $('#diesel_fuel_quantity').val(response.dieselFuelQuantity);
        $('#diesel_price_per_liter').val(response.dieselPricePerLiter);
        $('#regular_fuel_quantity').val(response.regularFuelQuantity);
        $('#regular_price_per_liter').val(response.regularPricePerLiter);
        $('#premium_fuel_quantity').val(response.premiumFuelQuantity);
        $('#premium_price_per_liter').val(response.premiumPricePerLiter);

        $('#summary-diesel-fuel-quantity').text(`${response.dieselFuelQuantity} lt`);
        $('#summary-regular-fuel-quantity').text(`${response.regularFuelQuantity} lt`);
        $('#summary-premium-fuel-quantity').text(`${response.premiumFuelQuantity} lt`);

        // complete() logic
        calculateFuelTotal();
        displayDetails('get sales proposal pricing computation details');
        break;
      }

      case 'get sales proposal refinancing details': {
        const sales_proposal_id = domText('#sales-proposal-id');

        const response = await runTo(SALES_CONTROLLER, { sales_proposal_id, transaction });

        if (!response?.success) {
          return fail('Get Sales Proposal Details Error', response, 'Unable to load refinancing details.');
        }

        $('#ref_stock_no').text(response.refStockNo);
        $('#ref_engine_no').val(response.refEngineNo);
        $('#ref_chassis_no').val(response.refChassisNo);
        $('#ref_plate_no').val(response.refPlateNo);

        $('#orcr_no').val(response.orcrNo);
        $('#orcr_date').val(response.orcrDate);
        $('#orcr_expiry_date').val(response.orcrExpiryDate);
        $('#received_from').val(response.receivedFrom);
        $('#received_from_address').val(response.receivedFromAddress);
        $('#received_from_id_number').val(response.receivedFromIDNumber);
        $('#unit_description').val(response.unitDescription);

        // prevent duplication in summary remarks
        const existingText = $('#summary-remarks').text().trim();
        const newText = String(response.unitDescription || '').trim();

        if (newText && !existingText.includes(newText)) {
          $('#summary-remarks').text(existingText ? `${existingText}\n\n${newText}` : newText);
        }

        checkOptionExist('#received_from_id_type', response.receivedFromIDType, '');

        $('#summary-stock-no').text(response.refStockNo);
        $('#summary-engine-no').text(response.refEngineNo);
        $('#summary-chassis-no').text(response.refChassisNo);
        $('#summary-plate-no').text(response.refPlateNo);

        $('#insurance_unit_no').text(response.refStockNo);
        $('#insurance_engine_no').text(response.refEngineNo);
        $('#insurance_chassis_no').text(response.refChassisNo);
        $('#insurance_plate_no').text(response.refPlateNo);

        // complete() logic
        displayDetails('get sales proposal pricing computation details');
        break;
      }


      case 'get sales proposal job order details': {
        const sales_proposal_job_order_id = sessionStorage.getItem('sales_proposal_job_order_id');

        const response = await runTo(SALES_CONTROLLER, {
          sales_proposal_job_order_id,
          transaction,
        });

        if (!response?.success) {
          return fail('Get Sales Proposal Job Order Details Error', response, 'Unable to load job order details.');
        }

        $('#sales_proposal_job_order_id').val(sales_proposal_job_order_id);
        $('#job_order').val(response.jobOrder);
        $('#job_order_cost').val(response.cost);

        break;
      }

      case 'get sales proposal condition details': {
        const sales_proposal_condition_id = sessionStorage.getItem('sales_proposal_condition_id');

        const response = await runTo(SALES_CONTROLLER, {
          sales_proposal_condition_id,
          transaction,
        });

        if (!response?.success) {
          return fail('Get Sales Proposal Condition Details Error', response, 'Unable to load condition details.');
        }

        $('#sales_proposal_condition_id').val(sales_proposal_condition_id);
        $('#approval_condition').val(response.approvalCondition);

        checkOptionExist('#condition_type', response.conditionType, '');
        break;
      }

      case 'get sales proposal pricing computation details': {
        let response;

        try {
          response = await runTo(SALES_CONTROLLER, { sales_proposal_id, transaction });

          if (!response?.success) {
            return fail(
              'Get Sales Proposal Pricing Computation Details Error',
              response,
              'Unable to load pricing computation details.'
            );
          }

          const productType = $('#product_type').val();

          if (!['Unit', 'Rental', 'Consignment'].includes(productType)) {
            $('#delivery_price').val(response.deliveryPrice);
          }

          $('#nominal_discount').val(response.nominalDiscount);
          $('#add_on_charge').val(response.addOnCharge);
          $('#cost_of_accessories').val(response.costOfAccessories);
          $('#reconditioning_cost').val(response.reconditioningCost);
          $('#downpayment').val(response.downpayment);
          $('#interest_rate').val(response.interestRate);

          if ($('#add_on_charge_2').length) $('#add_on_charge_2').val(response.addOnCharge);
          if ($('#nominal_discount_2').length) $('#nominal_discount_2').val(response.nominalDiscount);
          if ($('#interest_rate_2').length) $('#interest_rate_2').val(response.interestRate);
          if ($('#downpayment_2').length) $('#downpayment_2').val(response.downpayment);

        } finally {
          // original $.ajax complete:
          calculateTotalDeliveryPrice();
        }

        break;
      }

      case 'get sales proposal other charges details': {
        let response;

        try {
          response = await runTo(SALES_CONTROLLER, { sales_proposal_id, transaction });

          if (!response?.success) {
            return fail(
              'Get Sales Proposal Other Charges Details Error',
              response,
              'Unable to load other charges details.'
            );
          }

          $('#insurance_coverage').val(response.insuranceCoverage);
          $('#insurance_request_coverage_1').text(response.insuranceCoverage);

          $('#insurance_premium').val(response.insurancePremium);
          $('#insurance_request_premium_1').text(response.insurancePremium);

          $('#handling_fee').val(response.handlingFee);
          $('#transfer_fee').val(response.transferFee);
          $('#registration_fee').val(response.registrationFee);
          $('#doc_stamp_tax').val(response.docStampTax);
          $('#transaction_fee').val(response.transactionFee);

          $('#insurance_premium_discount').val(response.insurancePremiumDiscount);
          $('#handling_fee_discount').val(response.handlingFeeDiscount);
          $('#doc_stamp_tax_discount').val(response.docStampTaxDiscount);
          $('#transaction_fee_discount').val(response.transactionFeeDiscount);
          $('#transfer_fee_discount').val(response.transferFeeDiscount);

          $('#insurance_premium_subtotal').val(response.insurancePremiumSubtotal);
          $('#handling_fee_subtotal').val(response.handlingFeeSubtotal);
          $('#doc_stamp_tax_subtotal').val(response.docStampTaxSubtotal);
          $('#transaction_fee_subtotal').val(response.transactionFeeSubtotal);
          $('#transfer_fee_subtotal').val(response.transferFeeSubtotal);

          $('#summary-insurance-coverage').text(parseFloat(response.insuranceCoverage).toLocaleString('en-US'));
          $('#summary-insurance-premium').text(parseFloat(response.insurancePremiumSubtotal).toLocaleString('en-US'));
          $('#summary-handing-fee').text(parseFloat(response.handlingFeeSubtotal).toLocaleString('en-US'));
          $('#summary-transfer-fee').text(parseFloat(response.transferFeeSubtotal).toLocaleString('en-US'));
          $('#summary-registration-fee').text(parseFloat(response.registrationFee).toLocaleString('en-US'));
          $('#summary-doc-stamp-tax').text(parseFloat(response.docStampTaxSubtotal).toLocaleString('en-US'));
          $('#summary-transaction-fee').text(parseFloat(response.transactionFeeSubtotal).toLocaleString('en-US'));
          $('#summary-other-charges-total').text(parseFloat(response.totalOtherCharges).toLocaleString('en-US'));

        } finally {
          // original $.ajax complete:
          calculateTotalOtherCharges();
        }

        break;
      }

      case 'get sales proposal renewal amount details': {
        const response = await runTo(SALES_CONTROLLER, { sales_proposal_id, transaction });

        if (!response?.success) {
          return fail(
            'Get Sales Proposal Renewal Amount Details Error',
            response,
            'Unable to load renewal amount details.'
          );
        }

        $('#registration_second_year').val(response.registrationSecondYear);
        $('#registration_third_year').val(response.registrationThirdYear);
        $('#registration_fourth_year').val(response.registrationFourthYear);

        $('#insurance_coverage_second_year').val(response.insuranceCoverageSecondYear);
        $('#insurance_coverage_third_year').val(response.insuranceCoverageThirdYear);
        $('#insurance_coverage_fourth_year').val(response.insuranceCoverageFourthYear);

        $('#insurance_premium_second_year').val(response.insurancePremiumSecondYear);
        $('#insurance_premium_third_year').val(response.insurancePremiumThirdYear);
        $('#insurance_premium_fourth_year').val(response.insurancePremiumFourthYear);

        // summaries
        $('#summary-registration-second-year').text(response.registrationSecondYearSummary);
        $('#summary-registration-third-year').text(response.registrationThirdYearSummary);
        $('#summary-registration-fourth-year').text(response.registrationFourthYearSummary);

        $('#summary-insurance-coverage-second-year').text(response.insuranceCoverageSecondYearSummary);
        $('#2nd_year_coverage').text(response.insuranceCoverageSecondYearSummary);

        $('#summary-insurance-coverage-third-year').text(response.insuranceCoverageThirdYearSummary);
        $('#3rd_year_coverage').text(response.insuranceCoverageThirdYearSummary);

        $('#summary-insurance-coverage-fourth-year').text(response.insuranceCoverageFourthYearSummary);
        $('#4th_year_coverage').text(response.insuranceCoverageFourthYearSummary);

        $('#summary-insurance-premium-second-year').text(response.insurancePremiumSecondYearSummary);
        $('#summary-insurance-premium-third-year').text(response.insurancePremiumThirdYearSummary);
        $('#summary-insurance-premium-fourth-year').text(response.insurancePremiumFourthYearSummary);

        $('#2nd_year_date').text(response.secondYearInsuranceDate);
        $('#3rd_year_date').text(response.thirdYearInsuranceDate);
        $('#4th_year_date').text(response.fourthYearInsuranceDate);

        const product_category = $('#product_category').val();
        const product_type = $('#product_type').val();

        const setYearReadonly = (year, coverageReadonly, premiumReadonly) => {
          $(`#insurance_coverage_${year}`).attr('readonly', coverageReadonly);
          $(`#insurance_premium_${year}`).attr('readonly', premiumReadonly);
        };

        const computeReadonlyRules = ({ productType, productCategory, computeChecked, premiumIsComputed }) => {
          // Mode: Refinancing -> auto compute, always readonly
          if (productType === 'Refinancing') {
            return { coverage: true, premium: true };
          }

          // Mode: Restructure -> manual input allowed ONLY if checkbox is checked
          // (matches: attr('readonly', !checked))
          if (productType === 'Restructure') {
            const editable = !!computeChecked;
            return { coverage: !editable, premium: !editable };
          }

          // Mode: Default
          // - When checkbox is NOT checked => fields get reset and forced readonly
          //   (matches: setYear(... readonly: true) when unchecked)
          if (!computeChecked) {
            return { coverage: true, premium: true };
          }

          // Mode: Default + checkbox checked:
          // - If premium is computed (category 1/2/3) => readonly
          // - Else (premiumIsComputed false) => editable
          if (premiumIsComputed) {
            return { coverage: true, premium: true };
          }

          return { coverage: false, premium: false };
        };

        const isPremiumComputed = ['1', '2', '3'].includes(product_category);

        // 2nd year
        if (response.insuranceCoverageSecondYear > 0) {
            $('#compute_second_year').prop('checked', true);
            // PASS THE DATA HERE:
            const r = computeReadonlyRules({ 
                productType: product_type, 
                productCategory: product_category, 
                computeChecked: true, 
                premiumIsComputed: isPremiumComputed 
            });
            setYearReadonly('second_year', r.coverage, r.premium);
        } else {
            $('#compute_second_year').prop('checked', false);
        }

        // 3rd year
        if (response.insuranceCoverageThirdYear > 0) {
            $('#compute_third_year').prop('checked', true);
            // PASS THE DATA HERE:
            const r = computeReadonlyRules({ 
                productType: product_type, 
                productCategory: product_category, 
                computeChecked: true, 
                premiumIsComputed: isPremiumComputed 
            });
            setYearReadonly('third_year', r.coverage, r.premium);
        } else {
            $('#compute_third_year').prop('checked', false);
        }

        // 4th year
        if (response.insuranceCoverageFourthYear > 0) {
            $('#compute_fourth_year').prop('checked', true);
            // PASS THE DATA HERE:
            const r = computeReadonlyRules({ 
                productType: product_type, 
                productCategory: product_category, 
                computeChecked: true, 
                premiumIsComputed: isPremiumComputed 
            });
            setYearReadonly('fourth_year', r.coverage, r.premium);
        } else {
            $('#compute_fourth_year').prop('checked', false);
        }
        break;
      }

      case 'get sales proposal deposit amount details': {
        const sales_proposal_deposit_amount_id = sessionStorage.getItem('sales_proposal_deposit_amount_id');

        const response = await runTo(SALES_CONTROLLER, {
          sales_proposal_deposit_amount_id,
          transaction,
        });

        if (!response?.success) {
          return fail(
            'Get Sales Proposal Deposit Amount Details Error',
            response,
            'Unable to load deposit amount details.'
          );
        }

        $('#sales_proposal_deposit_amount_id').val(sales_proposal_deposit_amount_id);
        $('#deposit_date').val(response.depositDate);
        $('#reference_number').val(response.referenceNumber);
        $('#deposit_amount').val(response.depositAmount);

        break;
      }

      case 'get sales proposal additional job order details': {
        const sales_proposal_additional_job_order_id = sessionStorage.getItem('sales_proposal_additional_job_order_id');

        const response = await runTo(SALES_CONTROLLER, {
          sales_proposal_additional_job_order_id,
          transaction,
        });

        if (!response?.success) {
          return fail(
            'Get Sales Proposal Additional Job Order Details Error',
            response,
            'Unable to load additional job order details.'
          );
        }

        $('#sales_proposal_additional_job_order_id').val(sales_proposal_additional_job_order_id);
        $('#job_order_number').val(response.jobOrderNumber);
        $('#job_order_date').val(response.jobOrderDate);
        $('#particulars').val(response.particulars);
        $('#additional_job_order_cost').val(response.cost);

        break;
      }

      case 'get sales proposal confirmation details': {
        const response = await runTo(SALES_CONTROLLER, { sales_proposal_id, transaction });

        if (!response?.success) {
          return fail(
            'Get Sales Proposal Confirmation Details Error',
            response,
            'Unable to load confirmation details.'
          );
        }

        // small helper (no jQuery lookup duplication)
        const setImg = (selector, src) => {
          const el = document.querySelector(selector);
          if (el) el.src = src;
        };

        setImg('#client-confirmation-image', response.clientConfirmation);
        setImg('#no-deposit-image', response.noDepositApproval);
        setImg('#comaker-confirmation-image', response.comakerConfirmation);
        setImg('#credit-advice-image', response.creditAdvice);
        setImg('#new-engine-stencil-image', response.newEngineStencil);
        setImg('#additional-job-order-confirmation-image', response.additionalJobOrderConfirmationImage);
        setImg('#quality-control-form-image', response.qualityControlForm);
        setImg('#outgoing-checklist-image', response.outgoingChecklist);

        setImg('#unit-image', response.unitImage);
        setImg('#unit-back', response.unitBack);
        setImg('#unit-left', response.unitLeft);
        setImg('#unit-right', response.unitRight);
        setImg('#unit-interior', response.unitInterior);

        break;
      }

      case 'get comaker details': {
        const comaker_id = $('#comaker_id').val();

        const response = await runTo('controller/customer-controller.php', {
          comaker_id,
          transaction,
        });

        if (!response?.success) {
          return fail('Get Co-Maker Details Error', response, 'Unable to load co-maker details.');
        }

        $('#summary-comaker-name').text(response.comakerName);
        $('#summary-comaker-address').text(response.comakerAddress);
        $('#summary-comaker-mobile').text(response.comakerMobile);

        break;
      }

      case 'get additional maker details': {
        const comaker_id = $('#additional_maker_id').val();

        const response = await runTo('controller/customer-controller.php', {
          comaker_id,
          transaction,
        });

        if (!response?.success) {
          return fail('Get Co-Maker Details Error', response, 'Unable to load additional maker details.');
        }

        $('#summary-additional-maker-name').text(response.comakerName);
        $('#summary-additional-maker-address').text(response.comakerAddress);
        $('#summary-additional-maker-mobile').text(response.comakerMobile);

        break;
      }

      case 'get comaker2 details': {
        const comaker_id = $('#comaker_id2').val();

        const response = await runTo('controller/customer-controller.php', {
          comaker_id,
          transaction,
        });

        if (!response?.success) {
          return fail('Get Co-Maker Details Error', response, 'Unable to load additional co-maker details.');
        }

        $('#summary-additional-comaker-name').text(response.comakerName);
        $('#summary-additional-comaker-address').text(response.comakerAddress);
        $('#summary-additional-comaker-mobile').text(response.comakerMobile);

        break;
      }

      case 'get product details': {
        const product_id = $('#product_id_details').length
          ? $('#product_id_details').text()
          : $('#product_id').val();

        if (!product_id || product_id === '0') break;

        try {
          const response = await runTo('controller/product-controller.php', {
            product_id,
            transaction,
          });

          if (!response?.success) {
            return fail('Get Product Details Error', response, 'Unable to load product details.');
          }

          $('#delivery_price').val(response.productPrice);
          $('#old_color').val(response.colorName);
          $('#old_body').val(response.bodyTypeName);
          $('#old_engine').val(response.engineNumber);

          $('#product_engine_number').text(response.engineNumber);
          $('#product_chassis_number').text(response.chassisNumber);
          $('#product_plate_number').text(response.plateNumber);
          $('#product_category').val(response.productCategoryID);

          $('#summary-stock-no').text(response.summaryStockNumber);
          $('#summary-engine-no').text(response.engineNumber);
          $('#summary-chassis-no').text(response.chassisNumber);
          $('#summary-plate-no').text(response.plateNumber);

          $('#insurance_unit_no').text(response.summaryStockNumber);
          $('#insurance_engine_no').text(response.engineNumber);
          $('#insurance_chassis_no').text(response.chassisNumber);
          $('#insurance_plate_no').text(response.plateNumber);
          $('#insurance_color').text(response.colorName);

          if ($('#product_cost_label').length) {
            $('#product_cost_label').text(parseFloat(response.productCost).toLocaleString('en-US'));
          }

          if ($('#product_best_price_label').length) {
            $('#product_best_price_label').text(parseFloat(response.bestPrice).toLocaleString('en-US'));
          }
        } finally {
          // original $.ajax complete:
          calculateTotalDeliveryPrice();
          calculateTotalOtherCharges();
        }

        break;
      }

      case 'get sales proposal other product details': {
        const response = await runTo(SALES_CONTROLLER, { sales_proposal_id, transaction });

        if (!response?.success) {
          return fail('Get Other Product Details Error', response, 'Unable to load other product details.');
        }

        $('#year_model').val(response.yearModel);
        $('#insurance_year_model').text(response.yearModel);

        $('#cr_no').val(response.crNo);
        $('#cr_no_label').text(response.crNo);

        $('#mv_file_no').val(response.mvFileNo);
        $('#insurance_mv_file_no').text(response.mvFileNo);

        $('#make').val(response.make);
        $('#insurance_make').text(response.make);

        $('#product_description').val(response.productDescription);
        $('#other-details-gatepass1').text(response.productDescription);
        $('#other-details-gatepass2').text(response.productDescription);

        break;
      }

      case 'get sales proposal insurance request details': {
        const response = await runTo(SALES_CONTROLLER, { sales_proposal_id, transaction });

        if (!response?.success) {
          return fail('Get Insurance Request Details Error', response, 'Unable to load insurance request details.');
        }

        $('#od_theft').text(response.odTheft);
        $('#od_rate').text(response.odRate);
        $('#od_theft_premium').text(response.odTheftPremium);
        $('#total_premium').text(response.odTheftPremium);
        $('#vat_premium').text(response.vatPremium);
        $('#doc_stamps').text(response.docStamps);
        $('#local_govt_tax').text(response.localGovtTax);
        $('#gross').text(response.gross);
        $('#1st_year_coverage').text(response.odTheft);

        break;
      }

      default:
        break;
    }
  } catch (err) {
    handleNetworkError(err);
  }
}


/* ============================================================
   Existing helpers (kept, but fixed where needed)
   ============================================================ */

function checkIntegerDivision(dividend, divisor) {
  const result = dividend / divisor;
  return Number.isInteger(result) ? result : 0;
}

function calculateFirstDueDate() {
  const start_date = $('#start_date').val();
  const payment_frequency = $('#payment_frequency').val();
  const term_length = $('#term_length').val();
  const number_of_payments = $('#number_of_payments').val();

  // If you keep this PHP endpoint, at least fix string interpolation and keep it simple
  $.ajax({
    type: 'POST',
    url: './config/calculate_first_due_date.php',
    data: { start_date, term_length, payment_frequency, number_of_payments },
    success: (result) => $('#first_due_date').val(result),
  });
}

function calculatePaymentNumber() {
  const payment_frequency = $('#payment_frequency').val();
  const term_length = Number($('#term_length').val() || 0);

  let number_of_payments = 0;

  switch (payment_frequency) {
    case 'Lumpsum':
      number_of_payments = 1;
      break;
    case 'Monthly':
      number_of_payments = term_length;
      break;
    case 'Quarterly':
      number_of_payments = checkIntegerDivision(term_length, 3);
      break;
    case 'Semi-Annual':
      number_of_payments = checkIntegerDivision(term_length, 6);
      break;
    default:
      number_of_payments = 0;
  }

  $('#number_of_payments').val(number_of_payments);
  calculateFirstDueDate();
}

function calculateFuelTotal() {
  const diesel_fuel_quantity = parseCurrency($('#diesel_fuel_quantity').val());
  const diesel_price_per_liter = parseCurrency($('#diesel_price_per_liter').val());
  const regular_fuel_quantity = parseCurrency($('#regular_fuel_quantity').val());
  const regular_price_per_liter = parseCurrency($('#regular_price_per_liter').val());
  const premium_fuel_quantity = parseCurrency($('#premium_fuel_quantity').val());
  const premium_price_per_liter = parseCurrency($('#premium_price_per_liter').val());

  const total_diesel = diesel_fuel_quantity * diesel_price_per_liter;
  const total_regular = regular_fuel_quantity * regular_price_per_liter;
  const total_premium = premium_fuel_quantity * premium_price_per_liter;
  const total_delivery_price = total_diesel + total_regular + total_premium;

  $('#diesel_total').text(parseCurrency(total_diesel.toFixed(2)).toLocaleString('en-US'));
  $('#regular_total').text(parseCurrency(total_regular.toFixed(2)).toLocaleString('en-US'));
  $('#premium_total').text(parseCurrency(total_premium.toFixed(2)).toLocaleString('en-US'));
  $('#fuel_total').text(parseCurrency(total_delivery_price.toFixed(2)).toLocaleString('en-US'));
  $('#delivery_price').val(parseCurrency(total_delivery_price.toFixed(2)).toLocaleString('en-US'));

  calculateTotalDeliveryPrice();
}

function calculateTotalDeliveryPrice() {
  const delivery_price = parseCurrency($('#delivery_price').val());
  const add_on_charge = parseCurrency($('#add_on_charge').val());
  const nominal_discount = parseCurrency($('#nominal_discount').val());

  let total = (delivery_price + add_on_charge) - nominal_discount;
  if (total <= 0) total = 0;

  const formatted = parseFloat(total.toFixed(2)).toLocaleString('en-US');

  $('#total_delivery_price').val(formatted);
  $('#summary-deliver-price').text(formatted);
  $('#total_delivery_price_label').val(total);

  calculatePricingComputation();
}

function calculatePricingComputation() {
  let term_length = parseCurrency($('#term_length').val());
  const interest_rate = parseCurrency($('#interest_rate').val());
  const delivery_price = parseCurrency($('#total_delivery_price').val());
  const cost_of_accessories = parseCurrency($('#cost_of_accessories').val());
  const reconditioning_cost = parseCurrency($('#reconditioning_cost').val());
  const downpayment = parseCurrency($('#downpayment').val());
  const number_of_payments = parseCurrency($('#number_of_payments').val());

  const payment_frequency = $('#payment_frequency').val();

  if (payment_frequency === 'Lumpsum') term_length = 1;
  if (payment_frequency === 'Semi-Annual' || payment_frequency === 'Quarterly') term_length = number_of_payments;

  const subtotal = delivery_price + cost_of_accessories + reconditioning_cost;
  const outstanding_balance = subtotal - downpayment;
  const pn_amount = outstanding_balance * (1 + (interest_rate / 100));
  const repayment_amount = Math.ceil(pn_amount / (term_length || 1));
  const downpayment_percent = delivery_price ? (downpayment / delivery_price) * 100 : 0;

  $('#subtotal').val(parseCurrency(subtotal.toFixed(2)).toLocaleString('en-US'));
  $('#outstanding_balance').val(parseCurrency(outstanding_balance.toFixed(2)).toLocaleString('en-US'));

  $('#amount_financed').val(parseCurrency(outstanding_balance.toFixed(2)).toLocaleString('en-US'));
  $('#pn_amount').val(parseCurrency(pn_amount.toFixed(2)).toLocaleString('en-US'));
  $('#repayment_amount').val(parseCurrency(repayment_amount.toFixed(2)).toLocaleString('en-US'));

  $('#summary-cost-of-accessories').text(parseFloat(cost_of_accessories.toFixed(2)).toLocaleString('en-US'));
  $('#summary-reconditioning-cost').text(parseFloat(reconditioning_cost.toFixed(2)).toLocaleString('en-US'));
  $('#summary-downpayment').text(parseFloat(downpayment.toFixed(2)).toLocaleString('en-US'));
  $('#summary-repayment-amount').text(parseFloat(repayment_amount.toFixed(2)).toLocaleString('en-US'));
  $('#summary-interest-rate').text(`${parseFloat(interest_rate.toFixed(2)).toLocaleString('en-US')}%`);
  $('#summary-outstanding-balance').text(parseFloat(outstanding_balance.toFixed(2)).toLocaleString('en-US'));
  $('#summary-sub-total').text(parseFloat(subtotal.toFixed(2)).toLocaleString('en-US'));
  $('#downpayment-percent').text(parseFloat(downpayment_percent.toFixed(2)).toLocaleString('en-US'));
}

function calculateRenewalAmount() {
  const productType = $('#product_type').val();
  const productCategory = $('#product_category').val();

  const fmt = (n) => (parseCurrency(Number(n).toFixed(2)) || 0).toLocaleString('en-US');
  const money = (selector) => parseCurrency($(selector).val()) || 0;

  const setYear = (year, { coverage = 0, premium = 0, readonly = true, updateSummary = true } = {}) => {
    $(`#insurance_coverage_${year}_year`).val(coverage ? fmt(coverage) : 0);
    $(`#insurance_premium_${year}_year`).val(premium ? fmt(premium) : 0);

    if (updateSummary) {
      $(`#summary-insurance-coverage-${year}-year`).text(coverage ? fmt(coverage) : 0);
      $(`#summary-insurance-premium-${year}-year`).text(premium ? fmt(premium) : 0);
    }

    $(`#insurance_coverage_${year}_year`).attr('readonly', !!readonly);
    $(`#insurance_premium_${year}_year`).attr('readonly', !!readonly);
  };

  const resetAllYears = () => {
    setYear('second', { coverage: 0, premium: 0, readonly: true });
    setYear('third', { coverage: 0, premium: 0, readonly: true });
    setYear('fourth', { coverage: 0, premium: 0, readonly: true });
  };

  const computeChecked = (selector) => $(selector).is(':checked');

  const createdDateMillis = () => {
    let s = $('#created-date-summary').val();

    if (!s) {
      const now = new Date();
      const mm = String(now.getMonth() + 1).padStart(2, '0');
      const dd = String(now.getDate()).padStart(2, '0');
      const yyyy = now.getFullYear();
      s = `${mm}/${dd}/${yyyy}`;
    }

    return new Date(s).getTime();
  };

  const cutoffMillis = new Date('10/22/2025').getTime();
  const multiplier = createdDateMillis() < cutoffMillis ? 0.025 : 0.03;

  // --- Mode: Refinancing (auto compute, all readonly) ---
  if (productType === 'Refinancing') {
    const deliveryPrice = money('#insurance_coverage');

    if (deliveryPrice <= 0) {
      resetAllYears();
      return;
    }

    const secondCoverage = deliveryPrice * 0.9;
    const thirdCoverage = secondCoverage * 0.9;
    const fourthCoverage = thirdCoverage * 0.9;

    const premiumRefi = (coverage) => Math.ceil((((coverage * 0.025) + 2700) * 1.2526) + 1300);

    if (computeChecked('#compute_second_year')) {
      setYear('second', { coverage: secondCoverage, premium: premiumRefi(secondCoverage), readonly: true });
    } else {
      setYear('second', { coverage: 0, premium: 0, readonly: true });
    }

    if (computeChecked('#compute_third_year')) {
      setYear('third', { coverage: thirdCoverage, premium: premiumRefi(thirdCoverage), readonly: true });
    } else {
      setYear('third', { coverage: 0, premium: 0, readonly: true });
    }

    if (computeChecked('#compute_fourth_year')) {
      setYear('fourth', { coverage: fourthCoverage, premium: premiumRefi(fourthCoverage), readonly: true });
    } else {
      setYear('fourth', { coverage: 0, premium: 0, readonly: true });
    }

    return;
  }

  // --- Mode: Restructure (manual input toggled by checkbox) ---
  if (productType === 'Restructure') {
    const toggleManual = (year, checked) => {
      $(`#insurance_premium_${year}_year`).attr('readonly', !checked);
      $(`#insurance_coverage_${year}_year`).attr('readonly', !checked);
    };

    toggleManual('second', computeChecked('#compute_second_year'));
    toggleManual('third', computeChecked('#compute_third_year'));
    toggleManual('fourth', computeChecked('#compute_fourth_year'));
    return;
  }

  // --- Mode: Default (auto coverage, premium depends on category, may become editable) ---
  const deliveryPrice = money('#insurance_coverage');

  if (deliveryPrice <= 0) {
    resetAllYears();
    return;
  }

  const secondCoverage = deliveryPrice * 0.8;
  const thirdCoverage = secondCoverage * 0.9;
  const fourthCoverage = thirdCoverage * 0.9;

  const premiumDefault = (coverage) => {
    if (productCategory === '1' || productCategory === '3') {
      return Math.ceil((((coverage * multiplier) + 2700) * 1.2526) + 1300);
    }

    if (productCategory === '2') {
      return Math.ceil((coverage * 0.025) * 1.2526);
    }

    return null; // signals manual input
  };

  const applyDefaultYear = (year, coverage, computeSelector) => {
    if (!computeChecked(computeSelector)) {
      setYear(year, { coverage: 0, premium: 0, readonly: true });
      return;
    }

    const premium = premiumDefault(coverage);

    if (premium == null) {
      // coverage/premium become editable, premium starts at 0
      setYear(year, { coverage, premium: 0, readonly: false });
      return;
    }

    setYear(year, { coverage, premium, readonly: true });
  };

  applyDefaultYear('second', secondCoverage, '#compute_second_year');
  applyDefaultYear('third', thirdCoverage, '#compute_third_year');
  applyDefaultYear('fourth', fourthCoverage, '#compute_fourth_year');
}

function calculateTotalOtherCharges() {
  const productType = $('#product_type').val();
  const productCategory = $('#product_category').val();

  const fmt = (n) => (parseCurrency(Number(n).toFixed(2)) || 0).toLocaleString('en-US');
  const money = (selector) => parseCurrency($(selector).val()) || 0;

  const setAllZero = () => {
    const zeros = ['#insurance_premium', '#handling_fee', '#transfer_fee', '#transaction_fee', '#doc_stamp_tax'];
    zeros.forEach((s) => $(s).val('0.00'));

    const zeros2 = [
      '#insurance_premium_subtotal',
      '#handling_fee_subtotal',
      '#doc_stamp_tax_subtotal',
      '#transaction_fee_subtotal',
      '#transfer_fee_subtotal',
    ];
    zeros2.forEach((s) => $(s).val('0.00'));

    $('#summary-insurance-coverage').text('0.00');
    $('#summary-insurance-premium').text('0.00');
    $('#summary-handing-fee').text('0.00');
    $('#summary-transfer-fee').text('0.00');
    $('#summary-registration-fee').text('0.00');
    $('#summary-doc-stamp-tax').text('0.00');
    $('#summary-transaction-fee').text('0.00');
    $('#summary-other-charges-total').text('0.00');
  };

  if (['Fuel', 'Parts', 'Repair', 'Rental'].includes(productType)) {
    setAllZero();
    return;
  }

  const createdDateMillis = () => {
    let s = $('#created-date-summary').val();

    if (!s) {
      const now = new Date();
      const mm = String(now.getMonth() + 1).padStart(2, '0');
      const dd = String(now.getDate()).padStart(2, '0');
      const yyyy = now.getFullYear();
      s = `${mm}/${dd}/${yyyy}`;
    }

    return new Date(s).getTime();
  };

  const cutoffMillis = new Date('10/22/2025').getTime();
  const multiplier = createdDateMillis() < cutoffMillis ? 0.025 : 0.03;

  const amountFinanced = money('#amount_financed');
  const pnAmount = money('#pn_amount');

  const insuranceCoverage = money('#insurance_coverage');

  let insurancePremium = 0;

  if (productType === 'Refinancing' || productType === 'Restructure') {
    insurancePremium = money('#insurance_premium');
  } else if (productCategory === '1' || productCategory === '3' || productType === 'Brand New') {
    insurancePremium = Math.ceil((((insuranceCoverage * multiplier) + 2700) * 1.2526) + 1300);
  } else if (productCategory === '2') {
    insurancePremium = Math.ceil((insuranceCoverage * 0.025) * 1.2526);
  }

  const handlingFee = (amountFinanced * 0.035) + 6000;
  const transactionFee = (amountFinanced * 0.01) + 7000;
  const docStampTax = Math.ceil(((((amountFinanced - 5000) / 5000) * 20) + 40) + ((pnAmount / 200) * 1.5));

  $('#insurance_premium').val(fmt(insurancePremium));
  $('#handling_fee').val(fmt(handlingFee));
  $('#transaction_fee').val(fmt(transactionFee));
  $('#doc_stamp_tax').val(fmt(docStampTax));

  const transferFee = money('#transfer_fee');
  const insurancePremiumDiscount = money('#insurance_premium_discount');
  const handlingFeeDiscount = money('#handling_fee_discount');
  const registrationFee = money('#registration_fee');
  const docStampTaxDiscount = money('#doc_stamp_tax_discount');
  const transactionFeeDiscount = money('#transaction_fee_discount');
  const transferFeeDiscount = money('#transfer_fee_discount');

  const insurancePremiumSubtotal = insurancePremium - insurancePremiumDiscount;
  const handlingFeeSubtotal = handlingFee - handlingFeeDiscount;
  const docStampTaxSubtotal = docStampTax - docStampTaxDiscount;
  const transactionFeeSubtotal = transactionFee - transactionFeeDiscount;
  const transferFeeSubtotal = transferFee - transferFeeDiscount;

  $('#insurance_premium_subtotal').val(fmt(insurancePremiumSubtotal));
  $('#handling_fee_subtotal').val(Number(handlingFeeSubtotal).toFixed(2));
  $('#doc_stamp_tax_subtotal').val(Number(docStampTaxSubtotal).toFixed(2));
  $('#transaction_fee_subtotal').val(fmt(transactionFeeSubtotal));
  $('#transfer_fee_subtotal').val(Number(transferFeeSubtotal).toFixed(2));

  const total =
    insurancePremiumSubtotal +
    handlingFeeSubtotal +
    transferFeeSubtotal +
    registrationFee +
    docStampTaxSubtotal +
    transactionFeeSubtotal;

  $('#total_other_charges').val(fmt(total));

  $('#summary-insurance-coverage').text(fmt(insuranceCoverage));
  $('#summary-insurance-premium').text(fmt(insurancePremiumSubtotal));
  $('#summary-handing-fee').text(fmt(handlingFeeSubtotal));
  $('#summary-transfer-fee').text(fmt(transferFeeSubtotal));
  $('#summary-registration-fee').text(fmt(registrationFee));
  $('#summary-doc-stamp-tax').text(fmt(docStampTaxSubtotal));
  $('#summary-transaction-fee').text(fmt(transactionFeeSubtotal));
  $('#summary-other-charges-total').text(fmt(total));
}


/* ============================================================
   traverseTabs (keep yours, but fix a logic bug)
   - You had: const hasEnabledInputs = $form.find(':input:disabled').length > 0;
     That variable name & logic were inverted.
   ============================================================ */
async function traverseTabs(direction) {
  const $tabs = $('#v-pills-tab .nav-link'); // ✅ scoped
  const $active = $tabs.filter('.active');

  if (!$tabs.length || !$active.length) return;

  const currentStep = $active.data('step');

  const stepConfig = {
    proposal: { form: '#sales-proposal-form' },
    unit: { form: '#sales-proposal-unit-details-form' },
    fuel: { form: '#sales-proposal-fuel-details-form' },
    refinancing: { form: '#sales-proposal-refinancing-details-form', },
    pricing: { form: '#sales-proposal-pricing-computation-form' },
    otherCharges: { form: '#sales-proposal-other-charges-form' },
    renewal: { form: '#sales-proposal-renewal-amount-form' },
    otherProduct: { form: '#sales-proposal-other-product-details-form' },

    // ✅ YOUR MISSING ONES (revised)
    jobOrder: {
      onEnter: () => $('#add-sales-proposal-job-order-button').removeClass('d-none'),
      onLeave: () => $('#add-sales-proposal-job-order-button').addClass('d-none'),
    },
    approvalCondition: {
      onEnter: () => $('#add-sales-proposal-condition-button').removeClass('d-none'),
      onLeave: () => $('#add-sales-proposal-condition-button').addClass('d-none'),
    },
    additionalJobOrder: {
      onEnter: () => $('#add-sales-proposal-additional-job-order-button').removeClass('d-none'),
      onLeave: () => $('#add-sales-proposal-additional-job-order-button').addClass('d-none'),
    },
    deposit: {
      onEnter: () => $('#add-sales-proposal-deposit-amount-button').removeClass('d-none'),
      onLeave: () => $('#add-sales-proposal-deposit-amount-button').addClass('d-none'),
    },
    gatepass: {
      onEnter: () => $('#gatepass-print-button').removeClass('d-none'),
      onLeave: () => $('#gatepass-print-button').addClass('d-none'),
    },
    ciReport: {
      onEnter: () => $('#complete-ci-button').removeClass('d-none'),
      onLeave: () => $('#complete-ci-button').addClass('d-none'),
    },
    online: {
      onEnter: () => $('#online-print-button').removeClass('d-none'),
      onLeave: () => $('#online-print-button').addClass('d-none'),
    },
    authorization: {
      onEnter: () => $('#authorization-print-button').removeClass('d-none'),
      onLeave: () => $('#authorization-print-button').addClass('d-none'),
    },
    promissory: {
      onEnter: () => $('#pn-print-button').removeClass('d-none'),
      onLeave: () => $('#pn-print-button').addClass('d-none'),
    },
    disclosure: {
      onEnter: () => $('#disclosure-print-button').removeClass('d-none'),
      onLeave: () => $('#disclosure-print-button').addClass('d-none'),
    },
    insurance: {
      onEnter: () => $('#insurance-request-print-button').removeClass('d-none'),
      onLeave: () => $('#insurance-request-print-button').addClass('d-none'),
    },

    // ✅ Summary step stays as your “action hub”
    summary: {
      onEnter: () => {
        [
          '#tag-for-initial-approval-button',
          '#tag-for-review-button',
          '#sales-proposal-initial-approval-button',
          '#sales-proposal-final-approval-button',
          '#sales-proposal-reject-button',
          '#sales-proposal-cancel-button',
          '#for-ci-sales-proposal-button',
          '#sales-proposal-set-to-draft-button',
          '#for-dr-sales-proposal-button',
          '#approve-installment-sales-button',
          '#print-button',
        ].forEach((sel) => $(sel).removeClass('d-none'));
      },
      onLeave: () => {
        [
          '#tag-for-initial-approval-button',
          '#tag-for-review-button',
          '#sales-proposal-initial-approval-button',
          '#sales-proposal-final-approval-button',
          '#sales-proposal-reject-button',
          '#sales-proposal-cancel-button',
          '#for-ci-sales-proposal-button',
          '#sales-proposal-set-to-draft-button',
          '#for-dr-sales-proposal-button',
          '#approve-installment-sales-button',
          '#summary-print-button',
          '#print-button',
        ].forEach((sel) => $(sel).addClass('d-none'));
      },
    },
  };

  const $visibleTabs = $tabs.not('.d-none');
  if (!$visibleTabs.length) return;

  const currentVisibleIndex = $visibleTabs.index($active);

  const getNextVisibleIndex = (dir) => {
    if (dir === 'first') return 0;
    if (dir === 'last') return $visibleTabs.length - 1;
    if (dir === 'previous') return Math.max(0, currentVisibleIndex - 1);
    // next
    return Math.min($visibleTabs.length - 1, currentVisibleIndex + 1);
  };

  const nextVisibleIndex = getNextVisibleIndex(direction);
  const $nextTab = $visibleTabs.eq(nextVisibleIndex);
  const nextStep = $nextTab.data('step');

  // ---------- Validate + Submit on forward movement ----------
  const isForward = direction === 'next' || direction === 'last';

  const focusFirstInvalid = ($form) => {
    const $first = $form.find('.is-invalid:visible:first');
    if ($first.length) {
      $first.trigger('focus');
      const top = $first.offset()?.top;
      if (top) window.scrollTo({ top: top - 120, behavior: 'smooth' });
    }
  };

  const validateAndSubmit = async (formSelector) => {
    const $form = $(formSelector);
    if (!$form.length) return true;

    const hasEnabledUserInputs =
      $form.find(':input:not(:disabled):not([readonly])').filter(':visible').length > 0;

    if (!hasEnabledUserInputs) return true;

    // Ensure validator exists
    const validator = $form.data('validator');
    if (validator && typeof $form.valid === 'function') {
      if (!$form.valid()) {
        focusFirstInvalid($form);
        return false;
      }
    }

    // Call the stored submit callback directly (no native submit)
    const submitFn = $form.data('wizardSubmit');
    if (typeof submitFn === 'function') {
      await submitFn($form[0]);
    }

    return true;
  };

  if (isForward && stepConfig[currentStep]?.form) {
    const ok = await validateAndSubmit(stepConfig[currentStep].form);
    if (!ok) return;
  }

  // ---------- Leave current step ----------
  stepConfig[currentStep]?.onLeave?.();

  // ---------- Switch tab (Bootstrap 5 safe) ----------
  if (window.bootstrap?.Tab) {
    bootstrap.Tab.getOrCreateInstance($nextTab[0]).show();
  } else if (typeof $nextTab.tab === 'function') {
    $nextTab.tab('show');
  } else {
    // fallback
    $tabs.removeClass('active');
    $nextTab.addClass('active');
  }

  // ---------- Enter next step ----------
  stepConfig[nextStep]?.onEnter?.();

  // ---------- Progress ----------
  const progress = ((nextVisibleIndex + 1) / ($visibleTabs.length || 1)) * 100;
  $('#bar .progress-bar').css('width', `${progress}%`);

  // ---------- Button states ----------
  const isFirst = nextVisibleIndex === 0;
  const isLast = nextVisibleIndex === $visibleTabs.length - 1;

  $('#first-step, #previous-step').toggleClass('disabled', isFirst);
  $('#last-step, #next-step').toggleClass('disabled', isLast);
}



function disableFormAndSelect2(formId) {
  const form = document.getElementById(formId);
  if (!form) return;

  const elements = form.elements;

  for (let i = 0; i < elements.length; i++) {
    if (elements[i].hasAttribute('readonly')) continue;
    elements[i].disabled = true;
  }

  const select2Dropdowns = form.getElementsByClassName('select2');
  for (let j = 0; j < select2Dropdowns.length; j++) {
    const $select = $(select2Dropdowns[j]);
    $select.select2('destroy');
    $select.prop('disabled', true);
  }
}

