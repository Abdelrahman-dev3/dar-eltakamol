@extends('layouts.app')

@section('title', __('عروض البيع'))

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>{{ __('عروض البيع') }}</h2>
        
        @if($canCreate)
            <p>
                <a href="{{ route('sell-shares.create') }}" class="btn btn-primary">{{ __('إضافة عرض بيع جديد') }}</a>
            </p>
        @endif
        
        <div class="row">
            @forelse($sellShares as $sellShare)
                <div class="col-sm-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ __('طلب بيع عدد') }} - {{ $sellShare->count }}</h3>
                        </div>
                        <div class="panel-body">
                            <div class="col-xs-12">
                                <div class="row vertical-align">
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                {{ __('المستخدم') }}
                                            </div>
                                            <div class="col-xs-6">
                                                : {{ $sellShare->seller->name ?? __('غير محدد') }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-6">
                                                {{ __('السعر لكل سهم') }}
                                            </div>
                                            <div class="col-xs-6">
                                                : {{ number_format($sellShare->amount_per_share, 2) }} {{ __('ريال') }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-6">
                                                {{ __('الكمية') }}
                                            </div>
                                            <div class="col-xs-6">
                                                : {{ $sellShare->count }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-6">
                                                {{ __('تاريخ الانتهاء') }}
                                            </div>
                                            <div class="col-xs-6">
                                                : {{ $sellShare->end_date ? $sellShare->end_date->format('Y-m-d') : __('غير محدد') }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-6">
                                                {{ __('ملاحظات') }}
                                            </div>
                                            <div class="col-xs-6">
                                                : {{ $sellShare->notes ?? __('لا توجد ملاحظات') }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-6">
                                                {{ __('حالة الإعلان') }}
                                            </div>
                                            <div class="col-xs-6">
                                                : {{ $sellShare->getAdStatusText() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <a href="{{ route('sell-shares.show', $sellShare) }}" class="btn btn-info">{{ __('تفاصيل') }}</a>
                            
                            @if($sellShare->ad_status == 0) {{-- Initial status --}}
                                <a href="{{ route('sell-shares.edit', $sellShare) }}" class="btn btn-primary">{{ __('تعديل') }}</a>
                                <form action="{{ route('sell-shares.destroy', $sellShare) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('هل أنت متأكد من الحذف؟') }}')">{{ __('حذف') }}</button>
                                </form>
                            @endif
                            
                            <a href="{{ route('sell-shares.print', $sellShare) }}" class="btn btn-dark">{{ __('طباعة') }}</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-sm-12">
                    <div class="alert alert-info text-center">
                        {{ __('لا توجد عروض بيع') }}
                    </div>
                </div>
            @endforelse
        </div>
        
        {{ $sellShares->links() }}
    </div>
</div>
@endsection
