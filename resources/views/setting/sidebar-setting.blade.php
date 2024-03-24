
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{ route('reorderSave') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <section class="">
                    <ul class="list-unstyled m-1" id="drag-list">
                        @if( count($settings) > 0 )
                            @foreach($settings as $data)
                                <li class="card m-2 p-3 cursor-move">
                                    <input type="hidden" name="id[]" value="{{ $data->id }}">
                                    <span class="title board_title">
                                        <i class="fa fa-bars drag-area" aria-hidden="true"></i>
                                        {{ $data->title }}
                                    </span>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </section>
                <div class="modal-footer">
                    <button class="btn btn-md btn-primary" >{{ trans('messages.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    (function($) {
    "use strict";
        $(document).ready(function(){
            var sequence = [];
            dragula([$("#drag-list").get(0)]).on("dragend", function(el, target, src) {
                sequence = [];
                $(".board_title").each(function(idx, elem) {
                    sequence.push($(elem).text());
                });
            });
        })
})(jQuery);
</script>
