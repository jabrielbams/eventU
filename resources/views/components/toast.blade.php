<!-- Toast Notification Component -->
<div x-data="{
    toasts: [],
    addToast(message, type = 'success') {
        const id = Date.now();
        this.toasts.push({ id, message, type });
        setTimeout(() => this.removeToast(id), 5000);
    },
    removeToast(id) {
        this.toasts = this.toasts.filter(t => t.id !== id);
    }
}"
x-init="
    @if(session('success'))
        addToast('{{ session('success') }}', 'success');
    @endif
    @if(session('error'))
        addToast('{{ session('error') }}', 'error');
    @endif
"
class="fixed top-6 right-6 z-[100] flex flex-col gap-3 max-w-md">

    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="true"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-8"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-8"
             class="relative border-[3px] border-black shadow-[6px_6px_0px_#000] p-4 pr-12 font-bold uppercase text-sm"
             :class="toast.type === 'success' ? 'bg-[#CCFF00] text-black' : 'bg-industrial-red text-white'">

            <!-- Message -->
            <span x-text="toast.message"></span>

            <!-- Close Button -->
            <button @click="removeToast(toast.id)"
                    class="absolute top-1/2 right-3 -translate-y-1/2 w-6 h-6 flex items-center justify-center font-black text-lg hover:scale-110 transition-transform"
                    :class="toast.type === 'success' ? 'text-black' : 'text-white'">
                ×
            </button>

            <!-- Progress Bar -->
            <div class="absolute bottom-0 left-0 h-1 bg-black/30"
                 :class="toast.type === 'success' ? 'bg-black/30' : 'bg-white/30'"
                 x-init="$el.style.width = '100%'; $el.style.transition = 'width 5s linear'; setTimeout(() => $el.style.width = '0%', 50)">
            </div>
        </div>
    </template>
</div>
