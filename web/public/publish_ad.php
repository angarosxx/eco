<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecomercio - Publicar Anuncio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-md border border-gray-100">
        <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-6">Publicar Nuevo Anuncio</h2>

        <div id="alert-banner" class="hidden p-4 mb-6 rounded-lg bg-red-50 border border-red-200 text-sm text-red-600 flex items-center gap-2">
            <span id="alert-message"></span>
        </div>

        <form id="ad-form" enctype="multipart/form-data" class="space-y-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Título del Anuncio</label>
                    <input type="text" name="title" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Categoría</label>
                    <select name="category_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                        <option value="">Seleccione Categoría</option>
                        <option value="1">Vehículos</option>
                        <option value="2">Inmuebles / Propiedades</option>
                        <option value="3">Tecnología / Electrónica</option>
                        <option value="4">Herramientas e Industria</option>
                        <option value="5">Servicios</option>
                    </select>
                </div>
                <div id="vehicle-spec-panel" class="hidden sm:col-span-2 bg-blue-50/50 p-5 rounded-xl border border-blue-100 grid grid-cols-1 gap-4 sm:grid-cols-2">
    <h3 class="sm:col-span-2 text-md font-bold text-blue-900 border-b border-blue-200 pb-2">Especificaciones del Vehículo</h3>
    
    <div>
        <label class="block text-sm font-medium text-gray-700">Marca</label>
        <select id="vehicle_brand" name="vehicle_brand" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm sm:text-sm">
    <option value="">Seleccione Marca</option>
    <option value="1">Toyota</option>
    <option value="2">Hyundai</option>
    <option value="3">Chevrolet</option>
    <option value="4">Nissan</option>
    <option value="5">Suzuki</option>
    <option value="6">Ford</option>
    <option value="7">Mitsubishi</option>
    <option value="8">BMW</option>
    <option value="9">Audi</option>
    <option value="10">Mercedes-Benz</option>
    <option value="11">Volkswagen</option>
    <option value="12">Volvo</option>
    <option value="13">Peugeot</option>
    <option value="14">Citroën</option>
    <option value="15">Kia</option>
    <option value="16">Maxus</option>
    <option value="17">Great Wall</option>
    <option value="18">Mahindra</option>
    <option value="19">Scania (Camiones)</option>
</select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Modelo</label>
        <select id="vehicle_model" name="vehicle_model" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm sm:text-sm" disabled>
            <option value="">Seleccione una marca primero</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Año</label>
        <input type="number" name="vehicle_year" min="1950" max="2027" placeholder="Ej: 2020" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm sm:text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Tipo de Motor / Combustible</label>
        <select name="vehicle_engine" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm sm:text-sm">
            <option value="">Seleccione Combustible</option>
            <option value="bencina">Bencina</option>
            <option value="diesel">Diesel</option>
            <option value="hibrido">Híbrido</option>
            <option value="electrico">Eléctrico</option>
        </select>
    </div>

    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Kilometraje (Km)</label>
        <input type="number" name="vehicle_km" min="0" placeholder="Ej: 45000" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm sm:text-sm">
    </div>
</div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipo de Anuncio</label>
                    <select id="ad_type" name="ad_type" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                        <option value="">Seleccione una opción</option>
                        <option value="vendo">Vendo</option>
                        <option value="compro">Compro</option>
                        <option value="arriendo">Arriendo</option>
                    </select>
                </div>

                <div class="sm:col-span-2 bg-gray-50 p-4 rounded-lg border border-gray-200 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label id="price-label" class="block text-sm font-medium text-gray-700">Precio</label>
                        <input type="number" id="price-input" name="price" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    
                    <div id="price-type-wrapper" class="hidden">
                        <label class="block text-sm font-medium text-gray-700">Condición de Precio</label>
                        <select id="price_type" name="price_type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                            <option value="fixed">CLP Valor Fijo</option>
                            <option value="contact">Contactar por precio</option>
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea name="description" rows="4" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm"></textarea>
                </div>

                <div class="sm:col-span-2 space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Imágenes del Anuncio (Máximo 5)</label>
                    <input type="file" name="images[]" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <input type="file" name="images[]" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <input type="file" name="images[]" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <input type="file" name="images[]" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <input type="file" name="images[]" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="w-full sm:w-auto py-2 px-6 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    Publicar Anuncio
                </button>
            </div>
        </form>
    </div>

    <script>
        // Target Elements
