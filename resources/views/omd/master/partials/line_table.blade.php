<table class="table">

    <thead>

        <tr>

            <th width="80">
                No
            </th>

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
            <tr>

                <td>
                    {{ $loop->iteration }}
                </td>

                <td>

                    <strong>
                        {{ $line->name }}
                    </strong>

                </td>

                <td>

                    <div class="action-group">

                        <button type="button" class="btn btn-warning btn-sm"
                            onclick="editLine(
                                {{ $line->id }},
                                '{{ addslashes($line->name) }}',
                                {{ $selectedPlant?->id ?? 'null' }}
                            )">
                            Edit
                        </button>


                        <form method="POST" action="{{ route('omd.master.line.destroy', $line->id) }}"
                            class="delete-line-form" style="display:inline">

                            @csrf

                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm">
                                Hapus
                            </button>

                        </form>

                    </div>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="3" class="text-center">
                    Belum ada Line
                </td>

            </tr>
        @endforelse

    </tbody>

</table>
