@extends('layouts.app')

@section('title', __('عرض نوع الربح'))

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    {{ __('عرض نوع الربح') }}
                    <div class="pull-left">
                        <a href="{{ route('profits.edit', $profit) }}" class="btn btn-warning btn-sm">
                            <span class="glyphicon glyphicon-edit"></span> {{ __('تعديل') }}
                        </a>
                        <a href="{{ route('users-profits.index', ['profit_id' => $profit->id]) }}" class="btn btn-primary btn-sm">
                            <span class="glyphicon glyphicon-user"></span> {{ __('توزيعات المستخدمين') }}
                        </a>
                        <a href="{{ route('profits.index') }}" class="btn btn-default btn-sm">
                            <span class="glyphicon glyphicon-arrow-right"></span> {{ __('العودة') }}
                        </a>
                    </div>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('تاريخ الربح') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $profit->date->format('Y-m-d') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('تاريخ انتهاء التوزع') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $profit->end_date->format('Y-m-d') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('المبلغ الإجمالي') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ number_format($profit->amount, 2) }} {{ __('ريال') }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('الحالة') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        @if($profit->confirmed)
                            <span class="label label-success">{{ __('مؤكد') }}</span>
                        @else
                            <span class="label label-warning">{{ __('غير مؤكد') }}</span>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('تاريخ الإنشاء') }}:</strong>
                    </div>
                    <div class="col-md-6">
                        {{ $profit->created_at->format('Y-m-d H:i') }}
                    </div>
                </div>
                <hr>

                <h4>{{ __('توزيعات المستخدمين') }}</h4>
                @if($profit->usersProfits->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('المستخدم') }}</th>
                                    <th>{{ __('المبلغ المخصص') }}</th>
                                    <th>{{ __('النسبة') }}</th>
                                    <th>{{ __('الحالة') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                    @php
                        $totalAllocated = $profit->usersProfits->sum('amount');
                    @endphp
                                @foreach($profit->usersProfits as $userProfit)
                                    @php
                                        $percentage = $totalAllocated > 0 ? ($userProfit->amount / $totalAllocated) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $userProfit->contributor->name ?? __('غير معروف') }}</td>
                                        <td>{{ number_format($userProfit->amount, 2) }} {{ __('ريال') }}</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%">
                                                    {{ number_format($percentage, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
        @if($userProfit->is_paid ?? false)
        <span class="label label-success">{{ __('مدفوع') }}</span>
    @else
    <span class="label label-warning">{{ __('غير مدفوع') }}</span>
    @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="info">
                                    <td><strong>{{ __('المجموع') }}</strong></td>
                                    <td><strong>{{ number_format($totalAllocated, 2) }} {{ __('ريال') }}</strong></td>
                                    <td><strong>100%</strong></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <strong>{{ __('المبلغ المركوز') }}:</strong>
                            {{ number_format($profit->amount - $totalAllocated, 2) }} {{ __('ريال') }}
                        </div>
                        <div class="col-md-6">
                            @if($profit->confirmed && $profit->usersProfits->count() > 0)
                                <a href="{{ route('users-profits.index', ['profit_id' => $profit->id]) }}" class="btn btn-success">
                                    <span class="glyphicon glyphicon-credit-card"></span> {{ __('إدارة المدفوعات') }}
                                </a>
                            @endif
                       :</div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        {{ __('لم يتم توزيع هذا الربح على المستخدمين بعد.') }}
                        <a href="{{ route('users-profits.create', ['profit_id' => $profit->id]) }}" class="btn btn-primary btn-sm">
                            <span class="glyphicon glyphicon-plus"></span> {{ __('توزيع الربح') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
