@extends('layouts.app')

@section('title', 'Manage Registrants - ' . $event->title)

@section('content')
<div class="py-10 px-4">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="fixed top-24 right-4 z-50 neo-box bg-yellow-300 p-4 font-bold uppercase max-w-md">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-24 right-4 z-50 neo-box bg-telkom-red text-white p-4 font-bold uppercase max-w-md">
            {{ session('error') }}
        </div>
    @endif

    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="neo-box bg-white p-8 mb-8">
            <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                <div>
                    <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter mb-2">
                        Manajemen Peserta
                    </h1>
                    <p class="text-lg font-bold text-gray-600">
                        {{ $event->title }}
                    </p>
                </div>
                <a href="{{ route('events.show', $event->id) }}" class="neo-button-default">
                    &larr;  Kembali ke Detail Event
                </a>
            </div>

            <!-- Event Info Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-6 border-t-[3px] border-telkom-black">
                <div class="bg-gray-100 p-4 border-2 border-telkom-black">
                    <p class="text-xs font-bold uppercase text-gray-600 mb-1">Total Peserta</p>
                    <p class="text-3xl font-black">{{ is_countable($registrants) ? count($registrants) : 0 }}</p>
                </div>
                <div class="bg-gray-100 p-4 border-2 border-telkom-black">
                    <p class="text-xs font-bold uppercase text-gray-600 mb-1">Tanggal Event</p>
                    <p class="text-xl font-black">{{ $event->date->format('d M Y') }}</p>
                </div>
                <div class="bg-gray-100 p-4 border-2 border-telkom-black">
                    <p class="text-xs font-bold uppercase text-gray-600 mb-1">Status</p>
                    <span class="inline-block px-3 py-1 text-sm font-bold uppercase
                        @if($event->status === 'published') bg-green-500 text-white
                        @elseif($event->status === 'draft') bg-yellow-500 text-white
                        @elseif($event->status === 'cancelled') bg-red-500 text-white
                        @else bg-gray-500 text-white
                        @endif
                        border-2 border-telkom-black">
                        {{ $event->status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Registrants List -->
        <div class="neo-box bg-white p-8">
            <h2 class="text-2xl font-black uppercase mb-6 pb-4 border-b-[3px] border-telkom-black">
                Peserta Terdaftar
            </h2>

            @if(is_array($registrants) && count($registrants) > 0)
                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full border-[3px] border-telkom-black">
                        <thead>
                            <tr class="bg-telkom-black text-white">
                                <th class="text-left p-4 font-black uppercase border-r-2 border-white">#</th>
                                <th class="text-left p-4 font-black uppercase border-r-2 border-white">Name</th>
                                <th class="text-left p-4 font-black uppercase border-r-2 border-white">Email</th>
                                <th class="text-left p-4 font-black uppercase border-r-2 border-white">Registered At</th>
                                <th class="text-left p-4 font-black uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrants as $index => $registrant)
                                <tr class="border-b-2 border-telkom-black {{ $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }}">
                                    <td class="p-4 font-bold">{{ $index + 1 }}</td>
                                    <td class="p-4 font-bold">{{ $registrant['name'] ?? 'N/A' }}</td>
                                    <td class="p-4">{{ $registrant['email'] ?? 'N/A' }}</td>
                                    <td class="p-4 font-bold">{{ $registrant['registered_at'] ?? 'N/A' }}</td>
                                    <td class="p-4">
                                        <form method="POST" action="{{ route('events.registrants.remove', ['eventId' => $event->id, 'userId' => $registrant['id']]) }}"
                                              onsubmit="return confirm('Are you sure you want to remove this registrant?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-telkom-red text-white px-3 py-2 text-sm font-bold uppercase border-2 border-telkom-black hover:bg-red-700 transition-colors">
                                                Hapus Peserta
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-4">
                    @foreach($registrants as $index => $registrant)
                        <div class="neo-box bg-white p-4">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="text-xs font-bold uppercase text-gray-600">Peserta #{{ $index + 1 }}</p>
                                    <h3 class="text-xl font-black uppercase">{{ $registrant['name'] ?? 'N/A' }}</h3>
                                </div>
                            </div>
                            <div class="space-y-2 mb-4 text-sm">
                                <p><span class="font-bold">Email:</span> {{ $registrant['email'] ?? 'N/A' }}</p>
                                <p><span class="font-bold">Terdaftar:</span> {{ $registrant['registered_at'] ?? 'N/A' }}</p>
                            </div>
                            <form method="POST" action="{{ route('events.registrants.remove', ['eventId' => $event->id, 'userId' => $registrant['id']]) }}"
                                  onsubmit="return confirm('Are you sure you want to remove this registrant?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-telkom-red text-white px-4 py-3 font-bold uppercase border-2 border-telkom-black hover:bg-red-700 transition-colors">
                                    Hapus Peserta
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <!-- Export Actions -->
                <div class="mt-8 pt-6 border-t-[3px] border-telkom-black flex flex-wrap gap-4">
                    <button onclick="window.print()" class="neo-button-default bg-telkom-black text-white">
                        Print List Peserta
                    </button>
                    <button onclick="exportToCSV()" class="neo-button-default bg-green-600 text-white">
                        Export CSV
                    </button>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <h3 class="text-3xl font-black uppercase mb-2">Belum Ada Peserta</h3>
                    <p class="text-gray-600 font-bold">Belum ada yang mendaftar untuk acara ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function exportToCSV() {
    const registrants = @json($registrants);

    if (!registrants || registrants.length === 0) {
        alert('No registrants to export');
        return;
    }

    const csvContent = [
        ['#', 'Name', 'Email', 'Role', 'Registered At'].join(','),
        ...registrants.map((r, i) => [
            i + 1,
            `"${r.name || 'N/A'}"`,
            `"${r.email || 'N/A'}"`,
            r.role || 'user',
            `"${r.registered_at || 'N/A'}"`
        ].join(','))
    ].join('\n');

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);

    link.setAttribute('href', url);
    link.setAttribute('download', `registrants-{{ $event->id }}-{{ date('Y-m-d') }}.csv`);
    link.style.visibility = 'hidden';

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection
