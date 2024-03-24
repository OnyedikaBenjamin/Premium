<x-guest-layout>
    <div class="container">
        <div class="row no-gutters height-self-center">
            <div class="col-sm-12 text-center align-self-center">
                <div class="iq-error position-relative">
                        <img src="{{ asset('images/error/500.png')}}" class="img-fluid iq-error-img iq-error-img-dark mx-auto" alt="">
                        <img src="{{ asset('images/error/500-dark.png')}}" class="img-fluid iq-error-img" alt="">
                        <h2 class="mb-0">Oops! This Page is Not Working.</h2>
                        <p>The requested is Internal Server Error.</p>
                        <a class="btn btn-primary d-inline-flex align-items-center mt-3" href="{{route('home')}}"><i class="ri-home-4-line"></i>Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>