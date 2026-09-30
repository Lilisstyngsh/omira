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
     * Menampilkan daftar seluruh akun.
     */
    public function index()
    {
        $users = User::with('line.plant')
            ->orderBy('role')
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

            'role' => [
                'required',
                Rule::in(['user', 'omd']),
            ],

            'plant_id' => [
                'nullable',
                'integer',
                'exists:plants,id',
                Rule::requiredIf(fn() => $request->role === 'user'),
            ],

            'line_id' => [
                'nullable',
                'integer',
                'exists:lines,id',
                Rule::requiredIf(fn() => $request->role === 'user'),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $lineId = null;

        // Plant & Line hanya diperlukan untuk role User
        if ($validated['role'] === 'user') {
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

            $lineId = $line->id;
        }

        $password = !empty($validated['password'])
            ? $validated['password']
            : self::DEFAULT_PASSWORD;

        User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($password),
            'role' => $validated['role'],
            'line_id' => $lineId,
        ]);

        return redirect()
            ->route('omd.users.index')
            ->with(
                'account_success',
                'Akun berhasil ditambahkan.'
            );
    }

    /**
     * Form edit akun.
     */
    public function edit(User $user)
    {
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
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'role' => [
                'required',
                Rule::in(['user', 'omd']),
            ],

            'plant_id' => [
                'nullable',
                'integer',
                'exists:plants,id',
                Rule::requiredIf(fn() => $request->role === 'user'),
            ],

            'line_id' => [
                'nullable',
                'integer',
                'exists:lines,id',
                Rule::requiredIf(fn() => $request->role === 'user'),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $lineId = null;

        // Plant & Line hanya diperlukan untuk role User
        if ($validated['role'] === 'user') {
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

            $lineId = $line->id;
        }

        $user->name = trim($validated['name']);
        $user->email = strtolower(trim($validated['email']));
        $user->role = $validated['role'];
        $user->line_id = $lineId;

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
                'Akun berhasil diperbarui.'
            );
    }

    /**
     * Reset password ke password default sistem.
     */
    public function resetPassword(User $user)
    {
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
        $user->delete();

        return redirect()
            ->route('omd.users.index')
            ->with(
                'account_success',
                'Akun berhasil dihapus.'
            );
    }
}
