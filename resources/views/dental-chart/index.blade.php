<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
                
             <div class="p-8 border-b border-gray-100 flex justify-between items-center bg-white">
    <div>
        <h2 class="text-2xl font-black text-blue-900 uppercase tracking-tighter">Session History</h2>
        <div class="flex flex-col gap-1 mt-1">
            <p class="text-xs text-gray-500 font-bold uppercase">
                Patient: {{ $patient->last_name }}, {{ $patient->first_name }}
            </p>
            <p class="text-[10px] text-blue-600 font-black uppercase tracking-widest">
                ID: #{{ str_pad($patient->id, 5, '0', STR_PAD_LEFT) }}
            </p>
        </div>
    </div>
    
                    <a href="{{ route('dental-chart.create', $patient->id) }}" 
                       class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-bold text-xs transition uppercase tracking-widest shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                        New Session
                    </a>
                </div>

                <div class="p-0">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date of Visit</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nature of Treatment</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($sessions as $session)
                                <tr class="hover:bg-blue-50/30 transition group">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 rounded-full bg-blue-500 mr-3"></div>
                                            <span class="text-sm font-bold text-gray-700">{{ $session->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="text-sm text-gray-600 font-medium">
                                            {{ $session->nature_of_treatment ?? 'General Check-up' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <a href="{{ route('dental-chart.show', [$patient->id, $session->id]) }}" 
                                           class="inline-flex items-center text-blue-600 font-black text-[10px] uppercase tracking-widest hover:text-blue-800 transition">
                                            View Record
                                            <svg class="w-3 h-3 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <p class="text-gray-400 font-bold text-sm uppercase tracking-widest">No dental records found</p>
                                            <p class="text-gray-400 text-xs mt-1">Start by clicking the "New Session" button above.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($sessions->count() > 0)
                    <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            Showing {{ $sessions->count() }} total recorded sessions
                        </p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>