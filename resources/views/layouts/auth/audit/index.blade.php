@extends('layouts.valex')

@section('content')
<div class="space-y-6">

    <!-- Header Breadcrumb & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-2 border-b border-slate-200/80">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">System Audit Trail</h1>
            <p class="text-xs text-slate-500 mt-1">Immutable security event logging and action history.</p>
        </div>
        <div class="mt-3 sm:mt-0">
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-purple-50 text-[#6c5ffc] border border-purple-200">
                <i class="bi bi-shield-check mr-1.5"></i> Encapsulated OOP Audit Logger
            </span>
        </div>
    </div>

    <!-- SEARCH & FILTER CONTROLS -->
    <div class="valex-card p-5">
        <form method="GET" action="{{ route('audit.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Search Keywords</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search action, description..." 
                       class="w-full bg-slate-50 border border-slate-200 text-xs rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:border-[#6c5ffc]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Action Type</label>
                <select name="action_filter" class="w-full bg-slate-50 border border-slate-200 text-xs rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:border-[#6c5ffc]">
                    <option value="">All Action Types</option>
                    @foreach($distinctActions as $action)
                        <option value="{{ $action }}" {{ $actionFilter === $action ? 'selected' : '' }}>{{ $action }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" 
                       class="w-full bg-slate-50 border border-slate-200 text-xs rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:border-[#6c5ffc]">
            </div>
            <div>
                <button type="submit" class="w-full py-2 px-4 bg-[#6c5ffc] hover:bg-[#5646fc] text-white text-xs font-bold rounded-lg transition shadow-sm flex items-center justify-center">
                    <i class="bi bi-search mr-1.5"></i> Filter Logs
                </button>
            </div>
        </form>
    </div>

    <!-- AUDIT LOGS DATA TABLE -->
    <div class="valex-card p-6">
        <h2 class="text-base font-bold text-slate-800 mb-4">Event Activity Log</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Event ID</th>
                        <th class="py-3 px-4">Timestamp</th>
                        <th class="py-3 px-4">User</th>
                        <th class="py-3 px-4">Action</th>
                        <th class="py-3 px-4">Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-mono text-xs">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 font-bold text-slate-500">#{{ $log->id }}</td>
                            <td class="py-3 px-4 text-slate-400">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="py-3 px-4 font-sans font-medium text-slate-800">{{ $log->user->name ?? 'System' }}</td>
                            <td class="py-3 px-4 font-sans">
                                @if(str_contains($log->action, 'STATUS'))
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">{{ $log->action }}</span>
                                @elseif(str_contains($log->action, 'REPORT'))
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-purple-50 text-[#6c5ffc] border border-purple-200">{{ $log->action }}</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">{{ $log->action }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-sans text-slate-600">{{ $log->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 text-xs font-sans">
                                No audit events logged yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>

</div>
@endsection