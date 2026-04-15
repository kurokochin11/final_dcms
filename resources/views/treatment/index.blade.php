<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Treatments
        </h2>
    </x-slot>

    <div class="p-6">

        <!-- ADD BUTTON -->
        <button onclick="openCreate()"
            class="bg-blue-500 text-white px-4 py-2 rounded">
            + Add Treatment
        </button>

        <br><br>

        <table class="w-full border mt-4">
            <tr class="bg-gray-200">
                <th class="border p-2">Patient</th>
                <th class="border p-2">Plan</th>
                <th class="border p-2">Tooth</th>
                <th class="border p-2">Amount</th>
                <th class="border p-2">Action</th>
            </tr>

            @foreach($treatments as $t)
            <tr>
                <td class="border p-2">
                    {{ $t->patient->last_name }}, {{ $t->patient->first_name }}
                </td>
                <td class="border p-2">{{ $t->treatment_plan }}</td>
                <td class="border p-2">{{ $t->tooth_number }}</td>
                <td class="border p-2">₱{{ $t->amount }}</td>

                <td class="border p-2 space-x-2">

                    <!-- PDF -->
                    <a href="{{ route('treatments.pdf', $t->id) }}"
                        class="bg-green-500 text-white px-2 py-1 rounded">
                        PDF
                    </a>

                    <!-- EDIT -->
                    <button onclick="openEdit({{ $t->id }}, '{{ $t->treatment_plan }}', '{{ $t->tooth_number }}', {{ $t->amount }})"
                        class="bg-yellow-500 text-white px-2 py-1 rounded">
                        Edit
                    </button>

                    <!-- DELETE -->
                    <form action="{{ route('treatments.destroy', $t->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-500 text-white px-2 py-1 rounded">
                            Delete
                        </button>
                    </form>

                </td>
            </tr>
            @endforeach
        </table>
    </div>

    <!-- CREATE MODAL -->
    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded w-96">

            <h2 class="text-lg font-bold mb-2">Add Treatment</h2>

            <form method="POST" action="{{ route('treatments.store') }}">
                @csrf

                <select name="patient_id" class="w-full border p-2 mb-2">
                    @foreach($patients as $p)
                        <option value="{{ $p->id }}">
                            {{ $p->last_name }}, {{ $p->first_name }}
                        </option>
                    @endforeach
                </select>

                <input type="text" name="treatment_plan" placeholder="Plan"
                    class="w-full border p-2 mb-2">

                <input type="text" name="tooth_number" placeholder="Tooth"
                    class="w-full border p-2 mb-2">

                <input type="number" name="amount" placeholder="Amount"
                    class="w-full border p-2 mb-2">

                <button class="bg-blue-500 text-white px-4 py-2 rounded">
                    Save
                </button>
            </form>

            <button onclick="closeCreate()" class="mt-2 text-red-500">Close</button>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded w-96">

            <h2 class="text-lg font-bold mb-2">Edit Treatment</h2>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <input type="text" name="treatment_plan" id="edit_plan"
                    class="w-full border p-2 mb-2">

                <input type="text" name="tooth_number" id="edit_tooth"
                    class="w-full border p-2 mb-2">

                <input type="number" name="amount" id="edit_amount"
                    class="w-full border p-2 mb-2">

                <button class="bg-yellow-500 text-white px-4 py-2 rounded">
                    Update
                </button>
            </form>

            <button onclick="closeEdit()" class="mt-2 text-red-500">Close</button>
        </div>
    </div>

    <!-- JS -->
    <script>
        function openCreate() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        function closeCreate() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function openEdit(id, plan, tooth, amount) {
            document.getElementById('editModal').classList.remove('hidden');

            document.getElementById('edit_plan').value = plan;
            document.getElementById('edit_tooth').value = tooth;
            document.getElementById('edit_amount').value = amount;

            document.getElementById('editForm').action = "/treatments/" + id;
        }

        function closeEdit() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>

</x-app-layout>
