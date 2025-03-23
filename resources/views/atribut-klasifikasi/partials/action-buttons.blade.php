<div class="flex gap-2 justify-center">
    <button type="button" onclick="showDetailModal({{ json_encode($row) }})" class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
        Detail
    </button>
    <a href="{{ route('data-masyarakat.edit', $row->id) }}" class="px-3 py-1 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600">
        Edit
    </a>
    <button type="button" onclick="showDeleteModal({{ $row->id }})" class="px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600">
        Hapus
    </button>
</div>
