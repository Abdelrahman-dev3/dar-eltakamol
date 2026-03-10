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
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    
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

        body {
            font-family: 'Cairo', 'Arial', sans-serif;
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
            width: 280px;
            height: 100vh;
            background-color: #ffffff;
            color: var(--primary-color);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
            border-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 1px solid var(--border-color);
            box-shadow: {{ app()->getLocale() == 'ar' ? '-2px' : '2px' }} 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            text-align: center;
            flex-shrink: 0;
            background-color: #ffffff;
        }

        .sidebar-brand {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
            display: block;
        }

        .sidebar-brand:hover {
            color: var(--primary-hover);
            text-decoration: none;
        }

        .sidebar-nav {
            flex: 1;
            padding: 20px 0;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .nav-item {
            margin: 5px 15px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 18px 20px;
            color: var(--primary-color);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 1.5rem;
            margin: 5px 10px;
        }

        .nav-link:hover {
            background-color: rgba(170, 134, 63, 0.1);
            color: var(--primary-hover);
            text-decoration: none;
        }

        .dropdown-menu {
            background-color: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-top: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 10px 0;
            display: none;
            position: relative;
            width: 100%;
            list-style: none;
        }

        .dropdown-menu li {
            list-style: none;
        }

        .dropdown-menu li a {
            color: var(--primary-color);
            padding: 12px 20px;
            border-radius: 6px;
            margin: 2px 8px;
            font-size: 1.3rem;
            font-weight: 500;
            display: block;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .dropdown-menu li a:hover {
            background-color: rgba(170, 134, 63, 0.1);
            color: var(--primary-hover);
        }

        .nav-link.dropdown-toggle.active {
            background-color: var(--primary-color);
            color: white;
        }

        .nav-link i {
            margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 15px;
            width: 24px;
            text-align: center;
            font-size: 1.6rem;
        }

        /* Main Content Area */
        .main-content {
            margin-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 280px;
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
            padding: 20px;
            border-top: 1px solid var(--border-color);
            background-color: #ffffff;
            flex-shrink: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .user-info:hover {
            background-color: rgba(170, 134, 63, 0.1);
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
                width: 240px;
            }

            .main-content {
                margin-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 240px;
                padding: 25px;
            }

            .sidebar-brand img {
                width: 150px;
            }

            .nav-link {
                font-size: 1.4rem;
                padding: 15px 18px;
            }

            .dropdown-menu li a {
                font-size: 1.2rem;
                padding: 10px 15px;
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
            .main-content {
                padding: 40px;
            }

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
            border-radius: 6px;
        }

        .sidebar-brand:hover img {
            transform: scale(1.05);
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
                height: 35px !important;
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
                height: 30px !important;
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
                    <img src="{{ asset('images/logos/لوجو دار التكامل ذهبي.png') }}" alt="Dar Al-Takamol Holding Group" style="height: auto; width: 175px;">
                    <div style="font-size: 1rem; font-weight: 600; color: white; text-align: center; line-height: 1.2;">
                        {{ __('دار التكامل') }}
                    </div>
                    <div style="font-size: 0.75rem; color: rgba(255,255,255,0.8); text-align: center;">
                        Holding Group
                    </div>
                </a>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fa fa-dashboard"></i>
                        {{ __('لوحة التحكم') }}
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('contributors.index') }}" class="nav-link {{ request()->routeIs('contributors.*') ? 'active' : '' }}">
                        <i class="fa fa-users-cog"></i>
                        {{ __('المساهمين') }}
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('shares-trans.index') }}" class="nav-link {{ request()->routeIs('shares-trans.*') ? 'active' : '' }}">
                        <i class="fa fa-line-chart"></i>
                        {{ __('معاملات الأسهم') }}
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('sell-shares.index') }}" class="nav-link {{ request()->routeIs('sell-shares.*') ? 'active' : '' }}">
                        <i class="fa fa-handshake"></i>
                        {{ __('عروض البيع') }}
                    </a>
                </div>

                <!-- Transactions Dropdown -->
                <div class="nav-item">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-exchange-alt"></i>
                        {{ __('المعاملات') }}
                        <i class="fa fa-chevron-down" style="margin-left: auto;"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('share-trans-lines.index') }}"><i class="fa fa-list-alt" style="margin-right: 8px;"></i>{{ __('تفاصيل المعاملات') }}</a></li>
                        <li><a href="{{ route('modify.index') }}"><i class="fa fa-list-alt" style="margin-right: 8px;"></i>{{ __('ملاحظات التعديلات') }}</a></li>
                        <li><a href="{{ route('payments.index') }}"><i class="fa fa-credit-card" style="margin-right: 8px;"></i>{{ __('المدفوعات') }}</a></li>
                        <li><a href="{{ route('shares-pos.index') }}"><i class="fa fa-shopping-cart" style="margin-right: 8px;"></i>{{ __('طلبات الشراء') }}</a></li>
                    </ul>
                </div>

                <!-- Polls Dropdown -->
                <div class="nav-item">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-list-alt"></i>
                        {{ __('الاستطلاعات') }}
                        <i class="fa fa-chevron-down" style="margin-left: auto;"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('polls.index') }}"><i class="fa fa-vote-yea" style="margin-right: 8px;"></i>{{ __('الاستطلاعات') }}</a></li>
                        <li><a href="{{ route('poll-options.index') }}"><i class="fa fa-list-check" style="margin-right: 8px;"></i>{{ __('خيارات الاستطلاعات') }}</a></li>
                        <li><a href="{{ route('poll-answers.index') }}"><i class="fa fa-comments" style="margin-right: 8px;"></i>{{ __('إجابات الاستطلاعات') }}</a></li>
                    </ul>
                </div>

                <!-- Meetings -->
                <div class="nav-item">
                    <a href="{{ route('meetings.index') }}" class="nav-link {{ request()->routeIs('meetings.*') ? 'active' : '' }}">
                        <i class="fa fa-video"></i>
                        {{ __('الاجتماعات') }}
                    </a>
                </div>

                <!-- Regulations -->
                <div class="nav-item">
                    <a href="{{ route('regulations.index') }}" class="nav-link {{ request()->routeIs('regulations.*') ? 'active' : '' }}">
                        <i class="fa fa-gavel"></i>
                        {{ __('اللوائح') }}
                    </a>
                </div>

                <!-- Documents -->
                <div class="nav-item">
                    <a href="{{ route('documents.index') }}" class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                        <i class="fa fa-folder-open"></i>
                        {{ __('الملفات') }}
                    </a>
                </div>

                <!-- Circulars -->
                <div class="nav-item">
                    <a href="{{ route('circulars.index') }}" class="nav-link {{ request()->routeIs('circulars.*') ? 'active' : '' }}">
                        <i class="fa fa-bullhorn"></i>
                        {{ __('التعاميم') }}
                    </a>
                </div>

                <!-- Categories -->
                <div class="nav-item">
                    <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="fa fa-tags"></i>
                        {{ __('التصنيفات') }}
                    </a>
                </div>

                <!-- Users Management -->
                <div class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="fa fa-users"></i>
                        {{ __('إدارة المستخدمين') }}
                    </a>
                </div>

                <!-- Permissions Management -->
                <div class="nav-item">
                    <a href="{{ route('permissions.index') }}" class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                        <i class="fa fa-key"></i>
                        {{ __('الصلاحيات') }}
                    </a>
                </div>

                <!-- servies Dropdown -->
                <div class="nav-item">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-briefcase"></i>     
                        {{ __('الخدمات') }}
                        <i class="fa fa-chevron-down" style="margin-left: auto;"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('servies.index') }}"><i class="fa fa-briefcase"style="margin-right: 8px;"></i>{{ __('كل الخدمات') }}</a></li>
                        <li><a href="{{ route('servies.create') }}"><i class="fa fa-plus-circle"style="margin-right: 8px;"></i>{{ __('اضافة خدمة') }}</a></li>
                        <li><a href="{{ route('bookings.index') }}"><i class="fa fa-calendar" style="margin-right: 8px;"></i>{{ __('كل الحجوزات') }}</a></li>
                        <li><a href="{{ route('bookings.create') }}"><i class="fa fa-calendar" style="margin-right: 8px;"></i>{{ __('اضافة حجز') }}</a></li>
                </ul>
                </div>
                <div class="nav-item">
                    <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <i class="fa fa-cog"></i>
                        {{ __('الاعدادات الرئيسية') }}
                    </a>
                </div>
            </nav>

            <!-- User Profile Section -->
            <div class="user-profile">
                <div class="user-info">
                    <div class="user-avatar">
                        {{
                            collect(explode(' ', Auth::user()->name))->take(2)->map(fn($word) => mb_substr($word, 0, 1, 'UTF-8'))->join(' ')
                        }}
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 600; font-size: 1.4rem; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ Auth::user()->name }}</div>
                        <div style="font-size: 1.2rem; color: var(--text-light); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <div style="margin-top: 10px;">
                    <a href="{{ route('profile') }}" class="nav-link" style="padding: 12px 20px; font-size: 1.4rem; margin: 5px 0;">
                        <i class="fa fa-user"></i>
                        {{ __('الملف الشخصي') }}
                    </a>
                    <a href="{{ route('logout') }}" class="nav-link" style="padding: 12px 20px; font-size: 1.4rem; margin: 5px 0;" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out-alt"></i>
                        {{ __('تسجيل الخروج') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Sidebar Backdrop -->
        <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>
    @endif

    <!-- Main Content -->
    <div class="main-content {{ !Auth::check() ? 'no-auth' : '' }}">
        @if (Auth::check())
            <div class="content-header">
                <h1 style="margin: 0; font-size: 1.8rem; font-weight: 600; color: var(--text-primary);">
                    @yield('title', __('لوحة التحكم'))
                </h1>
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
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            
            if (sidebar) {
                const isOpen = sidebar.classList.toggle('open');
                
                // Toggle backdrop and body scroll on mobile
                if (window.innerWidth <= 768) {
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
            
            dropdownToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Close all other dropdowns
                    dropdownToggles.forEach(function(otherToggle) {
                        if (otherToggle !== toggle) {
                            const otherDropdown = otherToggle.nextElementSibling;
                            if (otherDropdown && otherDropdown.classList.contains('dropdown-menu')) {
                                otherDropdown.style.display = 'none';
                                otherToggle.classList.remove('active');
                            }
                        }
                    });
                    
                    // Toggle current dropdown
                    const dropdown = this.nextElementSibling;
                    if (dropdown && dropdown.classList.contains('dropdown-menu')) {
                        const isVisible = dropdown.style.display === 'block';
                        dropdown.style.display = isVisible ? 'none' : 'block';
                        this.classList.toggle('active', !isVisible);
                    }
                });
            });
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.nav-item')) {
                    dropdownToggles.forEach(function(toggle) {
                        const dropdown = toggle.nextElementSibling;
                        if (dropdown && dropdown.classList.contains('dropdown-menu')) {
                            dropdown.style.display = 'none';
                            toggle.classList.remove('active');
                        }
                    });
                }
            });

            // Close sidebar on mobile when clicking nav links
            const navLinks = document.querySelectorAll('.sidebar .nav-link:not(.dropdown-toggle)');
            navLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
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
                    if (window.innerWidth <= 768) {
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
            
            if (window.innerWidth > 768) {
                if (sidebar) sidebar.classList.remove('open');
                if (backdrop) backdrop.classList.remove('show');
                document.body.classList.remove('sidebar-open');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>