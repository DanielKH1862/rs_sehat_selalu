@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-gray-900 mb-4">Sistem Antrian RS Sehat Selalu</h1>
            <p class="text-xl text-gray-600">Sistem antrian digital untuk pelayanan yang lebih efisien</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1: Ambil Antrian -->
            <a href="/pasien" class="bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 p-8 text-center group hover:-translate-y-2">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-200 transition-colors">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-3">Ambil Antrian</h2>
                <p class="text-gray-600">Ambil nomor antrian untuk layanan yang Anda butuhkan</p>
            </a>

            <!-- Card 2: Panel Petugas -->
            <a href="/petugas" class="bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 p-8 text-center group hover:-translate-y-2">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-green-200 transition-colors">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-3">Panel Petugas</h2>
                <p class="text-gray-600">Kelola dan panggil antrian pasien</p>
            </a>

            <!-- Card 3: Display Antrian -->
            <a href="/display" class="bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 p-8 text-center group hover:-translate-y-2">
                <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-purple-200 transition-colors">
                    <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-3">Display Antrian</h2>
                <p class="text-gray-600">Tampilan layar pemanggilan antrian</p>
            </a>
        </div>
    </div>
</div>
@endsection
