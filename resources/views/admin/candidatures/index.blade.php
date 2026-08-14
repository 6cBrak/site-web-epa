<x-admin-layout title="Candidatures">
    <form method="GET" class="bg-white rounded-lg shadow p-4 mb-4 flex flex-wrap gap-3 items-end">
        <div>
            <x-input-label for="search" value="Recherche" />
            <x-text-input id="search" name="search" type="text" class="mt-1" value="{{ request('search') }}" placeholder="Nom, prénom, email..." />
        </div>
        <div>
            <x-input-label for="formation_id" value="Formation" />
            <select id="formation_id" name="formation_id" class="mt-1 border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">
                <option value="">Toutes</option>
                @foreach ($formations as $formation)
                    <option value="{{ $formation->id }}" @selected(request('formation_id') == $formation->id)>{{ $formation->title_fr }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <x-input-label for="antenne_id" value="Antenne" />
            <select id="antenne_id" name="antenne_id" class="mt-1 border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">
                <option value="">Toutes</option>
                @foreach ($antennes as $antenne)
                    <option value="{{ $antenne->id }}" @selected(request('antenne_id') == $antenne->id)>{{ $antenne->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <x-input-label for="status" value="Statut" />
            <select id="status" name="status" class="mt-1 border-gray-300 rounded-md shadow-sm focus:border-epa-red focus:ring-epa-red">
                <option value="">Tous</option>
                @foreach (['nouvelle' => 'Nouvelle', 'contactee' => 'Contactée', 'confirmee' => 'Confirmée', 'refusee' => 'Refusée'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-epa-red text-white text-sm font-medium rounded-md hover:opacity-90">Filtrer</button>
        <a href="{{ route('admin.candidatures.export', request()->query()) }}" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md hover:bg-gray-50">
            Exporter CSV
        </a>
    </form>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                <tr>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Candidat</th>
                    <th class="px-4 py-3">Formation</th>
                    <th class="px-4 py-3">Antenne</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @php
                    $statusColors = [
                        'nouvelle' => 'bg-blue-100 text-blue-800',
                        'contactee' => 'bg-yellow-100 text-yellow-800',
                        'confirmee' => 'bg-green-100 text-green-800',
                        'refusee' => 'bg-red-100 text-red-800',
                    ];
                @endphp
                @forelse ($candidatures as $candidature)
                    <tr>
                        <td class="px-4 py-3 text-gray-600">{{ $candidature->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $candidature->first_name }} {{ $candidature->last_name }}</div>
                            <div class="text-xs text-gray-500">{{ $candidature->email }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $candidature->formation->title_fr }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $candidature->antenne->name }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs {{ $statusColors[$candidature->status] }}">
                                {{ ucfirst($candidature->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.candidatures.show', $candidature) }}" class="text-epa-blue hover:underline">Voir</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucune candidature pour l'instant.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $candidatures->links() }}
    </div>
</x-admin-layout>
