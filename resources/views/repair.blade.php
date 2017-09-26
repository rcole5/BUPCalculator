@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Back Up Plan Calculator</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h2>Repair Plans</h2>

                    <!-- 1/2+2RPL Plans -->
                    <h3>Fridges &amp; Freezers</h3>
                    {{ Form::open(array('url' => '/repair/submit')) }}
                    <table class="table">
                        <tr>
                            <th>webgo $</th>
                            <th>GP %</th>
                            <th>Increments $</th>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group{{ $errors->has('webgo') ? ' has-error' : '' }}">
                                {!! Form::text('webgo', '', ['class' => 'form-control has-error', 'required']) !!}
                                </div>
                            </td>
                            <td>{!! Form::text('gp', '', ['class' => 'form-control', 'required']) !!}</td>
                            <td>{!! Form::number('inc', '5', ['class' => 'form-control', 'required']) !!}</td>
                        </tr>
                    </table>
                    <div class="btn-group col-md-12 col-sm-12" data-toggle="buttons">
                        <label class="btn btn-primary active col-md-3 col-sm-3">
                            {!! Form::radio('plan', '2plus3', true) !!}2 + 3
                        </label>
                        <label class="btn btn-primary col-md-3 col-sm-3">
                            {!! Form::radio('plan', '2plus4') !!}2 + 4
                        </label>
                        <label class="btn btn-primary col-md-3 col-sm-3">
                            {!! Form::radio('plan', '1plus4') !!}1 + 4
                        </label>
                        <label class="btn btn-primary col-md-3 col-sm-3">
                            {!! Form::radio('plan', '3plus3') !!}3 + 3
                        </label>
                    </div>
                    <div class="row col-md-12 col-sm-12" style="margin: 10px 0 0 0">
                        {!! Form::submit('Submit', ['class' => 'btn btn-primary col-md-4 col-md-offset-4 col-sm-4 col-sm-offset-4']) !!}
                    </div>
                    {{ Form::close() }}
                        
                    <!-- Manual Calculations -->
                    <h3>Manual</h3>
                    {{ Form::open(array('url' => '/submit/manual')) }}
                    <table class = "table">
                        <tr>
                            <th>webgo $</th>
                            <th>GP %</th>
                            <th>BUP $</th>
                        </tr>
                        <tr>
                            <td>{{ Form::text('webgo', '', ['class' => 'form-control']) }}</td>
                            <td>{{ Form::text('gp', '', ['class' => 'form-control']) }}</td>
                            <td>{{ Form::text('bup', '', ['class' => 'form-control']) }}</td>
                        </tr>
                        <tr>
                            <td>{{ Form::submit('submit', ['class' => 'btn btn-primary']) }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                    {{ Form::close() }}

                    <h2>Repair Plans</h2>
                    <h3>Coming Soon...</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
