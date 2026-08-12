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
     * Admin melihat semua; Sales/Partner hanya melihat customer miliknya.
     */
    public function index()
    {
        $user = Auth::user();

        $customers = Customer::with(['user', 'lead'])
            ->when($user->role !== 'admin', function ($query) use ($user) {
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
        $user = Auth::user();

        // Hanya Admin yang butuh daftar sales untuk assign pemilik customer
        $sales = $user->role === 'admin'
            ? User::where('role', 'sales')->orderBy('name')->get()
            : collect();

        // Lead yang belum punya customer, diisolasi sesuai role (batasi jumlah)
        $leads = Lead::doesntHave('customer')
            ->when($user->role !== 'admin', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->limit(50)
            ->get();

        return view('customers.create', compact('sales', 'leads'));
    }

    /**
     * Simpan customer baru.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'user_id' => $user->role === 'admin' ? 'required|exists:users,id' : 'nullable',
            'lead_id' => 'nullable|exists:leads,id',
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive,churned',
            'total_lifetime_value' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Mencegah manipulasi user_id oleh non-Admin
        if ($user->role !== 'admin') {
            $validated['user_id'] = $user->id;
        }

        // Validasi opsional: Jika lead_id diisi oleh Non-Admin, pastikan lead tersebut milik dia
        if (! empty($validated['lead_id']) && $user->role !== 'admin') {
            $lead = Lead::find($validated['lead_id']);
            if (! $lead || $lead->user_id !== $user->id) {
                abort(403, 'Anda tidak berhak menghubungkan Lead ini.');
            }
        }

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

        $user = Auth::user();

        $sales = $user->role === 'admin'
            ? User::where('role', 'sales')->orderBy('name')->get()
            : collect();

        $leads = Lead::where(function ($query) use ($customer) {
            $query->doesntHave('customer')
                ->orWhere('id', $customer->lead_id);
        })
            ->when($user->role !== 'admin', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->limit(50)
            ->get();

        return view('customers.edit', compact('customer', 'sales', 'leads'));
    }

    /**
     * Update customer.
     */
    public function update(Request $request, Customer $customer)
    {
        $this->authorizeAccess($customer);

        $user = Auth::user();

        $validated = $request->validate([
            'user_id' => $user->role === 'admin' ? 'required|exists:users,id' : 'nullable',
            'lead_id' => 'nullable|exists:leads,id',
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive,churned',
            'total_lifetime_value' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($user->role !== 'admin') {
            $validated['user_id'] = $customer->user_id; // Kunci agar tidak merubah pemilik asli
        }

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
     * Otorisasi: Selain Admin, user hanya boleh mengelola customer miliknya sendiri.
     */
    private function authorizeAccess(Customer $customer): void
    {
        if (Auth::user()->role !== 'admin' && $customer->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke data Customer ini.');
        }
    }
}
