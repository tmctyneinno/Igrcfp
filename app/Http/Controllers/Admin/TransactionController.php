<?php
// app/Http/Controllers/Admin/TransactionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'enrollment.course'])
            ->latest()
            ->paginate(15);
            
        $summary = [
            'total_revenue' => Transaction::where('status', 'completed')->sum('amount'),
            'total_transactions' => Transaction::count(),
            'pending_transactions' => Transaction::where('status', 'pending')->count(),
            'completed_transactions' => Transaction::where('status', 'completed')->count(),
        ];
            
        return view('admin.transactions.index', compact('transactions', 'summary'));
    }

    public function pending()
    {
        $transactions = Transaction::with(['user', 'enrollment.course'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);
            
        return view('admin.transactions.pending', compact('transactions'));
    }

    public function completed(Request $request)
    {
        $query = Transaction::with(['user', 'enrollment.course'])
            ->where('status', 'completed');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                ->orWhere('reference', 'like', "%{$search}%")
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // Apply payment method filter
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $transactions = $query->latest()->paginate($request->per_page ?? 15);

        // Calculate summary data
        $totalRevenue = Transaction::where('status', 'completed')->sum('amount');
        $averageAmount = $transactions->total() > 0 
            ? $totalRevenue / $transactions->total() 
            : 0;

        return view('admin.transactions.completed', compact(
            'transactions', 
            'totalRevenue', 
            'averageAmount'
        ));
    }

    public function failed()
    {
        $transactions = Transaction::with(['user', 'enrollment.course'])
            ->where('status', 'failed')
            ->latest()
            ->paginate(15);
            
        return view('admin.transactions.failed', compact('transactions'));
    }

    public function refunded()
    {
        $transactions = Transaction::with(['user', 'enrollment.course'])
            ->where('status', 'refunded')
            ->latest()
            ->paginate(15);
            
        return view('admin.transactions.refunded', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'enrollment.course']);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed,refunded'
        ]);

        $transaction->update(['status' => $request->status]);
        
        // Update enrollment status if needed
        if ($transaction->enrollment) {
            $enrollmentStatus = $request->status === 'completed' ? 'completed' : 'pending';
            $transaction->enrollment->update(['status' => $enrollmentStatus]);
        }
        
        return redirect()->back()->with('success', 'Transaction status updated successfully.');
    }
}