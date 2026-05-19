@extends('layouts.app')

@section('title', 'Edit Subject')
@section('page_title', 'Edit Subject')

@section('content')
<form method="POST" action="{{ route('admin.subjects.update', $subject->id) }}">
    @csrf
    @method('PUT')
    @include('admin.subjects._form')
</form>
@endsection