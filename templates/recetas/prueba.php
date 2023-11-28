{% extends 'base.html.twig' %}
{% block contenido%}
    
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Batidos Protéicos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .recipe-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s;
        }

        .recipe-card:hover {
            transform: scale(1.05);
        }

        .recipe-image {
            height: 200px;
            object-fit: contain;
        }
        img{
            max-width:100%;
            max-height: 100%
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Recetas de Batidos Protéicos</h1>

    <div class="row">
        <!-- Receta 1 -->
        <div class="col-md-4 mb-4">
            <div class="card recipe-card">
                <img src="{{ asset('images/batidoplatano.png') }}" class="card-img-top recipe-image" alt="Batido Protéico 1">
                <div class="card-body">
                    <h5 class="card-title">Batido de Plátano y Proteína de Suero</h5>
                    <p class="card-text">Un batido delicioso y nutritivo con plátano fresco y proteína de suero. Perfecto para después del entrenamiento.</p>
                    
                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#ingredients1" aria-expanded="false" aria-controls="ingredients1">
                        Ver ingredientes
                    </button>

                    <div class="collapse mt-3" id="ingredients1">
                        <p>Ingredientes:</p>
                        <ul>
                            <li>- Plátano fresco</li>
                            <li>- Proteína de suero</li>
                            <li>- 25 gr de Vegan Protein sabor Mango Té Matcha</li>
                            <li>- 2 Huevos</li>
                            <li>- 50 gm de azúcar</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receta 2 -->
        <div class="col-md-4 mb-4">
            <div class="card recipe-card">
                <img src="{{ asset('images/batidochoco.png') }}" class="card-img-top recipe-image" alt="Batido Protéico 2">
                <div class="card-body">
                    <h5 class="card-title">Batido de Chocolate y Almendras</h5>
                    <p class="card-text">Un batido indulgente con proteína de chocolate y almendras. Satisface tus antojos mientras obtienes tu dosis de proteínas.</p>
                    
                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#ingredients2" aria-expanded="false" aria-controls="ingredients2">
                        Ver ingredientes
                    </button>

                    <div class="collapse mt-3" id="ingredients2">
                        <p>Ingredientes:</p>
                        <ul>
                            <li>- Proteína de chocolate</li>
                            <li>- Almendras</li>
                            <li>- 25 gr de Vegan Protein sabor Mango Té Matcha</li>
                            <li>- 2 Huevos</li>
                            <li>- 50 gm de azúcar</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receta 3 -->
        <div class="col-md-4 mb-4">
            <div class="card recipe-card">
                <img src="{{ asset('images/bayas.png') }}" class="card-img-top recipe-image" alt="Batido Protéico 3">
                <div class="card-body">
                    <h5 class="card-title">Batido de Bayas y Yogur</h5>
                    <p class="card-text">Un batido refrescante con bayas mixtas y yogur griego. Ideal para un desayuno rápido y lleno de proteínas de nuestra tienda online.</p>
                    
                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#ingredients3" aria-expanded="false" aria-controls="ingredients3">
                        Ver ingredientes
                    </button>

                    <div class="collapse mt-3" id="ingredients3">
                        <p>Ingredientes:</p>
                        <ul>
                            <li>- Bayas mixtas (fresas, arándanos, frambuesas)</li>
                            <li>- Yogur griego</li>
                            <li>- ½ plátano</li>
                            <li>- 150 g de piña troceada congelada</li>
                            <li>- 60 g de mango troceado congelado</li>
                            <li>- 25 gr de Vegan Protein sabor Mango Té Matcha</li>
                            <li>- 10 ml de zumo de naranja</li>
                         
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
{% endblock%}