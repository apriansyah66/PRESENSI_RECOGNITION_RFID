<?php
$page = "Data Prodi";
require_once("./header.php");
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="page-title mt-4">➕ Tambah Data Prodi</h1>

            <div class="breadcrumb-3d">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="./data_prodi.php">📋 Data Prodi</a>
                    </li>
                    <li class="breadcrumb-item active">➕ Tambah Data Prodi</li>
                </ol>
            </div>

            <!-- START MESSAGE -->
            <div id="response">
                <?php if (isset($_GET['msg']) && $_GET['msg'] == 1): ?>
                    <div class="alert alert-success alert-dismissible fade show text-center h4" role="alert">
                        <strong>✅ Berhasil update data!</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
            <!-- END MESSAGE -->

            <div class="card-3d mb-4">
                <div class="card-header-3d">
                    <i class="fas fa-plus-square"></i>
                    Tambah Data Prodi
                </div>
                <div class="card-body-3d">
                    <form class="mb-0" action="./tambah_prodi_post.php" method="POST" id="appsform" enctype="multipart/form-data">
                        <div class="form-group-3d">
                            <div class="form-row-3d">
                                <label class="form-label-3d" for="namaProdi" style="min-width: 150px;">
                                    💼 Nama Prodi
                                </label>
                                <div class="input-group-3d" style="flex: 1;">
                                    <input type="text"
                                           class="form-control-3d with-icon"
                                           id="namaProdi"
                                           name="namaProdi"
                                           placeholder="Masukkan Nama Prodi"
                                           autocomplete="off"
                                           minlength="2"
                                           maxlength="50"
                                           required>
                                    <i class="fas fa-graduation-cap input-icon"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-buttons">
                            <button type="submit" class="btn-3d btn-primary-3d" id="submitBtn">
                                <i class="fas fa-paper-plane mr-2"></i>Submit
                            </button>
                            <button type="reset" class="btn-3d btn-danger-3d" id="resetBtn">
                                <i class="fas fa-undo mr-2"></i>Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php require_once("./footer.php"); ?>
</div>
