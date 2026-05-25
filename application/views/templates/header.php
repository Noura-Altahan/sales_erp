<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Mini ERP Stock - نظام المبيعات والمخزون' ?></title>
    
    <!-- Bootstrap 5 RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        * {
            font-family: 'Tajawal', sans-serif;
        }
        
        body {
            background-color: #f4f6f9;
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            height: 100%;
            width: 280px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        
        .sidebar-header h3 {
            color: white;
            margin: 0;
            font-size: 1.5rem;
        }
        
        .sidebar-header p {
            color: rgba(255,255,255,0.8);
            margin: 5px 0 0;
            font-size: 0.8rem;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .sidebar-menu .menu-item {
            padding: 12px 25px;
            transition: all 0.3s ease;
            border-right: 3px solid transparent;
        }
        
        .sidebar-menu .menu-item:hover {
            background: rgba(255,255,255,0.1);
            border-right-color: #fff;
        }
        
        .sidebar-menu .menu-item.active {
            background: rgba(255,255,255,0.15);
            border-right-color: #fff;
        }
        
        .sidebar-menu .menu-item a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1rem;
        }
        
        .sidebar-menu .menu-item a i {
            width: 25px;
            font-size: 1.2rem;
        }
        
        /* Main Content */
        .main-content {
            margin-right: 280px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }
        
        /* Top Navbar */
        .top-navbar {
            background: white;
            padding: 15px 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .toggle-sidebar {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #667eea;
            display: none;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-name {
            font-weight: 500;
            color: #333;
        }
        
        .user-role {
            font-size: 0.8rem;
            color: #667eea;
        }
        
        .logout-btn {
            color: #dc3545;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        /* Content Area */
        .content-area {
            padding: 25px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                right: -280px;
            }
            
            .sidebar.active {
                right: 0;
            }
            
            .main-content {
                margin-right: 0;
            }
            
            .toggle-sidebar {
                display: block;
            }
        }
        
        /* Card Styles */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        /* Table Styles */
        .table-custom {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .table-custom th {
            background: #667eea;
            color: white;
            border: none;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <i class="fas fa-box" style="font-size: 2rem; color: white; margin-bottom: 10px;"></i>
        <h3>Mini ERP Stock</h3>
        <p>نظام المبيعات والمخزون</p>
    </div>
    <div class="sidebar-menu">
        <div class="menu-item <?= ($active_menu ?? '') == 'dashboard' ? 'active' : '' ?>">
            <a href="<?= base_url('welcome') ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>لوحة التحكم</span>
            </a>
        </div>
        <div class="menu-item <?= ($active_menu ?? '') == 'products' ? 'active' : '' ?>">
            <a href="<?= base_url('products') ?>">
                <i class="fas fa-boxes"></i>
                <span>المنتجات</span>
            </a>
        </div>
        <div class="menu-item <?= ($active_menu ?? '') == 'stock' ? 'active' : '' ?>">
            <a href="<?= base_url('stock') ?>">
                <i class="fas fa-warehouse"></i>
                <span>المستودعات</span>
            </a>
        </div>
        <div class="menu-item <?= ($active_menu ?? '') == 'invoices' ? 'active' : '' ?>">
            <a href="<?= base_url('invoices/create') ?>">
                <i class="fas fa-file-invoice"></i>
                <span>فاتورة جديدة</span>
            </a>
        </div>
        <div class="menu-item">
            <a href="<?= base_url('invoices') ?>">
                <i class="fas fa-list"></i>
                <span>قائمة الفواتير</span>
            </a>
        </div>
        <div class="menu-item">
            <a href="<?= base_url('reports/low_stock') ?>">
                <i class="fas fa-exclamation-triangle"></i>
                <span>تقرير المخزون المنخفض</span>
            </a>
        </div>
        <hr style="background: rgba(255,255,255,0.2); margin: 15px 25px;">
        <div class="menu-item">
            <a href="<?= base_url('auth/logout') ?>" style="color: #ff6b6b;">
                <i class="fas fa-sign-out-alt"></i>
                <span>تسجيل الخروج</span>
            </a>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="top-navbar">
        <button class="toggle-sidebar" id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </button>
        <div class="user-info">
            <div class="text-end">
                <div class="user-name"><?= $this->session->userdata('username') ?></div>
                <div class="user-role"><?= $this->session->userdata('role') == 'admin' ? 'مدير النظام' : 'مسؤول مستودع' ?></div>
            </div>
            <i class="fas fa-user-circle" style="font-size: 2rem; color: #667eea;"></i>
        </div>
    </div>
    
    <div class="content-area">