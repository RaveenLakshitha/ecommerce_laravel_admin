<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DRAPE — Store Manager</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<script>
tailwind.config = {
  theme: {
    extend: {
      fontFamily: {
        sans: ['DM Sans', 'sans-serif'],
        mono: ['DM Mono', 'monospace'],
      },
      colors: {
        surface: {
          0: '#0a0a0b',
          1: '#111113',
          2: '#18181b',
          3: '#1f1f23',
          4: '#27272c',
          5: '#2e2e35',
        },
        border: {
          DEFAULT: '#2a2a32',
          subtle: '#1e1e24',
          strong: '#3a3a45',
        },
        accent: {
          DEFAULT: '#e8c547',
          dim: '#c9a82e',
          muted: 'rgba(232,197,71,0.12)',
          ring: 'rgba(232,197,71,0.3)',
        },
        ink: {
          DEFAULT: '#f0f0f2',
          muted: '#8b8b9a',
          faint: '#4a4a58',
        },
        status: {
          pending: '#f59e0b',
          processing: '#3b82f6',
          packed: '#8b5cf6',
          shipped: '#06b6d4',
          delivered: '#10b981',
          cancelled: '#ef4444',
          returned: '#f97316',
        }
      }
    }
  }
}
</script>
<style>
  * { box-sizing: border-box; }
  body { font-family: 'DM Sans', sans-serif; background: #0a0a0b; color: #f0f0f2; margin: 0; }

  ::-webkit-scrollbar { width: 4px; height: 4px; }
  ::-webkit-scrollbar-track { background: #111113; }
  ::-webkit-scrollbar-thumb { background: #2e2e35; border-radius: 2px; }
  ::-webkit-scrollbar-thumb:hover { background: #3a3a45; }

  .tab-btn { transition: all 0.15s ease; }
  .tab-btn.active { background: #1f1f23; color: #f0f0f2; }
  .tab-btn:not(.active) { color: #8b8b9a; }
  .tab-btn:not(.active):hover { color: #f0f0f2; background: #18181b; }

  .nav-item { transition: all 0.15s ease; border-left: 2px solid transparent; }
  .nav-item.active { border-left-color: #e8c547; background: rgba(232,197,71,0.06); color: #f0f0f2; }
  .nav-item:not(.active) { color: #8b8b9a; }
  .nav-item:not(.active):hover { color: #f0f0f2; background: #18181b; border-left-color: #2e2e35; }

  .pane { display: none; }
  .pane.active { display: block; }

  .badge { font-family: 'DM Mono', monospace; font-size: 10px; font-weight: 500; letter-spacing: 0.04em; padding: 2px 7px; border-radius: 4px; }

  .order-row { transition: background 0.12s ease; cursor: pointer; }
  .order-row:hover { background: #18181b; }
  .order-row.selected { background: rgba(232,197,71,0.05); border-left: 2px solid #e8c547; }

  .stepper-step { position: relative; }
  .stepper-step::after {
    content: '';
    position: absolute;
    top: 14px;
    left: calc(50% + 14px);
    width: calc(100% - 28px);
    height: 1px;
    background: #2a2a32;
  }
  .stepper-step:last-child::after { display: none; }
  .stepper-step.done .step-dot { background: #e8c547; border-color: #e8c547; }
  .stepper-step.done .step-dot svg { color: #0a0a0b; }
  .stepper-step.active .step-dot { border-color: #e8c547; background: rgba(232,197,71,0.12); }
  .stepper-step.active .step-dot svg { color: #e8c547; }
  .stepper-step.done::after { background: #e8c547; }

  .card { background: #111113; border: 1px solid #2a2a32; border-radius: 10px; }
  .input-field {
    background: #18181b;
    border: 1px solid #2a2a32;
    border-radius: 7px;
    color: #f0f0f2;
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    padding: 7px 11px;
    width: 100%;
    outline: none;
    transition: border-color 0.15s;
  }
  .input-field:focus { border-color: #e8c547; box-shadow: 0 0 0 3px rgba(232,197,71,0.1); }
  .input-field::placeholder { color: #4a4a58; }

  .btn-primary { background: #e8c547; color: #0a0a0b; font-weight: 600; font-size: 13px; padding: 8px 16px; border-radius: 7px; border: none; cursor: pointer; transition: all 0.15s; }
  .btn-primary:hover { background: #c9a82e; }
  .btn-ghost { background: transparent; color: #8b8b9a; font-size: 13px; padding: 7px 12px; border-radius: 7px; border: 1px solid #2a2a32; cursor: pointer; transition: all 0.15s; }
  .btn-ghost:hover { color: #f0f0f2; border-color: #3a3a45; background: #18181b; }

  .stat-card { background: #111113; border: 1px solid #2a2a32; border-radius: 10px; padding: 16px; }
  .progress-bar { height: 3px; background: #2a2a32; border-radius: 2px; overflow: hidden; }
  .progress-fill { height: 100%; border-radius: 2px; background: #e8c547; transition: width 0.4s ease; }

  .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); z-index: 100; display: flex; align-items: center; justify-content: center; }
  .modal { background: #111113; border: 1px solid #2a2a32; border-radius: 14px; width: 520px; max-width: 95vw; max-height: 85vh; overflow-y: auto; }
  .tag { background: #1f1f23; border: 1px solid #2a2a32; border-radius: 4px; font-size: 11px; color: #8b8b9a; padding: 2px 8px; }

  .section-label { font-size: 10px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: #4a4a58; margin-bottom: 8px; }

  .tooltip { position: relative; }
  .tooltip:hover::after {
    content: attr(data-tip);
    position: absolute; bottom: calc(100% + 6px); left: 50%; transform: translateX(-50%);
    background: #2e2e35; color: #f0f0f2; font-size: 11px; padding: 4px 8px;
    border-radius: 5px; white-space: nowrap; z-index: 50; pointer-events: none;
  }

  select.input-field { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238b8b9a' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; padding-right: 30px; }

  .chip { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 500; }

  @keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
  .pane.active { animation: fadeIn 0.2s ease; }

  .color-swatch { width: 20px; height: 20px; border-radius: 50%; border: 2px solid #2a2a32; display: inline-block; cursor: pointer; transition: transform 0.1s; }
  .color-swatch:hover { transform: scale(1.2); }
  .color-swatch.selected { border-color: #e8c547; }

  .size-chip { padding: 3px 10px; border-radius: 5px; font-size: 12px; font-family: 'DM Mono', monospace; border: 1px solid #2a2a32; background: #18181b; color: #8b8b9a; cursor: pointer; transition: all 0.1s; }
  .size-chip:hover, .size-chip.selected { border-color: #e8c547; background: rgba(232,197,71,0.08); color: #e8c547; }

  .img-thumb { width: 56px; height: 56px; border-radius: 7px; background: #18181b; border: 1px solid #2a2a32; object-fit: cover; }

  .courier-card { border: 1px solid #2a2a32; border-radius: 8px; padding: 10px 14px; cursor: pointer; transition: all 0.15s; }
  .courier-card:hover { border-color: #3a3a45; background: #18181b; }
  .courier-card.selected { border-color: #e8c547; background: rgba(232,197,71,0.05); }

  .timeline-item::before { content: ''; position: absolute; left: -21px; top: 5px; width: 8px; height: 8px; border-radius: 50%; background: #3a3a45; border: 2px solid #2a2a32; }
  .timeline-item.done::before { background: #e8c547; border-color: #e8c547; }
  .timeline-line { position: absolute; left: -17px; top: 14px; bottom: -8px; width: 1px; background: #2a2a32; }
  .timeline-item.done .timeline-line { background: #e8c547; }
</style>
</head>
<body class="min-h-screen flex flex-col">

<!-- Top Bar -->
<header class="flex items-center justify-between px-5 h-12 border-b border-border bg-surface-1 shrink-0 sticky top-0 z-40">
  <div class="flex items-center gap-3">
    <div class="flex items-center gap-1.5">
      <div class="w-6 h-6 bg-accent rounded-md flex items-center justify-center">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#0a0a0b" stroke-width="2.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
      </div>
      <span class="font-semibold text-sm tracking-tight text-ink">DRAPE</span>
    </div>
    <span class="text-border-strong text-xs">|</span>
    <span class="text-ink-muted text-xs">Manager Console</span>
  </div>
  <div class="flex items-center gap-3">
    <div class="flex items-center gap-1.5 bg-surface-3 rounded-md px-2.5 py-1.5">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#8b8b9a" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input class="bg-transparent text-xs text-ink outline-none w-36 placeholder:text-ink-faint" placeholder="Search orders, products..." />
    </div>
    <button class="relative tooltip" data-tip="Notifications">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#8b8b9a" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      <span class="absolute -top-0.5 -right-0.5 w-3.5 h-3.5 bg-accent rounded-full text-[8px] font-bold text-surface-0 flex items-center justify-center">5</span>
    </button>
    <div class="flex items-center gap-2">
      <div class="w-7 h-7 rounded-full bg-surface-4 flex items-center justify-center text-xs font-semibold text-ink">AM</div>
      <div class="hidden sm:block">
        <div class="text-xs font-medium text-ink">Ashan Mendis</div>
        <div class="text-[10px] text-ink-muted">Store Manager</div>
      </div>
    </div>
  </div>
</header>

<div class="flex flex-1 overflow-hidden" style="height: calc(100vh - 48px);">

<!-- Sidebar -->
<aside class="w-48 shrink-0 bg-surface-1 border-r border-border flex flex-col overflow-y-auto">
  <div class="p-3 pt-4">
    <div class="section-label px-2">Main</div>
    <nav class="flex flex-col gap-0.5">
      <button onclick="showPane('dashboard')" class="nav-item active flex items-center gap-2.5 px-2.5 py-2 rounded-r-md text-left text-[13px] font-medium" id="nav-dashboard">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        Dashboard
      </button>
      <button onclick="showPane('orders')" class="nav-item flex items-center gap-2.5 px-2.5 py-2 rounded-r-md text-left text-[13px] font-medium" id="nav-orders">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        Orders
        <span class="ml-auto badge bg-accent text-surface-0">12</span>
      </button>
      <button onclick="showPane('products')" class="nav-item flex items-center gap-2.5 px-2.5 py-2 rounded-r-md text-left text-[13px] font-medium" id="nav-products">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
        Products
      </button>
      <button onclick="showPane('inventory')" class="nav-item flex items-center gap-2.5 px-2.5 py-2 rounded-r-md text-left text-[13px] font-medium" id="nav-inventory">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
        Inventory
        <span class="ml-auto badge bg-red-500/20 text-red-400">3</span>
      </button>
      <button onclick="showPane('shipping')" class="nav-item flex items-center gap-2.5 px-2.5 py-2 rounded-r-md text-left text-[13px] font-medium" id="nav-shipping">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
        Shipping
      </button>
    </nav>

    <div class="section-label px-2 mt-4">Finance</div>
    <nav class="flex flex-col gap-0.5">
      <button onclick="showPane('payments')" class="nav-item flex items-center gap-2.5 px-2.5 py-2 rounded-r-md text-left text-[13px] font-medium" id="nav-payments">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        Payments
      </button>
      <button onclick="showPane('discounts')" class="nav-item flex items-center gap-2.5 px-2.5 py-2 rounded-r-md text-left text-[13px] font-medium" id="nav-discounts">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 14 15 8"/><circle cx="9.5" cy="8.5" r="0.5" fill="currentColor"/><circle cx="14.5" cy="13.5" r="0.5" fill="currentColor"/><path d="M21 7.5V5a2 2 0 0 0-2-2h-2.5M21 16.5V19a2 2 0 0 1-2 2h-2.5M3 7.5V5a2 2 0 0 1 2-2h2.5M3 16.5V19a2 2 0 0 0 2 2h2.5"/></svg>
        Discounts
      </button>
    </nav>

    <div class="section-label px-2 mt-4">Customers</div>
    <nav class="flex flex-col gap-0.5">
      <button onclick="showPane('customers')" class="nav-item flex items-center gap-2.5 px-2.5 py-2 rounded-r-md text-left text-[13px] font-medium" id="nav-customers">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Customers
      </button>
    </nav>
  </div>

  <div class="mt-auto p-3 border-t border-border">
    <div class="bg-surface-3 rounded-lg p-3">
      <div class="text-[11px] text-ink-muted mb-1">Today's Revenue</div>
      <div class="text-base font-semibold text-ink">LKR 284,500</div>
      <div class="progress-bar mt-2"><div class="progress-fill" style="width:68%"></div></div>
      <div class="text-[10px] text-ink-muted mt-1">68% of daily target</div>
    </div>
  </div>
</aside>

<!-- Main Content -->
<main class="flex-1 overflow-y-auto bg-surface-0">

  <!-- ==================== DASHBOARD ==================== -->
  <div id="pane-dashboard" class="pane active p-5">
    <div class="flex items-center justify-between mb-5">
      <div>
        <h1 class="text-base font-semibold text-ink">Good morning, Ashan 👋</h1>
        <p class="text-xs text-ink-muted mt-0.5">Thursday, 19 March 2026 — Here's what's happening today.</p>
      </div>
      <button class="btn-primary flex items-center gap-1.5" onclick="showPane('orders')">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Order
      </button>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">
      <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs text-ink-muted">Total Orders</span>
          <div class="w-7 h-7 rounded-lg bg-blue-500/10 flex items-center justify-center">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
          </div>
        </div>
        <div class="text-2xl font-semibold text-ink">148</div>
        <div class="flex items-center gap-1 mt-1">
          <span class="text-[10px] text-green-400">↑ 12%</span>
          <span class="text-[10px] text-ink-muted">vs yesterday</span>
        </div>
      </div>
      <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs text-ink-muted">Revenue</span>
          <div class="w-7 h-7 rounded-lg bg-accent-muted flex items-center justify-center">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#e8c547" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          </div>
        </div>
        <div class="text-2xl font-semibold text-ink">284.5K</div>
        <div class="flex items-center gap-1 mt-1">
          <span class="text-[10px] text-green-400">↑ 8.3%</span>
          <span class="text-[10px] text-ink-muted">LKR today</span>
        </div>
      </div>
      <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs text-ink-muted">Pending</span>
          <div class="w-7 h-7 rounded-lg bg-amber-500/10 flex items-center justify-center">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          </div>
        </div>
        <div class="text-2xl font-semibold text-ink">12</div>
        <div class="flex items-center gap-1 mt-1">
          <span class="text-[10px] text-amber-400">Action required</span>
        </div>
      </div>
      <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs text-ink-muted">Delivered</span>
          <div class="w-7 h-7 rounded-lg bg-emerald-500/10 flex items-center justify-center">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
        </div>
        <div class="text-2xl font-semibold text-ink">89</div>
        <div class="flex items-center gap-1 mt-1">
          <span class="text-[10px] text-green-400">Today</span>
        </div>
      </div>
    </div>

    <!-- Order Pipeline Funnel -->
    <div class="card p-4 mb-5">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-ink">Order Pipeline</h2>
        <span class="text-xs text-ink-muted">Last 7 days</span>
      </div>
      <div class="flex items-stretch gap-2">
        <div class="flex-1 text-center">
          <div class="bg-amber-500/10 rounded-lg p-3 mb-2">
            <div class="text-xl font-semibold text-amber-400">34</div>
            <div class="text-[10px] text-ink-muted mt-0.5">Pending</div>
          </div>
          <div class="progress-bar"><div class="progress-fill" style="width:100%;background:#f59e0b;"></div></div>
        </div>
        <div class="flex items-center text-ink-faint text-xs">→</div>
        <div class="flex-1 text-center">
          <div class="bg-blue-500/10 rounded-lg p-3 mb-2">
            <div class="text-xl font-semibold text-blue-400">28</div>
            <div class="text-[10px] text-ink-muted mt-0.5">Processing</div>
          </div>
          <div class="progress-bar"><div class="progress-fill" style="width:82%;background:#3b82f6;"></div></div>
        </div>
        <div class="flex items-center text-ink-faint text-xs">→</div>
        <div class="flex-1 text-center">
          <div class="bg-purple-500/10 rounded-lg p-3 mb-2">
            <div class="text-xl font-semibold text-purple-400">22</div>
            <div class="text-[10px] text-ink-muted mt-0.5">Packed</div>
          </div>
          <div class="progress-bar"><div class="progress-fill" style="width:65%;background:#8b5cf6;"></div></div>
        </div>
        <div class="flex items-center text-ink-faint text-xs">→</div>
        <div class="flex-1 text-center">
          <div class="bg-cyan-500/10 rounded-lg p-3 mb-2">
            <div class="text-xl font-semibold text-cyan-400">19</div>
            <div class="text-[10px] text-ink-muted mt-0.5">Shipped</div>
          </div>
          <div class="progress-bar"><div class="progress-fill" style="width:56%;background:#06b6d4;"></div></div>
        </div>
        <div class="flex items-center text-ink-faint text-xs">→</div>
        <div class="flex-1 text-center">
          <div class="bg-emerald-500/10 rounded-lg p-3 mb-2">
            <div class="text-xl font-semibold text-emerald-400">89</div>
            <div class="text-[10px] text-ink-muted mt-0.5">Delivered</div>
          </div>
          <div class="progress-bar"><div class="progress-fill" style="width:100%;background:#10b981;"></div></div>
        </div>
      </div>
    </div>

    <!-- Recent orders + Low Stock -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <div class="card">
        <div class="flex items-center justify-between p-4 border-b border-border">
          <h2 class="text-sm font-semibold text-ink">Recent Orders</h2>
          <button class="text-xs text-accent hover:text-accent-dim" onclick="showPane('orders')">View all →</button>
        </div>
        <div class="divide-y divide-border-subtle">
          <div class="flex items-center gap-3 px-4 py-2.5 hover:bg-surface-2 cursor-pointer" onclick="openOrderDetail('ORD-001')">
            <div class="w-7 h-7 rounded-full bg-surface-4 flex items-center justify-center text-[10px] font-semibold">NK</div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2">
                <span class="text-xs font-medium text-ink truncate">Nimal Kumara</span>
                <span class="badge bg-amber-500/15 text-amber-400">Pending</span>
              </div>
              <div class="text-[10px] text-ink-muted font-mono">#ORD-2026-001 · LKR 12,500</div>
            </div>
            <div class="text-[10px] text-ink-muted">2m ago</div>
          </div>
          <div class="flex items-center gap-3 px-4 py-2.5 hover:bg-surface-2 cursor-pointer">
            <div class="w-7 h-7 rounded-full bg-surface-4 flex items-center justify-center text-[10px] font-semibold">SP</div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2">
                <span class="text-xs font-medium text-ink truncate">Sitha Perera</span>
                <span class="badge bg-blue-500/15 text-blue-400">Processing</span>
              </div>
              <div class="text-[10px] text-ink-muted font-mono">#ORD-2026-002 · LKR 8,200</div>
            </div>
            <div class="text-[10px] text-ink-muted">15m ago</div>
          </div>
          <div class="flex items-center gap-3 px-4 py-2.5 hover:bg-surface-2 cursor-pointer">
            <div class="w-7 h-7 rounded-full bg-surface-4 flex items-center justify-center text-[10px] font-semibold">RA</div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2">
                <span class="text-xs font-medium text-ink truncate">Ruwan Abesinghe</span>
                <span class="badge bg-emerald-500/15 text-emerald-400">Delivered</span>
              </div>
              <div class="text-[10px] text-ink-muted font-mono">#ORD-2026-003 · LKR 24,000</div>
            </div>
            <div class="text-[10px] text-ink-muted">1h ago</div>
          </div>
          <div class="flex items-center gap-3 px-4 py-2.5 hover:bg-surface-2 cursor-pointer">
            <div class="w-7 h-7 rounded-full bg-surface-4 flex items-center justify-center text-[10px] font-semibold">MS</div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2">
                <span class="text-xs font-medium text-ink truncate">Malini Silva</span>
                <span class="badge bg-cyan-500/15 text-cyan-400">Shipped</span>
              </div>
              <div class="text-[10px] text-ink-muted font-mono">#ORD-2026-004 · LKR 6,750</div>
            </div>
            <div class="text-[10px] text-ink-muted">2h ago</div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="flex items-center justify-between p-4 border-b border-border">
          <h2 class="text-sm font-semibold text-ink">Low Stock Alerts</h2>
          <span class="badge bg-red-500/15 text-red-400">3 Critical</span>
        </div>
        <div class="divide-y divide-border-subtle">
          <div class="flex items-center gap-3 px-4 py-2.5">
            <div class="w-10 h-10 rounded-lg bg-surface-3 flex items-center justify-center overflow-hidden">
              <div class="text-lg">👕</div>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs font-medium text-ink truncate">Classic Oxford Shirt — White M</div>
              <div class="text-[10px] text-ink-muted">SKU: OXF-WHT-M</div>
            </div>
            <div class="text-right">
              <div class="text-xs font-semibold text-red-400">2 left</div>
              <div class="text-[10px] text-ink-muted">Min: 10</div>
            </div>
          </div>
          <div class="flex items-center gap-3 px-4 py-2.5">
            <div class="w-10 h-10 rounded-lg bg-surface-3 flex items-center justify-center overflow-hidden">
              <div class="text-lg">👔</div>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs font-medium text-ink truncate">Slim Fit Chino — Navy 32</div>
              <div class="text-[10px] text-ink-muted">SKU: CHN-NVY-32</div>
            </div>
            <div class="text-right">
              <div class="text-xs font-semibold text-orange-400">4 left</div>
              <div class="text-[10px] text-ink-muted">Min: 10</div>
            </div>
          </div>
          <div class="flex items-center gap-3 px-4 py-2.5">
            <div class="w-10 h-10 rounded-lg bg-surface-3 flex items-center justify-center overflow-hidden">
              <div class="text-lg">🧥</div>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs font-medium text-ink truncate">Linen Blazer — Camel L</div>
              <div class="text-[10px] text-ink-muted">SKU: BLZ-CAM-L</div>
            </div>
            <div class="text-right">
              <div class="text-xs font-semibold text-orange-400">3 left</div>
              <div class="text-[10px] text-ink-muted">Min: 8</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ==================== ORDERS ==================== -->
  <div id="pane-orders" class="pane">
    <div class="flex h-full" style="height: calc(100vh - 48px);">
      <!-- Order List -->
      <div id="order-list-col" class="border-r border-border flex flex-col" style="width:380px; flex-shrink:0;">
        <!-- Filters -->
        <div class="p-3 border-b border-border space-y-2">
          <div class="flex items-center gap-2">
            <div class="flex-1 flex items-center gap-1.5 bg-surface-3 rounded-md px-2.5 py-1.5">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#8b8b9a" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
              <input class="bg-transparent text-xs text-ink outline-none flex-1 placeholder:text-ink-faint" placeholder="Search orders…" />
            </div>
            <button class="btn-primary text-xs px-3 py-1.5" onclick="openAddOrderModal()">+ New</button>
          </div>
          <div class="flex gap-1.5 flex-wrap">
            <button class="tab-btn active text-[11px] px-2.5 py-1 rounded-md">All</button>
            <button class="tab-btn text-[11px] px-2.5 py-1 rounded-md">Pending</button>
            <button class="tab-btn text-[11px] px-2.5 py-1 rounded-md">Processing</button>
            <button class="tab-btn text-[11px] px-2.5 py-1 rounded-md">Shipped</button>
            <button class="tab-btn text-[11px] px-2.5 py-1 rounded-md">Delivered</button>
          </div>
        </div>

        <!-- Order Rows -->
        <div class="flex-1 overflow-y-auto divide-y divide-border-subtle">
          <!-- Order Item -->
          <div class="order-row selected px-3 py-3 flex gap-3" onclick="selectOrder(this, 'ORD-001')">
            <div class="w-8 h-8 rounded-full bg-surface-4 flex items-center justify-center text-[11px] font-semibold shrink-0">NK</div>
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-1">
                <span class="text-xs font-medium text-ink truncate">Nimal Kumara</span>
                <span class="badge bg-amber-500/15 text-amber-400 shrink-0">Pending</span>
              </div>
              <div class="text-[10px] text-ink-muted font-mono mt-0.5">#ORD-2026-001</div>
              <div class="flex items-center justify-between mt-1">
                <span class="text-[10px] text-ink-muted">Classic Shirt, Chino Pants</span>
                <span class="text-xs font-semibold text-ink">LKR 12,500</span>
              </div>
            </div>
          </div>

          <div class="order-row px-3 py-3 flex gap-3" onclick="selectOrder(this, 'ORD-002')">
            <div class="w-8 h-8 rounded-full bg-surface-4 flex items-center justify-center text-[11px] font-semibold shrink-0">SP</div>
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-1">
                <span class="text-xs font-medium text-ink truncate">Sitha Perera</span>
                <span class="badge bg-blue-500/15 text-blue-400 shrink-0">Processing</span>
              </div>
              <div class="text-[10px] text-ink-muted font-mono mt-0.5">#ORD-2026-002</div>
              <div class="flex items-center justify-between mt-1">
                <span class="text-[10px] text-ink-muted">Linen Blazer × 1</span>
                <span class="text-xs font-semibold text-ink">LKR 8,200</span>
              </div>
            </div>
          </div>

          <div class="order-row px-3 py-3 flex gap-3" onclick="selectOrder(this, 'ORD-003')">
            <div class="w-8 h-8 rounded-full bg-surface-4 flex items-center justify-center text-[11px] font-semibold shrink-0">RA</div>
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-1">
                <span class="text-xs font-medium text-ink truncate">Ruwan Abesinghe</span>
                <span class="badge bg-emerald-500/15 text-emerald-400 shrink-0">Delivered</span>
              </div>
              <div class="text-[10px] text-ink-muted font-mono mt-0.5">#ORD-2026-003</div>
              <div class="flex items-center justify-between mt-1">
                <span class="text-[10px] text-ink-muted">Office Suit Set × 2</span>
                <span class="text-xs font-semibold text-ink">LKR 24,000</span>
              </div>
            </div>
          </div>

          <div class="order-row px-3 py-3 flex gap-3" onclick="selectOrder(this, 'ORD-004')">
            <div class="w-8 h-8 rounded-full bg-surface-4 flex items-center justify-center text-[11px] font-semibold shrink-0">MS</div>
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-1">
                <span class="text-xs font-medium text-ink truncate">Malini Silva</span>
                <span class="badge bg-cyan-500/15 text-cyan-400 shrink-0">Shipped</span>
              </div>
              <div class="text-[10px] text-ink-muted font-mono mt-0.5">#ORD-2026-004</div>
              <div class="flex items-center justify-between mt-1">
                <span class="text-[10px] text-ink-muted">Polo T-Shirt × 3</span>
                <span class="text-xs font-semibold text-ink">LKR 6,750</span>
              </div>
            </div>
          </div>

          <div class="order-row px-3 py-3 flex gap-3" onclick="selectOrder(this, 'ORD-005')">
            <div class="w-8 h-8 rounded-full bg-surface-4 flex items-center justify-center text-[11px] font-semibold shrink-0">KD</div>
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-1">
                <span class="text-xs font-medium text-ink truncate">Kasun Dias</span>
                <span class="badge bg-purple-500/15 text-purple-400 shrink-0">Packed</span>
              </div>
              <div class="text-[10px] text-ink-muted font-mono mt-0.5">#ORD-2026-005</div>
              <div class="flex items-center justify-between mt-1">
                <span class="text-[10px] text-ink-muted">Casual Hoodie × 2</span>
                <span class="text-xs font-semibold text-ink">LKR 9,400</span>
              </div>
            </div>
          </div>

          <div class="order-row px-3 py-3 flex gap-3" onclick="selectOrder(this, 'ORD-006')">
            <div class="w-8 h-8 rounded-full bg-surface-4 flex items-center justify-center text-[11px] font-semibold shrink-0">PF</div>
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-1">
                <span class="text-xs font-medium text-ink truncate">Priya Fernando</span>
                <span class="badge bg-red-500/15 text-red-400 shrink-0">Cancelled</span>
              </div>
              <div class="text-[10px] text-ink-muted font-mono mt-0.5">#ORD-2026-006</div>
              <div class="flex items-center justify-between mt-1">
                <span class="text-[10px] text-ink-muted">Summer Dress × 1</span>
                <span class="text-xs font-semibold text-ink">LKR 4,200</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Order Detail Panel -->
      <div id="order-detail-col" class="flex-1 overflow-y-auto p-5 bg-surface-0">
        <!-- Status Pipeline -->
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="text-sm font-semibold text-ink">#ORD-2026-001</h2>
            <p class="text-[10px] text-ink-muted mt-0.5">Placed 19 Mar 2026, 08:32 AM · COD · Nimal Kumara</p>
          </div>
          <div class="flex items-center gap-2">
            <button class="btn-ghost text-xs flex items-center gap-1" onclick="printInvoice()">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
              Print
            </button>
            <button class="btn-ghost text-xs flex items-center gap-1" onclick="openRefundModal()">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4"/></svg>
              Refund
            </button>
          </div>
        </div>

        <!-- Stepper -->
        <div class="card p-4 mb-4">
          <div class="flex items-start justify-between" id="order-stepper">
            <div class="stepper-step done flex-1 flex flex-col items-center text-center">
              <div class="step-dot w-7 h-7 rounded-full border-2 border-surface-5 flex items-center justify-center mb-1.5">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              </div>
              <div class="text-[10px] font-semibold text-ink">Placed</div>
              <div class="text-[9px] text-ink-muted">08:32 AM</div>
            </div>
            <div class="stepper-step active flex-1 flex flex-col items-center text-center">
              <div class="step-dot w-7 h-7 rounded-full border-2 border-surface-5 flex items-center justify-center mb-1.5">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
              </div>
              <div class="text-[10px] font-semibold text-ink">Accepted</div>
              <div class="text-[9px] text-ink-muted">—</div>
            </div>
            <div class="stepper-step flex-1 flex flex-col items-center text-center">
              <div class="step-dot w-7 h-7 rounded-full border-2 border-surface-5 flex items-center justify-center mb-1.5">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
              </div>
              <div class="text-[10px] font-medium text-ink-muted">Packing</div>
              <div class="text-[9px] text-ink-muted">—</div>
            </div>
            <div class="stepper-step flex-1 flex flex-col items-center text-center">
              <div class="step-dot w-7 h-7 rounded-full border-2 border-surface-5 flex items-center justify-center mb-1.5">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
              </div>
              <div class="text-[10px] font-medium text-ink-muted">Shipped</div>
              <div class="text-[9px] text-ink-muted">—</div>
            </div>
            <div class="stepper-step flex-1 flex flex-col items-center text-center">
              <div class="step-dot w-7 h-7 rounded-full border-2 border-surface-5 flex items-center justify-center mb-1.5">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
              </div>
              <div class="text-[10px] font-medium text-ink-muted">Delivered</div>
              <div class="text-[9px] text-ink-muted">—</div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="mt-4 pt-4 border-t border-border flex items-center gap-2 flex-wrap">
            <button class="btn-primary text-xs flex items-center gap-1.5" onclick="acceptOrder()">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              Accept Order
            </button>
            <button class="btn-ghost text-xs flex items-center gap-1.5" onclick="markPacking()">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
              Mark Packed
            </button>
            <button class="btn-ghost text-xs flex items-center gap-1.5" onclick="openShipModal()">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
              Assign Courier
            </button>
            <button class="btn-ghost text-xs flex items-center gap-1.5" onclick="markDelivered()">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
              Delivered
            </button>
            <button class="ml-auto btn-ghost text-xs text-red-400 border-red-500/20 hover:bg-red-500/10 hover:border-red-500/30" onclick="cancelOrder()">
              Cancel
            </button>
          </div>
        </div>

        <!-- Two columns: Items + Customer -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-4">
          <!-- Items -->
          <div class="card">
            <div class="p-3 border-b border-border">
              <h3 class="text-xs font-semibold text-ink">Order Items</h3>
            </div>
            <div class="p-3 space-y-2">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-surface-3 flex items-center justify-center text-xl shrink-0">👕</div>
                <div class="flex-1 min-w-0">
                  <div class="text-xs font-medium text-ink truncate">Classic Oxford Shirt</div>
                  <div class="text-[10px] text-ink-muted">White · Size M · × 2</div>
                  <div class="flex gap-1 mt-1">
                    <span class="tag">SKU: OXF-WHT-M</span>
                  </div>
                </div>
                <div class="text-xs font-semibold text-ink shrink-0">LKR 7,000</div>
              </div>
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-surface-3 flex items-center justify-center text-xl shrink-0">👔</div>
                <div class="flex-1 min-w-0">
                  <div class="text-xs font-medium text-ink truncate">Slim Fit Chino</div>
                  <div class="text-[10px] text-ink-muted">Navy · Size 32 · × 1</div>
                  <div class="flex gap-1 mt-1">
                    <span class="tag">SKU: CHN-NVY-32</span>
                  </div>
                </div>
                <div class="text-xs font-semibold text-ink shrink-0">LKR 5,500</div>
              </div>
            </div>
            <div class="p-3 border-t border-border bg-surface-2/50 rounded-b-xl space-y-1">
              <div class="flex justify-between text-[10px] text-ink-muted"><span>Subtotal</span><span>LKR 12,500</span></div>
              <div class="flex justify-between text-[10px] text-ink-muted"><span>Shipping</span><span class="text-green-400">Free</span></div>
              <div class="flex justify-between text-xs font-semibold text-ink pt-1 border-t border-border"><span>Total</span><span>LKR 12,500</span></div>
            </div>
          </div>

          <!-- Customer + Shipping -->
          <div class="space-y-3">
            <div class="card p-3">
              <h3 class="text-xs font-semibold text-ink mb-2.5">Customer</h3>
              <div class="flex items-center gap-2.5 mb-3">
                <div class="w-9 h-9 rounded-full bg-surface-4 flex items-center justify-center text-sm font-semibold">NK</div>
                <div>
                  <div class="text-xs font-medium text-ink">Nimal Kumara</div>
                  <div class="text-[10px] text-ink-muted">nimal@email.com</div>
                </div>
              </div>
              <div class="grid grid-cols-2 gap-2 text-[10px]">
                <div><span class="text-ink-muted block">Phone</span><span class="text-ink font-mono">+94 71 234 5678</span></div>
                <div><span class="text-ink-muted block">Orders</span><span class="text-ink">14 total</span></div>
              </div>
            </div>
            <div class="card p-3">
              <h3 class="text-xs font-semibold text-ink mb-2.5">Shipping Address</h3>
              <div class="text-[10px] text-ink leading-relaxed">
                45/2, Galle Road<br>Colombo 03<br>Western Province, Sri Lanka
              </div>
              <div class="mt-2 pt-2 border-t border-border">
                <div class="flex justify-between text-[10px]">
                  <span class="text-ink-muted">Payment</span>
                  <span class="badge bg-amber-500/15 text-amber-400">Cash on Delivery</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Packing Checklist -->
        <div class="card p-3 mb-4">
          <h3 class="text-xs font-semibold text-ink mb-3">Packing Checklist</h3>
          <div class="space-y-2">
            <label class="flex items-center gap-2.5 cursor-pointer">
              <input type="checkbox" class="w-3.5 h-3.5 accent-yellow-400" checked>
              <span class="text-xs text-ink">Classic Oxford Shirt — White M × 2</span>
            </label>
            <label class="flex items-center gap-2.5 cursor-pointer">
              <input type="checkbox" class="w-3.5 h-3.5 accent-yellow-400">
              <span class="text-xs text-ink">Slim Fit Chino — Navy 32 × 1</span>
            </label>
            <label class="flex items-center gap-2.5 cursor-pointer">
              <input type="checkbox" class="w-3.5 h-3.5 accent-yellow-400">
              <span class="text-xs text-ink">Packing slip printed & inserted</span>
            </label>
            <label class="flex items-center gap-2.5 cursor-pointer">
              <input type="checkbox" class="w-3.5 h-3.5 accent-yellow-400">
              <span class="text-xs text-ink">Package sealed & labelled</span>
            </label>
          </div>
        </div>

        <!-- Tracking Timeline + Internal Notes -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
          <div class="card p-3">
            <h3 class="text-xs font-semibold text-ink mb-3">Tracking Timeline</h3>
            <div class="pl-5 relative">
              <div class="timeline-item done relative mb-4 pl-2">
                <div class="timeline-line"></div>
                <div class="text-xs font-medium text-ink">Order Placed</div>
                <div class="text-[10px] text-ink-muted">19 Mar 2026, 08:32 AM</div>
              </div>
              <div class="timeline-item done relative mb-4 pl-2">
                <div class="timeline-line"></div>
                <div class="text-xs font-medium text-ink">Payment Confirmed</div>
                <div class="text-[10px] text-ink-muted">COD — on delivery</div>
              </div>
              <div class="timeline-item relative mb-4 pl-2">
                <div class="timeline-line"></div>
                <div class="text-xs font-medium text-ink-muted">Processing</div>
                <div class="text-[10px] text-ink-muted">Awaiting action</div>
              </div>
              <div class="timeline-item relative mb-4 pl-2">
                <div class="timeline-line"></div>
                <div class="text-xs font-medium text-ink-muted">Packed</div>
                <div class="text-[10px] text-ink-muted">—</div>
              </div>
              <div class="timeline-item relative pl-2">
                <div class="text-xs font-medium text-ink-muted">Shipped</div>
                <div class="text-[10px] text-ink-muted">—</div>
              </div>
            </div>
          </div>

          <div class="card p-3">
            <h3 class="text-xs font-semibold text-ink mb-3">Internal Notes</h3>
            <div class="space-y-2 mb-3">
              <div class="bg-surface-3 rounded-lg p-2.5">
                <div class="text-[10px] text-ink-muted mb-0.5">Ashan · 08:45 AM</div>
                <div class="text-xs text-ink">Customer called to confirm size — confirmed M is correct.</div>
              </div>
            </div>
            <div class="flex gap-2">
              <input class="input-field text-xs py-1.5 flex-1" placeholder="Add internal note…" />
              <button class="btn-ghost text-xs px-3">Add</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ==================== PRODUCTS ==================== -->
  <div id="pane-products" class="pane p-5">
    <div class="flex items-center justify-between mb-5">
      <div>
        <h1 class="text-base font-semibold text-ink">Products & Catalog</h1>
        <p class="text-xs text-ink-muted mt-0.5">Manage your clothing catalog, variants, images & pricing</p>
      </div>
      <button class="btn-primary flex items-center gap-1.5" onclick="openAddProductModal()">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Product
      </button>
    </div>

    <!-- Filters -->
    <div class="flex items-center gap-2 mb-4 flex-wrap">
      <div class="flex items-center gap-1.5 bg-surface-2 rounded-md px-2.5 py-1.5 border border-border">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#8b8b9a" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input class="bg-transparent text-xs text-ink outline-none w-40 placeholder:text-ink-faint" placeholder="Search products…" />
      </div>
      <select class="input-field w-32 text-xs py-1.5"><option>All Categories</option><option>Shirts</option><option>Pants</option><option>Blazers</option><option>Accessories</option></select>
      <select class="input-field w-28 text-xs py-1.5"><option>All Status</option><option>Active</option><option>Draft</option><option>Archived</option></select>
      <select class="input-field w-28 text-xs py-1.5"><option>All Brands</option><option>DRAPE</option><option>Colombo Co.</option></select>
    </div>

    <!-- Product Table -->
    <div class="card overflow-hidden">
      <table class="w-full text-xs">
        <thead>
          <tr class="border-b border-border bg-surface-2">
            <th class="text-left px-4 py-2.5 text-[10px] text-ink-muted font-medium">Product</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">SKU</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Category</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Variants</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Price</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Stock</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Status</th>
            <th class="px-3 py-2.5"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border-subtle">
          <tr class="hover:bg-surface-2 cursor-pointer" onclick="openProductDetail()">
            <td class="px-4 py-3">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-surface-3 flex items-center justify-center text-lg shrink-0">👕</div>
                <div>
                  <div class="font-medium text-ink">Classic Oxford Shirt</div>
                  <div class="text-[10px] text-ink-muted mt-0.5">Button-down, slim fit</div>
                </div>
              </div>
            </td>
            <td class="px-3 py-3 font-mono text-ink-muted">OXF-001</td>
            <td class="px-3 py-3 text-ink-muted">Shirts</td>
            <td class="px-3 py-3">
              <div class="flex gap-1">
                <span class="chip bg-surface-3 text-ink-muted">S</span>
                <span class="chip bg-surface-3 text-ink-muted">M</span>
                <span class="chip bg-surface-3 text-ink-muted">L</span>
                <span class="chip bg-surface-3 text-ink-muted">XL</span>
              </div>
            </td>
            <td class="px-3 py-3">
              <div class="text-ink font-medium">LKR 3,500</div>
              <div class="text-[10px] text-red-400 line-through">LKR 4,000</div>
            </td>
            <td class="px-3 py-3">
              <div class="text-ink font-medium">48</div>
              <div class="text-[10px] text-green-400">In stock</div>
            </td>
            <td class="px-3 py-3"><span class="badge bg-green-500/15 text-green-400">Active</span></td>
            <td class="px-3 py-3">
              <div class="flex gap-1">
                <button class="btn-ghost text-[10px] px-2 py-1">Edit</button>
                <button class="btn-ghost text-[10px] px-2 py-1 text-red-400">Del</button>
              </div>
            </td>
          </tr>
          <tr class="hover:bg-surface-2 cursor-pointer">
            <td class="px-4 py-3">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-surface-3 flex items-center justify-center text-lg shrink-0">👔</div>
                <div>
                  <div class="font-medium text-ink">Slim Fit Chino</div>
                  <div class="text-[10px] text-ink-muted mt-0.5">Cotton blend, stretch</div>
                </div>
              </div>
            </td>
            <td class="px-3 py-3 font-mono text-ink-muted">CHN-001</td>
            <td class="px-3 py-3 text-ink-muted">Pants</td>
            <td class="px-3 py-3"><div class="flex gap-1"><span class="chip bg-surface-3 text-ink-muted">30</span><span class="chip bg-surface-3 text-ink-muted">32</span><span class="chip bg-surface-3 text-ink-muted">34</span></div></td>
            <td class="px-3 py-3"><div class="text-ink font-medium">LKR 5,500</div></td>
            <td class="px-3 py-3"><div class="text-ink font-medium">12</div><div class="text-[10px] text-amber-400">Low stock</div></td>
            <td class="px-3 py-3"><span class="badge bg-green-500/15 text-green-400">Active</span></td>
            <td class="px-3 py-3"><div class="flex gap-1"><button class="btn-ghost text-[10px] px-2 py-1">Edit</button><button class="btn-ghost text-[10px] px-2 py-1 text-red-400">Del</button></div></td>
          </tr>
          <tr class="hover:bg-surface-2 cursor-pointer">
            <td class="px-4 py-3">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-surface-3 flex items-center justify-center text-lg shrink-0">🧥</div>
                <div>
                  <div class="font-medium text-ink">Linen Blazer</div>
                  <div class="text-[10px] text-ink-muted mt-0.5">Summer collection, unstructured</div>
                </div>
              </div>
            </td>
            <td class="px-3 py-3 font-mono text-ink-muted">BLZ-001</td>
            <td class="px-3 py-3 text-ink-muted">Blazers</td>
            <td class="px-3 py-3"><div class="flex gap-1"><span class="chip bg-surface-3 text-ink-muted">S</span><span class="chip bg-surface-3 text-ink-muted">M</span><span class="chip bg-surface-3 text-ink-muted">L</span></div></td>
            <td class="px-3 py-3"><div class="text-ink font-medium">LKR 14,500</div></td>
            <td class="px-3 py-3"><div class="text-ink font-medium">6</div><div class="text-[10px] text-red-400">Critical</div></td>
            <td class="px-3 py-3"><span class="badge bg-green-500/15 text-green-400">Active</span></td>
            <td class="px-3 py-3"><div class="flex gap-1"><button class="btn-ghost text-[10px] px-2 py-1">Edit</button><button class="btn-ghost text-[10px] px-2 py-1 text-red-400">Del</button></div></td>
          </tr>
          <tr class="hover:bg-surface-2 cursor-pointer">
            <td class="px-4 py-3">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-surface-3 flex items-center justify-center text-lg shrink-0">🧦</div>
                <div>
                  <div class="font-medium text-ink">Polo T-Shirt</div>
                  <div class="text-[10px] text-ink-muted mt-0.5">Pique cotton, regular fit</div>
                </div>
              </div>
            </td>
            <td class="px-3 py-3 font-mono text-ink-muted">PLO-001</td>
            <td class="px-3 py-3 text-ink-muted">T-Shirts</td>
            <td class="px-3 py-3"><div class="flex gap-1"><span class="chip bg-surface-3 text-ink-muted">S</span><span class="chip bg-surface-3 text-ink-muted">M</span><span class="chip bg-surface-3 text-ink-muted">L</span><span class="chip bg-surface-3 text-ink-muted">XL</span></div></td>
            <td class="px-3 py-3"><div class="text-ink font-medium">LKR 2,250</div></td>
            <td class="px-3 py-3"><div class="text-ink font-medium">85</div><div class="text-[10px] text-green-400">In stock</div></td>
            <td class="px-3 py-3"><span class="badge bg-amber-500/15 text-amber-400">Draft</span></td>
            <td class="px-3 py-3"><div class="flex gap-1"><button class="btn-ghost text-[10px] px-2 py-1">Edit</button><button class="btn-ghost text-[10px] px-2 py-1 text-red-400">Del</button></div></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ==================== INVENTORY ==================== -->
  <div id="pane-inventory" class="pane p-5">
    <div class="flex items-center justify-between mb-5">
      <div>
        <h1 class="text-base font-semibold text-ink">Inventory</h1>
        <p class="text-xs text-ink-muted mt-0.5">Monitor stock levels and adjust quantities</p>
      </div>
      <button class="btn-ghost text-xs flex items-center gap-1.5">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Export CSV
      </button>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-3 gap-3 mb-5">
      <div class="stat-card text-center">
        <div class="text-2xl font-semibold text-ink mb-0.5">284</div>
        <div class="text-[10px] text-ink-muted">Total SKUs</div>
      </div>
      <div class="stat-card text-center border-amber-500/20">
        <div class="text-2xl font-semibold text-amber-400 mb-0.5">8</div>
        <div class="text-[10px] text-ink-muted">Low Stock</div>
      </div>
      <div class="stat-card text-center border-red-500/20">
        <div class="text-2xl font-semibold text-red-400 mb-0.5">3</div>
        <div class="text-[10px] text-ink-muted">Out of Stock</div>
      </div>
    </div>

    <div class="card overflow-hidden">
      <table class="w-full text-xs">
        <thead>
          <tr class="border-b border-border bg-surface-2">
            <th class="text-left px-4 py-2.5 text-[10px] text-ink-muted font-medium">Product / Variant</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">SKU</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Current</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Reserved</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Available</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Min Level</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Status</th>
            <th class="px-3 py-2.5"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border-subtle">
          <tr class="hover:bg-surface-2">
            <td class="px-4 py-2.5">
              <div class="font-medium text-ink">Oxford Shirt</div>
              <div class="flex gap-1 mt-1">
                <span class="color-swatch" style="background:#ffffff; border-color:#3a3a45;"></span>
                <span class="text-[10px] text-ink-muted ml-1">White · M</span>
              </div>
            </td>
            <td class="px-3 py-2.5 font-mono text-ink-muted">OXF-WHT-M</td>
            <td class="px-3 py-2.5 font-semibold text-ink">2</td>
            <td class="px-3 py-2.5 text-ink-muted">0</td>
            <td class="px-3 py-2.5 font-semibold text-red-400">2</td>
            <td class="px-3 py-2.5 text-ink-muted">10</td>
            <td class="px-3 py-2.5"><span class="badge bg-red-500/15 text-red-400">Critical</span></td>
            <td class="px-3 py-2.5"><button class="btn-ghost text-[10px] px-2 py-1">Adjust</button></td>
          </tr>
          <tr class="hover:bg-surface-2">
            <td class="px-4 py-2.5">
              <div class="font-medium text-ink">Slim Fit Chino</div>
              <div class="flex gap-1 mt-1">
                <span class="color-swatch" style="background:#1e3a5f;"></span>
                <span class="text-[10px] text-ink-muted ml-1">Navy · 32</span>
              </div>
            </td>
            <td class="px-3 py-2.5 font-mono text-ink-muted">CHN-NVY-32</td>
            <td class="px-3 py-2.5 font-semibold text-ink">4</td>
            <td class="px-3 py-2.5 text-ink-muted">1</td>
            <td class="px-3 py-2.5 font-semibold text-amber-400">3</td>
            <td class="px-3 py-2.5 text-ink-muted">10</td>
            <td class="px-3 py-2.5"><span class="badge bg-amber-500/15 text-amber-400">Low</span></td>
            <td class="px-3 py-2.5"><button class="btn-ghost text-[10px] px-2 py-1">Adjust</button></td>
          </tr>
          <tr class="hover:bg-surface-2">
            <td class="px-4 py-2.5">
              <div class="font-medium text-ink">Linen Blazer</div>
              <div class="flex gap-1 mt-1">
                <span class="color-swatch" style="background:#c19a6b;"></span>
                <span class="text-[10px] text-ink-muted ml-1">Camel · L</span>
              </div>
            </td>
            <td class="px-3 py-2.5 font-mono text-ink-muted">BLZ-CAM-L</td>
            <td class="px-3 py-2.5 font-semibold text-ink">3</td>
            <td class="px-3 py-2.5 text-ink-muted">0</td>
            <td class="px-3 py-2.5 font-semibold text-amber-400">3</td>
            <td class="px-3 py-2.5 text-ink-muted">8</td>
            <td class="px-3 py-2.5"><span class="badge bg-amber-500/15 text-amber-400">Low</span></td>
            <td class="px-3 py-2.5"><button class="btn-ghost text-[10px] px-2 py-1">Adjust</button></td>
          </tr>
          <tr class="hover:bg-surface-2">
            <td class="px-4 py-2.5">
              <div class="font-medium text-ink">Polo T-Shirt</div>
              <div class="flex gap-1 mt-1">
                <span class="color-swatch" style="background:#1a3a1a;"></span>
                <span class="text-[10px] text-ink-muted ml-1">Forest · L</span>
              </div>
            </td>
            <td class="px-3 py-2.5 font-mono text-ink-muted">PLO-GRN-L</td>
            <td class="px-3 py-2.5 font-semibold text-ink">42</td>
            <td class="px-3 py-2.5 text-ink-muted">3</td>
            <td class="px-3 py-2.5 font-semibold text-green-400">39</td>
            <td class="px-3 py-2.5 text-ink-muted">5</td>
            <td class="px-3 py-2.5"><span class="badge bg-green-500/15 text-green-400">OK</span></td>
            <td class="px-3 py-2.5"><button class="btn-ghost text-[10px] px-2 py-1">Adjust</button></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ==================== SHIPPING ==================== -->
  <div id="pane-shipping" class="pane p-5">
    <div class="flex items-center justify-between mb-5">
      <div>
        <h1 class="text-base font-semibold text-ink">Shipping & Delivery</h1>
        <p class="text-xs text-ink-muted mt-0.5">Fulfillment, couriers, tracking & shipping zones</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-5">
      <div class="stat-card"><div class="text-xl font-semibold text-ink mb-0.5">19</div><div class="text-[10px] text-ink-muted">Ready to Ship</div></div>
      <div class="stat-card"><div class="text-xl font-semibold text-cyan-400 mb-0.5">34</div><div class="text-[10px] text-ink-muted">In Transit</div></div>
      <div class="stat-card"><div class="text-xl font-semibold text-emerald-400 mb-0.5">89</div><div class="text-[10px] text-ink-muted">Delivered Today</div></div>
    </div>

    <!-- Courier Providers -->
    <div class="card p-4 mb-4">
      <h2 class="text-sm font-semibold text-ink mb-3">Delivery Providers</h2>
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-2">
        <div class="courier-card selected">
          <div class="font-semibold text-xs text-ink mb-0.5">DOMEX</div>
          <div class="text-[10px] text-ink-muted">Express · 1-2 days</div>
          <div class="text-[10px] text-green-400 mt-1">● Connected</div>
        </div>
        <div class="courier-card">
          <div class="font-semibold text-xs text-ink mb-0.5">Pronto</div>
          <div class="text-[10px] text-ink-muted">Standard · 2-3 days</div>
          <div class="text-[10px] text-green-400 mt-1">● Connected</div>
        </div>
        <div class="courier-card">
          <div class="font-semibold text-xs text-ink mb-0.5">Lanka Express</div>
          <div class="text-[10px] text-ink-muted">Economy · 3-5 days</div>
          <div class="text-[10px] text-ink-muted mt-1">○ Disconnected</div>
        </div>
        <div class="courier-card border-dashed border-accent/30 hover:border-accent">
          <div class="font-semibold text-xs text-accent mb-0.5">+ Add Courier</div>
          <div class="text-[10px] text-ink-muted">Integrate new provider</div>
        </div>
      </div>
    </div>

    <!-- Shipments Table -->
    <div class="card overflow-hidden">
      <div class="p-3 border-b border-border flex items-center justify-between">
        <h2 class="text-xs font-semibold text-ink">Active Shipments</h2>
        <button class="btn-ghost text-xs">Bulk Update</button>
      </div>
      <table class="w-full text-xs">
        <thead>
          <tr class="border-b border-border bg-surface-2">
            <th class="text-left px-4 py-2.5 text-[10px] text-ink-muted font-medium">Order</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Customer</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Courier</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Tracking #</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Dispatched</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">ETA</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border-subtle">
          <tr class="hover:bg-surface-2">
            <td class="px-4 py-2.5 font-mono text-ink-muted">#2026-004</td>
            <td class="px-3 py-2.5 font-medium text-ink">Malini Silva</td>
            <td class="px-3 py-2.5 text-ink-muted">DOMEX</td>
            <td class="px-3 py-2.5 font-mono text-accent">DMX-789123</td>
            <td class="px-3 py-2.5 text-ink-muted">18 Mar 14:00</td>
            <td class="px-3 py-2.5 text-ink">19 Mar</td>
            <td class="px-3 py-2.5"><span class="badge bg-cyan-500/15 text-cyan-400">In Transit</span></td>
          </tr>
          <tr class="hover:bg-surface-2">
            <td class="px-4 py-2.5 font-mono text-ink-muted">#2026-003</td>
            <td class="px-3 py-2.5 font-medium text-ink">Ruwan Abesinghe</td>
            <td class="px-3 py-2.5 text-ink-muted">Pronto</td>
            <td class="px-3 py-2.5 font-mono text-accent">PRT-456789</td>
            <td class="px-3 py-2.5 text-ink-muted">17 Mar 09:30</td>
            <td class="px-3 py-2.5 text-ink">19 Mar</td>
            <td class="px-3 py-2.5"><span class="badge bg-emerald-500/15 text-emerald-400">Delivered</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ==================== PAYMENTS ==================== -->
  <div id="pane-payments" class="pane p-5">
    <div class="flex items-center justify-between mb-5">
      <div>
        <h1 class="text-base font-semibold text-ink">Payments & Finances</h1>
        <p class="text-xs text-ink-muted mt-0.5">Transactions, gateways & refund management</p>
      </div>
    </div>

    <div class="grid grid-cols-4 gap-3 mb-5">
      <div class="stat-card"><div class="text-xl font-semibold text-green-400 mb-0.5">LKR 284.5K</div><div class="text-[10px] text-ink-muted">Collected Today</div></div>
      <div class="stat-card"><div class="text-xl font-semibold text-amber-400 mb-0.5">LKR 45.2K</div><div class="text-[10px] text-ink-muted">COD Pending</div></div>
      <div class="stat-card"><div class="text-xl font-semibold text-red-400 mb-0.5">LKR 8,500</div><div class="text-[10px] text-ink-muted">Refunded</div></div>
      <div class="stat-card"><div class="text-xl font-semibold text-ink mb-0.5">LKR 12,200</div><div class="text-[10px] text-ink-muted">Failed</div></div>
    </div>

    <!-- Payment Gateways -->
    <div class="grid grid-cols-3 gap-3 mb-5">
      <div class="card p-3">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs font-semibold text-ink">PayHere</span>
          <span class="badge bg-green-500/15 text-green-400">Active</span>
        </div>
        <div class="text-[10px] text-ink-muted mb-2">Local LKR payments</div>
        <div class="progress-bar"><div class="progress-fill" style="width:72%;"></div></div>
        <div class="text-[10px] text-ink-muted mt-1">72% of online transactions</div>
      </div>
      <div class="card p-3">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs font-semibold text-ink">Stripe</span>
          <span class="badge bg-green-500/15 text-green-400">Active</span>
        </div>
        <div class="text-[10px] text-ink-muted mb-2">International cards</div>
        <div class="progress-bar"><div class="progress-fill" style="width:18%;background:#3b82f6;"></div></div>
        <div class="text-[10px] text-ink-muted mt-1">18% of online transactions</div>
      </div>
      <div class="card p-3">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs font-semibold text-ink">Cash on Delivery</span>
          <span class="badge bg-green-500/15 text-green-400">Active</span>
        </div>
        <div class="text-[10px] text-ink-muted mb-2">Manual collection</div>
        <div class="progress-bar"><div class="progress-fill" style="width:10%;background:#f59e0b;"></div></div>
        <div class="text-[10px] text-ink-muted mt-1">10% of all orders</div>
      </div>
    </div>

    <!-- Transactions Table -->
    <div class="card overflow-hidden">
      <table class="w-full text-xs">
        <thead>
          <tr class="border-b border-border bg-surface-2">
            <th class="text-left px-4 py-2.5 text-[10px] text-ink-muted font-medium">Transaction ID</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Order</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Customer</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Gateway</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Amount</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Date</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Status</th>
            <th class="px-3 py-2.5"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border-subtle">
          <tr class="hover:bg-surface-2"><td class="px-4 py-2.5 font-mono text-ink-muted">TXN-8821</td><td class="px-3 py-2.5 font-mono">#2026-003</td><td class="px-3 py-2.5 text-ink">Ruwan Abesinghe</td><td class="px-3 py-2.5 text-ink-muted">PayHere</td><td class="px-3 py-2.5 font-semibold text-ink">LKR 24,000</td><td class="px-3 py-2.5 text-ink-muted">19 Mar 07:12</td><td class="px-3 py-2.5"><span class="badge bg-green-500/15 text-green-400">Success</span></td><td class="px-3 py-2.5"><button class="btn-ghost text-[10px] px-2 py-1">Refund</button></td></tr>
          <tr class="hover:bg-surface-2"><td class="px-4 py-2.5 font-mono text-ink-muted">TXN-8820</td><td class="px-3 py-2.5 font-mono">#2026-002</td><td class="px-3 py-2.5 text-ink">Sitha Perera</td><td class="px-3 py-2.5 text-ink-muted">Stripe</td><td class="px-3 py-2.5 font-semibold text-ink">LKR 8,200</td><td class="px-3 py-2.5 text-ink-muted">19 Mar 06:45</td><td class="px-3 py-2.5"><span class="badge bg-green-500/15 text-green-400">Success</span></td><td class="px-3 py-2.5"><button class="btn-ghost text-[10px] px-2 py-1">Refund</button></td></tr>
          <tr class="hover:bg-surface-2"><td class="px-4 py-2.5 font-mono text-ink-muted">TXN-8819</td><td class="px-3 py-2.5 font-mono">#2026-006</td><td class="px-3 py-2.5 text-ink">Priya Fernando</td><td class="px-3 py-2.5 text-ink-muted">PayHere</td><td class="px-3 py-2.5 font-semibold text-red-400">LKR 4,200</td><td class="px-3 py-2.5 text-ink-muted">18 Mar 22:10</td><td class="px-3 py-2.5"><span class="badge bg-red-500/15 text-red-400">Refunded</span></td><td class="px-3 py-2.5"><button class="btn-ghost text-[10px] px-2 py-1">View</button></td></tr>
          <tr class="hover:bg-surface-2"><td class="px-4 py-2.5 font-mono text-ink-muted">TXN-8818</td><td class="px-3 py-2.5 font-mono">#2026-001</td><td class="px-3 py-2.5 text-ink">Nimal Kumara</td><td class="px-3 py-2.5 text-ink-muted">COD</td><td class="px-3 py-2.5 font-semibold text-amber-400">LKR 12,500</td><td class="px-3 py-2.5 text-ink-muted">19 Mar 08:32</td><td class="px-3 py-2.5"><span class="badge bg-amber-500/15 text-amber-400">Pending</span></td><td class="px-3 py-2.5"><button class="btn-ghost text-[10px] px-2 py-1">Mark Paid</button></td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ==================== DISCOUNTS ==================== -->
  <div id="pane-discounts" class="pane p-5">
    <div class="flex items-center justify-between mb-5">
      <div>
        <h1 class="text-base font-semibold text-ink">Discounts & Promotions</h1>
        <p class="text-xs text-ink-muted mt-0.5">Coupons, flash sales & discount rules</p>
      </div>
      <button class="btn-primary text-xs flex items-center gap-1.5">+ Create Coupon</button>
    </div>

    <!-- Flash Sale Banner -->
    <div class="bg-gradient-to-r from-accent-muted to-surface-3 border border-accent/30 rounded-xl p-4 mb-5 flex items-center justify-between">
      <div>
        <div class="flex items-center gap-2 mb-1">
          <span class="text-[10px] font-mono font-semibold text-accent">FLASH SALE ACTIVE</span>
          <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse inline-block"></span>
        </div>
        <div class="text-sm font-semibold text-ink">Summer Collection — 20% OFF</div>
        <div class="text-[10px] text-ink-muted mt-0.5">Ends in 6h 42m · 148 uses so far</div>
      </div>
      <button class="btn-ghost text-xs">Edit</button>
    </div>

    <!-- Coupons -->
    <div class="card overflow-hidden mb-4">
      <div class="p-3 border-b border-border"><h2 class="text-xs font-semibold text-ink">Active Coupons</h2></div>
      <table class="w-full text-xs">
        <thead>
          <tr class="border-b border-border bg-surface-2">
            <th class="text-left px-4 py-2.5 text-[10px] text-ink-muted font-medium">Code</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Discount</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Min. Order</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Uses</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Expires</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Status</th>
            <th class="px-3 py-2.5"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border-subtle">
          <tr class="hover:bg-surface-2"><td class="px-4 py-2.5 font-mono font-semibold text-accent">WELCOME15</td><td class="px-3 py-2.5 text-ink">15% off</td><td class="px-3 py-2.5 text-ink-muted">LKR 5,000</td><td class="px-3 py-2.5 text-ink-muted">84 / ∞</td><td class="px-3 py-2.5 text-ink-muted">31 Mar 2026</td><td class="px-3 py-2.5"><span class="badge bg-green-500/15 text-green-400">Active</span></td><td class="px-3 py-2.5"><button class="btn-ghost text-[10px] px-2 py-1">Edit</button></td></tr>
          <tr class="hover:bg-surface-2"><td class="px-4 py-2.5 font-mono font-semibold text-accent">FREESHIP</td><td class="px-3 py-2.5 text-ink">Free shipping</td><td class="px-3 py-2.5 text-ink-muted">LKR 8,000</td><td class="px-3 py-2.5 text-ink-muted">211 / 500</td><td class="px-3 py-2.5 text-ink-muted">30 Apr 2026</td><td class="px-3 py-2.5"><span class="badge bg-green-500/15 text-green-400">Active</span></td><td class="px-3 py-2.5"><button class="btn-ghost text-[10px] px-2 py-1">Edit</button></td></tr>
          <tr class="hover:bg-surface-2"><td class="px-4 py-2.5 font-mono font-semibold text-ink-muted">BULK2GET1</td><td class="px-3 py-2.5 text-ink">Buy 2 get 1</td><td class="px-3 py-2.5 text-ink-muted">—</td><td class="px-3 py-2.5 text-ink-muted">32 / 100</td><td class="px-3 py-2.5 text-ink-muted">15 Mar 2026</td><td class="px-3 py-2.5"><span class="badge bg-surface-3 text-ink-muted">Expired</span></td><td class="px-3 py-2.5"><button class="btn-ghost text-[10px] px-2 py-1">Renew</button></td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ==================== CUSTOMERS ==================== -->
  <div id="pane-customers" class="pane p-5">
    <div class="flex items-center justify-between mb-5">
      <div>
        <h1 class="text-base font-semibold text-ink">Customers & CRM</h1>
        <p class="text-xs text-ink-muted mt-0.5">Customer profiles, order history & reviews</p>
      </div>
    </div>

    <div class="grid grid-cols-3 gap-3 mb-5">
      <div class="stat-card"><div class="text-xl font-semibold text-ink mb-0.5">1,284</div><div class="text-[10px] text-ink-muted">Total Customers</div></div>
      <div class="stat-card"><div class="text-xl font-semibold text-accent mb-0.5">342</div><div class="text-[10px] text-ink-muted">VIP Members</div></div>
      <div class="stat-card"><div class="text-xl font-semibold text-blue-400 mb-0.5">892</div><div class="text-[10px] text-ink-muted">Newsletter Subscribers</div></div>
    </div>

    <div class="card overflow-hidden">
      <table class="w-full text-xs">
        <thead>
          <tr class="border-b border-border bg-surface-2">
            <th class="text-left px-4 py-2.5 text-[10px] text-ink-muted font-medium">Customer</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Phone</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Orders</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Total Spent</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Tag</th>
            <th class="text-left px-3 py-2.5 text-[10px] text-ink-muted font-medium">Last Order</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border-subtle">
          <tr class="hover:bg-surface-2 cursor-pointer"><td class="px-4 py-2.5"><div class="flex items-center gap-2"><div class="w-7 h-7 rounded-full bg-surface-4 flex items-center justify-center text-[10px] font-semibold">NK</div><div><div class="font-medium text-ink">Nimal Kumara</div><div class="text-[10px] text-ink-muted">nimal@email.com</div></div></div></td><td class="px-3 py-2.5 font-mono text-ink-muted">+94 71 234 5678</td><td class="px-3 py-2.5 text-ink">14</td><td class="px-3 py-2.5 font-semibold text-ink">LKR 142,500</td><td class="px-3 py-2.5"><span class="badge bg-accent/15 text-accent">VIP</span></td><td class="px-3 py-2.5 text-ink-muted">Today</td></tr>
          <tr class="hover:bg-surface-2 cursor-pointer"><td class="px-4 py-2.5"><div class="flex items-center gap-2"><div class="w-7 h-7 rounded-full bg-surface-4 flex items-center justify-center text-[10px] font-semibold">SP</div><div><div class="font-medium text-ink">Sitha Perera</div><div class="text-[10px] text-ink-muted">sitha@gmail.com</div></div></div></td><td class="px-3 py-2.5 font-mono text-ink-muted">+94 77 345 6789</td><td class="px-3 py-2.5 text-ink">6</td><td class="px-3 py-2.5 font-semibold text-ink">LKR 48,200</td><td class="px-3 py-2.5"><span class="badge bg-blue-500/15 text-blue-400">Regular</span></td><td class="px-3 py-2.5 text-ink-muted">Today</td></tr>
          <tr class="hover:bg-surface-2 cursor-pointer"><td class="px-4 py-2.5"><div class="flex items-center gap-2"><div class="w-7 h-7 rounded-full bg-surface-4 flex items-center justify-center text-[10px] font-semibold">RA</div><div><div class="font-medium text-ink">Ruwan Abesinghe</div><div class="text-[10px] text-ink-muted">ruwan@hotmail.com</div></div></div></td><td class="px-3 py-2.5 font-mono text-ink-muted">+94 70 456 7890</td><td class="px-3 py-2.5 text-ink">22</td><td class="px-3 py-2.5 font-semibold text-ink">LKR 284,000</td><td class="px-3 py-2.5"><span class="badge bg-accent/15 text-accent">VIP</span></td><td class="px-3 py-2.5 text-ink-muted">Today</td></tr>
          <tr class="hover:bg-surface-2 cursor-pointer"><td class="px-4 py-2.5"><div class="flex items-center gap-2"><div class="w-7 h-7 rounded-full bg-surface-4 flex items-center justify-center text-[10px] font-semibold">PF</div><div><div class="font-medium text-ink">Priya Fernando</div><div class="text-[10px] text-ink-muted">priya@yahoo.com</div></div></div></td><td class="px-3 py-2.5 font-mono text-ink-muted">+94 76 567 8901</td><td class="px-3 py-2.5 text-ink">2</td><td class="px-3 py-2.5 font-semibold text-ink">LKR 12,400</td><td class="px-3 py-2.5"><span class="badge bg-red-500/15 text-red-400">Returned</span></td><td class="px-3 py-2.5 text-ink-muted">Yesterday</td></tr>
        </tbody>
      </table>
    </div>
  </div>

</main>
</div>

<!-- ==================== MODALS ==================== -->

<!-- Assign Courier Modal -->
<div id="ship-modal" class="modal-overlay hidden" onclick="if(event.target===this)closeShipModal()">
  <div class="modal p-5">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-sm font-semibold text-ink">Assign Courier — #ORD-2026-001</h2>
      <button onclick="closeShipModal()" class="text-ink-muted hover:text-ink">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="space-y-2 mb-4">
      <label class="courier-card selected flex items-center gap-3" onclick="selectCourier(this)">
        <input type="radio" name="courier" checked class="accent-yellow-400">
        <div class="flex-1">
          <div class="text-xs font-semibold text-ink">DOMEX Express</div>
          <div class="text-[10px] text-ink-muted">Estimated: 1-2 business days · LKR 350</div>
        </div>
      </label>
      <label class="courier-card flex items-center gap-3" onclick="selectCourier(this)">
        <input type="radio" name="courier" class="accent-yellow-400">
        <div class="flex-1">
          <div class="text-xs font-semibold text-ink">Pronto Standard</div>
          <div class="text-[10px] text-ink-muted">Estimated: 2-3 business days · LKR 200</div>
        </div>
      </label>
    </div>
    <div class="mb-4">
      <label class="section-label">Tracking Number (optional)</label>
      <input class="input-field" placeholder="Auto-generated if left empty" />
    </div>
    <div class="mb-4">
      <label class="section-label">Notes for courier</label>
      <textarea class="input-field" rows="2" placeholder="Fragile items, call before delivery…"></textarea>
    </div>
    <div class="flex gap-2">
      <button class="btn-primary flex-1" onclick="confirmShipment()">Confirm & Generate Label</button>
      <button class="btn-ghost" onclick="closeShipModal()">Cancel</button>
    </div>
  </div>
</div>

<!-- Refund Modal -->
<div id="refund-modal" class="modal-overlay hidden" onclick="if(event.target===this)closeRefundModal()">
  <div class="modal p-5">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-sm font-semibold text-ink">Issue Refund — #ORD-2026-001</h2>
      <button onclick="closeRefundModal()" class="text-ink-muted hover:text-ink">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="mb-4">
      <label class="section-label">Refund Type</label>
      <div class="flex gap-2">
        <button class="flex-1 tab-btn active text-xs py-2 rounded-lg border border-border" id="refund-full-btn" onclick="toggleRefundType('full')">Full Refund</button>
        <button class="flex-1 tab-btn text-xs py-2 rounded-lg border border-border" id="refund-partial-btn" onclick="toggleRefundType('partial')">Partial Refund</button>
      </div>
    </div>
    <div class="mb-4" id="refund-amount-row">
      <label class="section-label">Amount (LKR)</label>
      <input class="input-field" value="12500" id="refund-amount" />
    </div>
    <div class="mb-4">
      <label class="section-label">Reason</label>
      <select class="input-field"><option>Customer Request</option><option>Wrong Item Sent</option><option>Damaged Item</option><option>Quality Issue</option><option>Other</option></select>
    </div>
    <div class="mb-4">
      <label class="section-label">Internal Note</label>
      <textarea class="input-field" rows="2" placeholder="Add a note for this refund…"></textarea>
    </div>
    <div class="flex gap-2">
      <button class="btn-primary flex-1 bg-red-500 hover:bg-red-400" onclick="confirmRefund()">Process Refund</button>
      <button class="btn-ghost" onclick="closeRefundModal()">Cancel</button>
    </div>
  </div>
</div>

<!-- Add Product Modal -->
<div id="add-product-modal" class="modal-overlay hidden" onclick="if(event.target===this)closeAddProductModal()">
  <div class="modal p-5" style="width:600px;">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-sm font-semibold text-ink">Add New Product</h2>
      <button onclick="closeAddProductModal()" class="text-ink-muted hover:text-ink">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="grid grid-cols-2 gap-3 mb-3">
      <div class="col-span-2"><label class="section-label">Product Name</label><input class="input-field" placeholder="e.g. Classic Oxford Shirt" /></div>
      <div><label class="section-label">Category</label><select class="input-field"><option>Shirts</option><option>Pants</option><option>Blazers</option><option>T-Shirts</option><option>Accessories</option></select></div>
      <div><label class="section-label">Brand</label><select class="input-field"><option>DRAPE</option><option>Colombo Co.</option></select></div>
      <div><label class="section-label">Regular Price (LKR)</label><input class="input-field" placeholder="0.00" /></div>
      <div><label class="section-label">Sale Price (LKR)</label><input class="input-field" placeholder="Optional" /></div>
    </div>
    <div class="mb-3">
      <label class="section-label">Available Sizes</label>
      <div class="flex gap-1.5 flex-wrap">
        <button class="size-chip selected" onclick="toggleSize(this)">XS</button>
        <button class="size-chip selected" onclick="toggleSize(this)">S</button>
        <button class="size-chip selected" onclick="toggleSize(this)">M</button>
        <button class="size-chip selected" onclick="toggleSize(this)">L</button>
        <button class="size-chip selected" onclick="toggleSize(this)">XL</button>
        <button class="size-chip" onclick="toggleSize(this)">XXL</button>
      </div>
    </div>
    <div class="mb-3">
      <label class="section-label">Colors</label>
      <div class="flex gap-2 flex-wrap">
        <span class="color-swatch selected" style="background:#ffffff; border-color:#e8c547;" title="White"></span>
        <span class="color-swatch" style="background:#1a1a1a;" title="Black"></span>
        <span class="color-swatch" style="background:#1e3a5f;" title="Navy"></span>
        <span class="color-swatch" style="background:#c19a6b;" title="Camel"></span>
        <span class="color-swatch" style="background:#8B4513;" title="Brown"></span>
        <span class="color-swatch" style="background:#1a3a1a;" title="Forest"></span>
        <button class="w-5 h-5 rounded-full border border-dashed border-border-strong text-ink-faint text-[10px] flex items-center justify-center">+</button>
      </div>
    </div>
    <div class="mb-3">
      <label class="section-label">SEO</label>
      <div class="space-y-1.5">
        <input class="input-field text-xs" placeholder="Meta title" />
        <input class="input-field text-xs" placeholder="Slug (auto-generated)" />
        <textarea class="input-field text-xs" rows="2" placeholder="Meta description…"></textarea>
      </div>
    </div>
    <div class="mb-4">
      <label class="section-label">Tags</label>
      <input class="input-field text-xs" placeholder="e.g. summer, formal, slim-fit (comma separated)" />
    </div>
    <div class="flex gap-2">
      <button class="btn-primary flex-1" onclick="closeAddProductModal()">Save Product</button>
      <button class="btn-ghost" onclick="closeAddProductModal()">Cancel</button>
    </div>
  </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed bottom-5 right-5 z-50 hidden">
  <div class="bg-surface-4 border border-border rounded-lg px-4 py-3 flex items-center gap-2.5 shadow-xl">
    <svg id="toast-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
    <span id="toast-msg" class="text-xs text-ink font-medium"></span>
  </div>
</div>

<script>
// ---- Navigation ----
function showPane(name) {
  document.querySelectorAll('.pane').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  document.getElementById('pane-' + name).classList.add('active');
  document.getElementById('nav-' + name).classList.add('active');
}

// ---- Order Selection ----
function selectOrder(el, id) {
  document.querySelectorAll('.order-row').forEach(r => r.classList.remove('selected'));
  el.classList.add('selected');
}

// ---- Toast ----
function showToast(msg, success=true) {
  const t = document.getElementById('toast');
  document.getElementById('toast-msg').textContent = msg;
  document.getElementById('toast-icon').setAttribute('stroke', success ? '#10b981' : '#ef4444');
  t.classList.remove('hidden');
  setTimeout(() => t.classList.add('hidden'), 2500);
}

// ---- Order Actions ----
function acceptOrder() {
  const steps = document.querySelectorAll('.stepper-step');
  steps[0].classList.add('done');
  steps[1].classList.add('done');
  steps[1].classList.remove('active');
  steps[2].classList.add('active');
  showToast('Order accepted and moved to Processing');
}

function markPacking() {
  const steps = document.querySelectorAll('.stepper-step');
  steps[2].classList.add('done');
  steps[2].classList.remove('active');
  steps[3].classList.add('active');
  showToast('Order marked as Packed');
}

function markDelivered() {
  const steps = document.querySelectorAll('.stepper-step');
  steps.forEach(s => { s.classList.add('done'); s.classList.remove('active'); });
  showToast('Order marked as Delivered ✓');
}

function cancelOrder() {
  if(confirm('Are you sure you want to cancel this order?')) showToast('Order cancelled', false);
}

function printInvoice() { showToast('Preparing invoice for print…'); }

// ---- Ship Modal ----
function openShipModal() { document.getElementById('ship-modal').classList.remove('hidden'); }
function closeShipModal() { document.getElementById('ship-modal').classList.add('hidden'); }
function confirmShipment() { closeShipModal(); markDelivered(); showToast('Courier assigned & label generated'); }
function selectCourier(el) {
  document.querySelectorAll('.courier-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
}

// ---- Refund Modal ----
function openRefundModal() { document.getElementById('refund-modal').classList.remove('hidden'); }
function closeRefundModal() { document.getElementById('refund-modal').classList.add('hidden'); }
function confirmRefund() { closeRefundModal(); showToast('Refund processed successfully', true); }
function toggleRefundType(type) {
  const full = document.getElementById('refund-full-btn');
  const partial = document.getElementById('refund-partial-btn');
  const amt = document.getElementById('refund-amount');
  if(type === 'full') {
    full.classList.add('active'); partial.classList.remove('active');
    amt.value = '12500'; amt.readOnly = true;
  } else {
    partial.classList.add('active'); full.classList.remove('active');
    amt.readOnly = false; amt.focus();
  }
}

// ---- Add Product Modal ----
function openAddProductModal() { document.getElementById('add-product-modal').classList.remove('hidden'); }
function closeAddProductModal() { document.getElementById('add-product-modal').classList.add('hidden'); }
function openAddOrderModal() { showPane('orders'); showToast('Create a new order from the Orders panel'); }

// ---- Product detail (placeholder) ----
function openProductDetail() { showToast('Opening product editor…'); }

// ---- Toggle size chip ----
function toggleSize(el) { el.classList.toggle('selected'); }

// ---- Tab buttons in order list ----
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    this.closest('.flex').querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
  });
});
</script>
</body>
</html>