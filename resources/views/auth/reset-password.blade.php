<x-guest-layout>
    <section class="login-content">
        <div class="container h-100">
            <div class="row align-items-center justify-content-center h-100">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="auth-logo">
                                <img src="{{ getSingleMedia(settingSession('get'),'site_logo',null) }}" class="img-fluid rounded-normal" alt="logo">
                            </div>
                            <h2 class="mb-2 text-center">{{__('auth.reset_password')}}</h2>
                            <p>{{__('messages.reset_password_info')}}</p>
                            <!-- Validation Errors -->
                            <x-auth-validation-errors class="mb-4" :errors="$errors" />
                            {{ Form::open(['route' => 'password.update','method' => 'post','data-toggle'=>"validator"]) }}
                                <input type="hidden" name="token" value="{{ $request->route('token')}}">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('email',__('messages.email').' <span class="text-danger">*</span>', ['class' => 'form-control-label'],false) }}
                                            {{ Form::email('email',old('email'),['placeholder' => __('messages.email'),'class' =>'form-control','required']) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('password',__('messages.password').' <span class="text-danger">*</span>', ['class' => 'form-control-label'],false) }}
                                            {{ Form::password('password', ['class' => 'form-control', 'placeholder' => __('messages.password'), 'required']) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('password_confirmation',__('messages.password_confirmation').' <span class="text-danger">*</span>', ['class' => 'form-control-label'],false) }}
                                            {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => __('messages.password_confirmation'), 'required']) }}
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">  {{ __('Reset Password') }}</button>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
