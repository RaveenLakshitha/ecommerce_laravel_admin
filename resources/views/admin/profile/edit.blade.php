@extends('layouts.app')

@section('title', 'Sentinel Profile')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 pb-4 sm:py-12 pt-24 transition-all duration-300">
    <div class="max-w-5xl mx-auto space-y-12">
        
        {{-- Header Area --}}
        <div class="mb-10 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 animate-fade-in-up">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white uppercase tracking-tighter decoration-indigo-500/30 underline underline-offset-8">Sentinel Profile</h1>
                <p class="mt-6 text-sm text-gray-400 dark:text-gray-500 font-medium italic underline decoration-indigo-500/10 underline-offset-4">Coordinate personal identity markers, calibrate security credentials, and manage account lifecycle parameters.</p>
            </div>
            <div class="flex items-center gap-3 animate-fade-in-up delay-100">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-surface-tonal-a20 border border-gray-200 dark:border-surface-tonal-a30 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-surface-tonal-a30 transition-all shadow-sm active:scale-95 group">
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    Center Return
                </a>
            </div>
        </div>

        {{-- Profile Information --}}
        <div class="p-8 sm:p-12 bg-white dark:bg-surface-tonal-a20 shadow-sm rounded-[2.5rem] border border-gray-100 dark:border-surface-tonal-a30 animate-fade-in-scale">
            <div class="max-w-2xl">
                @include('admin.profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Security recalibration --}}
        <div class="p-8 sm:p-12 bg-white dark:bg-surface-tonal-a20 shadow-sm rounded-[2.5rem] border border-gray-100 dark:border-surface-tonal-a30 animate-fade-in-scale delay-100">
            <div class="max-w-2xl">
                @include('admin.profile.partials.update-password-form')
            </div>
        </div>

        {{-- Decommission Protocol --}}
        <div class="p-8 sm:p-12 bg-rose-50/30 dark:bg-rose-950/10 shadow-sm rounded-[2.5rem] border border-rose-100 dark:border-rose-950/30 animate-fade-in-scale delay-200">
            <div class="max-w-2xl">
                @include('admin.profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fade-in-scale {
        from { opacity: 0; transform: scale(0.98); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fade-in-up { animation: fade-in-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-fade-in-scale { animation: fade-in-scale 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
</style>
@endsection
