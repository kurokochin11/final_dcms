<x-app-layout>
    <div class="py-6" x-data="{ 
        selectedTool: 'check', 
        teeth: {{ json_encode($exam->tooth_data ?? []) }} 
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white p-8 rounded-lg shadow border border-gray-200">
            
            <div class="border-b-2 border-blue-900 pb-4 mb-6">
                <h2 class="text-2xl font-black text-center text-blue-900 uppercase tracking-widest">Patient's Examination Record Chart</h2>
                <div class="grid grid-cols-4 gap-4 mt-6 text-sm">
                    <div class="border-b border-gray-300"><strong>Name:</strong> {{ $patient->last_name }}, {{ $patient->first_name }}</div>
                    <div class="border-b border-gray-300"><strong>Age:</strong> {{ $patient->age }}</div>
                    <div class="border-b border-gray-300"><strong>Sex:</strong> {{ $patient->sex }}</div>
                    <div class="border-b border-gray-300"><strong>Contact:</strong> {{ $patient->mobile_number }}</div>
                </div>
            </div>

            <form action="{{ route('dental-chart.store', $patient->id) }}" method="POST">
                @csrf
                <div class="flex flex-row gap-8">
                    
                    <div class="w-1/3 space-y-3 bg-blue-50/50 p-5 rounded-lg border border-blue-100">
                        <h4 class="font-bold border-b border-blue-200 pb-1 text-blue-800 uppercase text-xs tracking-tighter">Clinical History</h4>
                        
                        @php
                            $allFields = [
                                'occlusion' => 'Occlusion',
                                'periodontal_condition' => 'Periodontal Condition',
                                'oral_hygiene' => 'Oral Hygiene',
                                'abnormalities' => 'Abnormalities',
                                'general_condition' => 'General Condition',
                                'physician' => 'Physician',
                                'nature_of_treatment' => 'Nature of Treatment',
                                'allergies' => 'Allergies',
                                'previous_bleeding' => 'Prev. History of Bleeding',
                                'chronic_ailments' => 'Chronic Ailments',
                                'blood_pressure' => 'Blood Pressure',
                                'drugs_taken' => 'Drugs Being Taken'
                            ];
                        @endphp

                        @foreach($allFields as $key => $label)
                            <div>
                                <label class="block text-[10px] uppercase font-black text-gray-500">{{ $label }}</label>
                                <input type="text" name="{{ $key }}" value="{{ $exam->$key ?? '' }}" 
                                       class="w-full text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 py-1">
                            </div>
                        @endforeach
                    </div>

                    <div class="w-2/3">
                        <div class="mb-6 flex items-center justify-between bg-white border p-3 rounded-lg shadow-sm">
                            <div class="flex gap-4 items-center">
                                <span class="font-bold text-gray-700 text-sm">Select Tool:</span>
                                <button type="button" @click="selectedTool = 'check'" :class="selectedTool === 'check' ? 'bg-green-600 text-white' : 'bg-gray-100'" class="px-4 py-1 rounded-full text-xs font-bold transition">✓ CHECK</button>
                                <button type="button" @click="selectedTool = 'wrong'" :class="selectedTool === 'wrong' ? 'bg-red-600 text-white' : 'bg-gray-100'" class="px-4 py-1 rounded-full text-xs font-bold transition">✗ WRONG</button>
                                <button type="button" @click="selectedTool = ''" class="px-4 py-1 bg-gray-200 rounded-full text-xs font-bold">CLEAR</button>
                            </div>
                        </div>

                        <h5 class="text-center font-bold text-gray-400 uppercase text-xs mb-2 tracking-widest">Permanent Teeth</h5>
                        <div class="bg-white border rounded p-4 mb-8">
                            @php
                                $upperAdult = [18,17,16,15,14,13,12,11,21,22,23,24,25,26,27,28];
                                $lowerAdult = [48,47,46,45,44,43,42,41,31,32,33,34,35,36,37,38];
                            @endphp

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

                        <h5 class="text-center font-bold text-gray-400 uppercase text-xs mb-2 tracking-widest">Deciduous Teeth</h5>
                        <div class="bg-gray-50 border border-dashed rounded p-4 flex flex-col items-center">
                            @php
                                $upperBaby = [55,54,53,52,51,61,62,63,64,65];
                                $lowerBaby = [85,84,83,82,81,71,72,73,74,75];
                            @endphp
                            <div class="flex gap-1 mb-4">
                                @foreach($upperBaby as $num)
                                    <div @click="teeth['{{ $num }}'] = selectedTool" class="w-8 flex flex-col items-center cursor-pointer">
                                        <span class="text-[8px] font-bold text-gray-400">{{ $num }}</span>
                                        <div class="w-7 h-9 border rounded-t flex items-center justify-center relative bg-white" :class="teeth['{{ $num }}'] ? 'border-blue-400 bg-blue-50' : 'border-gray-300'">
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <template x-if="teeth['{{ $num }}'] === 'check'"><span class="text-green-600 text-sm font-black">✓</span></template>
                                                <template x-if="teeth['{{ $num }}'] === 'wrong'"><span class="text-red-600 text-sm font-black">✗</span></template>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex gap-1">
                                @foreach($lowerBaby as $num)
                                    <div @click="teeth['{{ $num }}'] = selectedTool" class="w-8 flex flex-col items-center cursor-pointer">
                                        <div class="w-7 h-9 border rounded-b flex items-center justify-center relative bg-white" :class="teeth['{{ $num }}'] ? 'border-blue-400 bg-blue-50' : 'border-gray-300'">
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <template x-if="teeth['{{ $num }}'] === 'check'"><span class="text-green-600 text-sm font-black">✓</span></template>
                                                <template x-if="teeth['{{ $num }}'] === 'wrong'"><span class="text-red-600 text-sm font-black">✗</span></template>
                                            </div>
                                        </div>
                                        <span class="text-[8px] font-bold text-gray-400">{{ $num }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <input type="hidden" name="tooth_data" :value="JSON.stringify(teeth)">

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="bg-blue-900 text-white px-10 py-3 rounded-lg font-black hover:bg-black transition shadow-lg uppercase tracking-widest text-sm">
                                Save Patient Record
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>