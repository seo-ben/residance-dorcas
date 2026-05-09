<div 
    x-data="{ 
        toasts: [],
        addToast(message, type = 'info') {
            const id = Date.now();
            this.toasts.push({ id, message, type });
            setTimeout(() => {
                this.removeToast(id);
            }, 5000);
        },
        removeToast(id) {
            this.toasts = this.toasts.filter(toast => toast.id !== id);
        }
    }"
    @toast.window="addToast($event.detail.message, $event.detail.type)"
    class="fixed bottom-5 right-5 z-[100] flex flex-col space-y-3 max-w-sm w-full pointer-events-none"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div 
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-10"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-10"
            class="pointer-events-auto flex items-center p-4 rounded-2xl shadow-xl border bg-white"
            :class="{
                'border-green-100 bg-green-50 text-green-800': toast.type === 'success',
                'border-red-100 bg-red-50 text-red-800': toast.type === 'error',
                'border-blue-100 bg-blue-50 text-blue-800': toast.type === 'info',
                'border-yellow-100 bg-yellow-50 text-yellow-800': toast.type === 'warning'
            }"
        >
            <div class="flex-shrink-0 mr-3">
                <template x-if="toast.type === 'success'">
                    <i class="fas fa-check-circle text-green-500"></i>
                </template>
                <template x-if="toast.type === 'error'">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </template>
                <template x-if="toast.type === 'info'">
                    <i class="fas fa-info-circle text-blue-500"></i>
                </template>
                <template x-if="toast.type === 'warning'">
                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                </template>
            </div>
            <div class="flex-1 text-sm font-medium" x-text="toast.message"></div>
            <button @click="removeToast(toast.id)" class="ml-4 text-gray-400 hover:text-gray-600 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </template>
</div>

<script>
    window.toast = {
        success(message) {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message, type: 'success' } }));
        },
        error(message) {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message, type: 'error' } }));
        },
        info(message) {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message, type: 'info' } }));
        },
        warning(message) {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message, type: 'warning' } }));
        }
    };
</script>
