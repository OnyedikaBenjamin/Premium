<x-guest-layout>
    <section class="login-content">
        <div class="container h-100">
           <div class="row align-items-center justify-content-center h-100">
              <div class="col-md-5">
                 <div class="card p-5">
                    <div class="card-body">
                       <div class="auth-logo">
                           <a href="{{route('frontend.index')}}">
                              <img src="{{ getSingleMedia(settingSession('get'),'site_logo',null) }}" class="img-fluid rounded-normal" alt="logo">
                           </a>
                       </div>
                       <h3 class="mb-3 text-center">{{__('auth.reset_password')}}</h3>
                       <p class="text-center small text-secondary mb-3">{{__('auth.reset_password_here')}}</p>
                       <!-- Session Status -->
                       <x-auth-session-status class="mb-4" :status="session('status')" />

                       <!-- Validation Errors -->
                       <x-auth-validation-errors class="mb-4" :errors="$errors" />
                       <form method="POST" action="{{ route('password.email') }}">
                            {{csrf_field()}}
                          <div class="row">
                             <div class="col-lg-12">
                                <div class="form-group">
                                   <label class="text-secondary">{{__('auth.email')}}</label>
                                   <input class="form-control" type="email" placeholder="Enter Email" id="email" name="email" :value="old('email')" required autofocus>
                                </div>
                             </div>
                          </div>
                          <button type="submit" class="btn btn-primary btn-block">{{ __('auth.reset_password') }}</button>
                       </form>
                    </div>
                 </div>
              </div>
           </div>
        </div>
    </section>
</x-guest-layout>
