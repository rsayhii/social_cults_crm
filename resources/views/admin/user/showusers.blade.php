@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>User Details</h2>
    <p><strong>Name:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
