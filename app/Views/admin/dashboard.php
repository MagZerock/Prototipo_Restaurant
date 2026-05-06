<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Biconoir's</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-80 bg-[#1a4731] text-white flex flex-col sticky top-0 h-screen shadow-2xl">
        <div class="p-10 border-b border-green-800 text-center">
            <h1 class="text-3xl font-bold tracking-tighter">BICONOIR</h1>
            <p class="text-[10px] text-green-400 font-bold uppercase tracking-widest mt-1">Admin Center</p>
        </div>
        <nav class="flex-grow p-8 space-y-6">
            <a href="index.php?action=home" class="flex items-center space-x-3 text-green-100 hover:text-white transition">
                <span>🏠</span> <span class="font-bold">Volver a la Web</span>
            </a>
            <div class="pt-4 border-t border-green-800">
                <p class="text-[10px] text-green-500 font-bold uppercase mb-4">Métricas</p>
                <div class="space-y-4">
                    <div class="bg-green-900/40 p-4 rounded-2xl border border-green-700/30">
                        <p class="text-xs text-green-300">Total Platos</p>
                        <p class="text-2xl font-bold"><?php echo count($dishes); ?></p>
                    </div>
                    <div class="bg-green-900/40 p-4 rounded-2xl border border-green-700/30">
                        <p class="text-xs text-green-300">Reservas</p>
                        <p class="text-2xl font-bold"><?php echo count($reservations); ?></p>
                    </div>
                </div>
            </div>
        </nav>
        <div class="p-8 border-t border-green-800">
            <a href="index.php?action=logout" class="block w-full bg-red-500/20 hover:bg-red-500/40 text-red-200 text-center py-3 rounded-xl text-sm font-bold transition">Cerrar Sesión</a>
        </div>
    </aside>

    <!-- Main Content -->
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
            <!-- Dishes Section -->
            <section class="lg:col-span-2 bg-white rounded-[3rem] shadow-2xl p-10 border border-gray-100">
                <h3 class="text-2xl font-bold text-gray-800 mb-8 flex items-center">
                    <span class="mr-3">🍽️</span> Gestión del Menú
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($dishes as $dish): ?>
                        <div class="flex items-center justify-between p-6 bg-gray-50 rounded-[2rem] border border-transparent hover:border-green-200 transition-all group">
                            <div class="flex items-center space-x-4">
                                <img src="<?php echo $dish['image'] ?: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=200'; ?>" class="w-16 h-16 rounded-2xl object-cover shadow-md">
                                <div>
                                    <h4 class="font-bold text-gray-800"><?php echo $dish['name']; ?></h4>
                                    <p class="text-xs text-green-600 font-bold">$<?php echo number_format($dish['price'], 2); ?></p>
                                </div>
                            </div>
                            <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($dish)); ?>)" class="bg-white p-3 rounded-xl shadow-md text-blue-600 hover:bg-blue-600 hover:text-white transition-all">
                                ✏️
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Sidebar Info -->
            <div class="space-y-10">
                <!-- Reservations -->
                <section class="bg-white rounded-[3rem] shadow-2xl p-8 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <span class="mr-3 text-2xl">📅</span> Reservas
                    </h3>
                    <div class="space-y-4">
                        <?php foreach (array_reverse($reservations) as $r): ?>
                            <div class="p-4 border-l-4 border-green-500 bg-green-50/50 rounded-r-2xl">
                                <p class="font-bold text-gray-800 text-sm"><?php echo $r['customer']; ?></p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1"><?php echo $r['date']; ?> | <?php echo $r['type']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- Feedback -->
                <section class="bg-white rounded-[3rem] shadow-2xl p-8 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <span class="mr-3 text-2xl">⭐</span> Reseñas
                    </h3>
                    <div class="space-y-6">
                        <?php foreach (array_reverse($surveys) as $s): ?>
                            <div class="border-b border-gray-100 pb-4 last:border-0">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="font-bold text-gray-800 text-sm"><?php echo $s['customer']; ?></p>
                                    <span class="text-yellow-500 text-xs"><?php echo str_repeat('★', $s['rating']); ?></span>
                                </div>
                                <p class="text-xs text-gray-500 italic">"<?php echo $s['comment']; ?>"</p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>
        </div>

        <!-- Orders Management -->
        <section class="mt-12 bg-white rounded-[3rem] shadow-2xl p-10 border border-gray-100">
            <h3 class="text-2xl font-bold text-gray-800 mb-8 flex items-center">
                <span class="mr-3">🛍️</span> Gestión de Pedidos Activos
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                            <th class="pb-4 px-4">ID / Fecha</th>
                            <th class="pb-4 px-4">Cliente</th>
                            <th class="pb-4 px-4">Detalle / Ingredientes</th>
                            <th class="pb-4 px-4">Total</th>
                            <th class="pb-4 px-4">Estado Actual</th>
                            <th class="pb-4 px-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php foreach (array_reverse($orders) as $o): ?>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-6 px-4">
                                    <p class="font-bold text-gray-800 text-sm">#<?php echo $o['id']; ?></p>
                                    <p class="text-[10px] text-gray-400"><?php echo $o['created_at']; ?></p>
                                </td>
                                <td class="py-6 px-4">
                                    <p class="font-bold text-gray-700 text-sm"><?php echo $o['customer_name']; ?></p>
                                    <p class="text-xs text-gray-400"><?php echo $o['customer_email']; ?></p>
                                </td>
                                <td class="py-6 px-4">
                                    <?php foreach ($o['items'] as $item): ?>
                                        <div class="mb-2">
                                            <p class="text-xs font-bold text-gray-700"><?php echo $item['quantity']; ?>x <?php echo $item['name']; ?></p>
                                            <p class="text-[9px] text-gray-400 italic">Ingredientes: <?php echo implode(', ', $item['ingredients']); ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </td>
                                <td class="py-6 px-4">
                                    <span class="font-bold text-[#1a4731]">$<?php echo number_format($o['total'], 2); ?></span>
                                </td>
                                <td class="py-6 px-4">
                                    <span class="px-4 py-1 rounded-full text-[10px] font-bold uppercase bg-green-50 text-[#1a4731] border border-green-100">
                                        <?php echo $o['status']; ?>
                                    </span>
                                </td>
                                <td class="py-6 px-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="index.php?action=update_order_status&id=<?php echo $o['id']; ?>&status=En Preparación" class="p-2 bg-gray-100 hover:bg-yellow-100 rounded-lg text-xs" title="En Preparación">🍳</a>
                                        <a href="index.php?action=update_order_status&id=<?php echo $o['id']; ?>&status=Listo para Servir" class="p-2 bg-gray-100 hover:bg-green-100 rounded-lg text-xs" title="Listo">✅</a>
                                        <a href="index.php?action=update_order_status&id=<?php echo $o['id']; ?>&status=Entregado" class="p-2 bg-gray-100 hover:bg-blue-100 rounded-lg text-xs" title="Entregado">📦</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Modal ADD -->
    <div id="modal_add" class="hidden fixed inset-0 bg-black/70 backdrop-blur-md z-[100] flex items-center justify-center p-6">
        <div class="bg-white rounded-[3rem] p-12 max-w-md w-full shadow-2xl relative">
            <button onclick="document.getElementById('modal_add').classList.add('hidden')" class="absolute top-6 right-6 text-gray-300 hover:text-red-500">✕</button>
            <h3 class="text-3xl font-bold mb-8 uppercase tracking-tighter">Nuevo Plato</h3>
            <form action="index.php?action=add_dish" method="POST" class="space-y-5">
                <input type="text" name="name" required placeholder="Nombre del plato" class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-[#1a4731] outline-none">
                <textarea name="description" required placeholder="Descripción gourmet" class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-[#1a4731] outline-none h-32"></textarea>
                <input type="number" step="0.01" name="price" required placeholder="Precio (ej. 15.50)" class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-[#1a4731] outline-none">
                <input type="text" name="image" placeholder="URL de la imagen" class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-[#1a4731] outline-none">
                <button type="submit" class="w-full bg-[#1a4731] text-white py-5 rounded-2xl font-bold text-xl shadow-xl hover:bg-black transition-all">Guardar Plato</button>
            </form>
        </div>
    </div>

    <!-- Modal EDIT -->
    <div id="modal_edit" class="hidden fixed inset-0 bg-black/70 backdrop-blur-md z-[100] flex items-center justify-center p-6">
        <div class="bg-white rounded-[3rem] p-12 max-w-md w-full shadow-2xl relative">
            <button onclick="document.getElementById('modal_edit').classList.add('hidden')" class="absolute top-6 right-6 text-gray-300 hover:text-red-500">✕</button>
            <h3 class="text-3xl font-bold mb-8 uppercase tracking-tighter text-blue-600">Editar Plato</h3>
            <form action="index.php?action=edit_dish" method="POST" class="space-y-5">
                <input type="hidden" name="id" id="edit_id">
                <input type="text" name="name" id="edit_name" required class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-600 outline-none">
                <textarea name="description" id="edit_description" required class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-600 outline-none h-32"></textarea>
                <input type="number" step="0.01" name="price" id="edit_price" required class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-600 outline-none">
                <input type="text" name="image" id="edit_image" class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-600 outline-none">
                <button type="submit" class="w-full bg-blue-600 text-white py-5 rounded-2xl font-bold text-xl shadow-xl hover:bg-blue-800 transition-all">Actualizar Plato</button>
            </form>
        </div>
    </div>

    <script src="js/main.js"></script>
</body>
</html>
