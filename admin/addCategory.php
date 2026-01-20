<?php
$title = 'Курсы';
$headerName = 'добавитьКатегорию';
require_once 'header.php';
require_once 'headerAdditionally.php';

?>


            <div class="shadow containerr">
                <div class="boxx">

                    <form class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Название</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-6 justify-content-end d-flex align-items-end">
                            <button type="submit" class="btn btn-primary px-4">Создать</button>
                        </div>
                    </form>

                    <div class="mt-5 category">
                        <div class="header mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <h3>Категории</h3>
                                <div class="input-group search-i" style="width: 260px;">
                                    <span class="input-group-text bg-white search-input"><i class="bi bi-search"></i></span>
                                    <input type="text" class="form-control search-input" placeholder="Поиск">
                                </div>
                            </div>
                            <div class="line"></div>
                        </div>

                        <div class="content">
                            <table class="table align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-start">Курсы</th>
                                        <th>Статус</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-start">
                                            <a href="./teacher.html" class="text-decoration-none text-prymarys">
                                                Проф подготовка к демо-экзамену
                                            </a>
                                        </td>
                                        <td>
                                            <div class="circle circle-succesful"></div>Активно
                                        </td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td class="text-start">
                                            <a href="./teacher.html" class="text-decoration-none text-prymarys">
                                                Проф подготовка к демо-экзамену
                                            </a>
                                        </td>
                                        <td>
                                            <div class="circle circle-danger"></div>Активно
                                        </td>
                                        <td>
                                            <a href="#" class="text-black text-decoration-none">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </main>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../plugins/select2/js/select2.min.js"></script>
    <script>
        $('.select2').select2();
    </script>

</body>
</html>
