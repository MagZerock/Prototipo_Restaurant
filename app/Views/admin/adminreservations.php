<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservas - Panel de Administración</title>
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
            <a href="index.php?action=admin_dashboard" class="flex items-center space-x-3 text-green-100 hover:text-white transition">
                <span>📊</span> <span class="font-bold">Panel Principal</span>
            </a>
            <a href="index.php?action=admin_reservations" class="flex items-center space-x-3 text-white bg-green-900/50 p-4 rounded-xl transition">
                <span>📅</span> <span class="font-bold">Reservas</span>
            </a>
            <a href="index.php?action=inventory" class="flex items-center space-x-3 text-green-100 hover:text-white transition">
                <span>📦</span> <span class="font-bold">Inventario</span>
            </a>
            <a href="index.php?action=admin_surveys" class="flex items-center space-x-3 text-green-100 hover:text-white transition">
                <span>📝</span> <span class="font-bold">Encuestas</span>
            </a>
        </nav>
        <div class="p-8 border-t border-green-800">
            <a href="index.php?action=home" class="block w-full bg-green-500/20 hover:bg-green-500/40 text-green-200 text-center py-3 rounded-xl text-sm font-bold transition">Volver a la Web</a>
        </div>
    </aside>

    <main class="flex-grow p-12 overflow-y-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-6">
            <div>
                <h2 class="text-5xl font-bold text-gray-800 tracking-tighter uppercase">Gestión de Reservas</h2>
                <p class="text-gray-400 mt-2 italic">Visualiza y aprueba las reservaciones de los clientes.</p>
            </div>
            
            <div class="flex items-center gap-4">
                <input type="text" id="search_reservations" placeholder="🔍 Buscar ID, cliente, o fecha..." onkeyup="filterTable('search_reservations', 'reservations_table')" class="px-6 py-3 bg-white border border-gray-200 rounded-2xl outline-none text-sm focus:ring-2 focus:ring-[#1a4731] shadow-sm w-64">
                
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Filtrar por día:</label>
                    <form action="index.php" method="GET" class="flex items-center" id="dateFilterForm">
                        <input type="hidden" name="action" value="admin_reservations">
                        <input type="date" name="date" value="<?php echo htmlspecialchars($_GET['date'] ?? ''); ?>" 
                               class="px-4 py-2 bg-gray-50 border-0 rounded-xl outline-none text-sm font-bold text-gray-700 cursor-pointer"
                               onchange="document.getElementById('dateFilterForm').submit()">
                        <?php if (isset($_GET['date']) && !empty($_GET['date'])): ?>
                            <a href="index.php?action=admin_reservations" class="ml-2 text-xs text-red-400 hover:underline">Limpiar</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <section class="bg-white rounded-[3rem] shadow-2xl p-10 border border-gray-100">
            <div class="overflow-y-auto max-h-[600px]">
                <table class="w-full text-left" id="reservations_table">
                    <thead>
                        <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                            <th class="pb-4 px-4">ID Reserva</th>
                            <th class="pb-4 px-4">Fecha y Hora</th>
                            <th class="pb-4 px-4">Comensales</th>
                            <th class="pb-4 px-4">Detalles</th>
                            <th class="pb-4 px-4">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if (empty($reservations)): ?>
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-400 italic">No hay reservas para mostrar.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reservations as $r): ?>
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="py-6 px-4 font-bold text-gray-800 text-sm">#<?php echo substr($r['reservation_id'], 0, 8); ?></td>
                                    <td class="py-6 px-4">
                                        <p class="font-bold text-gray-700 text-sm"><?php echo $r['date']; ?></p>
                                        <p class="text-xs text-green-600 font-bold"><?php echo substr($r['time'], 0, 5); ?></p>
                                    </td>
                                    <td class="py-6 px-4">
                                        <div class="flex flex-col gap-2">
                                            <div class="flex items-center space-x-2">
                                                <span class="bg-[#1a4731] text-white w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs"><?php echo $r['number_of_people']; ?></span>
                                                <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Pax</span>
                                            </div>
                                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Cliente: <span class="text-gray-800"><?php echo htmlspecialchars($r['user']['name'] ?? 'Anónimo'); ?></span></p>
                                        </div>
                                    </td>
                                    <td class="py-6 px-4 max-w-xs">
                                        <?php 
                                            $cleanNotes = trim(preg_replace('/Duración:\s*([0-9\.]+)h\.?/i', '', $r['notes'] ?? ''));
                                        ?>
                                        <?php if (!empty($cleanNotes)): ?>
                                            <p class="text-xs text-gray-500 line-clamp-3" title="<?php echo htmlspecialchars($cleanNotes); ?>">
                                                <?php echo htmlspecialchars($cleanNotes); ?>
                                            </p>
                                        <?php else: ?>
                                            <p class="text-xs text-gray-300 italic">Sin detalles adicionales</p>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-6 px-4">
                                        <?php 
                                            $statusClass = 'bg-gray-100 text-gray-600';
                                            $displayStatus = 'CONFIRMADO';
                                            if ($r['status'] === 'Confirmada' || $r['status'] === 'Confirmed') {
                                                $statusClass = 'bg-green-100 text-[#1a4731] border-green-200';
                                                $displayStatus = 'CONFIRMADO';
                                            }
                                            if ($r['status'] === 'Rechazada' || $r['status'] === 'Cancelled') {
                                                $statusClass = 'bg-red-100 text-red-600 border-red-200';
                                                $displayStatus = 'CANCELADO';
                                            }
                                            if ($r['status'] === 'Pending' || $r['status'] === 'Pendiente') {
                                                $statusClass = 'bg-green-100 text-[#1a4731] border-green-200';
                                                $displayStatus = 'CONFIRMADO';
                                            }
                                        ?>
                                        <span class="px-4 py-1 rounded-full text-[10px] font-bold uppercase border <?php echo $statusClass; ?>">
                                            <?php echo $displayStatus; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script src="js/main.js"></script>
    <script src="js/adminreservations.js"></script>
</body>
</html>
