@extends('layouts.app')

@section('title', __('file.sentinel_profile'))

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">

            {{-- Header Area --}}
            <div class="mb-10 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mt-10">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white uppercase tracking-tighter">
                        {{ __('file.sentinel_profile') }}</h1>
                    <p
                        class="mt-2 text-sm text-gray-500 dark:text-gray-400 font-medium uppercase tracking-widest leading-relaxed">
                        {{ __('file.profile_overview_text') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-surface-tonal-a30 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all shadow-sm active:scale-95 group">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                        {{ __('file.center_return') }}
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 max-w-4xl">
                {{-- Profile Information --}}
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                    <div
                        class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                        <h2 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-widest">
                            {{ __('file.profile_information') }}</h2>
                    </div>
                    <div class="p-6">
                        @include('admin.profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Security recalibration --}}
                <div
                    class="bg-white dark:bg-surface-tonal-a20 rounded-lg shadow-sm border border-gray-200 dark:border-surface-tonal-a30 overflow-hidden">
                    <div
                        class="px-4 py-3 border-b border-gray-100 dark:border-surface-tonal-a30 bg-gray-100/50 dark:bg-surface-tonal-a20">
                        <h2 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-widest">
                            {{ __('file.security_recalibration') }}</h2>
                    </div>
                    <div class="p-6">
                        @include('admin.profile.partials.update-password-form')
                    </div>
                </div>

                {{-- Decommission Protocol --}}
                <div
                    class="bg-rose-50/30 dark:bg-rose-950/10 rounded-lg shadow-sm border border-rose-100 dark:border-rose-950/30 overflow-hidden">
                    <div
                        class="px-4 py-3 border-b border-rose-100/50 dark:border-rose-950/30 bg-rose-50/50 dark:bg-rose-950/20">
                        <h2 class="text-sm font-bold text-rose-900 dark:text-rose-400 uppercase tracking-widest">
                            {{ __('file.decommission_protocol') }}</h2>
                    </div>
                    <div class="p-6">
                        @include('admin.profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection