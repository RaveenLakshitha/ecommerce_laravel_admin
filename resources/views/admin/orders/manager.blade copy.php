@extends('layouts.app')

@section('title', 'Order Fulfillment Manager')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20 h-[calc(100vh-4rem)] overflow-hidden flex flex-col">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 shrink-0">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Order Manager</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage orders seamlessly across their fulfillment lifecycle.</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-1.5 text-sm font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                <svg class="mr-1.5 h-2 w-2 text-blue-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                Auto-refresh Active
            </span>
            <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                View All Orders
            </a>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="flex-1 overflow-x-auto overflow-y-hidden">
        <div class="flex h-full gap-6 pb-4" style="min-width: 1200px;">
            
            <!-- Column 1: New Orders (Pending) -->
            <div class="flex flex-col flex-1 bg-gray-50/50 dark:bg-gray-900/20 rounded-xl border border-gray-200 dark:border-gray-800">
                <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center bg-white dark:bg-gray-800 rounded-t-xl shrink-0 inset-0 shadow-sm border-t-4 border-t-yellow-400">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        New Orders (Accept)
                    </h2>
                    <span class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 py-1 px-2.5 rounded-full text-xs font-medium">3</span>
                </div>
                <div class="p-4 flex-1 overflow-y-auto space-y-4">
                    <!-- Dummy Card 1 -->
                    @for($i = 1; $i <= 3; $i++)
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition cursor-grab group">
                         <div class="flex justify-between items-start mb-2">
                             <a href="#" class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline">#ORD-{{ 5040 + $i }}</a>
                             <span class="text-xs text-gray-400">2 min ago</span>
                         </div>
                         <div class="mb-3">
                             <h3 class="text-sm font-medium text-gray-900 dark:text-white">John Doe {{ $i }}</h3>
                             <p class="text-xs text-gray-500 dark:text-gray-400">3 Items &bull; $145.00</p>
                         </div>
                         <div class="flex justify-between items-center mt-4">
                             <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Paid</span>
                             <button class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 opacity-0 group-hover:opacity-100 transition shadow-sm">
                                 Accept Order
                             </button>
                         </div>
                    </div>
                    @endfor
                </div>
            </div>

            <!-- Column 2: Accepted / Packing -->
            <div class="flex flex-col flex-1 bg-gray-50/50 dark:bg-gray-900/20 rounded-xl border border-gray-200 dark:border-gray-800">
                <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center bg-white dark:bg-gray-800 rounded-t-xl shrink-0 shadow-sm border-t-4 border-t-blue-500">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Packing
                    </h2>
                    <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 py-1 px-2.5 rounded-full text-xs font-medium">4</span>
                </div>
                <div class="p-4 flex-1 overflow-y-auto space-y-4">
                     <!-- Dummy Card -->
                    @for($i = 1; $i <= 4; $i++)
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-blue-200 dark:border-blue-900 hover:shadow-md transition cursor-grab group">
                         <div class="flex justify-between items-start mb-2">
                             <a href="#" class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline">#ORD-{{ 4010 + $i }}</a>
                             <span class="text-xs text-gray-400">1 hr ago</span>
                         </div>
                         <div class="mb-3">
                             <h3 class="text-sm font-medium text-gray-900 dark:text-white">Sarah Smith {{ $i }}</h3>
                             <p class="text-xs text-gray-500 dark:text-gray-400 items-center flex gap-1">
                                 <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                 New York, NY
                             </p>
                         </div>
                         <div class="flex justify-between items-center mt-4">
                             <button class="px-2 py-1 bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 text-xs font-medium rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                 Print Label
                             </button>
                             <button class="px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition shadow-sm">
                                 Ready to Ship
                             </button>
                         </div>
                    </div>
                    @endfor
                </div>
            </div>

            <!-- Column 3: Shipped / In Transit -->
            <div class="flex flex-col flex-1 bg-gray-50/50 dark:bg-gray-900/20 rounded-xl border border-gray-200 dark:border-gray-800">
                <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center bg-white dark:bg-gray-800 rounded-t-xl shrink-0 shadow-sm border-t-4 border-t-indigo-500">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                        In Transit
                    </h2>
                    <span class="bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 py-1 px-2.5 rounded-full text-xs font-medium">2</span>
                </div>
                <div class="p-4 flex-1 overflow-y-auto space-y-4">
                    <!-- Dummy Card -->
                    @for($i = 1; $i <= 2; $i++)
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition cursor-grab">
                         <div class="flex justify-between items-start mb-2">
                             <a href="#" class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline">#ORD-{{ 3020 + $i }}</a>
                             <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">FedEx</span>
                         </div>
                         <div class="mb-3">
                             <h3 class="text-sm font-medium text-gray-900 dark:text-white">Michael Brown {{ $i }}</h3>
                             <p class="text-xs text-gray-500 font-mono mt-1 blur-[1px] hover:blur-none transition-all">TRK-99283{{ $i }}</p>
                         </div>
                         
                         <!-- Tracking Progress -->
                         <div class="mt-3">
                             <div class="flex justify-between text-[10px] text-gray-500 mb-1">
                                 <span>Shipped</span>
                                 <span>Out for delivery</span>
                             </div>
                             <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
                                <div class="bg-indigo-600 h-1.5 rounded-full" style="width: 75%"></div>
                             </div>
                         </div>

                         <div class="flex justify-end mt-4">
                             <button class="px-3 py-1.5 bg-white border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 dark:bg-gray-800 text-xs font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm">
                                 Mark Delivered
                             </button>
                         </div>
                    </div>
                    @endfor
                </div>
            </div>

            <!-- Column 4: Delivered -->
            <div class="flex flex-col flex-1 bg-gray-50/50 dark:bg-gray-900/20 rounded-xl border border-gray-200 dark:border-gray-800 opacity-80">
                <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center bg-white dark:bg-gray-800 rounded-t-xl shrink-0 shadow-sm border-t-4 border-t-green-500">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Delivered
                    </h2>
                    <span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 py-1 px-2.5 rounded-full text-xs font-medium">12+</span>
                </div>
                <div class="p-4 flex-1 overflow-y-auto space-y-4">
                    <!-- Dummy Card -->
                    @for($i = 1; $i <= 3; $i++)
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 opacity-75 hover:opacity-100 transition">
                         <div class="flex justify-between items-start mb-2">
                             <a href="#" class="text-gray-500 dark:text-gray-400 font-bold hover:underline">#ORD-{{ 2050 + $i }}</a>
                             <span class="text-xs text-green-600 flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Complete</span>
                         </div>
                         <div class="mb-1">
                             <h3 class="text-sm font-medium text-gray-900 dark:text-white">Emily White {{ $i }}</h3>
                             <p class="text-xs text-gray-500 mt-1">Delivered today, 10:45 AM</p>
                         </div>
                    </div>
                    @endfor
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
