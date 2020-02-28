@extends('layouts.app')

@push('style')
<style>
    .disabled {
        color: red;
    }
    .selected {
        color: blue;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Museum</div>
                <div class="panel-body">

                    {!! Form::open(['route' => 'store', 'class' => 'form-horizontal form-create']) !!}

                    <div class="form-group row">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="form-group row">
                        {!! Form::label('type', 'Type:', ['class' => 'col-sm-3 col-form-label text-right']) !!}
                        <div class="col-sm-8">
                            {!! Form::select('type', ['' => '', 'family' => 'Family', 'group' => 'Group'], null, ['class' => 'form-control change', 'required' => 'required']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('number', 'Number:', ['class' => 'col-sm-3 col-form-label text-right']) !!}
                        <div class="col-sm-8">
                            {!! Form::number('number', 1, ['class' => $errors->has('number') ? 'form-control is-invalid change' : 'form-control change', 'required' => 'required', 'min' => '1']) !!}
                            {!! $errors->first('number', '<div class="invalid-feedback">:message</div>') !!}
                        </div>
                    </div>

                    <div id="ajax-loader">

                    </div>

                    <div class="form-group row">
                        <div class="col-sm-12 text-right">
                            {!! Form::submit('Create', ['class' => 'btn btn-info']) !!}
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        $(document).on('change', '.change', function () {
            if ($('select').val() == 'family' && $('#number').val() > 5) $('#number').val(5);

            if ($('select').val()) {
                $.ajax({
                    type: "POST",
                    'url': '{{ route('ajaxLoader') }}',
                    'data': $('.form-create').serialize(),
                }).done(function (response) {
                    $('#ajax-loader').html(response.innerHtml);
                }).fail(function () {
                    alert('error');
                });
            } else $('#ajax-loader').html('');
        });

        $(document).on('change', '.change-date', function () {
            $.ajax({
                type: "POST",
                'url': '{{ route('ajaxTimeLoader') }}',
                'data': $('.form-create').serialize(),
            }).done(function (response) {
                $('#ajax-time-loader').html(response.innerHtml);
            }).fail(function () {
                alert('error');
            });
        });
    });
</script>
@endpush
