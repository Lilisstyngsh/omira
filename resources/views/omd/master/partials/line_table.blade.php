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


                    <button class="btn btn-warning btn-sm"
                        onclick="editLine(
{{ $line->id }},
'{{ $line->name }}'
)">

                        Edit

                    </button>



                    <form method="POST" action="{{ route('omd.master.line.destroy', $line->id) }}" style="display:inline">


                        @csrf

                        @method('DELETE')


                        <button class="btn btn-danger btn-sm">

                            Hapus

                        </button>


                    </form>



                </td>


            </tr>



        @empty


            <tr>

                <td colspan="4" class="text-center">

                    Belum ada Line

                </td>

            </tr>
        @endforelse



    </tbody>


</table>
