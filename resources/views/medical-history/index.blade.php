
<link rel="stylesheet" href="{{ asset('assets/css/medical-history.css') }}">
<script src="{{ asset('assets/js/medical-history.js') }}" defer></script>

@section('title', 'Patient Medical History Form')
<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Medical History Form:
    </h2>

        <!-- Patient Info Section -->
    <div class="text-base font-medium patient-info">
        <p><strong>Name:</strong> {{ $patient->first_name }} {{ $patient->middle_name }} {{ $patient->last_name }}</p>
        <p><strong>Gender:</strong> {{ $patient->sex }}</p>
        <p><strong>Age:</strong> {{ $patient->age }}</p>
        <p><strong>Address:</strong> {{ $patient->address }}</p>
        <p><strong>Email:</strong> {{ $patient->email }}</p>
    </div>
    
</x-slot>


    <div class="py-6" x-data="{ step: 1 }">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">

                <form method="POST" action="{{ route('medical-history.store', $patient->id) }}">
                    @csrf

                    <!-- Question Set A -->
                    <div x-show="step === 1">
                        <h3 class="text-lg font-semibold mb-4">A. General Health Conditions</h3>

                        <div class="mb-4">
                            <label class="block font-medium">• Are you currently under the care of a physician?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[1]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[1]" value="No"> No</label>
                            </div>
                            <input type="text" name="medical_questions[2]" placeholder="If Yes, for what condition(s)?"
                                   class="mt-2 block w-full border-gray-300 rounded">
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Have you been hospitalized in the past 5 years?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[3]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[3]" value="No"> No</label>
                            </div>
                            <input type="text" name="medical_questions[4]" placeholder="If Yes, for what reason(s)?"
                                   class="mt-2 block w-full border-gray-300 rounded">
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Do you have any significant illnesses or medical conditions?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[5]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[5]" value="No"> No</label>
                            </div>
                            <input type="text" name="medical_questions[6]" placeholder="If Yes, please specify"
                                   class="mt-2 block w-full border-gray-300 rounded">
                            <p class="text-sm text-gray-500 mt-1">(e.g., Heart Disease, High Blood Pressure, Diabetes, Asthma, Epilepsy, Liver Disease, Kidney Disease, Thyroid Problems, Cancer, HIV/AIDS, Hepatitis)</p>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Do you experience frequent headaches or migraines?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[7]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[7]" value="No"> No</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Are you pregnant or suspect you might be? (For female patients)</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[8]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[8]" value="No"> No</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Do you have any blood disorders (e.g., hemophilia, anemia)?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[9]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[9]" value="No"> No</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Have you ever had any adverse reactions to anesthesia?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[10]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[10]" value="No"> No</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Have you ever had a serious injury to your head, neck, or jaw?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[11]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[11]" value="No"> No</label>
                            </div>
                        </div>

                        <button type="button" @click="step++"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            Next
                        </button>
                    </div>

                    <!-- Questions Set B -->
                    <div x-show="step === 2">
                        <h3 class="text-lg font-semibold mb-4">B. COVID-19 History and Exposure</h3>

                        <div class="mb-4">
                            <label class="block font-medium">• Have you tested positive for COVID-19 in the past 3 months?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[12]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[12]" value="No"> No</label>
                            </div>
                            <input type="text" name="medical_questions[13]" placeholder="If Yes, date of positive test:"
                                   class="mt-2 block w-full border-gray-300 rounded">

                                   
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Have you experienced any COVID-19 symptoms in the past 14 days? (e.g., fever, cough, shortness of breath, loss of taste/smell, sore throat, fatigue)</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[14]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[14]" value="No"> No</label>
                            </div>
                            <input type="text" name="medical_questions[15]" placeholder="If Yes, please specify symptoms"
                                   class="mt-2 block w-full border-gray-300 rounded">
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Have you been in close contact with anyone diagnosed with COVID-19 in the past 14 days?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[16]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[16]" value="No"> No</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Have you received any COVID-19 vaccine doses?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[17]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[17]" value="No"> No</label>
                            </div>
                            <input type="text" name="medical_questions[18]" placeholder="If Yes, please specify type and date(s) of last dose"
                                   class="mt-2 block w-full border-gray-300 rounded">
                        </div>

                        <div class="flex justify-between">
                            <button type="button" @click="step--"
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                                Back
                            </button>
                            <button type="button" @click="step++"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                Next
                            </button>
                        </div>
                    </div>

                    <!-- Question Set C -->
                    <div x-show="step === 3">
                        <h3 class="text-lg font-semibold mb-4">C. Allergies & Reactions</h3>

                        <div class="mb-4">
                            <label class="block font-medium">• Are you allergic to any medications?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[19]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[19]" value="No"> No</label>
                            </div>
                             <input type="text" name="medical_questions[20]" placeholder="If Yes, please specify"
                                   class="mt-2 block w-full border-gray-300 rounded">
                            <p class="text-sm text-gray-500 mt-1">	(e.g., Penicillin, Aspirin, Ibuprofen, Local Anesthetics)</p>
                        </div>

                           <div class="mb-4">
                            <label class="block font-medium"> •Are you allergic to latex, metals, or any dental materials?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[21]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[21]" value="No"> No</label>
                            </div>
                            <input type="text" name="medical_questions[22]" placeholder="If Yes, please specify: "
                                   class="mt-2 block w-full border-gray-300 rounded">
                        </div>

                         <div class="mb-4">
                            <label class="block font-medium">• Do you have any other allergies (e.g., food, environmental)?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[23]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[23]" value="No"> No</label>
                            </div>
                            <input type="text" name="medical_questions[24]" placeholder="If Yes, please list: "
                                   class="mt-2 block w-full border-gray-300 rounded">
                        </div>

                        <div class="flex justify-between">
                            <button type="button" @click="step--"
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                                Back
                            </button>
                            <button type="button" @click="step++"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                Next
                            </button>
                        </div>
                    </div>

                    <!-- Question set D -->
                    <div x-show="step === 4">
                        <h3 class="text-lg font-semibold mb-4">D. Current Medications</h3>

                        <div class="mb-4">
                            <label class="block font-medium">• Are you currently taking any prescription medications?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[25]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[25]" value="No"> No</label>
                            </div>
                            <input type="text" name="medical_questions[26]" placeholder="If Yes, please list"
                                   class="mt-2 block w-full border-gray-300 rounded">
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Are you currently taking any over-the-counter medications, supplements, or herbal remedies?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[27]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[27]" value="No"> No</label>
                            </div>
                            <input type="text" name="medical_questions[28]" placeholder="If Yes, please list"
                                   class="mt-2 block w-full border-gray-300 rounded">
                        </div>



                        <div class="flex justify-between">
                            <button type="button" @click="step--"
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                                Back
                            </button>
                            <button type="button" @click="step++"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                Next
                            </button>
                        </div>
                    </div>

                    <!-- Questions Set E -->
                    <div x-show="step === 5">
                        <h3 class="text-lg font-semibold mb-4">E. Habits & Lifestyle</h3>

                        <div class="mb-4">
                            <label class="block font-medium">• Do you smoke?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[29]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[29]" value="No"> No</label>
                            </div>
                            <input type="text" name="medical_questions[30]" placeholder="If Yes, how much/often?"
                                   class="mt-2 block w-full border-gray-300 rounded">
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Do you consume alcoholic beverages?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[31]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[31]" value="No"> No</label>
                            </div>
                            <input type="text" name="medical_questions[32]" placeholder="If Yes, how much/often?"
                                   class="mt-2 block w-full border-gray-300 rounded">
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Do you use recreational drugs?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[33]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[33]" value="No"> No</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">• Have you ever experienced any adverse reactions to dental procedures in the past?</label>
                            <div class="mt-1 flex gap-4">
                                <label><input type="radio" name="medical_questions[34]" value="Yes"> Yes</label>
                                <label><input type="radio" name="medical_questions[34]" value="No"> No</label>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" @click="step--"
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                                Back
                            </button>
                            <button type="button" @click="step++"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                Next
                            </button>
                        </div>
                    </div>

                    <!-- Question Set F -->
                    <div x-show="step === 6">
                        <h3 class="text-lg font-semibold mb-4">F. Blood Pressure</h3>

                        <div class="flex items-center space-x-2 mb-4">
                            <label for="bp"> BP</label>
                            <input id="bp" type="text" name="medical_questions[35]"
                                   class="block w-full border-gray-300 rounded">
                        </div>

                        <div class="flex justify-between">
                            <button type="button" @click="step--"
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                                Back
                            </button>
                            <button type="button" @click="step++"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                Next
                            </button>
                        </div>
                    </div>

                    <!-- Question Set G -->
                    <div x-show="step === 7">
                        <h3 class="text-lg font-semibold mb-4">G. Additional Notes</h3>

                        <div class="flex items-center space-x-2 mb-4">
                            <label for="gs" class="whitespace-nowrap">
                                 s, please list:
                            </label>
                           <textarea id="gs" name="medical_questions[36]" rows="4"
              class="w-full border-gray-300 rounded-lg px-4 py-3 text-lg resize-y"></textarea>
                        </div>
                        
                         <div class="flex justify-between">
        <button type="button" @click="step--"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
            Back
        </button>
        <button type="button" @click="step++"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Next
        </button>
    </div>
