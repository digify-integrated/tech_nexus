(function($) {
    'use strict';

    $(function() {
        $(document).on('change','#product_type',function() {
            var productType = $(this).val();

            $('#sales-proposal-tab-2, #sales-proposal-tab-3, #sales-proposal-tab-4').addClass('d-none');

            if (productType === 'Unit') {
                $('#sales-proposal-tab-2').removeClass('d-none');
                resetModalForm('sales-proposal-unit-details-form');
            }
            else if (productType === 'Fuel') {
                $('#sales-proposal-tab-3').removeClass('d-none');
                resetModalForm('sales-proposal-fuel-details-form');
            }
            else if (productType === 'Refinancing') {
                $('#sales-proposal-tab-4').removeClass('d-none');
                resetModalForm('sales-proposal-refinancing-details-form');
            }
        });

        $(document).on('click','#next-step',function() {
            traverseTabs('next');
        });

        $(document).on('click','#previous-step',function() {
            traverseTabs('previous');
        });

        $(document).on('click','#first-step',function() {
            traverseTabs('first');
        });

        $(document).on('click','#last-step',function() {
            traverseTabs('last');
        });
    });
})(jQuery);

function traverseTabs(direction) {
    // Get the current active tab
    var activeTab = $('.tab-pane.active');
    
    // Extract the current tab number
    var currentTabNumber = parseInt(activeTab.attr('id').replace('v-', ''));
    
    // Calculate the next tab number based on the direction
    var nextTabNumber;
    if (direction === 'next') {
        nextTabNumber = currentTabNumber + 1;
    } else if (direction === 'previous') {
        nextTabNumber = currentTabNumber - 1;
    } else if (direction === 'first') {
        nextTabNumber = 1;
    } else if (direction === 'last') {
        // Assuming there's a known total number of tabs
        var totalTabs = 11; // Change this to your total number of tabs
        nextTabNumber = totalTabs;
    }
    
    // Construct the ID of the next tab
    var nextTabId = 'v-' + nextTabNumber;
    
    // Activate the next tab if it exists
    if ($('#' + nextTabId).length > 0) {
        $('.nav-link[href="#' + nextTabId + '"]').tab('show');
    }
}