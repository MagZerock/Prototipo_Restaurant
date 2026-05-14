<?php ob_start(); ?>

<section class="py-24 bg-gray-50 flex items-center justify-center min-h-[80vh]">
    <div class="container mx-auto px-6 max-w-5xl">
        <div class="bg-white rounded-[3rem] shadow-2xl p-12 border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-3 bg-[#1a4731]"></div>
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
                <h1 class="text-5xl font-bold text-gray-800 tracking-tighter uppercase">Mis Pedidos</h1>
                
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 flex items-center space-x-4">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Filtrar por fecha:</label>
                    <form action="index.php" method="GET" class="flex items-center" id="userOrderDateForm">
                        <input type="hidden" name="action" value="orders">
                        <input type="date" name="date" value="<?php echo htmlspecialchars($_GET['date'] ?? ''); ?>" 
                               class="px-4 py-2 bg-white border border-gray-200 rounded-xl outline-none text-sm font-bold text-gray-700 cursor-pointer"
                               onchange="document.getElementById('userOrderDateForm').submit()">
                        <?php if (isset($_GET['date']) && !empty($_GET['date'])): ?>
                            <a href="index.php?action=orders" class="ml-2 text-xs text-red-400 hover:underline">Limpiar</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            
            <?php if (empty($orders)): ?>
                <div class="text-center py-20">
                    <p class="text-gray-400 italic text-xl mb-10">No se encontraron pedidos para esta fecha.</p>
                    <a href="index.php?action=menu" class="btn-primary">Ir al Menú</a>
                </div>
            <?php else: ?>
                <div class="space-y-10 overflow-y-auto max-h-[700px] pr-4">
                    <?php foreach (array_reverse($orders) as $order): ?>
                        <div class="p-8 bg-gray-50 rounded-[2.5rem] border border-gray-100 hover:border-green-200 transition-all">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pedido #<?php echo $order['id']; ?></p>
                                    <p class="text-sm text-gray-500 font-medium"><?php echo $order['created_at']; ?></p>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="text-2xl font-bold text-[#1a4731]">$<?php echo number_format($order['total'], 2); ?></span>
                                    <span class="bg-white px-6 py-2 rounded-full border border-green-200 text-[#1a4731] font-bold text-xs shadow-sm uppercase tracking-wider">
                                        <?php echo $order['status']; ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php foreach ($order['items'] as $item): ?>
                                    <div class="bg-white p-6 rounded-2xl border border-gray-50 shadow-sm">
                                        <div class="flex justify-between items-start mb-2">
                                            <h4 class="font-bold text-gray-800"><?php echo $item['name']; ?></h4>
                                            <span class="text-xs font-bold text-gray-400">x<?php echo $item['quantity']; ?></span>
                                        </div>
                                        <p class="text-[10px] text-gray-400 italic">
                                            Ingredientes: <?php echo implode(', ', $item['ingredients']); ?>
                                        </p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php 
$content = ob_get_clean(); 
include __DIR__ . '/layout.php'; 
?>
