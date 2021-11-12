<div class="col">
    <?php
    $module_name = 'commitments';
    $module_action = 'Edit';
    ?>
    {{ html()->modelForm($$module_name_singular, 'PATCH', route("frontend.$module_name.update", $$module_name_singular->commitments->first()->id))->class('form')->open() }}

    @include('benefactor::frontend.commitments.form',['module_name' => 'commitments'])

    <div class="row">
        <div class="col-12">
            <div class="float-right">
                <div class="form-group">
                {{ html()->button($text = ucfirst("OK") . "", $type = 'submit')->id("submit-reg")->class('btn btn-success btn-lg') }}
                </div>
            </div>
        </div>
    </div>

    {{ html()->form()->close() }}
    
</div>