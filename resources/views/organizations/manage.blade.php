@extends('layouts.app')

@section('title', 'Kelola Organisasi')

@section('content')
    <div class="min-h-screen py-10 px-4">
        <div class="max-w-6xl mx-auto">

            <!-- Header Section -->
            <div class="mb-10">
                <div class="flex flex-wrap items-center gap-4 mb-6">
                    <h1 class="text-4xl font-black uppercase tracking-tighter bg-black text-white px-4 py-2 inline-block transform -skew-x-3">
                        Kelola Organisasi
                    </h1>
                    <span class="bg-[#ED1C24] text-white font-bold text-sm px-3 py-1 uppercase tracking-wider">
                        {{ $organization->name }}
                    </span>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <!-- Pending Requests -->
                    <div class="bg-yellow-300 border-[3px] border-black p-4 shadow-[6px_6px_0px_#000]">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-mono text-xs uppercase tracking-wider">Menunggu</p>
                                <p class="text-4xl font-black">{{ $pendingCount }}</p>
                            </div>
                            <span class="text-4xl">⏳</span>
                        </div>
                    </div>

                    <!-- Approved Members -->
                    <div class="bg-[#CCFF00] border-[3px] border-black p-4 shadow-[6px_6px_0px_#000]">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-mono text-xs uppercase tracking-wider">Disetujui</p>
                                <p class="text-4xl font-black">{{ $approvedCount }}</p>
                            </div>
                            <span class="text-4xl">✓</span>
                        </div>
                    </div>

                    <!-- Total Members -->
                    <div class="bg-white border-[3px] border-black p-4 shadow-[6px_6px_0px_#000]">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-mono text-xs uppercase tracking-wider">Total</p>
                                <p class="text-4xl font-black">{{ $members->count() }}</p>
                            </div>
                            <span class="text-4xl">👥</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Members Table -->
            <div class="bg-white border-[3px] border-black shadow-[8px_8px_0px_#000]">
                <!-- Table Header -->
                <div class="h-8 w-full border-b-[3px] border-black bg-[repeating-linear-gradient(45deg,#ED1C24,#ED1C24_10px,#FFFFFF_10px,#FFFFFF_20px)] flex items-center justify-center">
                    <div class="bg-black text-white font-black text-xs px-2 py-0.5">
                        DAFTAR ANGGOTA // MEMBER LIST
                    </div>
                </div>

                <div class="p-6">
                    @if($members->isEmpty())
                        <div class="text-center py-12">
                            <p class="font-mono text-gray-500 uppercase">Belum ada anggota dalam organisasi ini.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b-[3px] border-black">
                                        <th class="text-left py-3 px-4 font-black uppercase text-xs tracking-wider">ID</th>
                                        <th class="text-left py-3 px-4 font-black uppercase text-xs tracking-wider">Nama</th>
                                        <th class="text-left py-3 px-4 font-black uppercase text-xs tracking-wider">Email</th>
                                        <th class="text-left py-3 px-4 font-black uppercase text-xs tracking-wider">Role</th>
                                        <th class="text-left py-3 px-4 font-black uppercase text-xs tracking-wider">Status</th>
                                        <th class="text-left py-3 px-4 font-black uppercase text-xs tracking-wider">Tanggal Request</th>
                                        <th class="text-center py-3 px-4 font-black uppercase text-xs tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($members as $member)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                            <td class="py-4 px-4 font-mono font-bold">#{{ $member->id }}</td>
                                            <td class="py-4 px-4">
                                                <div class="flex items-center gap-3">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=000000&color=ffffff&size=40" 
                                                         class="w-10 h-10 border-2 border-black" alt="{{ $member->name }}">
                                                    <span class="font-bold">{{ $member->name }}</span>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4 font-mono text-sm">{{ $member->email }}</td>
                                            <td class="py-4 px-4">
                                                <span class="bg-black text-white text-xs font-bold px-2 py-1 uppercase">
                                                    {{ $member->role }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4">
                                                @if($member->pivot->status === 'pending')
                                                    <span class="inline-flex items-center gap-1 bg-yellow-300 border-2 border-black text-xs font-bold px-2 py-1 uppercase">
                                                        ⏳ Pending
                                                    </span>
                                                @elseif($member->pivot->status === 'approved')
                                                    <span class="inline-flex items-center gap-1 bg-[#CCFF00] border-2 border-black text-xs font-bold px-2 py-1 uppercase">
                                                        ✓ Approved
                                                    </span>
                                                @elseif($member->pivot->status === 'rejected')
                                                    <span class="inline-flex items-center gap-1 bg-[#ED1C24] text-white border-2 border-black text-xs font-bold px-2 py-1 uppercase">
                                                        ✗ Rejected
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4 font-mono text-sm text-gray-600">
                                                {{ $member->pivot->created_at ? \Carbon\Carbon::parse($member->pivot->created_at)->format('d M Y, H:i') : '-' }}
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="flex items-center justify-center gap-2">
                                                    @if($member->pivot->status === 'pending')
                                                        <!-- Approve Button -->
                                                        <form action="{{ route('organizations.approve', [$organization->id, $member->id]) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="bg-[#CCFF00] border-2 border-black px-3 py-1.5 font-bold text-xs uppercase hover:shadow-[4px_4px_0px_#000] hover:-translate-x-[2px] hover:-translate-y-[2px] transition-all"
                                                                    title="Setujui">
                                                                ✓ Setujui
                                                            </button>
                                                        </form>

                                                        <!-- Reject Button -->
                                                        <form action="{{ route('organizations.reject', [$organization->id, $member->id]) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="bg-[#ED1C24] text-white border-2 border-black px-3 py-1.5 font-bold text-xs uppercase hover:shadow-[4px_4px_0px_#000] hover:-translate-x-[2px] hover:-translate-y-[2px] transition-all"
                                                                    title="Tolak">
                                                                ✗ Tolak
                                                            </button>
                                                        </form>
                                                    @elseif($member->pivot->status === 'approved' && $member->id !== auth()->id())
                                                        <!-- Remove Button for approved members (except self) -->
                                                        <form action="{{ route('organizations.remove', [$organization->id, $member->id]) }}" method="POST" class="inline"
                                                              onsubmit="return confirm('Yakin ingin menghapus {{ $member->name }} dari organisasi?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="bg-gray-200 border-2 border-black px-3 py-1.5 font-bold text-xs uppercase hover:bg-[#ED1C24] hover:text-white hover:shadow-[4px_4px_0px_#000] hover:-translate-x-[2px] hover:-translate-y-[2px] transition-all"
                                                                    title="Hapus dari organisasi">
                                                                🗑️ Hapus
                                                            </button>
                                                        </form>
                                                    @elseif($member->id === auth()->id())
                                                        <span class="font-mono text-xs text-gray-400 uppercase">— Kamu —</span>
                                                    @else
                                                        <span class="font-mono text-xs text-gray-400">—</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-8 bg-black text-white p-6 border-[3px] border-black">
                <div class="flex items-start gap-4">
                    <span class="text-3xl">💡</span>
                    <div>
                        <h3 class="font-black uppercase mb-2">Informasi</h3>
                        <ul class="font-mono text-sm space-y-1 text-gray-300">
                            <li>• User dengan status <span class="text-yellow-300">PENDING</span> menunggu persetujuan untuk bergabung.</li>
                            <li>• User dengan status <span class="text-[#CCFF00]">APPROVED</span> dapat membuat event atas nama organisasi.</li>
                            <li>• User dengan status <span class="text-[#ED1C24]">REJECTED</span> dapat mengajukan ulang permohonan.</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
