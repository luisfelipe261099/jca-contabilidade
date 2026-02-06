<?php
// Buscar dados do usuário logado se não estiver definido
if (!isset($usuario)) {
    $usuario = getUsuarioLogado();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#0A2463', 50: '#E6EAF3', 100: '#CCD5E7', 200: '#99ABCF', 300: '#6681B7', 400: '#33579F', 500: '#0A2463', 600: '#081D4F', 700: '#06163B', 800: '#040E28', 900: '#020714' },
                        accent: { DEFAULT: '#FB8500', 50: '#FFF3E6', 100: '#FFE7CC', 200: '#FFCF99', 300: '#FFB766', 400: '#FF9F33', 500: '#FB8500', 600: '#C96A00', 700: '#975000', 800: '#643500', 900: '#321B00' }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 antialiased">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: true, userMenuOpen: false }">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col" x-show="sidebarOpen" x-transition>
            <!-- Logo -->
            <div class="h-16 flex items-center px-6 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <span
                        class="text-2xl font-extrabold bg-gradient-to-r from-accent-500 to-accent-600 bg-clip-text text-transparent">JCA</span>
                    <span class="text-xl font-bold text-primary-600">ERP</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <!-- Principal -->
                <div class="mb-4">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Principal</p>
                    <a href="<?php echo SITE_URL; ?>/dashboard.php"
                        class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100'; ?>">
                        <i data-lucide="home" class="w-5 h-5"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="<?php echo SITE_URL; ?>/alertas.php"
                        class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'alertas.php' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100'; ?>">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span>Alertas</span>
                        <?php if (isset($stats['alertas_nao_lidos']) && $stats['alertas_nao_lidos'] > 0): ?>
                            <span
                                class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?php echo $stats['alertas_nao_lidos']; ?></span>
                        <?php endif; ?>
                    </a>
                </div>

                <!-- Gestão -->
                <div class="mb-4">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Gestão</p>
                    <a href="<?php echo SITE_URL; ?>/clientes.php"
                        class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'clientes.php' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100'; ?>">
                        <i data-lucide="building-2" class="w-5 h-5"></i>
                        <span>Clientes</span>
                    </a>
                    <?php if (isFuncionario()): ?>
                        <a href="<?php echo SITE_URL; ?>/funcionarios.php"
                            class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'funcionarios.php' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100'; ?>">
                            <i data-lucide="user-cog" class="w-5 h-5"></i>
                            <span>Usuários</span>
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo SITE_URL; ?>/tarefas.php"
                        class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'tarefas.php' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100'; ?>">
                        <i data-lucide="check-square" class="w-5 h-5"></i>
                        <span>Tarefas</span>
                    </a>
                    <!-- <a href="<?php echo SITE_URL; ?>/documentos.php" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'documentos.php' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100'; ?>">
                        <i data-lucide="folder" class="w-5 h-5"></i>
                        <span>Documentos</span>
                    </a> -->
                </div>

                <!-- RH -->
                <div class="mb-4">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Recursos Humanos
                    </p>
                    <a href="<?php echo SITE_URL; ?>/funcionarios-clientes.php"
                        class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'funcionarios-clientes.php' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100'; ?>">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        <span>Funcionários</span>
                    </a>
                    <!-- <a href="<?php echo SITE_URL; ?>/holerites.php" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'holerites.php' || basename($_SERVER['PHP_SELF']) == 'holerite-gerar.php' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100'; ?>">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                        <span>Holerites</span>
                    </a> -->
                    <a href="<?php echo SITE_URL; ?>/rh.php"
                        class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'rh.php' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100'; ?>">
                        <i data-lucide="user-check" class="w-5 h-5"></i>
                        <span>RH e Folha</span>
                    </a>
                </div>

                <!-- Módulos -->
                <div class="mb-4">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Módulos</p>
                    <a href="<?php echo SITE_URL; ?>/contabil.php"
                        class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'contabil.php' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100'; ?>">
                        <i data-lucide="calculator" class="w-5 h-5"></i>
                        <span>Contábil</span>
                    </a>
                    <a href="<?php echo SITE_URL; ?>/fiscal.php"
                        class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'fiscal.php' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100'; ?>">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                        <span>Fiscal</span>
                    </a>
                    <a href="<?php echo SITE_URL; ?>/obrigacoes.php"
                        class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'obrigacoes.php' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100'; ?>">
                        <i data-lucide="calendar-check" class="w-5 h-5"></i>
                        <span>Obrigações</span>
                    </a>
                    <a href="<?php echo SITE_URL; ?>/financeiro.php"
                        class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'financeiro.php' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100'; ?>">
                        <i data-lucide="dollar-sign" class="w-5 h-5"></i>
                        <span>Financeiro</span>
                    </a>
                </div>

                <!-- Relatórios -->
                <div class="mb-4">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Relatórios</p>
                    <a href="<?php echo SITE_URL; ?>/relatorios.php"
                        class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'relatorios.php' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100'; ?>">
                        <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                        <span>Relatórios</span>
                    </a>
                </div>

                <!-- Sistema -->
                <?php if (isAdmin()): ?>
                    <div class="mb-4">
                        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Sistema</p>
                        <a href="<?php echo SITE_URL; ?>/manual.php"
                            class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'manual.php' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100'; ?>">
                            <i data-lucide="book-open" class="w-5 h-5"></i>
                            <span>Manual do Sistema</span>
                        </a>
                        <a href="<?php echo SITE_URL; ?>/configuracoes.php"
                            class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'configuracoes.php' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100'; ?>">
                            <i data-lucide="settings" class="w-5 h-5"></i>
                            <span>Configurações</span>
                        </a>
                        <a href="<?php echo SITE_URL; ?>/logs.php"
                            class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'logs.php' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100'; ?>">
                            <i data-lucide="history" class="w-5 h-5"></i>
                            <span>Logs</span>
                        </a>
                    </div>
                <?php endif; ?>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-gray-50">
            <!-- Topbar -->
            <header
                class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 sticky top-0 z-10">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        <?php echo isset($pageTitle) ? $pageTitle : 'Dashboard'; ?></h1>
                    <?php if (isset($breadcrumb)): ?>
                        <nav class="text-sm text-gray-500 mt-0.5">
                            <?php echo $breadcrumb; ?>
                        </nav>
                    <?php endif; ?>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Notificações -->
                    <button onclick="window.location.href='<?php echo SITE_URL; ?>/alertas.php'"
                        class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <?php if (isset($stats['alertas_nao_lidos']) && $stats['alertas_nao_lidos'] > 0): ?>
                            <span
                                class="absolute top-1 right-1 bg-red-500 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full"><?php echo $stats['alertas_nao_lidos']; ?></span>
                        <?php endif; ?>
                    </button>

                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-3 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <div
                                class="w-9 h-9 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center text-white font-semibold">
                                <?php echo strtoupper(substr($usuario['nome'], 0, 1)); ?>
                            </div>
                            <div class="text-left hidden md:block">
                                <div class="text-sm font-semibold text-gray-900">
                                    <?php echo htmlspecialchars($usuario['nome']); ?></div>
                                <div class="text-xs text-gray-500"><?php echo ucfirst($usuario['tipo_usuario']); ?>
                                </div>
                            </div>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-500"></i>
                        </button>

                        <!-- Dropdown -->
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                            <a href="<?php echo SITE_URL; ?>/perfil.php"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i data-lucide="user" class="w-4 h-4"></i>
                                Meu Perfil
                            </a>
                            <hr class="my-1 border-gray-200">
                            <a href="<?php echo SITE_URL; ?>/logout.php"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                Sair
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash messages -->
            <?php $flash = showMessages();
            if (!empty($flash)): ?>
                <div class="px-6 py-4">
                    <?php echo $flash; ?>
                </div>
            <?php endif; ?>

            <!-- Page Content -->