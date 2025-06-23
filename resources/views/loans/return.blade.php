@extends('layouts.app')

@section('title', 'Return Tools')
@section('header', 'Return Tools - ' . $loan->loan_number)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Loan Information -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Loan Number</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $loan->loan_number }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Borrower</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $loan->user->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Program</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $loan->technicalProgram->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Expected Return</dt>
                    <dd class="mt-1 text-sm text-gray-900 {{ $loan->expected_return_date < now() ? 'text-red-600 font-semibold' : '' }}">
                        {{ $loan->expected_return_date->format('M d, Y') }}
                        @if($loan->expected_return_date < now())
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                {{ now()->diffInDays($loan->expected_return_date) }} days overdue
                            </span>
                        @endif
                    </dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Return Form -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Process Tool Return</h3>

            <form method="POST" action="{{ route('loans.return', $loan) }}" id="returnForm">
                @csrf

                <div class="space-y-6">
                    @foreach($loan->toolLoanItems as $item)
                        <div class="border border-gray-200 rounded-lg p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900">{{ $item->tool->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $item->tool->code }} - {{ $item->tool->category }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">Delivered</div>
                                    <div class="text-lg font-semibold text-blue-600">{{ $item->quantity_delivered }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="items[{{ $item->id }}][quantity_returned]" class="block text-sm font-medium text-gray-700">
                                        Quantity Returned *
                                    </label>
                                    <input type="number"
                                           name="items[{{ $item->id }}][quantity_returned]"
                                           id="items[{{ $item->id }}][quantity_returned]"
                                           value="{{ old("items.{$item->id}.quantity_returned", $item->quantity_delivered) }}"
                                           required
                                           min="0"
                                           max="{{ $item->quantity_delivered }}"
                                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error("items.{$item->id}.quantity_returned") border-red-500 @enderror">
                                    @error("items.{$item->id}.quantity_returned")
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Maximum: {{ $item->quantity_delivered }}</p>
                                </div>

                                <div>
                                    <label for="items[{{ $item->id }}][condition_returned]" class="block text-sm font-medium text-gray-700">
                                        Return Condition *
                                    </label>
                                    <select name="items[{{ $item->id }}][condition_returned]"
                                            id="items[{{ $item->id }}][condition_returned]"
                                            required
                                            onchange="toggleConditionAlert(this, {{ $item->id }})"
                                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error("items.{$item->id}.condition_returned") border-red-500 @enderror">
                                        <option value="">Select Condition</option>
                                        <option value="good" {{ old("items.{$item->id}.condition_returned") === 'good' ? 'selected' : '' }}>
                                            Good - Ready for use
                                        </option>
                                        <option value="damaged" {{ old("items.{$item->id}.condition_returned") === 'damaged' ? 'selected' : '' }}>
                                            Damaged - Needs repair
                                        </option>
                                        <option value="lost" {{ old("items.{$item->id}.condition_returned") === 'lost' ? 'selected' : '' }}>
                                            Lost - Cannot be returned
                                        </option>
                                    </select>
                                    @error("items.{$item->id}.condition_returned")
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                    <!-- Condition alerts -->
                                    <div id="condition-alert-{{ $item->id }}" class="mt-2 hidden">
                                        <div id="damaged-alert-{{ $item->id }}" class="bg-yellow-50 border border-yellow-200 rounded-md p-3 hidden">
                                            <div class="flex">
                                                <i class="fas fa-exclamation-triangle text-yellow-400 mr-2 mt-0.5"></i>
                                                <div class="text-sm text-yellow-800">
                                                    <strong>Damaged tools</strong> will be marked for maintenance and won't be available for future loans until repaired.
                                                </div>
                                            </div>
                                        </div>
                                        <div id="lost-alert-{{ $item->id }}" class="bg-red-50 border border-red-200 rounded-md p-3 hidden">
                                            <div class="flex">
                                                <i class="fas fa-times-circle text-red-400 mr-2 mt-0.5"></i>
                                                <div class="text-sm text-red-800">
                                                    <strong>Lost tools</strong> will be permanently removed from inventory. This action cannot be undone.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="items[{{ $item->id }}][notes]" class="block text-sm font-medium text-gray-700">
                                        Notes
                                    </label>
                                    <textarea name="items[{{ $item->id }}][notes]"
                                              id="items[{{ $item->id }}][notes]"
                                              rows="3"
                                              placeholder="Additional notes about the condition or any issues..."
                                              class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error("items.{$item->id}.notes") border-red-500 @enderror">{{ old("items.{$item->id}.notes") }}</textarea>
                                    @error("items.{$item->id}.notes")
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tool location tracking -->
                            <div class="mt-4 p-3 bg-gray-50 rounded-md">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    <span><strong>Current Location:</strong> {{ $loan->classroom->name }} ({{ $loan->classroom->location }})</span>
                                    <i class="fas fa-arrow-right mx-3"></i>
                                    <span><strong>Returning to:</strong> {{ $loan->warehouse->name }} ({{ $loan->warehouse->location }})</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Summary Section -->
                <div class="mt-8 bg-gray-50 rounded-lg p-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Return Summary</h4>
                    <div id="return-summary" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600" id="good-count">0</div>
                            <div class="text-sm text-gray-500">Good Condition</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-600" id="damaged-count">0</div>
                            <div class="text-sm text-gray-500">Damaged</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600" id="lost-count">0</div>
                            <div class="text-sm text-gray-500">Lost</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600" id="total-returned">0</div>
                            <div class="text-sm text-gray-500">Total Returned</div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-8">
                    <a href="{{ route('loans.show', $loan) }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Cancel
                    </a>
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-md text-sm font-medium"
                            onclick="return confirm('Are you sure you want to process this return? This action cannot be undone.')">
                        <i class="fas fa-check mr-2"></i>Process Return
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleConditionAlert(select, itemId) {
    const alertContainer = document.getElementById(`condition-alert-${itemId}`);
    const damagedAlert = document.getElementById(`damaged-alert-${itemId}`);
    const lostAlert = document.getElementById(`lost-alert-${itemId}`);

    // Hide all alerts first
    alertContainer.classList.add('hidden');
    damagedAlert.classList.add('hidden');
    lostAlert.classList.add('hidden');

    if (select.value === 'damaged') {
        alertContainer.classList.remove('hidden');
        damagedAlert.classList.remove('hidden');
    } else if (select.value === 'lost') {
        alertContainer.classList.remove('hidden');
        lostAlert.classList.remove('hidden');
    }

    updateSummary();
}

function updateSummary() {
    let goodCount = 0;
    let damagedCount = 0;
    let lostCount = 0;
    let totalReturned = 0;

    // Get all condition selects
    const conditionSelects = document.querySelectorAll('select[name*="[condition_returned]"]');
    const quantityInputs = document.querySelectorAll('input[name*="[quantity_returned]"]');

    conditionSelects.forEach((select, index) => {
        const quantity = parseInt(quantityInputs[index].value) || 0;
        totalReturned += quantity;

        switch (select.value) {
            case 'good':
                goodCount += quantity;
                break;
            case 'damaged':
                damagedCount += quantity;
                break;
            case 'lost':
                lostCount += quantity;
                break;
        }
    });

    document.getElementById('good-count').textContent = goodCount;
    document.getElementById('damaged-count').textContent = damagedCount;
    document.getElementById('lost-count').textContent = lostCount;
    document.getElementById('total-returned').textContent = totalReturned;
}

// Add event listeners to quantity inputs
document.addEventListener('DOMContentLoaded', function() {
    const quantityInputs = document.querySelectorAll('input[name*="[quantity_returned]"]');
    const conditionSelects = document.querySelectorAll('select[name*="[condition_returned]"]');

    quantityInputs.forEach(input => {
        input.addEventListener('input', updateSummary);
    });

    conditionSelects.forEach(select => {
        select.addEventListener('change', updateSummary);
    });

    // Initial summary update
    updateSummary();
});
</script>
@endsection
