@extends('layouts.app')

@section('title', 'تعديل فترة تداول')

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-3">تعديل فترة تداول</h1>
    @include('trading-periods.partials.form', ['action' => route('trading-periods.update', $period), 'method' => 'PUT'])
</div>
@endsection
