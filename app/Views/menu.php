<?php ob_start(); ?>

<section class="py-20 container mx-auto px-6">
    <div class="text-center mb-16">
        <h2 class="text-6xl font-bold text-gray-800 mb-4 tracking-tighter uppercase">Nuestra Carta</h2>
        <p class="text-gray-400 italic text-lg">Personaliza tu pedido y disfruta de la excelencia.</p>
        <div class="h-1.5 w-24 bg-[#1a4731] mx-auto mt-6 rounded-full"></div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10">
        <?php foreach ($dishes as $dish): ?>
            <div class="bg-white rounded-[3rem] overflow-hidden shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-500 flex flex-col group relative">
                <div class="h-64 overflow-hidden relative">
                    <img src="<?php echo $dish['image']; ?>" alt="<?php echo $dish['name']; ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-all"></div>
                    <div class="absolute top-6 right-6 bg-white/95 backdrop-blur-md px-5 py-1.5 rounded-full font-bold text-[#1a4731] shadow-2xl text-sm">
                        $<?php echo number_format($dish['price'], 2); ?>
                    </div>
                </div>
                <div class="p-8 flex flex-col flex-grow">
                    <h3 class="text-2xl font-bold text-gray-800 mb-3 tracking-tight group-hover:text-[#1a4731] transition-colors"><?php echo $dish['name']; ?></h3>
                    <p class="text-gray-500 text-sm mb-10 flex-grow leading-relaxed italic line-clamp-3"><?php echo $dish['description']; ?></p>
                    
                    <button onclick="openPersonalizeModal(<?php echo htmlspecialchars(json_encode($dish)); ?>)" class="w-full bg-[#1a4731] text-white py-4 rounded-[1.5rem] font-bold shadow-lg hover:bg-black hover:scale-[1.02] transition-all transform active:scale-95">
                        Añadir al Carrito
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<div id="modal_personalize" class="modal-personalize">
    <div class="bg-white rounded-[3.5rem] p-10 md:p-12 max-w-4xl w-full shadow-2xl relative border border-gray-100 overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-2 bg-[#1a4731]"></div>
        <button onclick="closePersonalizeModal()" class="absolute top-8 right-8 text-gray-300 hover:text-red-500 text-3xl transition">✕</button>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div>
                <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl mb-8 group">
                    <img id="modal_img" src="" class="w-full h-64 object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <p id="modal_price" class="absolute bottom-6 left-6 text-3xl font-bold text-white"></p>
                </div>
                <h3 id="modal_name" class="text-4xl font-bold text-gray-800 mb-4 uppercase tracking-tighter"></h3>
                <p id="modal_desc" class="text-gray-400 italic leading-relaxed text-sm"></p>
            </div>

            <form action="index.php?action=add_to_cart" method="POST" class="space-y-8">
                <input type="hidden" name="id" id="modal_id">
                
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 ml-1">Personaliza tus ingredientes:</label>
                    <div id="ingredients_list" class="grid grid-cols-2 gap-3 bg-gray-50 p-6 rounded-[2rem] border border-gray-100 max-h-60 overflow-y-auto">
                    </div>
                </div>

                <div class="flex items-center justify-between gap-6">
                    <div class="flex-grow">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Cantidad:</label>
                        <div class="flex items-center space-x-4 bg-gray-50 p-3 rounded-2xl border border-gray-100">
                            <button type="button" onclick="changeQty(-1)" class="w-10 h-10 bg-white rounded-xl shadow-sm font-bold text-xl hover:bg-[#1a4731] hover:text-white transition">-</button>
                            <input type="number" name="quantity" id="modal_qty" value="1" min="1" max="10" class="bg-transparent border-0 text-center font-bold text-[#1a4731] focus:ring-0 outline-none flex-grow text-xl">
                            <button type="button" onclick="changeQty(1)" class="w-10 h-10 bg-white rounded-xl shadow-sm font-bold text-xl hover:bg-[#1a4731] hover:text-white transition">+</button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#1a4731] text-white py-6 rounded-[2rem] font-bold text-2xl shadow-2xl hover:bg-black hover:scale-[1.02] transition-all transform active:scale-95">
                    Confirmar Pedido
                </button>
            </form>
        </div>
    </div>
</div>

<script src="js/menu.js"></script>

<?php 
$content = ob_get_clean(); 
include __DIR__ . '/layout.php'; 
?>
