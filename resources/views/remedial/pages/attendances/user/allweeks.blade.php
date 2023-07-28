@extends('remedial.layouts.master')
@section('title', 'Attendance')
@section('content')
<div class="col-md-6 mx-auto">
    <div class="card">
        <h5 class="card-header">{{ auth()->user()->name }}: <span><strong>Total-{{ auth()->user()->attendances->count() }}</strong></span></h5>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Week</th>
                            <th>No. of Lessons</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($weeks as $week)
                            <tr>
                                <td>Week {{ $week->week_number }}</td>
                                <td>{{ auth()->user()->attendances->where('week_id', $week->id)->count() }}</td>
                                <td>
                                    <a href="{{ route('user.viewweekly', ['week' => $week->week_number, 'user_id' => auth()->user()->id]) }}" class="btn btn-sm btn-info">Click to View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Info Pop-up -->
<style>
    .info-popup {
        display: none;
        position: fixed;
        top: 30%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.2);
        padding: 20px;
        text-align: center;
        max-width: 90%;
        z-index: 9999;
    }

    .info-popup-logo-container {
        /* Updated to display logos side by side */
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px; /* Adjust the gap between the logos as per your preference */
    }

    .info-popup-logo {
        width: 100px;
        height: 100px;
        position: relative;
    }

    .info-popup-logo img {
        position: absolute;
        width: 100%;
        height: 100%;
        animation: rotateAnimation1 20s linear infinite, rotateAnimation2 20s linear infinite; /* Updated keyframes to rotate logos in opposite directions */
    }

    @keyframes rotateAnimation1 {
        0% {
            transform: rotateY(0deg);
            opacity: 1;
        }
        50% {
            transform: rotateY(180deg);
            opacity: 0;
        }
        100% {
            transform: rotateY(360deg);
            opacity: 1;
        }
    }

    @keyframes rotateAnimation2 {
        0% {
            transform: rotateY(180deg);
            opacity: 0;
        }
        50% {
            transform: rotateY(0deg);
            opacity: 1;
        }
        100% {
            transform: rotateY(180deg);
            opacity: 0;
        }
    }

    .info-popup-text {
        font-size: 18px;
        margin-bottom: 15px;
    }

    .info-popup-dismiss-btn {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 8px 16px;
        font-size: 14px;
        cursor: pointer;
    }
</style>

<!-- JavaScript to show the info pop-up and handle dismissal -->
<script>
    // Show the info pop-up when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        var infoPopup = document.getElementById('infoPopup');
        if (infoPopup) {
            infoPopup.style.display = 'block';

            var dismissButton = document.getElementById('dismissButton');
            if (dismissButton) {
                // Hide the info pop-up when the dismiss button is clicked
                dismissButton.addEventListener('click', function() {
                    infoPopup.style.display = 'none';
                });
            }
        }
    });
</script>

<!-- Info Pop-up -->
<div class="info-popup" id="infoPopup">
    <div class="info-popup-logo-container">
        <div class="info-popup-logo">
            <img src="{{ asset('remedialsystem/assets/img/logo.png') }}" alt="Jamadata Logo">
        </div>
        <div class="info-popup-logo">
            <!-- Update the size of the Jamadata logo here -->
            <img src="{{ asset('remedialsystem/assets/img/jamadata.png') }}" alt="Jamadata" style="width: 150px; height: auto;">
        </div>
    </div>
    <p class="info-popup-text">Thank You. Remedial Commitee</p>
    <button class="info-popup-dismiss-btn" id="dismissButton">Close</button>
</div>
@endsection
