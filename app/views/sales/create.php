<?php include 'app/views/layouts/header.php'; ?>

<div class="row g-4 h-100">
    <!-- Left Column: Scanner and List -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0 fw-bold text-secondary"><i class="bi bi-basket me-2"></i>Nueva Venta</h5>
            </div>
            <div class="card-body d-flex flex-column">
                <!-- Search Box -->
                <div class="input-group mb-3 input-group-lg">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-upc-scan text-muted"></i></span>
                    <input type="text" id="search_product" class="form-control bg-light border-start-0" placeholder="Escanear código de barras o buscar producto..." autofocus>
                </div>
                
                <div id="search_results" class="list-group position-absolute w-100 shadow-lg" style="z-index: 1050; display: none; top: 80px;"></div>

                <!-- Product Table -->
                <div class="table-responsive flex-grow-1 border rounded bg-white mt-2" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light sticky-top text-secondary">
                            <tr>
                                <th class="ps-3 py-3">Producto</th>
                                <th width="120" class="text-end">Precio</th>
                                <th width="120" class="text-center">Cant.</th>
                                <th width="120" class="text-end pe-3">Subtotal</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody id="cart_items">
                            <!-- Items agregados por JS -->
                        </tbody>
                    </table>
                </div>
                
                <div id="empty_cart_msg" class="text-center py-5 text-muted">
                    <i class="bi bi-cart-x display-1 opacity-25"></i>
                    <p class="mt-3">El carrito está vacío</p>
                    <small>Escanea un producto para comenzar</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Summary and Actions -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-white">
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <h5 class="fw-bold text-secondary mb-4">Resumen de Venta</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold" id="subtotal_display">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="text-muted">Impuestos</span>
                        <span class="text-muted">$0.00</span>
                    </div>
                    
                    <div class="p-4 rounded-3 text-center mb-4" style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white;">
                        <small class="text-white-50 text-uppercase">Total a Pagar</small>
                        <h1 class="display-4 fw-bold mb-0" id="total_amount">$0.00</h1>
                    </div>
                </div>

                <div class="d-grid gap-3">
                    <button class="btn btn-primary btn-lg py-3 shadow-sm fw-bold" id="btn_process_sale" disabled>
                        <i class="bi bi-check-circle-fill me-2"></i> COBRAR
                    </button>
                    <button class="btn btn-outline-danger border-0" id="btn_cancel_sale">
                        <i class="bi bi-trash me-2"></i> Cancelar Venta
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const CSRF_TOKEN = "<?php echo Csrf::generateToken(); ?>";

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search_product');
    const resultsList = document.getElementById('search_results');
    const cartTable = document.getElementById('cart_items');
    const totalDisplay = document.getElementById('total_amount');
    const subtotalDisplay = document.getElementById('subtotal_display');
    const btnProcess = document.getElementById('btn_process_sale');
    const btnCancel = document.getElementById('btn_cancel_sale');
    const emptyMsg = document.getElementById('empty_cart_msg');

    let cart = [];
    let searchTimeout;

    // Buscar productos
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const term = this.value.trim();
        
        if (term.length < 2) {
            resultsList.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch('index.php?page=sales&action=search_product&term=' + encodeURIComponent(term))
                .then(response => response.json())
                .then(products => {
                    resultsList.innerHTML = '';
                    if (products.length > 0) {
                        products.forEach(p => {
                            const item = document.createElement('button');
                            item.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 border-start-0 border-end-0';
                            item.innerHTML = `
                                <div>
                                    <div class="fw-bold">${p.name}</div>
                                    <small class="text-muted"><i class="bi bi-upc"></i> ${p.code}</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">$${p.price}</span>
                            `;
                            item.onclick = () => {
                                addToCart(p);
                                searchInput.focus();
                            };
                            resultsList.appendChild(item);
                        });
                        resultsList.style.display = 'block';
                        
                        // Si es coincidencia exacta de código (scanner), agregar directo
                        const exactMatch = products.find(p => p.code === term);
                        if (exactMatch && products.length === 1) {
                            addToCart(exactMatch);
                            searchInput.value = '';
                            resultsList.style.display = 'none';
                        }
                    } else {
                        resultsList.innerHTML = '<div class="list-group-item text-muted p-3">No se encontraron productos</div>';
                        resultsList.style.display = 'block';
                    }
                });
        }, 300);
    });

    // Ocultar resultados al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsList.contains(e.target)) {
            resultsList.style.display = 'none';
        }
    });

    function addToCart(product) {
        const existingItem = cart.find(item => item.id === product.id);
        
        if (existingItem) {
            existingItem.quantity++;
        } else {
            cart.push({
                id: product.id,
                name: product.name,
                price: parseFloat(product.price),
                quantity: 1,
                is_bulk: product.is_bulk
            });
        }
        
        updateCartUI();
        resultsList.style.display = 'none';
        searchInput.value = '';
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        updateCartUI();
    }

    function updateQuantity(index, change) {
        cart[index].quantity = parseFloat(cart[index].quantity) + change;
        if (cart[index].quantity <= 0) {
            removeFromCart(index);
        } else {
            // Round to 3 decimal places to avoid float precision issues
            cart[index].quantity = Math.round(cart[index].quantity * 1000) / 1000;
            updateCartUI();
        }
    }

    function setQuantity(index, value) {
        let val = parseFloat(value);
        if (isNaN(val) || val <= 0) {
            val = 1; // Default fallback
        }
        
        // Check if item is not bulk, enforce integer
        if (cart[index].is_bulk != 1) {
            val = Math.round(val);
            if (val < 1) val = 1;
        }

        cart[index].quantity = val;
        updateCartUI();
    }

    function updateCartUI() {
        cartTable.innerHTML = '';
        let total = 0;

        if (cart.length === 0) {
            emptyMsg.style.display = 'block';
            cartTable.parentElement.style.display = 'none';
            btnProcess.disabled = true;
        } else {
            emptyMsg.style.display = 'none';
            cartTable.parentElement.style.display = 'block';
            btnProcess.disabled = false;
        }

        cart.forEach((item, index) => {
            const subtotal = item.price * item.quantity;
            total += subtotal;
            
            const isBulk = item.is_bulk == 1;
            const step = isBulk ? '0.001' : '1';

            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="ps-3">
                    <div class="fw-bold text-dark">${item.name}</div>
                    ${isBulk ? '<span class="badge bg-info text-dark" style="font-size: 0.7em;">Granel</span>' : ''}
                </td>
                <td class="text-end">$${item.price.toFixed(2)}</td>
                <td class="text-center">
                    <div class="input-group input-group-sm" style="width: 140px; margin: 0 auto;">
                        <button class="btn btn-outline-secondary" onclick="updateQuantity(${index}, -1)">-</button>
                        <input type="number" class="form-control text-center px-1" value="${item.quantity}" 
                               step="${step}" min="0.001" onchange="setQuantity(${index}, this.value)">
                        <button class="btn btn-outline-secondary" onclick="updateQuantity(${index}, 1)">+</button>
                    </div>
                </td>
                <td class="text-end fw-bold pe-3">$${subtotal.toFixed(2)}</td>
                <td class="text-center">
                    <button class="btn btn-link text-danger p-0" onclick="removeFromCart(${index})">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </td>
            `;
            // Expose helper functions globally for onclick events
            window.removeFromCart = removeFromCart;
            window.updateQuantity = updateQuantity;
            window.setQuantity = setQuantity;
            
            cartTable.appendChild(row);
        });

        const formattedTotal = '$' + total.toFixed(2);
        totalDisplay.textContent = formattedTotal;
        if(subtotalDisplay) subtotalDisplay.textContent = formattedTotal;
    }

    btnCancel.addEventListener('click', function() {
        if (confirm('¿Estás seguro de cancelar la venta actual?')) {
            cart = [];
            updateCartUI();
            searchInput.focus();
        }
    });

    btnProcess.addEventListener('click', function() {
        if (cart.length === 0) return;

        if (!confirm('¿Procesar venta por ' + totalDisplay.textContent + '?')) return;

        fetch('index.php?page=sales&action=store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                items: cart,
                csrf_token: CSRF_TOKEN
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirigir al ticket
                window.location.href = 'index.php?page=sales&action=ticket&id=' + data.sale_id;
            } else {
                alert('Error al procesar venta: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión');
        });
    });
});
</script>

<?php include 'app/views/layouts/footer.php'; ?>