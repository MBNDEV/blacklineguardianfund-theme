/**
 * Donation Form Interactions - Class-Based
 * Handles suggested amounts interaction with amount field
 * Works with any Gravity Form using the custom CSS classes
 */

(function($) {
  'use strict';

  // Wait for DOM to be ready
  $(document).ready(function() {
    initDonationForm();
  });

  // Gravity Forms specific hooks (if available)
  $(document).on('gform_post_render', function(event, form_id, current_page) {
    // Re-initialize after AJAX form submission or page change
    initDonationForm();
  });

  function initDonationForm() {
    // Look for the form within column-sections-block
    var $form = $('.column-sections-block form');
    
    // Check if donation form exists
    if ($form.length === 0) {
      return;
    }

    // Find fields by class
    var $amountField = $('.donation-amount-field input[type="text"]');
    var $suggestedAmounts = $('.suggested-amounts-field input[type="radio"]');
    var $donationType = $('.donation-type-field input[type="radio"]');
    
    // If fields not found, try alternate selectors
    if ($amountField.length === 0) {
      $amountField = $form.find('input[type="text"]').first();
    }
    
    if ($suggestedAmounts.length === 0) {
      // Look for radio buttons in a field that might contain suggested amounts
      $suggestedAmounts = $form.find('input[type="radio"][name*="suggested"], input[type="radio"][value="25"], input[type="radio"][value="50"]').filter(function() {
        return $(this).val() === '25' || $(this).val() === '50' || $(this).val() === '100' || $(this).val() === '250';
      });
    }
    
    // Suggested amounts interaction
    $suggestedAmounts.off('change.donation').on('change.donation', function() {
      var selectedAmount = $(this).val();
      
      // Populate the amount field with selected value
      if ($amountField.length > 0) {
        $amountField.val(selectedAmount + '.00').trigger('change');
      }
      
      // Add visual feedback
      $(this).closest('.gfield').addClass('amount-selected');
    });

    // Clear suggested amount selection when user types custom amount
    $amountField.off('input.donation').on('input.donation', function() {
      var customValue = $(this).val();
      var suggestedValues = ['25', '50', '100', '250'];
      
      // Remove .00 for comparison
      var cleanValue = customValue.replace('.00', '');
      
      // Only clear if the value doesn't match any suggested amount
      if ($.inArray(cleanValue, suggestedValues) === -1) {
        $suggestedAmounts.prop('checked', false);
      } else {
        // Check the matching suggested amount
        $suggestedAmounts.filter('[value="' + cleanValue + '"]').prop('checked', true);
      }
    });

    // Format amount field on blur (ensure 2 decimal places)
    $amountField.off('blur.donation').on('blur.donation', function() {
      var value = $(this).val();
      
      if (value && !isNaN(value)) {
        var formatted = parseFloat(value).toFixed(2);
        $(this).val(formatted);
      }
    });

    // Pre-select default suggested amount (50) on page load if amount field is empty
    if ($amountField.length && $amountField.val() === '') {
      var $default = $suggestedAmounts.filter('[value="50"]');
      if ($default.length > 0) {
        $default.prop('checked', true).trigger('change');
      }
    }

    // Donation type change handler (for future payment integration)
    $donationType.off('change.donation').on('change.donation', function() {
      var donationType = $(this).val();
      
      // Add custom class to form for styling purposes
      if (donationType === 'monthly') {
        $form.addClass('monthly-donation');
      } else {
        $form.removeClass('monthly-donation');
      }
    });

    // Form validation feedback
    $form.off('submit.donation').on('submit.donation', function(e) {
      // Custom validation for amount field
      var amount = parseFloat($amountField.val());
      var $amountFieldWrapper = $('.donation-amount-field');
      
      if (isNaN(amount) || amount < 1) {
        e.preventDefault();
        $amountFieldWrapper.addClass('gfield_error');
        
        // Add error message if it doesn't exist
        if ($amountFieldWrapper.find('.validation_message').length === 0) {
          $amountFieldWrapper.find('.ginput_container').after('<div class="validation_message">Please enter a valid donation amount (minimum $1.00)</div>');
        }
        
        // Scroll to error
        $('html, body').animate({
          scrollTop: $amountFieldWrapper.offset().top - 100
        }, 500);
        
        return false;
      } else {
        // Clear error if validation passes
        $amountFieldWrapper.removeClass('gfield_error');
        $amountFieldWrapper.find('.validation_message').remove();
      }
    });
  }

})(jQuery);
