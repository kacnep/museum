<div class="form-group row">
    {!! Form::label('date', 'Date:', ['class' => 'col-sm-3 col-form-label text-right']) !!}
    <div class="col-sm-8">
        @foreach($getAllDates as $key => $value)
            <label style="width: 13%;" class="{{ $value['disabled'] ? 'disabled' : '' }} {{ $value['selected'] ? 'selected' : '' }}">{!! Form::radio('date', $key, false, ['class' => 'change-date', 'required' => 'required', ($value['disabled'] || $value['selected']) ? 'disabled' : '']) !!} {{ $value['format'] }}</label>
        @endforeach
        {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>

<div id="ajax-time-loader">

</div>