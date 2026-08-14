<x-admin-layout title="Nouvelle antenne">
    <form method="POST" action="{{ route('admin.antennes.store') }}" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        @include('admin.antennes._form')

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.antennes.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:underline">Annuler</a>
            <button type="submit" class="px-4 py-2 bg-epa-red text-white text-sm font-medium rounded-md hover:opacity-90">
                Créer
            </button>
        </div>
    </form>
</x-admin-layout>
