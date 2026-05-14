<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biconoir's Restaurant - Oficial</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/layout.css">
</head>
<body class="flex flex-col min-h-screen antialiased">

    <header class="bg-[#1a4731] text-white shadow-2xl sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php?action=home" class="flex items-center">
                <img src="img/logoRestaurantGreen.png" alt="Logo" class="logo-main">
            </a>
            <nav class="hidden md:flex items-center space-x-8 font-medium">
                <a href="index.php?action=home" class="hover:text-green-300 transition-colors">Inicio</a>
                <a href="index.php?action=menu" class="hover:text-green-300 transition-colors">Menú</a>
                <a href="index.php?action=about" class="hover:text-green-300 transition-colors">Sobre Nosotros</a>
                <a href="index.php?action=reservations" class="hover:text-green-300 transition-colors">Reservar</a>
                <a href="index.php?action=survey" class="hover:text-green-300 transition-colors">Encuesta</a>
                <a href="index.php?action=orders" class="hover:text-green-300 transition-colors">Mis Pedidos</a>
                
                <a href="index.php?action=cart" class="relative hover:text-green-300 transition-colors text-2xl">
                    🛒
                    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                        <span class="absolute -top-2 -right-3 bg-red-500 text-white text-[10px] rounded-full h-4 w-4 flex items-center justify-center font-bold shadow-lg">
                            <?php echo array_sum(array_column($_SESSION['cart'], 'quantity')); ?>
                        </span>
                    <?php endif; ?>
                </a>

                <?php if (isset($_SESSION['user'])): ?>
                    <div class="relative group">
                        <button class="flex items-center space-x-2 bg-black/20 px-4 py-2 rounded-full border border-white/10 hover:bg-black/30 transition">
                            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                            <span class="font-bold text-sm"><?php echo $_SESSION['user']['name']; ?></span>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-2xl py-2 hidden group-hover:block text-gray-800 border border-gray-100 overflow-hidden">
                            <div class="px-4 py-2 bg-gray-50 border-b border-gray-100 mb-1">
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Sesión</p>
                                <p class="text-xs font-bold text-[#1a4731]"><?php echo strtoupper($_SESSION['user']['role']); ?></p>
                            </div>
                            <?php if (in_array($_SESSION['user']['role'], ['admin', 'administrator'])): ?>
                                <a href="index.php?action=admin_dashboard" class="block px-4 py-2 text-sm hover:bg-blue-50 text-blue-600 font-bold">⚙️ Gestionar</a>
                            <?php endif; ?>
                            <a href="index.php?action=logout" class="block px-4 py-2 text-sm hover:bg-red-50 text-red-500">Cerrar Sesión</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="index.php?action=login" class="bg-white text-[#1a4731] px-6 py-2 rounded-full text-sm font-bold shadow-lg hover:bg-gray-100 transition-all">Entrar</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="flex-grow">
        <?php echo $content; ?>
    </main>

    <footer class="bg-gray-900 text-gray-500 py-12 text-center text-xs">
        <div class="container mx-auto px-6">
            <p class="mb-2 uppercase tracking-widest font-bold">&copy; 2026 Biconoir's Restaurant</p>
            <p>La excelencia en cada detalle culinario.</p>
        </div>
    </footer>

    <script src="js/main.js"></script>
    <script src="js/user.js"></script>
    <script src="js/reservation.js"></script>
    <script src="js/survey.js"></script>
</body>
</html>
