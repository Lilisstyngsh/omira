@extends('layouts.app')


@section('header')
    Data Master Line
@endsection



@section('content')
    {{-- =====================================================
    PLANT DASHBOARD
===================================================== --}}


    <div class="card">


        <div class="card-body">


            <h4 class="fw-bold mb-4">

                Dashboard Master Line

            </h4>



            <div class="plant-dashboard">


                @foreach ($plants as $plant)
                    <div class=" plant-card
                {{ $selectedPlant && $selectedPlant->id == $plant->id ? 'active' : '' }}"
                        onclick="loadLine({{ $plant->id }}, this)">

                        <div class="plant-header">
                            <div class="plant-icon">
                                🏭
                            </div>

                            <div>
                                <h4>
                                    {{ $plant->name }}

                                </h4>

                                <p>
                                    {{ $plant->lines_count }} Line
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- =====================================================
    LINE TABLE
===================================================== --}}


    <div class="card mt-4">


        <div class="card-body">



            <div class="table-header">



                <h4 id="plantTitle">


                    {{ $selectedPlant?->name }}


                </h4>





                <button class="btn btn-primary" onclick="openAddModal()">


                    + Tambah Line


                </button>


            </div>






            <div class="table-responsive" id="lineTable">


                @include('omd.master.partials.line_table', [
                    'lines' => $lines,
                ])


            </div>



        </div>


    </div>









    {{-- =====================================================
    MODAL TAMBAH LINE
===================================================== --}}



    <div id="addModal" class="modal-overlay">


        <div class="modal-box">



            <h4 class="fw-bold mb-3">

                Tambah Line

            </h4>




            <form method="POST" action="{{ route('omd.master.line.store') }}">


                @csrf




                <input type="hidden" name="plant_id" id="addPlantId" value="{{ $selectedPlant?->id }}">






                <label class="form-label">

                    Nama Line

                </label>



                <input type="text" name="name" class="form-control" placeholder="Contoh : AS Body" required>








                <div class="modal-action">


                    <button type="button" onclick="closeAddModal()" class="btn btn-secondary">

                        Batal

                    </button>





                    <button class="btn btn-primary">

                        Simpan

                    </button>



                </div>



            </form>



        </div>



    </div>









    {{-- =====================================================
    MODAL EDIT LINE
===================================================== --}}



    <div id="editModal" class="modal-overlay">
        <div class="modal-box">
            <h4 class="fw-bold mb-3"> Edit Line</h4>
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')

                <label class="form-label">
                    Nama Line
                </label>

                <input type="text" name="name" id="editName" class="form-control" required>

                <div class="modal-action">

                    <button type="button" onclick="closeModal()" class="btn btn-secondary">
                        Batal
                    </button>
                
                    <button class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* =====================================================
               PLANT DASHBOARD
            ===================================================== */


        .plant-dashboard {


            display: flex;

            gap: 18px;

            overflow-x: auto;

            padding: 5px;


        }



        .plant-card {


            min-width: 220px;

            background: #ffffff;

            border-radius: 18px;

            padding: 18px;


            border: 2px solid transparent;


            box-shadow:
                0 8px 20px rgba(0, 0, 0, .08);


            cursor: pointer;


            transition: .25s;


        }



        .plant-card:hover {


            transform: translateY(-4px);


        }




        .plant-card.active {


            border-color: #7c3aed;


            background: #faf5ff;


        }




        .plant-header {


            display: flex;


            align-items: center;


            gap: 14px;


        }




        .plant-icon {


            width: 55px;


            height: 55px;


            border-radius: 15px;


            background: #ede9fe;


            display: flex;


            align-items: center;


            justify-content: center;


            font-size: 26px;


        }





        .plant-card h4 {


            margin: 0;


            font-size: 18px;


            font-weight: 800;


        }




        .plant-card p {


            margin: 4px 0 0;


            font-size: 13px;


            color: #64748b;


        }









        /* =====================================================
               TABLE HEADER
            ===================================================== */


        .table-header {


            display: flex;


            justify-content: space-between;


            align-items: center;


            margin-bottom: 15px;


        }









        /* =====================================================
               TABLE RESPONSIVE
            ===================================================== */


        .table-responsive {


            border-radius: 16px;


            overflow-x: auto;


        }



        #lineTable table {


            width: 100%;


            table-layout: fixed;


        }



        #lineTable th,
        #lineTable td {


            vertical-align: middle;


        }





        #lineTable th:nth-child(1),
        #lineTable td:nth-child(1) {


            width: 80px;


            text-align: center;


        }




        #lineTable th:nth-child(2),
        #lineTable td:nth-child(2) {


            width: auto;


        }





        #lineTable th:nth-child(3),
        #lineTable td:nth-child(3) {


            width: 220px;


        }





        .table tbody tr {


            height: 65px;


        }





        .table tbody tr:hover {


            background: #faf5ff;


        }









        /* =====================================================
               ACTION BUTTON
            ===================================================== */


        .action-group {


            display: flex;


            align-items: center;


            gap: 10px;


        }




        .action-group form {


            margin: 0;


        }




        .action-group .btn {


            min-width: 75px;


            height: 40px;


            border-radius: 12px;


            font-weight: 700;


        }









        /* =====================================================
               MODAL
            ===================================================== */


        .modal-overlay {


            display: none;


            position: fixed;


            inset: 0;


            background: rgba(0, 0, 0, .45);


            z-index: 9999;


            align-items: center;


            justify-content: center;


        }





        .modal-box {


            background: white;


            width: 400px;


            border-radius: 18px;


            padding: 25px;


            box-shadow:
                0 20px 50px rgba(0, 0, 0, .15);


        }





        .modal-box input {


            height: 42px;


            border-radius: 10px;


        }





        .modal-action {


            margin-top: 20px;


            display: flex;


            justify-content: flex-end;


            gap: 10px;


        }









        /* =====================================================
               SWEETALERT STYLE LIKE PLANT
            ===================================================== */


        .swal-modern {


            border-radius: 18px !important;


            width: 380px !important;


            padding: 25px !important;


        }





        .swal2-title {


            font-size: 22px !important;


            font-weight: 800 !important;


        }





        .swal2-html-container {


            font-size: 14px !important;


            color: #64748b !important;


        }





        .swal2-icon {


            transform: scale(.85);


        }





        .swal2-actions {


            margin-top: 15px !important;


        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        /* =====================================================
           LOAD LINE BY PLANT
        ===================================================== */


        function loadLine(id, element) {


            document
                .querySelectorAll('.plant-card')
                .forEach(card => {

                    card.classList.remove('active');

                });



            element.classList.add('active');



            // simpan plant aktif untuk tambah line

            document.getElementById(
                'addPlantId'
            ).value = id;





            fetch(
                    "{{ url('/omd/master/line/by-plant') }}/" + id
                )


                .then(response => response.json())


                .then(data => {



                    document.getElementById(
                        'plantTitle'
                    ).innerHTML = data.plant;





                    let html = `


        <table class="table">


        <thead>

        <tr>


            <th>
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


        `;



                    data.lines.forEach((line, index) => {


                        html += `


            <tr>



                <td>
                    ${index+1}
                </td>



                <td>
                    ${line.name}
                </td>





                <td>


                    <div class="action-group">





                    <button
                    class="btn btn-warning btn-sm"
                    onclick="editLine(
                        ${line.id},
                        '${line.name}'
                    )">


                        Edit


                    </button>







                    <form
                    class="delete-line-form"
                    method="POST"
                    action="/omd/master/line/${line.id}">


                        <input type="hidden"
                        name="_token"
                        value="{{ csrf_token() }}">



                        <input type="hidden"
                        name="_method"
                        value="DELETE">






                        <button
                        type="submit"
                        class="btn btn-danger btn-sm">


                            Hapus


                        </button>





                    </form>





                    </div>



                </td>



            </tr>



            `;



                    });






                    html += `


        </tbody>


        </table>


        `;





                    document.getElementById(
                        'lineTable'
                    ).innerHTML = html;




                });


        }









        /* =====================================================
           EDIT LINE
        ===================================================== */


        function editLine(id, name) {


            document.getElementById(
                'editModal'
            ).style.display = 'flex';





            document.getElementById(
                'editName'
            ).value = name;





            document.getElementById(
                    'editForm'
                ).action =
                "/omd/master/line/" + id;



        }







        function closeModal() {


            document.getElementById(
                'editModal'
            ).style.display = 'none';


        }









        /* =====================================================
           ADD MODAL
        ===================================================== */


        function openAddModal() {


            document.getElementById(
                'addModal'
            ).style.display = 'flex';


        }






        function closeAddModal() {


            document.getElementById(
                'addModal'
            ).style.display = 'none';


        }









        /* =====================================================
       SWEETALERT DELETE
    ===================================================== */


        document.addEventListener(
            'submit',
            function(e) {



                if (
                    e.target.classList.contains('delete-line-form')
                ) {


                    e.preventDefault();



                    let form = e.target;





                    Swal.fire({


                            title: 'Hapus Line?',



                            text: 'Apakah Anda yakin ingin menghapus line ini?',



                            icon: 'warning',



                            width: 380,



                            padding: '1.5rem',



                            showCancelButton: true,



                            confirmButtonText: 'Ya, Hapus',



                            cancelButtonText: 'Batal',




                            reverseButtons: true,



                            customClass: {



                                popup: 'swal-modern',



                                confirmButton: 'btn btn-danger mx-2',



                                cancelButton: 'btn btn-secondary mx-2'


                            },



                            buttonsStyling: false




                        })



                        .then((result) => {


                            if (result.isConfirmed) {


                                form.submit();


                            }


                        });



                }



            });
        /* =====================================================
           SUCCESS MESSAGE
        ===================================================== */


        @if (session('success'))


            Swal.fire({


                icon: 'success',


                title: 'Berhasil',


                text: "{{ session('success') }}",


                width: 380,


                timer: 1500,


                showConfirmButton: false,


                customClass: {


                    popup: 'swal-modern'


                }


            });
        @endif







        @if ($errors->any())


            Swal.fire({


                icon: 'error',


                title: 'Gagal',


                text: "{{ $errors->first() }}",


                width: 380,


                customClass: {


                    popup: 'swal-modern'


                }


            });
        @endif
    </script>
@endsection
