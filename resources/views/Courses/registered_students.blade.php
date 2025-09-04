@extends('adminlte::page')

@section('title', 'Registered Students')

@section('content')
<div class="container-fluid">
  <h1>Students Registered in Course #{{ $courseId }}</h1>
  @if($students->isEmpty())
    <p>No students registered yet.</p>
  @else
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>Student Name</th>
          <th>Email</th>
          <th>Registration Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
    @foreach($students as $index => $registration)
<tr>
  <td>{{ $index + 1 }}</td>
  <td>{{ $registration->student->first_name }} {{ $registration->student->last_name }}</td>
  <td>{{ $registration->student->user->email }}</td>
  <td>{{ optional($registration->registered_at)->format('Y-m-d') ?? 'N/A' }}</td>
  <td>{{ ucfirst($registration->status) ?? 'N/A' }}</td>
</tr>
@endforeach

      </tbody>
    </table>
  @endif
</div>
@endsection
