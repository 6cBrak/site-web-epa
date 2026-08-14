<x-admin-layout title="Équipe / Formateurs">
    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.team-members.create') }}"
           class="inline-flex items-center px-4 py-2 bg-epa-red text-white text-sm font-medium rounded-md hover:opacity-90">
            + Nouveau membre
        </a>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        @forelse ($teamMembers as $member)
            <div class="bg-white rounded-lg shadow p-5 text-center">
                @if ($member->photo)
                    <img src="{{ asset('storage/'.$member->photo) }}" alt="" class="h-20 w-20 rounded-full object-cover mx-auto mb-3">
                @else
                    <div class="h-20 w-20 rounded-full bg-gray-100 mx-auto mb-3"></div>
                @endif
                <h3 class="font-semibold">{{ $member->name }}</h3>
                <p class="text-xs text-gray-500 mb-3">{{ $member->role_fr }}</p>
                <div class="flex justify-center gap-3 text-sm">
                    <a href="{{ route('admin.team-members.edit', $member) }}" class="text-epa-blue hover:underline">Modifier</a>
                    <form method="POST" action="{{ route('admin.team-members.destroy', $member) }}"
                          onsubmit="return confirm('Supprimer ce membre ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-epa-red hover:underline">Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-500 py-6">Aucun membre pour l'instant.</p>
        @endforelse
    </div>
</x-admin-layout>
