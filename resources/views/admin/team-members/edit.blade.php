<x-admin-layout title="Modifier le membre">
    <form method="POST" action="{{ route('admin.team-members.update', $teamMember) }}" enctype="multipart/form-data"
          class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        @method('PUT')
        @include('admin.team-members._form')

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.team-members.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:underline">Annuler</a>
            <button type="submit" class="px-4 py-2 bg-epa-red text-white text-sm font-medium rounded-md hover:opacity-90">
                Enregistrer
            </button>
        </div>
    </form>
</x-admin-layout>
