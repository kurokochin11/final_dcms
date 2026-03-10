<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen" x-data="{ 
        selectedTool: 'check', 
        teeth: {{ json_encode($currentSession->tooth_data ?? (object)[]) }} 
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('dental-chart.index', $patient->id) }}" class="inline-flex items-center text-blue-700 font-black text-xs uppercase tracking-widest hover:text-black transition group">
                    <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                    Back to History
                </a>
            </div>

            <div class="bg-white p-10 rounded-xl shadow-sm border border-gray-200">
                <div class="border-b-2 border-blue-900 pb-6 mb-8">
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
                            {{ isset($currentSession) ? $currentSession->created_at->format('m/d/Y') : now()->format('m/d/Y') }}
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
                    
                    <div class="flex flex-col lg:flex-row gap-8">
                        <div class="w-full lg:w-1/4 space-y-3 bg-gray-50 p-5 rounded-xl border border-gray-100">
                            <h4 class="font-black border-b border-gray-200 pb-2 text-blue-900 uppercase text-[10px] tracking-widest mb-4">Clinical Data</h4>
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
                                    <label class="block text-[9px] uppercase font-black text-gray-400 mb-1">{{ $label }}</label>
                                    <input type="text" name="{{ $key }}" value="{{ $currentSession->$key ?? '' }}" 
                                           class="w-full text-xs border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 py-1.5 shadow-sm">
                                </div>
                            @endforeach
                        </div>

                        <div class="w-full lg:w-3/4">
                            <div class="mb-6 flex items-center justify-between bg-white border border-gray-200 p-3 rounded-xl shadow-sm">
                                <div class="flex gap-2 items-center">
                                    <span class="font-black text-gray-400 uppercase text-[9px] tracking-widest mr-2">Tools:</span>
                                    <button type="button" @click="selectedTool = 'check'" :class="selectedTool === 'check' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600'" class="px-3 py-1.5 rounded-md text-[9px] font-black transition-all uppercase tracking-widest">✓ Check</button>
                                    <button type="button" @click="selectedTool = 'wrong'" :class="selectedTool === 'wrong' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-600'" class="px-3 py-1.5 rounded-md text-[9px] font-black transition-all uppercase tracking-widest">✗ Wrong</button>
                                    <button type="button" @click="selectedTool = ''" :class="selectedTool === '' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600'" class="px-3 py-1.5 rounded-md text-[9px] font-black transition-all uppercase tracking-widest">Clear</button>
                                </div>
                            </div>

                            @php
                                $upperAdult = [18,17,16,15,14,13,12,11,21,22,23,24,25,26,27,28];
                                $lowerAdult = [48,47,46,45,44,43,42,41,31,32,33,34,35,36,37,38];
                                $upperDeciduous = [55,54,53,52,51,61,62,63,64,65];
                                $lowerDeciduous = [85,84,83,82,81,71,72,73,74,75];
                            @endphp

                            <h5 class="text-center font-black text-gray-300 uppercase text-[10px] mb-3 tracking-widest">Permanent Teeth</h5>
                            <div class="bg-white border border-gray-100 rounded-xl p-4 mb-8 shadow-sm">
                                <div class="grid grid-cols-16 gap-1 mb-6">
                                    @foreach($upperAdult as $num)
                                        <div @click="teeth['{{ $num }}'] = selectedTool" class="flex flex-col items-center group cursor-pointer">
                                            <span class="text-[8px] font-black text-gray-400 mb-1">{{ $num }}</span>
                                            <div class="w-full aspect-square border-2 rounded-t-lg flex items-center justify-center relative bg-gray-50 group-hover:bg-blue-50" :class="teeth['{{ $num }}'] ? 'border-blue-400 bg-blue-50' : 'border-gray-200'">
                                                <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12,2C10,2 7,3 6,5C5,7 5,10 6,14C7,18 9,22 12,22C15,22 17,18 18,14C19,10 19,7 18,5C17,3 14,2 12,2Z"/></svg>
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <template x-if="teeth['{{ $num }}'] === 'check'"><span class="text-green-600 text-base font-black">✓</span></template>
                                                    <template x-if="teeth['{{ $num }}'] === 'wrong'"><span class="text-red-600 text-base font-black">✗</span></template>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="grid grid-cols-16 gap-1">
                                    @foreach($lowerAdult as $num)
                                        <div @click="teeth['{{ $num }}'] = selectedTool" class="flex flex-col items-center group cursor-pointer">
                                            <div class="w-full aspect-square border-2 rounded-b-lg flex items-center justify-center relative bg-gray-50 group-hover:bg-blue-50" :class="teeth['{{ $num }}'] ? 'border-blue-400 bg-blue-50' : 'border-gray-200'">
                                                <svg class="w-4 h-4 text-gray-300 rotate-180" fill="currentColor" viewBox="0 0 24 24"><path d="M12,2C10,2 7,3 6,5C5,7 5,10 6,14C7,18 9,22 12,22C15,22 17,18 18,14C19,10 19,7 18,5C17,3 14,2 12,2Z"/></svg>
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <template x-if="teeth['{{ $num }}'] === 'check'"><span class="text-green-600 text-base font-black">✓</span></template>
                                                    <template x-if="teeth['{{ $num }}'] === 'wrong'"><span class="text-red-600 text-base font-black">✗</span></template>
                                                </div>
                                            </div>
                                            <span class="text-[8px] font-black text-gray-400 mt-1">{{ $num }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <h5 class="text-center font-black text-gray-300 uppercase text-[10px] mb-3 tracking-widest">Deciduous Teeth</h5>
                            <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm max-w-2xl mx-auto">
                                <div class="grid grid-cols-10 gap-2 mb-6">
                                    @foreach($upperDeciduous as $num)
                                        <div @click="teeth['{{ $num }}'] = selectedTool" class="flex flex-col items-center group cursor-pointer">
                                            <span class="text-[8px] font-black text-gray-400 mb-1">{{ $num }}</span>
                                            <div class="w-10 h-10 border-2 rounded-t-lg flex items-center justify-center relative bg-gray-50" :class="teeth['{{ $num }}'] ? 'border-blue-400 bg-blue-50' : 'border-gray-200'">
                                                <svg class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12,2C10,2 7,3 6,5C5,7 5,10 6,14C7,18 9,22 12,22C15,22 17,18 18,14C19,10 19,7 18,5C17,3 14,2 12,2Z"/></svg>
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <template x-if="teeth['{{ $num }}'] === 'check'"><span class="text-green-600 text-base font-black">✓</span></template>
                                                    <template x-if="teeth['{{ $num }}'] === 'wrong'"><span class="text-red-600 text-base font-black">✗</span></template>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="grid grid-cols-10 gap-2">
                                    @foreach($lowerDeciduous as $num)
                                        <div @click="teeth['{{ $num }}'] = selectedTool" class="flex flex-col items-center group cursor-pointer">
                                            <div class="w-10 h-10 border-2 rounded-b-lg flex items-center justify-center relative bg-gray-50" :class="teeth['{{ $num }}'] ? 'border-blue-400 bg-blue-50' : 'border-gray-200'">
                                                <svg class="w-3 h-3 text-gray-300 rotate-180" fill="currentColor" viewBox="0 0 24 24"><path d="M12,2C10,2 7,3 6,5C5,7 5,10 6,14C7,18 9,22 12,22C15,22 17,18 18,14C19,10 19,7 18,5C17,3 14,2 12,2Z"/></svg>
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <template x-if="teeth['{{ $num }}'] === 'check'"><span class="text-green-600 text-base font-black">✓</span></template>
                                                    <template x-if="teeth['{{ $num }}'] === 'wrong'"><span class="text-red-600 text-base font-black">✗</span></template>
                                                </div>
                                            </div>
                                            <span class="text-[8px] font-black text-gray-400 mt-1">{{ $num }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <input type="hidden" name="tooth_data" :value="JSON.stringify(teeth)">

                            <div class="mt-10 flex justify-between items-center border-t border-gray-100 pt-6">
                                @if(isset($currentSession))
                                 
                                    <div></div>
                                @endif
                                <button type="submit" class="bg-blue-900 text-white px-8 py-3 rounded-lg font-black hover:bg-black transition-all shadow-lg uppercase tracking-widest text-[10px]">
                                    {{ isset($currentSession) ? 'Update Record' : 'Save Examination' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Custom grid for the 16 teeth across */
        .grid-cols-16 {
            grid-template-columns: repeat(16, minmax(0, 1fr));
        }
    </style>
</x-app-layout>