const categorySelect = document.querySelector('select[name="category_id"]');
const vehiclePanel = document.getElementById('vehicle-spec-panel');
const brandSelect = document.getElementById('vehicle_brand');
const modelSelect = document.getElementById('vehicle_model');

// Listen for Category Selections (Category 1 = Vehículos)
categorySelect.addEventListener('change', () => {
    // If user picks Category 1 (Vehículos), open the extra panel
    if (categorySelect.value === "1") {
        vehiclePanel.classList.remove('hidden');
        setVehicleFieldsRequired(true);
    } else {
        vehiclePanel.classList.add('hidden');
        setVehicleFieldsRequired(false);
    }
});

// Set validation helper
const setVehicleFieldsRequired = (isRequired) => {
    vehiclePanel.querySelectorAll('input, select').forEach(field => {
        field.required = isRequired;
    });
};

// Handle Dynamic Model Fetching when Brand is chosen
brandSelect.addEventListener('change', async () => {
    const brandId = brandSelect.value;
    modelSelect.innerHTML = '<option value="">Cargando modelos...</option>';
    modelSelect.disabled = true;

    if (!brandId) {
        modelSelect.innerHTML = '<option value="">Seleccione una marca primero</option>';
        return;
    }

    try {
        const res = await fetch(`/api/vehicles/models.php?brand_id=${brandId}`);
        const models = await res.json();

        modelSelect.innerHTML = '<option value="">Seleccione Modelo</option>';
        models.forEach(model => {
            const opt = document.createElement('option');
            opt.value = model.id;
            opt.textContent = model.name;
            modelSelect.appendChild(opt);
        });
        modelSelect.disabled = false;
    } catch (err) {
        modelSelect.innerHTML = '<option value="">Error al cargar modelos</option>';
    }
});






        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('ad-form');
            const alertBanner = document.getElementById('alert-banner');
            const alertMessage = document.getElementById('alert-message');
            
            const adTypeSelect = document.getElementById('ad_type');
            const priceLabel = document.getElementById('price-label');
            const priceInput = document.getElementById('price-input');
            const priceTypeWrapper = document.getElementById('price-type-wrapper');
            const priceTypeSelect = document.getElementById('price_type');

            // Handle the UI state changes based on Ad Type selection
            adTypeSelect.addEventListener('change', () => {
                const type = adTypeSelect.value;
                
                // Reset defaults
                priceTypeWrapper.classList.add('hidden');
                priceInput.disabled = false;
                priceInput.required = true;
                priceLabel.textContent = "Precio (CLP)";

                if (type === 'vendo') {
                    priceTypeWrapper.classList.remove('hidden');
                    handlePriceTypeCondition();
                } else if (type === 'compro') {
                    priceLabel.textContent = "Precio Máximo Presupuesto (CLP)";
                } else if (type === 'arriendo') {
                    priceLabel.textContent = "Precio Arriendo Mensual (CLP)";
                }
            });

            // Toggle input behavior if user selects "Contact for price"
            const handlePriceTypeCondition = () => {
                if (priceTypeSelect.value === 'contact') {
                    priceInput.value = '';
                    priceInput.disabled = true;
                    priceInput.required = false;
                } else {
                    priceInput.disabled = false;
                    priceInput.required = true;
                }
            };
            priceTypeSelect.addEventListener('change', handlePriceTypeCondition);

            form?.addEventListener('submit', async (e) => {
                e.preventDefault();
                alertBanner.classList.add('hidden');
                
                // Enable temporarily so FormData collects the field value even if disabled
                priceInput.disabled = false; 
                const formData = new FormData(form);

                try {
                    const response = await fetch('/api/ads/create.php', {
                        method: 'POST',
                        body: formData
                    });

                    if (!response.ok) {
                        const errText = await response.text();
                        throw new Error(errText || 'Error en el servidor backend.');
                    }

                    const result = await response.json();
                    if (result.success) {
                        window.location.href = '/dashboard.php';
                    } else {
                        alertMessage.textContent = result.message || 'Error al guardar el anuncio.';
                        alertBanner.classList.remove('hidden');
                    }
                } catch (err) {
                    alertMessage.innerHTML = `<strong>Error de procesamiento:</strong><br><pre class="text-xs mt-1 overflow-x-auto">${err.message}</pre>`;
                    alertBanner.classList.remove('hidden');
                }
            });
        });
    </script>
</body>
</html>