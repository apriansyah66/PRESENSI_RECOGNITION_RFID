<?php
$page = "Dashboard";
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
    
    .dashboard-title {
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
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 25px;
        text-align: center;
        color: white;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .stat-card:hover::before {
        left: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        background: rgba(255, 255, 255, 0.25);
    }
    
    .stat-icon {
        font-size: 3rem;
        margin-bottom: 15px;
        display: block;
        filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.3));
    }
    
    .stat-number {
        font-size: 3rem;
        font-weight: bold;
        margin: 15px 0 10px 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .stat-label {
        font-size: 1.1rem;
        opacity: 0.9;
        font-weight: 500;
    }
    
    .table-container {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    
    .table-header {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        font-weight: bold;
        font-size: 1.2rem;
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
    }
    
    .table td {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 12px;
        vertical-align: middle;
    }
    
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.02);
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
    
    .btn-glass {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 8px 16px;
        border-radius: 25px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        backdrop-filter: blur(10px);
    }
    
    .btn-glass:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        text-decoration: none;
    }
    
    .status-hadir {
        color: #4CAF50;
        font-weight: bold;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }
    
    .status-izin {
        color: #FF9800;
        font-weight: bold;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }
    
    .status-terlambat {
        color: #F44336;
        font-weight: bold;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }
    
    .empty-data {
        text-align: center;
        padding: 40px;
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.1rem;
    }
    
    /* Floating Animation */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .stat-card:nth-child(1) { animation: float 6s ease-in-out infinite; }
    .stat-card:nth-child(2) { animation: float 6s ease-in-out infinite 1.5s; }
    .stat-card:nth-child(3) { animation: float 6s ease-in-out infinite 3s; }
    .stat-card:nth-child(4) { animation: float 6s ease-in-out infinite 4.5s; }
    
    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .stat-card {
            padding: 20px;
        }
        
        .stat-icon {
            font-size: 2.5rem;
        }
        
        .stat-number {
            font-size: 2.5rem;
        }
        
        .datatable-controls, .datatable-footer {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .datatable-right {
            width: 100%;
            justify-content: flex-end;
        }
    }
