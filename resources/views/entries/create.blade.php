@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create Entry</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('create') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('from') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">From</label>

                            <div class="col-md-6">
                                <select class="form-control" name="from" required>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('from'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('from') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('to') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">To</label>

                            <div class="col-md-6">
                                <select class="form-control" name="to" required>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('to'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('to') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('mpg') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">MPG</label>

                            <div class="col-md-6">
                                <input type="number" class="form-control" name="mpg">

                                @if ($errors->has('mpg'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('mpg') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Create Entry
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection