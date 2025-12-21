@extends('layouts.app')

@section('title', $event['title'] ?? 'Detail Event')

@section('content')
<div class="py-10 px-4">

    <!-- Back Button -->
    <div class="max-w-6xl mx-auto mb-8">
        <a href="{{ route('events.index') }}" class="neo-button-default inline-block">
            &larr; Kembali ke Daftar Event
        </a>
    </div>

    <!-- Event Detail Container -->
    <div class="max-w-6xl mx-auto">
        <div class="bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] overflow-hidden relative">
            <!-- Rivets -->
            <div class="absolute w-2 h-2 bg-black rounded-full top-2 left-2 z-10"></div>
            <div class="absolute w-2 h-2 bg-black rounded-full top-2 right-2 z-10"></div>

            @php
                $imageUrl = $event['image_url'] ?? 'https://placehold.co/800x1200';
                $categoryName = $event['category']['name'] ?? 'Event';
                $organizerName = $event['organization']['name'] ?? 'Unknown';

                // Format date and time
                $dateFormatted = \Carbon\Carbon::parse($event['date'])->locale('id')->isoFormat('dddd, D MMMM YYYY');
                $timeFormatted = \Carbon\Carbon::parse($event['time'])->format('H:i') . ' WIB';
            @endphp

            <!-- Event Poster - TOP -->
            <div class="relative w-full h-[400px] md:h-[500px] bg-gray-100">
                <img src="{{ $imageUrl }}"
                     class="w-full h-full object-contain"
                     alt="Event Poster">
            </div>

            <!-- Event Details - BOTTOM -->
            <div class="p-8 md:p-12 border-t-[3px] border-black">
                <!-- Category Badge -->
                <span class="inline-block bg-industrial-red text-white px-4 py-2 border-[2px] border-black shadow-[2px_2px_0px_0px_#000] font-black uppercase text-sm mb-6">
                    {{ $categoryName }}
                </span>

                <!-- Title -->
                <h1 class="text-3xl md:text-5xl font-black uppercase leading-[0.95] mb-8 tracking-tight">
                    {{ $event['title'] }}
                </h1>

                <!-- Meta Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 pb-8 border-b-[3px] border-black">
                    <div class="bg-gray-50 p-4 border-[2px] border-black">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg">📅</span>
                            <strong class="text-xs font-black uppercase text-gray-600">Tanggal</strong>
                        </div>
                        <span class="text-lg font-bold block font-mono">{{ $dateFormatted }}</span>
                    </div>
                    <div class="bg-gray-50 p-4 border-[2px] border-black">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg">🕒</span>
                            <strong class="text-xs font-black uppercase text-gray-600">Waktu</strong>
                        </div>
                        <span class="text-lg font-bold block font-mono">{{ $timeFormatted }}</span>
                    </div>
                    <div class="bg-gray-50 p-4 border-[2px] border-black">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg">📍</span>
                            <strong class="text-xs font-black uppercase text-gray-600">Lokasi</strong>
                        </div>
                        <span class="text-lg font-bold block font-mono">{{ $event['location'] }}</span>
                    </div>
                    <div class="bg-gray-50 p-4 border-[2px] border-black">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg">🏢</span>
                            <strong class="text-xs font-black uppercase text-gray-600">Penyelenggara</strong>
                        </div>
                        <a href="{{ route('organizations.show', $event['organization']['id']) }}"
                           class="text-lg font-bold block font-mono hover:text-industrial-red hover:underline transition-colors">
                            {{ $organizerName }}
                        </a>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <h2 class="text-xl font-black uppercase mb-4 tracking-tight">Deskripsi Event</h2>
                    <p class="text-base leading-relaxed text-gray-700 font-medium">
                        {{ $event['description'] }}
                    </p>
                </div>

                @if(auth()->user() && auth()->user()->role === 'organizer')
                    <!-- Organizer Actions -->
                    <div class="pt-6 border-t-[3px] border-black flex flex-col gap-4">
                        <a href="{{ route('events.registrants', $event['id']) }}"
                            class="block w-full text-center bg-white text-black font-black uppercase py-4 px-6 border-[3px] border-black shadow-[6px_6px_0px_#000] hover:bg-black hover:text-white hover:-translate-y-[2px] hover:-translate-x-[2px] hover:shadow-[8px_8px_0px_#000] transition-all">
                            Kelola Peserta
                        </a>
                        <a href="{{ route('announcements.index', ['event' => $event['id']]) }}"
                            class="block w-full text-center bg-industrial-red text-white font-black uppercase py-4 px-6 border-[3px] border-black shadow-[6px_6px_0px_#000] hover:bg-white hover:text-industrial-red hover:-translate-y-[2px] hover:-translate-x-[2px] hover:shadow-[8px_8px_0px_#000] transition-all">
                            Kelola Pengumuman Event
                        </a>
                        <a href="{{ route('announcements.create', ['event' => $event['id']]) }}"
                            class="block w-full text-center bg-white text-industrial-red font-black uppercase py-4 px-6 border-[3px] border-black shadow-[6px_6px_0px_#000] hover:bg-industrial-red hover:text-white hover:-translate-y-[2px] hover:-translate-x-[2px] hover:shadow-[8px_8px_0px_#000] transition-all">
                            + Buat Pengumuman Baru
                        </a>
                    </div>
                @endif

                <!-- Student Actions -->
                @if(auth()->user() && auth()->user()->role === 'student')
                    <div class="pt-6 border-t-[3px] border-black">
                        <div class="flex gap-4 items-center">
                            <!-- Register Button -->
                            <form method="POST" action="{{ route('events.register', $event['id']) }}" class="flex-1">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-industrial-red text-white font-black uppercase text-xl py-4 px-6 border-[3px] border-black shadow-[6px_6px_0px_#000] hover:bg-red-700 hover:-translate-y-[2px] hover:-translate-x-[2px] hover:shadow-[8px_8px_0px_#000] transition-all">
                                    Daftar Event
                                </button>
                            </form>

                            <!-- Bookmark Button -->
                            @if (isset($event['is_bookmarked']) && $event['is_bookmarked'])
                                <!-- Unbookmark Form -->
                                <form method="POST" action="{{ route('events.unbookmark', $event['id']) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-industrial-red text-white font-black uppercase px-6 py-4 border-[3px] border-black shadow-[6px_6px_0px_#000] hover:bg-red-700 hover:-translate-y-[2px] hover:-translate-x-[2px] hover:shadow-[8px_8px_0px_#000] transition-all"
                                        title="Hapus Bookmark">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/>
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <!-- Bookmark Form -->
                                <form method="POST" action="{{ route('events.bookmark', $event['id']) }}">
                                    @csrf
                                    <button type="submit"
                                        class="bg-white text-black font-black uppercase px-6 py-4 border-[3px] border-black shadow-[6px_6px_0px_#000] hover:bg-black hover:text-white hover:-translate-y-[2px] hover:-translate-x-[2px] hover:shadow-[8px_8px_0px_#000] transition-all"
                                        title="Tambah Bookmark">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 19V5z"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="mt-8 bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] p-8">
            <h2 class="text-2xl font-black uppercase mb-6 tracking-tight flex items-center gap-2">
                <span>⭐</span> Review Event
            </h2>

            @php
                $eventDate = \Carbon\Carbon::parse($event['date']);
                $isPastEvent = $eventDate->isPast() || (isset($event['status']) && $event['status'] === 'completed');
            @endphp

            <!-- Review Form (only for students who attended past/completed events and haven't reviewed yet) -->
            @if(auth()->check() && auth()->user()->role === 'student' && $isPastEvent && !$userReview)
                <div class="mb-8 p-6 bg-gray-50 border-[2px] border-black">
                    <h3 class="text-lg font-black uppercase mb-4">Tulis Review Anda</h3>
                    <form method="POST" action="{{ route('reviews.store', $event['id']) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-black uppercase mb-2">Rating</label>
                            <div class="flex gap-2" id="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" required>
                                        <span class="text-3xl text-gray-300 peer-checked:text-yellow-500 hover:text-yellow-400 transition-colors star-icon" data-rating="{{ $i }}">★</span>
                                    </label>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="review-comment" class="block text-sm font-black uppercase mb-2">Komentar</label>
                            <textarea name="comment" id="review-comment" rows="3" 
                                class="w-full px-4 py-3 border-[2px] border-black font-medium focus:outline-none focus:ring-2 focus:ring-industrial-red" 
                                placeholder="Bagikan pengalaman Anda tentang event ini..." required maxlength="1000"></textarea>
                        </div>
                        <button type="submit" 
                            class="bg-industrial-red text-white font-black uppercase py-3 px-6 border-[2px] border-black shadow-[4px_4px_0px_#000] hover:bg-red-700 hover:-translate-y-[1px] hover:-translate-x-[1px] hover:shadow-[5px_5px_0px_#000] transition-all">
                            Kirim Review
                        </button>
                    </form>
                </div>
            @elseif(!$isPastEvent)
                <div class="mb-8 p-4 bg-yellow-50 border-[2px] border-yellow-400">
                    <p class="text-yellow-800 font-medium">⚠️ Review hanya dapat diberikan setelah event selesai.</p>
                </div>
            @elseif($userReview)
                <div class="mb-8 p-4 bg-green-50 border-[2px] border-green-400">
                    <p class="text-green-800 font-medium">✓ Anda sudah memberikan review untuk event ini.</p>
                </div>
            @elseif(auth()->check() && auth()->user()->role !== 'student')
                <div class="mb-8 p-4 bg-blue-50 border-[2px] border-blue-400">
                    <p class="text-blue-800 font-medium">ℹ️ Hanya mahasiswa yang dapat memberikan review.</p>
                </div>
            @elseif(!auth()->check())
                <div class="mb-8 p-4 bg-gray-50 border-[2px] border-gray-400">
                    <p class="text-gray-800 font-medium">🔐 Silakan login untuk memberikan review.</p>
                </div>
            @endif

            <!-- Reviews List -->
            @if(isset($reviews) && $reviews->count() > 0)
                <div class="space-y-4">
                    @foreach($reviews as $review)
                        <div class="p-4 bg-gray-50 border-[2px] border-black" id="review-{{ $review->id }}">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="font-bold">{{ $review->user->name ?? 'Anonymous' }}</span>
                                    <div class="flex gap-1 mt-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="text-lg {{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }}">★</span>
                                        @endfor
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500 font-mono">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-700">{{ $review->comment }}</p>
                            
                            @if(auth()->check() && (auth()->id() === $review->user_id || (auth()->user()->role === 'organizer' && ($event['user']['id'] ?? null) === auth()->id())))
                                <div class="mt-3 flex gap-2">
                                    @if(auth()->id() === $review->user_id)
                                        <button type="button" onclick="toggleEditReview({{ $review->id }})" 
                                            class="text-sm bg-white text-black font-bold uppercase py-1 px-3 border-[2px] border-black shadow-[2px_2px_0px_#000] hover:bg-black hover:text-white transition-all">
                                            Edit
                                        </button>
                                    @endif
                                    <form method="POST" action="{{ route('reviews.destroy', $review->id) }}" class="inline" onsubmit="return confirm('Hapus review ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                            class="text-sm bg-red-500 text-white font-bold uppercase py-1 px-3 border-[2px] border-black shadow-[2px_2px_0px_#000] hover:bg-red-700 transition-all">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                                
                                @if(auth()->id() === $review->user_id)
                                    <!-- Edit Review Form (hidden by default) -->
                                    <div class="mt-4 hidden" id="edit-review-{{ $review->id }}">
                                        <form method="POST" action="{{ route('reviews.update', $review->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label class="block text-sm font-bold mb-1">Rating</label>
                                                <div class="flex gap-2 star-rating-container" data-current-rating="{{ $review->rating }}">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <label class="cursor-pointer">
                                                            <input type="radio" name="rating" value="{{ $i }}" class="hidden" {{ $review->rating == $i ? 'checked' : '' }} required>
                                                            <span class="text-2xl {{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }} hover:text-yellow-400 transition-colors">★</span>
                                                        </label>
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <textarea name="comment" rows="2" 
                                                    class="w-full px-3 py-2 border-[2px] border-black font-medium focus:outline-none focus:ring-2 focus:ring-industrial-red" 
                                                    required maxlength="1000">{{ $review->comment }}</textarea>
                                            </div>
                                            <div class="flex gap-2">
                                                <button type="submit" 
                                                    class="text-sm bg-green-500 text-white font-bold uppercase py-1 px-3 border-[2px] border-black shadow-[2px_2px_0px_#000] hover:bg-green-700 transition-all">
                                                    Simpan
                                                </button>
                                                <button type="button" onclick="toggleEditReview({{ $review->id }})" 
                                                    class="text-sm bg-gray-300 text-black font-bold uppercase py-1 px-3 border-[2px] border-black shadow-[2px_2px_0px_#000] hover:bg-gray-400 transition-all">
                                                    Batal
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 font-medium">Belum ada review untuk event ini.</p>
            @endif
        </div>

        <!-- Comments Section -->
        <div class="mt-8 bg-white border-[3px] border-black shadow-[8px_8px_0px_#000] p-8">
            <h2 class="text-2xl font-black uppercase mb-6 tracking-tight flex items-center gap-2">
                <span>💬</span> Diskusi
            </h2>

            <!-- Comment Form -->
            @auth
                <div class="mb-8 p-6 bg-gray-50 border-[2px] border-black">
                    <h3 class="text-lg font-black uppercase mb-4">Tulis Komentar</h3>
                    <form method="POST" action="{{ route('comments.store', $event['id']) }}">
                        @csrf
                        <div class="mb-4">
                            <textarea name="content" rows="3" 
                                class="w-full px-4 py-3 border-[2px] border-black font-medium focus:outline-none focus:ring-2 focus:ring-industrial-red" 
                                placeholder="Tulis komentar atau pertanyaan Anda..." required maxlength="1000"></textarea>
                        </div>
                        <button type="submit" 
                            class="bg-black text-white font-black uppercase py-3 px-6 border-[2px] border-black shadow-[4px_4px_0px_#666] hover:bg-gray-800 hover:-translate-y-[1px] hover:-translate-x-[1px] hover:shadow-[5px_5px_0px_#666] transition-all">
                            Kirim Komentar
                        </button>
                    </form>
                </div>
            @endauth

            <!-- Comments List -->
            @if(isset($comments) && $comments->count() > 0)
                <div class="space-y-4">
                    @foreach($comments as $comment)
                        <div class="p-4 bg-gray-50 border-[2px] border-black" id="comment-{{ $comment->id }}">
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-bold">{{ $comment->user->name ?? 'Anonymous' }}</span>
                                <span class="text-sm text-gray-500 font-mono">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-700">{{ $comment->content }}</p>
                            
                            @if(auth()->check() && (auth()->id() === $comment->user_id || (auth()->user()->role === 'organizer' && ($event['user']['id'] ?? null) === auth()->id())))
                                <div class="mt-3 flex gap-2">
                                    @if(auth()->id() === $comment->user_id)
                                        <button type="button" onclick="toggleEditComment({{ $comment->id }})" 
                                            class="text-sm bg-white text-black font-bold uppercase py-1 px-3 border-[2px] border-black shadow-[2px_2px_0px_#000] hover:bg-black hover:text-white transition-all">
                                            Edit
                                        </button>
                                    @endif
                                    <form method="POST" action="{{ route('comments.destroy', $comment->id) }}" class="inline" onsubmit="return confirm('Hapus komentar ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                            class="text-sm bg-red-500 text-white font-bold uppercase py-1 px-3 border-[2px] border-black shadow-[2px_2px_0px_#000] hover:bg-red-700 transition-all">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                                
                                @if(auth()->id() === $comment->user_id)
                                    <!-- Edit Comment Form (hidden by default) -->
                                    <div class="mt-4 hidden" id="edit-comment-{{ $comment->id }}">
                                        <form method="POST" action="{{ route('comments.update', $comment->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <textarea name="content" rows="2" 
                                                    class="w-full px-3 py-2 border-[2px] border-black font-medium focus:outline-none focus:ring-2 focus:ring-industrial-red" 
                                                    required maxlength="1000">{{ $comment->content }}</textarea>
                                            </div>
                                            <div class="flex gap-2">
                                                <button type="submit" 
                                                    class="text-sm bg-green-500 text-white font-bold uppercase py-1 px-3 border-[2px] border-black shadow-[2px_2px_0px_#000] hover:bg-green-700 transition-all">
                                                    Simpan
                                                </button>
                                                <button type="button" onclick="toggleEditComment({{ $comment->id }})" 
                                                    class="text-sm bg-gray-300 text-black font-bold uppercase py-1 px-3 border-[2px] border-black shadow-[2px_2px_0px_#000] hover:bg-gray-400 transition-all">
                                                    Batal
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 font-medium">Belum ada komentar untuk event ini.</p>
            @endif
        </div>
    </div>
</div>

<script>
    function toggleEditComment(commentId) {
        const editForm = document.getElementById('edit-comment-' + commentId);
        if (editForm) {
            editForm.classList.toggle('hidden');
        }
    }

    function toggleEditReview(reviewId) {
        const editForm = document.getElementById('edit-review-' + reviewId);
        if (editForm) {
            editForm.classList.toggle('hidden');
        }
    }

    // Star rating interactive selection - works for all star rating containers
    document.addEventListener('DOMContentLoaded', function() {
        // Handle all radio inputs for ratings
        document.querySelectorAll('input[name="rating"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const rating = parseInt(this.value);
                const container = this.closest('.flex');
                if (container) {
                    const stars = container.querySelectorAll('span');
                    stars.forEach((star, index) => {
                        if (index < rating) {
                            star.classList.remove('text-gray-300');
                            star.classList.add('text-yellow-500');
                        } else {
                            star.classList.remove('text-yellow-500');
                            star.classList.add('text-gray-300');
                        }
                    });
                }
            });
        });

        // Also handle click on star spans for visual feedback
        document.querySelectorAll('label input[name="rating"] + span').forEach(star => {
            star.addEventListener('click', function() {
                const input = this.previousElementSibling;
                if (input && input.type === 'radio') {
                    const rating = parseInt(input.value);
                    const container = this.closest('.flex');
                    if (container) {
                        const stars = container.querySelectorAll('span');
                        stars.forEach((s, index) => {
                            if (index < rating) {
                                s.classList.remove('text-gray-300');
                                s.classList.add('text-yellow-500');
                            } else {
                                s.classList.remove('text-yellow-500');
                                s.classList.add('text-gray-300');
                            }
                        });
                    }
                }
            });
        });
    });
</script>
@endsection
