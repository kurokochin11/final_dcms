<x-app-layout>
    <div class="py-6" x-data="{ 
        selectedTool: 'check', 
        teeth: {{ json_encode($currentSession->tooth_data ?? (object)[]) }} 
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-12 gap-6">
                
                <div class="col-span-3 space-y-4">
                    <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                        <h3 class="font-black text-blue-900 uppercase text-xs tracking-widest mb-4">Session History</h3>
                        <a href="{{ route('dental-chart.create', $patient->id) }}" class="block text-center bg-blue-50 text-blue-700 border border-blue-200 py-2 rounded mb-4 text-xs font-bold hover:bg-blue-100 transition">
                            + NEW SESSION
                        </a>
                        <div class="space-y-2 max-h-[500px] overflow-y-auto">
                            @foreach($patient->dentalCharts as $session)
                                <a href="{{ route('dental-chart.show', [$patient->id, $session->id]) }}" 
                                   class="block p-2 text-xs rounded border {{ isset($currentSession) && $currentSession->id == $session->id ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                                    <strong>{{ $session->created_at->format('M d, Y') }}</strong><br>
                                    <span class="opacity-75">{{ $session->nature_of_treatment ?? 'Check-up' }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-span-9 bg-white p-8 rounded-lg shadow border border-gray-200">
                    <div class="border-b-2 border-blue-900 pb-4 mb-6">
                        <div class="flex justify-between items-start">
                            <h2 class="text-2xl font-black text-blue-900 uppercase tracking-widest">Patient's Examination Record Chart</h2>
                            @if(isset($currentSession))
                                <span class="bg-blue-100 text-blue-800 text-[10px] px-2 py-1 rounded font-bold">VIEWING SESSION: {{ $currentSession->created_at->format('m/d/Y') }}</span>
                            @else
                                <span class="bg-green-100 text-green-800 text-[10px] px-2 py-1 rounded font-bold">NEW SESSION</span>
                            @endif
                        </div>
                        
                       <div class="grid grid-cols-4 gap-4 mt-6 text-[11px] uppercase">
        <div class="border-b border-gray-300 pb-1 col-span-2">
            <strong class="text-blue-800">Full Name:</strong> 
            {{ $patient->last_name }}, {{ $patient->first_name }} {{ $patient->middle_name }}
        </div>
        
        <div class="border-b border-gray-300 pb-1">
            <strong class="text-blue-800">Date:</strong> 
            {{ now()->format('m/d/Y') }}
        </div>
    </div>

    <div class="grid grid-cols-4 gap-4 mt-3 text-[11px] uppercase">
        <div class="border-b border-gray-300 pb-1">
            <strong class="text-blue-800">Birthdate:</strong> 
            {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') }}
        </div>
        <div class="border-b border-gray-300 pb-1">
            <strong class="text-blue-800">Age:</strong> 
            {{ $patient->age }}
        </div>
        <div class="border-b border-gray-300 pb-1">
            <strong class="text-blue-800">Sex:</strong> 
            {{ $patient->sex }}
        </div>
        <div class="border-b border-gray-300 pb-1">
            <strong class="text-blue-800">Status:</strong> 
            {{ $patient->civil_status }}
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 mt-3 text-[11px] uppercase">
        <div class="border-b border-gray-300 pb-1">
            <strong class="text-blue-800">Occupation:</strong> 
            {{ $patient->occupation ?? 'None' }}
        </div>
        <div class="border-b border-gray-300 pb-1">
            <strong class="text-blue-800">Mobile:</strong> 
            {{ $patient->mobile_number ?? 'N/A' }}
        </div>
        <div class="border-b border-gray-300 pb-1">
            <strong class="text-blue-800">Email:</strong> 
            <span class="lowercase">{{ $patient->email ?? 'N/A' }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 mt-3 text-[11px] uppercase">
        <div class="border-b border-gray-300 pb-1">
            <strong class="text-blue-800">Home Address:</strong> 
            {{ $patient->address }}, {{ $patient->city }}, {{ $patient->province }} {{ $patient->zip_code }}
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mt-3 text-[11px] uppercase">
        <div class="border-b border-gray-300 pb-1">
            <strong class="text-blue-800">In Case of Emergency:</strong> 
            {{ $patient->emergencyContact->full_name ?? 'N/A' }}
        </div>
        <div class="border-b border-gray-300 pb-1">
            <strong class="text-blue-800">Contact No:</strong> 
            {{ $patient->emergencyContact->mobile_number ?? 'N/A' }}
        </div>
    </div>
</div>

                    <form action="{{ isset($currentSession) ? route('dental-chart.update', $currentSession->id) : route('dental-chart.store', $patient->id) }}" method="POST">
                        @csrf
                        @if(isset($currentSession)) @method('PUT') @endif
                        
                        <div class="flex flex-row gap-8">
                            <div class="w-1/3 space-y-3 bg-blue-50/50 p-5 rounded-lg border border-blue-100">
                                <h4 class="font-bold border-b border-blue-200 pb-1 text-blue-800 uppercase text-xs tracking-tighter">Clinical Data</h4>
                                @php
                                    $fields = [
                                        'occlusion' => 'Occlusion',
                                        'periodontal_condition' => 'Periodontal Condition',
                                        'oral_hygiene' => 'Oral Hygiene',
                                        'abnormalities' => 'Abnormalities',
                                        'general_condition' => 'General Condition',
                                        'nature_of_treatment' => 'Nature of Treatment',
                                        'allergies' => 'Allergies',
                                        'blood_pressure' => 'Blood Pressure',
                                        'drugs_taken' => 'Drugs Being Taken'
                                    ];
                                @endphp
                                @foreach($fields as $key => $label)
                                    <div>
                                        <label class="block text-[10px] uppercase font-black text-gray-500">{{ $label }}</label>
                                        <input type="text" name="{{ $key }}" value="{{ $currentSession->$key ?? '' }}" 
                                               class="w-full text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 py-1">
                                    </div>
                                @endforeach
                            </div>

                            <div class="w-2/3">
                                @php
                                    $upperAdult = [18,17,16,15,14,13,12,11,21,22,23,24,25,26,27,28];
                                    $lowerAdult = [48,47,46,45,44,43,42,41,31,32,33,34,35,36,37,38];
                                    $upperDeciduous = [55,54,53,52,51,61,62,63,64,65];
                                    $lowerDeciduous = [85,84,83,82,81,71,72,73,74,75];
                                @endphp

                                <div class="mb-6 flex items-center justify-between bg-white border p-3 rounded-lg shadow-sm">
                                    <div class="flex gap-4 items-center">
                                        <span class="font-bold text-gray-700 text-sm">Select Tool:</span>
                                        <button type="button" @click="selectedTool = 'check'" :class="selectedTool === 'check' ? 'bg-green-600 text-white' : 'bg-gray-100'" class="px-4 py-1 rounded-full text-xs font-bold transition">✓ CHECK</button>
                                        <button type="button" @click="selectedTool = 'wrong'" :class="selectedTool === 'wrong' ? 'bg-red-600 text-white' : 'bg-gray-100'" class="px-4 py-1 rounded-full text-xs font-bold transition">✗ WRONG</button>
                                        <button type="button" @click="selectedTool = ''" :class="selectedTool === '' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600'" class="px-4 py-1 rounded-full text-xs font-bold transition"> CLEAR</button>
                                     </div>
                                    </div>

                                <h5 class="text-center font-bold text-gray-400 uppercase text-xs mb-2 tracking-widest">Permanent Teeth</h5>
                                <div class="bg-white border rounded p-4 mb-8 overflow-x-auto">
                                    <div class="min-w-[780px]">
                                        <div class="flex justify-center gap-1 mb-12">
                                            @foreach($upperAdult as $num)
                                                <div @click="teeth['{{ $num }}'] = selectedTool" class="w-10 flex flex-col items-center group cursor-pointer">
                                                    <span class="text-[9px] font-bold text-gray-400 group-hover:text-blue-600">{{ $num }}</span>
                                                    <div class="w-9 h-12 border-2 rounded-t-lg flex items-center justify-center relative bg-gray-50 group-hover:bg-blue-50" :class="teeth['{{ $num }}'] ? 'border-blue-400' : 'border-gray-200'">
                                                        <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12,2C10,2 7,3 6,5C5,7 5,10 6,14C7,18 9,22 12,22C15,22 17,18 18,14C19,10 19,7 18,5C17,3 14,2 12,2Z"/></svg>
                                                        <div class="absolute inset-0 flex items-center justify-center">
                                                            <template x-if="teeth['{{ $num }}'] === 'check'"><span class="text-green-600 text-xl font-black">✓</span></template>
                                                            <template x-if="teeth['{{ $num }}'] === 'wrong'"><span class="text-red-600 text-xl font-black">✗</span></template>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="flex justify-center gap-1">
                                            @foreach($lowerAdult as $num)
                                                <div @click="teeth['{{ $num }}'] = selectedTool" class="w-10 flex flex-col items-center group cursor-pointer">
                                                    <div class="w-9 h-12 border-2 rounded-b-lg flex items-center justify-center relative bg-gray-50 group-hover:bg-blue-50" :class="teeth['{{ $num }}'] ? 'border-blue-400' : 'border-gray-200'">
                                                        <svg class="w-6 h-6 text-gray-300 rotate-180" fill="currentColor" viewBox="0 0 24 24"><path d="M12,2C10,2 7,3 6,5C5,7 5,10 6,14C7,18 9,22 12,22C15,22 17,18 18,14C19,10 19,7 18,5C17,3 14,2 12,2Z"/></svg>
                                                        <div class="absolute inset-0 flex items-center justify-center">
                                                            <template x-if="teeth['{{ $num }}'] === 'check'"><span class="text-green-600 text-xl font-black">✓</span></template>
                                                            <template x-if="teeth['{{ $num }}'] === 'wrong'"><span class="text-red-600 text-xl font-black">✗</span></template>
                                                        </div>
                                                    </div>
                                                    <span class="text-[9px] font-bold text-gray-400 group-hover:text-blue-600">{{ $num }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <h5 class="text-center font-bold text-gray-400 uppercase text-[10px] mt-10 mb-2 tracking-widest">Deciduous Teeth</h5>
                                <div class="bg-white border rounded p-4 mb-8">
                                    <div class="flex justify-center gap-1 mb-10">
                                        @foreach($upperDeciduous as $num)
                                            <div @click="teeth['{{ $num }}'] = selectedTool" class="w-10 flex flex-col items-center group cursor-pointer">
                                                <span class="text-[9px] font-bold text-gray-400 group-hover:text-blue-600">{{ $num }}</span>
                                                <div class="w-8 h-10 border-2 rounded-t-lg flex items-center justify-center relative bg-gray-50 group-hover:bg-blue-50" :class="teeth['{{ $num }}'] ? 'border-blue-400' : 'border-gray-200'">
                                                    <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12,2C10,2 7,3 6,5C5,7 5,10 6,14C7,18 9,22 12,22C15,22 17,18 18,14C19,10 19,7 18,5C17,3 14,2 12,2Z"/></svg>
                                                    <div class="absolute inset-0 flex items-center justify-center">
                                                        <template x-if="teeth['{{ $num }}'] === 'check'"><span class="text-green-600 text-lg font-black">✓</span></template>
                                                        <template x-if="teeth['{{ $num }}'] === 'wrong'"><span class="text-red-600 text-lg font-black">✗</span></template>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="flex justify-center gap-1">
                                        @foreach($lowerDeciduous as $num)
                                            <div @click="teeth['{{ $num }}'] = selectedTool" class="w-10 flex flex-col items-center group cursor-pointer">
                                                <div class="w-8 h-10 border-2 rounded-b-lg flex items-center justify-center relative bg-gray-50 group-hover:bg-blue-50" :class="teeth['{{ $num }}'] ? 'border-blue-400' : 'border-gray-200'">
                                                    <svg class="w-5 h-5 text-gray-300 rotate-180" fill="currentColor" viewBox="0 0 24 24"><path d="M12,2C10,2 7,3 6,5C5,7 5,10 6,14C7,18 9,22 12,22C15,22 17,18 18,14C19,10 19,7 18,5C17,3 14,2 12,2Z"/></svg>
                                                    <div class="absolute inset-0 flex items-center justify-center">
                                                        <template x-if="teeth['{{ $num }}'] === 'check'"><span class="text-green-600 text-lg font-black">✓</span></template>
                                                        <template x-if="teeth['{{ $num }}'] === 'wrong'"><span class="text-red-600 text-lg font-black">✗</span></template>
                                                    </div>
                                                </div>
                                                <span class="text-[9px] font-bold text-gray-400 group-hover:text-blue-600">{{ $num }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <input type="hidden" name="tooth_data" :value="JSON.stringify(teeth)">

                                <div class="mt-8 flex justify-between items-center">
                                    @if(isset($currentSession))
                                        <button type="button" onclick="return confirm('Are you sure you want to delete this session?') ? document.getElementById('delete-form').submit() : false" class="text-red-600 text-xs font-bold hover:underline">Delete This Session</button>
                                    @else
                                        <div></div>
                                    @endif
                                    <button type="submit" class="bg-blue-900 text-white px-10 py-3 rounded-lg font-black hover:bg-black transition shadow-lg uppercase tracking-widest text-sm">
                                        {{ isset($currentSession) ? 'Update Session' : 'Save New Session' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    @if(isset($currentSession))
                    <form id="delete-form" action="{{ route('dental-chart.destroy', $currentSession->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>