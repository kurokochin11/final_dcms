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

@section('title', 'Patient Medical History Records')
<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">{{ __('Patient Medical History Records') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- ================= PATIENT TABLE ================= -->
           <div class="table-responsive">
              <table id="myTable" class="table table-striped table-bordered table-hover align-middle">
>
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th>Patient No.</th>
                            <th>Patient Name</th>
                            <th>Email</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($patients as $index => $patient)
                            <tr class="border-t">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $patient->first_name }} {{ $patient->last_name }}</td>
                                <td>{{ $patient->email ?? 'N/A' }}</td>
                                <td class="text-center space-x-2">

                                    <x-button class="btn btn-primary btn-xs"  onclick="openModal('view', '{{ $patient->id }}')">
                                        <i class="fas fa-eye"></i>
                                    </x-button>

                                    @if($patient->latestMedicalSession)
                                        <x-button class="btn btn-warning btn-xs" onclick="openModal('edit', '{{ $patient->id }}')">
                                            <i class="fas fa-edit"></i>
                                        </x-button>
                                    
                                    @endif

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">No patients found.</td>
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

<!-- ================= VIEW MODAL ================= -->
@foreach ($patients as $patient)
<div id="viewModal-{{ $patient->id }}"
     class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-5xl p-6 flex flex-col max-h-[90vh]">

      <!-- FILTER -->
<div class="mb-4">
    <label class="font-semibold text-sm">Filter by Medical Date</label>
    <select class="form-select w-64"
        onchange="filterMedicalSessionsByDate({{ $patient->id }}, this.value)">
        <option value="">All Dates</option>

        @foreach(
            $patient->medicalSessions
                ->pluck('created_at')
                ->map(fn($d) => $d->format('Y-m-d'))
                ->unique()
                ->sortDesc() as $date
        )
            <option value="{{ $date }}">
                {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}
            </option>
        @endforeach
    </select>
</div>

        <h3 class="text-lg font-bold mb-4">
            Medical History of {{ $patient->first_name }} {{ $patient->last_name }}
        </h3>

        <div class="flex-1 overflow-y-auto pr-2 space-y-6">

            @forelse($patient->medicalSessions as $session)
                <div class="session-item" data-year="{{ $session->created_at->year }}">

                    <h4 class="font-semibold border-b pb-1 mb-2">
                        Date: {{ $session->created_at->format('F j, Y') }}
                    </h4>

                    @php
                        $idToSet = function($id) {
                            if ($id >= 1 && $id <= 11) return 'A';
                            if ($id >= 12 && $id <= 18) return 'B';
                            if ($id >= 19 && $id <= 24) return 'C';
                            if ($id >= 25 && $id <= 28) return 'D';
                            if ($id >= 29 && $id <= 34) return 'E';
                            if ($id == 35) return 'F';
                            if ($id == 36) return 'G';
                            if ($id >= 37 && $id <= 40) return 'H';
                            return 'Uncategorized';
                        };

                        $groupedAnswers = $session->medicalAnswers->groupBy(
                            fn($ans) => $ans->question->question_set
                                ?? $idToSet($ans->medical_question_id)
                        );
                    @endphp

                    @foreach($groupedAnswers as $set => $answers)
                        <section class="mb-6">
                            <h5 class="font-semibold mb-2">Section {{ $set }}</h5>

                            <table class="w-full border rounded">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="text-left px-3 py-2 w-2/3">Question</th>
                                        <th class="text-left px-3 py-2">Answer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($answers as $answer)
                                        <tr class="border-t">
                                            <td class="px-3 py-2">
                                                {{ $answer->question->question_text }}
                                            </td>
                                            <td class="px-3 py-2">
                                                {{ $answer->answer_value ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </section>
                    @endforeach
                </div>
            @empty
                <p>No medical history recorded.</p>
            @endforelse

        </div>

        <div class="mt-4 text-right border-t pt-4">
            <x-button class="btn btn-black btn-xs" onclick="closeModal('view', '{{ $patient->id }}')"
                class="px-4 py-2 bg-gray-600 text-white rounded">
                Close
            </x-button>
        </div>
    </div>
</div>
@endforeach

<!-- ================= EDIT MODAL (LATEST SESSION ONLY) ================= -->
@foreach ($patients as $patient)
@if ($patient->latestMedicalSession)
<div id="editModal-{{ $patient->id }}"
     class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-5xl p-6">

        <h3 class="text-lg font-bold mb-6">
            Edit Medical History – {{ $patient->first_name }} {{ $patient->last_name }}
        </h3>

        @php
            $groupedAnswers = $patient->latestMedicalSession
                ->medicalAnswers
                ->groupBy(fn($ans) =>
                    $ans->question->question_set
                    ?? $idToSet($ans->medical_question_id)
                );
        @endphp

        <form method="POST" action="{{ route('medical-history.update', $patient->id) }}">
    @csrf
    @method('PUT')
            <div class="max-h-[65vh] overflow-y-auto space-y-8 pr-2">
                @foreach ($groupedAnswers as $set => $answers)
                    <section>
                        <h4 class="font-semibold mb-2">Section {{ $set }}</h4>

                        <table class="w-full border rounded">
                            @foreach ($answers as $answer)
                                <tr class="border-t">
                                    <td class="px-3 py-2 w-2/3">
                                        {{ $answer->question->question_text }}
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="text"
                                            name="medical_questions[{{ $answer->medical_question_id }}]"
                                            value="{{ $answer->answer_value }}"
                                            class="w-full border rounded p-2">
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </section>
                @endforeach
            </div>

            <div class="mt-6 flex justify-end space-x-2">
                <x-button class="btn btn-black btn-xs" type="button" onclick="closeModal('edit', '{{ $patient->id }}')">
                    Cancel
                </x-button>

                <x-button class="btn btn-success btn-xs" type="submit">
                    Save Changes
                </x-button>
            </div>
        </form>
    </div>
</div>
@endif
@endforeach

<!-- ================= JS ================= -->
<script>
function openModal(type, id) {
    document.getElementById(`${type}Modal-${id}`).classList.remove('hidden');
}
function closeModal(type, id) {
    document.getElementById(`${type}Modal-${id}`).classList.add('hidden');
}
function filterMedicalSessionsByYear(patientId, year) {
    const modal = document.getElementById(`viewModal-${patientId}`);
    modal.querySelectorAll('.session-item').forEach(item => {
        item.style.display = (!year || item.dataset.year == year)
            ? 'block'
            : 'none';
    });
}
</script>

</x-app-layout>
