
<?php
$field_name = 'donator_id';
$field_data_id = 'donator_id';
$field_lable = __("benefactor::$module_name.$field_name");
$field_placeholder = __("Select an option");
$required = "required";
?>
{{ html()->hidden($field_data_id, \Auth::user()->id)->placeholder($field_placeholder)->class('form-control select2')->attributes(["$required"]) }}
      
<div class="row">
    <div class="col-9">
        <div class="form-group">
            <?php
            $field_name = 'amount';
            $field_lable = "Silakan Pilih Nominal Anda";
            $field_placeholder = "Nominal Donasi";
            $required = "required";
            $buttons = ["100000","300000","500000","1000000"];
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            <br>
            @foreach($buttons as $button)
                <button type="button" id={{$button}} value={{$button}} class="amount_btn btn btn-primary btn-sm m-1">{{number_format($button, 0, ',', '.')}}</button>
            @endforeach
            <br>
            <small>Atau masukkan nominal:</small>
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-9">
        <div class="form-group">
            <?php
            $field_name = 'periods';
            $field_lable = __("benefactor::$module_name.$field_name");
            $field_placeholder = "Periode Donasi";
            $required = "required";
            $buttons = ["1","2","3","6","12"];
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            <br>
            @foreach($buttons as $button)
                <button type="button" id={{$button}} value={{$button}} class="month_btn btn btn-primary btn-sm m-1">{{$button}} Bulan</button>
            @endforeach
            <br>
            <small>Atau masukkan nominal:</small>
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
</div>

<!-- Select2 Library -->
<x-library.select2 />
<x-library.datetime-picker />

@push('after-styles')
<!-- File Manager -->
<link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
@endpush

@push ('after-scripts')

<!-- Date Time Picker & Moment Js-->
<script type="text/javascript">
$(function() {
    $('.datetime').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss',
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar-alt',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'far fa-calendar-check',
            clear: 'far fa-trash-alt',
            close: 'fas fa-times'
        }
    });
});
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $(".amount_btn").on('click', function(e) {
            $("#amount").val($(this).val());
        });

        $(".month_btn").on('click', function(e) {
            $("#periods").val($(this).val());
        });
    });
</script>

@endpush
