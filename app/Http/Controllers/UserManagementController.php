<?php

namespace App\Http\Controllers;

use App\Models\Line;
use App\Models\Plant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    private const DEFAULT_PASSWORD = 'Aiia@2026';

    /**
     * Menampilkan daftar akun user.
     */
    public function index()
    {
        $users = User::where('role', 'user')
            ->with('line.plant')
            ->orderBy('name')
            ->get();

        return view('omd.users.index', compact('users'));
    }

    /**
     * Form tambah akun.
     */
    public function create()
    {
        $plants = Plant::where('is_active', true)
            ->with([
                'lines' => function ($query) {
                    $query->where('is_active', true)
                        ->orderBy('name');
                }
            ])
            ->orderBy('name')
            ->get();

        return view('omd.users.create', compact('plants'));
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

            'plant_id' => [
                'required',
                'integer',
                'exists:plants,id',
            ],

            'line_id' => [
                'required',
                'integer',
                'exists:lines,id',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $line = Line::where('id', $validated['line_id'])
            ->where('plant_id', $validated['plant_id'])
            ->where('is_active', true)
            ->first();

        if (!$line) {
            return back()
                ->withInput()
                ->withErrors([
                    'line_id' => 'Line tidak sesuai dengan Plant yang dipilih.',
                ]);
        }

        $password = !empty($validated['password'])
            ? $validated['password']
            : self::DEFAULT_PASSWORD;

        User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($password),
            'role' => 'user',
            'line_id' => $line->id,
        ]);

        return redirect()
            ->route('omd.users.index')
            ->with(
                'account_success',
                'Akun user berhasil ditambahkan.'
            );
    }

    /**
     * Form edit akun.
     */
    public function edit(User $user)
    {
        $this->validateUser($user);

        $user->load('line.plant');

        $plants = Plant::where('is_active', true)
            ->with([
                'lines' => function ($query) {
                    $query->where('is_active', true)
                        ->orderBy('name');
                }
            ])
            ->orderBy('name')
            ->get();

        return view(
            'omd.users.edit',
            compact('user', 'plants')
        );
    }

    /**
     * Update akun.
     */
    public function update(Request $request, User $user)
    {
        $this->validateUser($user);

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
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'plant_id' => [
                'required',
                'integer',
                'exists:plants,id',
            ],

            'line_id' => [
                'required',
                'integer',
                'exists:lines,id',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $line = Line::where('id', $validated['line_id'])
            ->where('plant_id', $validated['plant_id'])
            ->where('is_active', true)
            ->first();

        if (!$line) {
            return back()
                ->withInput()
                ->withErrors([
                    'line_id' => 'Line tidak sesuai dengan Plant yang dipilih.',
                ]);
        }

        $user->name = trim($validated['name']);
        $user->email = strtolower(trim($validated['email']));
        $user->line_id = $line->id;

        if (!empty($validated['password'])) {
            $user->password = Hash::make(
                $validated['password']
            );
        }

        $user->save();

        return redirect()
            ->route('omd.users.index')
            ->with(
                'account_success',
                'Akun user berhasil diperbarui.'
            );
    }

    /**
     * Reset password ke password default sistem.
     */
    public function resetPassword(User $user)
    {
        $this->validateUser($user);

        $user->password = Hash::make(
            self::DEFAULT_PASSWORD
        );

        $user->save();

        return redirect()
            ->route('omd.users.index')
            ->with(
                'account_success',
                'Password akun berhasil direset ke password default sistem.'
            );
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
            ->with(
                'account_success',
                'Akun user berhasil dihapus.'
            );
    }

    /**
     * Pastikan akun yang dikelola adalah akun user.
     */
    private function validateUser(User $user): void
    {
        abort_unless(
            $user->role === 'user',
            404
        );
    }
}