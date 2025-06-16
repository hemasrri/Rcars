<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Application Form</title>
     <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 font-sans">

@include('layouts.user_header')

@php
    $action = old('action') ?? '';
    $isSubmit = ($action === 'submit');
@endphp

<div class="max-w-2xl mx-auto mt-4">

    <!-- Application Conditions Box -->
    <div class="bg-yellow-100 dark:bg-yellow-900 border-l-4 border-yellow-500 dark:border-yellow-400 p-4 mb-8 rounded-lg">
        <h3 class="font-bold text-lg">APPLICATION CONDITIONS</h3>
        <ol class="list-decimal pl-5">
           <li>The applicant is responsible for the number of days requested after approval is granted. No refund will be effected if the applicant stays less than the number of days from the date of application, does not stay at all, makes payment without approval, or in other matters involving the return of rental fees. Applicants may check vacancies with the approving officer at the applied Residential College.</li>
                <li>The applicant is responsible for the information provided in this form. Failure to fill in the information correctly allows the University to take appropriate action based on the regulations in force.</li>
                <li>Applicants are required to fill in the correct Residential College support email as stated. If there is no response from the Approving Officer (Residential College applied for) within 2 days after the application is submitted, please contact the number listed to confirm the rental status.</li>
                <li>The application must be submitted within <strong>14 days</strong> from the intended date of stay.</li>
                <li>The response period given to the applicant is within <strong>seven (7) working days</strong>from the intended date of stay.</li>
                <li>Applicants are subject to the <strong>Residential College Handbook</strong> and <strong>UTHM Student Housing Policy</strong>.</li>
            </ol>
    </div>

    <!-- Progress Bar -->
    <div class="w-full bg-gray-300 dark:bg-gray-700 rounded-full mb-6">
        <div class="bg-green-500 h-2 rounded-full" id="formProgressBar" style="width: 25%;"></div>
    </div>

    <!-- Optional Custom Alert -->
    <div class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 border border-green-400 dark:border-green-600 p-4 rounded mb-4 hidden" id="customAlert">
        Your application has been saved successfully!
    </div>

    <form method="POST" action="{{ route('application.submit') }}" enctype="multipart/form-data" id="applicationForm" novalidate
          class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        @csrf
    <input type="hidden" name="action" id="form-action" value="submit">

        <!-- Reusable Input Classes -->
        @php $inputClass = "w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100"; @endphp

        <label for="name" class="block font-semibold">Name:</label>
        <input type="text" id="name" name="name" value="{{ old('name', $user->user_name) }}" readonly class="{{ $inputClass }}">

        <label for="ic_number" class="block font-semibold">IC Number:</label>
        <input type="text" id="ic_number" name="ic_number" value="{{ old('ic_number', $user->ic_number) }}" readonly class="{{ $inputClass }}">

        <label for="phone" class="block font-semibold">Phone:</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" readonly class="{{ $inputClass }}">

        <label for="email" class="block font-semibold">Email:</label>
        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" readonly class="{{ $inputClass }}">

        <input type="hidden" name="semester" id="semester" value="{{ old('semester') }}">
        <input type="hidden" name="session" id="session" value="{{ old('session') }}">

        <label for="rental_purpose" class="block font-semibold">Rental Purpose:</label>
        <input type="text" id="rental_purpose" name="rental_purpose" value="{{ old('rental_purpose') }}" required class="{{ $inputClass }}">

        <label for="check_in_date" class="block font-semibold">Check-in Date:</label>
        <input type="date" id="check_in_date" name="check_in_date" value="{{ old('check_in_date') }}" @if($isSubmit) required min="{{ date('Y-m-d') }}" @endif class="{{ $inputClass }}">

        <label for="check_out_date" class="block font-semibold">Check-out Date:</label>
        <input type="date" id="check_out_date" name="check_out_date" value="{{ old('check_out_date') }}" @if($isSubmit) required min="{{ date('Y-m-d') }}" @endif class="{{ $inputClass }}">

        <label for="category" class="block font-semibold">Category:</label>
        <select id="category" name="category" required class="{{ $inputClass }}">
            <option value="">Select category</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>

        <label for="package_id" class="block font-semibold">Package:</label>
        <select id="package_id" name="package" required class="{{ $inputClass }}">
            <option value="">Select a package</option>
        </select>
        <div id="packageError" class="text-red-500 dark:text-red-400 text-sm mt-1 hidden">Please select a package.</div>

        <label for="num_participants" class="block font-semibold">Number of Participants:</label>
        <input type="number" id="num_participants" name="num_participants" value="{{ old('num_participants', 0) }}" readonly min="0" class="{{ $inputClass }}">

        <label for="male" class="block font-semibold">Male:</label>
        <input type="number" id="male" name="male" value="{{ old('male', 0) }}" min="0" required class="{{ $inputClass }}">

        <label for="female" class="block font-semibold">Female:</label>
        <input type="number" id="female" name="female" value="{{ old('female', 0) }}" min="0" required class="{{ $inputClass }}">

        <fieldset class="mb-4">
            <legend class="font-semibold">Any Disabled Participants?</legend>
            <label class="inline-flex items-center mr-4">
                <input type="radio" name="disabled_status" value="yes" {{ old('disabled_status') == 'yes' ? 'checked' : '' }} class="form-radio text-blue-600">
                <span class="ml-2">Yes</span>
            </label>
            <label class="inline-flex items-center">
                <input type="radio" name="disabled_status" value="no" {{ old('disabled_status', 'no') == 'no' ? 'checked' : '' }} class="form-radio text-blue-600">
                <span class="ml-2">No</span>
            </label>
        </fieldset>

        <div id="disabled_counts" style="display:none;">
            <label for="disabled_male" class="block font-semibold">Disabled Male:</label>
            <input type="number" id="disabled_male" name="disabled_male" value="{{ old('disabled_male', 0) }}" min="0" class="{{ $inputClass }}">

            <label for="disabled_female" class="block font-semibold">Disabled Female:</label>
            <input type="number" id="disabled_female" name="disabled_female" value="{{ old('disabled_female', 0) }}" min="0" class="{{ $inputClass }}">
        </div>
 <!-- Upload Document -->
    <label for="document" class="block font-semibold">Upload Document (optional):</label>
    <input type="file" id="document" name="document" accept=".pdf,.doc,.docx,.jpg,.png" class="{{ $inputClass }}">


        <!-- Submit + Draft Buttons -->
    <div class="flex flex-col sm:flex-row justify-between mt-6 gap-3">
        <button type="button" {{-- NOTE: now "type=button" --}}
                onclick="handleSubmit('submit')"
                class="w-full sm:w-auto bg-green-600 text-white py-2 px-6 rounded-lg hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-400 transition-all">
            <i class="fas fa-paper-plane mr-2"></i>Submit Application
        </button>

        <button type="button"
                onclick="handleSubmit('draft')"
                class="w-full sm:w-auto bg-yellow-400 text-black py-2 px-6 rounded-lg hover:bg-yellow-500 dark:bg-yellow-600 dark:hover:bg-yellow-500 transition-all">
            <i class="fas fa-save mr-2"></i>Save as Draft
        </button>
    </div>
