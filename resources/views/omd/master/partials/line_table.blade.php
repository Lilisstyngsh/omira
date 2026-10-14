<table class="table">

    <thead>

        <tr>

            <th>
                Line
            </th>

            <th>
                Aksi
            </th>

        </tr>

    </thead>


    <tbody>

        @forelse($lines as $line)
            <tr class="editable-line-row" data-line-id="{{ $line->id }}"
                data-plant-id="{{ $line->plant_id }}" data-name="{{ $line->name }}"
                data-update-url="{{ route('omd.master.line.update', $line) }}"
                tabindex="0" aria-label="Edit nama Line {{ $line->name }}">

                <td>

                    <div class="inline-name" data-editor="line">
                        <strong data-name-display>{{ $line->name }}</strong>
                    </div>

                </td>

                <td>

                    <div class="action-group">

                        <form method="POST" action="{{ route('omd.master.line.destroy', $line->id) }}"
                            class="delete-line-form" style="display:inline">

                            @csrf

                            @method('DELETE')

                            <button type="submit" class="master-delete" title="Hapus Line" aria-label="Hapus Line">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" aria-hidden="true">
                                    <path d="m6 6 12 12M18 6 6 18" />
                                </svg>
                            </button>

                        </form>

                    </div>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="2" class="text-center">
                    Belum ada Line
                </td>

            </tr>
        @endforelse

    </tbody>

</table>
