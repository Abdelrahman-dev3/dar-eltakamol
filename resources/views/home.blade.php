@extends('layouts.app')

@section('title', __('الرئيسية'))

@section('content')
@if (Auth::check())
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 style="margin: 0; display: flex; align-items: center; font-size: 1.8rem;">
                        <i class="fa fa-chart-line" style="margin-left: 10px; color: var(--accent-color); font-size: 1.6rem;"></i>
                        {{ __('أهداف الشركة') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="chart col-sm-12">
                        <img alt="" class="col-sm-12" src="{{ route('home.goals-chart') }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 style="margin: 0; display: flex; align-items: center; font-size: 1.8rem;">
                        <i class="fa fa-coins" style="margin-left: 10px; color: var(--success-color); font-size: 1.6rem;"></i>
                        {{ __('أرباح المستخدمين') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="chart col-sm-12">
                        <img alt="" class="col-sm-12" src="{{ route('home.user-profit') }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header" style="padding: 0px;">
                    <div class="home-logo-container">
                        <img src="{{ asset('images/logos/لوجو دار التكامل ابيض.png') }}" alt="Dar Al-Takamol Holding Group" style="height: auto; width: 195px;">
                       
                    </div>
                </div>
                <div class="card-body">
                    <div style="line-height:35px;">
                        <p dir="RTL" style="margin-left:0in; margin-right:0in; text-align:right; font-size: 1.3rem;">
                            <span style="font-family:'Cairo',sans-serif">
                                <strong><span style="font-size:1.5rem; color: var(--primary-color); display: flex; align-items: center;">
                                    <i class="fa fa-hand-peace" style="margin-left: 10px; font-size: 1.4rem;"></i>
                                    السلام عليكم ورحمة الله و بركاته
                                </span></strong><br />
                                <strong><span style="font-size:1.3rem; color: var(--text-primary); display: flex; align-items: center;">
                                    <i class="fa fa-home" style="margin-left: 10px; color: var(--accent-color); font-size: 1.3rem;"></i>
                                    مرحبا بك في موقع مجموعة دار التكامل القابضة
                                </span></strong>
                                <strong><span style="font-size:1.3rem; color: var(--success-color); display: flex; align-items: center;">
                                    <i class="fa fa-users" style="margin-left: 10px; font-size: 1.3rem;"></i>
                                    ـ قسم المساهمين ـ
                                </span></strong>
                            </span>
                        </p>

            <p dir="RTL" style="margin-left:0in; margin-right:0in; text-align:right"><span style="font-size:11pt"><span style="font-family:'Cairo',sans-serif"><strong><span style="font-size:11.5pt"><span style="font-family:'Cairo',sans-serif"><span style="color:#212529">يسرنا أن نضع بين أيديكم نافذة تقنية لتسهيل عملية&nbsp;تناقل الأسهم ومعرفة بيناتكم في المجموعة , كما سيتم نشر نظام وشروط بيع وشراء الأسهم من خلال هذه النافذة في أقرب فرصة.</span></span></span></strong></span></span></p>

            <p dir="RTL" style="margin-left:0in; margin-right:0in; text-align:right">
                <br />
                <span style="color:#3498db"><span style="font-size:11pt"><span style="font-family:'Cairo',sans-serif"><strong><span style="font-size:11.5pt"><span style="background-color:white"><span style="font-family:'Cairo',sans-serif">إن لم تكن قد سجلت في الموقع من قبل فيسعدنا تسجيلك عن طريق الضغط على </span></span></span></strong></span></span></span><span style="font-size:11pt"><span style="font-family:'Cairo',sans-serif"><strong><span style="font-size:11.5pt"><span style="font-family:'Cairo',sans-serif"><em><u><a href="{{ route('register') }}"><span style="color:#3300ff">تسجيل دخول جديد</span></a></u></em></span></span></strong></span></span><span style="color:#3498db"><span style="font-size:11pt"><span style="font-family:'Cairo',sans-serif"><strong><span style="font-size:11.5pt"><span style="background-color:white"><span style="font-family:'Cairo',sans-serif">&nbsp;بالأعلى</span></span></span></strong><strong>&nbsp;</strong><strong><span style="font-size:11.5pt"><span style="background-color:white"><span style="font-family:'Cairo',sans-serif">, و الضغط على </span></span></span></strong></span></span></span><span style="font-size:11pt"><span style="font-family:'Cairo',sans-serif"><strong><span style="font-size:11.5pt"><span style="background-color:white"><span style="font-family:'Cairo',sans-serif"><u><em><a href="{{ route('login') }}"><span style="color:#3300cc">دخول الأعضاء</span></a></em></u></span></span></span></strong></span></span><span style="color:#3498db"><span style="font-size:11pt"><span style="font-family:'Cairo',sans-serif"><strong><span style="font-size:11.5pt"><span style="background-color:white"><span style="font-family:'Cairo',sans-serif"> في حال كنت سجلت مسبقا </span></span></span></strong></span></span></span><span style="color:#e74c3c"><span style="font-size:11pt"><span style="font-family:'Cairo',sans-serif"><strong><span style="font-size:11.5pt"><span style="background-color:white"><span style="font-family:'Cairo',sans-serif">( في حال كان دخولك عن طريق الجوال ستجد القائمة في أعلى يسار الصفحة)</span></span></span></strong></span></span></span><span style="color:#3498db"><span style="font-size:11pt"><span style="font-family:'Cairo',sans-serif"><strong><span style="font-size:11.5pt"><span style="background-color:white"><span style="font-family:'Cairo',sans-serif">.</span></span></span></strong></span></span></span>
            </p>

            <p dir="RTL" style="margin-left:0in; margin-right:0in; text-align:right">
                <br />
                <span style="color:#e74c3c"><span style="font-size:12px"><span style="font-family:'Cairo',sans-serif"><strong><span style="background-color:white"><span style="font-family:'Cairo',sans-serif">ملاحظة:</span></span></strong></span></span></span>
            </p>

            <p dir="RTL" style="margin-left:0in; margin-right:0in; text-align:right"><span style="color:#e74c3c"><span style="font-size:12px"><span style="font-family:'Cairo',sans-serif"><strong><span style="background-color:white"><span style="font-family:'Cairo',sans-serif">كون النظام جديد فنأمل في حال وجود مشاكل أو مقترحات مراسلتنا عبر بريد المجموعة .........&nbsp; شاكرين تعاونكم</span></span></strong></span></span></span></p>
        </div>
    </div>
@endif
@endsection