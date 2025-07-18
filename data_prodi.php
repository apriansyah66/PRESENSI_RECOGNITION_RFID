<?php
$page = "Data Prodi";
require_once("./header.php");
?>

<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }
    
    .container-fluid {
        position: relative;
        z-index: 10;
    }
    
    .page-title {
        color: white;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        font-weight: bold;
        margin-bottom: 20px;
    }
    
    .breadcrumb-3d {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 30px;
    }
    
    .breadcrumb {
        background: transparent;
        margin: 0;
        padding: 10px 20px;
    }
    
    .breadcrumb-item {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .breadcrumb-item.active {
        color: #ffd700;
        font-weight: bold;
    }
    
    /* Alert Messages */
    .alert {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 15px;
        color: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    
    .alert-success {
        background: rgba(76, 175, 80, 0.3);
        border-color: rgba(76, 175, 80, 0.5);
    }
    
    .alert-danger {
        background: rgba(244, 67, 54, 0.3);
        border-color: rgba(244, 67, 54, 0.5);
    }
    
    /* Main Card Container */
    .card-3d {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        overflow: hidden;
    }
    
    .card-3d:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0,0,0,0.4);
    }
    
    .card-header-3d {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        font-weight: bold;
        font-size: 1.2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .card-header-3d i {
        margin-right: 10px;
        font-size: 1.3rem;
        filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.3));
    }
    
    /* Button Styles */
    .btn-3d {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 10px 20px;
        border-radius: 25px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        backdrop-filter: blur(10px);
        font-weight: 500;
        position: relative;
        overflow: hidden;
    }
    
    .btn-3d::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-3d:hover::before {
        left: 100%;
    }
    
    .btn-3d:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        text-decoration: none;
    }
    
    .btn-primary-3d {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        border-color: rgba(76, 175, 80, 0.5);
    }
    
    .btn-primary-3d:hover {
        background: linear-gradient(135deg, #45a049 0%, #4CAF50 100%);
        color: white;
    }
    
    .btn-danger-3d {
        background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
        border-color: rgba(244, 67, 54, 0.5);
    }
    
    .btn-danger-3d:hover {
        background: linear-gradient(135deg, #d32f2f 0%, #f44336 100%);
        color: white;
    }
    
    .btn-edit-3d {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        border-color: rgba(33, 150, 243, 0.5);
    }
    
    .btn-edit-3d:hover {
        background: linear-gradient(135deg, #1976D2 0%, #2196F3 100%);
        color: white;
    }
    
    /* DataTable Controls */
    .datatable-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: rgba(255, 255, 255, 0.1);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .datatable-left {
        display: flex;
        align-items: center;
        gap: 10px;
        color: white;
    }
    
    .datatable-right {
        display: flex;
        align-items: center;
        gap: 10px;
        color: white;
    }
    
    .entries-select, .search-input {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        padding: 8px 12px;
        color: white;
        backdrop-filter: blur(10px);
    }
    
    .entries-select option {
        background: #444;
        color: white;
    }
    
    .search-input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }
    
    .search-input:focus, .entries-select:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.3);
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }
    
    /* Table Styles */
    .table-responsive {
        padding: 20px;
    }
    
    .table {
        background: transparent;
        color: white;
        margin: 0;
    }
    
    .table th {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        font-weight: bold;
        text-align: center;
        padding: 15px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }
    
    .table td {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 12px;
        vertical-align: middle;
        text-align: center;
    }
    
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.01);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    
    /* Position ID Badge */
    .position-id {
        background: linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%);
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: bold;
        display: inline-block;
        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    }
    
    /* Position Name Style */
    .position-name {
        font-weight: bold;
        color: #ffd700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        font-size: 1.1rem;
    }
    
    /* Pagination */
    .datatable-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: rgba(255, 255, 255, 0.1);
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .pagination-info {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .pagination {
        display: flex;
        gap: 5px;
        margin: 0;
        list-style: none;
        padding: 0;
    }
    
    .pagination .page-item {
        display: inline-block;
    }
    
    .pagination .page-link {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 8px 12px;
        border-radius: 8px;
        transition: all 0.3s ease;
        text-decoration: none;
        backdrop-filter: blur(10px);
        cursor: pointer;
    }
    
    .pagination .page-link:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        text-decoration: none;
    }
    
    .pagination .page-item.active .page-link {
        background: rgba(255, 255, 255, 0.4);
        color: #ffd700;
        font-weight: bold;
    }
    
    .pagination .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .empty-data {
        text-align: center;
        padding: 40px;
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.1rem;
    }
    
    /* Action buttons in table */
    .table .btn-3d {
        padding: 5px 10px;
        font-size: 0.85rem;
        margin: 2px;
        border-radius: 15px;
    }
    
    /* Floating Animation */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .card-3d {
        animation: float 8s ease-in-out infinite;
    }
    
    /* Position Icon Animation */
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .position-icon {
        animation: spin 20s linear infinite;
        display: inline-block;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .card-header-3d {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .datatable-controls, .datatable-footer {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .datatable-right {
            width: 100%;
            justify-content: flex-end;
        }
        
        .table .btn-3d {
            padding: 3px 8px;
            font-size: 0.8rem;
            margin: 1px;
        }
    }
</style>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="page-title mt-4">💼 Data Prodi</h1>
            
            <div class="breadcrumb-3d">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Data Prodi</li>
                </ol>
            </div>
            
            <!-- START MESSAGE -->
            <?php
            if (isset($_GET['msg']) && isset($_SERVER['HTTP_REFERER'])) {
                if ($_GET['msg'] == 1 && $_SERVER['HTTP_REFERER']) {
            ?>
            <div class="alert alert-success alert-dismissible fade show text-center h4" role="alert">
                <strong>✅ Berhasil Menghapus Data Prodi!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php
                } else if ($_GET['msg'] == 2 && $_SERVER['HTTP_REFERER']) {
            ?>
            <div class="alert alert-danger alert-dismissible fade show text-center h4" role="alert">
                <strong>❌ Gagal Menghapus Data Prodi!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php
                }
            }
            ?>
            <!-- END MESSAGE -->
            
            <div class="card-3d mb-4">
                <div class="card-header-3d">
                    <div>
                        <i class="fas fa-briefcase position-icon"></i>
                        Data Prodi
                    </div>
                    <div>
                        <a href="./tambah_prodi.php" class="btn-3d btn-primary-3d">
                            <i class="fas fa-plus mr-2"></i>Tambah Data Prodi
                        </a>
                    </div>
                </div>
                
                <!-- DataTable Controls -->
                <div class="datatable-controls">
                    <div class="datatable-left">
                        <label>Show 
                            <select class="entries-select" id="entriesSelect">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            entries
                        </label>
                    </div>
                    <div class="datatable-right">
                        <label>Search:
                            <input type="text" class="search-input" id="searchInput" placeholder="Search positions...">
                        </label>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered" id="positionTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>🆔 ID</th>
                                <th>💼 Nama Prodi</th>
                                <th>⚙️ Action</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php
                            $sql = "SELECT * FROM `program_studi` ORDER BY `id_prodi` ASC";
                            $result = $koneksi->query($sql);
                            
                            $allData = [];
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $allData[] = $row;
                                }
                            }
                            
                            // Store data in JavaScript for client-side processing
                            echo "<script>var positionData = " . json_encode($allData) . ";</script>";
                            
                            // Display initial data
                            if (!empty($allData)) {
                                foreach (array_slice($allData, 0, 10) as $row) {
                                    $id_prodi = $row['id_prodi'];
                                    $nama_prodi = $row['nama_prodi'];
                            ?>
                            <tr>
                                <td>
                                    <span class="position-id"><?php echo $id_prodi; ?></span>
                                </td>
                                <td>
                                    <span class="position-name"><?php echo htmlspecialchars($nama_prodi); ?></span>
                                </td>
                                <td>
                                    <a href="edit_jabatan.php?jabatan_id=<?php echo $id_prodi; ?>" 
                                       class="btn-3d btn-edit-3d">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="hapus_jabatan.php?jabatan_id=<?php echo $id_prodi; ?>" 
                                       class="btn-3d btn-danger-3d" 
                                       onclick="return confirm('Apakah anda yakin?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="3" class="empty-data">📋 Tidak ada data Prodi</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- DataTable Footer -->
                <div class="datatable-footer">
                    <div class="pagination-info" id="paginationInfo">
                        Showing 1 to 10 of <?php echo count($allData); ?> entries
                    </div>
                    <nav>
                        <ul class="pagination" id="pagination">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" id="prevBtn">Previous</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#" data-page="1">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#" id="nextBtn">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </main>
    
    <script>
        // DataTable functionality for Position Data
        class PositionDataTable {
            constructor(data) {
                this.originalData = data;
                this.filteredData = [...data];
                this.currentPage = 1;
                this.entriesPerPage = 10;
                this.searchTerm = '';
                
                this.initializeEventListeners();
                this.render();
            }
            
            initializeEventListeners() {
                // Entries per page selector
                document.getElementById('entriesSelect').addEventListener('change', (e) => {
                    this.entriesPerPage = parseInt(e.target.value);
                    this.currentPage = 1;
                    this.render();
                });
                
                // Search input
                document.getElementById('searchInput').addEventListener('input', (e) => {
                    this.searchTerm = e.target.value.toLowerCase();
                    this.filterData();
                    this.currentPage = 1;
                    this.render();
                });
                
                // Pagination event delegation
                document.getElementById('pagination').addEventListener('click', (e) => {
                    e.preventDefault();
                    if (e.target.matches('.page-link')) {
                        const page = e.target.getAttribute('data-page');
                        if (page) {
                            this.currentPage = parseInt(page);
                            this.render();
                        } else if (e.target.id === 'prevBtn' && this.currentPage > 1) {
                            this.currentPage--;
                            this.render();
                        } else if (e.target.id === 'nextBtn' && this.currentPage < this.getTotalPages()) {
                            this.currentPage++;
                            this.render();
                        }
                    }
                });
            }
            
            filterData() {
                if (!this.searchTerm) {
                    this.filteredData = [...this.originalData];
                    return;
                }
                
                this.filteredData = this.originalData.filter(row => {
                    return Object.values(row).some(value => 
                        value && value.toString().toLowerCase().includes(this.searchTerm)
                    );
                });
            }
            
            getTotalPages() {
                return Math.ceil(this.filteredData.length / this.entriesPerPage);
            }
            
            getCurrentPageData() {
                const startIndex = (this.currentPage - 1) * this.entriesPerPage;
                const endIndex = startIndex + this.entriesPerPage;
                return this.filteredData.slice(startIndex, endIndex);
            }
            
            renderTable() {
                const tbody = document.getElementById('tableBody');
                const currentData = this.getCurrentPageData();
                
                if (currentData.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" class="empty-data">📋 Tidak ada data yang ditemukan</td></tr>';
                    return;
                }
                
                tbody.innerHTML = currentData.map(row => `
                    <tr>
                        <td>
                            <span class="position-id">${row.id_prodi}</span>
                        </td>
                        <td>
                            <span class="position-name">${row.nama_prodi}</span>
                        </td>
                        <td>
                            <a href="edit_jabatan.php?id_prodi=${row.id_prodi}" 
                               class="btn-3d btn-edit-3d">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="hapus_jabatan.php?id_prodi=${row.id_prodi}" 
                               class="btn-3d btn-danger-3d" 
                               onclick="return confirm('Apakah anda yakin?')">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                `).join('');
            }
            
            renderPagination() {
                const totalPages = this.getTotalPages();
                const pagination = document.getElementById('pagination');
                
                let paginationHTML = '';
                
                // Previous button
                paginationHTML += `
                    <li class="page-item ${this.currentPage === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" id="prevBtn">Previous</a>
                    </li>
                `;
                
                // Page numbers
                const maxVisiblePages = 5;
                let startPage = Math.max(1, this.currentPage - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
                
                if (endPage - startPage + 1 < maxVisiblePages) {
                    startPage = Math.max(1, endPage - maxVisiblePages + 1);
                }
                
                for (let i = startPage; i <= endPage; i++) {
                    paginationHTML += `
                        <li class="page-item ${i === this.currentPage ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>
                    `;
                }
                
                // Next button
                paginationHTML += `
                    <li class="page-item ${this.currentPage === totalPages ? 'disabled' : ''}">
                        <a class="page-link" href="#" id="nextBtn">Next</a>
                    </li>
                `;
                
                pagination.innerHTML = paginationHTML;
            }
            
            renderPaginationInfo() {
                const totalEntries = this.filteredData.length;
                const startEntry = totalEntries === 0 ? 0 : (this.currentPage - 1) * this.entriesPerPage + 1;
                const endEntry = Math.min(this.currentPage * this.entriesPerPage, totalEntries);
                
                document.getElementById('paginationInfo').textContent = 
                    `Showing ${startEntry} to ${endEntry} of ${totalEntries} entries`;
            }
            
            render() {
                this.renderTable();
                this.renderPagination();
                this.renderPaginationInfo();
            }
        }
        
        // Initialize DataTable when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the custom DataTable
            if (typeof positionData !== 'undefined') {
                new PositionDataTable(positionData);
            }
            
            // Add hover effects to buttons
            document.querySelectorAll('.btn-3d').forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-3px) scale(1.05)';
                });
                
                btn.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                });
            });
            
            // Add table row click effect
            document.addEventListener('click', function(e) {
                if (e.target.closest('.table tbody tr')) {
                    const row = e.target.closest('.table tbody tr');
                    row.style.background = 'rgba(255, 255, 255, 0.3)';
                    setTimeout(() => {
                        row.style.background = '';
                    }, 300);
                }
            });
            
            // Add position ID hover effect
            document.querySelectorAll('.position-id').forEach(badge => {
                badge.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.1) rotate(5deg)';
                });
                
                badge.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                });
            });
            
            // Add position name pulse effect
            document.querySelectorAll('.position-name').forEach(name => {
                name.addEventListener('mouseenter', function() {
                    this.style.textShadow = '0 0 20px #ffd700, 0 0 30px #ffd700';
                });
                
                name.addEventListener('mouseleave', function() {
                    this.style.textShadow = '2px 2px 4px rgba(0,0,0,0.5)';
                });
            });
        });
    </script>