</style>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="dashboard-title mt-4">🚀 Dashboard</h1>
            
            <div class="breadcrumb-3d">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
            
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <?php
                // Get statistics from database
                $sql_stats = "SELECT 
                    COUNT(*) as total_rekap,
                    SUM(CASE WHEN rekap_tanggal = CURRENT_DATE THEN 1 ELSE 0 END) as hari_ini,
                    SUM(CASE WHEN rekap_tanggal = CURRENT_DATE AND rekap_keterangan = 'Hadir' THEN 1 ELSE 0 END) as hadir,
                    SUM(CASE WHEN rekap_tanggal = CURRENT_DATE AND rekap_keterangan = 'Izin' THEN 1 ELSE 0 END) as izin,
                    SUM(CASE WHEN rekap_tanggal = CURRENT_DATE AND rekap_keterangan = 'Terlambat' THEN 1 ELSE 0 END) as terlambat
                    FROM rekap";
                $result_stats = $koneksi->query($sql_stats);
                $stats = $result_stats ? $result_stats->fetch_assoc() : ['total_rekap' => 0, 'hari_ini' => 0, 'hadir' => 0, 'izin' => 0, 'terlambat' => 0];
                
                // Get total employees
                $sql_karyawan = "SELECT COUNT(*) as total_karyawan FROM karyawan";
                $result_karyawan = $koneksi->query($sql_karyawan);
                $total_karyawan = $result_karyawan ? $result_karyawan->fetch_assoc()['total_karyawan'] : 0;
                ?>
                
                <div class="stat-card">
                    <span class="stat-icon">👥</span>
                    <div class="stat-number"><?php echo $total_karyawan; ?></div>
                    <div class="stat-label">Total Mahasiswa</div>
                </div>
                
                <div class="stat-card">
                    <span class="stat-icon">✅</span>
                    <div class="stat-number"><?php echo $stats['hadir'] ?? 0; ?></div>
                    <div class="stat-label">Hadir Hari Ini</div>
                </div>
                
                <div class="stat-card">
                    <span class="stat-icon">📋</span>
                    <div class="stat-number"><?php echo $stats['izin'] ?? 0; ?></div>
                    <div class="stat-label">Izin Hari Ini</div>
                </div>
                
                <div class="stat-card">
                    <span class="stat-icon">⏰</span>
                    <div class="stat-number"><?php echo $stats['terlambat'] ?? 0; ?></div>
                    <div class="stat-label">Terlambat Hari Ini</div>
                </div>
            </div>
            
            <!-- Attendance Table -->
            <div class="table-container mb-4">
                <div class="table-header">
                    <i class="fas fa-table mr-2"></i>
                    📊 Rekap Absen <small>Hari Ini</small>
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
                            <input type="text" class="search-input" id="searchInput" placeholder="Search...">
                        </label>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered" id="attendanceTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>📅 Tanggal</th>
                                <th>👤 Nama</th>
                                <th>💼 Prodi</th>
                                <th>🔵 Masuk</th>
                                <th>🔴 Pulang</th>
                                <th>📊 Status</th>
                                <th>⚙️ Action</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php
                            $sql = "SELECT `rekap`.`rekap_id`, `rekap`.`rekap_tanggal`, `jabatan`.`jabatan_nama`, `karyawan`.`jabatan_id`, `karyawan`.`karyawan_nama`, `rekap`.`rekap_masuk`, `rekap`.`rekap_keluar`, `rekap`.`rekap_keterangan` 
                            FROM `rekap`
                            INNER JOIN `karyawan` ON `rekap`.`karyawan_id` = `karyawan`.`karyawan_id`
                            INNER JOIN `jabatan` ON `karyawan`.`jabatan_id` = `jabatan`.`jabatan_id`
                            WHERE `rekap_tanggal` = CURRENT_DATE
                            ORDER BY `rekap`.`rekap_masuk` ASC";
                            $result = $koneksi->query($sql);
                            
                            $allData = [];
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $allData[] = $row;
                                }
                            }
                            
                            // Store data in JavaScript for client-side processing
                            echo "<script>var tableData = " . json_encode($allData) . ";</script>";
                            
                            // Display initial data
                            if (!empty($allData)) {
                                foreach (array_slice($allData, 0, 10) as $row) {
                                    $rekap_id = $row['rekap_id'];
                                    $tanggal = $row['rekap_tanggal'];
                                    $nama = $row['karyawan_nama'];
                                    $jabatan = $row['jabatan_nama'];
                                    $masuk = $row['rekap_masuk'];
                                    $pulang = $row['rekap_keluar'];
                                    $status = $row['rekap_keterangan'];
                                    
                                    // Status styling
                                    $status_class = '';
                                    $status_icon = '';
                                    switch($status) {
                                        case 'Hadir':
                                            $status_class = 'status-hadir';
                                            $status_icon = '✅';
                                            break;
                                        case 'Izin':
                                            $status_class = 'status-izin';
                                            $status_icon = '📋';
                                            break;
                                        case 'Terlambat':
                                            $status_class = 'status-terlambat';
                                            $status_icon = '⏰';
                                            break;
                                        default:
                                            $status_class = '';
                                            $status_icon = '❓';
                                    }
                            ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($tanggal)); ?></td>
                                <td><strong><?php echo htmlspecialchars($nama); ?></strong></td>
                                <td><?php echo htmlspecialchars($jabatan); ?></td>
                                <td><?php echo $masuk ? date('H:i', strtotime($masuk)) : '-'; ?></td>
                                <td><?php echo $pulang ? date('H:i', strtotime($pulang)) : '-'; ?></td>
                                <td class="<?php echo $status_class; ?>">
                                    <?php echo $status_icon . ' ' . $status; ?>
                                </td>
                                <td>
                                    <a href="edit_rekap.php?rekap_id=<?php echo $rekap_id; ?>" 
                                       class="btn-glass">
                                        <i class="fas fa-edit"></i> Lihat/Edit
                                    </a>
                                </td>
                            </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="7" class="empty-data">📝 Tidak ada data absensi hari ini</td></tr>';
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
        // DataTable functionality
        class SimpleDataTable {
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
            
            formatStatus(status) {
                const statusConfig = {
                    'Hadir': { class: 'status-hadir', icon: '✅' },
                    'Izin': { class: 'status-izin', icon: '📋' },
                    'Terlambat': { class: 'status-terlambat', icon: '⏰' }
                };
                
                const config = statusConfig[status] || { class: '', icon: '❓' };
                return `<span class="${config.class}">${config.icon} ${status}</span>`;
            }
            
            formatDate(dateString) {
                return new Date(dateString).toLocaleDateString('id-ID');
            }
            
            formatTime(timeString) {
                if (!timeString) return '-';
                return timeString.substring(0, 5); // HH:MM format
            }
            
            renderTable() {
                const tbody = document.getElementById('tableBody');
                const currentData = this.getCurrentPageData();
                
                if (currentData.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="empty-data">📝 Tidak ada data yang ditemukan</td></tr>';
                    return;
                }
                
                tbody.innerHTML = currentData.map(row => `
                    <tr>
                        <td>${this.formatDate(row.rekap_tanggal)}</td>
                        <td><strong>${row.karyawan_nama}</strong></td>
                        <td>${row.jabatan_nama}</td>
                        <td>${this.formatTime(row.rekap_masuk)}</td>
                        <td>${this.formatTime(row.rekap_keluar)}</td>
                        <td>${this.formatStatus(row.rekap_keterangan)}</td>
                        <td>
                            <a href="edit_rekap.php?rekap_id=${row.rekap_id}" class="btn-glass">
                                <i class="fas fa-edit"></i> Lihat/Edit
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
            if (typeof tableData !== 'undefined') {
                new SimpleDataTable(tableData);
            }
            
            // Add click animation to stat cards
            document.querySelectorAll('.stat-card').forEach(card => {
                card.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
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
        });
    </script>

<?php
require_once("./footer.php");
?>