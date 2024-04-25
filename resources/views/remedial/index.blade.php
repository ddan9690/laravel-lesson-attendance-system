@extends('remedial.layouts.master')

@section('title', 'Attendance')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="alert alert-info text-center" role="alert">
                <h5 class="alert-heading">{{ date('Y') }} Remedial Committee Members</h5>
                <ul class="list-group">
                    <li class="list-group-item">Mr. Rono Weldon</li>
                    <li class="list-group-item">Mr. Jared Ouma</li>
                    <li class="list-group-item">Mrs. Ruth Oketch</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="slider" style="max-width: 500px; height: 500px; margin: 0 auto;">
                <div><img src="{{ asset('remedialsystem/assets/img/theschool/plague.jpg') }}" alt="Plague" style="width: 100%; height: 100%; object-fit: cover;"></div>
                <div><img src="{{ asset('remedialsystem/assets/img/theschool/tution.jpg') }}" alt="Tution" style="width: 100%; height: 100%; object-fit: cover;"></div>
                <div><img src="{{ asset('remedialsystem/assets/img/theschool/nyabo.jpg') }}" alt="Nyabo" style="width: 100%; height: 100%; object-fit: cover;"></div>
                <div><img src="{{ asset('remedialsystem/assets/img/theschool/form-1.jpg') }}" alt="Form 1" style="width: 100%; height: 100%; object-fit: cover;"></div>
                <div><img src="{{ asset('remedialsystem/assets/img/theschool/eagle.jpg') }}" alt="Eagle" style="width: 100%; height: 100%; object-fit: cover;"></div>
                <div><img src="{{ asset('remedialsystem/assets/img/theschool/dining.jpg') }}" alt="Dining" style="width: 100%; height: 100%; object-fit: cover;"></div>
                <div><img src="{{ asset('remedialsystem/assets/img/theschool/commandemnt.jpg') }}" alt="Commandment" style="width: 100%; height: 100%; object-fit: cover;"></div>
                <div><img src="{{ asset('remedialsystem/assets/img/theschool/assembly-ground.jpg') }}" alt="Assembly Ground" style="width: 100%; height: 100%; object-fit: cover;"></div>
            </div>
        </div>
    </div>

</div>

<!-- Slick Carousel JavaScript and CSS -->
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Slick CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">

<!-- Include Slick Theme CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">

<!-- Include Slick JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

<script>
    $(document).ready(function() {
        $('.slider').slick({
            autoplay: true,
            autoplaySpeed: 2000,
            speed: 1000,
            dots: true,
            fade: true,
            cssEase: 'linear',
            slidesToShow: 1,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    });
</script>

@endsection
