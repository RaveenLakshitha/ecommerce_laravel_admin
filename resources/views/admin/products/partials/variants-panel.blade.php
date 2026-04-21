{{--
    Shared Variants Panel Partial
    Variables expected (passed from including view):
      $existingOptionsJson  - JSON string: [{name: "Color", values: ["red","green"]}, ...]
      $existingVariantsJson - JSON string: [{id,sku,price,barcode,stock_quantity,opts:{0:"red",1:"L"},image_url}, ...]
      $basePrice            - float: default price for new rows
--}}

<div class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-50/50 dark:bg-surface-tonal-a20">
        <h2 class="text-sm font-bold text-gray-900 dark:text-white">Options &amp; Variants</h2>
        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Define options to auto-generate all variant combinations</p>
    </div>

    <div class="p-4 space-y-4">

        {{-- Option Rows (rendered by JS) --}}
        <div id="vp-options-list" class="space-y-3"></div>

        {{-- Add Option Button --}}
        <button type="button" onclick="VariantsPanel.addOption()"
            class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-surface-tonal-a30 rounded-lg hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add another option
        </button>

        {{-- Variants Table (shown when there are combos) --}}
        <div id="vp-table-wrapper" class="hidden">
            <p class="text-xs font-bold text-gray-600 dark:text-gray-300 mb-3">Modify the variants to be created:</p>
            <div class="overflow-x-auto rounded-lg border border-gray-100 dark:border-surface-tonal-a30">
                <table class="w-full min-w-[700px] text-left border-collapse">
                    <thead class="bg-gray-50 dark:bg-surface-tonal-a20">
                        <tr>
                            <th class="px-3 py-2.5">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="vp-select-all" onchange="VariantsPanel.toggleAll(this)"
                                        class="h-3.5 w-3.5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Variant</span>
                                </div>
                            </th>
                            <th class="px-3 py-2.5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Price</th>
                            <th class="px-3 py-2.5 text-[10px] font-black text-gray-400 uppercase tracking-widest">SKU</th>
                            <th class="px-3 py-2.5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Inventory</th>
                            <th class="px-3 py-2.5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Image</th>
                        </tr>
                    </thead>
                    <tbody id="vp-variants-tbody" class="divide-y divide-gray-50 dark:divide-surface-tonal-a30">
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
const VariantsPanel = {
    options: [],           // [{name, values}]
    rowState: {},          // "val0|val1|..." => {id, sku, price, barcode, stock_quantity, image_url, enabled}
    availableAttributes: [], // [{name, values: []}]
    basePrice: 0,
    baseSku: '',
    selectedFiles: {},     // "key" => File object

    PILL_COLORS: [
        'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300',
        'bg-violet-100 dark:bg-violet-500/20 text-violet-700 dark:text-violet-300',
        'bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300',
        'bg-sky-100 dark:bg-sky-500/20 text-sky-700 dark:text-sky-300',
    ],
    LABEL_COLORS: [
        'text-emerald-600 dark:text-emerald-400 font-bold',
        'text-violet-600 dark:text-violet-400 font-bold',
        'text-amber-600 dark:text-amber-400 font-bold',
        'text-sky-600 dark:text-sky-400 font-bold',
    ],

    init(existingOptions, existingVariants, basePrice, allAttributes) {
        this.basePrice = parseFloat(basePrice) || 0;
        this.availableAttributes = allAttributes || [];

        // Initialize base SKU from product name if it exists
        const nameInput = document.getElementById('name');
        if (nameInput) {
            this.baseSku = this.generateBaseSku(nameInput.value);
            nameInput.addEventListener('input', (e) => {
                const oldBase = this.baseSku;
                this.baseSku = this.generateBaseSku(e.target.value);
                // If baseSku changed, we might want to refresh the table if any rows are using the default
                if (oldBase !== this.baseSku) {
                    this.renderVariantsTable();
                }
            });
        }

        // Build row state from server-side variant data
        (existingVariants || []).forEach(v => {
            const opts = v.opts || {};
            const sortedKeys = Object.keys(opts).map(Number).sort();
            const key = sortedKeys.map(k => opts[k]).join('|');
            this.rowState[key] = {
                id: v.id || '',
                sku: v.sku || '',
                price: parseFloat(v.price) || this.basePrice,
                barcode: v.barcode || '',
                stock_quantity: parseInt(v.stock_quantity) || 0,
                image_url: v.image_url || '',
            };
        });

        if (existingOptions && existingOptions.length > 0) {
            this.options = existingOptions.map(o => ({ name: o.name, values: [...(o.values || [])] }));
        } else {
            this.options = [{ name: '', values: [] }];
        }

        this.renderAll();

        // Attach pre-submit hook to save typed values before submit
        const forms = ['create-product-form', 'edit-product-form'];
        forms.forEach(id => {
            const f = document.getElementById(id);
            if (f) f.addEventListener('submit', () => {
                this.saveCurrentTableState();
                this.renderAll();
            });
        });
    },

    generateBaseSku(name) {
        if (!name) return '';
        const words = name.trim().split(/\s+/).filter(w => w.length > 0).slice(0, 4);
        return words.map(w => w[0].toUpperCase()).join('');
    },

    // ── Options Management ──────────────────────────────────────────────

    addOption() {
        this.saveCurrentTableState();
        this.options.push({ name: '', values: [] });
        this.renderAll();
        // Focus the new name input
        setTimeout(() => {
            const inputs = document.querySelectorAll('#vp-options-list .vp-opt-name');
            const last = inputs[inputs.length - 1];
            if (last) last.focus();
        }, 50);
    },

    removeOption(index) {
        this.saveCurrentTableState();
        this.options.splice(index, 1);
        if (this.options.length === 0) this.options = [{ name: '', values: [] }];
        this.renderAll();
    },

    updateOptionName(index, value) {
        if (this.options[index]) {
            this.options[index].name = value;
            // Clear values if name is changed? Shopify does this if it's a major change,
            // but for flexibility we'll keep them unless the user explicitly removes them.
        }
        // Re-render table and value suggestions
        this.renderVariantsTable();
        this.renderValueSuggestions(index);
    },

    handleTagKey(event, optIndex) {
        if (event.key === 'Enter' || event.key === ',') {
            event.preventDefault();
            this.addTagFromInput(optIndex, event.target.value);
        } else if (event.key === 'Backspace' && event.target.value === '') {
            const vals = this.options[optIndex].values;
            if (vals.length > 0) {
                this.saveCurrentTableState();
                vals.pop();
                this.renderAll();
                setTimeout(() => {
                    const ti = document.getElementById(`vp-tag-input-${optIndex}`);
                    if (ti) ti.focus();
                }, 20);
            }
        }
    },

    addTagFromInput(optIndex, value) {
        const raw = value.replace(/,/g, '').trim();
        if (raw) {
            this.saveCurrentTableState();
            if (!this.options[optIndex].values.includes(raw)) {
                this.options[optIndex].values.push(raw);
            }
            const ti = document.getElementById(`vp-tag-input-${optIndex}`);
            if (ti) ti.value = '';
            this.renderAll();
            // Re-focus tag input after re-render
            setTimeout(() => {
                const ti = document.getElementById(`vp-tag-input-${optIndex}`);
                if (ti) ti.focus();
            }, 20);
        }
    },

    removeTag(optIndex, tagIndex) {
        this.saveCurrentTableState();
        this.options[optIndex].values.splice(tagIndex, 1);
        this.renderAll();
    },

    // ── Rendering ───────────────────────────────────────────────────────

    renderAll() {
        this.renderOptionRows();
        this.renderVariantsTable();
    },

    renderOptionRows() {
        const container = document.getElementById('vp-options-list');
        if (!container) return;
        container.innerHTML = '';

        // Datalist for attribute names
        let datalistId = 'vp-attr-names-list';
        let datalist = document.getElementById(datalistId);
        if (!datalist) {
            datalist = document.createElement('datalist');
            datalist.id = datalistId;
            this.availableAttributes.forEach(attr => {
                const opt = document.createElement('option');
                opt.value = attr.name;
                datalist.appendChild(opt);
            });
            document.body.appendChild(datalist);
        }

        this.options.forEach((opt, optIndex) => {
            const pillColor = this.PILL_COLORS[optIndex % this.PILL_COLORS.length];
            const canDelete = this.options.length > 1;

            const tagPills = opt.values.map((v, vi) =>
                `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold whitespace-nowrap ${pillColor}">
                    ${this.esc(v)}
                    <button type="button"
                        onclick="event.stopPropagation();VariantsPanel.removeTag(${optIndex},${vi})"
                        class="leading-none opacity-60 hover:opacity-100 transition-opacity ml-0.5">&times;</button>
                </span>`
            ).join('');

            const row = document.createElement('div');
            row.className = 'flex flex-col gap-2';
            row.innerHTML = `
                <div class="flex items-stretch gap-3">
                    <div class="w-40 flex-shrink-0">
                        <input type="text"
                            list="${datalistId}"
                            class="vp-opt-name block w-full h-full rounded-md border border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-surface-tonal-a20 px-3 py-2 text-xs font-bold text-black dark:text-white outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-500/10 transition-all"
                            placeholder="Option name (e.g. Color)"
                            value="${this.esc(opt.name)}"
                            oninput="VariantsPanel.updateOptionName(${optIndex}, this.value)">
                    </div>
                    <div class="flex-1 flex flex-wrap items-center gap-1.5 min-h-[38px] rounded-md border border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-surface-tonal-a20 px-2 py-1.5 cursor-text"
                        onclick="document.getElementById('vp-tag-input-${optIndex}').focus()">
                        ${tagPills}
                        <input
                            id="vp-tag-input-${optIndex}"
                            type="text"
                            onfocus="VariantsPanel.renderValueSuggestions(${optIndex})"
                            placeholder="${opt.values.length === 0 ? 'Add values and press Enter…' : ''}"
                            class="flex-1 min-w-[120px] bg-transparent outline-none text-xs font-bold text-gray-700 dark:text-white placeholder:text-gray-400 placeholder:font-normal"
                            onkeydown="VariantsPanel.handleTagKey(event, ${optIndex})">
                    </div>
                    ${canDelete
                        ? `<button type="button" onclick="VariantsPanel.removeOption(${optIndex})"
                                class="p-2 text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors flex-shrink-0 self-center rounded-lg hover:bg-red-50 dark:hover:bg-red-950/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>`
                        : '<div class="w-9 flex-shrink-0"></div>'}
                </div>
                <div id="vp-suggestions-${optIndex}" class="ml-44 flex flex-wrap gap-1.5"></div>
            `;
            container.appendChild(row);
            this.renderValueSuggestions(optIndex);
        });
    },

    renderValueSuggestions(optIndex) {
        const opt = this.options[optIndex];
        const suggestionsContainer = document.getElementById(`vp-suggestions-${optIndex}`);
        if (!suggestionsContainer) return;

        const attr = this.availableAttributes.find(a => a.name.toLowerCase() === opt.name.toLowerCase());
        if (!attr || !attr.values) {
            suggestionsContainer.innerHTML = '';
            return;
        }

        // Filter out already selected values
        const remainingValues = attr.values.filter(v => !opt.values.includes(v));

        if (remainingValues.length === 0) {
            suggestionsContainer.innerHTML = '';
            return;
        }

        suggestionsContainer.innerHTML = remainingValues.map(v =>
            `<button type="button" 
                onclick="VariantsPanel.addTagFromInput(${optIndex}, '${this.esc(v)}')"
                class="px-2 py-0.5 rounded border border-gray-200 dark:border-surface-tonal-a30 text-[10px] text-gray-500 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 hover:border-indigo-200 dark:hover:border-indigo-800 transition-all font-medium">
                + ${this.esc(v)}
            </button>`
        ).join('');
    },

    renderVariantsTable() {
        const wrapper  = document.getElementById('vp-table-wrapper');
        const tbody    = document.getElementById('vp-variants-tbody');
        if (!wrapper || !tbody) return;

        // Valid options: name + at least one value
        const validOpts = this.options.map((o, i) => ({ ...o, origIndex: i }))
            .filter(o => o.name.trim() && o.values.length > 0);

        if (validOpts.length === 0) {
            wrapper.classList.add('hidden');
            tbody.innerHTML = '';
            this._clearOptionHiddens();
            return;
        }
        wrapper.classList.remove('hidden');

        const combos = this.cartesian(validOpts.map(o => o.values)); // [["red","L"], ...]
        tbody.innerHTML = '';

        combos.forEach((combo, rowIndex) => {
            const key     = combo.join('|');
            const state   = this.rowState[key] || {};
            const enabled = state.hasOwnProperty('enabled') ? state.enabled : true;

            // Label HTML
            const labelHtml = combo.map((val, ci) => {
                const origIdx = validOpts[ci].origIndex;
                return `<span class="${this.LABEL_COLORS[origIdx % this.LABEL_COLORS.length]}">${this.esc(val)}</span>`;
            }).join('<span class="text-gray-400 mx-0.5">•</span>');

            // Hidden opt inputs for backend
            const optHiddens = combo.map((val, ci) =>
                `<input type="hidden" name="variants[${rowIndex}][opts][${validOpts[ci].origIndex}]" value="${this.esc(val)}">`
            ).join('');

            const comboKey = combo.map(v => v.toUpperCase().substring(0, 3)).join('-');
            const defaultSku = this.baseSku ? `${this.baseSku}-${comboKey}` : '';
            const price    = parseFloat(state.price ?? this.basePrice).toFixed(2);
            const sku      = this.esc(state.sku || defaultSku);
            const barcode  = this.esc(state.barcode || '');
            const qty      = parseInt(state.stock_quantity ?? 0);
            const imageUrl = state.image_url || '';
            const id       = state.id || '';

            const imgInner = imageUrl
                ? `<img src="${imageUrl}" class="w-full h-full object-cover rounded-lg">`
                : `<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>`;

            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50 dark:hover:bg-surface-tonal-a30/50 transition-colors';
            tr.dataset.key = key;
            tr.innerHTML = `
                <td class="px-3 py-2.5">
                    <div class="flex items-center gap-2.5">
                        <input type="checkbox" name="variants[${rowIndex}][enabled]" value="1"
                            ${enabled !== false ? 'checked' : ''}
                            class="h-3.5 w-3.5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        ${id ? `<input type="hidden" name="variants[${rowIndex}][id]" value="${id}">` : ''}
                        ${optHiddens}
                        <span class="text-xs leading-tight">${labelHtml}</span>
                    </div>
                </td>
                <td class="px-3 py-2.5">
                    <div class="relative">
                        <input type="number" step="0.01" name="variants[${rowIndex}][price]"
                            value="${price}" min="0"
                            class="w-28 rounded-md border border-gray-100 dark:border-white/5 bg-white dark:bg-surface-tonal-a30 px-2 py-1.5 text-xs font-bold text-black dark:text-white outline-none focus:border-indigo-300 focus:ring-1 focus:ring-indigo-500/20 transition-all pr-6">
                        <span class="absolute inset-y-0 right-2 flex items-center text-gray-400 text-[10px] pointer-events-none font-black">$</span>
                    </div>
                </td>
                <td class="px-3 py-2.5">
                    <input type="text" name="variants[${rowIndex}][sku]" value="${sku}"
                        placeholder="SKU-001"
                        class="w-28 rounded-md border border-gray-100 dark:border-white/5 bg-white dark:bg-surface-tonal-a30 px-2 py-1.5 text-xs font-bold text-black dark:text-white outline-none focus:border-indigo-300 focus:ring-1 focus:ring-indigo-500/20 transition-all">
                </td>
                <td class="px-3 py-2.5">
                    <input type="number" name="variants[${rowIndex}][stock_quantity]"
                        value="${qty}" min="0"
                        class="w-20 rounded-md border border-gray-100 dark:border-white/5 bg-white dark:bg-surface-tonal-a30 px-2 py-1.5 text-xs font-bold text-black dark:text-white outline-none focus:border-indigo-300 focus:ring-1 focus:ring-indigo-500/20 transition-all">
                </td>
                <td class="px-3 py-2.5">
                    <div class="relative group/img">
                        <input type="file" name="variant_images[${rowIndex}]" accept="image/*"
                            class="hidden" onchange="VariantsPanel.handleImageSelect(this, ${rowIndex}, '${this.esc(key)}')">
                        <div id="vp-img-${rowIndex}"
                            onclick="this.previousElementSibling.click()"
                            title="Click to upload variant image"
                            class="w-10 h-10 rounded-lg border-2 ${imageUrl ? 'border-solid border-gray-200 dark:border-surface-tonal-a30' : 'border-dashed border-gray-200 dark:border-surface-tonal-a30 hover:border-indigo-400'} overflow-hidden cursor-pointer transition-all flex items-center justify-center bg-gray-50 dark:bg-surface-tonal-a30/20">
                            ${imgInner}
                        </div>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);

            // Restore file object if it exists in selectedFiles
            if (this.selectedFiles[key]) {
                const fileInput = tr.querySelector(`input[name="variant_images[${rowIndex}]"]`);
                if (fileInput) {
                    const dt = new DataTransfer();
                    dt.items.add(this.selectedFiles[key]);
                    fileInput.files = dt.files;
                }
            }
        });

        this._renderOptionHiddens(validOpts);
    },

    // ── State Persistence ────────────────────────────────────────────────

    saveCurrentTableState() {
        const tbody = document.getElementById('vp-variants-tbody');
        if (!tbody) return;

        tbody.querySelectorAll('tr[data-key]').forEach(tr => {
            const key  = tr.dataset.key;
            if (!key) return;
            const prev = this.rowState[key] || {};

            const get = (sel) => tr.querySelector(sel);
            const gv  = (sel) => { const el = get(sel); return el ? el.value : null; };

            this.rowState[key] = {
                id:             gv('input[name*="[id]"]') || prev.id || '',
                price:          gv('input[name*="[price]"]') ?? prev.price,
                sku:            gv('input[name*="[sku]"]') ?? prev.sku,
                stock_quantity: gv('input[name*="[stock_quantity]"]') ?? prev.stock_quantity,
                image_url:      prev.image_url || '',
                enabled:        get('input[type="checkbox"]')?.checked !== false,
            };
        });
    },

    // ── Helpers ──────────────────────────────────────────────────────────

    cartesian(arrays) {
        if (!arrays.length) return [[]];
        return arrays.reduce((acc, arr) => acc.flatMap(x => arr.map(y => [...x, y])), [[]]);
    },

    toggleAll(cb) {
        document.querySelectorAll('#vp-variants-tbody input[type="checkbox"]')
            .forEach(c => { c.checked = cb.checked; });
    },

    handleImageSelect(input, rowIndex, key) {
        if (!input.files?.[0]) return;
        const file = input.files[0];
        this.selectedFiles[key] = file;

        const reader = new FileReader();
        reader.onload = (e) => {
            const thumb = document.getElementById(`vp-img-${rowIndex}`);
            if (thumb) {
                thumb.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded-lg">`;
                thumb.classList.remove('border-dashed');
                thumb.classList.add('border-solid');
            }
            // Update state so re-renders don't lose the preview
            if (this.rowState[key]) this.rowState[key].image_url = e.target.result;
            else this.rowState[key] = { image_url: e.target.result };
        };
        reader.readAsDataURL(file);
    },

    _renderOptionHiddens(validOpts) {
        let container = document.getElementById('vp-option-hiddens');
        if (!container) {
            container = document.createElement('div');
            container.id = 'vp-option-hiddens';
            container.style.display = 'none';
            const form = document.getElementById('vp-options-list')?.closest('form');
            if (form) form.appendChild(container);
        }
        container.innerHTML = '';
        (validOpts || this.options.filter(o => o.name.trim())).forEach((opt, i) => {
            const idx = opt.hasOwnProperty('origIndex') ? opt.origIndex : i;
            container.innerHTML +=
                `<input type="hidden" name="options[${idx}][name]" value="${this.esc(opt.name)}">`;
            (opt.values || []).forEach(v => {
                container.innerHTML +=
                    `<input type="hidden" name="options[${idx}][values][]" value="${this.esc(v)}">`;
            });
        });
    },

    _clearOptionHiddens() {
        const c = document.getElementById('vp-option-hiddens');
        if (c) c.innerHTML = '';
    },

    esc(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    },
};

// Boot on DOMContentLoaded
document.addEventListener('DOMContentLoaded', function () {
    const existingOptions  = {!! $existingOptionsJson ?? '[]' !!};
    const existingVariants = {!! $existingVariantsJson ?? '[]' !!};
    const basePrice        = {{ $basePrice ?? 0 }};
    const allAttributes    = {!! $allAttributesJson ?? '[]' !!};
    VariantsPanel.init(existingOptions, existingVariants, basePrice, allAttributes);
});
</script>
@endpush
