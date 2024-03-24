@props(['id', 'img','name','dateTime','title','activeClass', 'about','nickName','contact','dob','gender','lang','status'])


<div class="chat-content animate__animated animate__fadeIn {{ $activeClass }}" data-toggle-extra="tab-content" id="user-content-{{$id}}">
    <div class="card">
        <div class="right-sidenav" id="first-sidenav">
        <div class="d-flex">
            <button type="button" class="btn btn-sm" data-extra-dismiss="right-sidenav">
                <i class="las la-times"></i>
            </button>
        </div>
        <div class="chat-profile mx-auto">
            <div class="avatar avatar-70 avatar-borderd avatar-rounded mx-auto" data-toggel-extra="right-sidenav" data-target="#first-sidenav">
               <img src="{{asset('images/')}}/{{$img}}" alt="users" class="img-fluid">
            </div>
            <h4 class="mb-2">{{ $name }}</h4>
            <h6 class="mb-2">{{ $about }}</h6>
        </div>
        <div class="chat-detail">
            <div class="row">
                <div class="col-6 col-md-6 title">Nick Name:</div>
                <div class="col-6 col-md-6 text-right">{{ $nickName }}</div>
            </div>
            <hr>
            <div class="row">
                <div class="col-6 col-md-6 title">Tel:</div>
                <div class="col-6 col-md-6 text-right">{{ $contact }}</div>
            </div>
            <hr>
            <div class="row">
                <div class="col-6 col-md-6 title">Date Of Birth:</div>
                <div class="col-6 col-md-6 text-right">{{ $dob }}</div>
            </div>
            <hr>
            <div class="row">
                <div class="col-6 col-md-6 title">Gender:</div>
                <div class="col-6 col-md-6 text-right">{{ $gender }}</div>
            </div>
            <hr>
            <div class="row">
                <div class="col-6 col-md-6 title">Language:</div>
                <div class="col-6 col-md-6 text-right">{{ $lang }}</div>
            </div>
        </div>
        </div>
        <div class="card-header chat-content-header">
        <div class="d-flex align-items-center">
            <button class="btn text-primary bg-primary-light btn-sm d-block d-lg-none mr-2" data-toggel-extra="side-nav" data-expand-extra=".chat-left-wrapper">
                <i class="las la-arrow-right"></i>
            </button>
            <div class="avatar-50 avatar-borderd avatar-rounded" data-toggel-extra="right-sidenav" data-target="#first-sidenav">
                <img src="{{asset('images/')}}/{{$img}}" alt="users" class="img-fluid">
            </div>
            <div class="chat-title">
                <h5>{{ $name }}</h5>
                <small>{{ $status }}</small>
            </div>
        </div>
        <div class="chat-action">
            <div class="nav" id="user-{{$id}}-action" role="tablist">
                <button class="btn text-primary bg-primary-light btn-sm" id="user-{{$id}}-video-call-tab">
                    <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </button>
                <button class="btn text-primary bg-primary-light btn-sm ml-2" id="user-{{$id}}-call-tab">
                    <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </button>
            </div>
        </div>
        </div>
        <div class="card-body msg-content" id="user-{{$id}}-chat-content">
        <div class="msg-list">
            <div class="single-msg">
                <div class="triangle-topleft single-msg-shap">
                </div>
                <div class="single-msg-content">
                    <div class="msg-detail">
                    <span>Sed porttitor lectus nibh. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</span>
                    </div>
                    <div class="msg-action">
                    <span>04:15</span>
                    {{-- {{!-- <button class="btn">Detail</button> --}}
                    </div>
                </div>
            </div>
            <div class="single-msg user">
                <div class="triangle-topright single-msg-shap">
                </div>
                <div class="single-msg-content user">
                    <div class="msg-detail">
                    <span>Donec sollicitudin molestie malesuada. Proin eget tortor risus. Quisque velit nisi, pretium ut lacinia in, elementum id enim.</span>
                    </div>
                    <div class="msg-action">
                    <span>04:15</span>
                    {{-- {{!-- <button class="btn">Detail</button> --}} 
                    </div>
                </div>
            </div>
            <div class="single-msg">
                <div class="triangle-topleft single-msg-shap">
                </div>
                <div class="single-msg-content">
                    <div class="msg-detail">
                    <span>Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui.</span>
                    </div>
                    <div class="msg-action">
                    <span>04:15</span>
                    {{-- {{!-- <button class="btn">Detail</button> --}} 
                    </div>
                </div>
            </div>
            <div class="single-msg">
                <div class="triangle-topleft single-msg-shap">
                </div>
                <div class="single-msg-content">
                    <div class="msg-detail">
                    <span>Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui.</span>
                    </div>
                    <div class="msg-action">
                    <span>04:15</span>
                    {{-- {{!-- <button class="btn">Detail</button> --}}
                    </div>
                </div>
            </div>
            <div class="single-msg">
                <div class="triangle-topleft single-msg-shap">
                </div>
                <div class="single-msg-content">
                    <div class="msg-detail">
                    <span>Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui.</span>
                    </div>
                    <div class="msg-action">
                    <span>04:15</span>
                    {{-- {{!-- <button class="btn">Detail</button> --}}
                    </div>
                </div>
            </div>
            <div class="single-msg user">
                <div class="triangle-topright single-msg-shap">
                </div>
                <div class="single-msg-content user">
                    <div class="msg-detail">
                    <span>Donec sollicitudin molestie malesuada. Proin eget tortor risus. Quisque velit nisi, pretium ut lacinia in, elementum id enim.</span>
                    </div>
                    <div class="msg-action">
                    <span>04:15</span>
                    {{-- {{!-- <button class="btn">Detail</button> --}} 
                    </div>
                </div>
            </div>
            <div class="single-msg user">
                <div class="triangle-topright single-msg-shap">
                </div>
                <div class="single-msg-content user">
                    <div class="msg-detail">
                    <span>Donec sollicitudin molestie malesuada. Proin eget tortor risus. Quisque velit nisi, pretium ut lacinia in, elementum id enim.</span>
                    </div>
                    <div class="msg-action">
                    <span>04:15</span>
                    {{-- {{!-- <button class="btn">Detail</button> --}} 
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="card-footer px-3 py-3">
        <form>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control" id="chat-input" placeholder="Enter here..." aria-label="Recipient's username" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button type="button" class="input-group-text chat-icon" id="basic-addon1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="las la-smile"></i>
                    </button>
                    <div class="dropdown-menu p-0 border-0" aria-labelledby="basic-addon1">
                    <emoji-picker data-target-input="#chat-input"></emoji-picker>
                    </div>
                </div>
                <div class="input-group-append">
                    <span class="input-group-text chat-icon" id="basic-addon2">
                    <i class="lab la-telegram-plane"></i>
                    </span>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>