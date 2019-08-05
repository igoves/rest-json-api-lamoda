<?php

# use App\App;

require_once '../app/app.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>roboStock Lamoda</title>
</head>
<body class="bg-light">
<div class="container">
    <h1 class="mb-4 mt-4">Hello, I`m roboStock Lamoda</h1>
    In our stock <b><?= $total_containers ?></b> containers with max capacity <b><?= $capacity_products ?></b> and total
    unique products <b><?= $unique_products ?></b>
    <button type="button" onclick="toggleGeneratorForm(this);" class="btn btn-sm btn-outline-secondary ml-3">Generator
    </button>
    <div class="card mt-3" id="generator" style="display:none;">
        <div class="card-body">
            <form action="" method="post" class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Qty containers</label>
                        <input type="number" min="1" name="qty_containers" class="form-control"
                               placeholder="Qty containers" value="1000"
                               required/>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Max capacity</label>
                        <input type="number" min="1" name="capacity_container" class="form-control"
                               placeholder="Max capacity container" value="10"
                               required/>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Unique products</label>
                        <input type="number" min="1" name="unique_products" class="form-control"
                               placeholder="Total unique products" value="100"
                               required/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label>before create, collection will clean</label><br/>
                    <button type="submit" name="generator" class="btn btn-primary">Create containers</button>
                    <a href="javascript:;" onclick="toggleGeneratorForm(this);" class="btn btn-outline-secondary ml-3">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <?php
    if (!empty($info)) {
        echo '<div class="alert alert-info mt-3">' . $info . '</div>';
    }
    ?>

    <h3 class="mb-3 mt-3">REST API</h3>
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-sm-6">
                    <p>
                        <strong>GET /containers.json</strong>
                        <a class="float-right btn btn-sm btn-outline-primary" href="/containers.json">get</a>
                    </p>
                    <p>Show collection of all containers</p>
                    <pre><code>curl -X GET -H "Content-type: application/json" http://localhost/containers.json</code></pre>
                </div>
                <div class="col-sm-6">
                    <p>
                        <strong>GET /containers/
                            <:id>.json
                        </strong>
                        <?php if (!empty($random_id)) { ?>
                            <a class="float-right btn btn-sm btn-outline-primary"
                               href="/containers/<?= $random_id ?>.json">get</a>
                        <?php } ?>
                    </p>
                    <p>Show a specific container</p>
                    <pre><code>curl -X GET -H "Content-type: application/json" http://localhost/containers/<:id>.json</code></pre>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-sm-6">
                    <p>
                        <strong>GET /containers/products.json</strong>
                        <a class="float-right btn btn-sm btn-outline-primary" href="/containers/products.json">get</a>
                    </p>
                    <p>Show a container with unique products</p>
                    <pre><code>curl -X GET -H "Content-type: application/json" http://localhost/containers/products.json</code></pre>
                </div>
                <div class="col-sm-6">
                    <p>
                        <strong>POST /containers.json</strong>
                    </p>
                    <p>Create a container (param: num containers and num products and capacity container)</p>
                    <pre><code>curl -X POST -H "Content-type: application/json" http://localhost/containers.json \
          -d '{ "name": "Container name", "products": [{ "id": 12321, "name": "Product 12321"}, { "id": 12322, "name": "Product 12322"}]}'</code></pre>
                </div>
            </div>

        </div>
    </div>
    <br/>
</div>

<script>
    var toggleGeneratorForm = function () {
        var block = document.getElementById('generator');
        if (block.style.display !== 'none') {
            block.style.display = 'none';
        } else {
            block.style.display = 'block';
        }
    };
</script>
</body>
</html>
