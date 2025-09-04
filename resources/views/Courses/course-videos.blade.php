@extends('adminlte::page')

@section('title', 'Course Videos')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Videos for course: {{ $course->name }}</h1>

    @if($videos->count() > 0)
        <div class="row">
            @foreach($videos as $video)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <video width="100%" controls>
<source src="{{ $video->video_url }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>

                    <div class="card-body">
                        <h5 class="card-title">{{ $video->title }}</h5>
                        <p class="card-text">{{ Str::limit($video->description, 100) }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <p class="text-muted">No videos found for this course.</p>
    @endif

    <a href="{{ route('courses.index') }}" class="btn btn-secondary mt-3">Back to Courses</a>
</div>
@endsection
