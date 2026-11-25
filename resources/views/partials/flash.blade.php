@if (session('success'))
    <div class="p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-xs font-semibold">
        {{ session('error') }}
    </div>
@endif
@if ($errors->any())
    <div class="p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-xs">
        <ul class="list-disc ml-4 space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif