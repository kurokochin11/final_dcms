<!-- ================= CSS ================= -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="../assets/css/plugins.min.css" />
<link rel="stylesheet" href="../assets/css/kaiadmin.min.css" />

<!-- ================= JS ================= -->
<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>

<script>
$(document).ready(function () {
    $('#myTable').DataTable({ responsive: true });
});

function openModal(type, id) {
    const modal = document.getElementById(`${type}Modal-${id}`);
    if (modal) modal.classList.remove('hidden');
}

function closeModal(type, id) {
    const modal = document.getElementById(`${type}Modal-${id}`);
    if (modal) modal.classList.add('hidden');
}
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
<thead>
<tr>
    <th>#</th>
    <th>Patient Name</th>
    <th>Email</th>
    <th class="text-center">Action</th>
</tr>
</thead>
<tbody>
@foreach ($patients as $index => $patient)
<tr>
    <td>{{ $index + 1 }}</td>
    <td>{{ $patient->first_name }} {{ $patient->last_name }}</td>
    <td>{{ $patient->email ?? 'N/A' }}</td>
    <td class="text-center">
        <x-button class="btn btn-primary btn-xs"
            onclick="openModal('view', '{{ $patient->id }}')">
            <i class="fas fa-eye"></i>
        </x-button>
    </td>
</tr>
@endforeach
</tbody>
</table>
</div>

</div>
</div>

<!-- ================= VIEW MODAL ================= -->
@foreach ($patients as $patient)
<div id="viewModal-{{ $patient->id }}"
     class="hidden fixed inset-0  flex items-center justify-center z-50">

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-5xl p-6 flex flex-col max-h-[90vh]">

<h3 class="text-lg font-bold mb-4">
    Medical History of {{ $patient->first_name }} {{ $patient->last_name }}
</h3>

<!-- DATE FILTER -->
<div class="mb-3 flex items-center gap-2">
    <label class="fw-bold">Filter by Medical Date:</label>
    <select class="form-select form-select-sm w-auto medical-date-filter">
        <option value="all">All Dates</option>

        @php
            // Get unique dates from medicalSessions
            $dates = $patient->medicalSessions
                ->map(fn($session) => $session->created_at->format('Y-m-d'))
                ->unique();
        @endphp

        @foreach ($dates as $date)
            <option value="{{ $date }}">
                {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}
            </option>
        @endforeach
    </select>
</div>


<div class="flex-1 overflow-y-auto space-y-6">

@foreach ($patient->medicalSessions as $session)

<div class="border rounded p-4 medical-session"
     data-medical-date="{{ $session->created_at->format('Y-m-d') }}">

<div class="flex justify-between items-center mb-2 border-b pb-1">
    <h4 class="font-semibold">
        Medical Date: {{ $session->created_at->format('F j, Y') }}
    </h4>

    <x-button class="btn btn-warning btn-xs"
        onclick="openModal('editSession', '{{ $session->id }}')">
        <i class="fas fa-edit"></i> Edit
    </x-button>
</div>

@php
$groupedAnswers = $session->responses->groupBy(
    fn($ans) => $ans->question->question_set ?? 'Uncategorized'
);
@endphp

@foreach ($groupedAnswers as $set => $answers)
<table class="table table-bordered mb-3">
<thead>
<tr>
    <th width="70%">Question</th>
    <th>Answer</th>
</tr>
</thead>
<tbody>
@foreach ($answers as $answer)
<tr>
    <td>{{ $answer->question->question_text }}</td>
    <td>{{ $answer->answer_value ?? '-' }}</td>
</tr>
@endforeach
</tbody>
</table>
@endforeach

</div>
@endforeach

</div>
 
<div class="mt-4 text-right">
   <button type="button" 
            class="btn btn-danger btn-medium mr-2" 
            onclick="window.open('{{ route('medical-history.download-pdf', $patient->id) }}', '_blank')">
        <i class="fas fa-file-pdf"></i> Download PDF
    </button>
    
<button class="btn btn-dark btn-medium"
    onclick="closeModal('view', '{{ $patient->id }}')">
    Close
</button>
</div>

</div>
</div>
@endforeach

<!-- ================= MEDICAL HISTORY EDIT SESSION MODAL ================= -->
@foreach ($patients as $patient)
@foreach ($patient->medicalSessions as $session)

<div id="editSessionModal-{{ $session->id }}"
     class="hidden fixed inset-0 bg-gray-900/30 backdrop-blur-sm flex items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-5xl overflow-hidden">

        <!-- HEADER -->
        <div class="bg-primary text-white px-6 py-3 flex justify-between items-center">
            <h3 class="text-lg font-bold mb-0">
                Edit Medical History —
                <span class="text-lg font-bold">
                    {{ $patient->first_name }} {{ $patient->last_name }}
                </span>
            </h3>

            <button type="button"
                onclick="closeModal('editSession', '{{ $session->id }}')"
                class="text-white text-2xl leading-none hover:opacity-75">
                &times;
            </button>
        </div>

        <!-- BODY -->
        <div class="p-6">

            @php
            $groupedAnswers = $session->responses->groupBy(
                fn($ans) => $ans->question->question_set ?? 'Uncategorized'
            );
            @endphp

            <form method="POST"
                  action="{{ route('medical-history.update-session', $session->id) }}">
                @csrf
                @method('PUT')

                <div class="max-h-[65vh] overflow-y-auto space-y-6">

                    @foreach ($groupedAnswers as $set => $answers)
                    <section>
                        <h4 class="font-semibold mb-2">Section {{ $set }}</h4>

                        <table class="table table-bordered">
                            @foreach ($answers as $answer)
                            <tr>
                                <td width="70%">
                                    {{ $answer->question->question_text }}
                                </td>
                                <td>
                                    <input type="text"
                                           name="medical_questions[{{ $answer->medical_question_id }}]"
                                           value="{{ $answer->answer_value }}"
                                           class="form-control">
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </section>
                    @endforeach

                </div>

                <!-- FOOTER -->
                <div class="mt-4 flex justify-end gap-2">
                    <x-button class="btn btn-black btn-sm"
                        type="button"
                        onclick="closeModal('editSession', '{{ $session->id }}')">
                        Cancel
                    </x-button>

                    <button class="btn btn-primary btn-sm" type="submit">
                        Update
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endforeach
@endforeach


<!-- ================= FILTER SCRIPT ================= -->
<script>
$(document).on('change', '.medical-date-filter', function () {
    const selectedDate = $(this).val();
    const modal = $(this).closest('.bg-white');

    modal.find('.medical-session').each(function () {
        const sessionDate = $(this).data('medical-date');
        if (selectedDate === 'all' || sessionDate === selectedDate) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });

    modal.find('.overflow-y-auto').animate({ scrollTop: 0 }, 300);
});
</script>

</x-app-layout>
