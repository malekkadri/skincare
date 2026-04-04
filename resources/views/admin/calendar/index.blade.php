@extends('admin.layouts.app')

@section('title', 'Calendar')
@section('header', 'Calendar')

@section('content')
<section class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
        <a class="btn btn-secondary" href="{{ route('admin.calendar.index', ['month' => $prevMonth]) }}">← {{ $focus->copy()->subMonth()->format('F Y') }}</a>
        <h2 style="margin:0">{{ $focus->format('F Y') }}</h2>
        <a class="btn btn-secondary" href="{{ route('admin.calendar.index', ['month' => $nextMonth]) }}">{{ $focus->copy()->addMonth()->format('F Y') }} →</a>
    </div>
    <div id="calendar-grid" class="grid" style="grid-template-columns:repeat(7,minmax(0,1fr)); gap:.5rem"></div>
</section>

<script>
    const focusMonth = '{{ $focus->format('Y-m') }}';
    const [year, month] = focusMonth.split('-').map(Number);
    const first = new Date(year, month - 1, 1);
    const last = new Date(year, month, 0);

    async function loadEvents() {
        const res = await fetch(`{{ route('admin.calendar.events') }}?start={{ $focus->copy()->startOfMonth()->toDateString() }}&end={{ $focus->copy()->endOfMonth()->toDateString() }}`);
        const data = await res.json();
        const eventsByDay = {};
        data.events.forEach(e => {
            const day = e.start.slice(0, 10);
            eventsByDay[day] = eventsByDay[day] || [];
            eventsByDay[day].push(e);
        });

        const grid = document.getElementById('calendar-grid');
        ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'].forEach(d => {
            const h = document.createElement('div');
            h.innerHTML = `<strong>${d}</strong>`;
            grid.appendChild(h);
        });

        const leading = (first.getDay() + 6) % 7;
        for (let i = 0; i < leading; i++) {
            const blank = document.createElement('div');
            blank.className = 'card';
            blank.style.minHeight = '130px';
            blank.style.opacity = '.3';
            grid.appendChild(blank);
        }

        for (let day = 1; day <= last.getDate(); day++) {
            const key = `${focusMonth}-${String(day).padStart(2, '0')}`;
            const cell = document.createElement('div');
            cell.className = 'card';
            cell.style.minHeight = '130px';
            cell.innerHTML = `<strong>${day}</strong>`;

            (eventsByDay[key] || []).forEach(event => {
                const item = document.createElement('a');
                item.href = event.url;
                item.style.display = 'block';
                item.style.padding = '.3rem .4rem';
                item.style.marginTop = '.3rem';
                item.style.borderRadius = '.4rem';
                item.style.textDecoration = 'none';
                item.style.fontSize = '.78rem';
                item.style.background = '#ede9fe';
                if (event.status === 'confirmed') item.style.background = '#dbeafe';
                if (event.status === 'completed') item.style.background = '#dcfce7';
                if (event.status === 'cancelled') item.style.background = '#fee2e2';
                item.innerText = `${event.start.substring(11, 16)} ${event.title}`;
                cell.appendChild(item);
            });

            grid.appendChild(cell);
        }
    }

    loadEvents();
</script>
@endsection