</form>


    @include('layouts.user_footer')
<script>
    function handleSubmit(actionValue) {
        const form = document.getElementById('applicationForm');
        const actionInput = document.getElementById('form-action');

        // Set action value
        actionInput.value = actionValue;

        if (actionValue === 'draft') {
            form.setAttribute('novalidate', true); // Disable validation
        } else {
            form.removeAttribute('novalidate'); // Enable validation
        }

        // Submit form manually
        form.submit();
    }
</script>
    <script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });

    function loadPackages(category, selectedPackage = null) {
    const $packageSelect = $('#package_id').prop('disabled', true).html('<option>Loading packages...</option>');
    $('#packageError').hide();

    if (!category) {
        $packageSelect.html('<option value="">Select a package</option>').prop('disabled', true);
        return;
    }

    $.post("{{ route('fetch.packages') }}", { category })
        .done(function (response) {
            $packageSelect.empty().append('<option value="">Select a package</option>');
            if (response.length) {
                response.forEach(pkg => {
                    const selected = selectedPackage == pkg.id ? 'selected' : '';
                    $packageSelect.append(`<option value="${pkg.id}" ${selected}>${pkg.package_name} - ${pkg.details} - RM${pkg.price_per_day}/day</option>`);
                });
                $packageSelect.prop('disabled', false);
            } else {
                $packageSelect.append('<option value="">No packages available</option>');
                $packageSelect.prop('disabled', false);
            }
        })
        .fail(function () {
            $packageSelect.html('<option>Error loading packages</option>').prop('disabled', true);
            alert('Failed to load packages. Please try again later.');
        });
}


    function toggleDisabledCounts() {
        const isDisabled = $('input[name="disabled_status"]:checked').val() === 'yes';
        $('#disabled_counts').toggle(isDisabled);
        $('#disabled_male, #disabled_female').prop('required', isDisabled).val(isDisabled ? '' : 0);
    }

    function updateParticipants() {
        const male = parseInt($('#male').val()) || 0;
        const female = parseInt($('#female').val()) || 0;
        $('#num_participants').val(male + female);

        const disabledMale = parseInt($('#disabled_male').val()) || 0;
        const disabledFemale = parseInt($('#disabled_female').val()) || 0;

        if (disabledMale > male) $('#disabled_male').val(male);
        if (disabledFemale > female) $('#disabled_female').val(female);
    }

    function setupAutoClear(id) {
        const input = $('#' + id);
        input.focus(() => input.val() === '0' && input.val(''));
        input.blur(() => input.val() === '' && input.val(0).trigger('input'));
    }

    function updateSemesterSession(date) {
        if (!date) return $('#semester, #session').val('');

        $.ajax({
            type: "POST",
            url: "{{ route('get.semester_session') }}",
            data: { date },
            async: false,
            success: data => {
                $('#semester').val(data.semester);
                $('#session').val(data.session);
            },
            error: () => {
                $('#semester, #session').val('');
                alert("Semester and session not found for today's date.");
            }
        });
    }

    <!-- inside the existing script tag -->
