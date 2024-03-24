<x-guest-layout>
   <section class="login-content">
      <div class="container h-100">
         <div class="row align-items-center justify-content-center h-100">
            <div class="col-md-5">
               <div class="card p-3">
                  <div class="card-body">
                     <div class="auth-logo">
                        <a href="{{route('frontend.index')}}">
                           <img src="{{ getSingleMedia(settingSession('get'),'site_logo',null) }}" class="img-fluid rounded-normal" alt="logo">
                        </a>
                     </div>
                     <h3 class="mb-3 font-weight-bold text-center">{{__('auth.get_start')}}</h3>
                     <!-- Session Status -->
                     <x-auth-session-status class="mb-4" :status="session('status')" />

                     <!-- Validation Errors -->
                     <x-auth-validation-errors class="mb-4" :errors="$errors" />
                     <form method="POST" action="{{ route('register') }}" data-toggle="validator">
                        {{csrf_field()}}
                        <div class="row">
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="username" class="text-secondary">{{__('auth.username')}} <span class="text-danger">*</span></label>
                                 <input class="form-control" id="username" name="username" value="{{old('username')}}" required placeholder="{{ __('auth.enter_name',[ 'name' => __('auth.username') ]) }}">
                                 <small class="help-block with-errors text-danger"></small>
                              </div>
                           </div>
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="first_name" class="text-secondary">{{__('auth.first_name')}} <span class="text-danger">*</span></label>
                                 <input class="form-control" id="first_name" name="first_name" value="{{old('first_name')}}" required placeholder="{{ __('auth.enter_name',[ 'name' => __('auth.first_name') ]) }}">
                                 <small class="help-block with-errors text-danger"></small>
                              </div>
                           </div>
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="last_name" class="text-secondary">{{__('auth.last_name')}} <span class="text-danger">*</span></label>
                                 <input class="form-control" id="last_name" name="last_name" value="{{old('last_name')}}" required placeholder="{{ __('auth.enter_name',[ 'name' => __('auth.last_name') ]) }}">
                                 <small class="help-block with-errors text-danger"></small>
                              </div>
                           </div>
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="email" class="text-secondary">{{__('auth.email')}} <span class="text-danger">*</span></label>
                                 <input class="form-control" type="email" id="email" name="email" value="{{old('email')}}" required placeholder="{{ __('auth.enter_name',[ 'name' => __('auth.email') ]) }}" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,}">
                                 <small class="help-block with-errors text-danger"></small>
                              </div>
                           </div>
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="password" class="text-secondary">{{__('auth.login_password')}} <span class="text-danger">*</span></label>
                                 <input class="form-control" type="password" id="password" name="password" required autocomplete="new-password" placeholder="{{ __('auth.enter_name',[ 'name' => __('auth.login_password') ]) }}">
                                 <small class="help-block with-errors text-danger"></small>
                              </div>
                           </div>
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="password_confirmation" class="text-secondary">{{__('auth.confirm_password')}} <span class="text-danger">*</span></label>
                                 <input class="form-control" onkeyup="checkPasswordMatch()" type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('auth.enter_name',[ 'name' => __('auth.confirm_password') ]) }}">
                                 <small class="help-block with-errors text-danger" id="confirm_passsword"></small>
                                 
                              </div>
                           </div>
                           <div class="col-lg-12 mt-2">
                              <div class="form-check mb-3 d-flex align-items-center">
                                 <input type="checkbox" class="form-check-input mt-0" id="customCheck1" required>
                                 <label class="form-check-label pl-2" for="customCheck1">
                                    {{__('auth.agree')}} <a href="{{ url('/') }}/#/term-conditions">{{__('auth.term_service')}}</a> &amp; <a href="{{ url('/') }}/#/privacy-policy">{{__('auth.privacy_policy')}}</a>
                                    <small class="help-block with-errors text-danger"></small>
                                 </label>
                              </div>
                           </div>

                        </div>
                        <button type="submit" class="btn btn-primary btn-block mt-2" id="submit-btn">{{ __('auth.create_account') }}</button>
                        <div class="col-lg-12 mt-3">
                           <p class="mb-0 text-center">{{__('auth.already_have_account')}} <a href="{{route('auth.login')}}">{{__('auth.sign_in')}}</a></p>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <script>
    function checkPasswordMatch() {

        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("password_confirmation").value;
        var errorElement = document.getElementById("confirm_passsword");
        var submitBtn = document.getElementById("submit-btn");

        if (password !== confirmPassword) {

            errorElement.innerHTML = "{{ __('auth.password_mismatch_error') }}";
            submitBtn.disabled = true;
        } else {
            errorElement.innerHTML = "";
            submitBtn.disabled = false;
        }
    }
</script>
   </section>
</x-guest-layout>