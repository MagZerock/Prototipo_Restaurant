<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Encuestas de Satisfacción - Biconoir's</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body class="bg-gray-50 flex min-h-screen">

    <aside class="w-80 bg-[#1a4731] text-white flex flex-col sticky top-0 h-screen shadow-2xl">
        <div class="p-10 border-b border-green-800 text-center">
            <h1 class="text-3xl font-bold tracking-tighter">BICONOIR</h1>
            <p class="text-[10px] text-green-400 font-bold uppercase tracking-widest mt-1">Admin Center</p>
        </div>
        <nav class="flex-grow p-8 space-y-6">
            <a href="index.php?action=home" class="flex items-center space-x-3 text-green-100 hover:text-white transition">
                <span>🏠</span> <span class="font-bold">Volver a la Web</span>
            </a>
            <a href="index.php?action=admin_dashboard" class="flex items-center space-x-3 text-green-100 hover:text-white transition">
                <span>📊</span> <span class="font-bold">Panel Principal</span>
            </a>
            <a href="index.php?action=admin_reservations" class="flex items-center space-x-3 text-green-100 hover:text-white transition">
                <span>📅</span> <span class="font-bold">Reservas</span>
            </a>
            <a href="index.php?action=inventory" class="flex items-center space-x-3 text-green-100 hover:text-white transition">
                <span>📦</span> <span class="font-bold">Inventario</span>
            </a>
            <a href="index.php?action=admin_surveys" class="flex items-center space-x-3 text-white bg-green-900/50 p-4 rounded-xl transition">
                <span>📝</span> <span class="font-bold">Encuestas</span>
            </a>
        </nav>
        <div class="p-8 border-t border-green-800 text-center">
            <p class="text-green-300 text-xs font-bold uppercase">Biconoir Gourmet v1.0</p>
        </div>
    </aside>

    <main class="flex-grow p-12 overflow-y-auto">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-5xl font-bold text-gray-800 tracking-tighter uppercase">Feedback de Clientes</h2>
                <p class="text-gray-400 mt-2 italic">Revisa las opiniones y calificaciones recibidas.</p>
            </div>
            <div class="flex gap-4">
                <input type="text" id="search_surveys" placeholder="🔍 Buscar por nombre o comentario..." onkeyup="filterTable('search_surveys', 'surveys_table')" class="px-6 py-3 bg-white border border-gray-200 rounded-2xl outline-none text-sm focus:ring-2 focus:ring-[#1a4731] shadow-sm w-64">
            </div>
        </div>

        <section class="bg-white rounded-[3rem] shadow-2xl p-10 border border-gray-100">
            <div class="overflow-y-auto max-h-[650px]">
                <table class="w-full text-left" id="surveys_table">
                    <thead>
                        <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                            <th class="pb-4 px-4">Cliente</th>
                            <th class="pb-4 px-4">Calificación</th>
                            <th class="pb-4 px-4">Comentario</th>
                            <th class="pb-4 px-4">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php foreach ($surveys as $survey): ?>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-6 px-4">
                                    <p class="font-bold text-gray-800"><?php echo htmlspecialchars($survey['customer_name']); ?></p>
                                </td>
                                <td class="py-6 px-4">
                                    <div class="flex items-center">
                                        <span class="text-yellow-400 mr-1 text-lg">★</span>
                                        <span class="font-bold text-gray-700"><?php echo $survey['rating']; ?>.0</span>
                                    </div>
                                </td>
                                <td class="py-6 px-4 max-w-md">
                                    <p class="text-sm text-gray-500 italic leading-relaxed">
                                        "<?php echo htmlspecialchars($survey['comment']); ?>"
                                    </p>
                                </td>
                                <td class="py-6 px-4">
                                    <span class="text-xs text-gray-400 font-bold uppercase">
                                        <?php echo date('d/m/Y H:i', strtotime($survey['created_at'])); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php if (empty($surveys)): ?>
                    <div class="text-center py-20">
                        <p class="text-gray-400 italic">No hay encuestas registradas aún.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script src="js/main.js"></script>
    <script src="js/adminsurveys.js"></script>
</body>
</html>
