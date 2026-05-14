<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario - Biconoir's</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body class="bg-gray-50 flex min-h-screen">

    <aside class="w-80 bg-[#1a4731] text-white flex flex-col sticky top-0 h-screen shadow-2xl">
        <div class="p-10 border-b border-green-800 text-center">
            <h1 class="text-3xl font-bold tracking-tighter">BICONOIR</h1>
            <p class="text-[10px] text-green-400 font-bold uppercase tracking-widest mt-1">Inventory Management</p>
        </div>
        <nav class="flex-grow p-8 space-y-6">
            <a href="index.php?action=admin_dashboard" class="flex items-center space-x-3 text-green-100 hover:text-white transition">
                <span>📊</span> <span class="font-bold">Panel Principal</span>
            </a>
            <a href="index.php?action=admin_reservations" class="flex items-center space-x-3 text-green-100 hover:text-white transition">
                <span>📅</span> <span class="font-bold">Reservas</span>
            </a>
            <a href="index.php?action=inventory" class="flex items-center space-x-3 text-white bg-green-900/50 p-4 rounded-xl transition">
                <span>📦</span> <span class="font-bold">Inventario</span>
            </a>
            <a href="index.php?action=admin_surveys" class="flex items-center space-x-3 text-green-100 hover:text-white transition">
                <span>📝</span> <span class="font-bold">Encuestas</span>
            </a>
        </nav>
        <div class="p-8 border-t border-green-800">
            <a href="index.php?action=home" class="block text-center text-green-300 text-xs font-bold uppercase">Ir a la Web</a>
        </div>
    </aside>

    <main class="flex-grow p-12 overflow-y-auto">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-5xl font-bold text-gray-800 tracking-tighter uppercase">Estado de Suministros</h2>
                <p class="text-gray-400 mt-2 italic">Control de stock e ingreso de nuevos lotes dinámicos.</p>
            </div>
            <button onclick="document.getElementById('modal_supply').classList.remove('hidden')" class="bg-black text-white px-8 py-4 rounded-2xl font-bold shadow-2xl hover:bg-[#1a4731] hover:scale-105 transition-all transform active:scale-95 flex items-center">
                <span class="mr-3 text-2xl">+</span> Cargar Suministros
            </button>
        </div>

        <div class="flex justify-between items-center mb-6 gap-4">
            <input type="text" id="search_inventory" placeholder="🔍 Buscar ingrediente o SKU..." onkeyup="filterTable('search_inventory', 'inventory_table')" class="w-full max-w-md px-6 py-3 bg-white border border-gray-200 rounded-2xl outline-none text-sm focus:ring-2 focus:ring-[#1a4731] shadow-sm">
        </div>

        <div class="bg-white rounded-[3rem] shadow-2xl p-10 border border-gray-100 overflow-y-auto max-h-[600px]">
            <table class="w-full text-left" id="inventory_table">
                <thead>
                    <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                        <th class="pb-4 px-4">Ingrediente / SKU</th>
                        <th class="pb-4 px-4">Stock Disponible</th>
                        <th class="pb-4 px-4">Unidad</th>
                        <th class="pb-4 px-4">Estado</th>
                        <th class="pb-4 px-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($inventory as $item): ?>
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-6 px-4">
                                <p class="font-bold text-gray-800 text-sm"><?php echo $item['name']; ?></p>
                                <p class="text-[10px] text-gray-400 uppercase font-bold"><?php echo $item['sku_code']; ?></p>
                            </td>
                            <td class="py-6 px-4">
                                <span class="text-xl font-bold <?php echo $item['total_stock'] < 5 ? 'text-red-500' : 'text-gray-800'; ?>">
                                    <?php echo number_format($item['total_stock'], 2); ?>
                                </span>
                            </td>
                            <td class="py-6 px-4">
                                <span class="text-xs text-gray-400 font-bold uppercase"><?php echo $item['unit']; ?></span>
                            </td>
                            <td class="py-6 px-4">
                                <span class="px-3 py-1 rounded-full text-[9px] font-bold border <?php echo $item['estadoClass']; ?>">
                                    <?php echo $item['estado']; ?>
                                </span>
                            </td>
                            <td class="py-6 px-4">
                                <div class="flex justify-center space-x-2">
                                    <button onclick="openEditIngredientModal(<?php echo htmlspecialchars(json_encode($item)); ?>)" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition shadow-sm">✏️</button>
                                    <button onclick="confirmDeleteIngredient('<?php echo $item['sku_code']; ?>')" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition shadow-sm">🗑️</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <div id="modal_supply" class="hidden fixed inset-0 bg-black/70 backdrop-blur-md z-[100] flex items-center justify-center p-6">
        <div class="bg-white rounded-[3rem] p-12 max-w-lg w-full shadow-2xl relative">
            <button onclick="document.getElementById('modal_supply').classList.add('hidden')" class="absolute top-6 right-6 text-gray-300 hover:text-red-500">✕</button>
            <h3 class="text-3xl font-bold mb-6 uppercase tracking-tighter">Cargar Insumos</h3>
            <form action="index.php?action=store_supply" method="POST" class="space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2 mb-2 block">Ingrediente</label>
                        <input list="ingredients_list" id="ingredient_input" name="name" required placeholder="Nombre del ingrediente" class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-[#1a4731] outline-none text-sm shadow-inner" oninput="handleIngredientSelection(this)">
                        <datalist id="ingredients_list">
                            <?php foreach ($ingredients as $ing): ?>
                                <option value="<?php echo $ing->name; ?>" data-sku="<?php echo $ing->sku_code; ?>" data-unit="<?php echo $ing->unit_of_measurement; ?>"><?php echo $ing->sku_code; ?></option>
                            <?php endforeach; ?>
                        </datalist>
                        <input type="hidden" name="sku_code" id="sku_code_hidden">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2 mb-2 block">Unidad</label>
                        <select name="unit_of_measurement" id="unit_input" required class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl outline-none text-sm">
                            <option value="kg">Kilogramos (kg)</option>
                            <option value="lt">Litros (lt)</option>
                            <option value="unidad">Unidad</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2 mb-2 block">Cantidad</label>
                        <input type="number" step="0.01" min="0" name="quantity" required class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl outline-none">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2 mb-2 block">Costo Unitario</label>
                        <input type="number" step="0.01" min="0" name="cost_per_unit" required class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl outline-none">
                    </div>
                </div>
                <button type="submit" class="w-full bg-[#1a4731] text-white py-5 rounded-3xl font-bold text-xl shadow-2xl hover:bg-black transition-all transform active:scale-95">Ingresar Lote</button>
            </form>
        </div>
    </div>

    <div id="modal_edit_ingredient" class="hidden fixed inset-0 bg-black/70 backdrop-blur-md z-[100] flex items-center justify-center p-6">
        <div class="bg-white rounded-[3rem] p-12 max-w-lg w-full shadow-2xl relative">
            <button onclick="document.getElementById('modal_edit_ingredient').classList.add('hidden')" class="absolute top-6 right-6 text-gray-300 hover:text-red-500">✕</button>
            <h3 class="text-3xl font-bold mb-8 uppercase tracking-tighter text-blue-600">Editar Ingrediente</h3>
            <form action="index.php?action=edit_ingredient" method="POST" class="space-y-6">
                <input type="hidden" name="sku_code" id="edit_sku">
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2 mb-2 block">Nombre del Ingrediente</label>
                        <input type="text" name="name" id="edit_name" required class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-600 outline-none">
                    </div>
                    
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2 mb-2 block">Unidad de Medida</label>
                        <select name="unit_of_measurement" id="edit_unit" required class="w-full px-6 py-4 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-600 outline-none text-sm">
                            <option value="kg">Kilogramos (kg)</option>
                            <option value="lt">Litros (lt)</option>
                            <option value="unidad">Unidad</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2 mb-2 block">Stock Actual</label>
                        <input type="text" id="display_stock" disabled class="w-full px-6 py-4 bg-gray-100 border-0 rounded-2xl text-gray-400 font-bold outline-none cursor-not-allowed">
                    </div>

                    <div class="col-span-2 bg-blue-50 p-6 rounded-3xl border border-blue-100">
                        <label class="text-[10px] font-bold text-blue-400 uppercase tracking-widest px-2 mb-2 block">Nuevo Stock Total (Ajuste)</label>
                        <input type="number" step="0.01" min="0" name="total_stock" id="edit_total_stock" required class="w-full px-6 py-4 bg-white border-0 rounded-2xl focus:ring-2 focus:ring-blue-600 outline-none shadow-sm">
                        <p class="text-[10px] text-blue-400 mt-2 italic">* El sistema creará un lote de ajuste para alcanzar esta cifra.</p>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-5 rounded-3xl font-bold text-xl shadow-2xl hover:bg-blue-800 transition-all transform active:scale-95">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <script>
        const ingredientsData = <?php echo json_encode($ingredients); ?>;
    </script>
    <script src="js/inventory.js"></script>
    <script src="js/ingredient.js"></script>
    <script src="js/inventorybatch.js"></script>
</body>
</html>
