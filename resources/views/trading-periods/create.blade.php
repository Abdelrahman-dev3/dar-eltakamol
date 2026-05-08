@extends('layouts.app')

@section('title', 'إضافة فترة تداول')

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-3">إضافة فترة تداول</h1>
    @include('trading-periods.partials.form', ['action' => route('trading-periods.store'), 'method' => null])
</div>
@endsection
