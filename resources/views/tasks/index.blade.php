@extends('layouts.app')

@section('styles')
    <style>

    </style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <a href="{{ route('tasks.create') }}">Create a Task</a>
                        </div>
                        <div class="col-auto">
                            
                            <select class="form-control" name="projects">
                                <option value="">- All Projects -</option>
                                @foreach( $projects as $project )
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if( $tasks->count() > 0 )
                        <ul class="list-group tasks" id="sortable">
                            @foreach( $tasks as $task )
                                <li class="list-group-item" data-task-id="{{ $task->id }}" data-project-id="{{ $task->project ? $task->project->id : '' }}">
                                    <div class="row">
                                        <div class="col">{{ $task->name }}</div>
                                        <div class="col-auto">{{ $task->project ? $task->project->name : '' }}</div>
                                        <div class="col-auto"><a class="btn btn-info text-white" href="{{ route('tasks.edit', ['id' => $task->id]) }}">Edit</a></div>
                                        <div class="col-auto">
                                            <form action="{{ route('tasks.destroy', ['id' => $task->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-success">
                                                    Complete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No tasks yet, <a href="{{ route('tasks.create') }}">Create a Task.</a></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        $("#sortable").sortable({
            stop: function( event, ui ) {
                var $e        = $(ui.item);
                var $prevItem = $e.prev();
                var $nextItem = $e.next();

                $.ajax({
                    url: "{{ route('tasks.setPriority') }}",
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        task_id: $e.data('task-id'),
                        prev_id: $prevItem ? $prevItem.data('task-id') : null,
                        next_id: $nextItem ? $nextItem.data('task-id') : null
                    } 
                });
            }
        });

        $('[name="projects"]').on('change', function(){
            var $this = $(this);
            console.log($this);

            if( $this.val() ){
                $('.tasks li').hide();

                $('.tasks li')
                    .filter( $(`[data-project-id="${$this.val()}"]`) )
                    .show();

                return;
            }

            $('.tasks li').show();
        });
    </script>
@endsection
