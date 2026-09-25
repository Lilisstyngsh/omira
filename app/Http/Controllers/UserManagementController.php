<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Menampilkan daftar akun user.
     */
    public function index()
    {
        $users = User::where('role', 'user')
            ->whereIn('user_group', ['ppic', 'produksi'])
            ->orderBy('user_group')
            ->orderBy('name')
            ->get();

        return view('omd.users.index', compact('users'));
    }

    /**
     * Form tambah akun.
     */
    public function create()
    {
        return view('omd.users.create');
    }

    /**
     * Simpan akun baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'user_group' => [
                'required',
                Rule::in(['ppic', 'produksi']),
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'user_group' => $validated['user_group'],
        ]);

        return redirect()
            ->route('omd.users.index')
            ->with('success', 'Akun user berhasil ditambahkan.');
    }

    /**
     * Form edit akun.
     */
    public function edit(User $user)
    {
        $this->validateUser($user);

        return view('omd.users.edit', compact('user'));
    }

    /**
     * Update akun.
     */
    public function update(Request $request, User $user)
    {
        $this->validateUser($user);

        $validated = $request->validate([
            'name' => [
                
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'user_group' => [
                'required',
                Rule::in(['ppic', 'produksi']),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $user->name = trim($validated['name']);
        $user->email = strtolower(trim($validated['email']));
        $user->user_group = $validated['user_group'];

        // Password hanya diubah jika diisi.
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('omd.users.index')
            ->with('success', 'Akun user berhasil diperbarui.');
    }

    /**
     * Hapus akun.
     */
    public function destroy(User $user)
    {
        $this->validateUser($user);

        $user->delete();

        return redirect()
            ->route('omd.users.index')
            ->with('success', 'Akun user berhasil dihapus.');
    }

    /**
     * Pastikan akun yang dikelola adalah akun user PPIC/Produksi.
     */
    private function validateUser(User $user): void
    {
        abort_unless(
            $user->role === 'user' &&
                in_array($user->user_group, ['ppic', 'produksi'], true),
            404
        );
    }
}
