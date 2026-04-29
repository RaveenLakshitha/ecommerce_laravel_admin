@extends('layouts.app')

@section('title', __('file.categories') . ' - ' . __('file.tree_view'))

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-20 transition-all duration-300">
        <div class="max-w-5xl mx-auto">

            {{-- Header Area --}}
            <div class="mb-10 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 animate-fade-in-up">
                <div>
                    <nav class="flex items-center gap-2 mb-4 group/nav">
                        <a href="{{ route('categories.index') }}"
                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">{{ __('file.taxonomy_grid') }}</a>
                        <svg class="w-3 h-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                        </svg>
                        <span
                            class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest italic">{{ __('file.recursive_tree_analytics') }}</span>
                    </nav>
                    <h1
                        class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white uppercase tracking-tighter decoration-indigo-500/30 underline underline-offset-8">
                        {{ __('file.hierarchy_visualization') }}</h1>
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400 font-medium italic">{{ __('file.tracing_structural_lineage') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('categories.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-surface-tonal-a30 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all shadow-sm active:scale-95 group">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                        {{ __('file.back_to_grid') }}
                    </a>
                </div>
            </div>

            {{-- Tree Card --}}
            <div
                class="bg-white dark:bg-surface-tonal-a20 rounded-3xl shadow-sm border border-gray-100 dark:border-surface-tonal-a30 overflow-hidden animate-fade-in-scale">
                <div class="p-10">
                    <ul class="space-y-6">
                        @forelse($categories as $category)
                            <li class="relative">
                                {{-- Phase 1: Root Architecture --}}
                                <div class="flex items-center group/root transition-all hover:translate-x-1">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-indigo-500/10 dark:bg-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 border border-indigo-200/50 dark:border-indigo-500/30 mr-4 shadow-lg shadow-indigo-500/5 group-hover/root:bg-indigo-600 group-hover/root:text-white transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="font-black text-lg text-gray-900 dark:text-white uppercase tracking-tighter leading-none group-hover/root:text-indigo-600 dark:group-hover/root:text-indigo-400 transition-colors">{{ $category->name }}</span>
                                        <span
                                            class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-0.5">{{ __('file.primary_logic_node') }}</span>
                                    </div>
                                    @if(!$category->is_active)
                                        <span
                                            class="ml-4 px-3 py-1 rounded-full text-[9px] font-black bg-gray-100 text-gray-400 dark:bg-surface-tonal-a30 dark:text-gray-500 border border-gray-200 dark:border-surface-tonal-a40">{{ __('file.decommissioned') }}</span>
                                    @endif
                                    <div class="ml-auto opacity-0 group-hover/root:opacity-100 transition-opacity">
                                        <a href="{{ route('categories.edit', $category) }}"
                                            class="text-[9px] font-black text-indigo-500 uppercase tracking-widest hover:underline">{{ __('file.calibrate') }}</a>
                                    </div>
                                </div>

                                {{-- Phase 2: Structural Branches --}}
                                @if($category->children->count() > 0)
                                    <ul class="mt-6 pl-10 border-l-2 border-gray-50 dark:border-surface-tonal-a30 ml-5 space-y-5">
                                        @foreach($category->children as $child)
                                            <li class="relative">
                                                <div
                                                    class="flex items-center text-gray-800 dark:text-gray-200 group/branch relative before:absolute before:w-6 before:h-0.5 before:bg-gray-50 dark:before:bg-surface-tonal-a30 before:-left-10 before:top-1/2 before:-translate-y-1/2 hover:translate-x-1 transition-all">
                                                    <div
                                                        class="w-2 h-2 rounded-full bg-indigo-500/30 border border-indigo-500/50 mr-3 group-hover/branch:bg-indigo-600 group-hover/branch:scale-125 transition-all shadow-[0_0_8px_rgba(99,102,241,0.2)]">
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span
                                                            class="font-bold text-[14px] text-gray-800 dark:text-gray-100 group-hover/branch:text-indigo-600 dark:group-hover/branch:text-indigo-400 transition-colors">{{ $child->name }}</span>
                                                        <span
                                                            class="text-[9px] font-bold text-gray-400 uppercase tracking-widest italic leading-none">{{ __('file.sub_classification') }}</span>
                                                    </div>
                                                    @if(!$child->is_active)
                                                        <span
                                                            class="ml-3 px-2 py-0.5 rounded-full text-[8px] font-black bg-gray-50 text-gray-300 dark:bg-surface-tonal-a30 dark:text-gray-600">{{ __('file.inactive') }}</span>
                                                    @endif
                                                </div>

                                                {{-- Phase 3: Terminal Elements --}}
                                                @if($child->children->count() > 0)
                                                    <ul
                                                        class="mt-4 pl-8 border-l border-dashed border-gray-200 dark:border-surface-tonal-a30/50 ml-1.5 space-y-3 pb-2">
                                                        @foreach($child->children as $grandchild)
                                                            <li
                                                                class="flex items-center text-gray-600 dark:text-gray-400 text-sm relative before:absolute before:w-5 before:h-px before:bg-gray-200 dark:before:bg-surface-tonal-a30/50 before:-left-8 before:top-1/2 before:-translate-y-1/2 group/leaf hover:translate-x-1 transition-all">
                                                                <div
                                                                    class="w-1.5 h-1.5 rounded-sm border border-gray-300 dark:border-surface-tonal-a40 mr-3 group-hover/leaf:bg-indigo-500 group-hover/leaf:border-indigo-500 transition-all rotate-45">
                                                                </div>
                                                                <span
                                                                    class="text-xs font-semibold group-hover/leaf:text-indigo-600 dark:group-hover/leaf:text-indigo-400 transition-colors">{{ $grandchild->name }}</span>
                                                                @if(!$grandchild->is_active)
                                                                    <span
                                                                        class="ml-2 w-1.5 h-1.5 rounded-full bg-rose-500 shadow-[0_0_5px_rgba(244,63,94,0.4)]"></span>
                                                                @endif

                                                                @if($grandchild->children->count() > 0)
                                                                    <span
                                                                        class="ml-3 text-[9px] font-black text-gray-400 uppercase tracking-widest bg-gray-50 dark:bg-surface-tonal-a30 px-2 py-0.5 rounded-md decoration-indigo-500/20 underline italic underline-offset-2">+{{ $grandchild->children->count() }}
                                                                        {{ __('file.descendants') }}</span>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                            @if(!$loop->last)
                                <div
                                    class="h-px bg-gray-100/50 dark:bg-surface-tonal-a30/30 my-8 -mx-10 shadow-sm shadow-gray-100/50">
                                </div>
                            @endif
                        @empty
                            <div class="text-center py-20 animate-fade-in-up">
                                <div
                                    class="mx-auto w-20 h-20 bg-gray-50 dark:bg-surface-tonal-a30 rounded-3xl flex items-center justify-center text-gray-200 dark:text-gray-600 mb-6 scale-125 opacity-30 transform -rotate-12">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                </div>
                                <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('file.zero_trace_integrity') }}
                                </p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 italic font-medium">{{ __('file.no_taxonomic_definitions') }}</p>
                            </div>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E5E7EB;
            border-radius: 10px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
        }

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fade-in-scale {
            from {
                opacity: 0;
                transform: scale(0.97);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-fade-in-scale {
            animation: fade-in-scale 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
@endsection