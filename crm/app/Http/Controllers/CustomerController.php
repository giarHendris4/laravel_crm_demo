<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Tampilkan daftar customer.
     * Admin melihat semua; Sales hanya melihat customer miliknya.
     */
    public function index()
    {
        $user = Auth::user();

        $customers = Customer::with(['user', 'lead'])
            ->when($user->role === 'sales', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->paginate(10);

        return view('customers.index', compact('customers'));
    }

    /**
     * Form tambah customer baru.
     */
    public function create()
    {
        // Daftar sales sebagai kandidat pemilik (hanya untuk admin)
        $sales = User::where('role', 'sales')->orderBy('name')->get();

        // Lead yang belum punya customer (untuk tracing asal-usul)
        $leads = Lead::doesntHave('customer')->latest()->get();

        return view('customers.create', compact('sales', 'leads'));
    }

    /**
     * Simpan customer baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'              => 'required|exists:users,id',
            'lead_id'              => 'nullable|exists:leads,id',
            'company_name'         => 'required|string|max:255',
            'contact_name'         => 'required|string|max:255',
            'email'                => 'nullable|email|max:255',
            'phone'                => 'nullable|string|max:50',
            'address'              => 'nullable|string|max:1000',
            'status'               => 'required|in:active,inactive,churned',
            'total_lifetime_value' => 'required|numeric|min:0',
            'notes'                => 'nullable|string',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer berhasil ditambahkan!');
    }

    /**
     * Detail customer.
     */
    public function show(Customer $customer)
    {
        $this->authorizeAccess($customer);

        $customer->load(['user', 'lead']);

        return view('customers.show', compact('customer'));
    }

    /**
     * Form edit customer.
     */
    public function edit(Customer $customer)
    {
        $this->authorizeAccess($customer);

        $sales = User::where('role', 'sales')->orderBy('name')->get();
        $leads = Lead::doesntHave('customer')
            ->orWhere('id', $customer->lead_id)
            ->latest()
            ->get();

        return view('customers.edit', compact('customer', 'sales', 'leads'));
    }

    /**
     * Update customer.
     */
    public function update(Request $request, Customer $customer)
    {
        $this->authorizeAccess($customer);

        $validated = $request->validate([
            'user_id'              => 'required|exists:users,id',
            'lead_id'              => 'nullable|exists:leads,id',
            'company_name'         => 'required|string|max:255',
            'contact_name'         => 'required|string|max:255',
            'email'                => 'nullable|email|max:255',
            'phone'                => 'nullable|string|max:50',
            'address'              => 'nullable|string|max:1000',
            'status'               => 'required|in:active,inactive,churned',
            'total_lifetime_value' => 'required|numeric|min:0',
            'notes'                => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer berhasil diperbarui!');
    }

    /**
     * Hapus customer.
     */
    public function destroy(Customer $customer)
    {
        $this->authorizeAccess($customer);

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer berhasil dihapus!');
    }

    /**
     * Otorisasi sederhana: Sales hanya bisa mengakses customer miliknya.
     * Admin bebas mengakses semua.
     */
    private function authorizeAccess(Customer $customer): void
    {
        if (Auth::user()->role === 'sales' && $customer->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
