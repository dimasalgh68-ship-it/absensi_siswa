<x-admin-layout>
    <x-slot name="title">Mata Pelajaran Guru</x-slot>

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-2 text-gray-800 font-weight-bold">
                    <i class="fas fa-chalkboard-user text-primary mr-2"></i>Mata Pelajaran Guru
                </h1>
                <p class="text-muted mb-0">Kelola mata pelajaran yang diajarkan oleh setiap guru</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-2xl">
                    <div class="card-body p-4">
                        @livewire('admin.teacher-subject-component')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
