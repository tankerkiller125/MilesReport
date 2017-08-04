@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Current Entries</div>

                    <div class="panel-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Created</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Miles</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($entries as $entry)
                                <tr>
                                    <td>{{ $entry->createdAt }}</td>
                                    <td>{{ $entry->fromLocation->name }}</td>
                                    <td>{{ $entry->toLocation->name }}</td>
                                    <td>{{ $entry->distance }}</td>
                                    <td>
                                        <p data-placement="top" data-toggle="tooltip" title="Edit">
                                            <button class="button btn-primary btn-xs"
                                                    onclick="location.href='{{ url('/update/' . $entry->id) }}';"><span
                                                        class="glyphicon glyphicon-pencil"></span></button>
                                        </p>
                                    </td>
                                    <td>
                                        <p data-placement="top" data-toggle="tooltip" title="Delete">
                                            <button class="button btn-danger btn-xs"
                                                    onclick="location.href='{{ url('/delete/' . $entry->id) }}';"><span
                                                        class="glyphicon glyphicon-trash"></span></button>
                                        </p>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $entries->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
