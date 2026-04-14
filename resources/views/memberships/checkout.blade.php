@extends('layouts.learning-center', ['title' => 'Membership Checkout'])

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Membership Checkout</h2>
            <p class="mt-2 text-gray-600">Confirm your membership details and complete payment.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-md p-6 border border-slate-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Billing Information</h3>

                    <form method="POST" action="{{ route('checkout.process') }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                @error('name')
                                    <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                @error('email')
                                    <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="pt-2">
                                <label class="inline-flex items-center gap-2 text-sm text-gray-600">
                                    <input type="checkbox" name="terms_accepted" value="1" class="rounded" required>
                                    I agree to the terms and conditions
                                </label>
                                @error('terms_accepted')
                                    <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full py-3 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 transition">
                                Pay {{ $membershipItems->first()?->membershipPlan?->currency ?? 'EUR' }} {{ number_format($cart->total_amount ?? 0, 2) }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-md p-6 border border-slate-200 sticky top-24">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                    <div class="space-y-3">
                        @foreach($membershipItems as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ $item->membershipPlan?->name }}</span>
                                <span class="font-semibold text-gray-900">{{ $item->membershipPlan?->currency }} {{ number_format($item->price, 2) }}</span>
                            </div>
                        @endforeach

                        <div class="border-t pt-3 flex justify-between font-bold text-gray-900">
                            <span>Total</span>
                            <span>{{ $membershipItems->first()?->membershipPlan?->currency ?? 'EUR' }} {{ number_format($cart->total_amount ?? 0, 2) }}</span>
                        </div>
                    </div>

                    <a href="{{ route('dashboard.memberships.index') }}" class="block text-center mt-4 text-blue-900 font-semibold hover:text-blue-700">
                        Back to Plans
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
