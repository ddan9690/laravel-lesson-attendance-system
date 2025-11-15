@extends('layouts.app')

@section('title', 'Capture Remedial Payment')

@section('content')
    <div class="max-w-2xl mx-auto p-6 bg-white shadow rounded" x-data="paymentForm()">

        {{-- Student Search --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Search Student by ADM</label>
            <input type="text" placeholder="Enter ADM" x-model="adm" @input.debounce.500="searchStudent"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-green-200">
            <template x-if="student">
                <div class="mt-2 p-2 border rounded bg-gray-50 text-sm">
                    <strong x-text="student.name"></strong> |
                    ADM: <span x-text="student.adm"></span> |
                    <span x-text="student.grade"></span> |
                    Stream: <span x-text="student.stream"></span>
                </div>
            </template>
            <template x-if="error">
                <div class="mt-2 p-2 bg-red-100 text-red-700 text-sm rounded" x-text="error"></div>
            </template>
        </div>

        {{-- Payment Form --}}
        <template x-if="student">
            <form method="POST" action="{{ route('remedial.payments.store') }}" @submit.prevent="confirmPayment">
                @csrf
                <input type="hidden" name="student_id" :value="student.id">

                {{-- Amount --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Amount (Ksh)</label>
                    <input type="number" name="amount" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-green-200">
                </div>

                {{-- Payment Type --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Payment Type</label>
                    <select name="payment_type" x-model="paymentType"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-green-200">
                        <option value="cash">Cash</option>
                        <option value="mpesa">M-Pesa</option>
                    </select>
                </div>

                {{-- M-Pesa Transaction --}}
                <div class="mb-4" x-show="paymentType === 'mpesa'" x-cloak>
                    <label class="block text-gray-700 font-medium mb-1">
                        M-Pesa Transaction Number
                    </label>
                    <input type="text" name="mpesa_transaction_number" x-model="mpesa"
                        @input="mpesa = mpesa.toUpperCase()" placeholder="e.g., TKERDA5TD2"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-green-200">
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-3 mt-6">
                    <button type="submit" :disabled="submitting"
                        class="bg-school-green text-white px-6 py-2 rounded hover:bg-green-700 disabled:opacity-50"
                        x-text="submitting ? 'Submitting...' : 'Submit Payment'">
                    </button>
                    <a href="{{ route('payments.index') }}"
                        class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">Cancel</a>
                </div>
            </form>
        </template>

    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function paymentForm() {
            return {
                adm: '',
                student: null,
                error: null,
                paymentType: 'cash',
                mpesa: '',
                submitting: false,

                searchStudent() {
                    if (this.adm.length < 2) {
                        this.student = null;
                        this.error = null;
                        return;
                    }
                    fetch(`{{ route('remedial.payments.searchStudent') }}?adm=${this.adm}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.id) {
                                this.student = data;
                                this.error = null;
                            } else {
                                this.student = null;
                                this.error = 'Student not found';
                            }
                        })
                        .catch(() => {
                            this.student = null;
                            this.error = 'Student not found';
                        });
                },

                confirmPayment(event) {
                    const amount = event.target.amount.value;
                    Swal.fire({
                        title: 'Confirm Payment',
                        html: `
                           
                            <strong>Name:</strong> ${this.student.name}<br>
                            <strong>ADM:</strong> ${this.student.adm}<br>
                            <strong> ${this.student.grade}<br></strong>
                            <strong>Stream:</strong> ${this.student.stream}<br>
                            <strong>Amount:</strong> Ksh ${amount}
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Submit',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submitPayment(event);
                        }
                    });
                },


                async submitPayment(event) {
                    this.submitting = true;
                    const form = event.target;
                    const formData = new FormData(form);

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': formData.get('_token'),
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            Swal.fire('Error', errorData.message || 'Failed to submit payment', 'error');
                            this.submitting = false;
                            return;
                        }

                        const data = await response.json();
                        Swal.fire('Success', data.message || 'Payment captured successfully', 'success')
                            .then(() => {
                                window.location.href = "{{ route('payments.index') }}";
                            });

                    } catch (err) {
                        Swal.fire('Error', 'An unexpected error occurred. Please try again.', 'error');
                        this.submitting = false;
                    }
                }
            }
        }
    </script>
@endsection
