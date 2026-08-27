@extends('layouts.admin')

@section('title', 'Tambah User')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin-user.css') }}">

<div class="add-user-page">

    {{-- HEADER --}}
    <div class="add-user-header">

        <div class="add-user-header-content">

            <span class="add-user-label">
                ADMINISTRATOR
            </span>

            <h1>
                Tambah User Baru
            </h1>

            <p>
                Tambahkan pengguna baru ke dalam sistem informasi sekolah.
            </p>

        </div>

    </div>


    {{-- FORM CARD --}}
    <div class="add-user-card">

        <div class="add-user-card-header">

            <div class="add-user-card-icon">

                <svg
                    width="23"
                    height="23"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M19 8v6"></path>
                    <path d="M22 11h-6"></path>
                </svg>

            </div>

            <div>

                <h2>
                    Informasi User
                </h2>

                <p>
                    Lengkapi data pengguna di bawah ini.
                </p>

            </div>

        </div>


        <div class="add-user-card-body">

            <form
                method="POST"
                action="{{ route('admin.users.store') }}"
            >

                @csrf

                @include('admin.users._form', [
                    'user' => null,
                    'roles' => $roles
                ])


                {{-- ACTION --}}
                <div class="add-user-actions">

                    <button
                        type="submit"
                        class="add-user-save"
                    >

                        <svg
                            width="17"
                            height="17"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"></path>
                            <path d="M17 21v-8H7v8"></path>
                            <path d="M7 3v5h8"></path>
                        </svg>

                        Simpan

                    </button>


                    <a
                        href="{{ route('admin.users.index') }}"
                        class="add-user-cancel"
                    >
                        Batal
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection