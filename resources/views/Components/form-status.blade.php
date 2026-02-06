   <dialog id="status-dialog" class="rounded-lg p-0 backdrop:bg-black/50">
       <form id="status-form" class="bg-white rounded-lg shadow-xl p-6 w-96">
           @csrf
           <input type="hidden" id="toggle-status-id">

           <h3 class="text-lg font-semibold text-gray-900">Change Status</h3>

           <p class="mt-2 text-sm text-gray-500">
               Change Post Status with ID:
               <strong id="toggle-status-title"></strong>
           </p>

           <div class="mt-4">
               <select id="new-status"
                   class="block w-full rounded-md border border-gray-300 px-2 py-1 text-sm
                    focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                   <option value="active">Active</option>
                   <option value="inactive">Inactive</option>
               </select>
           </div>
           <div class="mt-6 flex justify-end gap-2">
               <button type="button" id="cancel-status" class="px-4 py-2 rounded-md bg-gray-100">
                   Cancel
               </button>
               <button type="button" id="submit-status" class="px-4 py-2 rounded-md bg-indigo-600 text-white">
                   Save
               </button>
           </div>
       </form>
   </dialog>
