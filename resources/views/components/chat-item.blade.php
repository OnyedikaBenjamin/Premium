@props(['id', 'img','msg','name','dateTime'])

<li class="simple-item hover" data-toggle-extra="tab" data-target-extra="#user-content-{{$id}}">
    <div class="img-container">
        <div class="avatar avatar-60">
            <img src="{{asset('images/')}}/{{ $img }}" alt="users" class="img-fluid avatar-borderd avatar-rounded">
            <span class="avatar-status">
                <i class="ri-checkbox-blank-circle-fill text-success"></i>
            </span>
        </div>
    </div>
    <div class="simple-item-body">
        <div class="simple-item-title">
           
                <h5 class="title-text">{{ $name }}</h5>
                <div class="simple-item-time"> <span>{{ $dateTime }}</span> </div>
            
        </div>
        <div class="simple-item-content">
        <span class="simple-item-text short">
            {{ $msg }}
        </span>
        <div class="dropdown">
            <button class="btn btn-link " type="button" id="chat-dropdown-{{$id}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="las la-caret-down"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="chat-dropdown-{{$id}}">
                <a class="dropdown-item" href="#">Move Archive</a>
                <a class="dropdown-item" href="#">Favourite</a>
                <a class="dropdown-item" href="#">Delete</a>
            </div>
        </div>
        </div>
    </div>
</li>