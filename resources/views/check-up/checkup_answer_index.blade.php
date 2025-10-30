<!-- KaiAdmin Main CSS (includes Bootstrap) -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
 <link rel="stylesheet" href="../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../assets/css/kaiadmin.min.css" />
  <!-- JS -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script>
$(document).ready(function () {
    $('#myTable').DataTable({
        responsive: true
    });
});
</script>
@section('title', 'Patient Check-up Records')
<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">
            {{ __('Patient Check-up Records') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table  id="myTable"  class="sub-item">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="py-3 px-4 text-left text-gray-800 dark:text-gray-100">Patient No.</th>
                            <th class="py-3 px-4 text-left text-gray-800 dark:text-gray-100">Patient Name</th>
                            <th class="py-3 px-4 text-left text-gray-800 dark:text-gray-100">Email</th>
                            <th class="py-3 px-4 text-center text-gray-800 dark:text-gray-100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($patients as $index => $patient)
                            <tr class="border-t border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900">
                                <td class="py-3 px-4">{{ $index + 1 }}</td>
                                <td class="py-3 px-4">{{ $patient->first_name }} {{ $patient->last_name }}</td>
                                <td class="py-3 px-4">{{ $patient->email ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-center space-x-2">
                                    <button onclick="openModal('view', '{{ $patient->id }}')" 
                                        class="px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs">
                                        View
                                    </button>
                                    <button onclick="openModal('edit', '{{ $patient->id }}')" 
                                        class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs">
                                        Edit
                                    </button>
                                   
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-600 dark:text-gray-300">
                                    No patients found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-4">
                    {{ $patients->links() }}
                </div>
            </div>
        </div>
    </div>

 <!-- ========== VIEW MODAL  ========== -->
@foreach ($patients as $patient)
    <div id="viewModal-{{ $patient->id }}"
         class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-5xl p-6 relative flex flex-col max-h-[90vh]">

            <!-- Header -->
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">
                check-up results of {{ $patient->first_name }} {{ $patient->last_name }}
            </h3>

            @php
                $idToSet = function($id) {
                    $id = (int) $id;
                   if ($id >= 41 && $id <= 57) return 'I'; 
                    return 'Uncategorized';
                };

                $answersCollection = $patient->checkupAnswers ?? collect();
                $groupedAnswers = $answersCollection->groupBy(function($ans) use ($idToSet) {
                    return $ans->question->question_set ?? $idToSet($ans->checkup_question_id ?? 0);
                });
            @endphp

            <!-- Scrollable answers area (includes signature table at the end) -->
            <div class="flex-1 overflow-y-auto pr-2">
                @if ($groupedAnswers->isEmpty())
                    <p class="text-gray-600 dark:text-gray-300">No Results Recorded.</p>
                @else
                    <div class="space-y-10">
                        @foreach ($groupedAnswers as $set => $answers)
                            <section>
                                <h4 class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-3 border-b border-gray-300 dark:border-gray-700 pb-1">
                                    Section {{ $set }}
                                </h4>
                                <div class="overflow-x-auto">
                                    <table class="w-full border border-gray-200 dark:border-gray-700 rounded-lg">
                                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                                            <tr>
                                                <th class="px-4 py-2 text-left w-2/3">Question</th>
                                                <th class="px-4 py-2 text-left">Answer</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach ($answers as $answer)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                                    <td class="px-4 py-2 align-top text-sm font-medium text-gray-800 dark:text-gray-100">
                                                        {{ $answer->question->question_text ?? 'Unknown Question' }}
                                                    </td>
                                                    <td class="px-4 py-2 align-top text-sm text-gray-700 dark:text-gray-300">
                                                        {{ $answer->answer_value ?? '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        @endforeach

                        <!-- ===== Signature joins the table here ===== -->
                        <div class="overflow-x-auto">
                            <table class="w-full border border-gray-200 dark:border-gray-700 rounded-lg">
                                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left w-2/3">Patient's Signature</th>
                                        <th class="px-4 py-2 text-left">Signed / Date</th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white dark:bg-gray-800">
                                    <tr>
                                        <td class="px-4 py-4 align-top text-sm font-medium text-gray-800 dark:text-gray-100">
                                            {{-- if you want to show label only, leave as-is --}}
                                        </td>

                                        <td class="px-4 py-4 align-top text-sm text-gray-700 dark:text-gray-300">
                                            @if (!empty($patient->signature_path))
                                                <div class="flex items-center space-x-4">
                                                    <div class="w-48">
                                                        <img src="{{ asset($patient->signature_path) }}" alt="Patient signature"
                                                             class="max-h-20 object-contain border border-gray-200 dark:border-gray-600 rounded" />
                                                    </div>
                                                    <div>
                                                        <div class="text-sm">
                                                            Date: {{ $patient->signature_date ? \Carbon\Carbon::parse($patient->signature_date)->format('F j, Y') : '________________' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="w-full">
                                                    <div class="w-full border-b border-gray-400 dark:border-gray-600 h-10"></div>
                                                    <div class="mt-2 text-sm">
                                                        Date: ____________________________
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- ===== end signature table ===== -->

                    </div>
                @endif
            </div>

            <!-- Footer: signature already inside scroll area, footer pinned -->
            <div class="mt-4 text-right border-t border-gray-300 dark:border-gray-700 pt-4">
                <button onclick="closeModal('view', '{{ $patient->id }}')"
                        class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                    Close
                </button>
            </div>
        </div>
    </div>
@endforeach

   <!-- ========== EDIT MODAL ========== -->
@foreach ($patients as $patient)
    <div id="editModal-{{ $patient->id }}" 
         class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-5xl p-6 relative">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6">
                Edit Answers of this patient: {{ $patient->first_name }} {{ $patient->last_name }}
            </h3>

            @php
                $idToSet = function($id) {
                    $id = (int) $id;
                    if ($id >= 1  && $id <= 11) return 'A';
                    if ($id >= 12 && $id <= 18) return 'B';
                    if ($id >= 19 && $id <= 24) return 'C';
                    if ($id >= 25 && $id <= 28) return 'D';
                    if ($id >= 29 && $id <= 34) return 'E';
                    if ($id == 35) return 'F';
                    if ($id == 36) return 'G';
                    if ($id >= 37 && $id <= 40) return 'H';
                    return 'Uncategorized';
                };

                // Group answers by their section
                $groupedAnswers = $patient->checkupAnswers->groupBy(function($ans) use ($idToSet) {
                    return $ans->question->question_set ?? $idToSet($ans->checkup_question_id);
                });
            @endphp

            <form method="POST" action="{{ route('check-up.store', $patient->id) }}">
                @csrf

                @if ($groupedAnswers->isEmpty())
                    <p class="text-gray-600 dark:text-gray-300">No medical answers available to edit.</p>
                @else
                    <div class="max-h-[65vh] overflow-y-auto space-y-10 pr-2">
                        @foreach ($groupedAnswers as $set => $answers)
                            <section>
                                <!-- Section Header -->
                                <h4 class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-3 border-b border-gray-300 dark:border-gray-700 pb-1">
                                    Section {{ $set }}
                                </h4>

                                <!-- Table Layout -->
                                <div class="overflow-x-auto">
                                    <table class="w-full border border-gray-200 dark:border-gray-700 rounded-lg">
                                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                                            <tr>
                                                <th class="px-4 py-2 text-left w-2/3">Question</th>
                                                <th class="px-4 py-2 text-left">Answer</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach ($answers as $answer)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                                    <td class="px-4 py-2 align-top text-sm font-medium text-gray-800 dark:text-gray-100">
                                                        {{ $answer->question->question_text ?? 'Unknown Question' }}
                                                    </td>
                                                    <td class="px-4 py-2 align-top">
                                                        <input type="text"
                                                            name="medical_questions[{{ $answer->medical_question_id }}]"
                                                            value="{{ old('medical_questions.' . $answer->medical_question_id, $answer->answer_value) }}"
                                                            class="w-full border border-gray-300 dark:border-gray-700 rounded-lg p-2 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 text-sm"
                                                        />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        @endforeach
                    </div>
                @endif

                <!-- Buttons -->
                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('edit', '{{ $patient->id }}')" 
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endforeach

   
    <!-- ========== JS HANDLERS ========== -->
    <script>
        function openModal(type, id) {
            document.getElementById(`${type}Modal-${id}`).classList.remove('hidden');
        }

        function closeModal(type, id) {
            document.getElementById(`${type}Modal-${id}`).classList.add('hidden');
        }
    </script>
</x-app-layout>
