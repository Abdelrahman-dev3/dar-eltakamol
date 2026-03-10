@extends('layouts.app')

@section('title', __('لوحة التحكم'))

@section('content')
<style>
    @media (max-width: 768px) {
        .dashboard-title {
            font-size: 2rem !important;
            flex-direction: column;
            text-align: center;
        }
        
        .dashboard-title i {
            margin-left: 0 !important;
            margin-bottom: 10px;
            font-size: 1.8rem !important;
        }
        
        .dashboard-description {
            font-size: 1.2rem !important;
            flex-direction: column;
            text-align: center;
        }
        
        .dashboard-description i {
            margin-left: 0 !important;
            margin-bottom: 8px;
            font-size: 1.3rem !important;
        }
        
        .dashboard-card-title {
            font-size: 1.4rem !important;
            flex-direction: column;
            text-align: center;
        }
        
        .dashboard-card-title i {
            margin-left: 0 !important;
            margin-bottom: 8px;
            font-size: 1.2rem !important;
        }
        
        .dashboard-card-description {
            font-size: 1.1rem !important;
            flex-direction: column;
            text-align: center;
        }
        
        .dashboard-card-description i {
            margin-left: 0 !important;
            margin-bottom: 6px;
            font-size: 1.1rem !important;
        }
        
        .dashboard-btn {
            font-size: 1.1rem !important;
            padding: 15px 20px !important;
            width: 100%;
            margin-top: 10px;
        }
        
        .dashboard-btn i {
            font-size: 1rem !important;
        }
        
        .statistics-title {
            font-size: 1.6rem !important;
            flex-direction: column;
            text-align: center;
        }
        
        .statistics-title i {
            margin-left: 0 !important;
            margin-bottom: 8px;
            font-size: 1.4rem !important;
        }
        
        .statistics-card {
            margin-bottom: 20px;
        }
        
        .statistics-icon {
            font-size: 2.5rem !important;
        }
        
        .statistics-number {
            font-size: 2.5rem !important;
        }
        
        .statistics-label {
            font-size: 1.2rem !important;
        }
    }
    
    @media (max-width: 480px) {
        .dashboard-title {
            font-size: 1.8rem !important;
        }
        
        .dashboard-description {
            font-size: 1.1rem !important;
        }
        
        .dashboard-card-title {
            font-size: 1.3rem !important;
        }
        
        .dashboard-card-description {
            font-size: 1rem !important;
        }
        
        .dashboard-btn {
            font-size: 1rem !important;
            padding: 12px 18px !important;
        }
        
        .statistics-title {
            font-size: 1.4rem !important;
        }
        
        .statistics-icon {
            font-size: 2rem !important;
        }
        
        .statistics-number {
            font-size: 2rem !important;
        }
        
        .statistics-label {
            font-size: 1.1rem !important;
        }
    }
