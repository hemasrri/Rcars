<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications Management</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
     <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .content {
            margin-left: 270px;
            flex-grow: 1;
            padding: 20px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f0f0f0;
            color: black;
            padding: 15px;
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .user-menu {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .user-menu span {
            margin-right: 10px;
        }

        .dropdown {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            min-width: 150px;
            z-index: 1;
            border-radius: 5px;
        }

        .dropdown a {
            color: black;
            text-decoration: none;
            display: block;
            padding: 10px;
            transition: 0.3s;
        }

        .dropdown a:hover {
            background-color: #f1f1f1;
        }

        .user-menu:hover .dropdown {
            display: block;
        }

        /* Status colors */
        .status-pending {
            color: orange;
        }

        .status-approved {
            color: green;
        }

        .status-rejected {
            color: red;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100px;
                padding: 10px;
            }

            .content {
                margin-left: 120px;
            }

            header {
                padding: 10px;
            }
        }
    </style>
    <script>
        function toggleNestedLinks() {
            const nestedLinks = document.getElementById('nested-links');
            nestedLinks.style.display = nestedLinks.style.display === 'block' ? 'none' : 'block';
        }

        $(document).on('submit', '.reject-form', function(e) {
            e.preventDefault();
            const form = $(this);
            const applicationId = form.data('application-id');
            const url = form.attr('action');

            $.ajax({
                type: 'POST',
                url: url,
                data: form.serialize(),
                success: function(response) {
                    const row = $(`#application-row-${applicationId}`);
                    row.find('.status').html(`Rejected by: ${response.processed_by}<br>Reason: ${form.find('textarea[name="rejection_reason"]').val()}`);
                    row.find('.status').removeClass('status-pending').addClass('status-rejected');
                    $('#rejectApplicationModal' + applicationId).modal('hide');
                    alert('Application rejected successfully!');
                },
                error: function(xhr) {
                    const errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred while rejecting the application.';
                    alert(errorMessage);
                }
            });
        });
    </script>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col">
            {{-- Include Header --}}
            @include('layouts.header')

            <div class="container mx-auto mt-5">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="GET" action="{{ route('admin.applications.download') }}" class="mb-3">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Download Data</button>
                </form>

                <form method="GET" action="{{ route('admin.applications.index') }}" class="flex mb-3">
                    <input type="text" class="form-control mr-2" name="search" placeholder="Search by Application ID or Status..." value="{{ request('search') }}">
                    <select name="status" class="form-control mr-2">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Filter</button>
                    <a href="{{ route('admin.applications.index') }}" class="bg-gray-300 text-black py-2 px-4 rounded hover:bg-gray-400">Refresh</a>
                </form>

                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Date Applied</th>
                            <th>Check In & Check Out</th>
                            
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                            @if(in_array($application->application_status, ['pending', 'approved', 'rejected']))
                                <tr id="application-row-{{ $application->application_id }}">
                                    <td>
                                        <span class="text-blue-600 text-sm">{{ $application->application_id }}</span><br>
                                        {{ $application->name }}<br>
                                        {{ $application->email }}<br>
                                        {{ $application->phone }}
                                    </td>
                                    <td>
    {{ $application->created_at->format('d.m.Y') }}
    {{ $application->created_at->format('H:i:s') }}
</td>
                                   <td>
    <strong>Check In:</strong> 
    {{ $application->check_in_date ? $application->check_in_date->format('d.m.Y') : 'Not specified' }}<br>
    <strong>Check Out:</strong> 
    {{ $application->check_out_date ? $application->check_out_date->format('d.m.Y') : 'Not specified' }}
</td>
                                    <td class="status 
                                        @if($application->application_status == 'approved') status-approved
                                        @elseif($application->application_status == 'rejected') status-rejected 
                                        @else status-pending @endif">
                                        @php
                                            $formattedAllocations = [];
                                            if ($application->application_status == 'approved') {
                                                echo "Approved <br> Approved by: {$application->processed_by}<br>Allocated Rooms:<br>";
                                                $roomAllocations = !empty($application->room_allocation) ? json_decode($application->room_allocation, true) : null;
                                                if (is_null($roomAllocations) && strpos($application->room_allocation, ':') !== false) {
                                                    $parts = explode(': ', $application->room_allocation);
                                                    if (count($parts) == 2) {
                                                        $roomType = trim($parts[0]);
                                                        $roomNumber = trim($parts[1]);
                                                        $formattedAllocations[] = "{$roomType}: {$roomNumber}";
                                                    }
                                                } elseif (is_array($roomAllocations) && count($roomAllocations) > 0) {
                                                    foreach ($roomAllocations as $type => $roomNumbers) {
                                                        if (is_array($roomNumbers)) {
                                                            foreach ($roomNumbers as $roomNumber) {
                                                                $formattedAllocations[] = "{$type}: {$roomNumber}";
                                                            }
                                                        } else {
                                                            $formattedAllocations[] = "{$type}: {$roomNumbers}";
                                                        }
                                                    }
                                                } else {
                                                    $formattedAllocations[] = "No rooms allocated.";
                                                }
                                            } elseif ($application->application_status == 'rejected') {
                                                echo "Rejected<br>Rejected by: {$application->processed_by}<br>Reason: {$application->rejection_reason}";
                                            } else {
                                                echo "Pending";
                                            }
                                        @endphp
                                        {!! implode('<br>', $formattedAllocations) !!}
                                    </td>
                                  <td class="text-center">
    <div class="d-flex flex-wrap justify-content-center">
        <button type="button" class="btn btn-sm btn-info mr-1 mb-1" data-toggle="modal" data-target="#viewApplicationModal{{ $application->application_id }}">View</button>

        <form action="{{ route('admin.applications.approve', $application->application_id) }}" method="POST" class="mr-1 mb-1" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-sm btn-success">Accept</button>
        </form>

        <button type="button" class="btn btn-sm btn-danger mb-1" data-toggle="modal" data-target="#rejectApplicationModal{{ $application->application_id }}">Reject</button>
    </div>
</td>



                                </tr>

                                <!-- Modal for viewing application -->
                                <div class="modal fade" id="viewApplicationModal{{ $application->application_id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Application Details (ID: {{ $application->application_id }})</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Name:</strong> {{ $application->name }}</p>
                                                <p><strong>IC Number:</strong> {{ $application->ic_number }}</p>
                                                <p><strong>Email:</strong> {{ $application->email }}</p>
                                                <p><strong>Phone:</strong> {{ $application->phone }}</p>
                                                <p><strong>Rental Purpose:</strong> {{ $application->rental_purpose }}</p>
                                                <p><strong>Check In:</strong> {{ $application->check_in_date ? $application->check_in_date->format('Y-m-d') : 'Not specified' }}</p>
                                                <p><strong>Check Out:</strong> {{ $application->check_out_date ? $application->check_out_date->format('Y-m-d') : 'Not specified' }}</p>
                                                <p><strong>Participants:</strong> {{ $application->num_participants }}</p>
                                                <p><strong>Male:</strong> {{ $application->male }}</p>
                                                <p><strong>Female:</strong> {{ $application->female }}</p>
                                                <p><strong>Male Disability:</strong> {{ $application->disabled_male }}</p>
                                                <p><strong>Female Disability:</strong> {{ $application->disabled_female }}</p>
                                                <p><strong>Package:</strong> {{ $application->package }}</p>
                                                <p><strong>Document:</strong>
                                                    @if($application->document_path)
                                                        <a href="{{ asset('storage/' . $application->document_path) }}" target="_blank">View</a>
                                                    @else
                                                        Not uploaded
                                                    @endif
                                        </p>
                                                @if($application->application_status == 'rejected')
                                                    <p><strong>Rejection Reason:</strong> {{ $application->rejection_reason }}</p>
                                                @endif
                                            </div>
                                           <div class="modal-footer d-flex flex-wrap justify-content-between align-items-center">
    <form action="{{ route('admin.applications.approve', $application->application_id) }}" method="POST" class="d-flex align-items-center">
        @csrf
        <div class="form-check mr-3">
            <input class="form-check-input" type="checkbox" name="payment_exception" id="modal_payment_exception_{{ $application->application_id }}">
            <label class="form-check-label small text-nowrap" for="modal_payment_exception_{{ $application->application_id }}">
                Payment Exception
            </label>
        </div>
        <button type="submit" class="btn btn-success btn-sm mr-2">Accept</button>
    </form>

    <button type="button" class="btn btn-danger btn-sm mr-2" data-toggle="modal" data-target="#rejectApplicationModal{{ $application->application_id }}">Reject</button>

    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
</div>

                                        </div>
                                    </div>
                                </div>

                                <!-- Modal for rejecting application -->
                                <div class="modal fade" id="rejectApplicationModal{{ $application->application_id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.applications.reject', $application->application_id) }}" method="POST" class="reject-form" data-application-id="{{ $application->application_id }}">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Application (ID: {{ $application->application_id }})</h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <label for="rejection_reason">Reason for rejection:</label>
                                                    <textarea id="rejection_reason" name="rejection_reason" rows="3" class="form-control" required></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-danger">Reject</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-center">
                    {{ $applications->links() }}
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
