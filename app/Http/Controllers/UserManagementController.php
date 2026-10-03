<?php

namespace App\Http\Controllers;

use App\Models\Line;
use App\Models\Plant;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserManagementController extends Controller
{
    /**
     * Menampilkan seluruh akun User dan OMD.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'line_id' => ['nullable', 'integer', 'exists:lines,id'],
            'per_page' => ['nullable', 'integer', 'in:10,25,50,100'],
        ]);

        $search = trim((string) ($validated['search'] ?? ''));
        $lineId = isset($validated['line_id']) ? (int) $validated['line_id'] : null;
        $perPage = (int) ($validated['per_page'] ?? 10);

        $users = User::query()
            ->whereIn('role', ['user', 'omd'])
            ->with('line.plant')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('role', 'like', '%' . $search . '%')
                        ->orWhereHas('line', function ($lineQuery) use ($search) {
                            $lineQuery
                                ->where('name', 'like', '%' . $search . '%')
                                ->orWhereHas('plant', function ($plantQuery) use ($search) {
                                    $plantQuery->where('name', 'like', '%' . $search . '%');
                                });
                        });
                });
            })
            ->when($lineId, fn ($query) => $query->where('line_id', $lineId))
            ->orderByRaw("CASE WHEN role = 'omd' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        $lines = Line::query()
            ->where('is_active', true)
            ->with('plant')
            ->orderBy('name')
            ->get();

        return view('omd.users.index', compact('users', 'lines', 'search', 'lineId', 'perPage'));
    }

    /**
     * Form tambah akun.
     */
    public function create()
    {
        $plants = $this->activePlants();

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
                Rule::requiredIf(fn () => $request->input('role') === 'user'),
                'nullable',
                'integer',
                'exists:plants,id',
            ],
            'line_id' => [
                Rule::requiredIf(fn () => $request->input('role') === 'user'),
                'nullable',
                'integer',
                'exists:lines,id',
            ],
            'password' => [
                'nullable',
                'string',
                'min:4',
                'confirmed',
            ],
        ]);

        $lineId = $this->resolveLineId($validated);

        User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make(
                filled($validated['password'] ?? null)
                    ? $validated['password']
                    : $this->defaultPasswordForRole($validated['role'])
            ),
            'role' => $validated['role'],
            'line_id' => $lineId,
        ]);

        return redirect()
            ->route('omd.users.index')
            ->with('account_success', 'Akun berhasil ditambahkan dan dapat digunakan untuk login.');
    }

    /**
     * Form edit akun.
     */
    public function edit(User $user)
    {
        $this->validateManagedAccount($user);

        $user->load('line.plant');
        $plants = $this->activePlants();

        return view('omd.users.edit', compact('user', 'plants'));
    }

    /**
     * Update akun.
     */
    public function update(Request $request, User $user)
    {
        $this->validateManagedAccount($user);

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
            'role' => [
                'required',
                Rule::in(['user', 'omd']),
            ],
            'plant_id' => [
                Rule::requiredIf(fn () => $request->input('role') === 'user'),
                'nullable',
                'integer',
                'exists:plants,id',
            ],
            'line_id' => [
                Rule::requiredIf(fn () => $request->input('role') === 'user'),
                'nullable',
                'integer',
                'exists:lines,id',
            ],
            'password' => [
                'nullable',
                'string',
                'min:4',
                'confirmed',
            ],
        ]);

        if ($user->is($request->user()) && $validated['role'] !== 'omd') {
            return back()
                ->withInput()
                ->withErrors([
                    'role' => 'Akun OMD yang sedang digunakan tidak dapat diubah menjadi User.',
                ]);
        }

        $lineId = $this->resolveLineId($validated);

        $user->name = trim($validated['name']);
        $user->email = strtolower(trim($validated['email']));
        $user->role = $validated['role'];
        $user->line_id = $lineId;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('omd.users.index')
            ->with('account_success', 'Akun berhasil diperbarui.');
    }

    /**
     * Reset password ke password default sistem.
     */
    public function resetPassword(User $user)
    {
        $this->validateManagedAccount($user);

        $defaultPassword = $this->defaultPasswordForRole($user->role);

        $user->password = Hash::make($defaultPassword);
        $user->save();

        return redirect()
            ->route('omd.users.index')
            ->with(
                'account_success',
                'Password akun berhasil direset ke password default untuk role ' . strtoupper($user->role) . '.'
            );
    }

    /**
     * Hapus akun.
     */
    public function destroy(Request $request, User $user)
    {
        $this->validateManagedAccount($user);

        if ($user->is($request->user())) {
            return redirect()
                ->route('omd.users.index')
                ->withErrors([
                    'account_error' => 'Akun OMD yang sedang digunakan tidak dapat dihapus.',
                ]);
        }

        $user->delete();

        return redirect()
            ->route('omd.users.index')
            ->with('account_success', 'Akun berhasil dihapus.');
    }


    /**
     * Password default mengikuti nilai yang sama dengan DatabaseSeeder.
     */
    private function defaultPasswordForRole(string $role): string
    {
        return $role === 'omd'
            ? DatabaseSeeder::DEFAULT_OMD_PASSWORD
            : DatabaseSeeder::DEFAULT_USER_PASSWORD;
    }

    /**
     * Resolve Line untuk User. OMD tidak terikat Plant/Line.
     */
    private function resolveLineId(array $validated): ?int
    {
        if ($validated['role'] === 'omd') {
            return null;
        }

        $line = Line::query()
            ->where('id', $validated['line_id'])
            ->where('plant_id', $validated['plant_id'])
            ->where('is_active', true)
            ->first();

        if (!$line) {
            throw ValidationException::withMessages([
                'line_id' => 'Line tidak sesuai dengan Plant yang dipilih.',
            ]);
        }

        return $line->id;
    }

    /**
     * Plant dan Line aktif untuk form akun User.
     */
    private function activePlants()
    {
        return Plant::where('is_active', true)
            ->with([
                'lines' => function ($query) {
                    $query->where('is_active', true)
                        ->orderBy('name');
                },
            ])
            ->orderBy('name')
            ->get();
    }

    /**
     * Batasi manajemen akun pada role aplikasi yang didukung.
     */
    private function validateManagedAccount(User $user): void
    {
        abort_unless(
            in_array($user->role, ['user', 'omd'], true),
            404
        );
    }
}
