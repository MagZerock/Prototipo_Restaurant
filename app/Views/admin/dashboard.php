<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Biconoir's</title>
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
            <a href="index.php?action=admin_dashboard" class="flex items-center space-x-3 text-white bg-green-900/50 p-4 rounded-xl transition">
                <span>📊</span> <span class="font-bold">Panel Principal</span>
            </a>
            <a href="index.php?action=admin_reservations" class="flex items-center space-x-3 text-green-100 hover:text-white transition">
                <span>📅</span> <span class="font-bold">Reservas</span>
            </a>
            <a href="index.php?action=inventory" class="flex items-center space-x-3 text-green-100 hover:text-white transition">
                <span>📦</span> <span class="font-bold">Inventario</span>
            </a>
            <a href="index.php?action=admin_surveys" class="flex items-center space-x-3 text-green-100 hover:text-white transition">
                <span>📝</span> <span class="font-bold">Encuestas</span>
            </a>
            <div class="pt-4 border-t border-green-800">
                <p class="text-[10px] text-green-500 font-bold uppercase mb-4">Métricas</p>
                <div class="space-y-4">
                    <div class="bg-green-900/40 p-4 rounded-2xl border border-green-700/30">
                        <p class="text-xs text-green-300">Total Platos</p>
                        <p class="text-2xl font-bold"><?php echo count($dishes); ?></p>
                    </div>
                </div>
            </div>
        </nav>
        <div class="p-8 border-t border-green-800">
            <a href="index.php?action=logout" class="block w-full bg-red-500/20 hover:bg-red-500/40 text-red-200 text-center py-3 rounded-xl text-sm font-bold transition">Cerrar Sesión</a>
        </div>
    </aside>

    <main class="flex-grow p-12 overflow-y-auto">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-5xl font-bold text-gray-800 tracking-tighter uppercase">Gestión del Sistema</h2>
                <p class="text-gray-400 mt-2 italic">Administra el menú, reservas y feedback de clientes.</p>
            </div>
            <button onclick="document.getElementById('modal_add').classList.remove('hidden')" class="bg-[#1a4731] text-white px-8 py-4 rounded-2xl font-bold shadow-2xl hover:bg-black hover:scale-105 transition-all transform active:scale-95 flex items-center">
                <span class="mr-3 text-2xl">+</span> Agregar Plato
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <section class="lg:col-span-3 bg-white rounded-[3rem] shadow-2xl p-10 border border-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                    <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                        <span class="mr-3">🍽️</span> Gestión del Menú
                    </h3>
                    <input type="text" id="search_menu" placeholder="🔍 Buscar plato..." onkeyup="filterGrid('search_menu', 'menu_grid')" class="w-full max-w-sm px-6 py-3 bg-gray-50 border border-gray-200 rounded-2xl outline-none text-sm focus:ring-2 focus:ring-[#1a4731] shadow-sm">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-h-[600px] overflow-y-auto p-2" id="menu_grid">
                    <?php foreach ($dishes as $dish): ?>
                        <div class="flex items-center justify-between p-6 bg-gray-50 rounded-[2rem] border border-transparent hover:border-green-200 transition-all group">
                            <div class="flex items-center space-x-4">
                                <img src="<?php echo $dish['image'] ?: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=200'; ?>" class="w-16 h-16 rounded-2xl object-cover shadow-md">
                                <div>
                                    <h4 class="font-bold text-gray-800"><?php echo $dish['name']; ?></h4>
                                    <p class="text-xs text-green-600 font-bold">$<?php echo number_format($dish['price'], 2); ?></p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($dish)); ?>)" class="bg-white p-3 rounded-xl shadow-md text-blue-600 hover:bg-blue-600 hover:text-white transition-all">✏️</button>
                                <button onclick="confirmDeleteDish('<?php echo $dish['id']; ?>')" class="bg-white p-3 rounded-xl shadow-md text-red-600 hover:bg-red-600 hover:text-white transition-all">🗑️</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>

        <section class="mt-12 bg-white rounded-[3rem] shadow-2xl p-10 border border-gray-100">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                    <span class="mr-3">🛍️</span> Gestión de Pedidos
                </h3>
                <div class="flex items-center gap-4">
                    <div class="bg-gray-50 p-2 rounded-xl border border-gray-100 flex items-center space-x-3">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Fecha:</label>
                        <form action="index.php" method="GET" class="flex items-center" id="adminOrderDateForm">
                            <input type="hidden" name="action" value="admin_dashboard">
                            <input type="date" name="order_date" value="<?php echo htmlspecialchars($orderDate); ?>" 
                                   class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg outline-none text-xs font-bold text-gray-700 cursor-pointer"
                                   onchange="document.getElementById('adminOrderDateForm').submit()">
                            <?php if (isset($_GET['order_date']) && $_GET['order_date'] !== ''): ?>
                                <a href="index.php?action=admin_dashboard&order_date=" class="ml-2 text-[10px] text-red-400 hover:underline font-bold">Limpiar</a>
                            <?php endif; ?>
                        </form>
                    </div>
                    <input type="text" id="search_orders" placeholder="🔍 Buscar ID, cliente..." onkeyup="filterTable('search_orders', 'orders_table')" class="w-full max-w-sm px-6 py-3 bg-gray-50 border border-gray-200 rounded-2xl outline-none text-sm focus:ring-2 focus:ring-[#1a4731] shadow-sm">
                </div>
            </div>
            <div class="overflow-y-auto max-h-[600px]">
                <table class="w-full text-left" id="orders_table">
                    <thead>
                        <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                            <th class="pb-4 px-4">ID</th>
                            <th class="pb-4 px-4">Cliente</th>
                            <th class="pb-4 px-4">Detalle</th>
                            <th class="pb-4 px-4">Total</th>
                            <th class="pb-4 px-4">Estado</th>
                            <th class="pb-4 px-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php foreach (array_reverse($orders) as $o): ?>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-6 px-4 font-bold text-gray-800 text-sm">#<?php echo $o['id']; ?></td>
                                <td class="py-6 px-4">
                                    <p class="font-bold text-gray-700 text-sm"><?php echo $o['customer_name']; ?></p>
                                </td>
                                <td class="py-6 px-4">
                                    <?php if (isset($o['items']) && !empty($o['items'])): ?>
                                        <?php foreach ($o['items'] as $item): ?>
                                            <p class="text-xs font-bold text-gray-700"><?php echo $item['quantity']; ?>x <?php echo $item['name']; ?></p>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-xs text-gray-400 italic">Sin detalles</p>
                                    <?php endif; ?>
                                </td>
                                <td class="py-6 px-4 font-bold text-[#1a4731]">$<?php echo number_format($o['total'], 2); ?></td>
                                <td class="py-6 px-4">
                                    <span class="px-4 py-1 rounded-full text-[10px] font-bold uppercase <?php echo $o['status'] === 'Completado' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-green-50 text-[#1a4731] border-green-100'; ?> border"><?php echo $o['status']; ?></span>
                                </td>
                                <td class="py-6 px-4 flex justify-center gap-2">
                                    <button onclick="confirmUpdateOrderStatus('<?php echo $o['id']; ?>', 'Completado')" class="p-2 bg-gray-100 rounded-lg hover:bg-green-600 hover:text-white transition" title="Marcar como Completado (Descuenta Stock)">✅</button>
                                    <button onclick="confirmUpdateOrderStatus('<?php echo $o['id']; ?>', 'Cancelado')" class="p-2 bg-gray-100 rounded-lg hover:bg-red-600 hover:text-white transition" title="Cancelar">❌</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <div id="modal_add" class="hidden fixed inset-0 bg-black/70 backdrop-blur-md z-[100] flex items-center justify-center p-6">
        <div class="bg-white rounded-[3rem] p-12 max-w-2xl w-full shadow-2xl relative">
            <button onclick="document.getElementById('modal_add').classList.add('hidden')" class="absolute top-6 right-6 text-gray-300 hover:text-red-500">✕</button>
            <h3 class="text-3xl font-bold mb-8 uppercase tracking-tighter">Nuevo Plato</h3>
            <form action="index.php?action=add_dish" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-5">
                    <input type="text" name="name" required placeholder="Nombre del plato" class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl outline-none focus:ring-2 focus:ring-[#1a4731]">
                    <textarea name="description" required placeholder="Descripción" class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl outline-none focus:ring-2 focus:ring-[#1a4731] h-32"></textarea>
                    <input type="number" step="0.01" name="price" required placeholder="Precio" class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl outline-none focus:ring-2 focus:ring-[#1a4731]">
                    <input type="text" name="image" placeholder="URL imagen" class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl outline-none focus:ring-2 focus:ring-[#1a4731]">
                </div>
                <div class="space-y-4">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2">Composición e Inventario</p>
                    <input type="text" id="search_add_ingredients" placeholder="🔍 Buscar ingrediente..." onkeyup="filterIngredients('add')" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl outline-none text-xs focus:ring-1 focus:ring-[#1a4731]">
                    <div id="list_add_ingredients" class="space-y-2 max-h-80 overflow-y-auto p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <?php foreach ($ingredients as $ing): ?>
                            <div class="ingredient-item flex items-center justify-between p-3 bg-white rounded-xl shadow-sm" data-name="<?php echo strtolower($ing['name']); ?>">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="ingredients[<?php echo $ing['sku_code']; ?>][selected]" value="1" class="w-4 h-4 rounded border-gray-300 text-[#1a4731]">
                                    <span class="text-xs font-bold text-gray-700"><?php echo $ing['name']; ?></span>
                                </label>
                                <div class="flex items-center space-x-2">
                                    <input type="number" step="0.01" min="0" name="ingredients[<?php echo $ing['sku_code']; ?>][quantity]" placeholder="Cant." class="qty-input w-20 px-3 py-1 bg-gray-50 border border-gray-100 rounded-lg text-xs outline-none">
                                    <span class="text-[10px] text-gray-400 font-bold uppercase"><?php echo $ing['unit_of_measurement']; ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" class="w-full bg-[#1a4731] text-white py-5 rounded-2xl font-bold shadow-xl hover:bg-black transition-all">Guardar Plato</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal_edit" class="hidden fixed inset-0 bg-black/70 backdrop-blur-md z-[100] flex items-center justify-center p-6">
        <div class="bg-white rounded-[3rem] p-12 max-w-2xl w-full shadow-2xl relative">
            <button onclick="document.getElementById('modal_edit').classList.add('hidden')" class="absolute top-6 right-6 text-gray-300 hover:text-red-500">✕</button>
            <h3 class="text-3xl font-bold mb-8 uppercase tracking-tighter text-blue-600">Editar Plato</h3>
            <form action="index.php?action=edit_dish" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <input type="hidden" name="id" id="edit_id">
                <div class="space-y-5">
                    <input type="text" name="name" id="edit_name" required class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl outline-none focus:ring-2 focus:ring-blue-600">
                    <textarea name="description" id="edit_description" required class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl outline-none focus:ring-2 focus:ring-blue-600 h-32"></textarea>
                    <input type="number" step="0.01" name="price" id="edit_price" required class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl outline-none focus:ring-2 focus:ring-blue-600">
                    <input type="text" name="image" id="edit_image" class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl outline-none focus:ring-2 focus:ring-blue-600">
                </div>
                <div class="space-y-4">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2">Composición e Inventario</p>
                    <input type="text" id="search_edit_ingredients" placeholder="🔍 Buscar ingrediente..." onkeyup="filterIngredients('edit')" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl outline-none text-xs focus:ring-1 focus:ring-blue-600">
                    <div id="edit_ingredients_container" class="space-y-2 max-h-80 overflow-y-auto p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <?php foreach ($ingredients as $ing): ?>
                            <div class="ingredient-item flex items-center justify-between p-3 bg-white rounded-xl shadow-sm" data-name="<?php echo strtolower($ing['name']); ?>">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="ingredients[<?php echo $ing['sku_code']; ?>][selected]" value="1" data-sku="<?php echo $ing['sku_code']; ?>" class="w-4 h-4 rounded border-gray-300 text-blue-600">
                                    <span class="text-xs font-bold text-gray-700"><?php echo $ing['name']; ?></span>
                                </label>
                                <div class="flex items-center space-x-2">
                                    <input type="number" step="0.01" min="0" name="ingredients[<?php echo $ing['sku_code']; ?>][quantity]" data-qty-sku="<?php echo $ing['sku_code']; ?>" class="qty-input w-20 px-3 py-1 bg-gray-50 border border-gray-100 rounded-lg text-xs outline-none">
                                    <span class="text-[10px] text-gray-400 font-bold uppercase"><?php echo $ing['unit_of_measurement']; ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-5 rounded-2xl font-bold shadow-xl hover:bg-blue-800 transition-all">Actualizar Plato</button>
                </div>
            </form>
        </div>
    </div>


    <script src="js/main.js"></script>
    <script src="js/dish.js"></script>
    <script src="js/dashboard.js"></script>
</body>
</html>