function validateForm() {
    const action = $('button[type=submit][clicked=true]').val();

    if (action === 'draft') {
        $('#packageError').hide(); // hide any existing error
        return true; // skip validation for drafts
    }

    const rentalPurpose = $('#rental_purpose').val().trim();
    if (!rentalPurpose) {
        alert("Rental Purpose is required.");
        $('#rental_purpose').focus();
        return false;
    }

    const checkIn = $('#check_in_date').val();
    if (!checkIn) {
        alert("Check-in Date is required.");
        $('#check_in_date').focus();
        return false;
    }

    const checkOut = $('#check_out_date').val();
    if (!checkOut) {
        alert("Check-out Date is required.");
        $('#check_out_date').focus();
        return false;
    }

    const category = $('#category').val();
    if (!category) {
        alert("Category is required.");
        $('#category').focus();
        return false;
    }

    const packageId = $('#package_id').val();
    if (!packageId) {
        alert("Package selection is required.");
        $('#package_id').focus();
        $('#packageError').show();
        return false;
    } else {
        $('#packageError').hide();
    }

    const male = parseInt($('#male').val()) || 0;
    const female = parseInt($('#female').val()) || 0;
    const totalParticipants = male + female;

    if (totalParticipants < 1) {
        alert("Total number of participants (male + female) must be at least 1.");
        $('#male').focus();
        return false;
    }

    const disabledStatus = $('input[name="disabled_status"]:checked').val();

    if (disabledStatus === 'yes') {
        const disabledMale = parseInt($('#disabled_male').val()) || 0;
        const disabledFemale = parseInt($('#disabled_female').val()) || 0;
        const totalDisabled = disabledMale + disabledFemale;

        if (totalDisabled < 1) {
            alert("Total number of disabled participants (male + female) must be at least 1.");
            $('#disabled_male').focus();
            return false;
        }

        if (disabledMale > male) {
            alert("Disabled Male count cannot exceed total Male Participants.");
            $('#disabled_male').focus();
            return false;
        }

        if (disabledFemale > female) {
            alert("Disabled Female count cannot exceed total Female Participants.");
            $('#disabled_female').focus();
            return false;
        }
    }

    return true;
}


// Track which button was clicked
$('form button[type=submit]').click(function () {
    $('button[type=submit]', $(this).parents("form")).removeAttr('clicked');
    $(this).attr('clicked', 'true');
});



$('#applicationForm').submit(function (e) {
    if (!validateForm()) {
        e.preventDefault();
        return false;
    }

    updateSemesterSession(new Date().toISOString().split('T')[0]);
    return true;
});


$(function () {
    toggleDisabledCounts();

    const oldCategory = $('#category').val();
    const oldPackage = '{{ old("package") }}';

    if (oldCategory) {
        loadPackages(oldCategory, oldPackage);
    }

    $('#category').change(() => {
        $('#package_id').html('<option value="">Select a package</option>').prop('disabled', true);
        loadPackages($('#category').val());
    });

    $('input[name="disabled_status"]').change(toggleDisabledCounts);
    $('#male, #female').on('input', updateParticipants);
    ['male', 'female', 'disabled_male', 'disabled_female'].forEach(setupAutoClear);

    $('#check_in_date').change(function () {
        updateSemesterSession($(this).val());
    });

    updateParticipants();
});

// Progress Bar Update Logic (basic version)
$('#applicationForm input, #applicationForm select').on('input change', function () {
    let total = $('#applicationForm input[required], #applicationForm select[required]').length;
    let filled = $('#applicationForm input[required], #applicationForm select[required]').filter(function () {
        return $(this).val();
    }).length;

    let percentage = Math.floor((filled / total) * 100);
    $('#formProgressBar').css('width', percentage + '%').text(percentage + '%');
});

// Example: Show the alert after saving (you can trigger this dynamically)
function showCustomAlert(message) {
    $('#customAlert').text(message).fadeIn().delay(3000).fadeOut();
}

// Uncomment below if you want to show alert on form submit
/*
$('#submitBtn, #saveDraftBtn').click(function(e) {
    e.preventDefault(); // remove this line if you want real submission
    showCustomAlert("Form submitted successfully!");
});
*/

// Handle Disabled Counts visibility
$('input[name="disabled_status"]').on('change', function () {
    if ($(this).val() === 'yes') {
        $('#disabled_counts').slideDown();
    } else {
        $('#disabled_counts').slideUp();
    }
});

    
</script>

</body>
</html>
