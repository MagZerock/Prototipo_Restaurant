<?php ob_start(); ?>

<section class="py-24 bg-gray-50 flex items-center justify-center min-h-[80vh]">
    <div class="container mx-auto px-6 max-w-4xl">
        <div class="bg-white rounded-[3rem] shadow-2xl p-12 border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-3 bg-[#1a4731]"></div>
            
            <h1 class="text-5xl font-bold text-gray-800 tracking-tighter uppercase mb-10">Tu Selección Gourmet</h1>
            
            <?php if (empty($cart)): ?>
                <div class="text-center py-20">
                    <div class="text-7xl mb-6">🛒</div>
                    <p class="text-gray-400 italic text-xl mb-10">Tu carrito está vacío. ¿Qué tal algo delicioso para empezar?</p>
                    <a href="index.php?action=menu" class="inline-block bg-[#1a4731] text-white px-12 py-5 rounded-2xl font-bold shadow-xl hover:bg-black transition-all">Ver el Menú</a>
                </div>
            <?php else: ?>
                <div class="space-y-8">
                    <?php foreach ($cart as $id => $item): ?>
                        <div class="flex flex-col md:flex-row justify-between items-center p-6 bg-gray-50 rounded-[2rem] border border-gray-100 group hover:border-green-200 transition-all">
                            <div class="flex items-center space-x-6">
                                <div class="bg-[#1a4731] text-white w-12 h-12 rounded-full flex items-center justify-center font-bold">
                                    <?php echo $item['quantity']; ?>x
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-800"><?php echo $item['name']; ?></h3>
                                    <p class="text-gray-400 font-medium">$<?php echo number_format($item['price'], 2); ?> unidad</p>
                                    <a href="index.php?action=remove_from_cart&id=<?php echo $id; ?>" class="text-xs text-red-400 font-bold hover:underline">Eliminar del pedido</a>
                                </div>
                            </div>
                            <div class="text-right mt-4 md:mt-0">
                                <span class="text-3xl font-bold text-[#1a4731]">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="mt-12 pt-10 border-t-2 border-dashed border-gray-200 flex flex-col md:flex-row justify-between items-center gap-6">
                        <div>
                            <p class="text-gray-400 font-bold uppercase tracking-widest text-xs mb-1">Inversión Total</p>
                            <span class="text-6xl font-bold text-gray-800 tracking-tighter">$<?php 
                                $total = array_reduce($cart, function($carry, $item) {
                                    return $carry + ($item['price'] * $item['quantity']);
                                }, 0);
                                echo number_format($total, 2);
                            ?></span>
                        </div>
                        <a href="index.php?action=checkout" class="w-full md:w-auto bg-[#1a4731] text-white px-16 py-6 rounded-[2rem] font-bold text-2xl shadow-2xl hover:bg-black hover:scale-105 transition-all text-center">
                            Confirmar Pedido
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php 
$content = ob_get_clean(); 
include __DIR__ . '/layout.php'; 
?>
