<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen" x-data="{ 
        selectedTool: 'check', 
        teeth: {{ json_encode($currentSession->tooth_data ?? (object)[]) }},
        notes: {{ json_encode($currentSession->tooth_notes ?? (object)[]) }} 
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
                                    'denture_upper_since' => 'Denture Upper - Since',
                                    'denture_lower_since' => 'Denture Lower - Since',
                                    'abnormalities' => 'Abnormalities',
                                    'general_condition' => 'General Condition',
                                    'physician' => 'Physician',
                                    'nature_of_treatment' => 'Nature of Treatment',
                                    'allergies' => 'Allergies',
                                    'chronic_ailments' => 'Chronic Ailments',
                                    'blood_pressure' => 'Blood Pressure',
                                    'spo2' => 'SPO2 (%)',
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
                                    
                                    <button type="button" @click="selectedTool = 'check'" :class="selectedTool === 'check' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600'" class="px-4 py-2 rounded-md text-[10px] font-black transition-all uppercase tracking-widest">✓ Check</button>
                                    
                                    <button type="button" @click="selectedTool = 'wrong'" :class="selectedTool === 'wrong' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-600'" class="px-4 py-2 rounded-md text-[10px] font-black transition-all uppercase tracking-widest">✗ Wrong</button>

                                    <button type="button" @click="selectedTool = 'uncheck'" :class="selectedTool === 'uncheck' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600'" class="px-4 py-2 rounded-md text-[10px] font-black transition-all uppercase tracking-widest">∅ Uncheck</button>
                                    
                                    <button type="button" @click="teeth = {}; notes = {}; selectedTool = 'check'" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-md text-[10px] font-black transition-all uppercase tracking-widest hover:bg-gray-800 hover:text-white">Clear All</button>
                                </div>
                            </div>

                            @php
                                $upperAdult = [18,17,16,15,14,13,12,11,21,22,23,24,25,26,27,28];
                                $lowerAdult = [48,47,46,45,44,43,42,41,31,32,33,34,35,36,37,38];
                                $upperDeciduous = [55,54,53,52,51,61,62,63,64,65];
                                $lowerDeciduous = [85,84,83,82,81,71,72,73,74,75];
                            @endphp

                            <h5 class="text-center font-black text-gray-300 uppercase text-[10px] mb-3 tracking-widest">Permanent Teeth</h5>
                            <div class="bg-white border border-gray-100 rounded-xl p-6 mb-8 shadow-sm">
                                
                                <div class="grid grid-cols-16 gap-1 mb-10">
                                    @foreach($upperAdult as $num)
                                        <div class="flex flex-col items-center tooth-container" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                            <div x-show="open && notes['{{ $num }}']" x-transition class="absolute z-50 mb-14 bg-blue-900 text-white text-[10px] py-1 px-2 rounded shadow-xl font-bold whitespace-nowrap bottom-full pointer-events-none">
                                                <span x-text="notes['{{ $num }}']"></span>
                                                <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-blue-900"></div>
                                            </div>
                                            <input type="text" x-model="notes['{{ $num }}']" @focus="open = true" @blur="open = false" placeholder="..." class="w-full text-[9px] p-1 mb-1 bg-white border border-blue-100 rounded text-center focus:ring-1 focus:ring-blue-400 uppercase font-bold text-blue-900">
                                            <span class="text-[9px] font-black text-gray-400 mb-1">{{ $num }}</span>
                                            
                                            <div @click="if(selectedTool === 'uncheck') { delete teeth['{{ $num }}']; } else { teeth['{{ $num }}'] === selectedTool ? delete teeth['{{ $num }}'] : teeth['{{ $num }}'] = selectedTool }" 
                                                 class="w-full aspect-square border-2 rounded-t-lg flex items-center justify-center relative bg-gray-50 cursor-pointer" 
                                                 :class="teeth['{{ $num }}'] ? 'border-blue-400 bg-blue-50' : 'border-gray-200'">
                                                <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12,2C10,2 7,3 6,5C5,7 5,10 6,14C7,18 9,22 12,22C15,22 17,18 18,14C19,10 19,7 18,5C17,3 14,2 12,2Z"/></svg>
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <template x-if="teeth['{{ $num }}'] === 'check'"><span class="text-green-600 text-base font-black">✓</span></template>
                                                    <template x-if="teeth['{{ $num }}'] === 'wrong'"><span class="text-red-600 text-base font-black">✗</span></template>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="grid grid-cols-16 gap-1 mt-6">
                                    @foreach($lowerAdult as $num)
                                        <div class="flex flex-col items-center tooth-container" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                            <div x-show="open && notes['{{ $num }}']" x-transition class="absolute z-50 mb-2 bg-blue-900 text-white text-[10px] py-1 px-2 rounded shadow-xl font-bold whitespace-nowrap bottom-full pointer-events-none">
                                                <span x-text="notes['{{ $num }}']"></span>
                                                <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-blue-900"></div>
                                            </div>
                                            <div @click="if(selectedTool === 'uncheck') { delete teeth['{{ $num }}']; } else { teeth['{{ $num }}'] === selectedTool ? delete teeth['{{ $num }}'] : teeth['{{ $num }}'] = selectedTool }" 
                                                 class="w-full aspect-square border-2 rounded-b-lg flex items-center justify-center relative bg-gray-50 cursor-pointer" 
                                                 :class="teeth['{{ $num }}'] ? 'border-blue-400 bg-blue-50' : 'border-gray-200'">
                                                <svg class="w-5 h-5 text-gray-300 rotate-180" fill="currentColor" viewBox="0 0 24 24"><path d="M12,2C10,2 7,3 6,5C5,7 5,10 6,14C7,18 9,22 12,22C15,22 17,18 18,14C19,10 19,7 18,5C17,3 14,2 12,2Z"/></svg>
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <template x-if="teeth['{{ $num }}'] === 'check'"><span class="text-green-600 text-base font-black">✓</span></template>
                                                    <template x-if="teeth['{{ $num }}'] === 'wrong'"><span class="text-red-600 text-base font-black">✗</span></template>
                                                </div>
                                            </div>
                                            <span class="text-[9px] font-black text-gray-400 mt-1">{{ $num }}</span>
                                            <input type="text" x-model="notes['{{ $num }}']" @focus="open = true" @blur="open = false" placeholder="..." class="w-full text-[9px] p-1 mt-1 bg-white border border-blue-100 rounded text-center focus:ring-1 focus:ring-blue-400 uppercase font-bold text-blue-900">
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <h5 class="text-center font-black text-gray-300 uppercase text-[10px] mb-3 tracking-widest">Deciduous Teeth</h5>
                            <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm max-w-3xl mx-auto">
                                <div class="grid grid-cols-10 gap-3 mb-10">
                                    @foreach($upperDeciduous as $num)
                                        <div class="flex flex-col items-center tooth-container" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                            <div x-show="open && notes['{{ $num }}']" x-transition class="absolute z-50 mb-14 bg-blue-900 text-white text-[10px] py-1 px-2 rounded shadow-xl font-bold whitespace-nowrap bottom-full pointer-events-none">
                                                <span x-text="notes['{{ $num }}']"></span>
                                                <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-blue-900"></div>
                                            </div>
                                            <input type="text" x-model="notes['{{ $num }}']" @focus="open = true" @blur="open = false" placeholder="..." class="w-full text-[9px] p-1 mb-1 bg-white border border-blue-100 rounded text-center focus:ring-1 focus:ring-blue-400 uppercase font-bold">
                                            <span class="text-[9px] font-black text-gray-400 mb-1">{{ $num }}</span>
                                            <div @click="if(selectedTool === 'uncheck') { delete teeth['{{ $num }}']; } else { teeth['{{ $num }}'] === selectedTool ? delete teeth['{{ $num }}'] : teeth['{{ $num }}'] = selectedTool }" 
                                                 class="w-12 h-12 border-2 rounded-t-lg flex items-center justify-center relative bg-gray-50 cursor-pointer" 
                                                 :class="teeth['{{ $num }}'] ? 'border-blue-400 bg-blue-50' : 'border-gray-200'">
                                                <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12,2C10,2 7,3 6,5C5,7 5,10 6,14C7,18 9,22 12,22C15,22 17,18 18,14C19,10 19,7 18,5C17,3 14,2 12,2Z"/></svg>
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <template x-if="teeth['{{ $num }}'] === 'check'"><span class="text-green-600 text-sm font-black">✓</span></template>
                                                    <template x-if="teeth['{{ $num }}'] === 'wrong'"><span class="text-red-600 text-sm font-black">✗</span></template>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="grid grid-cols-10 gap-3">
                                    @foreach($lowerDeciduous as $num)
                                        <div class="flex flex-col items-center tooth-container" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                            <div x-show="open && notes['{{ $num }}']" x-transition class="absolute z-50 mb-2 bg-blue-900 text-white text-[10px] py-1 px-2 rounded shadow-xl font-bold whitespace-nowrap bottom-full pointer-events-none">
                                                <span x-text="notes['{{ $num }}']"></span>
                                                <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-blue-900"></div>
                                            </div>
                                            <div @click="if(selectedTool === 'uncheck') { delete teeth['{{ $num }}']; } else { teeth['{{ $num }}'] === selectedTool ? delete teeth['{{ $num }}'] : teeth['{{ $num }}'] = selectedTool }" 
                                                 class="w-12 h-12 border-2 rounded-b-lg flex items-center justify-center relative bg-gray-50 cursor-pointer" 
                                                 :class="teeth['{{ $num }}'] ? 'border-blue-400 bg-blue-50' : 'border-gray-200'">
                                                <svg class="w-4 h-4 text-gray-300 rotate-180" fill="currentColor" viewBox="0 0 24 24"><path d="M12,2C10,2 7,3 6,5C5,7 5,10 6,14C7,18 9,22 12,22C15,22 17,18 18,14C19,10 19,7 18,5C17,3 14,2 12,2Z"/></svg>
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <template x-if="teeth['{{ $num }}'] === 'check'"><span class="text-green-600 text-sm font-black">✓</span></template>
                                                    <template x-if="teeth['{{ $num }}'] === 'wrong'"><span class="text-red-600 text-sm font-black">✗</span></template>
                                                </div>
                                            </div>
                                            <span class="text-[9px] font-black text-gray-400 mt-1">{{ $num }}</span>
                                            <input type="text" x-model="notes['{{ $num }}']" @focus="open = true" @blur="open = false" placeholder="..." class="w-full text-[9px] p-1 mt-1 bg-white border border-blue-100 rounded text-center focus:ring-1 focus:ring-blue-400 uppercase font-bold">
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <input type="hidden" name="tooth_data" :value="JSON.stringify(teeth)">
                            <input type="hidden" name="tooth_notes" :value="JSON.stringify(notes)">

                            <div class="mt-10 flex justify-end items-center border-t border-gray-100 pt-6">
                                <button type="submit" class="bg-blue-900 text-white px-10 py-3 rounded-lg font-black hover:bg-black transition-all shadow-lg uppercase tracking-widest text-[10px]">
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
        .grid-cols-16 { grid-template-columns: repeat(16, minmax(0, 1fr)); }
        .tooth-container { position: relative; }
    </style>
</x-app-layout>