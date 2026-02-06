<dialog id="delete-dialog" class="rounded-lg p-0 backdrop:bg-black/50">
    <form id="delete-form" class="bg-white rounded-lg shadow-xl p-6 w-96">
        @csrf

        <input type="hidden" id="delete-post-id">

        <h3 class="text-lg font-semibold text-gray-900">
            Delete Post
        </h3>

        <p class="mt-2 text-sm text-gray-500">
            Are you sure you want to delete
            <strong id="delete-post-title"></strong>?
        </p>

        <div class="mt-6 flex justify-end gap-2">
            <button type="button" id="cancel-delete" class="px-4 py-2 rounded-md bg-gray-100">
                Cancel
            </button>
            <button type="submit" class="px-4 py-2 rounded-md bg-red-600 text-white">
                Delete
            </button>
        </div>
    </form>
</dialog>
