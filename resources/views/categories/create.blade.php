@extends('layouts.valex')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-slate-800 pb-2 border-b border-slate-200/80">Add Category</h1>
    @include('partials.flash')
    @include('categories._form')
</div>
@endsection