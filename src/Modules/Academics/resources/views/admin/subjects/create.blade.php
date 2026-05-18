@extends('layouts.app')

@section('title', 'Create Subject')
@section('page_title', 'Create Subject')

@section('content')
<form method="POST" action="{{ route('admin.subjects.store') }}">
    @csrf
    @include('admin.subjects._form')
</form>
@endsection