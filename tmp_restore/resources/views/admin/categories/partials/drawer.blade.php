{{-- Category Create Drawer --}}
<div id="create-drawer" class="fixed inset-0 z-[100] hidden overflow-hidden transition-all duration-500">
    <div id="create-overlay" class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 transition-opacity duration-300" onclick="closeCreateDrawer()"></div>
    <div id="create-panel" class="absolute inset-y-0 right-0 w-full md:max-w-lg bg-white dark:bg-surface-tonal-a20 shadow-2xl transform translate-x-full transition-transform duration-500 ease-in-out flex flex-col">
        <div class="flex items-center justify-between px-8 py-6 border-b border-gray-100 dark:border-white/5">
            <div>
                <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">Add New Category</h3>
                <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mt-1">Catalog Structure</p>
            </div>
            <button onclick="closeCreateDrawer()" class="p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 text-gray-400 hover:text-gray-900 dark:hover:text-white transition-all">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto custom-scrollbar p-8">
            <form id="create-form" class="space-y-8" enctype="multipart/form-data">
                @csrf
                
                {{-- Category Name --}}
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Category Name</label>
                    <input type="text" name="name" id="create-name" required placeholder="e.g. Tablets & Electronics" 
                        class="w-full bg-gray-50 dark:bg-surface-tonal-a30 border-0 rounded-2xl px-5 py-4 text-sm font-bold text-gray-900 dark:text-white placeholder:text-gray-300 focus:ring-2 focus:ring-emerald-500/20 transition-all shadow-inner">
                </div>

                {{-- Parent Category --}}
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Parent Category</label>
                    <div class="relative">
                        <select name="parent_id" id="create-parent" class="w-full bg-gray-50 dark:bg-surface-tonal-a30 border-0 rounded-2xl px-5 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 transition-all appearance-none cursor-pointer">
                            <option value="">None (Root Level)</option>
                            @php 
                                $parents = \App\Models\Category::with('children.children')->whereNull('parent_id')->orderBy('name')->get();
                            @endphp
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @foreach($parent->children as $child)
                                    <option value="{{ $child->id }}">&nbsp;&nbsp;— {{ $child->name }}</option>
                                    @foreach($child->children as $grandchild)
                                        <option value="{{ $grandchild->id }}">&nbsp;&nbsp;&nbsp;&nbsp;—— {{ $grandchild->name }}</option>
                                    @endforeach
                                @endforeach
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    {{-- Status --}}
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Status</label>
                        <div class="relative">
                            <select name="is_active" id="create-status" class="w-full bg-gray-50 dark:bg-surface-tonal-a30 border-0 rounded-2xl px-5 py-4 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 transition-all appearance-none cursor-pointer">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Thumbnail Link/Hint --}}
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Thumbnail</label>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gray-50 dark:bg-surface-tonal-a30 flex items-center justify-center overflow-hidden border border-gray-100 dark:border-white/5" id="mini-preview-container">
                                <svg id="mini-placeholder" class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <img id="mini-preview" class="hidden w-full h-full object-cover">
                            </div>
                            <button type="button" onclick="document.getElementById('create-image').click()" 
                                class="px-4 py-2 bg-white dark:bg-surface-tonal-a30 border border-gray-100 dark:border-white/5 text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-gray-50 transition-all shadow-sm">
                                Choose file
                            </button>
                            <input type="file" name="image" id="create-image" accept="image/*" class="hidden" onchange="previewImage(this)">
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Description</label>
                    <phone:textarea name="description" id="create-description" rows="5" placeholder="Enter category details..." 
                        class="w-full bg-gray-50 dark:bg-surface-tonal-a30 border-0 rounded-2xl px-5 py-4 text-sm font-bold text-gray-900 dark:text-white placeholder:text-gray-300 focus:ring-2 focus:ring-emerald-500/20 transition-all resize-none shadow-inner"></textarea>
                </div>
            </form>
        </div>

        <div class="px-8 py-6 bg-gray-100/50 dark:bg-surface-tonal-a10 border-t border-gray-100 dark:border-white/5 flex gap-4">
            <button onclick="closeCreateDrawer()" class="flex-1 px-6 py-4 bg-white dark:bg-surface-tonal-a30 border border-gray-100 dark:border-white/5 text-gray-500 hover:text-gray-900 dark:hover:text-white font-black text-xs uppercase tracking-[0.2em] rounded-2xl transition-all shadow-sm active:scale-95">
                Cancel
            </button>
            <button type="submit" form="create-form" id="submit-btn" class="flex-[1.5] flex items-center justify-center gap-2 px-6 py-4 bg-emerald-400 hover:bg-emerald-500 text-emerald-950 font-black text-xs uppercase tracking-[0.15em] rounded-2xl transition-all shadow-lg shadow-emerald-500/20 active:scale-[0.98]">
                <span id="btn-text">Create Category</span>
                <svg id="loading-spinner" class="animate-spin h-4 w-4 hidden" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const drawer = document.getElementById('create-drawer');
        const overlay = document.getElementById('create-overlay');
        const panel = document.getElementById('create-panel');
        const form = document.getElementById('create-form');

        window.openCreateDrawer = () => {
            drawer.classList.remove('hidden');
            setTimeout(() => { 
                overlay.classList.add('opacity-100'); 
                panel.classList.remove('translate-x-full'); 
            }, 10);
            document.body.style.overflow = 'hidden';
            resetImagePreview();
        };

        window.closeCreateDrawer = () => {
            overlay.classList.remove('opacity-100');
            panel.classList.add('translate-x-full');
            document.body.style.overflow = '';
            setTimeout(() => drawer.classList.add('hidden'), 500);
        };

        window.previewImage = (input) => {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    document.getElementById('mini-preview').src = e.target.result;
                    document.getElementById('mini-preview').classList.remove('hidden');
                    document.getElementById('mini-placeholder').classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        };

        window.resetImagePreview = () => {
            const preview = document.getElementById('mini-preview');
            const placeholder = document.getElementById('mini-placeholder');
            if (preview) preview.classList.add('hidden');
            if (placeholder) placeholder.classList.remove('hidden');
            const input = document.getElementById('create-image');
            if (input) input.value = '';
        };

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = document.getElementById('submit-btn');
            const btnText = document.getElementById('btn-text');
            const spinner = document.getElementById('loading-spinner');

            btn.disabled = true;
            btn.classList.add('opacity-50', 'pointer-events-none');
            btnText.classList.add('hidden');
            spinner.classList.remove('hidden');

            fetch('{{ route('categories.store') }}', {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Success behavior based on page context
                    if (typeof table !== 'undefined' && table.draw) {
                        table.draw(false);
                    }
                    
                    // Specific to product create page: update select
                    const catSelect = document.querySelector('select[name="categories[]"]');
                    if (catSelect && data.category) {
                        const option = new Option(data.category.name, data.category.id, true, true);
                        catSelect.appendChild(option);
                        $(catSelect).trigger('change');
                    }

                    closeCreateDrawer();
                    if (typeof showNotification === 'function') {
                        showNotification('Success', data.message, 'success');
                    }
                    form.reset();
                } else {
                    if (typeof showNotification === 'function') {
                        showNotification('Error', data.message || 'Something went wrong', 'error');
                    }
                }
            })
            .catch(err => {
                console.error(err);
                if (typeof showNotification === 'function') {
                    showNotification('Error', 'An unexpected error occurred', 'error');
                }
            })
            .finally(() => {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'pointer-events-none');
                btnText.classList.remove('hidden');
                spinner.classList.add('hidden');
            });
        });
    });
</script>
