<div class="searchbar-wrapper">
    <form action="/advanced_search.php" method="GET" class="searchbar-form">
        
        <div class="search-input-group">
            <label for="search-query" class="search-label">¿Qué estás buscando?</label>
            <input type="text" id="search-query" name="q" placeholder="Ej. Toyota, zapatillas, etc..." class="search-input">
        </div>

        <div class="search-input-group">
            <label for="search-location" class="search-label">Ubicación</label>
            <select id="search-location" name="comuna" class="search-select">
                <option value="">Toda la región</option>
                <option value="antofagasta">Antofagasta</option>
                <option value="calama">Calama</option>
                <option value="san-pedro">San Pedro de Atacama</option>
                <option value="mejillones">Mejillones</option>
            </select>
        </div>

        <div class="search-input-group">
            <label for="search-price" class="search-label">Precio Máximo</label>
            <select id="search-price" name="precio_max" class="search-select">
                <option value="">Cualquier precio</option>
                <option value="50000">$50.000</option>
                <option value="150000">$150.000</option>
                <option value="5000000">$5.000.000</option>
                <option value="15000000">$15.000.000</option>
            </select>
        </div>

        <div class="search-button-group">
            <button type="submit" class="search-btn-submit">
                Buscar
            </button>
        </div>

    </form>
</div>