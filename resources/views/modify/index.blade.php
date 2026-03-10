@extends('layouts.app')

@section('title', __('ملاحظات التعديل'))

@section('content')
<style>
    .container {
        max-width: 1200px;
        margin: 40px auto;
        background: var(--card-bg);
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 20px 30px;
        direction: rtl;
        font-size: 17px;
    }

    .search-bar {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        gap: 10px;
        flex-wrap: wrap;
    }

    .search-bar input {
        flex: 1;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 1rem;
        outline: none;
    }

    .search-bar input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 4px var(--accent-color);
    }

    .search-bar button {
        background-color: var(--primary-color);
        color: var(--text-white);
        border: none;
        border-radius: 8px;
        padding: 10px 18px;
        font-size: 0.95rem;
        cursor: pointer;
        transition: 0.3s;
    }

    .search-bar button:hover {
        background-color: var(--primary-hover);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    thead {
        background: var(--primary-color);
        color: var(--text-white);
    }

    th, td {
        padding: 12px 16px;
        text-align: right;
        border-bottom: 1px solid var(--border-color);
    }

    th{
        white-space: nowrap;
    }

    tbody tr:hover {
        background-color: #fff9f0;
    }

    td a {
        color: var(--primary-color);
        text-decoration: none;
        transition: color 0.3s;
    }

    td a:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }

    .no-data {
        text-align: center;
        color: var(--text-secondary);
        padding: 20px;
    }

    @media (max-width: 768px) {
        th, td {
            font-size: 0.85rem;
            padding: 8px;
        }
    }
</style>

<div class="container">
    <div class="search-bar">
        <input type="text" id="search" placeholder="ابحث عن الصفحة أو من قام بالتعديل...">
    </div>

    <table id="editTable">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('الصفحة المعدلة') }}</th>
                <th>{{ __('سبب التعديل') }}</th>
                <th>{{ __('من قام بالتعديل') }}</th>
                <th>{{ __('منذ') }}</th>
                <th>{{ __('التاريخ') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($edits as $edit)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><a href="{{ $edit->page_name }}">{{ __('عرض الصفحة') }}</a></td>
                    <td>{{ $edit->note }}</td>
                    <td>{{ $edit->user->name }}</td>
                    <td style="white-space: nowrap;">{{ $edit->created_at->diffForHumans() }}</td>
                    <td style="white-space: nowrap;">{{ $edit->created_at }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="no-data">لا توجد ملاحظات تعديل حالياً</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    const searchInput = document.getElementById('search');
    searchInput.addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const rows = document.querySelectorAll('#editTable tbody tr');
        rows.forEach(row => {
            const rowText = row.innerText.toLowerCase();
            row.style.display = rowText.includes(searchText) ? '' : 'none';
        });
    });
</script>

@endsection
