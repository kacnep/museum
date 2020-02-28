<div class="form-group row">
    {!! Form::label('time', 'Time:', ['class' => 'col-sm-3 col-form-label text-right']) !!}
    <div class="col-sm-8">
        @foreach($getAllTimes as $key => $value)
            <label style="width: 13%;" class="{{ $value['disabled'] ? 'selected' : '' }}">{!! Form::radio('time', $key, false, ['required' => 'required', $value['disabled'] ? 'disabled' : '']) !!} {{ $value['format'] }}</label>
        @endforeach
        {!! $errors->first('time', '<div class="invalid-feedback">:message</div>') !!}
    </div>
</div>
