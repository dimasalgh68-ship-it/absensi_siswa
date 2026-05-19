<div>
  <div class="mb-4 flex-col items-center gap-5 sm:flex-row md:flex md:justify-between lg:mr-4">
    <h3 class="mb-4 text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200 md:mb-0">
      Data Mata Pelajaran
    </h3>
    <x-button wire:click="showCreating">
      <x-heroicon-o-plus class="mr-2 h-4 w-4" /> Tambah Mata Pelajaran
    </x-button>
  </div>
  <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
    <thead class="bg-gray-50 dark:bg-gray-900">
      <tr>
        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
          Kode
        </th>
        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
          Nama Mata Pelajaran
        </th>
        <th scope="col" class="relative px-6 py-3">
          <span class="sr-only">Actions</span>
        </th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
      @foreach ($subjects as $subject)
        <tr>
          <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
            {{ $subject->code }}
          </td>
          <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
            {{ $subject->name }}
          </td>
          <td class="relative flex justify-end gap-2 px-6 py-4">
            <x-button wire:click="edit({{ $subject->id }})">
              Edit
            </x-button>
            <x-danger-button wire:click="confirmDeletion({{ $subject->id }}, '{{ $subject->name }}')">
              Delete
            </x-danger-button>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <x-confirmation-modal wire:model="confirmingDeletion">
    <x-slot name="title">
      Hapus Mata Pelajaran
    </x-slot>

    <x-slot name="content">
      Apakah Anda yakin ingin menghapus <b>{{ $deleteName }}</b>?
    </x-slot>

    <x-slot name="footer">
      <x-secondary-button wire:click="$toggle('confirmingDeletion')" wire:loading.attr="disabled">
        {{ __('Cancel') }}
      </x-secondary-button>

      <x-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
        {{ __('Confirm') }}
      </x-danger-button>
    </x-slot>
  </x-confirmation-modal>

  <x-dialog-modal wire:model="creating">
    <x-slot name="title">
      Mata Pelajaran Baru
    </x-slot>

    <form wire:submit="create">
      <x-slot name="content">
        <x-label for="code">Kode Mata Pelajaran</x-label>
        <x-input id="code" class="mt-1 block w-full" type="text" wire:model="code" placeholder="Contoh: MTK, IPA, BHS" />
        @error('code')
          <x-input-error for="code" class="mt-2" message="{{ $message }}" />
        @enderror

        <x-label for="name" class="mt-4">Nama Mata Pelajaran</x-label>
        <x-input id="name" class="mt-1 block w-full" type="text" wire:model="name" placeholder="Contoh: Matematika" />
        @error('name')
          <x-input-error for="name" class="mt-2" message="{{ $message }}" />
        @enderror
      </x-slot>

      <x-slot name="footer">
        <x-secondary-button wire:click="$toggle('creating')" wire:loading.attr="disabled">
          {{ __('Cancel') }}
        </x-secondary-button>

        <x-button class="ml-2" wire:click="create" wire:loading.attr="disabled">
          {{ __('Confirm') }}
        </x-button>
      </x-slot>
    </form>
  </x-dialog-modal>

  <x-dialog-modal wire:model="editing">
    <x-slot name="title">
      Edit Mata Pelajaran
    </x-slot>

    <form wire:submit.prevent="update">
      <x-slot name="content">
        <x-label for="code">Kode Mata Pelajaran</x-label>
        <x-input id="code" class="mt-1 block w-full" type="text" wire:model="code" />
        @error('code')
          <x-input-error for="code" class="mt-2" message="{{ $message }}" />
        @enderror

        <x-label for="name" class="mt-4">Nama Mata Pelajaran</x-label>
        <x-input id="name" class="mt-1 block w-full" type="text" wire:model="name" />
        @error('name')
          <x-input-error for="name" class="mt-2" message="{{ $message }}" />
        @enderror
      </x-slot>

      <x-slot name="footer">
        <x-secondary-button wire:click="$toggle('editing')" wire:loading.attr="disabled">
          {{ __('Cancel') }}
        </x-secondary-button>

        <x-button class="ml-2" wire:click="update" wire:loading.attr="disabled">
          {{ __('Confirm') }}
        </x-button>
      </x-slot>
    </form>
  </x-dialog-modal>
</div>
