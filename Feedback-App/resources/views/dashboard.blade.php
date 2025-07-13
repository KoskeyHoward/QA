<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Feedback Management Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="p-4 mb-4 text-green-700 bg-green-100 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Tab Navigation -->
                    <div class="mb-6 border-b border-gray-200">
                        <nav class="flex -mb-px space-x-8 overflow-x-auto">
                            <a href="{{ route('dashboard') }}" 
                               class="@if(!request()->has('tab')) border-indigo-500 text-indigo-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                All Feedback
                            </a>
                            <a href="{{ route('dashboard', ['tab' => 'pending']) }}" 
                               class="@if(request('tab') === 'pending') border-indigo-500 text-indigo-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Pending
                            </a>
                            <a href="{{ route('dashboard', ['tab' => 'approved']) }}" 
                               class="@if(request('tab') === 'approved') border-indigo-500 text-indigo-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Approved
                            </a>
                        </nav>
                    </div>

                    <!-- Feedback Table -->
                    <div class="overflow-x-auto">
                        @php
                            $currentTab = request('tab');
                            $showActions = in_array($currentTab, ['pending', 'approved']) || !$currentTab;
                        @endphp

                        @if(($currentTab === 'pending' && $pendingFeedback->count() > 0) || 
                            ($currentTab === 'approved' && $approvedFeedback->count() > 0) || 
                            (!$currentTab && ($pendingFeedback->count() > 0 || $approvedFeedback->count() > 0)))
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Email</th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Message</th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Rating</th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                                        @if($showActions)
                                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($currentTab === 'pending' ? $pendingFeedback : ($currentTab === 'approved' ? $approvedFeedback : $pendingFeedback->concat($approvedFeedback)) as $feedback)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $feedback->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $feedback->email }}</td>
                                        <td class="max-w-xs px-6 py-4 truncate">{{ $feedback->message }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($feedback->rating)
                                                {{ str_repeat('⭐', $feedback->rating) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($feedback->approved) bg-green-100 text-green-800 
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ $feedback->approved ? 'Approved' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $feedback->created_at->format('M d, Y') }}
                                        </td>
                                        @if($showActions)
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex space-x-2">
                                                @if(!$feedback->approved)
                                                    <form action="{{ route('feedback.approve', $feedback) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="text-green-600 hover:text-green-900">Approve</button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('feedback.destroy', $feedback) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-gray-500">No feedback found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>