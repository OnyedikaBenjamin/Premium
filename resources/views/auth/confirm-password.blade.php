<x-guest-layout>
    <section class="login-content">
        <div class="container-fluid h-100">
            <div class="row align-items-center justify-content-center h-100">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-lg-12 text-center">
                                    <img src="{{ getSingleMedia(settingSession('get'),'site_logo',null) }}" class="img-fluid" width="80" alt="">
                                    <h2>{{__('auth.confirm_password')}}</h2>
                                    <p>{{ __('auth.password_continue') }}</p>
                                    <!-- Validation Errors -->
                                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                                    <form method="POST" action="{{ route('password.confirm') }}">
                                        @csrf

                                        <!-- Password -->
                                        <div class="form-group">
                                            <label for="password">{{__('auth.password')}}</label>
                                            <input id="password" class="form-control"
                                                            type="password"
                                                            name="password"
                                                            required autocomplete="current-password" />
                                        </div>

                                        <button class="btn btn-primary btn-block">
                                            {{ __('auth.confirm') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
