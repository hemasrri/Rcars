<!DOCTYPE html>
<html lang="en" class="scroll-smooth dark">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Application</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
         <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 font-sans">
@include('layouts.user_header')
<main class="max-w-2xl mx-auto mt-4 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold text-center mb-8">Edit Application</h1>

    {{-- Session & Validation Errors --}}
    @if (session('error'))
        <div class="mb-4 px-4 py-3 text-sm text-red-700 bg-red-100 border border-red-400 rounded">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-4 px-4 py-3 text-sm text-red-700 bg-red-100 border border-red-400 rounded">
            <strong>There were some problems:</strong>
            <ul class="list-disc list-inside mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('application.update', $application->application_id) }}" enctype="multipart/form-data" id="applicationForm" novalidate>
        @csrf
        @method('PUT')
        <input type="hidden" name="action" id="form-action" value="submit">

        <!-- Application Conditions Box -->
        <div class="bg-yellow-100 dark:bg-yellow-900 border-l-4 border-yellow-500 dark:border-yellow-400 p-4 mb-8 rounded-lg">
            <h3 class="font-bold text-lg">APPLICATION CONDITIONS</h3>
            <ol class="list-decimal pl-5">
                <li>The applicant is responsible for the number of days requested after approval is granted. No refund will be effected if the applicant stays less than the number of days from the date of application, does not stay at all, makes payment without approval, or in other matters involving the return of rental fees. Applicants may check vacancies with the approving officer at the applied Residential College.</li>
                <li>The applicant is responsible for the information provided in this form. Failure to fill in the information correctly allows the University to take appropriate action based on the regulations in force.</li>
                <li>Applicants are required to fill in the correct Residential College support email as stated. If there is no response from the Approving Officer (Residential College applied for) within 2 days after the application is submitted, please contact the number listed to confirm the rental status.</li>
                <li>The application must be submitted within <strong>14 days</strong> from the intended date of stay.</li>
                <li>The response period given to the applicant is within <strong>seven (7) working days</strong> from the intended date of stay.</li>
                <li>Applicants are subject to the <strong>Residential College Handbook</strong> and <strong>UTHM Student Housing Policy</strong>.</li>
            </ol>
        </div>

        <!-- User Info -->
        <label for="name" class="block font-semibold">Name:</label>
        <input type="text" id="name" name="name" value="{{ old('name', $application->name) }}" readonly class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 mb-4">

        <label for="ic_number" class="block font-semibold">IC Number:</label>
        <input type="text" id="ic_number" name="ic_number" value="{{ old('ic_number', $application->ic_number) }}" readonly class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 mb-4">

        <label for="phone" class="block font-semibold">Phone:</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone', $application->phone) }}" readonly class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 mb-4">

        <label for="email" class="block font-semibold">Email:</label>
        <input type="email" id="email" name="email" value="{{ old('email', $application->email) }}" readonly class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 mb-4">

        <label for="rental_purpose" class="block font-semibold">Rental Purpose:</label>
        <input type="text" id="rental_purpose" name="rental_purpose" value="{{ old('rental_purpose', $application->rental_purpose) }}" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 mb-4">

        <label for="check_in_date" class="block font-semibold">Check-in Date:</label>
        <input type="date" id="check_in_date" name="check_in_date" value="{{ old('check_in_date', optional($application->check_in_date)->format('Y-m-d')) }}" required min="{{ date('Y-m-d') }}" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 mb-4">

        <label for="check_out_date" class="block font-semibold">Check-out Date:</label>
        <input type="date" id="check_out_date" name="check_out_date" value="{{ old('check_out_date', optional($application->check_out_date)->format('Y-m-d')) }}" required min="{{ date('Y-m-d') }}" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 mb-4">

        <label for="category" class="block font-semibold">Category:</label>
        <select id="category" name="category" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 mb-4">
            <option value="">Select category</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->category }}" {{ old('category', $application->category) == $cat->category ? 'selected' : '' }}>{{ $cat->category }}</option>
            @endforeach
        </select>

        <label for="package_id" class="block font-semibold">Package:</label>
        <select id="package_id" name="package_id" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 mb-4">
            <option value="">Select a package</option>
        </select>
        <div id="packageError" class="text-red-500 dark:text-red-400 text-sm mt-1 hidden">Please select a package.</div>

        <label for="num_participants" class="block font-semibold">Number of Participants:</label>
        <input type="number" id="num_participants" name="num_participants" value="{{ old('num_participants', $application->num_participants) }}" readonly min="0" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 mb-4">

        <label for="male" class="block font-semibold">Male:</label>
        <input type="number" id="male" name="male" value="{{ old('male', $application->male) }}" min="0" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 mb-4">

        <label for="female" class="block font-semibold">Female:</label>
        <input type="number" id="female" name="female" value="{{ old('female', $application->female) }}" min="0" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 mb-4">

        <fieldset class="mb-4">
            <legend class="font-semibold">Any Disabled Participants?</legend>
            <label class="inline-flex items-center mr-4">
                <input type="radio" name="disabled_status" value="yes" {{ ($application->disabled_male || $application->disabled_female) ? 'checked' : '' }} class="form-radio text-blue-600">
                <span class="ml-2">Yes</span>
            </label>
            <label class="inline-flex items-center">
                <input type="radio" name="disabled_status" value="no" {{ (!$application->disabled_male && !$application->disabled_female) ? 'checked' : '' }} class="form-radio text-blue-600">
                <span class="ml-2">No</span>
            </label>
        </fieldset>

        <div id="disabled_counts" class="{{ ($application->disabled_male || $application->disabled_female) ? '' : 'hidden' }}">
            <label for="disabled_male" class="block font-semibold">Disabled Male:</label>
            <input type="number" id="disabled_male" name="disabled_male" value="{{ old('disabled_male', $application->disabled_male) }}" min="0" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 mb-4">

            <label for="disabled_female" class="block font-semibold">Disabled Female:</label>
            <input type="number" id="disabled_female" name="disabled_female" value="{{ old('disabled_female', $application->disabled_female) }}" min="0" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 mb-4">
        </div>

        <!-- Upload Document -->
        <label for="document" class="block font-semibold">Upload Document (optional):</label>
        <input type="file" id="document" name="document" accept=".pdf,.doc,.docx,.jpg,.png" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 mb-4">

        <!-- Submit + Draft Buttons -->
        <div class="flex flex-col sm:flex-row justify-between mt-6 gap-3">
            <button type="button" onclick="handleSubmit('submit')"
                    class="w-full sm:w-auto bg-green-600 text-white py-2 px-6 rounded-lg hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-400 transition-all">
                <i class="fas fa-paper-plane mr-2"></i>Submit Application
            </button>

            <button type="button" onclick="handleSubmit('draft')"
                    class="w-full sm:w-auto bg-yellow-400 text-black py-2 px-6 rounded-lg hover:bg-yellow-500 dark:bg-yellow-600 dark:hover:bg-yellow-500 transition-all">
                <i class="fas fa-save mr-2"></i>Save as Draft
            </button>
        </div>
    </form>
