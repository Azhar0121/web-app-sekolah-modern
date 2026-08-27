@extends('layouts.admin')

@section('title', 'Edit Role: ' . $role->name)

@section('content')

<link rel="stylesheet" href="{{ asset('css/roles-edit.css') }}">

<div class="roles-page">

    {{-- HEADER --}}
    <div class="roles-header">

        <div class="roles-title-area">

            <span class="roles-label">
                ADMINISTRATOR
            </span>

            <h1>
                Edit Role: {{ $role->name }}
            </h1>

            <p>
                Perbarui informasi role dan atur permission yang dimiliki
                oleh role ini.
            </p>

        </div>

    </div>


    <form method="POST" action="{{ route('admin.roles.update', $role) }}">

        @csrf
        @method('PUT')


        {{-- ERROR --}}
        @if ($errors->any())

            <div class="role-error">

                <div class="role-error-icon">

                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 8v4"></path>
                        <path d="M12 16h.01"></path>
                    </svg>

                </div>

                <div>

                    <strong>
                        Terjadi kesalahan
                    </strong>

                    @foreach ($errors->all() as $error)

                        <div>
                            {{ $error }}
                        </div>

                    @endforeach

                </div>

            </div>

        @endif


        {{-- INFORMASI ROLE --}}
        <div class="role-edit-card">

            <div class="role-edit-card-header">

                <div class="role-edit-icon">

                    <svg
                        width="22"
                        height="22"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>

                </div>

                <div>

                    <h2>
                        Informasi Role
                    </h2>

                    <p>
                        Tentukan nama dan deskripsi role.
                    </p>

                </div>

            </div>


            <div class="role-edit-body">

                <div class="role-form-grid">

                    {{-- NAMA --}}
                    <div class="role-form-group">

                        <label for="name">
                            Nama Role
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name', $role->name) }}"
                            required
                        >

                    </div>


                    {{-- DESKRIPSI --}}
                    <div class="role-form-group">

                        <label for="description">
                            Deskripsi
                        </label>

                        <input
                            type="text"
                            name="description"
                            id="description"
                            value="{{ old('description', $role->description) }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- PERMISSION --}}
        <div class="role-edit-card permission-card">

            <div class="role-edit-card-header">

                <div class="role-edit-icon permission-icon">

                    <svg
                        width="22"
                        height="22"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                        <path d="M8 9h8"></path>
                        <path d="M8 13h5"></path>
                    </svg>

                </div>

                <div>

                    <h2>
                        Permission untuk Role Ini
                    </h2>

                    <p>
                        Pilih hak akses yang dapat digunakan oleh role ini.
                    </p>

                </div>

            </div>


            <div class="permission-body">

                @foreach ($permissionsByModule as $module => $permissions)

                    <div class="permission-module">

                        <div class="permission-module-title">
                            {{ $module ?? 'Lainnya' }}
                        </div>


                        <div class="permission-grid">

                            @foreach ($permissions as $permission)

                                <div class="permission-item">

                                    <input
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $permission->id }}"
                                        id="perm-{{ $permission->id }}"
                                        @checked(in_array($permission->id, old('permissions', $assignedPermissionIds)))
                                    >

                                    <label for="perm-{{ $permission->id }}">

                                        <span class="permission-check">

                                            <svg
                                                width="14"
                                                height="14"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="3"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>

                                        </span>

                                        <span class="permission-text">

                                            <strong>
                                                {{ $permission->name }}
                                            </strong>

                                            <code>
                                                {{ $permission->slug }}
                                            </code>

                                        </span>

                                    </label>

                                </div>

                            @endforeach

                        </div>

                    </div>

                @endforeach

            </div>

        </div>


        {{-- ACTION --}}
        <div class="role-form-actions">

            <button
                type="submit"
                class="save-role-button"
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
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>

                Simpan Perubahan

            </button>


            <a
                href="{{ route('admin.roles.index') }}"
                class="cancel-role-button"
            >
                Batal
            </a>

        </div>

    </form>

</div>

@endsection