@extends('layouts.app')

@section('title', 'Tactical Roster - ' . $event->title)

@section('content')
    <div class="min-h-screen bg-[#F2F2F2] p-4 md:p-8 font-sans"
        style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px;">
        <div class="max-w-6xl mx-auto">

            <!-- Main Card Container -->
            <div class="bg-white border-[3px] border-black shadow-[12px_12px_0px_0px_black] mb-8 relative">

                <!-- Hazard Pattern Strip -->
                <div class="h-4 w-full border-b-[3px] border-black"
                    style="background: repeating-linear-gradient(45deg, #FF0000, #FF0000 10px, #FFFFFF 10px, #FFFFFF 20px);">
                </div>

                <div class="p-6 md:p-8">
                    <!-- Header Section -->
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 border-b-[3px] border-black pb-6">
                        <div>
                            <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter leading-none mb-2">
                                DAFTAR PESERTA
                            </h1>
                            <p class="font-mono text-lg md:text-xl font-bold bg-black text-white px-2 py-1 inline-block">
                                EVENT: {{ Str::limit($event->title, 40) }}
                            </p>
                        </div>

                        <div class="flex flex-col items-end gap-2">
                            <div class="font-mono font-bold text-sm">
                                TOTAL OPS: {{ is_countable($registrants) ? count($registrants) : 0 }}
                            </div>
                            <a href="{{ route('events.show', $event->id) }}"
                                class="font-bold border-[3px] border-black px-4 py-2 hover:bg-[#CCFF00] hover:shadow-[4px_4px_0px_0px_black] transition-all uppercase text-sm">
                                &larr; KEMBALI KE MARKAS
                            </a>
                        </div>
                    </div>

                    <!-- Action Toolbar -->
                    <div class="flex flex-col md:flex-row gap-4 mb-8">
                        <!-- Search Input -->
                        <div class="flex-grow">
                            <input type="text" id="searchInput" placeholder="Cari Nama..."
                                class="w-full border-[3px] border-black p-3 font-mono text-sm focus:outline-none focus:bg-[#CCFF00] focus:shadow-[4px_4px_0px_0px_black] transition-all placeholder:text-gray-500 placeholder:uppercase">
                        </div>

                        <!-- Export Button -->
                        <button onclick="exportToCSV()"
                            class="bg-[#CCFF00] border-[3px] border-black px-6 py-3 font-black uppercase tracking-wide shadow-[4px_4px_0px_0px_black] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_black] transition-all flex items-center gap-2 whitespace-nowrap">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            UNDUH DATA (CSV)
                        </button>
                    </div>

                    <!-- Data Display -->
                    @if (isset($registrants) && count($registrants) > 0)

                        <!-- View A: The Desktop Table (Hidden on Mobile) -->
                        <div class="hidden md:block overflow-hidden border-[3px] border-black">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-black text-white uppercase text-sm leading-normal">
                                        <th class="py-4 px-6 font-black border-r-2 border-white w-16">NO</th>
                                        <th class="py-4 px-6 font-black border-r-2 border-white">NAMA MAHASISWA</th>
                                        <!-- NIM removed -->
                                        <th class="py-4 px-6 font-black border-r-2 border-white">EMAIL</th>
                                        <th class="py-4 px-6 font-black border-r-2 border-white">TANGGAL DAFTAR</th>
                                        <th class="py-4 px-6 font-black text-center">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm font-bold text-gray-900" id="registrantsTableBody">
                                    @foreach ($registrants as $index => $registrant)
                                        <tr
                                            class="border-b-[3px] border-black hover:bg-[#CCFF00] transition-colors cursor-pointer group">
                                            <td class="py-4 px-6 border-r-[3px] border-black font-mono">{{ $index + 1 }}
                                            </td>
                                            <td
                                                class="py-4 px-6 border-r-[3px] border-black font-black uppercase text-lg group-hover:translate-x-1 transition-transform">
                                                {{ $registrant['name'] ?? 'N/A' }}
                                            </td>
                                            <!-- NIM cell removed -->
                                            <td
                                                class="py-4 px-6 border-r-[3px] border-black font-mono text-gray-700 group-hover:text-black">
                                                {{ $registrant['email'] ?? 'N/A' }}
                                            </td>
                                            <td class="py-4 px-6 border-r-[3px] border-black font-mono">
                                                {{ isset($registrant['registered_at']) ? \Carbon\Carbon::parse($registrant['registered_at'])->format('d M Y H:i') : '-' }}
                                            </td>
                                            <td class="py-4 px-6 text-center">
                                                <form method="POST"
                                                    action="{{ route('events.registrants.remove', ['eventId' => $event->id, 'userId' => $registrant['id']]) }}"
                                                    onsubmit="return confirm('KONFIRMASI: Hapus peserta ini dari misi?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="bg-red-500 text-white p-2 border-2 border-black hover:bg-black hover:text-red-500 transition-colors"
                                                        title="Hapus Peserta">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- View B: The Mobile Cards (Hidden on Desktop) -->
                        <div class="md:hidden grid grid-cols-1 gap-4" id="registrantsMobileGrid">
                            @foreach ($registrants as $index => $registrant)
                                <div
                                    class="bg-white border-[3px] border-black shadow-[4px_4px_0px_0px_black] p-4 flex flex-col gap-3 relative overflow-hidden">
                                    <div
                                        class="absolute top-0 right-0 bg-black text-white text-xs font-mono font-bold px-2 py-1">
                                        #{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}
                                    </div>

                                    <div>
                                        <span class="text-xs font-bold text-gray-500 uppercase">Nama Personil</span>
                                        <h3 class="text-xl font-black uppercase leading-tight">
                                            {{ $registrant['name'] ?? 'N/A' }}</h3>
                                    </div>

                                    <div class="grid grid-cols-1 gap-2">
                                        <!-- NIM removed -->
                                        <div>
                                            <span class="text-xs font-bold text-gray-500 uppercase block">Terdaftar</span>
                                            <span
                                                class="font-mono font-bold text-xs">{{ isset($registrant['registered_at']) ? \Carbon\Carbon::parse($registrant['registered_at'])->format('d M y') : '-' }}</span>
                                        </div>
                                    </div>

                                    <div>
                                        <span class="text-xs font-bold text-gray-500 uppercase block">Email</span>
                                        <span
                                            class="font-mono text-sm break-all">{{ $registrant['email'] ?? 'N/A' }}</span>
                                    </div>

                                    <div class="pt-3 mt-1 border-t-2 border-dashed border-gray-300">
                                        <form method="POST"
                                            action="{{ route('events.registrants.remove', ['eventId' => $event->id, 'userId' => $registrant['id']]) }}"
                                            onsubmit="return confirm('KONFIRMASI: Hapus peserta ini dari event?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-full bg-red-500 text-white font-black uppercase text-sm py-2 border-2 border-black hover:bg-black hover:text-red-500 transition-colors shadow-[2px_2px_0px_0px_black]">
                                                DISKUALIFIKASI
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="border-[3px] border-dashed border-gray-400 bg-gray-50 p-12 text-center">
                            <div class="inline-block bg-gray-200 border-2 border-gray-400 p-4 rounded-full mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-black uppercase text-gray-400 mb-2">BELUM ADA PESERTA</h3>
                            <p class="text-gray-500 font-mono">Belum ada data personel yang masuk untuk event ini.</p>
                        </div>
                    @endif

                </div>

                <!-- Bottom Strip -->
                <div class="h-4 w-full border-t-[3px] border-black bg-black"></div>
            </div>

        </div>
    </div>

    <script>
        // Client-side Search Functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();

            // Filter Table Rows
            const tableRows = document.querySelectorAll('#registrantsTableBody tr');
            tableRows.forEach(row => {
                const name = row.children[1].textContent.toLowerCase();
                const email = row.children[2].textContent
                    .toLowerCase(); // Adjusted index after removing NIM

                if (name.includes(searchText) || email.includes(searchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Filter Mobile Cards
            const mobileCards = document.querySelectorAll('#registrantsMobileGrid > div');
            mobileCards.forEach(card => {
                // Need to traverse deeper for mobile structure
                const name = card.querySelector('h3').textContent.toLowerCase();

                if (name.includes(searchText)) {
                    card.style.display = 'flex'; // grid items are usually block/flex
                } else {
                    card.style.display = 'none';
                }
            });
        });

        function exportToCSV() {
            const registrants = @json($registrants ?? []);

            if (!registrants || registrants.length === 0) {
                alert('DATA KOSONG: Tidak ada data untuk diunduh.');
                return;
            }

            const headers = ['NO', 'NAMA', 'EMAIL', 'TANGGAL DAFTAR'];
            const csvContent = [
                headers.join(','),
                ...registrants.map((r, i) => [
                    i + 1,
                    `"${r.name || 'N/A'}"`,
                    `"${r.email || 'N/A'}"`,
                    `"${r.registered_at || 'N/A'}"`
                ].join(','))
            ].join('\n');

            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);

            const date = new Date().toISOString().slice(0, 10);
            link.setAttribute('href', url);
            link.setAttribute('download', `DATA_PESERTA_EVENT_{{ $event->id }}_${date}.csv`);
            link.style.visibility = 'hidden';

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
@endsection
