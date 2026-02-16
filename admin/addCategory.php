<?php
$title = 'Курсы';
$headerName = 'добавитьКатегорию';
require_once 'header.php';
require_once 'headerAdditionally.php';

$categories = $pdo->query('SELECT * FROM `courses_categories`');
?>


            <div class="shadow containerr">
                <div class="boxx">

                    <form class="row g-3" method="post" action="../scripts/addCategory.php">
                        <div class="col-md-6">
                            <label class="form-label">Название</label>
                            <input type="text" class="form-control" name="title">
                        </div>
                        <div class="col-6 justify-content-end d-flex align-items-end">
                            <button type="submit" class="btn btn-primarys px-4">Создать</button>
                        </div>
                    </form>

                    <div class="mt-5 category">
                        <div class="header mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <h3>Категории</h3>
                            </div>
                            <div class="line"></div>
                        </div>

                        <div class="content">
                            <table class="table align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>id</th>
                                        <th>Название</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?foreach($categories as $category):?>
                                        <tr>
                                            <td><?=  $category['id']?></td>
                                            <td><?= $category['title'] ?></td>
                                        </tr>
                                    <?endforeach?>
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
