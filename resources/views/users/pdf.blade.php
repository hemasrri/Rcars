<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Application Details - {{ $application->application_id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 20px;
        }
        .header-bar {
            background-color: #f7eec9;
            border-left: 5px solid #f0ad4e;
            padding: 8px;
            font-weight: bold;
            color: #6b4f07;
            margin-bottom: 10px;
        }
        .section-header {
            background-color: #008cba;
            color: white;
            padding: 8px;
            font-weight: bold;
        }
        .section-subheader {
            background-color: #d7ecf7;
            padding: 6px;
            font-weight: bold;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        .col {
            flex: 0 0 50%;
            padding: 4px 0;
        }
        .label {
            display: inline-block;
            width: 160px;
            font-weight: bold;
        }
        .value {
            display: inline-block;
        }
        .full-width {
            flex: 0 0 100%;
        }
        .top-left {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 10px;
            color: #444;
        }
       .logo-section {
    margin-top: 40px;
    margin-bottom: 20px;
    text-align: center;
}

.logo-section img {
    height: 50px;
    vertical-align: middle;
    display: inline-block;
}

.logo-text {
    display: inline-block;
    font-weight: bold;
    font-size: 16px;
    color: #333;
    font-family: Arial, sans-serif;
    vertical-align: middle;
    margin-left: 10px;
}

    </style>
</head>
<body>

    <!-- Download date and time -->
    <div class="top-left">
        Downloaded on: {{ \Carbon\Carbon::now()->format('d-M-Y H:i:s') }}
    </div>

    <!-- Logo and university name -->
    <div class="logo-section">
    <img src="{{ public_path('images/uthm.png') }}" alt="UTHM Logo" /><br>
    <div class="logo-text">UNIVERSITI TUN HUSSEIN ONN MALAYSIA</div>
</div>

    <!-- Existing header bar -->
    <div class="header-bar">
        APPLICATION DETAILS (ID = {{ $application->application_id }})
    </div>

    <div class="section-header">
        SUBMITTED ON: {{ \Carbon\Carbon::parse($application->created_at)->format('d-M-Y') }},
        USER ID: {{ $application->user_id ?? '-' }}
    </div>

    <!-- Continue with the rest of your content unchanged -->
    <div class="section-subheader">APPLICATION STATUS & PROGRAM</div>
    <div class="row">
        <div class="col">
            <span class="label">Status:</span>
            <span class="value">{{ ucfirst($application->application_status) }}</span>
        </div>
        <div class="col full-width">
            <span class="label">Program Title:</span>
            <span class="value">{{ $application->rental_purpose }}</span>
        </div>
    </div>

    <div class="section-subheader">APPLICANT INFORMATION</div>
    <div class="row">
        <div class="col"><span class="label">Name:</span> <span class="value">{{ $application->name }}</span></div>
        <div class="col"><span class="label">IC Number:</span> <span class="value">{{ $application->ic_number }}</span></div>
        <div class="col"><span class="label">Phone:</span> <span class="value">{{ $application->phone }}</span></div>
        <div class="col"><span class="label">Email:</span> <span class="value">{{ $application->email }}</span></div>
    </div>

    <div class="section-subheader">RENTAL DETAILS</div>
    <div class="row">
        <div class="col"><span class="label">Check-In Date:</span> <span class="value">{{ $application->check_in_date }}</span></div>
        <div class="col"><span class="label">Check-Out Date:</span> <span class="value">{{ $application->check_out_date }}</span></div>
    </div>

    <div class="section-subheader">PARTICIPANT DETAILS</div>
    <div class="row">
        <div class="col"><span class="label">Total Participants:</span> <span class="value">{{ $application->num_participants }}</span></div>
        <div class="col"><span class="label">Male:</span> <span class="value">{{ $application->male }}</span></div>
        <div class="col"><span class="label">Female:</span> <span class="value">{{ $application->female }}</span></div>
        <div class="col"><span class="label">Disabled (Male):</span> <span class="value">{{ $application->disabled_male }}</span></div>
        <div class="col"><span class="label">Disabled (Female):</span> <span class="value">{{ $application->disabled_female }}</span></div>
    </div>

    <div class="section-subheader">PACKAGE DETAILS</div>
    <div class="row">
        <div class="col"><span class="label">Package Name:</span> <span class="value">{{ $package->package_name ?? 'N/A' }}</span></div>
        <div class="col"><span class="label">Category:</span> <span class="value">{{ $package->category ?? 'N/A' }}</span></div>
    </div>

    <div class="section-subheader">DOCUMENT & FINAL STATUS</div>
    <div class="row">
        <div class="col full-width">
            <span class="label">Supporting Document:</span>
            <span class="value">
                @if($application->document_path)
                    Available
                @else
                    None
                @endif
            </span>
        </div>
        <div class="col full-width">
            <span class="label">Final Status:</span>
            <span class="value">{{ ucfirst($application->application_status) }}</span>
        </div>
    </div>

</body>
</html>
