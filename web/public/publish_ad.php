<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecomercio - Publicar Anuncio</title>
    <link rel="stylesheet" href="[rsms.me](https://rsms.me/inter/inter.css)">
    <script src="[cdn.jsdelivr.net](https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4)"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-md border border-gray-100">
        <h1 class="text-3xl font-extrabold text-gray-900 text-center mb-2">Publicar Nuevo Anuncio</h1>
        <p class="text-sm text-gray-500 text-center mb-8">Completa los datos del aviso y elige dónde quieres publicarlo.</p>

        <div id="alert-banner" class="hidden p-4 mb-6 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
            <span id="alert-message"></span>
        </div>

        <div id="success-banner" class="hidden p-4 mb-6 rounded-lg bg-green-50 border border-green-200 text-sm text-green-700">
            <span id="success-message"></span>
        </div>

        <form id="ad-form" enctype="multipart/form-data" class="space-y-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700">Título del Anuncio</label>
                    <input
                        id="title"
                        type="text"
                        name="title"
                        required
                        maxlength="255"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm"
                        placeholder="Ej: Toyota Corolla 2020 en excelente estado"
                    >
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Categoría</label>
                    <select
                        id="category_id"
                        name="category_id"
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm"
                    >
                        <option value="">Seleccione Categoría</option>
                        <option value="1">Vehículos</option>
                        <option value="2">Inmuebles / Propiedades</option>
                        <option value="3">Tecnología / Electrónica</option>
                        <option value="4">Herramientas e Industria</option>
                        <option value="5">Servicios</option>
                    </select>
                </div>

                <div>
                    <label for="ad_type" class="block text-sm font-medium text-gray-700">Tipo de Anuncio</label>
                    <select
                        id="ad_type"
                        name="ad_type"
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm"
                    >
                        <option value="">Seleccione una opción</option>
                        <option value="vendo">Vendo</option>
                        <option value="compro">Compro</option>
                        <option value="arriendo">Arriendo</option>
                    </select>
                </div>

                <div>
                    <label for="region_id" class="block text-sm font-medium text-gray-700">Región</label>
                    <select
                        id="region_id"
                        name="region_id"
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm"
                    >
                        <option value="">Cargando regiones...</option>
                    </select>
                </div>

                <div>
                    <label for="comuna_id" class="block text-sm font-medium text-gray-700">Comuna</label>
                    <select
                        id="comuna_id"
                        name="comuna_id"
                        required
                        disabled
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm disabled:bg-gray-100"
                    >
                        <option value="">Seleccione una región primero</option>
                    </select>
                </div>

                <div id="vehicle-spec-panel" class="hidden sm:col-span-2 bg-blue-50/50 p-5 rounded-xl border border-blue-100 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <h3 class="sm:col-span-2 text-md font-bold text-blue-900 border-b border-blue-200 pb-2">Especificaciones del Vehículo</h3>

                    <div>
                        <label for="vehicle_brand" class="block text-sm font-medium text-gray-700">Marca</label>
                        <select
                            id="vehicle_brand"
                            name="vehicle_brand"
                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm sm:text-sm"
                        >
                            <option value="">Seleccione Marca</option>
                            <optgroup label="Americanas">
                                <option value="1">Ford</option>
                                <option value="2">Chevrolet</option>
                                <option value="3">Dodge</option>
                                <option value="4">Jeep</option>
                                <option value="5">Tesla</option>
                                <option value="6">GMC</option>
                                <option value="7">Cadillac</option>
                                <option value="8">Lincoln</option>
                            </optgroup>
                            <optgroup label="Japonesas">
                                <option value="9">Toyota</option>
                                <option value="10">Honda</option>
                                <option value="11">Nissan</option>
                                <option value="12">Mazda</option>
                                <option value="13">Mitsubishi</option>
                                <option value="14">Subaru</option>
                                <option value="15">Suzuki</option>
                                <option value="16">Lexus</option>
                                <option value="17">Isuzu</option>
                            </optgroup>
                            <optgroup label="Alemanas">
                                <option value="18">Volkswagen</option>
                                <option value="19">BMW</option>
                                <option value="20">Mercedes-Benz</option>
                                <option value="21">Audi</option>
                                <option value="22">Porsche</option>
                            </optgroup>
                            <optgroup label="Coreanas">
                                <option value="23">Hyundai</option>
                                <option value="24">Kia</option>
                                <option value="25">Genesis</option>
                            </optgroup>
                            <optgroup label="Chinas">
                                <option value="26">BYD</option>
                                <option value="27">Chery</option>
                                <option value="28">MG</option>
                                <option value="29">Haval</option>
                                <option value="30">JAC</option>
                                <option value="31">Geely</option>
                            </optgroup>
                            <optgroup label="Italianas">
                                <option value="32">Fiat</option>
                                <option value="33">Alfa Romeo</option>
                                <option value="34">Ferrari</option>
                                <option value="35">Lamborghini</option>
                            </optgroup>
                            <optgroup label="Otras Europeas">
                                <option value="36">Renault</option>
                                <option value="37">Peugeot</option>
                                <option value="38">Citroën</option>
                                <option value="39">Volvo</option>
                                <option value="40">Land Rover</option>
                                <option value="41">Jaguar</option>
                                <option value="42">Bentley</option>
                                <option value="43">Rolls-Royce</option>
                            </optgroup>
                        </select>
                    </div>

                    <div>
                        <label for="vehicle_model" class="block text-sm font-medium text-gray-700">Modelo</label>
                        <select
                            id="vehicle_model"
                            name="vehicle_model"
                            disabled
                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm sm:text-sm disabled:bg-gray-100"
                        >
                            <option value="">Seleccione una marca primero</option>
                        </select>
                    </div>

                    <div>
                        <label for="vehicle_year" class="block text-sm font-medium text-gray-700">Año</label>
                        <input
                            id="vehicle_year"
                            type="number"
                            name="vehicle_year"
                            min="1950"
                            max="2035"
                            placeholder="Ej: 2020"
                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm sm:text-sm"
                        >
                    </div>

                    <div>
                        <label for="vehicle_engine" class="block text-sm font-medium text-gray-700">Tipo de Motor / Combustible</label>
                        <select
                            id="vehicle_engine"
                            name="vehicle_engine"
                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm sm:text-sm"
                        >
                            <option value="">Seleccione Combustible</option>
                            <option value="bencina">Bencina</option>
                            <option value="diesel">Diesel</option>
                            <option value="hibrido">Híbrido</option>
                            <option value="electrico">Eléctrico</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="vehicle_km" class="block text-sm font-medium text-gray-700">Kilometraje (Km)</label>
                        <input
                            id="vehicle_km"
                            type="number"
                            name="vehicle_km"
                            min="0"
                            placeholder="Ej: 45000"
                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm sm:text-sm"
                        >
                    </div>
                </div>

                <div class="sm:col-span-2 bg-gray-50 p-4 rounded-lg border border-gray-200 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label id="price-label" for="price" class="block text-sm font-medium text-gray-700">Precio (CLP)</label>
                        <input
                            id="price"
                            type="number"
                            name="price"
                            min="0"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm"
                        >
                    </div>

                    <div id="price-type-wrapper" class="hidden">
                        <label for="price_type" class="block text-sm font-medium text-gray-700">Condición de Precio</label>
                        <select
                            id="price_type"
                            name="price_type"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm"
                        >
                            <option value="fixed">CLP Valor Fijo</option>
                            <option value="contact">Contactar por precio</option>
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm"
                        placeholder="Describe el producto o servicio con todos los detalles importantes."
                    ></textarea>
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
                <button
                    id="submit-button"
                    type="submit"
                    class="w-full sm:w-auto py-2 px-6 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors disabled:opacity-70 disabled:cursor-not-allowed"
                >
                    Publicar Anuncio
                </button>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('ad-form');
        const alertBanner = document.getElementById('alert-banner');
        const alertMessage = document.getElementById('alert-message');
        const successBanner = document.getElementById('success-banner');
        const successMessage = document.getElementById('success-message');
        const submitButton = document.getElementById('submit-button');

        const categorySelect = document.getElementById('category_id');
        const regionSelect = document.getElementById('region_id');
        const comunaSelect = document.getElementById('comuna_id');

        const vehiclePanel = document.getElementById('vehicle-spec-panel');
        const brandSelect = document.getElementById('vehicle_brand');
        const modelSelect = document.getElementById('vehicle_model');

        const adTypeSelect = document.getElementById('ad_type');
        const priceLabel = document.getElementById('price-label');
        const priceInput = document.getElementById('price');
        const priceTypeWrapper = document.getElementById('price-type-wrapper');
        const priceTypeSelect = document.getElementById('price_type');

        const hideMessages = () => {
            alertBanner.classList.add('hidden');
            successBanner.classList.add('hidden');
            alertMessage.textContent = '';
            successMessage.textContent = '';
        };

        const showError = (message) => {
            successBanner.classList.add('hidden');
            successMessage.textContent = '';
            alertMessage.textContent = message;
            alertBanner.classList.remove('hidden');
        };

        const showSuccess = (message) => {
            alertBanner.classList.add('hidden');
            alertMessage.textContent = '';
            successMessage.textContent = message;
            successBanner.classList.remove('hidden');
        };

        const setVehicleFieldsRequired = (isRequired) => {
            vehiclePanel.querySelectorAll('input, select').forEach((field) => {
                if (field.name === 'vehicle_brand' || field.name === 'vehicle_model' || field.name === 'vehicle_year') {
                    field.required = isRequired;
                }
            });
        };

        const resetVehicleFields = () => {
            brandSelect.value = '';
            modelSelect.innerHTML = '<option value="">Seleccione una marca primero</option>';
            modelSelect.disabled = true;
            document.getElementById('vehicle_year').value = '';
            document.getElementById('vehicle_engine').value = '';
            document.getElementById('vehicle_km').value = '';
        };

        const handleCategoryChange = () => {
            if (categorySelect.value === '1') {
                vehiclePanel.classList.remove('hidden');
                setVehicleFieldsRequired(true);
            } else {
                vehiclePanel.classList.add('hidden');
                setVehicleFieldsRequired(false);
                resetVehicleFields();
            }
        };

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

        const handleAdTypeChange = () => {
            const type = adTypeSelect.value;

            priceTypeWrapper.classList.add('hidden');
            priceInput.disabled = false;
            priceInput.required = true;
            priceLabel.textContent = 'Precio (CLP)';

            if (type === 'vendo') {
                priceTypeWrapper.classList.remove('hidden');
                handlePriceTypeCondition();
            } else if (type === 'compro') {
                priceLabel.textContent = 'Precio Máximo Presupuesto (CLP)';
            } else if (type === 'arriendo') {
                priceLabel.textContent = 'Precio Arriendo Mensual (CLP)';
            }
        };

        const loadRegions = async () => {
            regionSelect.innerHTML = '<option value="">Cargando regiones...</option>';

            try {
                const response = await fetch('/api/locations.php?type=regions', {
                    credentials: 'include'
                });

                if (!response.ok) {
                    throw new Error('No se pudieron cargar las regiones.');
                }

                const regions = await response.json();

                regionSelect.innerHTML = '<option value="">Seleccione Región</option>';

                regions.forEach((region) => {
                    const option = document.createElement('option');
                    option.value = region.id;
                    option.textContent = region.name;
                    regionSelect.appendChild(option);
                });
            } catch (error) {
                regionSelect.innerHTML = '<option value="">Error al cargar regiones</option>';
                showError(error.message || 'No se pudieron cargar las regiones.');
            }
        };

        const loadComunas = async (regionId) => {
            comunaSelect.innerHTML = '<option value="">Cargando comunas...</option>';
            comunaSelect.disabled = true;

            try {
                const response = await fetch(`/api/locations.php?type=comunas&region_id=${encodeURIComponent(regionId)}`, {
                    credentials: 'include'
                });

                if (!response.ok) {
                    throw new Error('No se pudieron cargar las comunas.');
                }

                const comunas = await response.json();

                comunaSelect.innerHTML = '<option value="">Seleccione Comuna</option>';

                comunas.forEach((comuna) => {
                    const option = document.createElement('option');
                    option.value = comuna.id;
                    option.textContent = comuna.name;
                    comunaSelect.appendChild(option);
                });

                comunaSelect.disabled = false;
            } catch (error) {
                comunaSelect.innerHTML = '<option value="">Error al cargar comunas</option>';
                showError(error.message || 'No se pudieron cargar las comunas.');
            }
        };

        const loadVehicleModels = async (brandId) => {
            modelSelect.innerHTML = '<option value="">Cargando modelos...</option>';
            modelSelect.disabled = true;

            try {
                const response = await fetch(`/api/vehicles/models.php?marca_id=${encodeURIComponent(brandId)}`, {
                    credentials: 'include'
                });

                if (!response.ok) {
                    throw new Error('No se pudieron cargar los modelos.');
                }

                const models = await response.json();

                modelSelect.innerHTML = '<option value="">Seleccione Modelo</option>';

                models.forEach((model) => {
                    const option = document.createElement('option');
                    option.value = model.id;
                    option.textContent = model.nombre;
                    modelSelect.appendChild(option);
                });

                modelSelect.disabled = false;
            } catch (error) {
                modelSelect.innerHTML = '<option value="">Error al cargar modelos</option>';
                showError(error.message || 'No se pudieron cargar los modelos.');
            }
        };

        categorySelect.addEventListener('change', handleCategoryChange);

        adTypeSelect.addEventListener('change', handleAdTypeChange);
        priceTypeSelect.addEventListener('change', handlePriceTypeCondition);

        regionSelect.addEventListener('change', async () => {
            hideMessages();

            const regionId = regionSelect.value;

            if (!regionId) {
                comunaSelect.innerHTML = '<option value="">Seleccione una región primero</option>';
                comunaSelect.disabled = true;
                return;
            }

            await loadComunas(regionId);
        });

        brandSelect.addEventListener('change', async () => {
            hideMessages();

            const brandId = brandSelect.value;

            if (!brandId) {
                modelSelect.innerHTML = '<option value="">Seleccione una marca primero</option>';
                modelSelect.disabled = true;
                return;
            }

            await loadVehicleModels(brandId);
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            hideMessages();

            submitButton.disabled = true;
            submitButton.textContent = 'Publicando...';

            priceInput.disabled = false;
            const formData = new FormData(form);
            handlePriceTypeCondition();

            try {
                const response = await fetch('/api/ads/create.php', {
                    method: 'POST',
                    credentials: 'include',
                    body: formData
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    throw new Error(result.message || 'Error al guardar el anuncio.');
                }

                showSuccess('Anuncio publicado correctamente. Redirigiendo...');
                setTimeout(() => {
                    window.location.href = '/dashboard.php';
                }, 1200);
            } catch (error) {
                showError(error.message || 'Error de procesamiento.');
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Publicar Anuncio';
            }
        });

        handleCategoryChange();
        handleAdTypeChange();
        loadRegions();
    });
    </script>
</body>
</html>
