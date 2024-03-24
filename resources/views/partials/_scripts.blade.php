 <!-- Backend Bundle JavaScript -->
 <script src="{{ asset('js/backend-bundle.min.js')}}"></script>

<script src="{{ asset('vendor/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('vendor/tinymce/js/tinymce/jquery.tinymce.min.js') }}"></script>
<link href="{{ asset('css/dragula.css') }}" rel="stylesheet">
<script src="{{ asset('js/dragula.min.js') }}"></script>
<script>
    // Text Editor code
      if (typeof(tinyMCE) != "undefined") {
         // tinymceEditor()
         function tinymceEditor(target, button, callback, height = 200) {
            var rtl=$("html[lang=ar]").attr('dir');
            tinymce.init({
               selector: target || '.textarea',
               directionality : rtl,
               height: height,
               skin: 'oxide-dark',
               relative_urls: false,
               remove_script_host: false,
               content_css: [
                  'https://fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                  'https://www.tinymce.com/css/codepen.min.css'
               ],
               image_advtab: true,
               menubar: false,
               plugins: ["textcolor colorpicker image imagetools media charmap link print textcolor code codesample table"],
               toolbar: "image undo redo | link image | code table",
               toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist | removeformat | code | image |' + button,
               image_title: true,
               automatic_uploads: true,
               setup: callback,
               convert_urls:false,
               file_picker_types: 'image',
               file_picker_callback: function(cb, value, meta) {
                  var input = document.createElement('input');
                  input.setAttribute('type', 'file');
                  input.setAttribute('accept', 'image/*');

                  input.onchange = function() {
                     var file = this.files[0];

                     var reader = new FileReader();
                     reader.onload = function() {
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);

                        cb(blobInfo.blobUri(), { title: file.name });
                     };
                     reader.readAsDataURL(file);
                  };
                  input.click();
               }
            });
         }
      }
      function showCheckLimitData(id){
         var checkbox =  $('#'+id).is(":checked")
         if(checkbox == true){
            $('.'+id).removeClass('d-none')
         }else{
            $('.'+id).addClass('d-none')

         }
      }
</script>
 @yield('bottom_script')

 <!-- Flextree Javascript-->
 <script src="{{ asset('vendor/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
 <script src="{{ asset('js/flex-tree.min.js')}}"></script>
 <script src="{{ asset('js/tree.js')}}"></script>

 <!-- Table Treeview JavaScript -->
 <script src="{{ asset('js/table-treeview.js')}}"></script>

 <!-- SweetAlert JavaScript -->
 <script src="{{ asset('js/sweetalert.js')}}"></script>

 <!-- Vectoe Map JavaScript -->
 <script src="{{ asset('js/vector-map-custom.js')}}"></script>

 <!-- Chart Custom JavaScript -->
 <script src="{{ asset('js/customizer.js')}}"></script>

 <script src="{{ asset('vendor/confirmJs/confirm.min.js')}}"></script>

 <script src="{{ asset('vendor/vanillajs-datepicker/dist/js/datepicker-full.js')}}"></script>

 <script src="{{ asset('js/charts/progressbar.js')}}"></script>

 <!-- Chart Custom JavaScript -->
 <script src="{{ asset('js/chart-custom.js')}}"></script>
 <script src="{{ asset('js/charts/01.js')}}"></script>
 <script src="{{ asset('js/charts/02.js')}}"></script>

 <!-- slider JavaScript -->
 <script src="{{ asset('js/slider.js')}}"></script>

 <!-- Emoji picker -->
 <script src="{{ asset('vendor/emoji-picker-element/index.js')}}" type="module"></script>
 @if(isset($assets) && (in_array('datatable',$assets) || in_array('datatable_builder',$assets)))
    <script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    <script src="{{ asset('vendor/datatables/js/dataTables.select.min.js') }}"></script>
@endif
    <script src="{{ asset('vendor/fullcalendar/core/main.js') }}"></script>
    <script src="{{ asset('vendor/fullcalendar/interaction/main.js') }}"></script>
    <script src="{{ asset('vendor/fullcalendar/daygrid/main.js') }}"></script>
    <script src="{{ asset('vendor/fullcalendar/timegrid/main.js') }}"></script>
    <script src="{{ asset('vendor/fullcalendar/list/main.js') }}"></script>
    <script src="{{ asset('vendor/fullcalendar/bootstrap/main.js') }}"></script>
 <!-- app JavaScript -->
 <script src="{{ asset('js/app.js')}}"></script>
 @include('helper.app_message')