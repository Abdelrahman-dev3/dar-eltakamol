@if (Auth::check())
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            {{ __('المعاملات') }} <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="{{ route('share-trans-lines.index') }}">{{ __('تفاصيل المعاملات') }}</a></li>
            <li><a href="{{ route('payments.index') }}">{{ __('المدفوعات') }}</a></li>
            <li><a href="{{ route('shares-pos.index') }}">{{ __('طلبات الشراء') }}</a></li>
        </ul>
    </li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            {{ __('الأرباح') }} <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="{{ route('profits.index') }}">{{ __('أنواع الأرباح') }}</a></li>
            <li><a href="{{ route('users-profits.index') }}">{{ __('أرباح المستخدمين') }}</a></li>
        </ul>
    </li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            {{ __('الاستطلاعات') }} <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="{{ route('polls.index') }}">{{ __('الاستطلاعات') }}</a></li>
            <li><a href="{{ route('poll-options.index') }}">{{ __('خيارات الاستطلاعات') }}</a></li>
            <li><a href="{{ route('poll-answers.index') }}">{{ __('التصويتات') }}</a></li>
        </ul>
    </li>
    <li><a href="{{ route('shares-trans.index') }}">{{ __('معاملات الأسهم') }}</a></li>
    <li><a href="{{ route('sell-shares.index') }}">{{ __('عروض البيع') }}</a></li>
    <li><a href="{{ route('contributors.index') }}">{{ __('المساهمين') }}</a></li>
    <li><a href="{{ route('dashboard') }}">{{ __('الرئيسية') }}</a></li>

@endif