</main>

<script>
    window.csrfToken = '{{ csrf_token() }}';
    window.fetchPackagesRoute = '{{ route("fetch.packages") }}';
    window.getSemesterSessionRoute = '{{ route("get.semester_session") }}';
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': window.csrfToken }
        });

        const appElement = document.getElementById('edit-app');
        const savedCategory = appElement?.dataset.category;
        const savedPackageId = appElement?.dataset.package;

        // Load packages if a saved category exists
        if (savedCategory) {
            $('#category').val(savedCategory);
            loadPackages(savedCategory, savedPackageId);
        }

        // Event listener for category change
        $('#category').on('change', function () {
            const selectedCategory = $(this).val();
            $('#package_id').html('<option value="">Select a package</option>').prop('disabled', true);
            loadPackages(selectedCategory);
        });

        // Function to load packages based on selected category
        function loadPackages(category, selectedPackage = null) {
            const $packageSelect = $('#package_id');
            $packageSelect.prop('disabled', true).html('<option>Loading packages...</option>');
            $('#packageError').hide();

            if (!category) {
                $packageSelect.html('<option value="">Select a package</option>').prop('disabled', true);
                return;
            }

            $.post(window.fetchPackagesRoute, { category })
                .done(function (response) {
                    $packageSelect.empty().append('<option value="">Select a package</option>');

                    if (Array.isArray(response) && response.length > 0) {
                        response.forEach(pkg => {
                            $packageSelect.append(
                                `<option value="${pkg.id}">${pkg.package_name} - RM${pkg.price_per_day}/day</option>`
                            );
                        });

                        if (selectedPackage) {
                            $packageSelect.val(selectedPackage);
                        }

                        $packageSelect.prop('disabled', false);
                    } else {
                        $packageSelect.append('<option disabled>No packages available</option>');
                        $packageSelect.prop('disabled', false);
                    }
                })
                .fail(function () {
                    $packageSelect.html('<option disabled>Error loading packages</option>').prop('disabled', true);
                    $('#packageError')
                        .text('Failed to load packages. Please try again later.')
                        .removeClass('hidden');
                });
        }

        // Function to update participant counts
        function updateParticipants() {
            const male = parseInt($('#male').val()) || 0;
            const female = parseInt($('#female').val()) || 0;
            $('#num_participants').val(male + female);

            const disabledMale = parseInt($('#disabled_male').val()) || 0;
            const disabledFemale = parseInt($('#disabled_female').val()) || 0;

            if (disabledMale > male) $('#disabled_male').val(male);
            if (disabledFemale > female) $('#disabled_female').val(female);
        }

        // Function to update semester and session based on date
        async function updateSemesterSession(date) {
            if (!date) {
                $('#semester, #session').val('');
                return;
            }

            try {
                const response = await $.post(window.getSemesterSessionRoute, { date });
                $('#semester').val(response.semester);
                $('#session').val(response.session);
            } catch (error) {
                $('#semester, #session').val('');
                alert("Semester and session not found for selected date.");
            }
        }

        // Function to validate the form
        function validateForm() {
            const action = $('button[type=submit][clicked=true]').val();
            if (action === 'draft') return true;

            if (!$('#rental_purpose').val().trim()) return alertFocus("Rental Purpose is required.", '#rental_purpose');
            if (!$('#check_in_date').val()) return alertFocus("Check-in Date is required.", '#check_in_date');
            if (!$('#check_out_date').val()) return alertFocus("Check-out Date is required.", '#check_out_date');
            if (!$('#category').val()) return alertFocus("Category is required.", '#category');
            if (!$('#package_id').val()) return alertFocus("Package selection is required.", '#package_id', '#packageError');

            const male = parseInt($('#male').val()) || 0;
            const female = parseInt($('#female').val()) || 0;
            if (male + female < 1) return alertFocus("At least one participant is required.", '#male');

            if ($('input[name="disabled_status"]:checked').val() === 'yes') {
                const dm = parseInt($('#disabled_male').val()) || 0;
                const df = parseInt($('#disabled_female').val()) || 0;
                if (dm + df < 1) return alertFocus("At least one disabled participant required.", '#disabled_male');
                if (dm > male) return alertFocus("Disabled Male cannot exceed Male.", '#disabled_male');
                if (df > female) return alertFocus("Disabled Female cannot exceed Female.", '#disabled_female');
            }

            return true;
        }

        // Function to alert and focus on a specific input
        function alertFocus(msg, selector, errorBlock = null) {
            alert(msg);
            if (errorBlock) $(errorBlock).show();
            $(selector).focus();
            return false;
        }

        // On ready
        $(function () {
            $('form button[type=submit]').click(function () {
                $('button[type=submit]', $(this).parents("form")).removeAttr('clicked');
                $(this).attr('clicked', 'true');
            });

            $('input[name="disabled_status"]').change(function () {
                const isYes = $(this).val() === 'yes';
                $('#disabled_counts').toggle(isYes);
                $('#disabled_male, #disabled_female').prop('required', isYes);
            }).trigger('change');

            $('#category').change(function () {
                $('#package_id').html('<option value="">Select a package</option>').prop('disabled', true);
                loadPackages($(this).val());
            });

            $('#male, #female, #disabled_male, #disabled_female').on('focus', function () {
                if ($(this).val() === '0') {
                    $(this).val('');
                }
            });

            $('#male, #female').on('input', function () {
                if ($(this).val() === '0') {
                    $(this).val('');
                }
                updateParticipants();
            });

            $('#disabled_male, #disabled_female').on('input', function () {
                if ($(this).val() === '0') {
                    $(this).val('');
                }
            });

            $('#check_in_date').change(function () {
                updateSemesterSession($(this).val());
            });

            updateParticipants();
            loadPackages($('#category').val(), '{{ old("package_id", $application->package_id) }}');

            $('#applicationForm input, #applicationForm select').on('input change', function () {
                const required = $('#applicationForm input[required], #applicationForm select[required]');
                const filled = required.filter(function () { return $(this).val(); }).length;
                const percent = Math.floor((filled / required.length) * 100);
                $('#formProgressBar').css('width', percent + '%').text(percent + '%');
            });

            // Handle form submission
            window.handleSubmit = function(action) {
                $('#form-action').val(action);
                if (action === 'draft') {
                    $('#applicationForm').submit();
                } else {
                    if (!validateForm()) {
                        return;
                    }
                    $('#applicationForm').submit();
                }
            };
        });
    });
</script>

</body>
</html>