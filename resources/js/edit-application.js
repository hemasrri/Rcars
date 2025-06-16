$(function () {
    // Initialize the form
    toggleDisabledCounts();
    updateParticipants();
    updateProgressBar();

    loadPackages(window.savedCategory, window.savedPackageId); // Use global vars from Blade

    // Event listeners
    $('#category').change(function () {
        loadPackages($(this).val(), null);
    });

    $('input[name="disabled_status"]').change(toggleDisabledCounts);
    $('#male, #female').on('input', function () {
        updateParticipants();
        updateProgressBar();
    });

    $('#rental_purpose, #check_in_date, #check_out_date, #category, #package_id').on('input change', updateProgressBar);
    $('#disabled_male, #disabled_female').on('input', updateProgressBar);

    $('#applicationForm').submit(function (e) {
        if (!validateForm()) {
            e.preventDefault();
        }
    });
});

function loadPackages(category, selected) {
    let $packageSelect = $('#package_id');
    $packageSelect.prop('disabled', true).html('<option>Loading packages...</option>');
    $('#packageError').addClass('hidden');

    if (!category) {
        $packageSelect.html('<option value="">Select a package</option>').prop('disabled', true);
        return;
    }

    $.post(window.fetchPackagesUrl, {
        category: category,
        _token: window.csrfToken
    })
    .done(function (response) {
        $packageSelect.empty().append('<option value="">Select a package</option>');
        response.forEach(function (pkg) {
            let isSelected = selected == pkg.id ? 'selected' : '';
            $packageSelect.append(`
                <option value="${pkg.id}" ${isSelected}>
                    ${pkg.package_name} - RM${pkg.price_per_day}/day
                </option>
            `);
        });
        $packageSelect.prop('disabled', false);
    })
    .fail(function () {
        $packageSelect.html('<option>Error loading packages</option>').prop('disabled', true);
    });
}

function toggleDisabledCounts() {
    let disabled = $('input[name="disabled_status"]:checked').val() === 'yes';
    $('#disabled_counts').toggleClass('hidden', !disabled);
    $('#disabled_male, #disabled_female').prop('required', disabled);
    updateProgressBar();
}

function updateParticipants() {
    let male = parseInt($('#male').val()) || 0;
    let female = parseInt($('#female').val()) || 0;
    $('#num_participants').val(male + female);
}

function updateProgressBar() {
    let requiredFields = ['#rental_purpose', '#check_in_date', '#check_out_date', '#category', '#package_id'];
    let totalReq = requiredFields.length;
    let filled = 0;

    requiredFields.forEach(function (selector) {
        if ($(selector).val()) filled++;
    });

    let male = parseInt($('#male').val()) || 0;
    let female = parseInt($('#female').val()) || 0;
    let totalParticipants = male + female;
    if (totalParticipants > 0) filled++; else totalReq++;

    let disabledRequired = $('input[name="disabled_status"]:checked').val() === 'yes';
    if (disabledRequired) {
        totalReq += 2;
        let disabledMale = parseInt($('#disabled_male').val()) || 0;
        let disabledFemale = parseInt($('#disabled_female').val()) || 0;
        if ((disabledMale + disabledFemale) > 0) filled++;
    }

    let percent = Math.floor((filled / totalReq) * 100);
    $('#formProgressBar').css('width', percent + '%').text(percent + '%');
}

function validateForm() {
    let action = $('#form-action').val();
    if (action === 'draft') return true;

    const requiredFields = ['#rental_purpose', '#check_in_date', '#check_out_date', '#category', '#package_id'];
    for (let i = 0; i < requiredFields.length; i++) {
        if (!$(requiredFields[i]).val()) {
            alert("Please fill out all required fields.");
            $(requiredFields[i]).focus();
            return false;
        }
    }

    let male = parseInt($('#male').val()) || 0;
    let female = parseInt($('#female').val()) || 0;
    if (male + female < 1) {
        alert("At least one participant is required.");
        return false;
    }

    let disabledStatus = $('input[name="disabled_status"]:checked').val();
    if (disabledStatus === 'yes') {
        let disabledMale = parseInt($('#disabled_male').val()) || 0;
        let disabledFemale = parseInt($('#disabled_female').val()) || 0;
        if (disabledMale + disabledFemale < 1) {
            alert("Please enter at least one disabled participant.");
            return false;
        }
        if (disabledMale > male || disabledFemale > female) {
            alert("Disabled count cannot exceed total participants.");
            return false;
        }
    }
    return true;
}

function handleSubmit(action) {
    $('#form-action').val(action);
    if (action === 'draft') {
        $('#applicationForm').attr('novalidate', true);
    } else {
        $('#applicationForm').removeAttr('novalidate');
    }

    if (validateForm()) {
        $('#applicationForm').submit();
    } else {
        alert("Please correct the errors before submitting.");
    }
}
