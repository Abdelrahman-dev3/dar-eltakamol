<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('المساهمين')) - {{ __('مجلس إدارة دار التكامل') }}</title>
    
    <!-- Meta tags -->
    <meta name="application-name" content="{{ __('إدارة شركة دار التكامل القابضة') }}">
    <meta name="description" content="{{ __('شركة دار التكامل القابضة هي مجموعة استثمارية تضم عدة شركات و لها أنشطة متعددة كالمقاولات و الاستثمار العقاري و تجارة الجملة و التجزئة في منتجات الحديد') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zain:wght@200;300;400;700;800;900&display=swap" rel="stylesheet" />
    <script>
        (function() {
            const fontSizeStorageKey = 'dar-takamol-font-size';
            const themeStorageKey = 'dar-takamol-theme';
            const allowedSizes = ['small', 'medium', 'large'];
            const allowedThemes = ['light', 'dark'];

            try {
                const savedSize = localStorage.getItem(fontSizeStorageKey);
                const initialSize = allowedSizes.includes(savedSize) ? savedSize : 'small';
                const savedTheme = localStorage.getItem(themeStorageKey);
                const initialTheme = allowedThemes.includes(savedTheme) ? savedTheme : 'light';
                document.documentElement.setAttribute('data-font-size', initialSize);
                document.documentElement.setAttribute('data-theme', initialTheme);
            } catch (error) {
                document.documentElement.setAttribute('data-font-size', 'small');
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Cairo Font CSS -->
    <link href="{{ asset('css/cairo-font.css') }}" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Font Awesome Icons -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://use.fontawesome.com">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.4.0/css/all.css" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- Simple Icon Test -->
    <style>
        .icon-test {
            font-size: 24px;
            margin: 10px;
            display: inline-block;
        }
        .icon-test:before {
            content: "★";
            color: #ff6b35;
        }
    </style>
    <style>
        :root {
            --app-font-family: 'Zain', sans-serif;
            --app-font-size-small: 10px;
            --app-font-size-medium: 13px;
            --app-font-size-large: 15px;

            /* Business Color Palette - Golden Theme */
            --primary-color: #aa863f;      /* Golden Primary */
            --primary-hover: #957a36;      /* Darker Golden */
            --secondary-color: #8b7355;    /* Warm Brown */
            --accent-color: #c4a85a;       /* Light Golden */
            --success-color: #059669;      /* Emerald Green */
            --warning-color: #d97706;      /* Amber */
            --danger-color: #dc2626;       /* Red */
            --sidebar-bg: #aa863f;         /* Golden Sidebar */
            --sidebar-hover: #957a36;      /* Darker Golden Hover */
            --content-bg: #f8fafc;         /* Light Gray */
            --card-bg: #ffffff;            /* White */
            --border-color: #e2e8f0;       /* Light Border */
            --text-primary: #1e293b;       /* Dark Text */
            --text-secondary: #64748b;     /* Gray Text */
            --text-light: #94a3b8;         /* Light Text */
            --text-white: #ffffff;         /* White Text */
            --golden-light: #d4af37;      /* Bright Golden */
            --golden-dark: #8b6914;       /* Dark Golden */
        }

        html {
            font-size: var(--app-font-size-small);
        }

        html[data-font-size="small"] {
            font-size: var(--app-font-size-small);
        }

        html[data-font-size="medium"] {
            font-size: var(--app-font-size-medium);
        }

        html[data-font-size="large"] {
            font-size: var(--app-font-size-large);
        }

        html[data-theme="dark"] {
            --primary-color: #8d6e2b;
            --primary-hover: #e6c985;
            --secondary-color: #c8b38a;
            --accent-color: #a68a52;
            --content-bg: #0f172a;
            --card-bg: #111827;
            --border-color: rgba(148, 163, 184, 0.18);
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-light: #94a3b8;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
        }

        body {
            font-family: var(--app-font-family) !important;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
            background-color: var(--content-bg);
            margin: 0;
            padding: 0;
            transition: overflow 0.3s ease;
        }

        body.sidebar-open {
            overflow: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 0;
            width: 292px;
            height: 100vh;
            background: linear-gradient(180deg, #ffffff 0%, #fcfaf4 100%);
            color: var(--primary-color);
            z-index: 1000;
            transition: transform 0.32s ease, box-shadow 0.32s ease;
            overflow-y: auto;
            overflow-x: hidden;
            border-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 1px solid var(--border-color);
            box-shadow: {{ app()->getLocale() == 'ar' ? '-10px' : '10px' }} 0 30px rgba(15, 23, 42, 0.08);
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 14px 16px 12px;
            border-bottom: 1px solid var(--border-color);
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            position: sticky;
            top: 0;
            z-index: 2;
        }

        .sidebar-brand {
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 12px 10px 10px;
            border-radius: 18px;
            background: linear-gradient(180deg, rgba(170, 134, 63, 0.12) 0%, rgba(170, 134, 63, 0.04) 100%);
            border: 1px solid rgba(170, 134, 63, 0.14);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);
        }

        .sidebar-brand:hover {
            color: var(--primary-hover);
            text-decoration: none;
            transform: translateY(-1px);
            box-shadow: 0 14px 30px rgba(170, 134, 63, 0.12);
        }

        .sidebar-brand-logo {
            width: 118px;
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }

        .sidebar-brand-title {
            font-size: 0.96rem;
            font-weight: 700;
            color: var(--text-primary);
            text-align: center;
            line-height: 1.2;
        }

        .sidebar-brand-subtitle {
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-secondary);
            text-align: center;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 10px 22px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .nav-item {
            margin: 6px 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            color: var(--primary-color);
            text-decoration: none;
            border-radius: 16px;
            transition: background-color 0.25s ease, color 0.25s ease, transform 0.25s ease, box-shadow 0.25s ease;
            font-weight: 600;
            font-size: 1.32rem;
            margin: 0;
            position: relative;
        }

        .nav-link:hover {
            background-color: rgba(170, 134, 63, 0.12);
            color: var(--primary-hover);
            text-decoration: none;
            transform: translateY(-1px);
        }

        .nav-link.active,
        .nav-link.dropdown-toggle.active {
            background: linear-gradient(135deg, var(--primary-color), #c39a47);
            color: white;
            box-shadow: 0 14px 24px rgba(170, 134, 63, 0.24);
        }

        .dropdown-menu {
            background: rgba(170, 134, 63, 0.06);
            border: 1px solid transparent;
            border-radius: 16px;
            margin: 8px 10px 0;
            box-shadow: none;
            padding: 0;
            display: block;
            position: relative;
            width: 100%;
            list-style: none;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transform: translateY(-6px);
            transition: max-height 0.34s ease, opacity 0.24s ease, transform 0.24s ease, padding 0.24s ease, border-color 0.24s ease, box-shadow 0.24s ease;
        }

        .dropdown-menu li {
            list-style: none;
        }

        .nav-item.is-open > .dropdown-menu {
            opacity: 1;
            transform: translateY(0);
            padding: 8px 0;
            border-color: rgba(170, 134, 63, 0.14);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        }

        .dropdown-menu li a {
            color: var(--primary-color);
            padding: 11px 14px;
            border-radius: 12px;
            margin: 4px 8px;
            font-size: 1.14rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .dropdown-menu li a:hover,
        .dropdown-menu li a.active {
            background-color: rgba(170, 134, 63, 0.1);
            color: var(--primary-hover);
        }

        .nav-link i {
            margin: 0;
            width: 24px;
            text-align: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .dropdown-menu li a i {
            width: 18px;
            text-align: center;
            flex-shrink: 0;
        }

        .nav-link .nav-link-label {
            flex: 1;
            min-width: 0;
        }

        .nav-link .nav-arrow {
            margin-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: auto;
            margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0;
            width: auto;
            font-size: 0.9rem;
            opacity: 0.72;
            transition: transform 0.28s ease, opacity 0.28s ease;
        }

        .nav-item.is-open > .nav-link .nav-arrow {
            transform: rotate(180deg);
            opacity: 1;
        }

        .nav-link.active i,
        .nav-link.dropdown-toggle.active i {
            color: inherit;
        }

        /* Main Content Area */
        .main-content {
            margin-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 292px;
            min-height: 100vh;
            padding: 0;
        }

        /* Main Content Area for Non-Authenticated Users */
        .main-content.no-auth {
            margin-left: 0;
            margin-right: 0;
        }

        /* Main Content Area when no sidebar (not authenticated) */
        .main-content.no-sidebar {
            margin-left: 0;
            margin-right: 0;
        }

        .main-content.no-sidebar .content-header {
            text-align: center;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: white;
        }

        .main-content.no-sidebar .content-header h1 {
            color: white;
        }
        
        .main-content.no-sidebar .content-body {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 30px;
        }

        .content-header {
            background-color: var(--card-bg);
            padding: 20px 30px;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .content-body {
            padding: 30px;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 900;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 24px;
            background: rgba(255, 255, 255, 0.88);
            border-bottom: 1px solid rgba(170, 134, 63, 0.12);
            backdrop-filter: blur(16px);
        }

        .topbar-links {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            min-width: 0;
        }

        .topbar-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 42px;
            padding: 10px 14px;
            border-radius: 14px;
            color: var(--text-secondary);
            text-decoration: none;
            background: rgba(170, 134, 63, 0.06);
            border: 1px solid transparent;
            font-size: 1rem;
            font-weight: 700;
            transition: all 0.25s ease;
        }

        .topbar-link:hover,
        .topbar-link.is-active {
            color: var(--primary-color);
            background: rgba(170, 134, 63, 0.12);
            border-color: rgba(170, 134, 63, 0.14);
            text-decoration: none;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .topbar-action-group {
            position: relative;
        }

        .topbar-icon-btn,
        .topbar-profile-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            min-height: 46px;
            border: 1px solid rgba(170, 134, 63, 0.14);
            background: rgba(255, 255, 255, 0.92);
            color: var(--text-primary);
            border-radius: 16px;
            padding: 10px 14px;
            font-size: 1rem;
            font-weight: 700;
            transition: all 0.25s ease;
        }

        .topbar-icon-btn:hover,
        .topbar-profile-btn:hover,
        .topbar-action-group.is-open > .topbar-icon-btn,
        .topbar-action-group.is-open > .topbar-profile-btn {
            color: var(--primary-color);
            border-color: rgba(170, 134, 63, 0.26);
            transform: translateY(-1px);
        }

        .topbar-theme-btn.is-dark {
            background: linear-gradient(135deg, #111827, #1f2937);
            color: #f8fafc;
            border-color: rgba(148, 163, 184, 0.24);
        }

        .topbar-profile-avatar {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), #c39a47);
            color: #fff;
            font-size: 0.92rem;
            font-weight: 800;
        }

        .topbar-panel {
            position: absolute;
            top: calc(100% + 10px);
            {{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0;
            min-width: 270px;
            padding: 14px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.98);
            border: 1px solid rgba(170, 134, 63, 0.14);
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.12);
            opacity: 0;
            visibility: hidden;
            transform: translateY(8px);
            transition: all 0.25s ease;
        }

        .topbar-action-group.is-open > .topbar-panel {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .topbar-panel-title {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            color: var(--text-primary);
            font-size: 1.04rem;
            font-weight: 800;
        }

        .topbar-panel-note {
            display: block;
            margin-top: 10px;
            color: var(--text-light);
            font-size: 0.88rem;
        }

        .topbar-profile-menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .topbar-profile-link {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 44px;
            padding: 10px 12px;
            border-radius: 14px;
            color: var(--text-primary);
            text-decoration: none;
            background: rgba(170, 134, 63, 0.05);
            transition: all 0.25s ease;
        }

        .topbar-profile-link:hover {
            color: var(--primary-color);
            text-decoration: none;
            background: rgba(170, 134, 63, 0.10);
        }

        /* Cards */
        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 20px;
            border-radius: 12px 12px 0 0;
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            font-weight: 500;
            padding: 10px 20px;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
            border-radius: 8px;
        }

        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
            border-radius: 8px;
        }

        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
            border-radius: 8px;
        }
        
        .btn-info {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            border-radius: 8px;
        }
        
        .btn-default {
            background-color: var(--text-light);
            border-color: var(--border-color);
            color: var(--text-primary);
            border-radius: 8px;
        }
        
        /* Button Sizes - Standardized */
        .btn-xs {
            padding: 8px 14px;
            font-size: 1.2rem;
            line-height: 1.4;
            border-radius: 6px;
        }
        
        .btn-sm {
            padding: 10px 18px;
            font-size: 1.3rem;
            line-height: 1.4;
            border-radius: 6px;
        }

        /* Table Action Buttons - Standardized */
        .table .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
        }
        
        .table .btn-group .btn {
            margin: 0;
            white-space: nowrap;
            flex-shrink: 0;
        }
        
        .table .btn-group .btn i {
            margin-right: 6px;
        }
        
        .table .btn-group form {
            display: inline-block;
            margin: 0;
        }
        
        .table .btn-group form .btn {
            margin: 0;
        }

        /* Tables */
        .table {
            background-color: var(--card-bg);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table th {
            background-color: var(--secondary-color);
            color: white;
            font-weight: 600;
            border: none;
            padding: 15px;
        }

        .table td {
            padding: 15px;
            border-color: var(--border-color);
        }
        
        .table td .btn-group {
            justify-content: flex-start;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8fafc;
        }

        /* Pagination */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px;
            margin: 24px 0 0;
            padding: 0;
        }

        .pagination > li {
            display: inline-flex;
        }

        .pagination > li > a,
        .pagination > li > span {
            min-width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 15px;
            border-radius: 14px !important;
            border: 1px solid rgba(170, 134, 63, 0.14);
            background: rgba(255, 255, 255, 0.96);
            color: var(--text-primary);
            font-size: 0.98rem;
            font-weight: 800;
            line-height: 1;
            text-decoration: none;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.05);
            transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease, background-color 0.22s ease, color 0.22s ease;
        }

        .pagination > li > a:hover,
        .pagination > li > span:hover {
            color: var(--primary-color);
            background: rgba(170, 134, 63, 0.10);
            border-color: rgba(170, 134, 63, 0.28);
            transform: translateY(-1px);
            box-shadow: 0 14px 24px rgba(15, 23, 42, 0.08);
        }

        .pagination > li:first-child > a,
        .pagination > li:first-child > span,
        .pagination > li:last-child > a,
        .pagination > li:last-child > span {
            min-width: 112px;
            padding: 0 18px;
            background: linear-gradient(135deg, rgba(170, 134, 63, 0.12), rgba(170, 134, 63, 0.04));
            border-color: rgba(170, 134, 63, 0.18);
            color: var(--primary-color);
        }

        .pagination > .active > span,
        .pagination > .active > span:hover,
        .pagination > .active > span:focus,
        .pagination > .active > a,
        .pagination > .active > a:hover,
        .pagination > .active > a:focus {
            background: linear-gradient(135deg, var(--primary-color), #c49b48);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 16px 28px rgba(170, 134, 63, 0.24);
        }

        .pagination > .disabled > span,
        .pagination > .disabled > span:hover,
        .pagination > .disabled > span:focus,
        .pagination > .disabled > a,
        .pagination > .disabled > a:hover,
        .pagination > .disabled > a:focus {
            background: rgba(148, 163, 184, 0.12);
            border-color: rgba(148, 163, 184, 0.14);
            color: var(--text-light);
            box-shadow: none;
            cursor: not-allowed;
            transform: none;
        }

        /* Alerts */
        .alert {
            border-radius: 8px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        /* Forms */
        .form-control {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(30, 58, 138, 0.25);
        }

        /* User Profile Section */
        .user-profile {
            padding: 16px;
            border-top: 1px solid var(--border-color);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96) 0%, #fff 100%);
            flex-shrink: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            padding: 12px;
            border-radius: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            border: 1px solid rgba(170, 134, 63, 0.12);
            background: rgba(170, 134, 63, 0.04);
        }

        .user-info:hover {
            background-color: rgba(170, 134, 63, 0.1);
            transform: translateY(-1px);
        }

        .font-size-card {
            margin-top: 12px;
            padding: 12px;
            border-radius: 16px;
            border: 1px solid rgba(170, 134, 63, 0.12);
            background: rgba(170, 134, 63, 0.05);
        }

        .font-size-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
            color: var(--text-primary);
            font-size: 1.08rem;
            font-weight: 700;
        }

        .font-size-card-header i {
            color: var(--primary-color);
        }

        .font-size-options {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
        }

        .font-size-option {
            border: 1px solid rgba(170, 134, 63, 0.14);
            background: #fff;
            color: var(--text-secondary);
            border-radius: 12px;
            min-height: 42px;
            padding: 8px 10px;
            font-size: 0.98rem;
            font-weight: 700;
            transition: all 0.25s ease;
        }

        .font-size-option:hover {
            color: var(--primary-color);
            border-color: rgba(170, 134, 63, 0.34);
            transform: translateY(-1px);
        }

        .font-size-option.is-active {
            background: linear-gradient(135deg, var(--primary-color), #c39a47);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 12px 20px rgba(170, 134, 63, 0.20);
        }

        .font-size-option[data-font-size-option="small"] {
            font-size: 0.90rem;
        }

        .font-size-option[data-font-size-option="medium"] {
            font-size: 1rem;
        }

        .font-size-option[data-font-size-option="large"] {
            font-size: 1.08rem;
        }

        html[data-theme="dark"] .sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
            box-shadow: {{ app()->getLocale() == 'ar' ? '-10px' : '10px' }} 0 30px rgba(2, 6, 23, 0.45);
        }

        html[data-theme="dark"] .sidebar-header,
        html[data-theme="dark"] .user-profile,
        html[data-theme="dark"] .topbar,
        html[data-theme="dark"] .topbar-panel,
        html[data-theme="dark"] .topbar-icon-btn,
        html[data-theme="dark"] .topbar-profile-btn,
        html[data-theme="dark"] .font-size-option,
        html[data-theme="dark"] .content-header,
        html[data-theme="dark"] .card,
        html[data-theme="dark"] .table,
        html[data-theme="dark"] .footer {
            background-color: rgba(17, 24, 39, 0.96);
            color: var(--text-primary);
        }

        html[data-theme="dark"] .sidebar-brand,
        html[data-theme="dark"] .user-info,
        html[data-theme="dark"] .font-size-card,
        html[data-theme="dark"] .topbar-link,
        html[data-theme="dark"] .topbar-profile-link,
        html[data-theme="dark"] .dropdown-menu {
            background: rgba(148, 163, 184, 0.08);
            border-color: rgba(148, 163, 184, 0.16);
        }

        html[data-theme="dark"] .nav-link:hover,
        html[data-theme="dark"] .dropdown-menu li a:hover,
        html[data-theme="dark"] .dropdown-menu li a.active,
        html[data-theme="dark"] .topbar-link:hover,
        html[data-theme="dark"] .topbar-link.is-active,
        html[data-theme="dark"] .topbar-profile-link:hover,
        html[data-theme="dark"] .font-size-option:hover {
            background: rgba(213, 179, 106, 0.12);
        }

        html[data-theme="dark"] .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(148, 163, 184, 0.06);
        }

        html[data-theme="dark"] .pagination > li > a,
        html[data-theme="dark"] .pagination > li > span {
            background: rgba(15, 23, 42, 0.92);
            border-color: rgba(148, 163, 184, 0.14);
            color: var(--text-primary);
            box-shadow: 0 10px 20px rgba(2, 6, 23, 0.24);
        }

        html[data-theme="dark"] .pagination > li > a:hover,
        html[data-theme="dark"] .pagination > li > span:hover {
            background: rgba(213, 179, 106, 0.10);
            border-color: rgba(213, 179, 106, 0.24);
            color: #f7d58e;
        }

        html[data-theme="dark"] .pagination > li:first-child > a,
        html[data-theme="dark"] .pagination > li:first-child > span,
        html[data-theme="dark"] .pagination > li:last-child > a,
        html[data-theme="dark"] .pagination > li:last-child > span {
            background: linear-gradient(135deg, rgba(213, 179, 106, 0.16), rgba(213, 179, 106, 0.06));
            border-color: rgba(213, 179, 106, 0.22);
            color: #f7d58e;
        }

        html[data-theme="dark"] .pagination > .disabled > span,
        html[data-theme="dark"] .pagination > .disabled > span:hover,
        html[data-theme="dark"] .pagination > .disabled > span:focus,
        html[data-theme="dark"] .pagination > .disabled > a,
        html[data-theme="dark"] .pagination > .disabled > a:hover,
        html[data-theme="dark"] .pagination > .disabled > a:focus {
            background: rgba(51, 65, 85, 0.7);
            border-color: rgba(71, 85, 105, 0.5);
            color: rgba(148, 163, 184, 0.8);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background-color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 12px;
            font-weight: bold;
            color: white;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX({{ app()->getLocale() == 'ar' ? '100%' : '-100%' }});
                width: 280px;
                max-width: 85%;
                z-index: 1050;
                box-shadow: {{ app()->getLocale() == 'ar' ? '-4px' : '4px' }} 0 20px rgba(0, 0, 0, 0.3);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                margin-right: 0;
                padding: 15px;
            }

            .main-content.no-auth {
                margin-left: 0;
                margin-right: 0;
                padding: 15px;
            }

            .mobile-toggle {
                display: block;
                position: fixed;
                top: 20px;
                {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 20px;
                z-index: 1001;
                background-color: var(--primary-color);
                color: white;
                border: none;
                padding: 15px 18px;
                border-radius: 10px;
                cursor: pointer;
                font-size: 1.6rem;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                transition: all 0.3s ease;
            }

            .mobile-toggle:hover {
                background-color: var(--primary-hover);
                transform: scale(1.05);
            }

            .mobile-toggle:active {
                transform: scale(0.95);
            }

            .topbar {
                margin-top: 70px;
                padding: 14px 15px;
                flex-direction: column;
                align-items: stretch;
            }

            .topbar-links {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 4px;
            }

            .topbar-actions {
                justify-content: space-between;
            }

            .topbar-action-group {
                flex: 1 1 auto;
            }

            .topbar-icon-btn,
            .topbar-profile-btn {
                width: 100%;
                justify-content: center;
            }

            .topbar-panel {
                min-width: 100%;
                width: 100%;
            }

            /* Auth page mobile adjustments */
            .auth-page {
                padding: 10px;
                min-height: 100vh;
            }

            .auth-container {
                padding: 25px 20px;
                margin: 5px;
                border-radius: 15px;
            }

            .auth-title {
                font-size: 1.8rem;
                margin-bottom: 10px;
            }

            .auth-subtitle {
                font-size: 1.1rem;
                margin-bottom: 25px;
            }

            .form-control {
                padding: 20px 25px;
                font-size: 1.4rem;
                border-radius: 12px;
            }

            .btn-modern {
                padding: 20px 25px;
                font-size: 1.4rem;
                border-radius: 12px;
            }

            .form-label {
                font-size: 1.3rem;
                margin-bottom: 8px;
            }

            .input-icon {
                font-size: 1.5rem;
            }

            /* Dashboard mobile adjustments */
            .card {
                margin-bottom: 20px;
                border-radius: 15px;
            }

            .card-header {
                padding: 20px;
                border-radius: 15px 15px 0 0;
            }

            .card-body {
                padding: 20px;
            }

            /* Statistics cards mobile */
            .col-md-3 {
                margin-bottom: 15px;
            }

            /* Footer mobile */
            .footer {
                padding: 20px 15px;
                text-align: center;
            }

            .footer p {
                font-size: 1rem;
                line-height: 1.5;
            }
        }

        /* Tablet Responsive */
        @media (min-width: 769px) and (max-width: 1024px) {
            .sidebar {
                width: 252px;
            }

            .main-content {
                margin-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 252px;
                padding: 25px;
            }

            .sidebar-brand img {
                width: 112px;
            }

            .nav-link {
                font-size: 1.22rem;
                padding: 13px 14px;
            }

            .dropdown-menu li a {
                font-size: 1.05rem;
                padding: 10px 12px;
            }

            .auth-container {
                max-width: 500px;
                padding: 45px;
                margin: 20px auto;
            }

            .auth-title {
                font-size: 2rem;
            }

            .auth-subtitle {
                font-size: 1.2rem;
            }

            .form-control {
                padding: 18px 22px;
                font-size: 1.3rem;
            }

            .btn-modern {
                padding: 18px 22px;
                font-size: 1.3rem;
            }

            .form-label {
                font-size: 1.2rem;
            }

            .input-icon {
                font-size: 1.4rem;
            }

            /* Dashboard tablet adjustments */
            .card {
                margin-bottom: 25px;
            }

            .card-header {
                padding: 25px;
            }

            .card-body {
                padding: 25px;
            }

            /* Statistics cards tablet */
            .col-md-3 {
                margin-bottom: 20px;
            }

            /* Footer tablet */
            .footer {
                padding: 25px 20px;
            }
        }

        /* Mobile Sidebar Backdrop */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-backdrop.show {
            display: block;
            opacity: 1;
        }

        @media (max-width: 768px) {
            .sidebar-backdrop {
                display: block;
            }
        }

        /* Small Mobile Responsive */
        @media (max-width: 480px) {
            .sidebar {
                width: 260px;
                max-width: 90%;
            }

            .auth-container {
                padding: 20px 15px;
                margin: 5px;
            }

            .auth-title {
                font-size: 1.6rem;
            }

            .auth-subtitle {
                font-size: 1rem;
            }

            .form-control {
                padding: 18px 20px;
                font-size: 1.3rem;
            }

            .btn-modern {
                padding: 18px 20px;
                font-size: 1.3rem;
            }

            .form-label {
                font-size: 1.2rem;
            }

            .input-icon {
                font-size: 1.4rem;
            }

            .mobile-toggle {
                padding: 12px 15px;
                font-size: 1.4rem;
                top: 15px;
                {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 15px;
            }

            .main-content {
                padding: 10px;
            }

            .content-header {
                padding: 15px 20px;
            }

            .content-header h1 {
                font-size: 1.5rem;
            }

            .card-header {
                padding: 15px;
            }

            .card-body {
                padding: 15px;
            }

            .footer {
                padding: 15px 10px;
            }

            .nav-link {
                font-size: 1.3rem;
                padding: 15px 18px;
            }

            .dropdown-menu li a {
                font-size: 1.2rem;
                padding: 10px 15px;
            }
        }

        /* Large Desktop Responsive */
        @media (min-width: 1200px) {

            .auth-container {
                max-width: 600px;
                padding: 60px;
            }

            .auth-title {
                font-size: 2.5rem;
            }

            .auth-subtitle {
                font-size: 1.3rem;
            }

            .form-control {
                padding: 22px 28px;
                font-size: 1.3rem;
            }

            .btn-modern {
                padding: 22px 28px;
                font-size: 1.3rem;
            }

            .form-label {
                font-size: 1.3rem;
            }

            .input-icon {
                font-size: 1.5rem;
            }

            .card {
                margin-bottom: 30px;
            }

            .card-header {
                padding: 30px;
            }

            .card-body {
                padding: 30px;
            }
        }

        .mobile-toggle {
            display: none;
        }

        /* Custom Scrollbar for Sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--primary-hover);
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(170, 134, 63, 0.3);
            border-radius: 2px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(170, 134, 63, 0.5);
        }

        /* Smooth scrolling */
        .sidebar,
        .sidebar-nav {
            scroll-behavior: smooth;
        }

        /* Touch-friendly tap highlighting */
        .nav-link,
        .dropdown-menu li a,
        .mobile-toggle,
        .user-info {
            -webkit-tap-highlight-color: rgba(170, 134, 63, 0.2);
            tap-highlight-color: rgba(170, 134, 63, 0.2);
        }

        /* Footer */
        .footer {
            background-color: var(--card-bg);
            border-top: 1px solid var(--border-color);
            padding: 20px 0;
            margin-top: 50px;
            text-align: center;
            color: var(--text-secondary);
        }
        
        /* RTL Support */
        .rtl {
            direction: rtl;
            text-align: right;
        }
        
        .ltr {
            direction: ltr;
            text-align: left;
        }

        /* Loading States */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        /* Authentication Pages Styling */
        .auth-page {
            background: linear-gradient(135deg, var(--content-bg) 0%, #f1f5f9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(170, 134, 63, 0.15);
            padding: 50px 40px;
            width: 100%;
            max-width: 480px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(170, 134, 63, 0.2);
        }

        .auth-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }

        .auth-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .auth-logo {
            margin-bottom: 25px;
        }

        .logo-placeholder {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 8px 20px rgba(170, 134, 63, 0.3);
            transition: transform 0.3s ease;
        }

        .logo-placeholder:hover {
            transform: scale(1.05);
        }

        .logo-icon {
            font-size: 2.5rem;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .auth-title {
            font-size: 2.6rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 12px 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .auth-subtitle {
            color: var(--text-secondary);
            font-size: 1.5rem;
            line-height: 1.5;
            margin: 0;
        }

        /* Modern Form Styling */
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 12px;
            font-size: 1.7rem;
        }

        .form-control {
            width: 100%;
            padding: 18px 20px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 1.7rem;
            transition: all 0.3s ease;
            background-color: var(--card-bg);
            color: var(--text-primary);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.1);
            transform: translateY(-2px);
        }

        .form-control::placeholder {
            color: var(--text-light);
            font-weight: 400;
        }

        /* Input with Icon */
        .input-group {
            position: relative;
            width: 100%;
            display: block;
        }

        .input-icon {
            position: absolute;
            {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 1.3rem;
        }

        .input-group .form-control {
            padding-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 55px;
            width: 100%;
            display: block;
        }

        /* Modern Button Styling */
        .btn-modern {
            width: 100%;
            padding: 18px 20px;
            border: none;
            border-radius: 12px;
            font-size: 1.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-modern:hover::before {
            left: 100%;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: white;
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(30, 58, 138, 0.3);
        }

        .btn-secondary-modern {
            background: var(--card-bg);
            color: var(--text-primary);
            border: 2px solid var(--border-color);
        }

        .btn-secondary-modern:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        /* Form Links */
        .form-links {
            text-align: center;
            margin-top: 25px;
        }

        .form-links a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .form-links a:hover {
            color: var(--primary-hover);
        }

        /* Divider */
        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
            color: var(--text-light);
            font-size: 1rem;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border-color);
        }

        .divider span {
            background: var(--card-bg);
            padding: 0 15px;
        }

        /* Checkbox Styling */
        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 10px;
            accent-color: var(--primary-color);
        }

        .form-check-label {
            color: var(--text-secondary);
            font-size: 1rem;
            cursor: pointer;
        }

        /* Error States */
        .form-control.is-invalid {
            border-color: var(--danger-color);
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
        }

        .invalid-feedback {
            color: var(--danger-color);
            font-size: 0.95rem;
            margin-top: 5px;
            display: block;
        }

        /* Success States */
        .form-control.is-valid {
            border-color: var(--success-color);
            box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1);
        }

        /* Loading Button */
        .btn-loading {
            position: relative;
            color: transparent !important;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid transparent;
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Common Button Icons */
        .btn i {
            margin-right: 8px;
        }

        .btn i:only-child {
            margin-right: 0;
        }

        /* Action Button Icons */
        .btn-create {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-create:hover {
            background-color: #047857;
            border-color: #047857;
        }

        .btn-edit {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
        }

        .btn-edit:hover {
            background-color: #b45309;
            border-color: #b45309;
        }

        .btn-delete {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .btn-delete:hover {
            background-color: #b91c1c;
            border-color: #b91c1c;
        }

        .btn-view {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .btn-view:hover {
            background-color: #a68b4a;
            border-color: #a68b4a;
        }

        /* Table Action Icons */
        .table-actions {
            display: flex;
            gap: 5px;
        }

        .table-actions .btn {
            padding: 6px 10px;
            font-size: 1rem;
        }

        /* Status Icons */
        .status-active {
            color: var(--success-color);
        }

        .status-inactive {
            color: var(--danger-color);
        }

        .status-pending {
            color: var(--warning-color);
        }

        /* Data Display Icons */
        .data-icon {
            font-size: 1.4rem;
            margin-right: 8px;
            color: var(--primary-color);
        }

        .data-icon.success {
            color: var(--success-color);
        }

        .data-icon.warning {
            color: var(--warning-color);
        }

        .data-icon.danger {
            color: var(--danger-color);
        }

        /* Golden Theme Enhancements */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .btn-primary:focus {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            box-shadow: 0 0 0 0.2rem rgba(170, 134, 63, 0.25);
        }

        .btn-primary:active {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        /* Golden gradient backgrounds */
        .golden-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        }

        .golden-gradient-dark {
            background: linear-gradient(135deg, var(--primary-hover), var(--primary-color));
        }

        /* Golden text colors */
        .text-golden {
            color: var(--primary-color);
        }

        .text-golden-light {
            color: var(--accent-color);
        }

        /* Golden borders */
        .border-golden {
            border-color: var(--primary-color);
        }

        /* Logo Styling */
        .auth-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .auth-logo img {
            display: block;
            margin: 0 auto 15px auto;
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .sidebar-brand {
            text-align: center;
        }

        .sidebar-brand img {
            display: block;
            margin: 0 auto 0px auto;
            max-width: 100%;
            height: auto;
            border-radius: 0;
        }

        .sidebar-brand:hover img {
            transform: scale(1.04);
            transition: transform 0.3s ease;
        }

        /* Home page logo styling */
        .home-logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .home-logo-container img {
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(170, 134, 63, 0.2);
        }

        a{
            text-decoration: none !important;
        }

        /* Logo responsive adjustments */
        @media (max-width: 768px) {
            .auth-logo img {
                height: 60px !important;
            }
            
            .sidebar-brand img {
                width: 96px !important;
                height: auto !important;
            }

            .home-logo-container {
                flex-direction: column;
                text-align: center;
            }

            .home-logo-container img {
                margin-left: 0 !important;
            }
        }

        @media (max-width: 480px) {
            .auth-logo img {
                height: 50px !important;
            }
            
            .sidebar-brand img {
                width: 88px !important;
                height: auto !important;
            }
        }

        /* Font Awesome Fallback */
        .fa {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "FontAwesome", sans-serif !important;
            font-weight: 900;
            font-style: normal;
            font-variant: normal;
            text-rendering: auto;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Bootstrap Icons */
        .bi {
            font-family: "bootstrap-icons" !important;
            font-style: normal;
            font-variant: normal;
            text-rendering: auto;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Ensure icons are visible */
        i.fa, i.bi {
            display: inline-block;
            width: auto;
            height: auto;
        }

        /* Icon Fallbacks using Unicode */
        .fa-building:before { content: "🏢"; }
        .fa-envelope:before { content: "✉️"; }
        .fa-lock:before { content: "🔒"; }
        .fa-sign-in:before { content: "🔑"; }
        .fa-user-plus:before { content: "👤+"; }
        .fa-user:before { content: "👤"; }
        .fa-bars:before { content: "☰"; }
        .fa-tachometer-alt:before { content: "📊"; }
        .fa-users-cog:before { content: "👥"; }
        .fa-chart-line:before { content: "📈"; }
        .fa-handshake:before { content: "🤝"; }
        .fa-exchange-alt:before { content: "🔄"; }
        .fa-coins:before { content: "💰"; }
        .fa-chart-pie:before { content: "📊"; }
        .fa-users:before { content: "👥"; }
        .fa-eye:before { content: "👁️"; }
        .fa-list:before { content: "📋"; }
        .fa-chart-bar:before { content: "📊"; }
        .fa-user-check:before { content: "✅"; }
        .fa-tags:before { content: "🏷️"; }
        .fa-list-alt:before { content: "📄"; }
        .fa-credit-card:before { content: "💳"; }
        .fa-shopping-cart:before { content: "🛒"; }
        .fa-user-dollar:before { content: "💵"; }
        .fa-poll-h:before { content: "📊"; }
        .fa-vote-yea:before { content: "🗳️"; }
        .fa-list-check:before { content: "✅"; }
        .fa-comments:before { content: "💬"; }
        .fa-user-cog:before { content: "⚙️"; }
        .fa-sign-out-alt:before { content: "🚪"; }
        .fa-check-circle:before { content: "✅"; }
        .fa-exclamation-circle:before { content: "⚠️"; }
        .fa-copyright:before { content: "©"; }
        .fa-heart:before { content: "❤️"; }
        .fa-hand-peace:before { content: "✌️"; }
        .fa-home:before { content: "🏠"; }
        .fa-chevron-down:before { content: "▼"; }
        .fa-video:before { content: "📹"; }
        .fa-gavel:before { content: "⚖️"; }
        .fa-folder-open:before { content: "📂"; }
        .fa-bullhorn:before { content: "📣"; }
        .fa-tags:before { content: "🏷️"; }

        /* Show Bootstrap Icons if Font Awesome fails */
        .fa:not([class*="fa-"]) {
            display: none;
        }

        .fa:not([class*="fa-"]) + .bi {
            display: inline-block !important;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Mobile Toggle Button (Only show when authenticated) -->
    @if (Auth::check())
                    <button class="mobile-toggle" onclick="toggleSidebar()">
                        <i class="fa fa-bars"></i>
                        <i class="bi bi-list" style="display: none;"></i>
                </button>
    @endif

    <!-- Sidebar (Only show when authenticated) -->
    @if (Auth::check())
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('dashboard') }}" class="sidebar-brand">
                    <img src="{{ asset('images/logos/لوجو دار التكامل ذهبي.png') }}" alt="Dar Al-Takamol Holding Group" class="sidebar-brand-logo">
                </a>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fa fa-dashboard"></i>
                        <span class="nav-link-label">{{ __('لوحة التحكم') }}</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('contributors.index') }}" class="nav-link {{ request()->routeIs('contributors.*') ? 'active' : '' }}">
                        <i class="fa fa-users-cog"></i>
                        <span class="nav-link-label">{{ __('المساهمين') }}</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('shares-trans.index') }}" class="nav-link {{ request()->routeIs('shares-trans.*') ? 'active' : '' }}">
                        <i class="fa fa-line-chart"></i>
                        <span class="nav-link-label">{{ __('معاملات الأسهم') }}</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('sell-shares.index') }}" class="nav-link {{ request()->routeIs('sell-shares.*') ? 'active' : '' }}">
                        <i class="fa fa-handshake"></i>
                        <span class="nav-link-label">{{ __('عروض البيع') }}</span>
                    </a>
                </div>

                <!-- Transactions Dropdown -->
                <div class="nav-item {{ request()->routeIs('share-trans-lines.*') || request()->routeIs('modify.*') || request()->routeIs('payments.*') || request()->routeIs('shares-pos.*') ? 'has-active-child is-open' : '' }}">
                    <a href="#" class="nav-link dropdown-toggle {{ request()->routeIs('share-trans-lines.*') || request()->routeIs('modify.*') || request()->routeIs('payments.*') || request()->routeIs('shares-pos.*') ? 'active' : '' }}" data-toggle="dropdown" aria-expanded="{{ request()->routeIs('share-trans-lines.*') || request()->routeIs('modify.*') || request()->routeIs('payments.*') || request()->routeIs('shares-pos.*') ? 'true' : 'false' }}">
                        <i class="fa fa-exchange-alt"></i>
                        <span class="nav-link-label">{{ __('المعاملات') }}</span>
                        <i class="fa fa-chevron-down nav-arrow" aria-hidden="true"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('share-trans-lines.index') }}" class="{{ request()->routeIs('share-trans-lines.*') ? 'active' : '' }}"><i class="fa fa-list-alt"></i>{{ __('تفاصيل المعاملات') }}</a></li>
                        <li><a href="{{ route('modify.index') }}" class="{{ request()->routeIs('modify.*') ? 'active' : '' }}"><i class="fa fa-list-alt"></i>{{ __('ملاحظات التعديلات') }}</a></li>
                        <li><a href="{{ route('payments.index') }}" class="{{ request()->routeIs('payments.*') ? 'active' : '' }}"><i class="fa fa-credit-card"></i>{{ __('المدفوعات') }}</a></li>
                        <li><a href="{{ route('shares-pos.index') }}" class="{{ request()->routeIs('shares-pos.*') ? 'active' : '' }}"><i class="fa fa-shopping-cart"></i>{{ __('طلبات الشراء') }}</a></li>
                    </ul>
                </div>

                <!-- Polls Dropdown -->
                <div class="nav-item {{ request()->routeIs('polls.*') || request()->routeIs('poll-options.*') || request()->routeIs('poll-answers.*') ? 'has-active-child is-open' : '' }}">
                    <a href="#" class="nav-link dropdown-toggle {{ request()->routeIs('polls.*') || request()->routeIs('poll-options.*') || request()->routeIs('poll-answers.*') ? 'active' : '' }}" data-toggle="dropdown" aria-expanded="{{ request()->routeIs('polls.*') || request()->routeIs('poll-options.*') || request()->routeIs('poll-answers.*') ? 'true' : 'false' }}">
                        <i class="fa fa-list-alt"></i>
                        <span class="nav-link-label">{{ __('الاستطلاعات') }}</span>
                        <i class="fa fa-chevron-down nav-arrow" aria-hidden="true"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('polls.index') }}" class="{{ request()->routeIs('polls.*') ? 'active' : '' }}"><i class="fa fa-vote-yea"></i>{{ __('الاستطلاعات') }}</a></li>
                        <li><a href="{{ route('poll-options.index') }}" class="{{ request()->routeIs('poll-options.*') ? 'active' : '' }}"><i class="fa fa-list-check"></i>{{ __('خيارات الاستطلاعات') }}</a></li>
                        <li><a href="{{ route('poll-answers.index') }}" class="{{ request()->routeIs('poll-answers.*') ? 'active' : '' }}"><i class="fa fa-comments"></i>{{ __('إجابات الاستطلاعات') }}</a></li>
                    </ul>
                </div>

                <!-- Meetings -->
                <div class="nav-item">
                    <a href="{{ route('meetings.index') }}" class="nav-link {{ request()->routeIs('meetings.*') ? 'active' : '' }}">
                        <i class="fa fa-video"></i>
                        <span class="nav-link-label">{{ __('الاجتماعات') }}</span>
                    </a>
                </div>

                <!-- Regulations -->
                <div class="nav-item">
                    <a href="{{ route('regulations.index') }}" class="nav-link {{ request()->routeIs('regulations.*') ? 'active' : '' }}">
                        <i class="fa fa-gavel"></i>
                        <span class="nav-link-label">{{ __('اللوائح') }}</span>
                    </a>
                </div>

                <!-- Documents -->
                <div class="nav-item">
                    <a href="{{ route('documents.index') }}" class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                        <i class="fa fa-folder-open"></i>
                        <span class="nav-link-label">{{ __('الملفات') }}</span>
                    </a>
                </div>

                <!-- Circulars -->
                <div class="nav-item">
                    <a href="{{ route('circulars.index') }}" class="nav-link {{ request()->routeIs('circulars.*') ? 'active' : '' }}">
                        <i class="fa fa-bullhorn"></i>
                        <span class="nav-link-label">{{ __('التعاميم') }}</span>
                    </a>
                </div>

                <!-- Categories -->
                <div class="nav-item">
                    <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="fa fa-tags"></i>
                        <span class="nav-link-label">{{ __('العضوية') }}</span>
                    </a>
                </div>

                <!-- Users Management -->
                <div class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="fa fa-users"></i>
                        <span class="nav-link-label">{{ __('إدارة المستخدمين') }}</span>
                    </a>
                </div>

                <!-- Permissions Management -->
                <div class="nav-item">
                    <a href="{{ route('permissions.index') }}" class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                        <i class="fa fa-key"></i>
                        <span class="nav-link-label">{{ __('الصلاحيات') }}</span>
                    </a>
                </div>

                <!-- servies Dropdown -->
                <div class="nav-item {{ request()->routeIs('servies.*') || request()->routeIs('bookings.*') ? 'has-active-child is-open' : '' }}">
                    <a href="#" class="nav-link dropdown-toggle {{ request()->routeIs('servies.*') || request()->routeIs('bookings.*') ? 'active' : '' }}" data-toggle="dropdown" aria-expanded="{{ request()->routeIs('servies.*') || request()->routeIs('bookings.*') ? 'true' : 'false' }}">
                        <i class="fa fa-briefcase"></i>     
                        <span class="nav-link-label">{{ __('الخدمات') }}</span>
                        <i class="fa fa-chevron-down nav-arrow" aria-hidden="true"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('servies.index') }}" class="{{ request()->routeIs('servies.index') || request()->routeIs('servies.edit') ? 'active' : '' }}"><i class="fa fa-briefcase"></i>{{ __('كل الخدمات') }}</a></li>
                        <li><a href="{{ route('servies.create') }}" class="{{ request()->routeIs('servies.create') ? 'active' : '' }}"><i class="fa fa-plus-circle"></i>{{ __('اضافة خدمة') }}</a></li>
                        <li><a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.index') || request()->routeIs('bookings.edit') ? 'active' : '' }}"><i class="fa fa-calendar"></i>{{ __('كل الحجوزات') }}</a></li>
                        <li><a href="{{ route('bookings.create') }}" class="{{ request()->routeIs('bookings.create') ? 'active' : '' }}"><i class="fa fa-calendar"></i>{{ __('اضافة حجز') }}</a></li>
                </ul>
                </div>
                <div class="nav-item">
                    <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <i class="fa fa-cog"></i>
                        <span class="nav-link-label">{{ __('الاعدادات الرئيسية') }}</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Mobile Sidebar Backdrop -->
        <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>
    @endif

    <!-- Main Content -->
    <div class="main-content {{ !Auth::check() ? 'no-auth' : '' }}">
        @if (Auth::check())
            <div class="topbar">
                <div class="topbar-links">
                    <a href="{{ route('dashboard') }}" class="topbar-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        {{ __('لوحة التحكم') }}
                    </a>
                    <a href="{{ route('contributors.index') }}" class="topbar-link {{ request()->routeIs('contributors.*') ? 'is-active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        {{ __('المساهمين') }}
                    </a>
                    <a href="{{ route('meetings.index') }}" class="topbar-link {{ request()->routeIs('meetings.*') ? 'is-active' : '' }}">
                        <i class="bi bi-camera-video-fill"></i>
                        {{ __('الاجتماعات') }}
                    </a>
                    <a href="{{ route('settings.index') }}" class="topbar-link {{ request()->routeIs('settings.*') ? 'is-active' : '' }}">
                        <i class="bi bi-sliders2"></i>
                        {{ __('إعدادات النظام') }}
                    </a>
                </div>

                <div class="topbar-actions">
                    <div class="topbar-action-group">
                        <button type="button" class="topbar-icon-btn" data-topbar-toggle="display-settings" aria-expanded="false">
                            <i class="bi bi-type"></i>
                            {{ __('إعدادات العرض') }}
                        </button>
                        <div class="topbar-panel" data-topbar-panel="display-settings">
                            <div class="topbar-panel-title">
                                <i class="bi bi-type"></i>
                                {{ __('حجم الخط') }}
                            </div>
                            <div class="font-size-options">
                                <button type="button" class="font-size-option" data-font-size-option="small">{{ __('صغير') }}</button>
                                <button type="button" class="font-size-option" data-font-size-option="medium">{{ __('وسط') }}</button>
                                <button type="button" class="font-size-option" data-font-size-option="large">{{ __('كبير') }}</button>
                            </div>
                            <span class="topbar-panel-note">{{ __('يتم حفظ اختيارك تلقائيًا لكل الزيارات القادمة.') }}</span>
                        </div>
                    </div>

                    <button type="button" class="topbar-icon-btn topbar-theme-btn" data-theme-toggle aria-pressed="false">
                        <i class="bi bi-moon-stars-fill"></i>
                        {{ __('دارك مود') }}
                    </button>

                    <div class="topbar-action-group">
                        <button type="button" class="topbar-profile-btn" data-topbar-toggle="profile-menu" aria-expanded="false">
                            <span class="topbar-profile-avatar">
                                {{ collect(explode(' ', Auth::user()->name))->take(2)->map(fn($word) => mb_substr($word, 0, 1, 'UTF-8'))->join(' ') }}
                            </span>
                            <span>{{ Auth::user()->name }}</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="topbar-panel" data-topbar-panel="profile-menu">
                            <div class="topbar-profile-menu">
                                <a href="{{ route('profile') }}" class="topbar-profile-link">
                                    <i class="bi bi-person-circle"></i>
                                    {{ __('الملف الشخصي') }}
                                </a>
                                <a href="{{ route('settings.index') }}" class="topbar-profile-link">
                                    <i class="bi bi-gear-fill"></i>
                                    {{ __('إعدادات النظام') }}
                                </a>
                                <a href="{{ route('logout') }}" class="topbar-profile-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i>
                                    {{ __('تسجيل الخروج') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="content-body {{ !Auth::check() ? 'auth-page' : '' }}">
        @if (session('success'))
                <div class="alert alert-success alert-dismissible fade-in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                    <i class="fa fa-check-circle" style="margin-right: 8px;"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade-in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                    <i class="fa fa-exclamation-circle" style="margin-right: 8px;"></i>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <p style="display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                <i class="fa fa-copyright" style="margin-left: 8px; color: var(--text-light);"></i>
                {{ date('Y') }} - 
                <span style="color: var(--primary-color); font-weight: 600; margin: 0 8px;">
                    {{ __('مجلس إدارة دار التكامل') }}
                </span>
                <i class="fa fa-heart" style="margin-right: 8px; color: var(--danger-color);"></i>
            </p>
        </div>
    </footer>

    <!-- Logout Form -->
    @if (Auth::check())
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @endif

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        const FONT_SIZE_STORAGE_KEY = 'dar-takamol-font-size';
        const THEME_STORAGE_KEY = 'dar-takamol-theme';
        const FONT_SIZE_OPTIONS = ['small', 'medium', 'large'];
        const SIDEBAR_RESIZE_BREAKPOINT = 768;

        function applyFontSize(size) {
            const normalizedSize = FONT_SIZE_OPTIONS.includes(size) ? size : 'small';
            document.documentElement.setAttribute('data-font-size', normalizedSize);

            document.querySelectorAll('[data-font-size-option]').forEach(function(button) {
                const isActive = button.getAttribute('data-font-size-option') === normalizedSize;
                button.classList.toggle('is-active', isActive);
                button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });

            return normalizedSize;
        }

        function applyTheme(theme) {
            const normalizedTheme = theme === 'dark' ? 'dark' : 'light';
            const isDark = normalizedTheme === 'dark';
            document.documentElement.setAttribute('data-theme', normalizedTheme);

            document.querySelectorAll('[data-theme-toggle]').forEach(function(button) {
                button.classList.toggle('is-dark', isDark);
                button.setAttribute('aria-pressed', isDark ? 'true' : 'false');
                button.innerHTML = isDark
                    ? '<i class="bi bi-sun-fill"></i>{{ __("الوضع الفاتح") }}'
                    : '<i class="bi bi-moon-stars-fill"></i>{{ __("دارك مود") }}';
            });

            return normalizedTheme;
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            
            if (sidebar) {
                const isOpen = sidebar.classList.toggle('open');
                
                // Toggle backdrop and body scroll on mobile
                if (window.innerWidth <= SIDEBAR_RESIZE_BREAKPOINT) {
                    if (isOpen) {
                        if (backdrop) backdrop.classList.add('show');
                        document.body.classList.add('sidebar-open');
                    } else {
                        if (backdrop) backdrop.classList.remove('show');
                        document.body.classList.remove('sidebar-open');
                    }
                }
            }
        }

        // Dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            const savedFontSize = document.documentElement.getAttribute('data-font-size') || 'small';
            const savedTheme = document.documentElement.getAttribute('data-theme') || 'light';

            applyFontSize(savedFontSize);
            applyTheme(savedTheme);

            document.querySelectorAll('[data-font-size-option]').forEach(function(button) {
                button.addEventListener('click', function() {
                    const selectedSize = this.getAttribute('data-font-size-option');
                    const appliedSize = applyFontSize(selectedSize);

                    try {
                        localStorage.setItem(FONT_SIZE_STORAGE_KEY, appliedSize);
                    } catch (error) {
                        // Ignore storage failures and keep the chosen size for the current page.
                    }
                });
            });

            document.querySelectorAll('[data-theme-toggle]').forEach(function(button) {
                button.addEventListener('click', function() {
                    const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
                    const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    const appliedTheme = applyTheme(nextTheme);

                    try {
                        localStorage.setItem(THEME_STORAGE_KEY, appliedTheme);
                    } catch (error) {
                        // Ignore storage failures and keep the chosen theme for the current page.
                    }
                });
            });

            const topbarToggles = document.querySelectorAll('[data-topbar-toggle]');

            topbarToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();

                    const parentGroup = this.closest('.topbar-action-group');
                    const shouldOpen = !parentGroup?.classList.contains('is-open');

                    document.querySelectorAll('.topbar-action-group').forEach(function(group) {
                        group.classList.remove('is-open');
                        const groupToggle = group.querySelector('[data-topbar-toggle]');
                        if (groupToggle) {
                            groupToggle.setAttribute('aria-expanded', 'false');
                        }
                    });

                    if (shouldOpen && parentGroup) {
                        parentGroup.classList.add('is-open');
                        this.setAttribute('aria-expanded', 'true');
                    }
                });
            });

            function setDropdownState(toggle, shouldOpen) {
                const navItem = toggle.closest('.nav-item');
                const dropdown = toggle.nextElementSibling;

                if (!navItem || !dropdown || !dropdown.classList.contains('dropdown-menu')) {
                    return;
                }

                navItem.classList.toggle('is-open', shouldOpen);
                toggle.classList.toggle('active', shouldOpen || navItem.classList.contains('has-active-child'));
                toggle.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
                dropdown.style.maxHeight = shouldOpen ? dropdown.scrollHeight + 'px' : '0px';
            }

            function closeOtherDropdowns(currentToggle) {
                dropdownToggles.forEach(function(otherToggle) {
                    if (otherToggle !== currentToggle) {
                        setDropdownState(otherToggle, false);
                    }
                });
            }

            dropdownToggles.forEach(function(toggle) {
                const shouldStartOpen = toggle.closest('.nav-item')?.classList.contains('is-open');
                setDropdownState(toggle, Boolean(shouldStartOpen));
            });
            
            dropdownToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const navItem = this.closest('.nav-item');
                    const isOpen = navItem ? navItem.classList.contains('is-open') : false;

                    closeOtherDropdowns(this);
                    setDropdownState(this, !isOpen);
                });
            });
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.nav-item')) {
                    dropdownToggles.forEach(function(toggle) {
                        setDropdownState(toggle, false);
                    });
                }

                if (!event.target.closest('.topbar-action-group')) {
                    document.querySelectorAll('.topbar-action-group').forEach(function(group) {
                        group.classList.remove('is-open');
                        const groupToggle = group.querySelector('[data-topbar-toggle]');
                        if (groupToggle) {
                            groupToggle.setAttribute('aria-expanded', 'false');
                        }
                    });
                }
            });

            // Close sidebar on mobile when clicking nav links
            const navLinks = document.querySelectorAll('.sidebar .nav-link:not(.dropdown-toggle)');
            navLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= SIDEBAR_RESIZE_BREAKPOINT) {
            const sidebar = document.getElementById('sidebar');
                        const backdrop = document.getElementById('sidebarBackdrop');
                        if (sidebar && sidebar.classList.contains('open')) {
                            sidebar.classList.remove('open');
                            if (backdrop) backdrop.classList.remove('show');
                            document.body.classList.remove('sidebar-open');
                        }
                    }
                });
            });

            // Close sidebar when clicking dropdown links on mobile
            const dropdownLinks = document.querySelectorAll('.dropdown-menu a');
            dropdownLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= SIDEBAR_RESIZE_BREAKPOINT) {
                        const sidebar = document.getElementById('sidebar');
                        const backdrop = document.getElementById('sidebarBackdrop');
                        if (sidebar && sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
                            if (backdrop) backdrop.classList.remove('show');
                            document.body.classList.remove('sidebar-open');
                        }
            }
                });
            });
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            
            if (window.innerWidth > SIDEBAR_RESIZE_BREAKPOINT) {
                if (sidebar) sidebar.classList.remove('open');
                if (backdrop) backdrop.classList.remove('show');
                document.body.classList.remove('sidebar-open');
            }

            document.querySelectorAll('.dropdown-toggle').forEach(function(toggle) {
                const navItem = toggle.closest('.nav-item');
                const dropdown = toggle.nextElementSibling;

                if (navItem && dropdown && dropdown.classList.contains('dropdown-menu') && navItem.classList.contains('is-open')) {
                    dropdown.style.maxHeight = dropdown.scrollHeight + 'px';
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