</style>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h2 class="dashboard-title" style="margin: 0; display: flex; align-items: center; font-size: 3rem;">
                    <i class="fa fa-dashboard" style="margin-left: 15px; color: var(--primary-color); font-size: 2.5rem;"></i>
                    {{ __('مرحباً بك في لوحة التحكم') }}
                </h2>
            </div>
            <div class="card-body">
                <p class="dashboard-description" style="font-size: 1.7rem; color: var(--text-secondary); display: flex; align-items: center;">
                    <i class="fa fa-building" style="margin-left: 10px; color: var(--primary-color); font-size: 1.9rem;"></i>
                    {{ __('مرحباً بك في نظام إدارة مجلس دار التكامل') }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header" style="background-color: var(--primary-color);">
                <h3 class="dashboard-card-title panel-title" style="margin: 0; display: flex; align-items: center; font-size: 2rem; color: white;">
                    <i class="fa fa-users-cog" style="margin-left: 10px; font-size: 1.8rem;"></i>
                    {{ __('المساهمين') }}
                </h3>
            </div>
            <div class="card-body">
                <p class="dashboard-card-description" style="display: flex; align-items: center; font-size: 1.6rem;">
                    <i class="fa fa-user-check" style="margin-left: 10px; color: var(--primary-color); font-size: 1.7rem;"></i>
                    {{ __('إدارة بيانات المساهمين') }}
                </p>
                <a href="{{ route('contributors.index') }}" class="btn dashboard-btn" style="font-size: 1.6rem; background-color: var(--primary-color); border-color: var(--primary-color); color: white;">
                    <i class="fa fa-eye" style="margin-right: 8px; font-size: 1.5rem;"></i>
                    {{ __('عرض المساهمين') }}
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header" style="background-color: var(--primary-color);">
                <h3 class="dashboard-card-title panel-title" style="margin: 0; display: flex; align-items: center; font-size: 2rem; color: white;">
                    <i class="fa fa-handshake" style="margin-left: 10px; font-size: 1.8rem;"></i>
                    {{ __('عروض البيع') }}
                </h3>
            </div>
            <div class="card-body">
                <p class="dashboard-card-description" style="display: flex; align-items: center; font-size: 1.6rem;">
                    <i class="fa fa-tags" style="margin-left: 10px; color: var(--primary-color); font-size: 1.7rem;"></i>
                    {{ __('إدارة عروض بيع الأسهم') }}
                </p>
                <a href="{{ route('sell-shares.index') }}" class="btn dashboard-btn" style="font-size: 1.6rem; background-color: var(--primary-color); border-color: var(--primary-color); color: white;">
                    <i class="fa fa-list" style="margin-right: 8px; font-size: 1.5rem;"></i>
                    {{ __('عرض العروض') }}
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header" style="background-color: var(--primary-color);">
                <h3 class="dashboard-card-title panel-title" style="margin: 0; display: flex; align-items: center; font-size: 2rem; color: white;">
                    <i class="fa fa-chart-line" style="margin-left: 10px; font-size: 1.8rem;"></i>
                    {{ __('معاملات الأسهم') }}
                </h3>
            </div>
            <div class="card-body">
                <p class="dashboard-card-description" style="display: flex; align-items: center; font-size: 1.6rem;">
                    <i class="fa fa-exchange-alt" style="margin-left: 10px; color: var(--primary-color); font-size: 1.7rem;"></i>
                    {{ __('إدارة معاملات الأسهم') }}
                </p>
                <a href="{{ route('shares-trans.index') }}" class="btn dashboard-btn" style="font-size: 1.6rem; background-color: var(--primary-color); border-color: var(--primary-color); color: white;">
                    <i class="fa fa-chart-bar" style="margin-right: 8px; font-size: 1.5rem;"></i>
                    {{ __('عرض المعاملات') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="statistics-title panel-title" style="margin: 0; display: flex; align-items: center; font-size: 2.2rem; color: var(--primary-color);">
                    <i class="fa fa-chart-pie" style="margin-left: 10px; color: var(--primary-color); font-size: 2rem;"></i>
                    {{ __('إحصائيات سريعة') }}
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-center statistics-card" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-hover)); color: white; border: none;">
                            <div class="card-body">
                                <i class="fa fa-users statistics-icon" style="font-size: 3.5rem; margin-bottom: 15px; opacity: 0.8;"></i>
                                <h3 class="statistics-number" style="margin: 10px 0; font-size: 3.5rem; font-weight: bold;">{{ $contributorsCount ?? 0 }}</h3>
                                <p class="statistics-label" style="margin: 0; font-size: 1.7rem;">{{ __('إجمالي المساهمين') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center statistics-card" style="background: linear-gradient(135deg, var(--success-color), #047857); color: white; border: none;">
                            <div class="card-body">
                                <i class="fa fa-handshake statistics-icon" style="font-size: 3.5rem; margin-bottom: 15px; opacity: 0.8;"></i>
                                <h3 class="statistics-number" style="margin: 10px 0; font-size: 3.5rem; font-weight: bold;">{{ $sellSharesCount ?? 0 }}</h3>
                                <p class="statistics-label" style="margin: 0; font-size: 1.7rem;">{{ __('عروض البيع النشطة') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center statistics-card" style="background: linear-gradient(135deg, var(--accent-color), #0284c7); color: white; border: none;">
                            <div class="card-body">
                                <i class="fa fa-exchange-alt statistics-icon" style="font-size: 3.5rem; margin-bottom: 15px; opacity: 0.8;"></i>
                                <h3 class="statistics-number" style="margin: 10px 0; font-size: 3.5rem; font-weight: bold;">{{ $transactionsCount ?? 0 }}</h3>
                                <p class="statistics-label" style="margin: 0; font-size: 1.7rem;">{{ __('إجمالي المعاملات') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center statistics-card" style="background: linear-gradient(135deg, var(--warning-color), #b45309); color: white; border: none;">
                            <div class="card-body">
                                <i class="fa fa-coins statistics-icon" style="font-size: 3.5rem; margin-bottom: 15px; opacity: 0.8;"></i>
                                <h3 class="statistics-number" style="margin: 10px 0; font-size: 3.5rem; font-weight: bold;">{{ $totalShares ?? 0 }}</h3>
                                <p class="statistics-label" style="margin: 0; font-size: 1.7rem;">{{ __('إجمالي الأسهم') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