</div>


<!-- Question Set H -->
<div x-show="step === 8">
    <h3 class="text-lg font-semibold mb-4">H. Medical Clearance</h3>

    <div class="mb-4">
        <label class="block font-medium">• Date of Clearance (MM/DD/YYYY)</label>
        <input type="date" name="medical_questions[37]"
               class="mt-1 block w-full border-gray-300 rounded">
    </div>

    <div class="mb-4">
        <label class="block font-medium">• Physician's Name</label>
        <input type="text" name="medical_questions[38]"
               class="mt-1 block w-full border-gray-300 rounded">
    </div>

    <div class="mb-4">
        <label class="block font-medium">• Contact Number</label>
        <input type="text" name="medical_questions[39]"
               class="mt-1 block w-full border-gray-300 rounded">
    </div>

    <div class="mb-4">
        <label class="block font-medium">• Reason for clearance</label>
        <textarea name="medical_questions[40]" rows="3"
                  class="mt-1 block w-full border-gray-300 rounded"></textarea>
    </div>

                        <div class="flex justify-between">
                            <button type="button" @click="step--"
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                                Back
                            </button>
                            <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                                Save Medical History
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Progress Indicator -->
                <div class="mt-6 flex justify-center space-x-2">
                    <template x-for="i in 8">
                        <span class="w-3 h-3 rounded-full"
                              :class="step === i ? 'bg-blue-600' : 'bg-gray-400'"></span>
                    </template>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
