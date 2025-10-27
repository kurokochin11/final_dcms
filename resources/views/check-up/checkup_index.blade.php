<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Check-up Form
            <div class="text-base font-medium text-gray-600 dark:text-gray-400 mt-2">
                <p>Name: {{ $patient->first_name }} {{ $patient->last_name }}</p>
                <p>Gender: {{ $patient->sex }}</p>
                <p>Age: {{ $patient->age }}</p>
                <p>Address: {{ $patient->address }}</p>
                <p>Middle Name: {{ $patient->middle_name }}</p>
                <p>Email: {{ $patient->email }}</p>
            </div>
        </h2>
    </x-slot>

    <div class="py-6" x-data="{ step: 1 }">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">

                <form method="POST" action="{{ route('check-up.store', $patient->id) }}">
                    @csrf

                    <!-- Step 1: Dental Check-up (part 1) -->
                    <div x-show="step === 1" x-cloak x-transition>
                        <h3 class="text-lg font-semibold mb-4">Dental Check-up (I) — Part 1</h3>

                        <div class="mb-4">
                            <label class="block font-medium">• Reason for Today's Visit / Chief Complaint:</label>
                            <input type="text" name="checkup_questions[41]"
                                   placeholder="Describe reason for visit"
                                   class="w-full border border-gray-300 rounded p-2 mt-1">
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Are you experiencing any pain or discomfort?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="checkup_questions[42]" value="Yes"> Yes</label>
                                <label><input type="radio" name="checkup_questions[42]" value="No"> No</label>
                            </div>

                            <input type="text" name="checkup_questions[43]" placeholder="If yes, location and duration"
                                   class="mt-2 block w-full border border-gray-300 rounded p-2">
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Have you had any previous dental treatment?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="checkup_questions[44]" value="Yes"> Yes</label>
                                <label><input type="radio" name="checkup_questions[44]" value="No"> No</label>
                            </div>

                            <input type="text" name="checkup_questions[45]" placeholder="Please specify and approximate dates"
                                   class="mt-2 block w-full border border-gray-300 rounded p-2">
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Do your gums bleed when you brush or floss?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="checkup_questions[46]" value="Yes"> Yes</label>
                                <label><input type="radio" name="checkup_questions[46]" value="No"> No</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Are your teeth sensitive to hot, cold, or sweets?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="checkup_questions[47]" value="Yes"> Yes</label>
                                <label><input type="radio" name="checkup_questions[47]" value="No"> No</label>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <div></div>
                            <button type="button" @click="step = 2"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                Next
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Dental Check-up (part 2) -->
                    <div x-show="step === 2" x-cloak x-transition>
                        <h3 class="text-lg font-semibold mb-4">Dental Check-up (I) — Part 2</h3>

                        <div class="mb-4">
                            <label class="block font-medium">• Do you grind or clench your teeth (bruxism)?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="checkup_questions[48]" value="Yes"> Yes</label>
                                <label><input type="radio" name="checkup_questions[48]" value="No"> No</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Do you have any loose teeth?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="checkup_questions[49]" value="Yes"> Yes</label>
                                <label><input type="radio" name="checkup_questions[49]" value="No"> No</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Have you noticed any clicking, popping, or pain in your jaw (TMJ issues)?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="checkup_questions[50]" value="Yes"> Yes</label>
                                <label><input type="radio" name="checkup_questions[50]" value="No"> No</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• How often do you brush your teeth?</label>
                            <input type="text" name="checkup_questions[51]" placeholder="e.g., Twice a day"
                                   class="mt-2 block w-full border border-gray-300 rounded p-2">
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• How often do you floss?</label>
                            <input type="text" name="checkup_questions[52]" placeholder="e.g., Once a day"
                                   class="mt-2 block w-full border border-gray-300 rounded p-2">
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Do you use any other oral hygiene aids (e.g., mouthwash)?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="checkup_questions[53]" value="Yes"> Yes</label>
                                <label><input type="radio" name="checkup_questions[53]" value="No"> No</label>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" @click="step = 1"
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                                Back
                            </button>
                            <button type="button" @click="step = 3"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                Next
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Medical Clearance / Physician info -->
                    <div x-show="step === 3" x-cloak x-transition>
                        <h3 class="text-lg font-semibold mb-4">Medical Clearance</h3>

                        <div class="mb-4">
                            <label class="block font-medium">• Physician's Name (for medical clearance, if required)</label>
                            <input type="text" name="checkup_questions[54]"
                                   class="mt-1 block w-full border border-gray-300 rounded p-2"
                                   placeholder="Enter physician name">
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Physician's Contact Number</label>
                            <input type="text" name="checkup_questions[55]"
                                   class="mt-1 block w-full border border-gray-300 rounded p-2"
                                   placeholder="Enter contact number">
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Reason for Medical Clearance</label>
                            <textarea name="checkup_questions[56]" rows="3"
                                      class="mt-1 block w-full border border-gray-300 rounded p-2"
                                      placeholder="Describe reason for medical clearance"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Date of Medical Clearance</label>
                            <input type="date" name="checkup_questions[57]"
                                   class="mt-1 block w-full border border-gray-300 rounded p-2">
                        </div>

                        <div class="flex justify-between">
                            <button type="button" @click="step = 2"
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                                Back
                            </button>
                            <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                                Save Results
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Progress Indicator -->
                <div class="mt-6 flex justify-center space-x-2">
                    <template x-for="i in 3" :key="i">
                        <span class="w-3 h-3 rounded-full"
                              :class="step === i ? 'bg-blue-600' : 'bg-gray-400'"></span>
                    </template>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
